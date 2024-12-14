<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender Interaktif</title>
    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
    <style>
        /* Styling untuk Kalender agar mendukung Dark Mode */
        #calendar-container {
            max-width: 1100px;
            margin: 0 0;
        }
    </style>    
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
                            <h3>Kalender</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            @include('partials.current-time')
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Kalender</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Kalender Agenda</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h6>Keterangan:</h6>
                                    <div class="d-flex">
                                        <div class="d-flex align-items-center me-4">
                                            <div style="width: 20px; height: 20px; background-color: #1BA1E2; margin-right: 10px; border-radius: 5px;"></div>
                                            <span>Baru</span>
                                        </div>
                                        <div class="d-flex align-items-center me-4">
                                            <div style="width: 20px; height: 20px; background-color: #647687; margin-right: 10px; border-radius: 5px;"></div>
                                            <span>Selesai</span>
                                        </div>
                                        <div class="d-flex align-items-center me-4">
                                            <div style="width: 20px; height: 20px; background-color: #7F00FF; margin-right: 10px; border-radius: 5px;"></div>
                                            <span>Berlangsung</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div style="width: 20px; height: 20px; background-color: #28A745; margin-right: 10px; border-radius: 5px;"></div>
                                            <span>Terkonfirmasi</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="calendar-container">
                                <div id="calendar"></div>
                            </div>

                            <!-- Modal Dialog -->
                            <div class="modal fade" id="agendaModal" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Agenda pada Tanggal: <span id="modalDate"></span></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <ul id="agendaList"></ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        @include('partials.footer')
    </div>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const agendaModal = new bootstrap.Modal(document.getElementById('agendaModal')); // Bootstrap modal instance
            const modalDate = document.getElementById('modalDate');
            const agendaList = document.getElementById('agendaList');
    
            console.log("Initializing calendar..."); // Log saat kalender diinisialisasi
            
            // Periksa apakah dark mode aktif
            function isDarkMode() {
                return document.body.classList.contains('dark-mode');
            }

            var events = []
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',  // Menggunakan tampilan grid minggu dengan waktu
                events: '/api/admin-get-all-agenda',  // Endpoint untuk mendapatkan semua agenda
                eventDisplay: 'block',
                nowIndicator: true,  // Menampilkan indikator waktu saat ini
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'today'
                },
                dateClick: function (info) {
                    const selectedDate = info.dateStr;
                    console.log("Date clicked:", selectedDate); // Log tanggal yang diklik
                    modalDate.textContent = selectedDate;

                    // Ambil agenda dari server untuk tanggal yang diklik
                    fetch(`/api/admin-get-agenda?date=${selectedDate}`)
                        .then((response) => response.json())
                        .then((data) => {
                            console.log("Agenda data for date:", selectedDate, data); // Log data agenda yang diterima
                            agendaList.innerHTML = ''; // Hapus isi sebelumnya
                            if (data.length > 0) {
                                data.forEach((agenda) => {
                                    const li = document.createElement('li');
                                    li.textContent = `${agenda.jam_awal} - ${agenda.jam_akhir}: ${agenda.agenda} (oleh ${agenda.booked_by})`;
                                    agendaList.appendChild(li);
                                });
                            } else {
                                const li = document.createElement('li');
                                li.textContent = 'Tidak ada agenda pada tanggal ini.';
                                agendaList.appendChild(li);
                            }
                        })
                        .catch((error) => {
                            console.error('Error fetching agenda:', error); // Log error jika gagal fetch
                            agendaList.innerHTML = '<li>Terjadi kesalahan saat mengambil data.</li>';
                        });

                    // Tampilkan modal
                    agendaModal.show();
                }
            });

            console.log("Rendering calendar..."); // Log sebelum render kalender
            calendar.setOption('locale', 'id');
            calendar.render();
        });
    </script>            
    <script src="assets/static/js/components/dark.js"></script>
    <script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/compiled/js/app.js"></script>
</body>
</html>
