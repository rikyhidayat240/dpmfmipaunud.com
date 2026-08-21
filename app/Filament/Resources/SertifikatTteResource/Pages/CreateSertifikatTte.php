<?php

namespace App\Filament\Resources\SertifikatTteResource\Pages;

use App\Filament\Resources\SertifikatTteResource;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSertifikatTte extends CreateRecord
{
    protected static string $resource = SertifikatTteResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $unique_link = env('APP_URL') . str_replace('.', '-', uniqid('/sertifikat/', true));
        $data['unique_link'] = $unique_link;

        $options = new QROptions([
            'version'              => 7,
            'outputType'           => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'             => QRCode::ECC_H,
            'scale'                => 10,
            'imageTransparent'     => false,
            'quality'              => 100,
            'quietzoneSize'        => 0,
        ]);

        $subName = str_replace('/', '_', $data['nomor'] ?? 'Sertifikat');
        $filename = 'qr-codes/' . uniqid() . '_' . $subName . '.png';
        
        $fullPath = public_path('storage/' . $filename);
        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $qrcode = new QRCode($options);
        
        // Pass the $fullPath directly into render() so it saves the actual binary file, not base64 string
        $qrcode->render($unique_link, $fullPath);
        
        $data['qr_code'] = $filename;

        return $data;
    }
}
