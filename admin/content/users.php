<?php
// Manajemen Admin
$query = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
$rows = mysqli_fetch_all($query, MYSQLI_ASSOC);

$alert_script = '';
if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    $alert_script = "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '{$alert['type']}',
                title: '{$alert['title']}',
                text: '{$alert['message']}',
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#28a745',
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
    ";
    unset($_SESSION['alert']);
}
?>

<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item active">Pengguna Admin</li>
                    </ol>
                </div>
                <h4 class="page-title">Manajemen Pengguna Admin</h4>
            </div>
        </div>
    </div>

    <!-- Kartu Petunjuk -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30 bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-2"><i class="mdi mdi-account-group text-primary mr-2"></i>Apa itu Pengguna Admin?</h5>
                            <p class="mb-0 text-muted">
                                <strong>Pengguna Admin</strong> adalah akun yang memiliki akses untuk masuk ke panel admin 
                                dan mengelola konten website. Anda dapat menambah, mengubah, atau menghapus pengguna admin.
                            </p>
                        </div>
                        <div class="col-md-4 text-center mt-3 mt-md-0">
                            <i class="mdi mdi-account-key text-primary" style="font-size: 60px;"></i>
                            <small class="d-block text-muted mt-2">Kelola Akses Admin</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu Konten -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mt-0 header-title">Daftar Admin</h4>
                            <p class="text-muted m-b-30 font-14">
                                Kelola pengguna yang dapat mengakses dashboard
                            </p>
                        </div>
                        <a href="?page=add_user" class="btn btn-success">
                            <i class="mdi mdi-account-plus"></i> Tambah Admin Baru
                        </a>
                    </div>

                    <?php if (count($rows) > 0): ?>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="20%">Username</th>
                                    <th width="30%">Email</th>
                                    <th width="20%">Dibuat</th>
                                    <th width="15%">Diubah</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rows as $key => $row): ?>
                                    <tr>
                                        <td><?php echo $key + 1; ?></td>
                                        <td>
                                            <strong><i class="mdi mdi-account text-primary mr-1"></i><?php echo htmlspecialchars($row['username']); ?></strong>
                                            <?php if (isset($_SESSION['id']) && $row['id'] == $_SESSION['id']): ?>
                                            <span class="badge badge-info">Anda</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><i class="mdi mdi-email-outline text-muted mr-1"></i><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><small><i class="mdi mdi-calendar text-muted mr-1"></i><?php echo date('d M Y H:i', strtotime($row['created_at'])); ?></small></td>
                                        <td>
                                            <?php if (!empty($row['updated_at'])): ?>
                                                <small><i class="mdi mdi-calendar-edit text-muted mr-1"></i><?php echo date('d M Y H:i', strtotime($row['updated_at'])); ?></small>
                                            <?php else: ?>
                                                <small class="text-muted">-</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="?page=edit_user&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary" title="Ubah">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <?php if (!isset($_SESSION['id']) || $row['id'] != $_SESSION['id']): ?>
                                            <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus"
                                                data-id="<?php echo $row['id']; ?>"
                                                data-username="<?php echo htmlspecialchars($row['username']); ?>">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                            <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-secondary" disabled title="Tidak bisa hapus diri sendiri">
                                                <i class="mdi mdi-delete-off"></i>
                                            </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="mdi mdi-account-off text-muted" style="font-size: 48px;"></i>
                        <h5 class="mt-3 text-muted">Belum Ada Admin</h5>
                        <p class="text-muted">Klik "Tambah Admin Baru" untuk membuat admin pertama.</p>
                        <a href="?page=add_user" class="btn btn-success mt-2">
                            <i class="mdi mdi-account-plus"></i> Tambah Admin Baru
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu Panduan -->
    <div class="row">
        <!-- Cara Penggunaan -->
        <div class="col-lg-4">
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-book-open-page-variant mr-2"></i>Cara Penggunaan</h5>
                </div>
                <div class="card-body">
                    <ol class="small mb-0">
                        <li class="mb-2">
                            <strong>Tambah Admin:</strong><br>
                            <small class="text-muted">Klik tombol hijau untuk menambah admin.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Ubah Admin:</strong><br>
                            <small class="text-muted">Klik ikon pensil untuk mengubah data.</small>
                        </li>
                        <li class="mb-0">
                            <strong>Hapus Admin:</strong><br>
                            <small class="text-muted">Klik ikon hapus (tidak bisa hapus diri sendiri).</small>
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Penjelasan Kolom -->
        <div class="col-lg-4">
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-table mr-2"></i>Penjelasan Kolom</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless small mb-0">
                        <tr>
                            <td><span class="badge badge-dark">Username</span></td>
                            <td>Nama login admin</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-primary">Email</span></td>
                            <td>Email untuk pemulihan</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-secondary">Dibuat</span></td>
                            <td>Tanggal akun dibuat</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-warning">Diubah</span></td>
                            <td>Terakhir diperbarui</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tips Keamanan -->
        <div class="col-lg-4">
            <div class="card m-b-30 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="mdi mdi-shield-lock mr-2"></i>Tips Keamanan</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2">Gunakan password yang kuat (min 8 karakter)</li>
                        <li class="mb-2">Kombinasikan huruf, angka & simbol</li>
                        <li class="mb-2">Jangan bagikan password kepada siapapun</li>
                        <li class="mb-0">Ganti password secara berkala</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $alert_script; ?>

<script>
// SweetAlert untuk konfirmasi hapus
document.querySelectorAll('.btn-delete').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const username = this.dataset.username;
        
        Swal.fire({
            icon: 'warning',
            title: 'Hapus Admin?',
            html: 'Anda akan menghapus akun admin:<br><strong>"' + username + '"</strong><br><br><small class="text-danger">Tindakan ini tidak dapat dibatalkan!</small>',
            showCancelButton: true,
            confirmButtonText: '<i class="mdi mdi-delete"></i> Ya, Hapus!',
            cancelButtonText: '<i class="mdi mdi-close"></i> Batal',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    html: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                window.location.href = '?page=delete_user&id=' + id + '&confirm=yes';
            }
        });
    });
});
</script>