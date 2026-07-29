<x-filament-panels::page>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4 mb-6">

        <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                Total Tagihan
            </p>
            <p class="text-3xl font-extrabold text-blue-600 dark:text-blue-400 mt-1">
                {{ $totalTagihan }}
            </p>
        </div>

        <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                Tagihan Lunas
            </p>
            <p class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">
                {{ $totalLunas }}
            </p>
        </div>

        <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                Pending Verifikasi
            </p>
            <p class="text-3xl font-extrabold text-amber-500 dark:text-amber-400 mt-1">
                {{ $totalPending }}
            </p>
        </div>

        <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                Lamport Clock
            </p>
            <p class="text-3xl font-extrabold text-purple-600 dark:text-purple-400 mt-1">
                {{ $lamportClock }}
            </p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                Total transaksi tercatat
            </p>
        </div>

    </div>

    {{-- Info Sistem --}}
    <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 p-6 shadow-sm mb-6">
        <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">
            Informasi Sistem & Layanan
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr>
                        <td class="py-3 text-gray-500 dark:text-gray-400 w-48 font-medium">
                            Total Admin User
                        </td>
                        <td class="py-3 font-bold text-gray-900 dark:text-white">
                            {{ $totalUsers }} User Terdaftar
                        </td>
                    </tr>
                    <tr>
                        <td class="py-3 text-gray-500 dark:text-gray-400 font-medium">
                            Panel Admin Keuangan
                        </td>
                        <td class="py-3">
                            <a href="/admin" target="_blank"
                               class="inline-flex items-center gap-1 text-primary-600 dark:text-primary-400 hover:underline text-xs font-bold">
                                <span>localhost:8003/admin</span>
                                <span>→</span>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-3 text-gray-500 dark:text-gray-400 font-medium">
                            Panel Portal Mahasiswa
                        </td>
                        <td class="py-3">
                            <a href="/mahasiswa/login" target="_blank"
                               class="inline-flex items-center gap-1 text-primary-600 dark:text-primary-400 hover:underline text-xs font-bold">
                                <span>localhost:8003/mahasiswa/login</span>
                                <span>→</span>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-3 text-gray-500 dark:text-gray-400 font-medium">
                            Lamport Clock Terakhir
                        </td>
                        <td class="py-3 font-mono font-bold text-purple-600 dark:text-purple-400">
                            Clock #{{ $lamportClock }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Kelola Admin / Aksi Cepat --}}
    <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 p-6 shadow-sm">
        <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">
            Aksi Cepat Superadmin
        </h2>
        <div class="flex gap-3 flex-wrap">
            <a href="/superadmin/users"
               class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-md transition">
                <x-heroicon-o-user-group class="w-4 h-4" />
                <span>Kelola Akun Admin</span>
            </a>
            <a href="/admin" target="_blank"
               class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-500 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-md transition">
                <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4" />
                <span>Buka Panel Admin Keuangan</span>
            </a>
        </div>
    </div>

</x-filament-panels::page>