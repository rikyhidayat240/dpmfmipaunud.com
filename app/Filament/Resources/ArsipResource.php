<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Arsip;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\ArsipResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ArsipResource\RelationManagers;
use App\Filament\Resources\ArsipResource\RelationManagers\CommentsRelationManager;
use App\Filament\Resources\ArsipResource\RelationManagers\MentionsRelationManager;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ArsipResource extends Resource
{
    protected static ?string $model = Arsip::class;
    protected static ?string $navigationGroup = 'Kelola Dokumen';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'Arsip Dokumen';
    protected static ?string $slug = 'arsip-dokumen';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        // ->maxLength(255)
                        ->label('Judul Arsip Dokumen')
                        ->placeholder('Masukkan Judul Dokumen'),
                    Forms\Components\Select::make('id_kategori')
                        ->required()
                        ->label('Kategori Arsip')
                        ->relationship('kategori', 'name'),
                    Forms\Components\Textarea::make('description')
                        //->required()
                        ->columnSpanFull()
                        ->label('Catatan Arsip')
                        ->placeholder('Masukkan Catatan Arsip'),
                    Forms\Components\Hidden::make('id_user')
                        ->default(Auth::user()->id),
                ])->columnSpan(['md' => 1]),
                Forms\Components\Section::make([
                    Forms\Components\TextArea::make('link')
                        // ->url()
                        ->label('Link Dokumen')
                        // ->prefixIcon('heroicon-o-link')
                        ->placeholder('Masukkan Link Dokumen')
                        ->rows(4),
                    Forms\Components\FileUpload::make('file')
                        ->disk('public')
                        ->directory('arsip-dokumen')
                        ->label('Arsip Dokumen')
                        ->downloadable()
                        ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                        ->getUploadedFileNameForStorageUsing(
                            function (TemporaryUploadedFile $file) {
                                return $file->getClientOriginalName();
                            },
                        )
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            $extensions = ['.pdf', '.docx'];
                            $filename = $state->getClientOriginalName();
                            foreach ($extensions as $extension) {
                                if (str_ends_with($filename, $extension)) {
                                    $filename = str_replace($extension, '', $filename);
                                }
                            }
                            $set('title', $filename);
                        })
                        ->visibility('public')
                        ->columnSpanFull()
                        ->helperText('Format yang diterima: PDF, DOCX.'),
                ])->columnSpan(['md' => 1]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->wrap()
                    ->sortable()
                    ->searchable()
                    ->label('Judul Arsip Dokumen'),
                Tables\Columns\TextColumn::make('kategori.name')
                    ->sortable()
                    ->searchable()
                    ->label('Kategori Arsip'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengunggah')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('created_at')
                    ->label('Tahun Dibuat')
                    ->options(
                        fn() => Arsip::selectRaw('YEAR(created_at) as year')
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
                Tables\Filters\SelectFilter::make('kategori.name')
                    ->relationship('kategori', 'name')
                    ->label('Tipe Arsip'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.arsip-dokumen.index')),
                    Tables\Actions\RestoreAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.arsip-dokumen.index')),
                    Tables\Actions\ForceDeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.arsip-dokumen.index')),
                ]),
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
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::class,
            MentionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArsips::route('/'),
            'create' => Pages\CreateArsip::route('/create'),
            'view' => Pages\ViewArsip::route('/{record}'),
            'edit' => Pages\EditArsip::route('/{record}/edit'),
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
