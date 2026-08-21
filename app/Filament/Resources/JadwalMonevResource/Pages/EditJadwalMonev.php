<?php

namespace App\Filament\Resources\JadwalMonevResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\JadwalMonevResource;

class EditJadwalMonev extends EditRecord
{
    protected static string $resource = JadwalMonevResource::class;
    public function getTitle(): string
    {
        return 'Ubah Data ' . "{$this->getRecord()->name} {$this->getRecord()->programKerja->name}";
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
