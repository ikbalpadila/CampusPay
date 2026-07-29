@extends('layouts.mahasiswa')
@section('title', 'Riwayat Transaksi')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-2">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white">Riwayat Transaksi</h1>
            <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-0.5">Catatan histori seluruh transaksi pembayaran Anda</p>
        </div>
    </div>

    @if(count($transactions) > 0)
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200/80 dark:border-slate-800 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    <tr>
                        <th class="px-6 py-4">ID Transaksi</th>
                        <th class="px-6 py-4">Nominal</th>
                        <th class="px-6 py-4">Metode Pembayaran</th>
                        <th class="px-6 py-4">Lamport Clock</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Tanggal & Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-slate-800 dark:text-slate-200">
                    @foreach($transactions as $trx)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                        <td class="px-6 py-4 font-mono font-bold text-slate-600 dark:text-slate-400 text-xs">
                            #{{ $trx['id'] }}
                        </td>
                        <td class="px-6 py-4 font-extrabold text-slate-900 dark:text-white">
                            Rp {{ number_format($trx['nominal'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ $trx['metode'] === 'virtual_account' ? 'Virtual Account' : 'Transfer Manual' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 bg-purple-50 dark:bg-purple-950/70 text-purple-700 dark:text-purple-300 text-[11px] font-bold px-2.5 py-0.5 rounded-full border border-purple-200/60 dark:border-purple-800/60 font-mono">
                                Clock #{{ $trx['lamport_clock'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                {{ $trx['status'] === 'success' 
                                    ? 'bg-emerald-100 dark:bg-emerald-950/70 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60' 
                                    : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">
                                {{ $trx['status'] === 'success' ? 'Berhasil' : ucfirst($trx['status']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($trx['created_at'])->format('d M Y H:i') }} WIB
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-12 text-center">
        <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 mx-auto flex items-center justify-center mb-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="text-base font-bold text-slate-800 dark:text-slate-200">Belum Ada Riwayat Transaksi</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Histori transaksi pembayaran yang pernah Anda lakukan akan ditampilkan di sini.</p>
    </div>
    @endif

</div>
@endsection