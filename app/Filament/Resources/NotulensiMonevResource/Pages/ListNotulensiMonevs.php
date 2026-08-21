<?php

namespace App\Filament\Resources\NotulensiMonevResource\Pages;

use App\Filament\Resources\NotulensiMonevResource;
use App\Models\NotulensiMonev;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListNotulensiMonevs extends ListRecords
{
    protected static string $resource = NotulensiMonevResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-m-plus')
                ->label('Notulensi Monev'),
        ];
    }

    public function getTabs(): array
    {
        $years = NotulensiMonev::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        $tabs = [];

        foreach ($years as $year) {
            $tabs[(string) $year] = Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereYear('created_at', $year))
                ->badge(NotulensiMonev::whereYear('created_at', $year)->count());
        }

        return $tabs;
    }
}
