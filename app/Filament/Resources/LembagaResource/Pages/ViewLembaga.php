<?php

namespace App\Filament\Resources\LembagaResource\Pages;

use App\Filament\Resources\LembagaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLembaga extends ViewRecord
{
    protected static string $resource = LembagaResource::class;
    public function getTitle(): string
    {
        return 'Lihat Lembaga ' . $this->getRecord()->username;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
