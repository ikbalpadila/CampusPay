@extends('layouts.mahasiswa')
@section('title', 'Pembayaran Tagihan')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <!-- Back link -->
    <div>
        <a href="{{ route('mahasiswa.tagihan') }}"
            class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 text-xs font-semibold transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Daftar Tagihan</span>
        </a>
    </div>

    <!-- Page Title -->
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white">Pembayaran Virtual Account</h1>
        <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-0.5">Selesaikan pembayaran secara otomatis melalui Virtual Account</p>
    </div>

    <!-- Info Tagihan Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-sm">
        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Detail Tagihan
        </h2>

        <div class="space-y-3 text-sm divide-y divide-slate-100 dark:divide-slate-800/60">
            <div class="flex justify-between items-center pt-2">
                <span class="text-slate-500 dark:text-slate-400">Jenis Pembayaran</span>
                <span class="font-bold text-slate-900 dark:text-white">
                    {{ $tagihan->paymentType->nama ?? '-' }}
                </span>
            </div>
            <div class="flex justify-between items-center pt-2">
                <span class="text-slate-500 dark:text-slate-400">Semester</span>
                <span class="font-medium text-slate-800 dark:text-slate-200">{{ $tagihan->semester_nama }}</span>
            </div>
            <div class="flex justify-between items-center pt-2">
                <span class="text-slate-500 dark:text-slate-400">Nominal Pembayaran</span>
                <span class="font-extrabold text-lg text-indigo-600 dark:text-indigo-400">
                    Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}
                </span>
            </div>
            <div class="flex justify-between items-center pt-2">
                <span class="text-slate-500 dark:text-slate-400">Tenggat Waktu (Jatuh Tempo)</span>
                <span class="font-medium text-slate-800 dark:text-slate-200">
                    {{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('d M Y') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Virtual Account Section -->
    @if($vaData)
    <div class="bg-gradient-to-br from-indigo-500/10 via-indigo-500/5 to-transparent dark:from-indigo-950/50 dark:via-slate-900 border-2 border-indigo-500/40 dark:border-indigo-500/50 rounded-3xl p-6 shadow-md space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-sm text-indigo-900 dark:text-indigo-200 uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Nomor Virtual Account
            </h2>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-600 text-white uppercase tracking-wider">Aktif</span>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-indigo-200 dark:border-indigo-800/80 p-5 text-center relative group">
            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Nomor Rekening VA</p>
            <p id="va-number" class="text-2xl sm:text-3xl font-extrabold tracking-widest font-mono text-indigo-600 dark:text-indigo-400">
                {{ $vaData['nomor_va'] }}
            </p>

            <button type="button" onclick="copyVANumber()"
                class="mt-3 inline-flex items-center gap-1.5 bg-indigo-50 dark:bg-indigo-950/80 hover:bg-indigo-100 dark:hover:bg-indigo-900 text-indigo-600 dark:text-indigo-300 font-bold text-xs px-4 py-2 rounded-xl transition border border-indigo-200 dark:border-indigo-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                <span id="copy-btn-text">Salin Nomor VA</span>
            </button>
        </div>

        <div class="space-y-2 text-xs pt-1">
            <div class="flex justify-between items-center text-slate-600 dark:text-slate-400">
                <span>Nominal Transfer Persis</span>
                <span class="font-bold text-rose-600 dark:text-rose-400 text-sm">
                    Rp {{ number_format($vaData['nominal'], 0, ',', '.') }}
                </span>
            </div>
            <div class="flex justify-between items-center text-slate-600 dark:text-slate-400">
                <span>Kadaluarsa Pada</span>
                <span class="font-semibold text-slate-800 dark:text-slate-200">
                    {{ \Carbon\Carbon::parse($vaData['expired_at'])->format('d M Y H:i') }} WIB
                </span>
            </div>
        </div>

        <div class="bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-900/60 rounded-2xl p-4 text-xs text-amber-800 dark:text-amber-300 flex gap-3 items-start">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div class="leading-relaxed">
                <strong class="font-bold">Penting:</strong> Pastikan nominal yang Anda transfer <strong>TEPAT SAMA</strong> hingga digit terakhir. Pembayaran akan dikonfirmasi secara otomatis oleh sistem dalam beberapa detik setelah transfer berhasil.
            </div>
        </div>
    </div>

    <script>
        function copyVANumber() {
            const vaNum = document.getElementById('va-number').innerText.trim();
            navigator.clipboard.writeText(vaNum).then(() => {
                const btnText = document.getElementById('copy-btn-text');
                btnText.innerText = 'Tersalin!';
                setTimeout(() => {
                    btnText.innerText = 'Salin Nomor VA';
                }, 2000);
            });
        }
    </script>

    @else
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 text-center space-y-4 shadow-sm">
        <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-950/70 text-indigo-600 dark:text-indigo-400 mx-auto flex items-center justify-center">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <div>
            <h2 class="font-bold text-base text-slate-900 dark:text-white">Dapatkan Nomor Virtual Account</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-sm mx-auto">
                Klik tombol di bawah ini untuk membuat kode pembayaran Virtual Account yang dapat dibayar melalui ATM/Mobile Banking.
            </p>
        </div>

        <form method="POST" action="{{ route('mahasiswa.tagihan.generate-va', $tagihan->id) }}" class="pt-2">
            @csrf
            <button type="submit"
                class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-bold py-3.5 rounded-2xl text-sm shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 active:scale-[0.99] transition-all">
                Generate Virtual Account
            </button>
        </form>
    </div>
    @endif

</div>
@endsection