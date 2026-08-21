<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Filament\Resources\PostResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
    protected static ?string $title = 'Buat Feed Instagram Baru';

    protected function afterCreate(): void
    {
        $user = User::find($this->record->id_user);
        Notification::make()
            ->success()
            ->title('Penugasan Feeds Instagram')
            ->body("Anda telah ditugaskan untuk memproses feeds {$this->record->title}.")
            ->sendToDatabase($user);
    }
}
