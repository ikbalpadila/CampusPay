<?php

namespace App\Filament\Widgets;

use App\Models\Tagihan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PaymentChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Pembayaran 6 Bulan Terakhir';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data   = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month    = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M Y');

            $data[] = Tagihan::where('status', 'lunas')
                ->whereYear('updated_at', $month->year)
                ->whereMonth('updated_at', $month->month)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pembayaran Lunas',
                    'data' => $data,
                    'backgroundColor' => '#1E4D8C',
                    'borderColor' => '#1E4D8C',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}