<?php

namespace App\Filament\Resources\ArsipResource\Pages;

use App\Filament\Resources\ArsipResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewArsip extends ViewRecord
{
    protected static string $resource = ArsipResource::class;
    public function getTitle(): string
    {
        return 'Lihat Arsip ' . "{$this->getRecord()->title}";
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
