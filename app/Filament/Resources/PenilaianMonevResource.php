<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenilaianMonevResource\Pages;
use App\Filament\Resources\PenilaianMonevResource\RelationManagers;
use App\Models\PenilaianMonev;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Collection;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PenilaianMonevResource extends Resource
{
    protected static ?string $model = PenilaianMonev::class;
    protected static ?string $navigationGroup = 'Monitoring & Evaluasi';
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $label = 'Poin Penilaian';
    protected static ?string $slug = 'poin-penilaian';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('aspek')
                    ->label('Aspek Penilaian')
                    ->placeholder('Masukkan Aspek Penilaian')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('kriteria')
                    ->label('Kriteria Penilaian')
                    ->placeholder('Masukkan Kriteria Penilaian')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->disableToolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'codeBlock',
                        'link',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('aspek')
                    ->label('Aspek Penilaian')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kriteria')
                    ->label('Kriteria Penilaian')
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
                        ->successRedirectUrl(route('filament.admin.resources.poin-penilaian.index')),
                    Tables\Actions\RestoreAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.poin-penilaian.index')),
                    Tables\Actions\ForceDeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.poin-penilaian.index')),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenilaianMonevs::route('/'),
            'create' => Pages\CreatePenilaianMonev::route('/create'),
            'view' => Pages\ViewPenilaianMonev::route('/{record}'),
            'edit' => Pages\EditPenilaianMonev::route('/{record}/edit'),
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
