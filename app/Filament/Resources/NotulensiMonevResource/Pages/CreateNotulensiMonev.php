<?php

namespace App\Filament\Resources\NotulensiMonevResource\Pages;

use App\Filament\Resources\NotulensiMonevResource;
use App\Models\NotulensiMonev;
use App\Models\JadwalMonev;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateNotulensiMonev extends CreateRecord
{
    protected static string $resource = NotulensiMonevResource::class; 
    protected static ?string $title = 'Buat Notulensi Monev Baru';

    protected function handleRecordCreation(array $data): NotulensiMonev
    {
        $notulensi = NotulensiMonev::create([
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'kehadiran' => $data['kehadiran'],
            'tim_monev' => $data['tim_monev'],
            'agenda' => $data['agenda'],
            'scores' => $data['scores'],
            'descriptions' => $data['descriptions'],
            'photos' => $data['photos'],
            'id_jadwal' => $data['id_jadwal'],
            'id_user' => Auth::user()->id,
        ]);
        JadwalMonev::query()
            ->where('id', $data['id_jadwal'])
            ->update(['notulen' => 1]);
        return $notulensi;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Notulensi berhasil dibuat';
    }

    protected function onValidationError(ValidationException $exception): void
    {
        Notification::make()
            ->title('Notulensi gagal dibuat')
            ->danger()
            ->body('Terjadi kesalahan saat menyimpan data.')
            ->send();
        parent::onValidationError($exception);
    }
}
