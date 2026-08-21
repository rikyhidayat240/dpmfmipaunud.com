<?php

namespace App\Filament\Resources\JadwalMonevResource\Pages;

use App\Filament\Resources\JadwalMonevResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJadwalMonev extends CreateRecord
{
    protected static string $resource = JadwalMonevResource::class;
    protected static ?string $title = 'Buat Jadwal Monev Baru';
}
