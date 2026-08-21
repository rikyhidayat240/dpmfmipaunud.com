<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AspirasiResource\Pages;
use App\Filament\Resources\AspirasiResource\RelationManagers;
use App\Models\Aspirasi;
use App\Models\KategoriAspirasi;
use App\Models\ProgramStudi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class AspirasiResource extends Resource
{
    protected static ?string $model = Aspirasi::class;
    protected static ?string $navigationGroup = 'Kelola Aspirasi';
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $label = 'Aspirasi';
    protected static ?string $slug = 'aspirasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_prodi')
                    ->label('Program Studi')
                    ->required()
                    ->options(fn() => ProgramStudi::all()->pluck('name', 'id')),
                Forms\Components\Select::make('id_kategori')
                    ->label('Kategori Aspirasi')
                    ->required()
                    ->options(fn() => KategoriAspirasi::all()->pluck('name', 'id')),
                Forms\Components\RichEditor::make('description')
                    ->label('Deskripsi Aspirasi')
                    ->placeholder('Masukkan Deskripsi Aspirasi')
                    ->required()
                    ->columnSpanFull()
                    ->disableToolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'codeBlock',
                    ]),
                Forms\Components\FileUpload::make('photos')
                    ->multiple()
                    ->disk('public')
                    ->panelLayout('grid')
                    ->directory('dokum-aspirasi')
                    ->downloadable()
                    ->getUploadedFileNameForStorageUsing(
                        fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                            ->prepend(now()->timestamp . '-'),
                    )
                    ->visibility('public')
                    ->columnSpanFull()
                    ->label('Foto Pendukung')
                    ->helperText('Format yang diterima: JPG, JPEG, PNG, WEBP.')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('programStudi.name')
                    ->label('Program Studi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kategori.name')
                    ->label('Kategori Aspirasi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi Aspirasi')
                    ->wrap()
                    ->html()
                    ->lineClamp(2)
                    ->limit(100),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('created_at')
                    ->label('Tahun Dibuat')
                    ->options(
                        fn() => Aspirasi::selectRaw('YEAR(created_at) as year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year', 'year')
                            ->toArray()
                    )
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['value']) && $data['value'] !== null) {
                            return $query->whereYear('created_at', $data['value']);
                        }
                        return $query->whereYear('created_at', date('Y'));
                    }),
                Tables\Filters\SelectFilter::make('programStudi.name')
                    ->label('Program Studi')
                    ->relationship('programStudi', 'name'),
                Tables\Filters\SelectFilter::make('kategori.name')
                    ->label('Kategori Aspirasi')
                    ->relationship('kategori', 'name'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.aspirasi.index')),
                    Tables\Actions\RestoreAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.aspirasi.index')),
                    Tables\Actions\ForceDeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.aspirasi.index')),
                ])->visible(fn() => in_array(Auth::user()->role, ['Admin', 'Inti', 'Komisi 3'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->action(fn(Collection $records) => $records->each->delete())
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\RestoreBulkAction::make()
                        ->requiresConfirmation()
                        ->action(fn(Collection $records) => $records->each->restore())
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->action(fn(Collection $records) => $records->each->forceDelete())
                        ->deselectRecordsAfterCompletion(),
                ])->visible(fn() => in_array(Auth::user()->role, ['Admin', 'Inti', 'Komisi 3'])),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAspirasis::route('/'),
            'create' => Pages\CreateAspirasi::route('/create'),
            'view' => Pages\ViewAspirasi::route('/{record}'),
            'edit' => Pages\EditAspirasi::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
