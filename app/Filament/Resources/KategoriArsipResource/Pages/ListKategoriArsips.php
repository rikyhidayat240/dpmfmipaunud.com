<?php

namespace App\Filament\Resources\KategoriArsipResource\Pages;

use App\Filament\Resources\KategoriArsipResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKategoriArsips extends ListRecords
{
    protected static string $resource = KategoriArsipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Kategori')
                ->icon('heroicon-m-plus'),
        ];
    }
}
