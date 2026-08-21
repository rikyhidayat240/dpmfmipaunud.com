<?php

namespace App\Filament\Resources\PenilaianMonevResource\Pages;

use App\Filament\Resources\PenilaianMonevResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenilaianMonev extends EditRecord
{
    protected static string $resource = PenilaianMonevResource::class;
    public function getTitle(): string
    {
        return 'Ubah Poin ' . $this->getRecord()->aspek;
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
