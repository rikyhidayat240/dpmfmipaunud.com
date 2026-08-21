<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\ChartWidget;

class AspirasiChart extends ChartWidget
{
    protected static ?string $heading = 'Data Aspirasi';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Aspirasi Masuk',
                    'data' => collect(range(1, 12))->map(function ($month) {
                        return \App\Models\Aspirasi::whereYear('created_at', now()->year)
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
