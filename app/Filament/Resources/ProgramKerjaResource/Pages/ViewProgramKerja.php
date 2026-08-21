<?php

namespace App\Filament\Resources\ProgramKerjaResource\Pages;

use App\Filament\Resources\ProgramKerjaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProgramKerja extends ViewRecord
{
    protected static string $resource = ProgramKerjaResource::class;
    public function getTitle(): string
    {
        return 'Lihat Data ' . $this->getRecord()->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
