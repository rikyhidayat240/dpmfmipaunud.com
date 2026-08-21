<?php

namespace App\Filament\Resources\ProgramKerjaResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProgramKerjaResource;

class EditProgramKerja extends EditRecord
{
    protected static string $resource = ProgramKerjaResource::class;
    protected $users;
    public function getTitle(): string
    {
        return 'Ubah Data ' . $this->getRecord()->name;
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
