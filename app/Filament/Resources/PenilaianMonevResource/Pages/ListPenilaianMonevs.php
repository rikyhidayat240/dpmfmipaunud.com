<?php

namespace App\Filament\Resources\PenilaianMonevResource\Pages;

use App\Filament\Resources\PenilaianMonevResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenilaianMonevs extends ListRecords
{
    protected static string $resource = PenilaianMonevResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-m-plus')
                ->label('Poin Penilaian'),
        ];
    }
}
