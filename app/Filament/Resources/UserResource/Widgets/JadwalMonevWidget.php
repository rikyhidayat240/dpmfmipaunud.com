<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\JadwalMonev;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\TableWidget as BaseWidget;

class JadwalMonevWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Jadwal Monev Mendatang';
    public function table(Table $table): Table
    {
        return $table
            ->query(JadwalMonev::query()
            ->whereYear('created_at', date('Y'))
            ->whereHas('timMonev', function ($query) {
                $query->where('id_user', Auth::user()->id)->where('tanggal', '>=', now()->toDateString());
            }))
            ->defaultSort('tanggal', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Kegiatan'),
                Tables\Columns\TextColumn::make('programKerja.name')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->label('Nama Program Kerja'),
                Tables\Columns\TextColumn::make('jumlah_tim_monev')
                    ->numeric()
                    ->label('Tim Monev')
                    ->suffix(' Orang'),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable()
                    ->datetime('l, j M Y'),
                Tables\Columns\TextColumn::make('start_time')
                    ->time('H:i')
                    ->label('Mulai'),
                Tables\Columns\TextColumn::make('end_time')
                    ->time('H:i')
                    ->label('Selesai'),
            ]);
    }
}
