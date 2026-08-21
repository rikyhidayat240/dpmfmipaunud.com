<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\Post;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Actions\Action;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\PostResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\RelationManagers;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationGroup = 'Kelola Media Sosial';
    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $label = 'Feeds Instagram';
    protected static ?string $slug = 'feeds-instagram';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->label('Judul Feed')
                    ->placeholder('Masukkan Judul Feed')
                    ->maxLength(255),
                Forms\Components\Select::make('category')
                    ->required()
                    ->label('Kategori Feed')
                    ->options([
                        'Program Kerja' => 'Program Kerja',
                        'Hari Raya' => 'Hari Raya',
                        'Hotline' => 'Hotline',
                        'Opening Bulan' => 'Opening Bulan',
                        'Tim Monev' => 'Tim Monev',
                        'Repost' => 'Repost',
                        'Ucapan' => 'Ucapan',
                        'Laporan' => 'Laporan',
                        'Lainnya' => 'Lainnya',
                    ]),
                Forms\Components\DatePicker::make('tanggal_upload')
                    ->label('Tanggal Upload')
                    ->required()
                    ->locale('id')
                    ->displayFormat('j F Y')
                    ->prefixIcon('heroicon-o-calendar')
                    ->placeholder('Masukkan Tanggal Upload')
                    ->native(false),
                Forms\Components\Select::make('id_user')
                    ->required()
                    ->label('Penanggung Jawab')
                    ->options(fn() => User::where('role', 'Komisi 5')->pluck('name', 'id')),
                Forms\Components\RichEditor::make('caption')
                    ->required()
                    ->columnSpanFull()
                    ->label('Deskripsi Feed')
                    ->placeholder('Masukkan Deskripsi Feed')
                    ->disableToolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'codeBlock',
                    ]),
                Forms\Components\TextInput::make('link_drive')
                    ->url()
                    ->columnSpanFull()
                    ->prefixIcon('heroicon-o-link')
                    ->label('Link Google Drive')
                    ->placeholder('Masukkan Link Google Drive'),
                Forms\Components\FileUpload::make('photos')
                    ->multiple()
                    ->disk('public')
                    ->panelLayout('grid')
                    ->directory('feed-instagram')
                    ->label('Foto Postingan')
                    ->downloadable()
                    ->getUploadedFileNameForStorageUsing(
                        fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                            ->prepend(now()->timestamp . '-'),
                    )
                    ->visibility('public')
                    ->columnSpanFull()
                    ->helperText('Format yang diterima: JPG, JPEG, PNG, WEBP.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Feed')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->sortable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_upload')
                    ->label('Tanggal Upload')
                    ->sortable()
                    ->date('l, j F Y'),
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Penanggung Jawab')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('caption')
                    ->label('Deskripsi Feed')
                    ->limit(40)
                    ->html()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\CheckboxColumn::make('diproses')
                    ->label('Diproses')
                    ->disabled(fn($record) => now()->lt(Carbon::parse($record->tanggal_upload)))
                    ->afterStateUpdated(function ($record, $state) {
                        $record->update(['diproses' => $state]);
                        if ($state) {
                            Notification::make()
                                ->title('Feed telah diproses')
                                ->success()
                                ->duration(5000)
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Feed batal diproses')
                                ->danger()
                                ->duration(5000)
                                ->send();
                        }
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('created_at')
                    ->label('Tahun Dibuat')
                    ->options(
                        fn() => Post::selectRaw('YEAR(created_at) as year')
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
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori Feed')
                    ->options([
                        'Program Kerja' => 'Program Kerja',
                        'Hari Raya' => 'Hari Raya',
                        'Hotline' => 'Hotline',
                        'Opening Bulan' => 'Opening Bulan',
                        'Tim Monev' => 'Tim Monev',
                        'Repost' => 'Repost',
                        'Ucapan' => 'Ucapan',
                        'Laporan' => 'Laporan',
                        'Lainnya' => 'Lainnya',
                    ]),
                Tables\Filters\Filter::make('tanggal_upload')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal')
                            ->reactive()
                            ->native(false)
                            ->displayFormat('j F Y')
                            ->placeholder('Pilih Tanggal Upload')
                            ->afterStateUpdated(function (Forms\Set $set) {
                                $set('bulan', null);
                            }),
                        Forms\Components\Select::make('bulan')
                            ->options([
                                '01' => 'Januari',
                                '02' => 'Februari',
                                '03' => 'Maret',
                                '04' => 'April',
                                '05' => 'Mei',
                                '06' => 'Juni',
                                '07' => 'Juli',
                                '08' => 'Agustus',
                                '09' => 'September',
                                '10' => 'Oktober',
                                '11' => 'November',
                                '12' => 'Desember',
                            ])
                            ->reactive()
                            ->afterStateUpdated(function (Forms\Set $set) {
                                $set('tanggal', null);
                            }),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal_upload', '=', $date),
                            )
                            ->when(
                                $data['bulan'],
                                fn(Builder $query, $month): Builder => $query->whereMonth('tanggal_upload', '=', $month),
                            );
                    }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.feeds-instagram.index')),
                    Tables\Actions\RestoreAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.feeds-instagram.index')),
                    Tables\Actions\ForceDeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.feeds-instagram.index')),
                ])->visible(fn() => in_array(Auth::user()->role, ['Admin', 'Inti', 'Komisi 5'])),
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
                ])->visible(fn() => in_array(Auth::user()->role, ['Admin', 'Inti', 'Komisi 5'])),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
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
