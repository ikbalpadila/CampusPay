<x-filament-panels::page>

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Data Mahasiswa
            </h2>
            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">
                Kelola daftar seluruh mahasiswa terintegrasi dari Student Service
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            {{-- Download Template --}}
            <a
                href="{{ route('template.mahasiswa') }}"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm active:scale-[0.99] transition">
                <x-heroicon-o-document-arrow-down class="w-4 h-4" />
                <span>Download Template</span>
            </a>
        
            {{-- Import Excel --}}
            <a
                href="{{ \App\Filament\Pages\ImportMahasiswaPage::getUrl() }}"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs shadow-sm active:scale-[0.99] transition">
                <x-heroicon-o-arrow-up-tray class="w-4 h-4" />
                <span>Import Excel</span>
            </a>
        
            {{-- Tambah Manual --}}
            <button
                wire:click="openCreateModal"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-bold text-xs shadow-md active:scale-[0.99] transition">
                <x-heroicon-o-plus-circle class="w-4 h-4" />
                <span>Tambah Mahasiswa</span>
            </button>
        </div>
    </div>

    <!-- FILTER CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl border border-gray-200/80 dark:border-gray-800 shadow-sm">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <x-heroicon-o-magnifying-glass class="w-4 h-4" />
            </span>
            <input
                type="text"
                wire:model.live="search"
                placeholder="Cari NIM atau Nama Mahasiswa..."
                class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white pl-10 pr-4 py-2.5 text-xs md:text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm placeholder:text-gray-400"
            >
        </div>

        <div>
            <select
                wire:model.live="semesterFilter"
                class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2.5 text-xs md:text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm"
            >
                <option value="">Semua Semester</option>
                @foreach($semesters as $semester)
                    <option value="{{ $semester['id'] }}">
                        {{ $semester['nama'] }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- TABLE CONTAINER -->
    <div class="overflow-x-auto rounded-2xl border border-gray-200/80 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">

        <table class="min-w-full text-sm text-left">

            <thead class="bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200/80 dark:border-gray-800 text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3.5 whitespace-nowrap">NIM</th>
                    <th class="px-4 py-3.5 whitespace-nowrap">Nama Lengkap</th>
                    <th class="px-4 py-3.5 whitespace-nowrap">Program Studi</th>
                    <th class="px-4 py-3.5 whitespace-nowrap">Semester</th>
                    <th class="px-4 py-3.5 whitespace-nowrap">Kelas</th>
                    <th class="px-4 py-3.5 whitespace-nowrap text-center sticky right-0 bg-gray-50 dark:bg-gray-800 shadow-l">
                        Aksi
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-800 dark:text-gray-200">

                @forelse($mahasiswas as $mhs)
                    <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-800/40 transition">

                        <td class="px-4 py-3.5 whitespace-nowrap font-mono font-semibold text-xs text-gray-600 dark:text-gray-400">{{ $mhs['nim'] }}</td>
                        <td class="px-4 py-3.5 whitespace-nowrap font-bold text-gray-900 dark:text-white">{{ $mhs['nama'] }}</td>
                        <td class="px-4 py-3.5 whitespace-nowrap text-xs">{{ $mhs['prodi'] }}</td>
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 dark:bg-indigo-950/70 text-indigo-700 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/60">
                                {{ $mhs['semester']['nama'] ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 whitespace-nowrap text-xs font-mono">{{ $mhs['kelas'] }}</td>

                        <!-- AKSI -->
                        <td class="px-4 py-3.5 whitespace-nowrap sticky right-0 bg-white dark:bg-gray-900 border-l border-gray-100 dark:border-gray-800">
                            <div class="flex items-center justify-center gap-1.5">
                                <button
                                    wire:click="viewMahasiswa({{ $mhs['id'] }})"
                                    class="px-2.5 py-1 text-xs font-bold bg-sky-50 dark:bg-sky-950/80 text-sky-700 dark:text-sky-300 hover:bg-sky-100 dark:hover:bg-sky-900 rounded-lg border border-sky-200 dark:border-sky-800 transition"
                                >
                                    Detail
                                </button>

                                <button
                                    wire:click="editMahasiswa({{ $mhs['id'] }})"
                                    class="px-2.5 py-1 text-xs font-bold bg-amber-50 dark:bg-amber-950/80 text-amber-700 dark:text-amber-300 hover:bg-amber-100 dark:hover:bg-amber-900 rounded-lg border border-amber-200 dark:border-amber-800 transition"
                                >
                                    Edit
                                </button>

                                <button
                                    wire:click="confirmDelete({{ $mhs['id'] }})"
                                    class="px-2.5 py-1 text-xs font-bold bg-rose-50 dark:bg-rose-950/80 text-rose-700 dark:text-rose-300 hover:bg-rose-100 dark:hover:bg-rose-900 rounded-lg border border-rose-200 dark:border-rose-800 transition"
                                >
                                    Hapus
                                </button>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center p-12 text-gray-400 dark:text-gray-500 text-sm">
                            Belum ada data mahasiswa yang cocok dengan pencarian Anda.
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

</div>

<!-- DETAIL MODAL -->
@if($showDetailModal && $selectedMahasiswa)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-2xl w-full max-w-xl p-6 space-y-5 animate-in fade-in zoom-in-95 duration-150">

        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                Detail Data Mahasiswa
            </h3>
            <button wire:click="$set('showDetailModal', false)" class="p-1 rounded-lg text-gray-400 hover:text-rose-500 transition">✕</button>
        </div>

        <div class="grid grid-cols-2 gap-4 text-xs md:text-sm">
            <div class="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-xl"><span class="text-xs font-bold text-gray-400 uppercase">NIM</span><p class="font-mono font-bold text-gray-900 dark:text-white mt-1">{{ $selectedMahasiswa['nim'] }}</p></div>
            <div class="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-xl"><span class="text-xs font-bold text-gray-400 uppercase">Nama</span><p class="font-bold text-gray-900 dark:text-white mt-1">{{ $selectedMahasiswa['nama'] }}</p></div>
            <div class="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-xl"><span class="text-xs font-bold text-gray-400 uppercase">Prodi</span><p class="font-medium text-gray-800 dark:text-gray-200 mt-1">{{ $selectedMahasiswa['prodi'] }}</p></div>
            <div class="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-xl"><span class="text-xs font-bold text-gray-400 uppercase">Fakultas</span><p class="font-medium text-gray-800 dark:text-gray-200 mt-1">{{ $selectedMahasiswa['fakultas'] }}</p></div>
            <div class="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-xl"><span class="text-xs font-bold text-gray-400 uppercase">Kelas</span><p class="font-mono font-medium text-gray-800 dark:text-gray-200 mt-1">{{ $selectedMahasiswa['kelas'] }}</p></div>
            <div class="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-xl"><span class="text-xs font-bold text-gray-400 uppercase">Semester</span><p class="font-medium text-gray-800 dark:text-gray-200 mt-1">{{ $selectedMahasiswa['semester']['nama'] ?? '-' }}</p></div>
        </div>

        <div class="pt-2 text-right">
            <button wire:click="$set('showDetailModal', false)" class="px-5 py-2 text-xs font-bold rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 transition">
                Tutup
            </button>
        </div>

    </div>
</div>
@endif

<!-- TAMBAH MODAL -->
@if($showModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl p-6 space-y-5">

        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                Tambah Mahasiswa Baru
            </h3>
            <button wire:click="$set('showModal', false)" class="p-1 rounded-lg text-gray-400 hover:text-rose-500 transition">✕</button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">NIM</label>
                <input wire:model="nim" placeholder="Contoh: 2021010001" class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2 text-sm focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                <input wire:model="nama" placeholder="Masukkan Nama" class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2 text-sm focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Program Studi</label>
                <input wire:model="prodi" placeholder="Contoh: Teknik Informatika" class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2 text-sm focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Fakultas</label>
                <input wire:model="fakultas" placeholder="Contoh: Fakultas Teknik" class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2 text-sm focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Kelas</label>
                <input wire:model="kelas" placeholder="Contoh: TI-5A" class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2 text-sm focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Semester</label>
                <select wire:model="semester_id" class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2 text-sm focus:ring-2 focus:ring-primary-500">
                    <option value="">Pilih Semester</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester['id'] }}">{{ $semester['nama'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
            <button wire:click="$set('showModal', false)" class="px-5 py-2 text-xs font-bold rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 transition">
                Batal
            </button>
            <button wire:click="createMahasiswa" class="px-5 py-2 text-xs font-bold rounded-xl bg-primary-600 hover:bg-primary-500 text-white shadow-md transition">
                Simpan Mahasiswa
            </button>
        </div>

    </div>
</div>
@endif

<!-- EDIT MODAL -->
@if($showEditModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl p-6 space-y-5">

        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                Edit Data Mahasiswa
            </h3>
            <button wire:click="$set('showEditModal', false)" class="p-1 rounded-lg text-gray-400 hover:text-rose-500 transition">✕</button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">NIM</label>
                <input wire:model="nim" class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2 text-sm focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                <input wire:model="nama" class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2 text-sm focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Program Studi</label>
                <input wire:model="prodi" class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2 text-sm focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Fakultas</label>
                <input wire:model="fakultas" class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2 text-sm focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Kelas</label>
                <input wire:model="kelas" class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2 text-sm focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Semester</label>
                <select wire:model="semester_id" class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2 text-sm focus:ring-2 focus:ring-primary-500">
                    @foreach($semesters as $semester)
                        <option value="{{ $semester['id'] }}">{{ $semester['nama'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
            <button wire:click="$set('showEditModal', false)" class="px-5 py-2 text-xs font-bold rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 transition">
                Batal
            </button>
            <button wire:click="updateMahasiswa" class="px-5 py-2 text-xs font-bold rounded-xl bg-amber-600 hover:bg-amber-500 text-white shadow-md transition">
                Update Data
            </button>
        </div>

    </div>
</div>
@endif

<!-- DELETE MODAL -->
@if($showDeleteModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-2xl w-full max-w-md p-6 space-y-4">

        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
            Konfirmasi Hapus Data
        </h3>

        <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">
            Apakah Anda yakin ingin menghapus data mahasiswa ini? Tindakan ini tidak dapat dibatalkan.
        </p>

        <div class="flex justify-end gap-2 pt-3">
            <button
                wire:click="$set('showDeleteModal', false)"
                class="px-4 py-2 text-xs font-bold rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 transition">
                Batal
            </button>

            <button
                wire:click="deleteMahasiswa"
                class="px-4 py-2 text-xs font-bold rounded-xl bg-rose-600 hover:bg-rose-500 text-white shadow-md transition">
                Ya, Hapus Data
            </button>
        </div>

    </div>
</div>
@endif

</x-filament-panels::page>