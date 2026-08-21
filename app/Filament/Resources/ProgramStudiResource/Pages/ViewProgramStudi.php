<?php

namespace App\Filament\Resources\ProgramStudiResource\Pages;

use App\Filament\Resources\ProgramStudiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProgramStudi extends ViewRecord
{
    protected static string $resource = ProgramStudiResource::class;
    public function getTitle(): string
    {
        return 'Lihat ' . $this->getRecord()->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
