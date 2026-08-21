<?php

namespace App\Filament\Resources\KategoriArsipResource\Pages;

use App\Filament\Resources\KategoriArsipResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKategoriArsip extends CreateRecord
{
    protected static string $resource = KategoriArsipResource::class;
    protected static ?string $title = 'Buat Kategori Arsip Baru';
}
