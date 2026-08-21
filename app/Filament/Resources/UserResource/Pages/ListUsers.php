<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('cetak_pdf')
                ->label('Cetak Poin Keaktifan')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->visible(fn () => Auth::user()->role === 'Admin' || Auth::user()->specifiedRole === 'Kepala Komisi 4')
                ->action(function () {
                    $users = User::where('role', '!=', 'Admin')
                        ->where('is_active', true)
                        ->get()
                        ->map(function ($user) {
                            $user->poin_keaktifan = $user->jadwalMonevs()
                                ->wherePivot('hadir', true)
                                ->whereYear('tanggal', '>=', 2026)
                                ->count();
                            return $user;
                        })
                        ->sortBy(function ($user) {
                            $roleOrder = [
                                'Ketua',
                                'Wakil Ketua',
                                'Wakil Ketua 1',
                                'Wakil Ketua 2',
                                'Sekretaris 1',
                                'Sekretaris 2',
                                'Bendahara 1',
                                'Bendahara 2',
                                'Kepala Komisi 1',
                                'Wakil Kepala Komisi 1',
                                'Anggota Komisi 1',
                                'Kepala Komisi 2',
                                'Wakil Kepala Komisi 2',
                                'Anggota Komisi 2',
                                'Kepala Komisi 3',
                                'Wakil Kepala Komisi 3',
                                'Anggota Komisi 3',
                                'Kepala Komisi 4',
                                'Wakil Kepala Komisi 4',
                                'Anggota Komisi 4',
                                'Kepala Komisi 5',
                                'Wakil Kepala Komisi 5',
                                'Anggota Komisi 5',
                            ];
                            $index = array_search($user->specifiedRole, $roleOrder);
                            $sortIndex = str_pad($index === false ? 999 : $index, 3, '0', STR_PAD_LEFT);
                            return $sortIndex . '-' . strtolower($user->name);
                        })
                        ->values();

                    $pdf = Pdf::loadView('pdf.poin-keaktifan', ['users' => $users]);
                    $fileName = 'Rekap_Poin_Keaktifan_' . now()->format('Y-m-d_H-i-s') . '.pdf';
                    
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, $fileName);
                }),
            Actions\CreateAction::make()
                ->icon('heroicon-m-plus')
                ->label('Fungsionaris')
                ->visible(fn () => Auth::user()->role === 'Admin'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'aktif' => Tab::make('Aktif')
                ->modifyQueryUsing(fn($query) => $query->where('is_active', true)),
            'tidak_aktif' => Tab::make('Tidak Aktif')
                ->modifyQueryUsing(fn($query) => $query->where('is_active', false)),
        ];
    }
}
