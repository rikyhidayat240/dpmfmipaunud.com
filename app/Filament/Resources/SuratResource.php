<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Surat;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\SuratResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SuratResource\RelationManagers;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Filament\Resources\SuratResource\RelationManagers\CommentsRelationManager;
use App\Filament\Resources\SuratResource\RelationManagers\MentionsRelationManager;

class SuratResource extends Resource
{
    protected static ?string $model = Surat::class;
    protected static ?string $navigationGroup = 'Kelola Dokumen';
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $label = 'Daftar Surat';
    protected static ?string $slug = 'daftar-surat';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(['md' => 2])
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->label('Judul Surat')
                        ->placeholder('Masukkan Judul Surat'),
                    Forms\Components\Textarea::make('description')
                        ->columnSpanFull()
                        ->label('Catatan Surat')
                        ->placeholder('Masukkan Catatan Surat'),
                    Forms\Components\FileUpload::make('file')
                        ->required()
                        ->disk('public')
                        ->directory('arsip-surat')
                        ->label('Dokumen Surat')
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
                Forms\Components\Section::make([
                    Forms\Components\Select::make('type')
                        ->required()
                        ->label('Tipe Surat')
                        ->options([
                            'Surat Masuk' => 'Surat Masuk',
                            'Surat Keluar' => 'Surat Keluar',
                        ]),
                    Forms\Components\Select::make('id_kategori')
                        ->required()
                        ->label('Kategori Surat')
                        ->relationship('kategori', 'name'),
                    Forms\Components\Select::make('id_lembaga')
                        ->label('Lembaga Mahasiswa')
                        ->relationship('lembaga', 'username'),
                    Forms\Components\Select::make('id_proker')
                        ->label('Program Kerja')
                        ->reactive()
                        ->afterStateUpdated(function (Forms\Set $set, $state) {
                            $proker = ProgramKerja::find($state);
                            if ($proker) {
                                $set('id_lembaga', $proker->id_lembaga);
                            }
                        })
                        ->relationship('programKerja', 'name'),
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
                    ->label('Judul Dokumen Surat'),
                Tables\Columns\TextColumn::make('type')
                    ->sortable()
                    ->searchable()
                    ->label('Tipe Surat'),
                Tables\Columns\TextColumn::make('kategori.name')
                    ->sortable()
                    ->searchable()
                    ->label('Kategori'),
                Tables\Columns\TextColumn::make('lembaga.username')
                    ->sortable()
                    ->searchable()
                    ->label('Lembaga')
                    ->default('Tidak ditambahkan'),
                Tables\Columns\TextColumn::make('programKerja.name')
                    ->wrap()
                    ->sortable()
                    ->searchable()
                    ->label('Program Kerja')
                    ->default('Tidak ditambahkan'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('created_at')
                    ->label('Tahun Dibuat')
                    ->options(
                        fn() => Surat::selectRaw('YEAR(created_at) as year')
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
                    ->label('Tipe Surat'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.daftar-surat.index')),
                    Tables\Actions\RestoreAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.daftar-surat.index')),
                    Tables\Actions\ForceDeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.daftar-surat.index')),
                ])->visible(fn() => in_array(Auth::user()->role, ['Admin', 'Inti', 'Komisi 2'])),
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
                ])->visible(fn() => in_array(Auth::user()->role, ['Admin', 'Inti', 'Komisi 2'])),
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
            'index' => Pages\ListSurats::route('/'),
            'create' => Pages\CreateSurat::route('/create'),
            'view' => Pages\ViewSurat::route('/{record}'),
            'edit' => Pages\EditSurat::route('/{record}/edit'),
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
