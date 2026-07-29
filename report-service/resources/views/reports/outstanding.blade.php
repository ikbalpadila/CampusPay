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
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background: #B71C1C; color: white; padding: 8px; text-align: left; }
        td { padding: 6px 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #fff5f5; }
        .footer { margin-top: 20px; font-size: 10px; color: #888; }
        .badge-danger { color: #B71C1C; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $title }}</h2>
        <p>{{ $subtitle }}</p>
        <p>Digenerate pada: {{ $generated_at }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Jenis Tagihan</th>
                <th>Semester</th>
                <th>Nominal</th>
                <th>Jatuh Tempo</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $i => $tagihan)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $tagihan['mahasiswa_nim'] }}</td>
                <td>{{ $tagihan['mahasiswa_nama'] }}</td>
                <td>{{ $tagihan['payment_type']['nama'] ?? '-' }}</td>
                <td>{{ $tagihan['semester_nama'] }}</td>
                <td>Rp {{ number_format($tagihan['nominal'], 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($tagihan['jatuh_tempo'])->format('d/m/Y') }}</td>
                <td class="badge-danger">BELUM BAYAR</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total mahasiswa menunggak: {{ count($items) }}</p>
        <p>Total tunggakan: Rp {{ number_format(collect($items)->sum('nominal'), 0, ',', '.') }}</p>
        <p>Dokumen ini digenerate otomatis oleh sistem CampusPay</p>
    </div>
</body>
</html>