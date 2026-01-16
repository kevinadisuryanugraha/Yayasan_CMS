<?php
// Manajemen Metode Pembayaran
$query = mysqli_query($conn, "SELECT * FROM payment_methods ORDER BY sort_order ASC, id DESC");
$rows = mysqli_fetch_all($query, MYSQLI_ASSOC);

// Cek apakah ada alert dari session
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
                showConfirmButton: false,
                timer: 1500
            });
        });
    </script>
    ";
    unset($_SESSION['alert']);
}

// Count by type
$count_bank = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM payment_methods WHERE type = 'bank'"))['cnt'] ?? 0;
$count_ewallet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM payment_methods WHERE type = 'ewallet'"))['cnt'] ?? 0;
$count_qris = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM payment_methods WHERE type = 'qris'"))['cnt'] ?? 0;
$count_total = $count_bank + $count_ewallet + $count_qris;

// Type labels and icons
$type_labels = [
    'bank' => ['label' => 'Bank Transfer', 'icon' => 'mdi-bank', 'color' => 'primary'],
    'ewallet' => ['label' => 'E-Wallet', 'icon' => 'mdi-wallet', 'color' => 'success'],
    'qris' => ['label' => 'QRIS', 'icon' => 'mdi-qrcode', 'color' => 'info']
];
?>

