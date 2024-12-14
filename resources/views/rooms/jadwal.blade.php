<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal</title>
    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    @include('partials.sidebar')
    <script src="assets/static/js/initTheme.js"></script>
    <div id="app">
        @include('partials.topbar')
        <div id="main">
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Jadwal Peminjaman Ruang</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            @include('partials.current-time')
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Status Peminjaman</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Jadwal Peminjaman</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach ($bookings as $booking)
                            <div class="list-group-item">
                                <h5 class="mb-1">{{ $booking->room->name }}</h5>
                                <p class="mb-1">
                                    Tanggal: {{ $booking->formatted_booking_date }} <br>
                                    Jam: {{ $booking->jam_awal }} - {{ $booking->jam_akhir }}
                                </p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @include('partials.footer')
    </div>
    <script src="assets/static/js/components/dark.js"></script>
    <script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/compiled/js/app.js"></script>

    <script>
        // Fungsi untuk menampilkan waktu saat ini
        function updateTime() {
            var now = new Date();
            var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            var dateString = now.toLocaleDateString('id-ID', options); // Format tanggal dan hari
            var timeString = now.toLocaleTimeString('id-ID'); // Format waktu

            document.getElementById('current-time').innerHTML = dateString + ' | ' + timeString;
        }

        // Memperbarui waktu setiap detik
        setInterval(updateTime, 1000);

        // Menampilkan waktu pertama kali ketika halaman dimuat
        updateTime();
    </script>
</body>
</html>
