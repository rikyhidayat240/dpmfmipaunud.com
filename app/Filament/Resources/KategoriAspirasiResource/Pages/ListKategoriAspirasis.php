<?php

namespace App\Filament\Resources\KategoriAspirasiResource\Pages;

use App\Filament\Resources\KategoriAspirasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKategoriAspirasis extends ListRecords
{
    protected static string $resource = KategoriAspirasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-m-plus')
                ->label('Kategori'),
        ];
    }
}
