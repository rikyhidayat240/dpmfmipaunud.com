<?php

namespace App\Filament\Resources\DosenResource\Pages;

use App\Filament\Resources\DosenResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDosen extends ViewRecord
{
    protected static string $resource = DosenResource::class;
    public function getTitle(): string
    {
        return 'Lihat Data ' . $this->getRecord()->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
