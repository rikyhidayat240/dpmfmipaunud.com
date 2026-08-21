<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
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

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;
    protected static ?string $navigationGroup = 'Kelola Media Sosial';
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $label = 'Blog Website';
    protected static ?string $slug = 'blog-website';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tab')
                    ->label('Tab Blog')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan Tab Blog'),
                Forms\Components\TextInput::make('title')
                    ->label('Judul Blog')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan Judul Blog'),
                Forms\Components\TextInput::make('subtitle')
                    ->label('Subjudul Blog')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan Subjudul Blog'),
                Forms\Components\Select::make('divisi')
                    ->label('Bagian Blog')
                    ->required()
                    ->options([
                        'Inti' => 'Inti',
                        'Komisi 1' => 'Komisi 1',
                        'Komisi 2' => 'Komisi 2',
                        'Komisi 3' => 'Komisi 3',
                        'Komisi 4' => 'Komisi 4',
                        'Komisi 5' => 'Komisi 5',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->placeholder('Pilih Bagian Blog'),
                Forms\Components\RichEditor::make('description')
                    ->label('Konten Blog')
                    ->required()
                    ->placeholder('Masukkan Konten Blog')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('cover')
                    ->required()
                    ->disk('public')
                    ->directory('blog-website')
                    ->label('Foto Cover Blog')
                    ->downloadable()
                    ->getUploadedFileNameForStorageUsing(
                        fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                            ->prepend(now()->timestamp . '-cover-'),
                    )
                    ->visibility('public')
                    ->columnSpanFull()
                    ->helperText('Format yang diterima: JPG, JPEG, PNG, WEBP.'),
                Forms\Components\FileUpload::make('photos')
                    ->multiple()
                    ->disk('public')
                    ->panelLayout('grid')
                    ->directory('blog-website')
                    ->label('Foto Galeri Blog')
                    ->downloadable()
                    ->getUploadedFileNameForStorageUsing(
                        fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                            ->prepend(now()->timestamp . '-gallery'),
                    )
                    ->visibility('public')
                    ->columnSpanFull()
                    ->helperText('Format yang diterima: JPG, JPEG, PNG, WEBP.'),
                Forms\Components\Hidden::make('id_user')->default(Auth::user()->id),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tab')
                    ->label('Tab Blog')
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Blog')
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('divisi')
                    ->label('Bagian')
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtitle')
                    ->label('Subjudul Blog')
                    ->limit(40)
                    ->html()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Upload')
                    ->sortable()
                    ->date('l, j F Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Dibuat Oleh')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('divisi')
                    ->options([
                        'Inti' => 'Inti',
                        'Komisi 1' => 'Komisi 1',
                        'Komisi 2' => 'Komisi 2',
                        'Komisi 3' => 'Komisi 3',
                        'Komisi 4' => 'Komisi 4',
                        'Komisi 5' => 'Komisi 5',
                        'Lainnya' => 'Lainnya',
                    ]),
                Tables\Filters\Filter::make('created_at')
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
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '=', $date),
                            )
                            ->when(
                                $data['bulan'],
                                fn(Builder $query, $month): Builder => $query->whereMonth('created_at', '=', $month),
                            );
                    }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.blog-website.index')),
                    Tables\Actions\RestoreAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.blog-website.index')),
                    Tables\Actions\ForceDeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.blog-website.index')),
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'view' => Pages\ViewBlog::route('/{record}'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
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