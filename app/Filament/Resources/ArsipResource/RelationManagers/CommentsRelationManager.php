<?php

namespace App\Filament\Resources\ArsipResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';
    protected static ?string $title = 'Komentar';
    protected static ?string $modelLabel = 'Komentar';

    public static function getRecordTitle(?Model $record): string
    {
        return "Komentar " . ($record?->username ?? '');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\RichEditor::make('comment')
                    ->required()
                    ->columnSpanFull()
                    ->label('Komentar')
                    ->placeholder('Masukkan Komentar'),
                Forms\Components\Hidden::make('id_user')
                    ->default(Auth::user()->id),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('comment')
            ->columns([
                Tables\Columns\Layout\Grid::make()
                    ->columns(1)
                    ->schema([
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('user.username')
                                ->label('Fungsionaris')
                                ->weight(FontWeight::Bold)
                                ->size(TextColumn\TextColumnSize::Medium)
                                ->icon('heroicon-s-user-circle')
                                ->searchable(),
                            Tables\Columns\TextColumn::make('comment')
                                ->label('Komentar')
                                ->searchable()
                                ->html()
                                ->wrap()
                                ->lineClamp(5),
                        ])->space(2),
                    ]),
            ])
            ->contentGrid([
                'md' => 2,
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Komentar')
                    ->icon('heroicon-m-plus'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Selengkapnya')
                    ->recordTitle(fn($record) => 'Komentar ' . $record->user->username),
                Tables\Actions\EditAction::make()
                    ->recordTitle(fn($record) => 'Komentar ' . $record->user->username),
                Tables\Actions\DeleteAction::make()
                    ->recordTitle(fn($record) => 'Komentar ' . $record->user->username),
            ]);
    }
}
