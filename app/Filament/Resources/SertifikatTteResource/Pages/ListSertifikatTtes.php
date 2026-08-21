<?php

namespace App\Filament\Resources\SertifikatTteResource\Pages;

use App\Filament\Resources\SertifikatTteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSertifikatTtes extends ListRecords
{
    protected static string $resource = SertifikatTteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat TTE Sertifikat'),
        ];
    }
}
