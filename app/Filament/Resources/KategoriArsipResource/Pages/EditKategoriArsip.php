<?php

namespace App\Filament\Resources\KategoriArsipResource\Pages;

use App\Filament\Resources\KategoriArsipResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKategoriArsip extends EditRecord
{
    protected static string $resource = KategoriArsipResource::class;
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
