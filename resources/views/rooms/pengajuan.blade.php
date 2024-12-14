<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Peminjaman Ruangan</title>
    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>   
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
                            <h3>Pengajuan Peminjaman Ruangan</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            @include('partials.current-time')
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Pengajuan Peminjaman</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Pengajuan Peminjaman</h5>
                        </div>
                        <div class="card-body">
                            <!-- Search Bar and Add Booking Button -->
                            <div class="d-flex justify-content-between mb-4">
                                <!-- Search Bar -->
                                <input type="text" class="form-control w-50" id="searchBar" placeholder="Cari ruangan...">

                                <!-- Add Booking Button -->
                                <button class="btn btn-primary" data-toggle="modal" data-target="#addBookingModal">Tambah Peminjaman</button>
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
                                                    @elseif($booking->status === 'cancelled')
                                                        <span class="badge badge-secondary">Dibatalkan</span>
                                                    @else
                                                        <span class="badge badge-danger">Ditolak</span>
                                                    @endif
                                                </td>
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

    <!-- Modal for Adding Booking -->
    <div class="modal fade" id="addBookingModal" tabindex="-1" aria-labelledby="addBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBookingModalLabel">Tambah Peminjaman Ruangan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="bookingForm" action="/book" method="POST">
                        @csrf

                        <!-- Single Input Field with Autocomplete -->
                        <div class="form-group">
                            <label for="roomSearch">Cari Ruangan</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="roomSearch" 
                                name="room_name" 
                                placeholder="Ketik nama ruangan..." 
                                autocomplete="off" 
                                required
                            />
                            <input type="hidden" id="roomId" name="room_id" />
                        </div>
                        
                        <input type="hidden" name="booked_by" value="{{ auth()->user()->name }}">
                        <input type="hidden" name="user_email" value="{{ auth()->user()->email }}">

                        <!-- Date Input -->
                        <div class="form-group">
                            <label for="booking_date">Tanggal</label>
                            <input type="date" class="form-control" id="booking_date" name="booking_date" required>
                        </div>

                        <!-- Jam Awal dan Jam Akhir -->
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="jam_awal">Jam Awal</label>
                                    <select class="form-control" id="jam_awal" name="jam_awal" required></select>
                                </div>
                                <div class="col-md-6">
                                    <label for="jam_akhir">Jam Akhir</label>
                                    <select class="form-control" id="jam_akhir" name="jam_akhir" required></select>
                                </div>
                            </div>
                        </div>

                        <!-- Jumlah Peserta -->
                        <div class="form-group">
                            <label for="jumlah_peserta">Jumlah Peserta <span id="roomCapacityLabel">-</span> orang</label>
                            <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta" required placeholder="Masukkan jumlah peserta">
                        </div>

                        <!-- Agenda -->
                        <div class="form-group">
                            <label for="agenda">Agenda</label>
                            <textarea class="form-control" id="agenda" name="agenda" rows="3" required placeholder="Masukkan agenda kegiatan"></textarea>
                        </div>

                        <!-- Penanggung Jawab -->
                        <div class="form-group">
                            <label for="penanggung_jawab">Penanggung Jawab</label>
                            <input type="text" class="form-control" id="penanggung_jawab" name="penanggung_jawab" required placeholder="Masukkan nama penanggung jawab">
                        </div>

                        <div id="availabilityMessage" class="mt-2 mb-2"></div>
                        <button type="submit" class="btn btn-primary" id="submitButton">Ajukan Peminjaman</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery UI for Autocomplete -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <script>
        $(document).ready(function () {
            // Data untuk autocomplete
            const rooms = @json($rooms->map(fn($room) => ['id' => $room->id, 'name' => $room->name, 'capacity' => $room->capacity]));
            console.log(rooms);

            // Inisialisasi autocomplete
            $('#roomSearch').autocomplete({
                source: function (request, response) {
                    const results = rooms.filter(room => room.name.toLowerCase().includes(request.term.toLowerCase()));
                    response(results.map(room => ({
                        label: room.name,
                        value: room.name,
                        id: room.id,
                        capacity: room.capacity
                    })));
                },
                minLength: 1,
                select: function (event, ui) {
                    $('#roomId').val(ui.item.id); // Set hidden input dengan ID ruangan
                    $('#roomCapacityLabel').text(ui.item.capacity); // Tampilkan kapasitas di label
                },
                appendTo: "#addBookingModal" // Pastikan dropdown muncul di modal
            });

            // Tambahkan log untuk memastikan dropdown muncul
            $(document).on('mousedown', function () {
                console.log($(".ui-autocomplete").html());
            });

            // Kosongkan label kapasitas jika input dihapus
            $('#roomSearch').on('input', function () {
                if (!$(this).val()) {
                    $('#roomCapacityLabel').text('-'); // Reset kapasitas
                }
            });

            // Menetapkan interval waktu 30 menit antara 08:00 hingga 17:00
            function generateTimeOptions() {
                var start = 8;  // Jam mulai 08:00
                var end = 15;   // Jam selesai 17:00
                var interval = 30; // Interval 30 menit
                var options = [];

                for (var hour = start; hour < end; hour++) {
                    for (var min = 0; min < 60; min += interval) {
                        var time = (hour < 10 ? '0' : '') + hour + ':' + (min < 10 ? '0' : '') + min;
                        options.push(time);
                    }
                }
                options.push('15:00')
                return options;
            }

            var timeOptions = generateTimeOptions();

            // Menetapkan opsi waktu pada input "Jam Awal" dan "Jam Akhir"
            $('#jam_awal, #jam_akhir').each(function() {
                var $this = $(this);
                $this.empty();  // Kosongkan pilihan yang ada
                timeOptions.forEach(function(time) {
                    $this.append('<option value="' + time + '">' + time + '</option>');
                });
            });

            // Set default values untuk Jam Awal dan Jam Akhir
            $('#jam_awal').val('08:00');  // Jam awal default
            $('#jam_akhir').val('08:30'); // Jam akhir default

            // Pastikan Jam Akhir tidak kurang dari Jam Awal
            $('#jam_awal, #jam_akhir').on('change', function() {
                var jamAwal = $('#jam_awal').val();
                var jamAkhir = $('#jam_akhir').val();
                if (jamAkhir <= jamAwal) {
                    // Jika Jam Akhir <= Jam Awal, set Jam Akhir menjadi lebih dari Jam Awal
                    var newJamAkhir = incrementTime(jamAwal);
                    $('#jam_akhir').val(newJamAkhir);
                }
            });

            // Fungsi untuk increment waktu 30 menit
            function incrementTime(time) {
                var [hour, minute] = time.split(':').map(Number);
                minute += 30;
                if (minute >= 60) {
                    minute = 0;
                    hour++;
                }
                if (hour < 10) hour = '0' + hour;
                if (minute < 10) minute = '0' + minute;
                return hour + ':' + minute;
            }
            
            // Check availability when inputs change
            const form = $('#bookingForm');
            const availabilityMessage = $('#availabilityMessage');
            const submitButton = $('#submitButton');

            form.find('#roomSearch, #booking_date, #jam_awal, #jam_akhir, #jumlah_peserta').on('change', function () {
                const room_id = $('#roomId').val();
                const booking_date = $('#booking_date').val();
                const start_time = $('#jam_awal').val();
                const end_time = $('#jam_akhir').val();
                const jumlah_peserta = $('#jumlah_peserta').val();

                if (room_id && booking_date && start_time && end_time && jumlah_peserta) {
                    $.ajax({
                        url: "{{ route('checkAvailability') }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            room_id,
                            booking_date,
                            jam_awal: start_time,
                            jam_akhir: end_time,
                            jumlah_peserta
                        },
                        success: function (response) {
                            if (response.available) {
                                availabilityMessage.html('<span class="text-success">' + response.message + '</span>');
                                submitButton.prop('disabled', false);
                            } else {
                                availabilityMessage.html('<span class="text-danger">' + response.message + '</span>');
                                submitButton.prop('disabled', true);
                            }
                        },
                        error: function (xhr) {
                            console.error(xhr.responseText);
                            availabilityMessage.html('<span class="text-danger">Terjadi kesalahan saat mengecek ketersediaan.</span>');
                            submitButton.prop('disabled', true);
                        }
                    });
                } else {
                    availabilityMessage.html('');
                    submitButton.prop('disabled', true);
                }
            });
        });
    </script>

    <script src="assets/static/js/components/dark.js"></script>
    <script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/compiled/js/app.js"></script>

</body>
</html>
