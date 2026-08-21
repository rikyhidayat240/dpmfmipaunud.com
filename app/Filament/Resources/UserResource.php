<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\ProgramStudi;
use App\Models\User;
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

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $label = 'Daftar Fungsionaris';
    protected static ?string $navigationGroup = 'Kelola Individu';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $slug = 'daftar-fungsionaris';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(['md' => 2, 'xl' => 3])
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\FileUpload::make('photo')
                        ->image()
                        ->required()
                        ->disk('public')
                        ->directory('profile-photos')
                        ->visibility('public')
                        ->openable()
                        ->downloadable()
                        ->label('Foto Profil')
                        ->preserveFilenames()
                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                            return (string) str($file
                            ->getClientOriginalName())
                            ->prepend(now()->timestamp . '-');
                        }),
                ])->columnSpan(1),
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->label('Nama Lengkap')
                        ->placeholder('Masukkan Nama Lengkap'),
                    Forms\Components\TextInput::make('username')
                        ->required()
                        ->maxLength(50)
                        ->label('Nama Pengguna')
                        ->placeholder('Masukkan Nama Pengguna'),
                    Forms\Components\Select::make('role')
                        ->required()
                        ->label('Peran')
                        ->options([
                            'Admin' => 'Admin',
                            'Inti' => 'Inti',
                            'Komisi 1' => 'Komisi 1',
                            'Komisi 2' => 'Komisi 2',
                            'Komisi 3' => 'Komisi 3',
                            'Komisi 4' => 'Komisi 4',
                            'Komisi 5' => 'Komisi 5',
                        ])
                        ->visibleOn(['create', 'edit'])
                        ->visible(fn () => Auth::user()->role === 'Admin'),
                    Forms\Components\Select::make('isPimpinan')
                        ->required()
                        ->label('Status')
                        ->options([
                            true => 'Pimpinan',
                            false => 'Non Pimpinan',
                        ])
                        ->visibleOn(['create', 'edit'])
                        ->visible(fn () => Auth::user()->role === 'Admin'),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Status Aktif')
                        ->default(true)
                        ->helperText('Matikan jika fungsionaris ini sudah demisioner/alumni.')
                        ->visible(fn () => Auth::user()->role === 'Admin'),
                    Forms\Components\TextInput::make('specifiedRole')
                        ->required()
                        ->maxLength(50)
                        ->label('Jabatan')
                        ->placeholder('Masukkan Jabatan Pengguna')
                        ->disabled(fn () => Auth::user()->role !== 'Admin'),
                    Forms\Components\Select::make('id_prodi')
                        ->required()
                        ->label('Program Studi')
                        ->options(fn() => ProgramStudi::all()->pluck('name', 'id')),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Masukkan Email Pengguna'),
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->maxLength(255)
                        ->hiddenOn(['view'])
                        ->placeholder('Masukkan Kata Sandi Pengguna (Kosongkan jika tidak diubah)')
                        ->revealable()
                        ->required(fn (string $context): bool => $context === 'create')
                        ->dehydrated(fn ($state) => filled($state)),
                ])->columnSpan(['md' => 1, 'xl' => 2]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto'),
                Tables\Columns\TextColumn::make('username')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Pengguna'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Lengkap'),
                Tables\Columns\TextColumn::make('email')
                    ->copyable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->searchable()
                    ->sortable()
                    ->label('Peran')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('isPimpinan')
                    ->boolean()
                    ->label('Pimpinan')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('specifiedRole')
                    ->searchable()
                    ->sortable()
                    ->label('Jabatan')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('prodi.name')
                    ->searchable()
                    ->sortable()
                    ->label('Program Studi')
                    ->default('Belum ditambahkan')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('poin_keaktifan')
                    ->label('Poin Keaktifan')
                    ->getStateUsing(function (User $record) {
                        return $record->is_active ? $record->jadwalMonevs()
                            ->wherePivot('hadir', true)
                            ->whereYear('tanggal', '>=', 2026)
                            ->count() : '-';
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->withCount(['jadwalMonevs as poin_keaktifan' => function ($query) {
                            $query->where('jadwal_tim_monevs.hadir', true)
                                  ->whereYear('tanggal', '>=', 2026);
                        }])->orderBy('poin_keaktifan', $direction);
                    })
                    ->badge(function(User $record) {
                        return $record->is_active;
                    })
                    ->color('success')
                    ->icon(fn(User $record) => $record->is_active ? 'heroicon-o-star' : null)
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->visible(fn () => Auth::user()->role === 'Admin'),
            ])
            ->defaultSort('role', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Jabatan')
                    ->options([
                        'Admin' => 'Admin',
                        'Inti' => 'Inti',
                        'Komisi 1' => 'Komisi 1',
                        'Komisi 2' => 'Komisi 2',
                        'Komisi 3' => 'Komisi 3',
                        'Komisi 4' => 'Komisi 4',
                        'Komisi 5' => 'Komisi 5',
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('toggle_active')
                        ->label(fn(User $record) => $record->is_active ? 'Nonaktifkan' : 'Aktifkan')
                        ->icon(fn(User $record) => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->color(fn(User $record) => $record->is_active ? 'danger' : 'success')
                        ->action(function (User $record) {
                            $record->update(['is_active' => !$record->is_active]);
                        })
                        ->requiresConfirmation()
                        ->modalHeading(fn(User $record) => $record->is_active ? 'Nonaktifkan Fungsionaris?' : 'Aktifkan Fungsionaris?')
                        ->modalDescription('Apakah Anda yakin ingin mengubah status fungsionaris ini?'),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.daftar-fungsionaris.index')),
                    Tables\Actions\RestoreAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.daftar-fungsionaris.index')),
                    Tables\Actions\ForceDeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.daftar-fungsionaris.index')),
                ])->visible(fn () => Auth::user()->role === 'Admin'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('toggle_active_bulk')
                        ->label('Ubah Status Aktif/Tidak Aktif')
                        ->icon('heroicon-o-arrow-path')
                        ->action(function (Collection $records) {
                            $records->each(function (User $record) {
                                $record->update(['is_active' => !$record->is_active]);
                            });
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete())
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\RestoreBulkAction::make()
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->restore())
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->forceDelete())
                        ->deselectRecordsAfterCompletion(),
                ])->visible(fn () => Auth::user()->role === 'Admin'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
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
