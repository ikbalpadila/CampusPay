<x-filament-panels::page>

    {{-- ALERT --}}
    @if($message)
        <div class="mb-6 rounded-2xl border border-emerald-500/30 bg-emerald-50 dark:bg-emerald-950/60 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.172 7.707 8.879a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-emerald-800 dark:text-emerald-300 font-bold text-sm">
                    {{ $message }}
                </span>
            </div>
        </div>
    @endif

    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        {{-- TOTAL --}}
        <div class="bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                        Total Semester
                    </p>
                    <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white mt-2">
                        {{ count($semesters) }}
                    </h2>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/70 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl">
                    📚
                </div>
            </div>
        </div>

        {{-- AKTIF --}}
        <div class="bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                        Semester Aktif
                    </p>
                    <h2 class="text-4xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-2">
                        {{ collect($semesters)->where('is_aktif', true)->count() }}
                    </h2>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl">
                    ✅
                </div>
            </div>
        </div>

        {{-- NONAKTIF --}}
        <div class="bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                        Semester Nonaktif
                    </p>
                    <h2 class="text-4xl font-extrabold text-amber-500 dark:text-amber-400 mt-2">
                        {{ collect($semesters)->where('is_aktif', false)->count() }}
                    </h2>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/70 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl">
                    📅
                </div>
            </div>
        </div>

    </div>

    {{-- TABEL --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden">

        {{-- HEADER TABLE --}}
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    Daftar Semester
                </h2>
                <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    Kelola data semester dan periode akademik yang aktif di sistem.
                </p>
            </div>
        
            <button
                wire:click="openCreateModal"
                class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-purple-600 hover:bg-purple-500 rounded-xl text-white font-bold text-xs shadow-md transition">
                <span>+ Tambah Semester</span>
            </button>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200/80 dark:border-gray-800 text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Nama Semester</th>
                        <th class="px-6 py-4">Tahun Ajaran</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-800 dark:text-gray-200">
                    @forelse($semesters as $sem)
                        <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-800/40 transition">
                            <td class="px-6 py-4 font-mono font-semibold text-xs text-gray-500">
                                #{{ $sem['id'] }}
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                {{ $sem['nama'] }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300 font-mono text-xs">
                                {{ $sem['tahun_ajaran'] }}
                            </td>
                            <td class="px-6 py-4">
                                @if($sem['is_aktif'])
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/70 text-emerald-700 dark:text-emerald-300 text-xs font-bold border border-emerald-200/60 dark:border-emerald-800/60">
                                        ● Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 dark:bg-rose-950/70 text-rose-700 dark:text-rose-300 text-xs font-bold border border-rose-200/60 dark:border-rose-800/60">
                                        ● Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    @if(!$sem['is_aktif'])
                                        <button
                                            wire:click="setAktif({{ $sem['id'] }})"
                                            class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 rounded-xl text-white font-bold text-xs shadow-sm transition">
                                            Aktifkan
                                        </button>
                                    @endif

                                    <button
                                        wire:click="editSemester({{ $sem['id'] }})"
                                        class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-white font-bold text-xs shadow-sm transition">
                                        Edit
                                    </button>

                                    <button
                                        wire:click="deleteSemester({{ $sem['id'] }})"
                                        onclick="return confirm('Yakin ingin menghapus semester ini?')"
                                        class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 rounded-xl text-white font-bold text-xs shadow-sm transition">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-gray-400 dark:text-gray-500 text-sm">
                                Belum ada data semester.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- MODAL EDIT --}}
    @if($showEditModal)
        <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
                <div class="border-b border-gray-100 dark:border-gray-800 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        Edit Semester
                    </h3>
                    <button wire:click="$set('showEditModal', false)" class="text-gray-400 hover:text-rose-500">✕</button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Nama Semester
                        </label>
                        <select
                            wire:model="editNama"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-purple-500">
                            <option value="Semester 1">Semester 1</option>
                            <option value="Semester 2">Semester 2</option>
                            <option value="Semester 3">Semester 3</option>
                            <option value="Semester 4">Semester 4</option>
                            <option value="Semester 5">Semester 5</option>
                            <option value="Semester 6">Semester 6</option>
                            <option value="Semester 7">Semester 7</option>
                            <option value="Semester 8">Semester 8</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Tahun Ajaran
                        </label>
                        <input
                            type="text"
                            wire:model="editTahunAjaran"
                            placeholder="2025/2026"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t border-gray-100 dark:border-gray-800 p-4">
                    <button
                        wire:click="$set('showEditModal', false)"
                        class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold text-xs transition">
                        Batal
                    </button>
                    <button
                        wire:click="updateSemester"
                        class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-md transition">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL CREATE --}}
    @if($showCreateModal)
        <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
                <div class="border-b border-gray-100 dark:border-gray-800 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        Tambah Semester Baru
                    </h3>
                    <button wire:click="$set('showCreateModal', false)" class="text-gray-400 hover:text-rose-500">✕</button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Nama Semester
                        </label>
                        <select
                            wire:model="nama"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-purple-500">
                            <option value="">Pilih Semester</option>
                            <option value="Semester 1">Semester 1</option>
                            <option value="Semester 2">Semester 2</option>
                            <option value="Semester 3">Semester 3</option>
                            <option value="Semester 4">Semester 4</option>
                            <option value="Semester 5">Semester 5</option>
                            <option value="Semester 6">Semester 6</option>
                            <option value="Semester 7">Semester 7</option>
                            <option value="Semester 8">Semester 8</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Tahun Ajaran
                        </label>
                        <input
                            type="text"
                            wire:model="tahun_ajaran"
                            placeholder="Contoh: 2025/2026"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t border-gray-100 dark:border-gray-800 p-4">
                    <button
                        wire:click="$set('showCreateModal', false)"
                        class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold text-xs transition">
                        Batal
                    </button>
                    <button
                        wire:click="createSemester"
                        class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-md transition">
                        Simpan Semester
                    </button>
                </div>
            </div>
        </div>
    @endif

</x-filament-panels::page>