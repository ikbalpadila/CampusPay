@extends('layouts.mahasiswa')
@section('title', 'Tagihan Saya')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-2">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white">Tagihan Saya</h1>
            <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-0.5">Daftar seluruh kewajiban tagihan akademik Anda</p>
        </div>
    </div>

    <!-- Bills List -->
    <div class="space-y-4">
        @forelse($tagihans as $tagihan)
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                
                <!-- Tagihan Info -->
                <div class="space-y-1.5 flex-1">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h3 class="font-bold text-slate-900 dark:text-white text-base md:text-lg">
                            {{ $tagihan->paymentType->nama ?? '-' }}
                        </h3>
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-bold
                            {{ $tagihan->status === 'lunas' ? 'bg-emerald-100 dark:bg-emerald-950/70 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60' :
                               ($tagihan->status === 'pending' ? 'bg-amber-100 dark:bg-amber-950/70 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/60' :
                               'bg-rose-100 dark:bg-rose-950/70 text-rose-700 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/60') }}">
                            {{ $tagihan->status === 'belum_bayar' ? 'Belum Bayar' : ucfirst($tagihan->status) }}
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 dark:text-slate-400">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            {{ $tagihan->semester_nama }}
                        </span>
                        <span>•</span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Jatuh Tempo: <strong class="text-slate-700 dark:text-slate-300 font-semibold">{{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('d M Y') }}</strong>
                        </span>
                    </div>

                    @if($tagihan->catatan)
                    <p class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/60 rounded-xl px-3 py-2 border border-slate-100 dark:border-slate-800/80 inline-block mt-2">
                        <span class="font-semibold">Catatan:</span> {{ $tagihan->catatan }}
                    </p>
                    @endif
                </div>

                <!-- Price & Action Button -->
                <div class="flex flex-col md:items-end justify-between md:justify-center border-t md:border-t-0 pt-3 md:pt-0 border-slate-100 dark:border-slate-800 gap-2">
                    <div class="text-left md:text-right">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Nominal</span>
                        <p class="text-xl md:text-2xl font-extrabold text-slate-900 dark:text-white">
                            Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}
                        </p>
                    </div>

                    @if($tagihan->status === 'belum_bayar')
                    <div class="flex flex-wrap gap-2 mt-1">
                        <a href="{{ route('mahasiswa.tagihan.bayar', $tagihan->id) }}"
                            class="inline-flex items-center gap-1.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md shadow-indigo-500/20 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>Bayar VA</span>
                        </a>
                        <a href="{{ route('mahasiswa.tagihan.upload-bukti', $tagihan->id) }}"
                            class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs px-4 py-2.5 rounded-xl transition-all border border-slate-200/80 dark:border-slate-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            <span>Upload Bukti</span>
                        </a>
                    </div>
                    @elseif($tagihan->status === 'pending')
                    <div class="inline-flex items-center gap-1.5 bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 text-xs font-semibold px-3 py-1.5 rounded-xl border border-amber-200/60 dark:border-amber-800/60 mt-1">
                        <svg class="w-4 h-4 animate-spin text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Menunggu verifikasi admin</span>
                    </div>
                    @elseif($tagihan->status === 'lunas')
                    <div class="inline-flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 text-xs font-semibold px-3 py-1.5 rounded-xl border border-emerald-200/60 dark:border-emerald-800/60 mt-1">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Lunas</span>
                    </div>
                    @endif
                </div>

            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 mx-auto flex items-center justify-center mb-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-800 dark:text-slate-200">Belum Ada Tagihan</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Seluruh tagihan pembayaran Anda akan muncul di halaman ini.</p>
        </div>
        @endforelse
    </div>

</div>
@endsection