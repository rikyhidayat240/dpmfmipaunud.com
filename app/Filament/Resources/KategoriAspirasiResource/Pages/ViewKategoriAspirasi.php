<?php

namespace App\Filament\Resources\KategoriAspirasiResource\Pages;

use App\Filament\Resources\KategoriAspirasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKategoriAspirasi extends ViewRecord
{
    protected static string $resource = KategoriAspirasiResource::class;
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
