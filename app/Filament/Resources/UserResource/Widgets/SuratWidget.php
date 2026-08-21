<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\Arsip;
use App\Models\Laporan;
use App\Models\Surat;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SuratWidget extends BaseWidget
{
    protected $suratMasukDiff;
    protected $suratKeluarDiff;
    protected $totalLaporanDiff;
    protected $totalArsipDiff;

    public function __construct()
    {
        $suratMasukBulanLalu = Surat::where('type', 'Surat Masuk')->whereMonth('created_at', now()->subMonth()->month)->count();
        $suratMasukBulanIni = Surat::where('type', 'Surat Masuk')->whereMonth('created_at', now()->month)->count();
        $this->suratMasukDiff = $suratMasukBulanIni - $suratMasukBulanLalu;

        $suratKeluarBulanLalu = Surat::where('type', 'Surat Keluar')->whereMonth('created_at', now()->subMonth()->month)->count();
        $suratKeluarBulanIni = Surat::where('type', 'Surat Keluar')->whereMonth('created_at', now()->month)->count();
        $this->suratKeluarDiff = $suratKeluarBulanIni - $suratKeluarBulanLalu;

        $totalLaporanBulanLalu = Laporan::whereMonth('created_at', now()->subMonth()->month)->count();
        $totalLaporanBulanIni = Laporan::whereMonth('created_at', now()->month)->count();
        $this->totalLaporanDiff = $totalLaporanBulanIni - $totalLaporanBulanLalu;

        $totalArsipBulanLalu = Arsip::whereMonth('created_at', now()->subMonth()->month)->count();
        $totalArsipBulanIni = Arsip::whereMonth('created_at', now()->month)->count();
        $this->totalArsipDiff = $totalArsipBulanIni - $totalArsipBulanLalu;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Surat Masuk', function () {
                $surats = Surat::where('type', 'Surat Masuk')->whereYear('created_at', date('Y'))->count();
                return "{$surats} Surat";
            })
                ->description("{$this->suratMasukDiff} dari bulan lalu")
                ->color($this->suratMasukDiff > 0 ? 'success' : 'danger')
                ->descriptionIcon($this->suratMasukDiff > 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down', IconPosition::Before),
            Stat::make('Jumlah Surat Keluar', function () {
                $surats = Surat::where('type', 'Surat Keluar')->whereYear('created_at', date('Y'))->count();
                return "{$surats} Surat";
            })
                ->description("{$this->suratKeluarDiff} dari bulan lalu")
                ->color($this->suratKeluarDiff > 0 ? 'success' : 'danger')
                ->descriptionIcon($this->suratKeluarDiff > 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down', IconPosition::Before),
            Stat::make('Jumlah Laporan Masuk', function () {
                $laporans = Laporan::whereYear('created_at', date('Y'))->count();
                return "{$laporans} Laporan";
            })
                ->description("{$this->totalLaporanDiff} dari bulan lalu")
                ->color($this->totalLaporanDiff > 0 ? 'success' : 'danger')
                ->descriptionIcon($this->totalLaporanDiff > 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down', IconPosition::Before),
            Stat::make('Jumlah Arsip Dokumen', function () {
                $arsips = Arsip::whereYear('created_at', date('Y'))->count();
                return "{$arsips} Arsip";
            })
                ->description("{$this->totalArsipDiff} dari bulan lalu")
                ->color($this->totalArsipDiff > 0 ? 'success' : 'danger')
                ->descriptionIcon($this->totalArsipDiff > 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down', IconPosition::Before),
        ];
    }
}
