<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Booking</title>
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
        <div class="subtitle">Laporan Booking Fasilitas</div>
        <div>Periode: {{ date('d/m/Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Booking</th>
                <th>Pemesan</th>
                <th>Fasilitas</th>
                <th>Jam</th>
                <th>Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_booking)->format('d/m/Y') }}</td>
                <td>{{ $item->user_name ?? $item->user->name ?? '-' }}</td>
                <td>{{ $item->item }}</td>
                <td>{{ date('H:i', strtotime($item->jam_mulai)) }} - {{ date('H:i', strtotime($item->jam_selesai)) }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>
                    @if($item->status == 'pending') Pending
                    @elseif($item->status == 'confirmed') Dikonfirmasi
                    @elseif($item->status == 'cancelled') Dibatalkan
                    @else Selesai
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
