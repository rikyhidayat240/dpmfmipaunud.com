<?php

namespace App\Filament\Resources\MentionResource\Pages;

use App\Filament\Resources\MentionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMentions extends ManageRecords
{
    protected static string $resource = MentionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
