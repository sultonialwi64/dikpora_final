<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Peminjaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
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
        th {
            background-color: #f2f2f2;
        }
        .report-title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="report-title">
        Laporan Peminjaman Ruangan Bulan {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}
    </div>    
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Ruang</th>
                <th>Agenda</th>
                <th>Tanggal Peminjaman</th>
                <th>Jam</th>
                <th>Nama Pemohon</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($formattedBookings as $index => $booking)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $booking->room->name }}</td>
                    <td>{{ $booking->agenda }}</td>
                    <td>{{ $booking->formatted_booking_date }}</td>
                    <td>{{ $booking->jam_awal }} - {{ $booking->jam_akhir }}</td>
                    <td>{{ $booking->booked_by }}</td>
                    <td>{{ ucfirst($booking->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
