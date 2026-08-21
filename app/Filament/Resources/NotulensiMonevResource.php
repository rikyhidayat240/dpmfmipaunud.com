<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\JadwalMonev;
use App\Models\ProgramKerja;
use App\Models\NotulensiMonev;
use App\Models\PenilaianMonev;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\NotulensiMonevResource\Pages;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class NotulensiMonevResource extends Resource
{
    protected static ?string $model = NotulensiMonev::class;
    protected static ?string $navigationGroup = 'Monitoring & Evaluasi';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $label = 'Notulensi Monev';
    protected static ?string $slug = 'notulensi-monev';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Tim Monev')
                        ->columns(['md' => 2])
                        ->completedIcon('heroicon-s-check-circle')
                        ->schema([
                            Forms\Components\Section::make([
                                Forms\Components\Select::make('id_jadwal')
                                    ->hiddenOn(['edit', 'view'])
                                    ->label('Jadwal Kegiatan')
                                    ->required()
                                    ->options(function () {
                                        return JadwalMonev::query()
                                            ->where('notulen', 0)
                                            ->where('tanggal', '<=', now()->format('Y-m-d'))
                                            ->whereNull('deleted_at')
                                            ->whereDoesntHave('notulensi')
                                            ->with('programKerja')
                                            ->whereHas('timMonev', fn($query) => $query
                                            ->where('id_user', Auth::user()->id))
                                            ->get()
                                            ->mapWithKeys(function ($jadwal) {
                                                $label = "{$jadwal->name} {$jadwal->programKerja?->name}";
                                                return [$jadwal->id => $label];
                                            });
                                    })
                                    ->searchable()
                                    ->live()
                                    ->validationMessages([
                                        'required' => 'Pilih jadwal kegiatan yang diawasi.',
                                    ]),
                                Forms\Components\Select::make('id_jadwal')
                                    ->hiddenOn('create')
                                    ->label('Jadwal Kegiatan')
                                    ->required()
                                    ->options(function () {
                                        return JadwalMonev::query()
                                            ->with('programKerja')
                                            ->whereNull('deleted_at')
                                            ->get()
                                            ->mapWithKeys(function ($jadwal) {
                                                $label = "{$jadwal->name} {$jadwal->programKerja?->name}";
                                                return [$jadwal->id => $label];
                                            });
                                    })
                                    ->searchable()
                                    ->live()
                                    ->validationMessages([
                                        'required' => 'Pilih jadwal kegiatan yang diawasi.',
                                    ]),
                            ])->columnSpan(1),
                            Forms\Components\Section::make([
                                Forms\Components\CheckboxList::make('tim_monev')
                                    ->label('Kehadiran Tim Monev')
                                    ->required()
                                    ->default([])
                                    ->live()
                                    ->options(function (callable $get) {
                                        $selectedJadwalId = $get('id_jadwal');
                                        $jadwal = JadwalMonev::find($selectedJadwalId);
                                        $adminId = User::where('specifiedRole', 'Admin')
                                            ->whereNull('deleted_at')
                                            ->value('id');
                                        return $jadwal ? $jadwal->timMonev()
                                            ->where('id', '!=', $adminId)
                                            ->pluck('name', 'id')
                                            ->toArray() : [];
                                    })
                                    ->afterStateUpdated(function (Forms\Get $get, $state) {
                                        $idJadwal = JadwalMonev::where('id', $get('id_jadwal'))->value('id');
                                        foreach ($state as $key => $value) {
                                            DB::table('jadwal_tim_monevs')
                                                ->where('id_jadwal', $idJadwal)
                                                ->where('id_user', intval($value))
                                                ->update(['hadir' => true]);
                                        }
                                        DB::table('jadwal_tim_monevs')
                                            ->where('id_jadwal', $idJadwal)
                                            ->whereNotIn('id_user', $state)
                                            ->update(['hadir' => false]);
                                    })
                                    ->validationMessages([
                                        'required' => 'Pilih tim monev yang hadir dalam kegiatan.',
                                    ])
                                    ->columnSpan(1),
                            ])->columnSpan(1)
                        ]),
                    Forms\Components\Wizard\Step::make('Kegiatan')
                        ->columns(['md' => 2])
                        ->completedIcon('heroicon-s-check-circle')
                        ->schema([
                            Forms\Components\TimePicker::make('start_time')
                                ->required()
                                ->seconds(false)
                                ->label('Waktu Mulai')
                                ->suffix('WITA')
                                ->native(false)
                                ->placeholder('HH:mm')
                                ->columnSpan(['md' => 1]),
                            Forms\Components\TimePicker::make('end_time')
                                ->required()
                                ->seconds(false)
                                ->label('Waktu Selesai')
                                ->suffix('WITA')
                                ->native(false)
                                ->placeholder('HH:mm')
                                ->columnSpan(['md' => 1]),
                            Forms\Components\TextInput::make('kehadiran')
                                ->label('Jumlah Panitia yang Hadir')
                                ->required()
                                ->numeric()
                                ->suffix(' Orang')
                                ->minValue(1)
                                ->columnSpanFull()
                                ->placeholder('Masukkan Jumlah Panitia'),
                            Forms\Components\RichEditor::make('agenda')
                                ->label('Agenda Kegiatan')
                                ->required()
                                ->disableToolbarButtons([
                                    'attachFiles',
                                    'codeBlock',
                                    'blockquote',
                                    'strike',
                                    'link',
                                ])
                                ->columnSpanFull()
                                ->placeholder('Masukkan Agenda Kegiatan'),
                        ]),
                    Forms\Components\Wizard\Step::make('Poin Penilaian')
                        ->columns(1)
                        ->completedIcon('heroicon-s-check-circle')
                        ->schema(function () {
                            $penilaians = PenilaianMonev::all();
                            return $penilaians->map(function ($penilaian) {
                                return Forms\Components\Section::make($penilaian->aspek)
                                    ->description($penilaian->kriteria)
                                    ->collapsible()
                                    ->schema([
                                        Forms\Components\Grid::make(['md' => 5])
                                            ->schema([
                                                Forms\Components\Radio::make("scores.{$penilaian->id}")
                                                    ->label('Skor Penilaian')
                                                    ->required()
                                                    ->inline()
                                                    ->inlineLabel(false)
                                                    ->columnSpan(['md' => 2])
                                                    ->options([
                                                        '1' => '1',
                                                        '2' => '2',
                                                        '3' => '3',
                                                        '4' => '4',
                                                        '5' => '5',
                                                        'Kosong' => 'Kosong',
                                                    ])
                                                    ->default('Kosong'),
                                                Forms\Components\Textarea::make("descriptions.{$penilaian->id}")
                                                    ->required()
                                                    ->rows(3)
                                                    ->columnSpan(['md' => 3])
                                                    ->label('Keterangan')
                                                    ->default('Belum ada penilaian yang ditambahkan.')
                                            ])
                                    ]);
                            })->toArray();
                        }),
                    Forms\Components\Wizard\Step::make('Dokumentasi')
                        ->columns(1)
                        ->completedIcon('heroicon-s-check-circle')
                        ->schema([
                            Forms\Components\FileUpload::make('photos')
                                ->multiple()
                                ->required()
                                ->minFiles(3)
                                ->maxFiles(30)
                                ->disk('public')
                                ->downloadable()
                                ->panelLayout('grid')
                                ->directory('dokumentasi-monev')
                                ->preserveFilenames()
                                ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                    return (string) str($file
                                    ->getClientOriginalName())
                                    ->prepend(now()->format('Y-m-d-H-i-s') . '-');
                                })
                                ->visibility('public')
                                ->columnSpanFull()
                                ->label('Foto Dokumentasi')
                                ->helperText('Format yang diterima: JPG, JPEG, PNG, WEBP.')
                        ])
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('jadwal.name')
                    ->label('Jadwal Kegiatan')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('jadwal.programKerja.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jadwal.tanggal')
                    ->label('Tanggal Kegiatan')
                    ->date('l, j F Y')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kehadiran')
                    ->label('Panitia Hadir')
                    ->suffix(' Orang'),
                Tables\Columns\TextColumn::make('id_user')
                    ->label('Notulen')
                    ->wrap()
                    ->formatStateUsing(function ($state) {
                        $user = User::find($state);
                        return $user->username;
                        // return $user->username . ' (' . $user->specifiedRole . ')';
                    }),
            ])
            ->filters([
                Tables\Filters\Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal_kegiatan')
                            ->native(false)
                            ->displayFormat('j F Y')
                            ->placeholder('Pilih Tanggal Kegiatan'),
                        Forms\Components\Select::make('bulan_kegiatan')
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
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal_kegiatan'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '=', $date),
                            )
                            ->when(
                                $data['bulan_kegiatan'],
                                fn(Builder $query, $month): Builder => $query->whereMonth('tanggal', '=', $month),
                            );
                    }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('Unduh')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->label('Unduh')
                        ->url(fn($record): string => route('notulensi.download', ['id' => $record->id]), shouldOpenInNewTab: true)
                        ->openUrlInNewTab()
                        ->successRedirectUrl(route('filament.admin.resources.notulensi-monev.index')),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.notulensi-monev.index')),
                    Tables\Actions\RestoreAction::make()
                        ->successRedirectUrl(route('filament.admin.resources.notulensi-monev.index')),
                    Tables\Actions\ForceDeleteAction::make()
                        ->after(function (Tables\Actions\ForceDeleteAction $action, mixed $record) {
                            if (!empty($record->id_jadwal)) {
                                $jadwal = JadwalMonev::find($record->id_jadwal);
                                if ($jadwal !== null) {
                                    $jadwal->update(['notulen' => false]);
                                }
                            }
                        }),
                ]),
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
                ])->visible(fn() => in_array(Auth::user()->role, ['Admin', 'Komisi 4'])),
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
            'index' => Pages\ListNotulensiMonevs::route('/'),
            'create' => Pages\CreateNotulensiMonev::route('/create'),
            'view' => Pages\ViewNotulensiMonev::route('/{record}'),
            'edit' => Pages\EditNotulensiMonev::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }

    // Di controller atau form handler
    public function save(NotulensiMonev $notulensiMonev, array $data)
    {
        $scores = [];
        $descriptions = [];
        $timMonevAttendance = [];
        foreach ($data['scores'] as $penilaianId => $score) {
            $scores[$penilaianId] = [
                'score' => $score,
            ];
        }
        foreach ($data['descriptions'] as $penilaianId => $description) {
            $descriptions[$penilaianId] = [
                'description' => $description,
            ];
        }
        foreach ($data['tim_monev'] as $userId => $isPresent) {
            if ($userId != 1) {
                $timMonevAttendance[$userId] = $isPresent;
            }
        }
        $notulensiMonev->update([
            'scores' => $scores,
            'descriptions' => $descriptions,
            'tim_monev' => $timMonevAttendance,
        ]);
    }
}
