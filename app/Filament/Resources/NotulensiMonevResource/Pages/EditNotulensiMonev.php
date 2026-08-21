<?php

namespace App\Filament\Resources\NotulensiMonevResource\Pages;

use App\Filament\Resources\NotulensiMonevResource;
use App\Models\NotulensiMonev;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNotulensiMonev extends EditRecord
{
    protected static string $resource = NotulensiMonevResource::class;
    public function getTitle(): string
    {
        return 'Ubah Notulensi ' . "{$this->getRecord()->jadwal->name} {$this->getRecord()->jadwal->programKerja->name}";
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
