<x-filament-panels::page>

    <div class="max-w-4xl mx-auto space-y-6">

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 dark:bg-emerald-950/60 dark:border-emerald-800/80 p-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <x-heroicon-o-check-circle class="h-5 w-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                    <span class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">
                        {{ session('success') }}
                    </span>
                </div>
            </div>
        @endif

        <div class="fi-section rounded-2xl bg-white dark:bg-gray-900 shadow-sm border border-gray-200/80 dark:border-gray-800 transition-colors overflow-hidden">

            {{-- Header --}}
            <div class="border-b border-gray-100 dark:border-gray-800 px-6 py-5">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    Generate Tagihan Massal
                </h2>
                <p class="mt-1 text-xs md:text-sm text-gray-500 dark:text-gray-400">
                    Buat tagihan secara otomatis untuk seluruh mahasiswa aktif berdasarkan semester dan jenis pembayaran.
                </p>
            </div>

            {{-- Form Body --}}
            <form wire:submit.prevent="generate">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">

                    {{-- Jenis Pembayaran --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                            Jenis Pembayaran
                        </label>
                        <select
                            wire:model="data.payment_type_id"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                            <option value="">Pilih Jenis Pembayaran</option>
                            @foreach (\App\Models\PaymentType::where('is_aktif', true)->get() as $pt)
                                <option value="{{ $pt->id }}">
                                    {{ $pt->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Semester --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                            Semester
                        </label>
                        <select
                            wire:model="data.semester_id"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                            <option value="">Pilih Semester</option>
                            @foreach ($this->getSemesterOptions() as $id => $nama)
                                <option value="{{ $id }}">
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Nominal --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                            Nominal Tagihan (Rp)
                        </label>
                        <input
                            type="number"
                            wire:model="data.nominal"
                            placeholder="Contoh: 2500000"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500">
                    </div>

                    {{-- Jatuh Tempo --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                            Jatuh Tempo
                        </label>
                        <input
                            type="date"
                            wire:model="data.jatuh_tempo"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                    </div>

                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-800">
                    <span class="text-xs text-gray-400 dark:text-gray-500">
                        * Tagihan akan digenerate untuk seluruh mahasiswa yang berada pada semester terpilih.
                    </span>

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-primary-500 active:scale-[0.99] transition-all disabled:opacity-50">

                        <svg wire:loading
                             class="h-4 w-4 animate-spin"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>

                        <span wire:loading.remove>Generate Tagihan</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </div>

            </form>

        </div>

    </div>

</x-filament-panels::page>