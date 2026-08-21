<?php

namespace App\Filament\Resources\KategoriArsipResource\Pages;

use App\Filament\Resources\KategoriArsipResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKategoriArsip extends ViewRecord
{
    protected static string $resource = KategoriArsipResource::class;
    public function getTitle(): string
    {
        return 'Lihat Kategori ' . $this->getRecord()->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
