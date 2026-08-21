<?php

namespace App\Filament\Resources\JadwalMonevResource\Pages;

use Filament\Actions;
use App\Models\JadwalMonev;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\JadwalMonevResource;

class ListJadwalMonevs extends ListRecords
{
    protected static string $resource = JadwalMonevResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-m-plus')
                ->label('Jadwal Monev'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua'),
            'mendatang' => Tab::make('Mendatang')
                ->modifyQueryUsing(function (Builder $query) {
                    $user = Auth::user();
                    if (!in_array($user->role, ['Admin', 'Komisi 4'])) {
                        return $query->whereHas('timMonev', function ($query) {
                            $query->where('id_user', Auth::user()->id);
                        })
                        ->where('notulen', false)->orderBy('tanggal', 'asc');
                    } else {
                        return $query->where('notulen', false)->orderBy('tanggal', 'asc');
                    }
                }),
            'selesai' => Tab::make('Selesai')
                ->modifyQueryUsing(function (Builder $query) {
                    $user = Auth::user();
                    if (!in_array($user->role, ['Admin', 'Komisi 4'])) {
                        return $query->whereHas('timMonev', function ($query) {
                            $query->where('id_user', Auth::user()->id);
                        })
                        ->where('notulen', true)->orderBy('tanggal', 'desc');
                    } else {
                        return $query->where('notulen', true)->orderBy('tanggal', 'desc');
                    }
                }),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'mendatang';
    }
}
