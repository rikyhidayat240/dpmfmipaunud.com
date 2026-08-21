<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramKerjaResource\Pages;
use App\Filament\Resources\ProgramKerjaResource\RelationManagers;
use App\Filament\Resources\ProgramKerjaResource\RelationManagers\TimMonevRelationManager;
use App\Models\Lembaga;
use App\Models\ProgramKerja;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ProgramKerjaResource extends Resource
{
    protected static ?string $model = ProgramKerja::class;
    protected static ?string $navigationGroup = 'Monitoring & Evaluasi';
    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';
    protected static ?string $label = 'Program Kerja';
    protected static ?string $slug = 'program-kerja';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(['md' => 2])
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Program Kerja')
                    ->placeholder('Masukkan Nama Program Kerja'),
                Forms\Components\Select::make('id_lembaga')
                    ->required()
                    ->options(Lembaga::whereNull('deleted_at')
                        ->pluck('username', 'id')
                    )
                    ->searchable()
                    ->label('Lembaga Mahasiswa'),
                Forms\Components\TextInput::make('jumlah_panitia')
                    ->required()
                    ->numeric()
                    ->suffix('Orang')
                    ->label('Jumlah Panitia')
                    ->placeholder('Masukkan Jumlah Panitia'),
                Forms\Components\TextInput::make('jumlah_tim_monev')
                    ->required()
                    ->numeric()
                    ->live()
                    ->suffix('Orang')
                    ->label('Jumlah Tim Monev')
                    ->placeholder('Masukkan Jumlah Tim Monev'),
                Forms\Components\Select::make('timMonev')
                    ->relationship('timMonev', 'username', fn (Builder $query) => $query->where('is_active', true))
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->maxItems(fn (Forms\Get $get) => (int) $get('jumlah_tim_monev'))
                    ->rules([
                        fn (Forms\Get $get) => function (string $attribute, $value, \Closure $fail) use ($get) {
                            $expected = (int) $get('jumlah_tim_monev');
                            if (is_array($value) && count($value) !== $expected) {
                                $fail("Jumlah anggota yang dipilih harus persis {$expected} orang.");
                            }
                        },
                    ])
                    ->label('Anggota Tim Monev'),
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->label('Deskripsi Program Kerja')
                    ->disableToolbarButtons([
                        'attachFiles',
                    ])
                    ->columnSpanFull()
                    ->placeholder('Masukkan Deskripsi Program Kerja'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->wrap()
                    ->label('Nama Program Kerja')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah_panitia')
                    ->numeric()
                    ->label('Panitia')
                    ->suffix(' Orang'),
                Tables\Columns\TextColumn::make('jumlah_tim_monev')
                    ->numeric()
                    ->label('Tim Monev')
                    ->suffix(' Orang'),
                Tables\Columns\TextColumn::make('lembaga.username')
                    ->label('Lembaga Mahasiswa')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi Program Kerja')
                    ->limit(40)
                    ->html(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('created_at')
                    ->label('Tahun Dibuat')
                    ->options(
                        fn() => ProgramKerja::selectRaw('YEAR(created_at) as year')
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
                Tables\Filters\SelectFilter::make('id_lembaga')
                    ->label('Lembaga Mahasiswa')
                    ->relationship('lembaga', 'username'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.program-kerja.index')),
                    Tables\Actions\RestoreAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.program-kerja.index')),
                    Tables\Actions\ForceDeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.program-kerja.index')),
                ])->visible(fn () => in_array(Auth::user()->role, ['Admin', 'Inti', 'Komisi 4'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
                ])->visible(fn () => in_array(Auth::user()->role, ['Admin', 'Inti', 'Komisi 4'])),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TimMonevRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProgramKerjas::route('/'),
            'create' => Pages\CreateProgramKerja::route('/create'),
            'view' => Pages\ViewProgramKerja::route('/{record}'),
            'edit' => Pages\EditProgramKerja::route('/{record}/edit'),
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
