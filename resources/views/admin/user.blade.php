<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar User</title>
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
                            <h3>Daftar User</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            @include('partials.current-time')
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Daftar User</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Daftar User</h5>
                        </div>
                        <div class="card-body">
                            <!-- Tombol Tambah di ujung kanan -->
                            <div class="d-flex justify-content-end mb-3">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Tambah</button>
                            </div>
                            
                            <!-- Tabel Daftar User -->
                            <div class="table-responsive">
                                <table class="table table-striped" id="usersTable">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Nomor Pegawai</th>
                                            <th>Bidang</th>
                                            <th>Telepon</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $index => $user)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->no_pegawai }}</td>
                                                <td>{{ $user->bidang }}</td>
                                                <td>{{ $user->telp }}</td>
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

    <!-- Modal Tambah User -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="userName">Nama User</label>
                            <input type="text" class="form-control" id="userName" name="name" required placeholder="Masukkan nama user">
                        </div>
                        <div class="form-group">
                            <label for="userEmail">Email</label>
                            <input type="email" class="form-control" id="userEmail" name="email" required placeholder="Masukkan email user">
                        </div>
                        <div class="form-group">
                            <label for="userNoPegawai">Nomor Pegawai</label>
                            <input type="text" class="form-control" id="userNoPegawai" name="no_pegawai" required placeholder="Masukkan nomor pegawai">
                        </div>
                        <div class="form-group">
                            <label for="userBidang">Bidang</label>
                            <input type="text" class="form-control" id="userBidang" name="bidang" required placeholder="Masukkan bidang">
                        </div>
                        <div class="form-group">
                            <label for="userTelp">Telepon</label>
                            <input type="text" class="form-control" id="userTelp" name="telp" required placeholder="Masukkan nomor telepon">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#usersTable').DataTable({
                pageLength: 10, // Maksimal 10 user per halaman
                lengthChange: false, // Nonaktifkan dropdown jumlah data per halaman
                language: {
                    search: "Cari User:",
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
