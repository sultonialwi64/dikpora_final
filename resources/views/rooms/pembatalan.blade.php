<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembatalan Peminjaman Ruangan</title>
    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
                            <h3>Pembatalan Peminjaman Ruangan</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            @include('partials.current-time')
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Pembatalan Peminjaman</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Pembatalan Peminjaman</h5>
                        </div>
                        <div class="card-body">
                            <!-- Search Bar and Add Booking Button -->
                            <div class="d-flex justify-content-between mb-4">
                                <!-- Search Bar -->
                                <input type="text" class="form-control w-50" id="searchBar" placeholder="Cari ruangan...">
                            </div>

                            {{-- <!-- Tampilkan pesan sukses -->
                            @if (session('success'))
                                <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- Tampilkan pesan error -->
                            @if ($errors->has('error'))
                                <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                                    {{ $errors->first('error') }}
                                </div>
                            @endif --}}

                            <!-- Status Peminjaman dalam bentuk tabel -->
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Ruangan</th>
                                            <th>Agenda</th>
                                            <th>Tanggal</th>
                                            <th>Jam</th>
                                            <th>Status Peminjaman</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bookings as $index => $booking)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $booking->room->name }}</td>
                                                <td>{{ $booking->agenda }}</td>
                                                <td>{{ $booking->formatted_booking_date }}</td>
                                                <td>{{ $booking->jam_awal }} - {{ $booking->jam_akhir }}</td>
                                                <td>
                                                    @if($booking->status === 'accepted')
                                                        <span class="badge badge-success">Disetujui</span>
                                                    @elseif($booking->status === 'pending')
                                                        <span class="badge badge-warning">Menunggu</span>
                                                    @else
                                                        <span class="badge badge-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <!-- Button Informasi Pengajuan -->
                                                    <button 
                                                        class="btn btn-info btn-sm" 
                                                        data-toggle="modal" 
                                                        data-target="#infoModal{{ $booking->id }}"
                                                        title="Informasi Pengajuan">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                
                                                    <!-- Button Batalkan Pengajuan -->
                                                    <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button 
                                                            type="submit" 
                                                            class="btn btn-danger btn-sm" 
                                                            title="Batalkan Pengajuan"
                                                            onclick="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')">
                                                            <i class="fas fa-times-circle"></i>
                                                        </button>
                                                    </form>
                                                </td>                                                
                                            </tr>
                                        @endforeach
                                        @foreach ($bookings as $booking)
                                        <div class="modal fade" id="infoModal{{ $booking->id }}" tabindex="-1" aria-labelledby="infoModalLabel{{ $booking->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="infoModalLabel{{ $booking->id }}">Informasi Pengajuan</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Nama Ruangan:</strong> {{ $booking->room->name }}</p>
                                                        <p><strong>Agenda:</strong> {{ $booking->agenda }}</p>
                                                        <p><strong>Jumlah Peserta:</strong> {{ $booking->jumlah_peserta }}</p>
                                                        <p><strong>Tanggal:</strong> {{ $booking->formatted_booking_date }}</p>
                                                        <p><strong>Jam:</strong> {{ $booking->jam_awal }} - {{ $booking->jam_akhir }}</p>
                                                        <p><strong>Status:</strong> 
                                                            @if($booking->status === 'accepted')
                                                                Disetujui
                                                            @elseif($booking->status === 'pending')
                                                                Menunggu
                                                            @else
                                                                Ditolak
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
</body>
</html>