<div class="container-fluid">
    <!-- Judul Halaman dengan Breadcrumb -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
                        <li class="breadcrumb-item active">Metode Pembayaran</li>
                    </ol>
                </div>
                <h4 class="page-title">Manajemen Metode Pembayaran</h4>
            </div>
        </div>
    </div>

    <!-- Kartu Petunjuk (Header) -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30 bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-2"><i class="mdi mdi-credit-card-multiple text-primary mr-2"></i>Apa itu
                                Metode Pembayaran?</h5>
                            <p class="mb-0 text-muted">
                                <strong>Metode Pembayaran</strong> adalah pengaturan rekening bank, e-wallet, dan QRIS
                                yang akan ditampilkan saat user melakukan pembayaran (pendaftaran event berbayar,
                                donasi, dll).
                                Anda dapat menambahkan beberapa rekening bank, nomor e-wallet, serta QR Code QRIS.
                            </p>
                        </div>
                        <div class="col-md-4 text-center mt-3 mt-md-0">
                            <i class="mdi mdi-credit-card-settings-outline text-primary"
                                style="font-size: 80px; opacity: 0.5;"></i>
                            <small class="d-block text-muted mt-2">Kelola Metode Pembayaran</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card m-b-30">
                <div class="card-body text-center py-3">
                    <h2 class="mb-1 text-dark">
                        <?php echo $count_total; ?>
                    </h2>
                    <small class="text-muted"><i class="mdi mdi-format-list-bulleted mr-1"></i>Total Metode</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card m-b-30">
                <div class="card-body text-center py-3">
                    <h2 class="mb-1 text-primary">
                        <?php echo $count_bank; ?>
                    </h2>
                    <small class="text-muted"><i class="mdi mdi-bank mr-1"></i>Bank Transfer</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card m-b-30">
                <div class="card-body text-center py-3">
                    <h2 class="mb-1 text-success">
                        <?php echo $count_ewallet; ?>
                    </h2>
                    <small class="text-muted"><i class="mdi mdi-wallet mr-1"></i>E-Wallet</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card m-b-30">
                <div class="card-body text-center py-3">
                    <h2 class="mb-1 text-info">
                        <?php echo $count_qris; ?>
                    </h2>
                    <small class="text-muted"><i class="mdi mdi-qrcode mr-1"></i>QRIS</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu Konten Utama -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mt-0 header-title">Daftar Metode Pembayaran</h4>
                            <p class="text-muted m-b-30 font-14">
                                Kelola semua rekening bank, e-wallet, dan QRIS
                            </p>
                        </div>
                        <a href="?page=add_payment_method" class="btn btn-success">
                            <i class="mdi mdi-plus"></i> Tambah Metode Baru
                        </a>
                    </div>

                    <?php if (count($rows) > 0): ?>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="10%">Tipe</th>
                                        <th width="8%">Logo</th>
                                        <th width="15%">Nama</th>
                                        <th width="15%">No. Rekening/HP</th>
                                        <th width="15%">Nama Pemilik</th>
                                        <th width="8%">QR Code</th>
                                        <th width="8%">Status</th>
                                        <th width="8%">Urutan</th>
                                        <th width="8%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as $key => $row): ?>
                                        <?php $type_info = $type_labels[$row['type']] ?? $type_labels['bank']; ?>
                                        <tr>
                                            <td>
                                                <?php echo $key + 1; ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?php echo $type_info['color']; ?> p-2">
                                                    <i class="mdi <?php echo $type_info['icon']; ?> mr-1"></i>
                                                    <?php echo $type_info['label']; ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($row['icon']): ?>
                                                    <img src="../<?php echo $row['icon']; ?>" alt="Logo"
                                                        style="max-height: 35px; max-width: 60px;">
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><strong>
                                                    <?php echo htmlspecialchars($row['name']); ?>
                                                </strong></td>
                                            <td>
                                                <?php if ($row['account_number']): ?>
                                                    <code><?php echo htmlspecialchars($row['account_number']); ?></code>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['account_name'] ?? '-'); ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($row['qr_image']): ?>
                                                    <a href="../<?php echo $row['qr_image']; ?>" target="_blank"
                                                        class="btn btn-sm btn-outline-info">
                                                        <i class="mdi mdi-qrcode"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($row['is_active']): ?>
                                                    <span class="badge badge-success">✅ Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">❌ Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-light border">
                                                    <?php echo $row['sort_order']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="?page=edit_payment_method&id=<?php echo $row['id']; ?>"
                                                        class="btn btn-warning" title="Edit">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-delete"
                                                        data-id="<?php echo $row['id']; ?>"
                                                        data-name="<?php echo htmlspecialchars($row['name']); ?>" title="Hapus">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="mdi mdi-credit-card-off text-muted" style="font-size: 64px;"></i>
                            <h5 class="mt-3 text-muted">Belum Ada Metode Pembayaran</h5>
                            <p class="text-muted">Silakan tambahkan rekening bank, e-wallet, atau QRIS.</p>
                            <a href="?page=add_payment_method" class="btn btn-success mt-2">
                                <i class="mdi mdi-plus"></i> Tambah Metode Pembayaran
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu Panduan -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-book-open-page-variant mr-2"></i>Cara Penggunaan</h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li class="mb-2">
                            <strong>Tambah Metode:</strong><br>
                            <small class="text-muted">Klik tombol hijau "Tambah Metode Baru" untuk menambahkan
                                rekening.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Upload Logo:</strong><br>
                            <small class="text-muted">Upload logo bank/e-wallet agar tampilan lebih profesional.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Upload QR Code:</strong><br>
                            <small class="text-muted">Untuk QRIS/e-wallet, upload gambar QR Code agar user bisa scan
                                langsung.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Atur Urutan:</strong><br>
                            <small class="text-muted">Urutan menentukan posisi tampilan di form pembayaran.</small>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-information-outline mr-2"></i>Jenis Metode Pembayaran</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="40%"><span class="badge badge-primary p-2"><i class="mdi mdi-bank mr-1"></i>Bank
                                    Transfer</span></td>
                            <td>BCA, BNI, BRI, Mandiri, dll</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-success p-2"><i class="mdi mdi-wallet mr-1"></i>E-Wallet</span>
                            </td>
                            <td>GoPay, Dana, ShopeePay, OVO, LinkAja</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-info p-2"><i class="mdi mdi-qrcode mr-1"></i>QRIS</span></td>
                            <td>QR Code universal yang bisa di-scan semua e-wallet</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $alert_script; ?>

<script>
    // Delete Button Handler
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const name = this.dataset.name;

            Swal.fire({
                icon: 'warning',
                title: 'Hapus Metode Pembayaran?',
                html: 'Anda akan menghapus metode pembayaran:<br><strong>"' + name + '"</strong>',
                showCancelButton: true,
                confirmButtonText: '<i class="mdi mdi-delete"></i> Ya, Hapus',
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

                    $.post('content/cms_home/ajax/delete_payment_method.php', { id: id }, function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message
                            });
                        }
                    }, 'json').fail(function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal menghapus metode pembayaran.'
                        });
                    });
                }
            });
        });
    });
</script>