<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Tagihan;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class Laporan extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $title = 'Laporan Pembayaran';
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?int $navigationSort = 4;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected string $view = 'filament.pages.laporan';

    public array $stats = [
        'tagihan' => [
            'total' => 0,
            'lunas' => 0,
            'pending' => 0,
            'belum_bayar' => 0,
        ],
        'keuangan' => [
            'total_pemasukan' => 0,
            'total_pemasukan_formatted' => 'Rp 0',
        ],
        'transactions' => [
            'total_va' => 0,
            'total_manual' => 0,
        ]
    ];

    public array $transactionsMap = [];
    public string $reportType = 'transactions'; // transactions, outstanding, summary

    public static function getNavigationGroup(): ?string
    {
        return 'Keuangan';
    }

    public function mount(): void
    {
        $this->loadStatsAndTransactions();
    }

    public function loadStatsAndTransactions(): void
    {
        // 1. Direct DB calculation for Tagihan Stats
        $totalTagihan    = Tagihan::count();
        $lunasCount      = Tagihan::where('status', 'lunas')->count();
        $pendingCount    = Tagihan::where('status', 'pending')->count();
        $belumBayarCount = Tagihan::where('status', 'belum_bayar')->count();

        $totalPemasukan = Tagihan::where('status', 'lunas')->sum('nominal');

        $this->stats['tagihan'] = [
            'total'       => $totalTagihan,
            'lunas'       => $lunasCount,
            'pending'     => $pendingCount,
            'belum_bayar' => $belumBayarCount,
        ];

        $this->stats['keuangan'] = [
            'total_pemasukan'           => $totalPemasukan,
            'total_pemasukan_formatted' => 'Rp ' . number_format($totalPemasukan, 0, ',', '.'),
        ];

        // 2. Fetch Transactions directly from transaction-service
        $transactionUrl = config('services.transaction_service.url', 'http://127.0.0.1:8006');
        try {
            $transResponse = Http::timeout(3)->get("{$transactionUrl}/api/transactions");
            if ($transResponse->successful()) {
                $rawTransactions = $transResponse->json('data.data') ?? $transResponse->json('data') ?? [];
                if (!is_array($rawTransactions)) {
                    $rawTransactions = [];
                }
                $totalVa = 0;
                $totalManual = 0;
                $map = [];
                foreach ($rawTransactions as $trx) {
                    if (($trx['status'] ?? '') === 'success') {
                        if (($trx['metode'] ?? '') === 'virtual_account') {
                            $totalVa++;
                        } elseif (($trx['metode'] ?? '') === 'transfer_manual') {
                            $totalManual++;
                        }
                    }
                    if (isset($trx['tagihan_id'])) {
                        $map[$trx['tagihan_id']] = $trx;
                    }
                }
                $this->transactionsMap = $map;
                $this->stats['transactions']['total_va'] = $totalVa;
                $this->stats['transactions']['total_manual'] = $totalManual;
            }
        } catch (\Exception $e) {
            Log::error('Gagal fetch transactions: ' . $e->getMessage());
        }
    }

    public function getSemesterOptions(): array
    {
        try {
            $response = Http::get(config('services.student.url') . '/api/semesters');
            if ($response->successful()) {
                return collect($response->json('data'))
                    ->pluck('nama', 'id')
                    ->toArray();
            }
        } catch (\Exception $e) {
            Log::error('Gagal fetch semesters: ' . $e->getMessage());
        }
        return [];
    }

    public function getTransactionsMap(): array
    {
        if (empty($this->transactionsMap)) {
            $transactionUrl = config('services.transaction_service.url', 'http://127.0.0.1:8006');
            try {
                $transResponse = Http::timeout(3)->get("{$transactionUrl}/api/transactions");
                if ($transResponse->successful()) {
                    $rawTransactions = $transResponse->json('data.data') ?? $transResponse->json('data') ?? [];
                    if (is_array($rawTransactions)) {
                        $map = [];
                        foreach ($rawTransactions as $trx) {
                            if (isset($trx['tagihan_id'])) {
                                $map[$trx['tagihan_id']] = $trx;
                            }
                        }
                        $this->transactionsMap = $map;
                    }
                }
            } catch (\Exception $e) {
                Log::error('Gagal fetch transactionsMap: ' . $e->getMessage());
            }
        }
        return $this->transactionsMap;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Tagihan::query()->with('paymentType'))
            ->columns([
                TextColumn::make('mahasiswa_nama')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('mahasiswa_nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('paymentType.nama')
                    ->label('Jenis Tagihan')
                    ->sortable(),

                TextColumn::make('semester_nama')
                    ->label('Semester')
                    ->sortable(),

                TextColumn::make('nominal')
                    ->label('Nominal')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'belum_bayar' => 'danger',
                        'pending' => 'warning',
                        'lunas' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'belum_bayar' => 'Belum Bayar',
                        'pending' => 'Pending',
                        'lunas' => 'Lunas',
                        default => $state,
                    }),

                TextColumn::make('metode')
                    ->label('Metode Pembayaran')
                    ->state(fn ($record) => $this->getTransactionsMap()[$record->id]['metode'] ?? '—')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'virtual_account' => 'Virtual Account',
                        'transfer_manual' => 'Transfer Manual',
                        default => $state,
                    }),

                TextColumn::make('nomor_va')
                    ->label('Nomor VA')
                    ->state(fn ($record) => $this->getTransactionsMap()[$record->id]['nomor_va'] ?? '—'),

                TextColumn::make('lamport_clock')
                    ->label('Lamport Clock')
                    ->state(fn ($record) => $this->getTransactionsMap()[$record->id]['lamport_clock'] ?? '—'),

                TextColumn::make('admin_verif')
                    ->label('Admin Verifikasi')
                    ->state(function ($record) {
                        if (!$record->catatan) return '—';
                        if (str_contains(strtolower($record->catatan), 'admin') || str_contains(strtolower($record->catatan), 'setuju') || str_contains(strtolower($record->catatan), 'tolak')) {
                            return 'Admin Keuangan';
                        }
                        return '—';
                    }),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('Tanggal Awal'),
                        DatePicker::make('created_until')->label('Tanggal Akhir'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),

                SelectFilter::make('payment_type_id')
                    ->label('Jenis Pembayaran')
                    ->options(fn () => \App\Models\PaymentType::pluck('nama', 'id')->toArray()),

                SelectFilter::make('semester_id')
                    ->label('Semester')
                    ->options(function () {
                        $apiOptions = $this->getSemesterOptions();
                        if (!empty($apiOptions)) {
                            return $apiOptions;
                        }
                        return Tagihan::whereNotNull('semester_id')
                            ->pluck('semester_nama', 'semester_id')
                            ->toArray();
                    }),

                SelectFilter::make('status')
                    ->label('Status Pembayaran')
                    ->options([
                        'belum_bayar' => 'Belum Bayar',
                        'pending' => 'Pending',
                        'lunas' => 'Lunas',
                    ]),

                Filter::make('mahasiswa')
                    ->form([
                        TextInput::make('mahasiswa_search')
                            ->label('Mahasiswa')
                            ->placeholder('Nama / NIM'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['mahasiswa_search'],
                            fn (Builder $query, $search): Builder => $query->where(function ($q) use ($search) {
                                $q->where('mahasiswa_nama', 'like', "%{$search}%")
                                  ->orWhere('mahasiswa_nim', 'like', "%{$search}%");
                            })
                        );
                    }),

                Filter::make('nomor_va')
                    ->form([
                        TextInput::make('va_search')
                            ->label('Nomor VA')
                            ->placeholder('Cari Nomor VA...'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $search = $data['va_search'] ?? null;
                        if (!$search) {
                            return $query;
                        }
                        $transMap = $this->getTransactionsMap();
                        $matchingIds = collect($transMap)
                            ->filter(fn ($t) => str_contains($t['nomor_va'] ?? '', $search))
                            ->keys()
                            ->toArray();
                        return $query->whereIn('id', $matchingIds);
                    }),

                SelectFilter::make('metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->options([
                        'virtual_account' => 'Virtual Account',
                        'transfer_manual' => 'Transfer Manual',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;
                        if (!$value) {
                            return $query;
                        }
                        $transMap = $this->getTransactionsMap();
                        $matchingIds = collect($transMap)
                            ->filter(fn ($t) => ($t['metode'] ?? '') === $value)
                            ->keys()
                            ->toArray();
                        return $query->whereIn('id', $matchingIds);
                    }),

                SelectFilter::make('admin_verifikasi')
                    ->label('Admin Verifikator')
                    ->options([
                        'admin_keuangan' => 'Admin Keuangan (Manual)',
                        'system'         => 'Otomatis (System / VA)',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;
                        if (!$value) {
                            return $query;
                        }
                        if ($value === 'admin_keuangan') {
                            return $query->where(function ($q) {
                                $q->where('catatan', 'like', '%admin%')
                                  ->orWhere('catatan', 'like', '%setuju%')
                                  ->orWhere('catatan', 'like', '%tolak%');
                            });
                        } elseif ($value === 'system') {
                            return $query->where('status', 'lunas')
                                ->where(function ($q) {
                                    $q->where('catatan', 'not like', '%admin%')
                                      ->orWhereNull('catatan');
                                });
                        }
                        return $query;
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public function downloadPdf()
    {
        $query = $this->getFilteredTableQuery()->with('paymentType');

        if ($this->reportType === 'outstanding') {
            $query->where('status', 'belum_bayar');
        } elseif ($this->reportType === 'transactions') {
            $query->whereIn('status', ['lunas', 'pending']);
        }

        $tagihans = $query->get();
        $transMap = $this->getTransactionsMap();

        return response()->streamDownload(function () use ($tagihans, $transMap) {
            echo view('reports.pdf', [
                'tagihans'   => $tagihans,
                'transMap'   => $transMap,
                'reportType' => $this->reportType,
            ])->render();
        }, "laporan_{$this->reportType}_" . now()->format('Ymd_His') . ".html", [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }

    public function downloadExcel()
    {
        $query = $this->getFilteredTableQuery()->with('paymentType');

        if ($this->reportType === 'outstanding') {
            $query->where('status', 'belum_bayar');
        } elseif ($this->reportType === 'transactions') {
            $query->whereIn('status', ['lunas', 'pending']);
        }

        $tagihans = $query->get();
        $transMap = $this->getTransactionsMap();

        $filename = "laporan_{$this->reportType}_" . now()->format('Ymd_His') . ".csv";

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($tagihans, $transMap) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM for Excel

            fputcsv($file, [
                'No',
                'Nama Mahasiswa',
                'NIM',
                'Jenis Tagihan',
                'Semester',
                'Nominal (Rp)',
                'Tanggal Tagihan',
                'Status Pembayaran',
                'Metode Pembayaran',
                'Nomor Virtual Account',
                'Lamport Clock',
                'Verifikator'
            ]);

            $no = 1;
            foreach ($tagihans as $tagihan) {
                $trx = $transMap[$tagihan->id] ?? null;
                $metode = $trx ? ($trx['metode'] === 'virtual_account' ? 'Virtual Account' : 'Transfer Manual') : '—';
                $noVa = $trx['nomor_va'] ?? '—';
                $clock = $trx['lamport_clock'] ?? '—';

                $adminVerif = '—';
                if ($tagihan->catatan && (str_contains(strtolower($tagihan->catatan), 'admin') || str_contains(strtolower($tagihan->catatan), 'setuju') || str_contains(strtolower($tagihan->catatan), 'tolak'))) {
                    $adminVerif = 'Admin Keuangan';
                } elseif ($tagihan->status === 'lunas') {
                    $adminVerif = 'Otomatis System';
                }

                fputcsv($file, [
                    $no++,
                    $tagihan->mahasiswa_nama,
                    $tagihan->mahasiswa_nim,
                    $tagihan->paymentType->nama ?? '—',
                    $tagihan->semester_nama,
                    (float)$tagihan->nominal,
                    $tagihan->created_at ? $tagihan->created_at->format('d/m/Y H:i') : '—',
                    strtoupper($tagihan->status),
                    $metode,
                    $noVa,
                    $clock,
                    $adminVerif
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
