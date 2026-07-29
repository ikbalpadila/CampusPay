<x-filament-panels::page>

<div class="max-w-3xl space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Import Data Mahasiswa
        </h1>
        <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mt-1">
            Upload file Excel (.xlsx / .xls) sesuai format standar untuk mengimpor data mahasiswa ke dalam sistem secara massal.
        </p>
    </div>

    {{-- FORMAT INFO CARD --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 space-y-4 shadow-sm">

        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                Format File Yang Diperlukan
            </h2>
            
            <a href="{{ route('template.mahasiswa') }}"
               class="inline-flex items-center gap-1.5 text-xs font-bold text-primary-600 dark:text-primary-400 hover:underline">
                <x-heroicon-o-document-arrow-down class="w-4 h-4" />
                <span>Download Template Excel</span>
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800">
            <table class="w-full text-xs text-left text-gray-600 dark:text-gray-300">
                <thead class="bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-800 text-gray-700 dark:text-gray-300 uppercase font-bold text-[11px]">
                    <tr>
                        <th class="px-3 py-2.5">nim</th>
                        <th class="px-3 py-2.5">nama</th>
                        <th class="px-3 py-2.5">prodi</th>
                        <th class="px-3 py-2.5">fakultas</th>
                        <th class="px-3 py-2.5">semester</th>
                        <th class="px-3 py-2.5">kelas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 font-mono">
                    <tr class="bg-white dark:bg-gray-900">
                        <td class="px-3 py-2">2021010001</td>
                        <td class="px-3 py-2 font-sans font-medium">Budi Santoso</td>
                        <td class="px-3 py-2 font-sans">Teknik Informatika</td>
                        <td class="px-3 py-2 font-sans">Fakultas Teknik</td>
                        <td class="px-3 py-2 font-sans">Semester 5</td>
                        <td class="px-3 py-2">TI-5A</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <ul class="text-xs text-gray-500 dark:text-gray-400 space-y-1.5 list-disc list-inside bg-gray-50 dark:bg-gray-800/50 p-3 rounded-xl border border-gray-100 dark:border-gray-800">
            <li>Baris pertama (header) harus persis sama dengan nama kolom di atas.</li>
            <li>Nama semester harus sesuai dengan data semester aktif di sistem.</li>
            <li>NIM yang sudah terdaftar dalam database akan otomatis dilewati.</li>
        </ul>

    </div>

    {{-- FORM CARD --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">

        <form wire:submit="import" class="space-y-5">

            {{ $this->form }}

            <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-800">

                <span class="text-xs text-gray-400 dark:text-gray-500">
                    Proses pengunggahan mungkin memakan waktu beberapa detik...
                </span>

                <button type="submit"
                    class="px-6 py-2.5 text-sm font-bold text-white bg-primary-600 rounded-xl shadow-md hover:bg-primary-500 active:scale-[0.99] transition-all">
                    Import Data
                </button>

            </div>

        </form>

    </div>

    {{-- LINK DATA --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900 p-4 flex items-center justify-between">

        <span class="text-xs md:text-sm text-gray-600 dark:text-gray-400">
            Ingin melihat data mahasiswa yang ada?
        </span>

        <a href="{{ \App\Filament\Pages\MahasiswaPage::getUrl() }}"
            class="text-xs md:text-sm font-bold text-primary-600 dark:text-primary-400 hover:underline">
            Kembali ke Data Mahasiswa →
        </a>

    </div>

</div>

<x-filament-actions::modals />

</x-filament-panels::page>