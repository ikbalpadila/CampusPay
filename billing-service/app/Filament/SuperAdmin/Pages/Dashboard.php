<?php

namespace App\Filament\SuperAdmin\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Http;

class Dashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Dashboard Super Admin';

    protected static ?int $navigationSort = 1;


    public int $totalTagihan = 0;

    public int $totalLunas = 0;

    public int $totalPending = 0;

    public int $totalUsers = 0;

    public int $lamportClock = 0;



    /**
     * View halaman dashboard
     */
    public function getView(): string
    {
        return 'filament.superadmin.pages.dashboard';
    }



    /**
     * Load data dashboard
     */
    public function mount(): void
    {
        $this->loadDashboardData();
    }



    /**
     * Ambil data statistik
     */
    private function loadDashboardData(): void
    {

        // Total tagihan
        $this->totalTagihan = \App\Models\Tagihan::count();


        // Tagihan lunas
        $this->totalLunas =
            \App\Models\Tagihan::where(
                'status',
                'lunas'
            )->count();



        // Tagihan pending
        $this->totalPending =
            \App\Models\Tagihan::where(
                'status',
                'pending'
            )->count();



        // Total user
        $this->totalUsers =
            \App\Models\User::count();



        // Ambil Lamport Clock dari Transaction Service
        try {

            $url =
                env(
                    'TRANSACTION_SERVICE_URL',
                    'http://127.0.0.1:8006'
                )
                . '/api/transactions/clock';



            $response = Http::timeout(3)
                ->get($url);



            if ($response->successful()) {

                $this->lamportClock =
                    $response->json(
                        'data.current_lamport_clock',
                        0
                    );

            }


        } catch (\Throwable $e) {

            $this->lamportClock = 0;

        }

    }
}