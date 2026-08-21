<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LembagaResource\Pages;
use App\Filament\Resources\LembagaResource\RelationManagers;
use App\Models\Lembaga;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class LembagaResource extends Resource
{
    protected static ?string $model = Lembaga::class;
    protected static ?string $navigationGroup = 'Kelola Organisasi';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $label = 'Lembaga Mahasiswa';
    protected static ?string $slug = 'lembaga-mahasiswa';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(['md' => 2])
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nama Lembaga')
                    ->maxLength(255)
                    ->placeholder('Masukkan Nama Lembaga'),
                Forms\Components\TextInput::make('username')
                    ->required()
                    ->label('Akronim Lembaga')
                    ->maxLength(255)
                    ->placeholder('Masukkan Akronim Lembaga'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan Email Lembaga'),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->label('Narahubung')
                    ->maxLength(255)
                    ->placeholder('Masukkan Nomor Narahubung'),
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->label('Deskripsi Lembaga Mahasiswa')
                    ->disableToolbarButtons([
                        'attachFiles',
                    ])
                    ->columnSpanFull()
                    ->placeholder('Masukkan Deskripsi Lembaga'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('username')
                    ->sortable()
                    ->searchable()
                    ->label('Akronim'),
                Tables\Columns\TextColumn::make('name')
                    ->wrap()
                    ->sortable()
                    ->searchable()
                    ->label('Nama Lembaga Mahasiswa'),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->sortable()
                    ->searchable()
                    ->label('Narahubung'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.lembaga-mahasiswa.index')),
                    Tables\Actions\RestoreAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.lembaga-mahasiswa.index')),
                    Tables\Actions\ForceDeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.lembaga-mahasiswa.index')),
                ])->visible(fn () => in_array(Auth::user()->role, ['Admin', 'Inti', 'Komisi 4', 'Komisi 5'])),
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
                ])->visible(fn () => in_array(Auth::user()->role, ['Admin', 'Inti', 'Komisi 4', 'Komisi 5'])),
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
            'index' => Pages\ListLembagas::route('/'),
            'create' => Pages\CreateLembaga::route('/create'),
            'view' => Pages\ViewLembaga::route('/{record}'),
            'edit' => Pages\EditLembaga::route('/{record}/edit'),
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
