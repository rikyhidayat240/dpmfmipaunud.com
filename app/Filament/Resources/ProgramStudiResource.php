<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramStudiResource\Pages;
use App\Filament\Resources\ProgramStudiResource\RelationManagers;
use App\Models\Dosen;
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

class ProgramStudiResource extends Resource
{
    protected static ?string $model = ProgramStudi::class;
    protected static ?string $navigationGroup = 'Kelola Organisasi';
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $slug = 'program-studi';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(['md' => 2])
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->label('Nama Program Studi')
                    ->placeholder('Masukkan Nama Program Studi'),
                Forms\Components\Select::make('id_kaprodi')
                    ->required()
                    ->options(Dosen::all()
                        ->where('role', 'Kaprodi')
                        ->whereNull('deleted_at')
                        ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->columnSpan(1)
                    ->label('Koordinator Program Studi'),
                Forms\Components\Select::make('id_dospem')
                    ->required()
                    ->options(Dosen::all()
                        ->where('role', 'Dospem')
                        ->whereNull('deleted_at')
                        ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->columnSpan(1)
                    ->label('Dosen Pembina Program Studi'),
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->label('Deskripsi Program Studi')
                    ->disableToolbarButtons([
                        'attachFiles',
                    ])
                    ->columnSpanFull()
                    ->placeholder('Masukkan Deskripsi Program Studi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Program Studi')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kaprodi.name')
                    ->label('Koordinator Program Studi')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('dosenPembina.name')
                    ->label('Dosen Pembina Program Studi')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.program-studi.index')),
                    Tables\Actions\RestoreAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.program-studi.index')),
                    Tables\Actions\ForceDeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.program-studi.index')),
                ])->visible(fn () => Auth::user()->role === 'Admin'),
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
            'index' => Pages\ListProgramStudis::route('/'),
            'create' => Pages\CreateProgramStudi::route('/create'),
            'view' => Pages\ViewProgramStudi::route('/{record}'),
            'edit' => Pages\EditProgramStudi::route('/{record}/edit'),
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
