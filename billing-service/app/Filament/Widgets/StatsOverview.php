<?php

namespace App\Filament\Widgets;

use App\Models\Tagihan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $total        = Tagihan::count();
        $lunas        = Tagihan::where('status', 'lunas')->count();
        $pending      = Tagihan::where('status', 'pending')->count();
        $belumBayar   = Tagihan::where('status', 'belum_bayar')->count();
        $totalNominal = Tagihan::where('status', 'lunas')->sum('nominal');

        return [
            Stat::make('Total Tagihan', $total)
                ->description('Semua tagihan')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Lunas', $lunas)
                ->description('Pembayaran berhasil')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Pending', $pending)
                ->description('Menunggu verifikasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Belum Bayar', $belumBayar)
                ->description('Outstanding')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger'),

            Stat::make(
                'Total Pemasukan',
                'Rp ' . number_format($totalNominal, 0, ',', '.')
            )
                ->description('Dari tagihan lunas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}