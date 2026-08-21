<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\ProgramKerja;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\TableWidget as BaseWidget;

class ProgramKerjaWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Program Kerja yang Anda Monev';
    public function table(Table $table): Table
    {
        return $table
            ->query(ProgramKerja::query()
            ->whereYear('created_at', date('Y'))
            ->whereHas('timMonev', function ($query) {
                $query->where('id_user', Auth::user()->id);
            }))
            ->defaultSort('created_at', 'desc')
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
            ]);
    }
}
