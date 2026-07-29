<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #1E4D8C; }
        .header p { margin: 4px 0; color: #555; }
        .stat-grid { display: flex; gap: 12px; margin: 20px 0; }
        .stat-box { flex: 1; padding: 12px; border-radius: 6px; text-align: center; }
        .stat-box h3 { margin: 0 0 6px; font-size: 22px; }
        .stat-box p { margin: 0; font-size: 11px; }
        .box-blue  { background: #EAF0FA; color: #1E4D8C; }
        .box-green { background: #EAF7EF; color: #1A6B3C; }
        .box-amber { background: #FFF8E1; color: #7A4F00; }
        .box-red   { background: #FDECEA; color: #B71C1C; }
        .footer { margin-top: 30px; font-size: 10px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $title }}</h2>
        <p>{{ $subtitle }}</p>
        <p>Digenerate pada: {{ $generated_at }}</p>
    </div>

    <table style="width:100%;border-collapse:collapse;margin-top:16px">
        <tr>
            <td style="padding:12px;background:#EAF0FA;border-radius:6px;text-align:center;width:25%">
                <div style="font-size:24px;font-weight:bold;color:#1E4D8C">{{ $summary['total'] }}</div>
                <div style="font-size:11px;color:#555">Total Tagihan</div>
            </td>
            <td style="width:2%"></td>
            <td style="padding:12px;background:#EAF7EF;border-radius:6px;text-align:center;width:25%">
                <div style="font-size:24px;font-weight:bold;color:#1A6B3C">{{ $summary['lunas'] }}</div>
                <div style="font-size:11px;color:#555">Lunas</div>
            </td>
            <td style="width:2%"></td>
            <td style="padding:12px;background:#FFF8E1;border-radius:6px;text-align:center;width:25%">
                <div style="font-size:24px;font-weight:bold;color:#7A4F00">{{ $summary['pending'] }}</div>
                <div style="font-size:11px;color:#555">Pending</div>
            </td>
            <td style="width:2%"></td>
            <td style="padding:12px;background:#FDECEA;border-radius:6px;text-align:center;width:25%">
                <div style="font-size:24px;font-weight:bold;color:#B71C1C">{{ $summary['belum_bayar'] }}</div>
                <div style="font-size:11px;color:#555">Belum Bayar</div>
            </td>
        </tr>
    </table>

    <table style="width:100%;border-collapse:collapse;margin-top:20px">
        <tr style="background:#1E4D8C;color:white">
            <th style="padding:8px">Keterangan</th>
            <th style="padding:8px;text-align:right">Nilai</th>
        </tr>
        <tr>
            <td style="padding:8px;border-bottom:1px solid #ddd">Total Pemasukan (Transaksi Sukses)</td>
            <td style="padding:8px;border-bottom:1px solid #ddd;text-align:right;font-weight:bold;color:#1A6B3C">
                Rp {{ number_format($total_pemasukan, 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td style="padding:8px;border-bottom:1px solid #ddd">Total Tagihan Lunas</td>
            <td style="padding:8px;border-bottom:1px solid #ddd;text-align:right">{{ $summary['lunas'] }}</td>
        </tr>
        <tr>
            <td style="padding:8px;border-bottom:1px solid #ddd">Total Tagihan Belum Bayar</td>
            <td style="padding:8px;border-bottom:1px solid #ddd;text-align:right;color:#B71C1C">{{ $summary['belum_bayar'] }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>Laporan ini digenerate otomatis oleh sistem CampusPay</p>
        <p>Universitas Muhammadiyah Banten</p>
    </div>
</body>
</html>