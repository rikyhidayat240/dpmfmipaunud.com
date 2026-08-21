<?php

namespace App\Filament\Resources\ArsipResource\Pages;

use App\Filament\Resources\ArsipResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArsip extends EditRecord
{
    protected static string $resource = ArsipResource::class;
    public function getTitle(): string
    {
        return 'Ubah Arsip ' . "{$this->getRecord()->title}";
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
