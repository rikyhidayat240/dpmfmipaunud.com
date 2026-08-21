<?php

namespace App\Filament\Resources\JadwalMonevResource\Pages;

use App\Filament\Resources\JadwalMonevResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewJadwalMonev extends ViewRecord
{
    protected static string $resource = JadwalMonevResource::class;
    public function getTitle(): string
    {
        return 'Lihat Data ' . "{$this->getRecord()->name} {$this->getRecord()->programKerja->name}";
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
