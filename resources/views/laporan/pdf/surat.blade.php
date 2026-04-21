<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Surat</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 12px; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; }
        .subtitle { font-size: 14px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; font-weight: bold; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Cipakat Hub</div>
        <div class="subtitle">Laporan Pengajuan Surat</div>
        <div>Periode: {{ date('d/m/Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pemohon</th>
                <th>Jenis Surat</th>
                <th>Keterangan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($surats as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                <td>{{ $item->user_name ?? $item->user->name ?? '-' }}</td>
                <td>{{ $item->jenis_surat }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
                <td>
                    @if($item->status == 'pending') Pending
                    @elseif($item->status == 'diproses') Diproses
                    @elseif($item->status == 'selesai') Selesai
                    @else Ditolak
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>
