<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Laporan Peminjaman</h1>
    <p>Periode: {{ $start_date ?? 'Semua' }} - {{ $end_date ?? 'Semua' }}</p>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Ruang</th>
                <th>Tanggal Peminjaman</th>
                <th>Jam</th>
                <th>Nama Pemohon</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bookings as $index => $booking)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $booking->room->name }}</td>
                    <td>{{ $booking->booking_date }}</td>
                    <td>{{ $booking->jam_awal }} - {{ $booking->jam_akhir }}</td>
                    <td>{{ $booking->booked_by }}</td>
                    <td>{{ ucfirst($booking->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
