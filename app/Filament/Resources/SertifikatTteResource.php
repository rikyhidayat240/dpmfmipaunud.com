<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SertifikatTteResource\Pages;
use App\Models\SertifikatTte;
use App\Models\Lembaga;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class SertifikatTteResource extends Resource
{
    protected static ?string $model = SertifikatTte::class;
    protected static ?string $navigationGroup = 'Kelola Dokumen';
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $label = 'TTE Sertifikat';
    protected static ?string $slug = 'tte-sertifikat';
    protected static ?int $navigationSort = 4; // Below "Tanda Tangan Elektronik" which is 3

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('nomor')
                        ->required()
                        ->maxLength(255)
                        ->label('Nomor Sertifikat')
                        ->placeholder('Masukkan Nomor Sertifikat')
                        ->columnSpanFull(),
                    Forms\Components\Select::make('id_lembaga')
                        ->required()
                        ->options(function () {
                            return Lembaga::whereNull('deleted_at')
                                ->pluck('username', 'id');
                        })
                        ->columnSpanFull()
                        ->searchable()
                        ->label('Lembaga Pemohon'),
                    Forms\Components\Textarea::make('keperluan')
                        ->label('Keperluan')
                        ->placeholder('Masukkan Keperluan Pembuatan Sertifikat')
                        ->required()
                        ->columnSpanFull(),
                ])->columnSpan(['md' => 1]),
                Forms\Components\Section::make([
                    Forms\Components\Select::make('id_user')
                        ->required()
                        ->options(function () {
                            return User::whereIn('role', ['Admin', 'Inti'])
                                ->where('is_active', true)
                                ->whereNull('deleted_at')
                                ->pluck('name', 'id');
                        })
                        ->default(function () {
                            return optional(User::where('specifiedRole', 'Ketua')
                                ->where('is_active', true)
                                ->whereNull('deleted_at')
                                ->latest()
                                ->first())->id;
                        })
                        ->columnSpanFull()
                        ->searchable()
                        ->label('Penanda Tangan'),
                    Forms\Components\FileUpload::make('file')
                        ->disk('public')
                        ->directory('file-sertifikat')
                        ->label('Berkas Pendukung (Sertifikat)')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                        ->maxSize(5120)
                        ->downloadable()
                        ->visibility('public')
                        ->columnSpanFull()
                        ->helperText('Format yang diterima: PDF, JPG, PNG. Maks: 5MB.'),
                ])->columnSpan(['md' => 1]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor')
                    ->searchable()
                    ->sortable()
                    ->label('Nomor Sertifikat'),
                Tables\Columns\TextColumn::make('lembaga.username')
                    ->searchable()
                    ->sortable()
                    ->label('Pemohon'),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->label('TTE Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('file')
                    ->label('Berkas')
                    ->formatStateUsing(fn ($state) => $state ? 'Terlampir' : 'Kosong')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger'),
                Tables\Columns\ImageColumn::make('qr_code')
                    ->label('QR Code')
                    ->disk('public'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('downloadQR')
                        ->label('Unduh QR Code')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->action(function (SertifikatTte $record) {
                            $path1 = public_path('storage/' . $record->qr_code);
                            $path2 = storage_path('app/public/' . $record->qr_code);
                            $finalPath = file_exists($path1) ? $path1 : (file_exists($path2) ? $path2 : null);

                            if ($finalPath) {
                                $nomor_safe = str_replace(['/', '\\', ':'], '_', $record->nomor);
                                return response()->download($finalPath, $nomor_safe . '.png');
                            }
                            // Using a notification instead of generic response in action
                            \Filament\Notifications\Notification::make()
                                ->title('File tidak ditemukan')
                                ->body('QR Code tidak ditemukan di server.')
                                ->danger()
                                ->send();
                        }),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('downloadFile')
                        ->label('Unduh Berkas')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('info')
                        ->visible(fn (SertifikatTte $record) => $record->file !== null)
                        ->action(function (SertifikatTte $record) {
                            $path1 = public_path('storage/' . $record->file);
                            $path2 = storage_path('app/public/' . $record->file);
                            $finalPath = file_exists($path1) ? $path1 : (file_exists($path2) ? $path2 : null);

                            if ($finalPath) {
                                return response()->download($finalPath, 'Berkas_' . basename($record->file));
                            }
                            \Filament\Notifications\Notification::make()
                                ->title('File tidak ditemukan')
                                ->body('Berkas pendukung tidak ditemukan di server.')
                                ->danger()
                                ->send();
                        }),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSertifikatTtes::route('/'),
            'create' => Pages\CreateSertifikatTte::route('/create'),
            'edit' => Pages\EditSertifikatTte::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                \Illuminate\Database\Eloquent\SoftDeletingScope::class,
            ]);
    }
}
