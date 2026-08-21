<?php

namespace App\Filament\Resources\ArsipResource\Pages;

use Filament\Actions;
use App\Filament\Resources\ArsipResource;
use Filament\Resources\Pages\ListRecords;

class ListArsips extends ListRecords
{
    protected static string $resource = ArsipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-m-plus')
                ->label('Arsip'),
        ];
    }
}
