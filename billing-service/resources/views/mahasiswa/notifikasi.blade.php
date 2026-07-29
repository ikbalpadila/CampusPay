@extends('layouts.mahasiswa')
@section('title', 'Notifikasi')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white">Notifikasi</h1>
            <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-0.5">Pemberitahuan resmi terkait tagihan dan transaksi pembayaran Anda</p>
        </div>
        @if(count($notifications) > 0)
        <form method="POST" action="{{ route('mahasiswa.notifikasi.mark-read') }}">
            @csrf
            <button type="submit"
                class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-indigo-600 dark:text-indigo-400 text-xs font-bold px-3.5 py-2 rounded-xl transition border border-slate-200/80 dark:border-slate-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Tandai Semua Dibaca</span>
            </button>
        </form>
        @endif
    </div>

    <!-- Notification Cards -->
    <div class="space-y-3">
        @forelse($notifications as $notif)
        <div class="bg-white dark:bg-slate-900 rounded-3xl border p-5 shadow-sm transition-all
            {{ !$notif['is_read'] 
                ? 'border-l-4 border-l-indigo-600 dark:border-l-indigo-500 border-indigo-200/80 dark:border-slate-800 bg-indigo-50/20 dark:bg-slate-900/90' 
                : 'border-slate-200/80 dark:border-slate-800' }}">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 mt-0.5
                        {{ !$notif['is_read'] ? 'bg-indigo-100 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-slate-900 dark:text-white text-sm md:text-base">
                                {{ $notif['title'] }}
                            </h3>
                            @if(!$notif['is_read'])
                            <span class="inline-block w-2 h-2 rounded-full bg-indigo-600 dark:bg-indigo-400 animate-pulse"></span>
                            @endif
                        </div>
                        <p class="text-xs md:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            {{ $notif['message'] }}
                        </p>
                    </div>
                </div>

                <div class="text-right flex-shrink-0">
                    <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($notif['created_at'])->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 mx-auto flex items-center justify-center mb-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-800 dark:text-slate-200">Tidak Ada Notifikasi</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Semua pemberitahuan baru akan ditampilkan di halaman ini.</p>
        </div>
        @endforelse
    </div>

</div>
@endsection