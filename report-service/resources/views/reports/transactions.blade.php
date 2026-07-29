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
        th { background: #1E4D8C; color: white; padding: 8px; text-align: left; }
        td { padding: 6px 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f5f5f5; }
        .footer { margin-top: 20px; font-size: 10px; color: #888; }
        .badge-success { color: #27500A; font-weight: bold; }
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
                <th>ID Transaksi</th>
                <th>Tagihan ID</th>
                <th>Nominal</th>
                <th>Metode</th>
                <th>Lamport Clock</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $i => $trx)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $trx['id'] }}</td>
                <td>{{ $trx['tagihan_id'] }}</td>
                <td>Rp {{ number_format($trx['nominal'], 0, ',', '.') }}</td>
                <td>{{ $trx['metode'] }}</td>
                <td>{{ $trx['lamport_clock'] }}</td>
                <td class="badge-success">{{ strtoupper($trx['status']) }}</td>
                <td>{{ \Carbon\Carbon::parse($trx['created_at'])->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total transaksi: {{ count($items) }}</p>
        <p>Total nominal: Rp {{ number_format(collect($items)->sum('nominal'), 0, ',', '.') }}</p>
        <p>Dokumen ini digenerate otomatis oleh sistem CampusPay</p>
    </div>
</body>
</html>