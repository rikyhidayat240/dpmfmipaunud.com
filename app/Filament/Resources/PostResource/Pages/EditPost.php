<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Filament\Resources\PostResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;
    protected $userBefore;
    public function getTitle(): string
    {
        return 'Ubah Feed ' . $this->getRecord()->title;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function beforeValidate(): void
    {
        $this->userBefore = $this->record->id_user;
    }

    protected function afterSave(): void
    {
        if ($this->userBefore != $this->record->id_user) {
            $user = User::find($this->record->id_user);
            $oldUser = User::find($this->userBefore);
            Notification::make()
                ->success()
                ->title('Penugasan Feeds Instagram')
                ->body("Anda telah ditugaskan untuk memproses feeds {$this->record->title}.")
                ->sendToDatabase($user);
            Notification::make()
                ->danger()
                ->title('Pengalihan Tugas Feeds Instagram')
                ->body("Tugas Anda untuk memproses feeds {$this->record->title} telah dialihkan.")
                ->sendToDatabase($oldUser);
        }
    }
}
