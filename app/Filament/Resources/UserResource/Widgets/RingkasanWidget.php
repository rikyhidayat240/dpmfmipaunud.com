<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\JadwalMonev;
use App\Models\ProgramKerja;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class RingkasanWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Program Kerja Dimonev', function () {
                $userId = Auth::user()->id;
                $count = ProgramKerja::whereHas('timMonev', function ($query) use ($userId) {
                    $query->where('id_user', $userId);
                })->whereYear('created_at', date('Y'))->count();
                return "{$count} Proker";
            }),
            Stat::make('Total Partisipasi Monev', function () {
                $userId = Auth::user()->id;
                $count = JadwalMonev::whereHas('timMonev', function ($query) use ($userId) {
                    $query->where('id_user', $userId)->where('hadir', 1);
                })->whereYear('created_at', date('Y'))->count();
                return "{$count} Kegiatan";
            }),
            Stat::make('Persentase Keaktifan Monev', function () {
                $userId = Auth::user()->id;
                $total = 15;
                $hadir = JadwalMonev::whereHas('timMonev', function ($query) use ($userId) {
                    $query->where('id_user', $userId)->where('hadir', 1);
                })->whereYear('created_at', date('Y'))->count();
                $persentase = $hadir < 15 ? number_format(($hadir / $total) * 100, 2) : 100;
                return "{$persentase}%";
            }),
        ];
    }
}
