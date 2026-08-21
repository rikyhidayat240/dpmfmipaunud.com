<?php

namespace App\Filament\Resources\PenilaianMonevResource\Pages;

use App\Filament\Resources\PenilaianMonevResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePenilaianMonev extends CreateRecord
{
    protected static string $resource = PenilaianMonevResource::class;
    protected static ?string $title = 'Buat Poin Penilaian Baru';
}
