<?php

namespace App\Filament\Resources\PenilaianMonevResource\Pages;

use App\Filament\Resources\PenilaianMonevResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPenilaianMonev extends ViewRecord
{
    protected static string $resource = PenilaianMonevResource::class;
    public function getTitle(): string
    {
        return 'Lihat Poin ' . $this->getRecord()->aspek;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
