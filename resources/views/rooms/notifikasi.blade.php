<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi</title>
    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 5px;
        }
        .status-pending {
            background-color: #E0A800;
            color: white;
        }
        .status-accepted {
            background-color: #28A745;
            color: white;
        }
        .status-cancelled {
            background-color: #6C757D;
            color: white;
        }
        .status-rejected {
            background-color: #d9534f;
            color: white;
        }
        .list-item {
            position: relative;
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
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
                            <h3>Notifikasi</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            @include('partials.current-time')
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Notifikasi</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Notifikasi Peminjaman</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @foreach ($formattedBookings as $booking)
                                <div class="list-item" data-toggle="modal" data-target="#bookingModal" 
                                     data-name="{{ $booking->room->name }}" 
                                     data-status="{{ ucfirst($booking->status) }}" 
                                     data-agenda="{{ $booking->agenda }}"
                                     data-jumlah="{{ $booking->jumlah_peserta }}"
                                     data-date="{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}" 
                                     data-time="{{ $booking->jam_awal }} - {{ $booking->jam_akhir }}" 
                                     data-penanggung="{{ $booking->penanggung_jawab }}">
                                    <!-- Menampilkan teks berdasarkan status -->
                                    <span class="status-badge 
                                        @if ($booking->status == 'pending') status-pending 
                                        @elseif ($booking->status == 'accepted') status-accepted
                                        @elseif ($booking->status == 'cancelled') status-cancelled
                                        @else status-rejected @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>

                                    <h5 class="mb-1">
                                        @if ($booking->status == 'pending')
                                            Pengajuan Peminjaman Ruangan Terkirim
                                        @elseif ($booking->status == 'accepted')
                                            Pengajuan Peminjaman Ruangan Dikonfirmasi
                                        @elseif ($booking->status == 'cancelled')
                                            Pengajuan Peminjaman Ruangan Dibatalkan
                                        @else
                                            Pengajuan Peminjaman Ruangan Ditolak
                                        @endif
                                    </h5>
                                    
                                    <!-- Menampilkan Tanggal, Jam, dan Updated At -->
                                    <p class="mb-1">
                                        {{ \Carbon\Carbon::parse($booking->updated_at)->translatedFormat('l, d F Y') }} | {{ \Carbon\Carbon::parse($booking->updated_at)->translatedFormat('H:i') }}
                                    </p>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- Modal for Booking Details -->
        <div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bookingModalLabel">Detail Peminjaman Ruangan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Nama Ruangan:</strong>
                                    <p id="modalRoomName"></p>
                                </div>
                                <div class="form-group">
                                    <strong>Tanggal & Jam:</strong>
                                    <p id="modalBookingDateAndTime">
                                        <span id="modalBookingDate"></span> 
                                        <span id="modalBookingTime"></span>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <strong>Jumlah Peserta:</strong>
                                    <p id="modalJumlah"></p>
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Agenda:</strong>
                                    <p id="modalAgenda"></p>
                                </div>
                                <div class="form-group">
                                    <strong>Penanggung Jawab:</strong>
                                    <p id="modalPenanggungJawab"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        @include('partials.footer')
    </div>

    <script src="assets/static/js/components/dark.js"></script>
    <script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/compiled/js/app.js"></script>

    <script>
        // Mengatur modal dengan data yang dikirim dari setiap item daftar
        $('#bookingModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // tombol yang memicu modal
            var roomName = button.data('name');
            var agenda = button.data('agenda');
            var date = button.data('date');
            var time = button.data('time');
            var jumlah = button.data('jumlah')
            var penanggung_jawab = button.data('penanggung');

            // Update isi modal dengan data yang dikirim
            $('#modalRoomName').text(roomName);
            $('#modalAgenda').text(agenda);
            $('#modalBookingDate').text(date);
            $('#modalBookingTime').text(time);
            $('#modalJumlah').text(jumlah);
            $('#modalPenanggungJawab').text(penanggung_jawab);
        });
    </script>
</body>
</html>
