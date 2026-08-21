<?php

namespace App\Filament\Resources\KategoriSuratResource\Pages;

use App\Filament\Resources\KategoriSuratResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKategoriSurat extends ViewRecord
{
    protected static string $resource = KategoriSuratResource::class;
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
