<?php

namespace App\Filament\Resources\KategoriAspirasiResource\Pages;

use App\Filament\Resources\KategoriAspirasiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKategoriAspirasi extends CreateRecord
{
    protected static string $resource = KategoriAspirasiResource::class;
    protected static ?string $title = 'Buat Kategori Aspirasi Baru';
}
