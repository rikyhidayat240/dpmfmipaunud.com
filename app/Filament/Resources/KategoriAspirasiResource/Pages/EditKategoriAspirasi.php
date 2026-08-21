<?php

namespace App\Filament\Resources\KategoriAspirasiResource\Pages;

use App\Filament\Resources\KategoriAspirasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKategoriAspirasi extends EditRecord
{
    protected static string $resource = KategoriAspirasiResource::class;
    public function getTitle(): string
    {
        return 'Ubah Kategori ' . $this->getRecord()->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
