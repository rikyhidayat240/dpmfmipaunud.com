<?php

namespace App\Filament\Resources\AspirasiResource\Pages;

use App\Filament\Resources\AspirasiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAspirasi extends CreateRecord
{
    protected static string $resource = AspirasiResource::class;
    protected static ?string $title = 'Buat Aspirasi Baru';
}
