<?php

namespace App\Filament\Resources\LaporanResource\RelationManagers;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class MentionsRelationManager extends RelationManager
{
    protected static string $relationship = 'mentions';
    protected static ?string $title = 'Sebutan';
    protected static ?string $modelLabel = 'Sebutan';

    public static function getRecordTitle(?Model $record): string
    {
        return "Sebutan " . ($record?->username ?? '');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('users_mentioned')
                    ->required()
                    ->multiple()
                    ->columnSpanFull()
                    ->label('Sebut Fungsionaris')
                    ->options(User::all()->pluck('name', 'id')->toArray()),
                Forms\Components\RichEditor::make('notes')
                    ->required()
                    ->columnSpanFull()
                    ->label('Catatan')
                    ->placeholder('Masukkan Catatan'),
                Forms\Components\Hidden::make('id_user')
                    ->default(Auth::user()->id),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('users_mentioned')
            ->columns([
                Tables\Columns\TextColumn::make('users_mentioned')
                    ->wrap()
                    ->label('Fungsionaris')
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        $userIds = explode(',', $state);
                        $users = User::whereIn('id', $userIds)->get();
                        $usernames = $users->pluck('username')->join(', ');
                        return $usernames ?: '-';
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat pada')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return \Carbon\Carbon::parse($state)->locale('id')->translatedFormat('j F Y, H:i');
                    }),
                Tables\Columns\TextColumn::make('notes')
                    ->html()
                    ->wrap()
                    ->label('Catatan')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Sebutan')
                    ->icon('heroicon-m-plus')
                    ->after(function ($record) {
                        $userIds = collect($record->users_mentioned)
                            ->map(fn($userId) => User::find($userId)->id)->toArray();
                        foreach ($userIds as $userId) {
                            Notification::make()
                                ->info()
                                ->title('Sebutan Baru')
                                ->body('Anda disebut pada laporan: ' . $this->getOwnerRecord()->title)
                                ->actions([
                                    \Filament\Notifications\Actions\Action::make('view')
                                        ->label('Lihat')
                                        ->url(fn () => '/admin/daftar-laporan/' . $this->getOwnerRecord()->id . '/edit?activeRelationManager=1')
                                ])
                                ->sendToDatabase(User::find($userId));
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->recordTitle(fn($record) => 'Sebutan ' . $record->user->username),
                    Tables\Actions\EditAction::make()
                        ->recordTitle(fn($record) => 'Sebutan ' . $record->user->username)
                        ->after(function ($record) {
                            $userIds = collect($record->users_mentioned)
                                ->map(fn($userId) => User::find($userId)->id)->toArray();
                            foreach ($userIds as $userId) {
                                Notification::make()
                                    ->info()
                                    ->title('Sebutan Baru')
                                    ->body('Anda disebut pada laporan: ' . $this->getOwnerRecord()->title)
                                    ->actions([
                                        \Filament\Notifications\Actions\Action::make('view')
                                            ->label('Lihat')
                                            ->url(fn () => '/admin/daftar-laporan/' . $this->getOwnerRecord()->id . '/edit?activeRelationManager=1')
                                    ])
                                    ->sendToDatabase(User::find($userId));
                            }
                        }),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ]);
    }
}
