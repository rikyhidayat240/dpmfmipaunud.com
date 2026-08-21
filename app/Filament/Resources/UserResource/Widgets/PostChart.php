<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\ChartWidget;

class PostChart extends ChartWidget
{
    protected static ?string $heading = 'Data Feeds Instagram';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Feeds Diproses',
                    'data' => collect(range(1, 12))->map(function ($month) {
                        return \App\Models\Post::whereYear('created_at', now()->year)
                            ->where('diproses', 1)
                            ->whereMonth('created_at', $month)
                            ->when($month > now()->month, function ($query) {
                                return $query->where('id', null);
                            })
                            ->count();
                    })->toArray(),
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
