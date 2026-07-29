@extends('layouts.mahasiswa')
@section('title', 'Upload Bukti Transfer')

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
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white">Upload Bukti Transfer</h1>
        <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-0.5">Unggah resi/bukti pembayaran manual untuk verifikasi admin</p>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-sm space-y-5">
        
        <!-- Summary Box -->
        <div class="bg-slate-50 dark:bg-slate-800/60 rounded-2xl p-4 border border-slate-100 dark:border-slate-700/60 text-sm flex items-center justify-between">
            <div>
                <p class="font-bold text-slate-900 dark:text-white">
                    {{ $tagihan->paymentType->nama ?? '-' }}
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    {{ $tagihan->semester_nama }}
                </p>
            </div>
            <div class="text-right">
                <span class="text-[10px] uppercase font-bold text-slate-400">Total Nominal</span>
                <p class="font-extrabold text-indigo-600 dark:text-indigo-400 text-base">
                    Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <form method="POST"
              action="{{ route('mahasiswa.tagihan.upload-bukti.post', $tagihan->id) }}"
              enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Custom File Dropzone Input -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                    Foto / File Resi Transfer
                </label>
                
                <div class="relative border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-2xl p-6 text-center hover:border-indigo-500 dark:hover:border-indigo-500 bg-slate-50/50 dark:bg-slate-800/40 transition group">
                    <input type="file" id="bukti_transfer" name="bukti_transfer"
                        accept=".jpg,.jpeg,.png,.pdf"
                        onchange="handleFileSelected(this)"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                        required>
                    
                    <div class="space-y-2">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 mx-auto flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200" id="file-label-title">Pilih file atau tarik ke sini</p>
                            <p class="text-xs text-slate-400 mt-1">Format didukung: JPG, PNG, PDF (Maksimal 2MB)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Notice Box -->
            <div class="bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-200/60 dark:border-indigo-800/60 rounded-2xl p-4 text-xs text-indigo-900 dark:text-indigo-300 flex items-start gap-3">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="leading-relaxed">
                    Pastikan resi atau foto transaksi menampilkan <strong>Nama Pengirim</strong>, <strong>Nominal Transfer</strong>, dan <strong>Tanggal Transfer</strong> dengan jelas agar verifikasi dapat diproses lebih cepat.
                </div>
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-bold py-3.5 rounded-2xl text-sm shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 active:scale-[0.99] transition-all">
                Upload Bukti Transfer
            </button>
        </form>
    </div>

</div>

<script>
    function handleFileSelected(input) {
        const title = document.getElementById('file-label-title');
        if (input.files && input.files[0]) {
            title.innerHTML = '<span class="text-indigo-600 dark:text-indigo-400">File terpilih:</span> ' + input.files[0].name;
        }
    }
</script>
@endsection