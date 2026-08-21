<?php

namespace App\Filament\Resources\SignatureResource\Pages;

use App\Filament\Resources\SignatureResource;
use App\Models\Signature;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSignature extends CreateRecord
{
    protected static string $resource = SignatureResource::class;
    protected static ?string $title = 'Buat Tanda Tangan Elektronik Baru';

    protected function handleRecordCreation(array $data): Signature
    {
        $signature = Signature::create([
            'nomor' => $data['nomor'],
            'unique_link' => env('APP_URL') . str_replace('.', '-', uniqid('/signature/', true)),
            'file' => $data['file'],
            'keperluan' => $data['keperluan'],
            'id_user' => $data['id_user'],
            'id_lembaga' => $data['id_lembaga'],
        ]);
        return $signature;
    }
}
