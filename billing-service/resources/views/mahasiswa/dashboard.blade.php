@extends('layouts.mahasiswa')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-blue-700 dark:from-indigo-900 dark:via-indigo-800 dark:to-slate-900 text-white shadow-xl shadow-indigo-500/10 p-6 md:p-8">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-block px-3 py-1 bg-white/20 dark:bg-white/10 backdrop-blur-md rounded-full text-xs font-semibold tracking-wide uppercase mb-2 text-indigo-100">
                    Dashboard Mahasiswa
                </span>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                    Selamat datang kembali, {{ $mahasiswa['nama'] }}!
                </h1>
                <p class="text-indigo-100 dark:text-slate-300 text-sm mt-1">
                    Kelola dan pantau seluruh pembayaran akademik Anda dengan cepat dan aman.
                </p>
            </div>

            <div class="hidden md:block text-right bg-white/10 dark:bg-white/5 backdrop-blur-md px-4 py-3 rounded-2xl border border-white/10">
                <p class="text-xs text-indigo-200 uppercase tracking-wider font-semibold">Hari ini</p>
                <p class="text-sm font-bold text-white mt-0.5">
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <!-- Total Tagihan -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Total Tagihan</span>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/70 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 dark:text-white">
                {{ $totalTagihan }}
            </p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Tagihan terdaftar</p>
        </div>

        <!-- Lunas -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Lunas</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">
                {{ $tagihanLunas }}
            </p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Pembayaran terverifikasi</p>
        </div>

        <!-- Pending -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Pending</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/70 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-amber-500 dark:text-amber-400">
                {{ $tagihanPending }}
            </p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Menunggu verifikasi admin</p>
        </div>

        <!-- Belum Bayar -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Belum Bayar</span>
                <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/70 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-rose-500 dark:text-rose-400">
                {{ $tagihanBelum }}
            </p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Tagihan perlu dilunasi</p>
        </div>
    </div>

    <!-- Outstanding Payment Callout Banner -->
    @if($totalNominalBelum > 0)
    <div class="bg-gradient-to-r from-rose-500/10 via-rose-500/5 to-transparent dark:from-rose-950/40 dark:via-rose-950/20 border border-rose-200 dark:border-rose-900/60 rounded-3xl p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-rose-700 dark:text-rose-300 font-bold text-xs uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                Tunggakan Tagihan Aktif
            </div>
            <p class="text-2xl md:text-3xl font-extrabold text-rose-600 dark:text-rose-400 mt-1">
                Rp {{ number_format($totalNominalBelum, 0, ',', '.') }}
            </p>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                Harap segera lakukan pembayaran sebelum tenggat waktu berakhir.
            </p>
        </div>
        <a href="{{ route('mahasiswa.tagihan') }}"
            class="inline-flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm px-5 py-3 rounded-2xl shadow-lg shadow-rose-600/25 hover:shadow-rose-600/40 transition-all flex-shrink-0">
            <span>Bayar Sekarang</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>
    @else
    <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/60 rounded-3xl p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 shadow-lg shadow-emerald-500/25">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div>
            <h3 class="text-base font-bold text-emerald-900 dark:text-emerald-200">Semua Tagihan Bebas / Lunas</h3>
            <p class="text-xs text-emerald-700 dark:text-emerald-400 mt-0.5">Terima kasih! Tidak ada kewajiban pembayaran yang tertunda saat ini.</p>
        </div>
    </div>
    @endif

    <!-- Recent Tagihan List Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <div>
                <h2 class="font-bold text-base text-slate-900 dark:text-white">Tagihan Terbaru</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Ringkasan tagihan teratas Anda</p>
            </div>
            <a href="{{ route('mahasiswa.tagihan') }}"
                class="inline-flex items-center gap-1.5 text-indigo-600 dark:text-indigo-400 text-xs font-bold hover:underline">
                <span>Lihat semua</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="divide-y divide-slate-100 dark:divide-slate-800/60">
            @forelse($tagihans->take(3) as $tagihan)
            <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">
                            {{ $tagihan->paymentType->nama ?? '-' }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            {{ $tagihan->semester_nama }} • Jatuh tempo: <span class="font-medium text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('d M Y') }}</span>
                        </p>
                    </div>
                </div>

                <div class="flex sm:flex-col items-center sm:items-end justify-between gap-1">
                    <p class="font-extrabold text-slate-900 dark:text-white text-base">
                        Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}
                    </p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                        {{ $tagihan->status === 'lunas' ? 'bg-emerald-100 dark:bg-emerald-950/70 text-emerald-700 dark:text-emerald-300' :
                           ($tagihan->status === 'pending' ? 'bg-amber-100 dark:bg-amber-950/70 text-amber-700 dark:text-amber-300' :
                           'bg-rose-100 dark:bg-rose-950/70 text-rose-700 dark:text-rose-300') }}">
                        {{ $tagihan->status === 'belum_bayar' ? 'Belum Bayar' : ucfirst($tagihan->status) }}
                    </span>
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center text-slate-400 dark:text-slate-500 text-sm">
                Belum ada data tagihan.
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection