<?php

namespace App\Filament\Resources\LaporanResource\Pages;

use Filament\Actions;
use App\Models\Laporan;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LaporanResource;

class ListLaporans extends ListRecords
{
    protected static string $resource = LaporanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Laporan')
                ->icon('heroicon-m-plus'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua'),
            'lpj' => Tab::make('LPJ')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', 'Laporan Pertanggungjawaban')),
            'lpjk' => Tab::make('LPJK')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', 'Laporan Pertanggungjawaban Keuangan')),
            'tor' => Tab::make('TOR')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', 'Term of References (TOR)')),
            'rab' => Tab::make('RAB')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', 'Rancangan Anggaran Biaya')),
            'lain' => Tab::make('Lainnya')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', 'Laporan Lainnya')),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'semua';
    }
}
