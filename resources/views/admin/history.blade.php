<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Peminjaman</title>
    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
<body>
    @include('partials.admin-sidebar')
    <script src="assets/static/js/initTheme.js"></script>
    <div id="app">
        @include('partials.topbar')
        <div id="main">
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Histori Peminjaman</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            @include('partials.current-time')
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Histori Peminjaman</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <h5 class="card-title mb-2">Histori Peminjaman</h5>
                                <div class="d-flex flex-wrap align-items-center">
                                    <!-- Filter Bulan dan Tahun -->
                                    <form method="get" action="{{ route('history') }}" class="d-flex align-items-center mr-3 mb-2">
                                        <select name="month" class="form-control mr-2">
                                            @foreach(range(1, 12) as $m)
                                                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </form>
                                    <a href="{{ route('download.history', ['month' => $m]) }}" class="btn btn-primary mb-2">
                                        Download Laporan (.pdf)
                                    </a>                                    
                                </div>
                            </div>                            
                        </div>                        
                        <div class="card-body">
                            <!-- Tabel Daftar Peminjaman -->
                            <div class="table-responsive">
                                <table class="table table-striped" id="bookingsTable">
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
                                                <td>{{ $booking->formatted_booking_date }}</td>
                                                <td>{{ $booking->jam_awal }} - {{ $booking->jam_akhir }}</td>
                                                <td>{{ $booking->booked_by }}</td>
                                                <td>{{ ucfirst($booking->status) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>                            
                        </div>
                    </div>
                </section>
            </div>
        </div>
        @include('partials.footer')
    </div>

    <script src="assets/static/js/components/dark.js"></script>
    <script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/compiled/js/app.js"></script>

    <script>
        $(document).ready(function () {
            $('#bookingsTable').DataTable({
                pageLength: 10, // Maksimal 10 peminjaman per halaman
                lengthChange: false, // Nonaktifkan dropdown jumlah data per halaman
                language: {
                    search: "Cari Peminjaman:",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya",
                    },
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                },
            });
        });
    </script>

    <script src="assets/static/js/initTheme.js"></script>
    <script src="assets/compiled/js/app.js"></script>
</body>
</html>
