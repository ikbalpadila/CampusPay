<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Row 1: Main Stats --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            
            {{-- Total Tagihan --}}
            <div class="flex items-center p-4 bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 rounded-2xl shadow-sm transition hover:shadow-md">
                <div class="p-3 bg-indigo-50 dark:bg-indigo-950/70 rounded-xl mr-4 text-indigo-600 dark:text-indigo-400">
                    <x-heroicon-o-document-duplicate class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Tagihan</p>
                    <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">{{ number_format($stats['tagihan']['total'], 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Total Pembayaran --}}
            <div class="flex items-center p-4 bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 rounded-2xl shadow-sm transition hover:shadow-md">
                <div class="p-3 bg-emerald-50 dark:bg-emerald-950/70 rounded-xl mr-4 text-emerald-600 dark:text-emerald-400">
                    <x-heroicon-o-check-circle class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Pembayaran</p>
                    <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">{{ number_format($stats['tagihan']['lunas'], 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Total Pending --}}
            <div class="flex items-center p-4 bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 rounded-2xl shadow-sm transition hover:shadow-md">
                <div class="p-3 bg-amber-50 dark:bg-amber-950/70 rounded-xl mr-4 text-amber-600 dark:text-amber-400">
                    <x-heroicon-o-clock class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Pending</p>
                    <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">{{ number_format($stats['tagihan']['pending'], 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Total Lunas --}}
            <div class="flex items-center p-4 bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 rounded-2xl shadow-sm transition hover:shadow-md">
                <div class="p-3 bg-teal-50 dark:bg-teal-950/70 rounded-xl mr-4 text-teal-600 dark:text-teal-400">
                    <x-heroicon-o-shield-check class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Lunas</p>
                    <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">{{ number_format($stats['tagihan']['lunas'], 0, ',', '.') }}</p>
                </div>
            </div>

        </div>

        {{-- Row 2: Payment Methods & Income --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

            {{-- Total VA --}}
            <div class="flex items-center p-4 bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 rounded-2xl shadow-sm transition hover:shadow-md">
                <div class="p-3 bg-purple-50 dark:bg-purple-950/70 rounded-xl mr-4 text-purple-600 dark:text-purple-400">
                    <x-heroicon-o-credit-card class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Virtual Account</p>
                    <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">{{ number_format($stats['transactions']['total_va'], 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Total Transfer Manual --}}
            <div class="flex items-center p-4 bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 rounded-2xl shadow-sm transition hover:shadow-md">
                <div class="p-3 bg-cyan-50 dark:bg-cyan-950/70 rounded-xl mr-4 text-cyan-600 dark:text-cyan-400">
                    <x-heroicon-o-arrow-path-rounded-square class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Transfer Manual</p>
                    <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">{{ number_format($stats['transactions']['total_manual'], 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Total Pendapatan --}}
            <div class="flex items-center p-4 bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 rounded-2xl shadow-sm transition hover:shadow-md sm:col-span-2 lg:col-span-1">
                <div class="p-3 bg-sky-50 dark:bg-sky-950/70 rounded-xl mr-4 text-sky-600 dark:text-sky-400">
                    <x-heroicon-o-banknotes class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Pendapatan</p>
                    <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">{{ $stats['keuangan']['total_pemasukan_formatted'] }}</p>
                </div>
            </div>

        </div>

        {{-- Export Controls --}}
        <div class="p-6 bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 rounded-2xl shadow-sm space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Ekspor & Filter Laporan</h3>
                    <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">Pilih tipe laporan dan format unduhan yang Anda butuhkan.</p>
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    <div class="w-full sm:w-48">
                        <select wire:model.live="reportType" class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs font-semibold px-3 py-2.5 shadow-sm">
                            <option value="transactions">Laporan Transaksi</option>
                            <option value="outstanding">Laporan Tunggakan</option>
                            <option value="summary">Ringkasan Keuangan</option>
                        </select>
                    </div>

                    <button wire:click="downloadPdf" class="inline-flex items-center gap-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white px-4 py-2.5 text-xs font-bold shadow-md active:scale-[0.99] transition">
                        <x-heroicon-o-document-arrow-down class="w-4 h-4" />
                        <span>Download PDF</span>
                    </button>

                    <button wire:click="downloadExcel" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2.5 text-xs font-bold shadow-md active:scale-[0.99] transition">
                        <x-heroicon-o-document-text class="w-4 h-4" />
                        <span>Download Excel</span>
                    </button>
                    
                    <button wire:click="loadStatsAndTransactions" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-750 px-4 py-2.5 text-xs font-bold shadow-sm transition">
                        <x-heroicon-o-arrow-path class="w-4 h-4" />
                        <span>Refresh Data</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Table Preview --}}
        <div class="p-6 bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pratinjau Data Laporan</h3>
                <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">Menampilkan seluruh data tagihan dan rincian transaksi terkait.</p>
            </div>
            
            {{ $this->table }}
        </div>

    </div>
</x-filament-panels::page>
