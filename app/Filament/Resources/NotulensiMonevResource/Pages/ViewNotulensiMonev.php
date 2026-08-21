<?php

namespace App\Filament\Resources\NotulensiMonevResource\Pages;

use App\Filament\Resources\NotulensiMonevResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNotulensiMonev extends ViewRecord
{
    protected static string $resource = NotulensiMonevResource::class;
    public function getTitle(): string
    {
        return 'Notulensi ' . "{$this->getRecord()->jadwal->name} {$this->getRecord()->jadwal->programKerja->name}";
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
