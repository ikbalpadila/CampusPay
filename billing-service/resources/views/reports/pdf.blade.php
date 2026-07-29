<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembayaran - CampusPay</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #1f2937;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header table {
            width: 100%;
        }
        .header-title {
            font-size: 20px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 0;
            text-transform: uppercase;
        }
        .header-subtitle {
            font-size: 12px;
            color: #4b5563;
            margin: 2px 0 0 0;
        }
        .meta-info {
            margin-bottom: 20px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
        }
        .meta-info table {
            width: 100%;
            font-size: 11px;
        }
        .meta-info td {
            padding: 3px 0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .data-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #1e3a8a;
        }
        .data-table td {
            padding: 7px 10px;
            border: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-lunas { background-color: #dcfce7; color: #15803d; }
        .badge-pending { background-color: #fef3c7; color: #b45309; }
        .badge-belum_bayar { background-color: #fee2e2; color: #b91c1c; }
        
        .footer {
            margin-top: 40px;
            width: 100%;
        }
        .footer table {
            width: 100%;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-space {
            height: 60px;
        }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 12px; margin-bottom: 20px; border-radius: 8px; text-align: center;">
        <span style="font-weight: bold; color: #1e40af;">📌 Petunjuk:</span> Jendela cetak otomatis terbuka. Pilih <strong>"Save as PDF"</strong> pada tujuan printer untuk menyimpan dokumen sebagai PDF.
        <button onclick="window.print()" style="margin-left: 10px; background: #2563eb; color: white; border: none; padding: 6px 14px; border-radius: 6px; cursor: pointer;">Cetak / Simpan PDF</button>
    </div>

    <div class="header">
        <table>
            <tr>
                <td>
                    <h1 class="header-title">CAMPUSPAY — LAPORAN {{ strtoupper($reportType) }}</h1>
                    <p class="header-subtitle">Sistem Layanan Keuangan Digital Kampus Terdistribusi</p>
                </td>
                <td style="text-align: right; font-size: 11px; color: #6b7280;">
                    Tanggal Cetak: <strong>{{ now()->format('d/m/Y H:i') }}</strong>
                </td>
            </tr>
        </table>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td style="width: 15%;"><strong>Tipe Laporan:</strong></td>
                <td style="width: 35%;">{{ ucfirst($reportType) }}</td>
                <td style="width: 15%;"><strong>Total Recort:</strong></td>
                <td style="width: 35%;">{{ count($tagihans) }} Data</td>
            </tr>
            <tr>
                <td><strong>Total Nominal:</strong></td>
                <td colspan="3" style="color: #1e3a8a; font-weight: bold;">
                    Rp {{ number_format(collect($tagihans)->sum('nominal'), 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">NO</th>
                <th style="width: 22%;">NAMA MAHASISWA</th>
                <th style="width: 12%;">NIM</th>
                <th style="width: 16%;">JENIS TAGIHAN</th>
                <th style="width: 12%;">SEMESTER</th>
                <th style="width: 14%;">NOMINAL</th>
                <th style="width: 10%;">STATUS</th>
                <th style="width: 10%;">METODE</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tagihans as $index => $tagihan)
                @php
                    $trx = $transMap[$tagihan->id] ?? null;
                    $metode = $trx ? ($trx['metode'] === 'virtual_account' ? 'VA' : 'Manual') : '—';
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $tagihan->mahasiswa_nama }}</strong></td>
                    <td>{{ $tagihan->mahasiswa_nim }}</td>
                    <td>{{ $tagihan->paymentType->nama ?? '—' }}</td>
                    <td>{{ $tagihan->semester_nama }}</td>
                    <td>Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</td>
                    <td style="text-align: center;">
                        <span class="badge badge-{{ $tagihan->status }}">
                            {{ str_replace('_', ' ', $tagihan->status) }}
                        </span>
                    </td>
                    <td style="text-align: center;">{{ $metode }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #9ca3af; padding: 20px;">
                        Tidak ada data tagihan yang sesuai dengan filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <table>
            <tr>
                <td></td>
                <td class="signature-box">
                    <p>Tangerang, {{ now()->format('d F Y') }}</p>
                    <p><strong>Admin Keuangan</strong></p>
                    <div class="signature-space"></div>
                    <p><u>( Bagian Keuangan Kampus )</u></p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
