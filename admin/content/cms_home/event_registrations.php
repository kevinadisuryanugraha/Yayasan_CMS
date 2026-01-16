<?php
// Manajemen Pendaftaran Event
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

// Add deleted_at column if not exists
$check_column = mysqli_query($conn, "SHOW COLUMNS FROM event_registrations LIKE 'deleted_at'");
if (mysqli_num_rows($check_column) == 0) {
    mysqli_query($conn, "ALTER TABLE event_registrations ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL");
}

// Count trash items
$count_trash = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM event_registrations WHERE deleted_at IS NOT NULL"))['cnt'] ?? 0;

// Get filter parameters
$event_filter = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Build query
$where = "1=1 AND (r.deleted_at IS NULL)";
if ($event_filter > 0) {
    $where .= " AND r.event_id = $event_filter";
}
if (!empty($status_filter)) {
    $where .= " AND r.status = '$status_filter'";
}
if (!empty($search)) {
    $where .= " AND (r.full_name LIKE '%$search%' OR r.email LIKE '%$search%' OR r.registration_code LIKE '%$search%')";
}

// Fetch registrations with event data
$query = mysqli_query($conn, "
    SELECT r.*, e.title as event_title, e.event_date 
    FROM event_registrations r 
    JOIN events e ON r.event_id = e.id 
    WHERE $where 
    ORDER BY r.created_at DESC
");
$rows = mysqli_fetch_all($query, MYSQLI_ASSOC);

// Fetch events for filter dropdown
$events_query = mysqli_query($conn, "SELECT id, title FROM events ORDER BY event_date DESC");

// Count by status
$count_pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM event_registrations WHERE status = 'pending' AND deleted_at IS NULL"))['cnt'] ?? 0;
$count_confirmed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM event_registrations WHERE status = 'confirmed' AND deleted_at IS NULL"))['cnt'] ?? 0;
$count_cancelled = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM event_registrations WHERE status = 'cancelled' AND deleted_at IS NULL"))['cnt'] ?? 0;
$count_attended = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM event_registrations WHERE status = 'attended' AND deleted_at IS NULL"))['cnt'] ?? 0;
$count_total = $count_pending + $count_confirmed + $count_cancelled + $count_attended;
?>

<div class="container-fluid">
    <!-- Judul Halaman dengan Breadcrumb -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=events">Events</a></li>
                        <li class="breadcrumb-item active">Pendaftaran Event</li>
                    </ol>
                </div>
                <h4 class="page-title">Manajemen Pendaftaran Event</h4>
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
                            <h5 class="mb-2"><i class="mdi mdi-account-multiple-check text-primary mr-2"></i>Apa itu
                                Pendaftaran Event?</h5>
                            <p class="mb-0 text-muted">
                                <strong>Pendaftaran Event</strong> adalah daftar peserta yang mendaftar mengikuti
                                acara/kegiatan melalui website.
                                Di sini Anda dapat melihat data peserta, mengkonfirmasi pendaftaran, menandai kehadiran,
                                atau membatalkan pendaftaran.
                                Sistem akan otomatis menghitung kuota peserta berdasarkan status konfirmasi.
                            </p>
                        </div>
                        <div class="col-md-4 text-center mt-3 mt-md-0">
                            <i class="mdi mdi-clipboard-account-outline text-primary"
                                style="font-size: 80px; opacity: 0.5;"></i>
                            <small class="d-block text-muted mt-2">Kelola Peserta Event</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card m-b-30 <?php echo $status_filter == 'pending' ? 'border-warning' : ''; ?>">
                <div class="card-body text-center py-3">
                    <a href="?page=event_registrations&status=pending" class="text-decoration-none">
                        <h2 class="mb-1 text-warning"><?php echo $count_pending; ?></h2>
                        <small class="text-muted"><i class="mdi mdi-clock-outline mr-1"></i>Menunggu Konfirmasi</small>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card m-b-30 <?php echo $status_filter == 'confirmed' ? 'border-success' : ''; ?>">
                <div class="card-body text-center py-3">
                    <a href="?page=event_registrations&status=confirmed" class="text-decoration-none">
                        <h2 class="mb-1 text-success"><?php echo $count_confirmed; ?></h2>
                        <small class="text-muted"><i class="mdi mdi-check-circle-outline mr-1"></i>Terkonfirmasi</small>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card m-b-30 <?php echo $status_filter == 'attended' ? 'border-info' : ''; ?>">
                <div class="card-body text-center py-3">
                    <a href="?page=event_registrations&status=attended" class="text-decoration-none">
                        <h2 class="mb-1 text-info"><?php echo $count_attended; ?></h2>
                        <small class="text-muted"><i class="mdi mdi-account-check-outline mr-1"></i>Sudah Hadir</small>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card m-b-30 <?php echo $status_filter == 'cancelled' ? 'border-secondary' : ''; ?>">
                <div class="card-body text-center py-3">
                    <a href="?page=event_registrations&status=cancelled" class="text-decoration-none">
                        <h2 class="mb-1 text-secondary"><?php echo $count_cancelled; ?></h2>
                        <small class="text-muted"><i class="mdi mdi-close-circle-outline mr-1"></i>Dibatalkan</small>
                    </a>
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
                            <h4 class="mt-0 header-title">Daftar Pendaftaran</h4>
                            <p class="text-muted m-b-30 font-14">
                                Total <?php echo $count_total; ?> pendaftaran terdaftar
                            </p>
                        </div>
                        <a href="?page=event_registrations_trash" class="btn btn-outline-danger mr-2">
                            <i class="mdi mdi-delete"></i> Trash <?php if ($count_trash > 0): ?><span class="badge badge-danger"><?php echo $count_trash; ?></span><?php endif; ?>
                        </a>
                        <a href="?page=events" class="btn btn-outline-primary">
                            <i class="mdi mdi-arrow-left"></i> Kembali ke Events
                        </a>
                    </div>

                    <!-- Filter Form -->
                    <div class="card border-light mb-4">
                        <div class="card-body bg-light py-3">
                            <form method="GET" class="mb-0">
                                <input type="hidden" name="page" value="event_registrations">
                                <div class="row align-items-end">
                                    <div class="col-md-3">
                                        <label class="text-muted small">Filter Event</label>
                                        <select name="event_id" class="form-control">
                                            <option value="">-- Semua Event --</option>
                                            <?php while ($event = mysqli_fetch_assoc($events_query)): ?>
                                                <option value="<?php echo $event['id']; ?>" <?php echo $event_filter == $event['id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($event['title']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="text-muted small">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">-- Semua --</option>
                                            <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>⏳ Menunggu</option>
                                            <option value="confirmed" <?php echo $status_filter == 'confirmed' ? 'selected' : ''; ?>>✅ Terkonfirmasi</option>
                                            <option value="attended" <?php echo $status_filter == 'attended' ? 'selected' : ''; ?>>📍 Hadir</option>
                                            <option value="cancelled" <?php echo $status_filter == 'cancelled' ? 'selected' : ''; ?>>❌ Dibatalkan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small">Cari Peserta</label>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Nama, email, atau kode registrasi..."
                                            value="<?php echo htmlspecialchars($search); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="mdi mdi-magnify"></i> Cari
                                        </button>
                                        <a href="?page=event_registrations" class="btn btn-outline-secondary">
                                            <i class="mdi mdi-refresh"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <?php if (count($rows) > 0): ?>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="12%">Kode</th>
                                        <th width="20%">Peserta</th>
                                        <th width="20%">Event</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Pembayaran</th>
                                        <th width="10%">Terdaftar</th>
                                        <th width="13%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as $key => $row): ?>
                                        <?php
                                        $status_badges = [
                                            'pending' => 'warning',
                                            'confirmed' => 'success',
                                            'attended' => 'info',
                                            'cancelled' => 'secondary'
                                        ];
                                        $status_texts = [
                                            'pending' => '⏳ Menunggu',
                                            'confirmed' => '✅ Terkonfirmasi',
                                            'attended' => '📍 Hadir',
                                            'cancelled' => '❌ Dibatalkan'
                                        ];
                                        ?>
                                        <tr>
                                            <td><?php echo $key + 1; ?></td>
                                            <td>
                                                <code
                                                    class="text-primary font-weight-bold"><?php echo $row['registration_code']; ?></code>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($row['full_name']); ?></strong><br>
                                                <small class="text-muted">
                                                    <i class="mdi mdi-email-outline"></i> <?php echo $row['email']; ?><br>
                                                    <i class="mdi mdi-phone"></i> <?php echo $row['phone']; ?>
                                                </small>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-primary"><?php echo htmlspecialchars($row['event_title']); ?></span><br>
                                                <small class="text-muted"><i class="mdi mdi-calendar"></i>
                                                    <?php echo date('d M Y', strtotime($row['event_date'])); ?></small>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?php echo $status_badges[$row['status']]; ?> p-2">
                                                    <?php echo $status_texts[$row['status']]; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($row['payment_amount'] > 0): ?>
                                                    <?php if ($row['payment_status'] == 'paid'): ?>
                                                        <span class="badge badge-success">💰 Lunas</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-danger">⚠️ Belum</span>
                                                    <?php endif; ?>
                                                    <br><small class="text-muted">Rp
                                                        <?php echo number_format($row['payment_amount'], 0, ',', '.'); ?></small>
                                                <?php else: ?>
                                                    <span class="badge badge-light border">🎁 Gratis</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small><?php echo date('d M Y', strtotime($row['created_at'])); ?></small><br>
                                                <small
                                                    class="text-muted"><?php echo date('H:i', strtotime($row['created_at'])); ?></small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-info btn-view"
                                                        data-id="<?php echo $row['id']; ?>" title="Lihat Detail">
                                                        <i class="mdi mdi-eye"></i>
                                                    </button>
                                                    <?php if ($row['status'] == 'pending'): ?>
                                                        <button type="button" class="btn btn-success btn-status"
                                                            data-id="<?php echo $row['id']; ?>" data-status="confirmed"
                                                            data-name="<?php echo htmlspecialchars($row['full_name']); ?>"
                                                            title="Konfirmasi">
                                                            <i class="mdi mdi-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <?php if ($row['status'] == 'confirmed'): ?>
                                                        <button type="button" class="btn btn-primary btn-status"
                                                            data-id="<?php echo $row['id']; ?>" data-status="attended"
                                                            data-name="<?php echo htmlspecialchars($row['full_name']); ?>"
                                                            title="Tandai Hadir">
                                                            <i class="mdi mdi-account-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <?php if ($row['status'] != 'cancelled'): ?>
                                                        <button type="button" class="btn btn-danger btn-status"
                                                            data-id="<?php echo $row['id']; ?>" data-status="cancelled"
                                                            data-name="<?php echo htmlspecialchars($row['full_name']); ?>"
                                                            title="Batalkan">
                                                            <i class="mdi mdi-close"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-outline-danger btn-delete"
                                                        data-id="<?php echo $row['id']; ?>"
                                                        data-name="<?php echo htmlspecialchars($row['full_name']); ?>"
                                                        title="Hapus">
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
                            <i class="mdi mdi-account-search text-muted" style="font-size: 64px;"></i>
                            <h5 class="mt-3 text-muted">Belum Ada Data Pendaftaran</h5>
                            <p class="text-muted">Belum ada peserta yang mendaftar untuk event Anda.</p>
                            <a href="?page=events" class="btn btn-primary mt-2">
                                <i class="mdi mdi-arrow-left"></i> Kelola Event
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu Panduan & Tips -->
    <div class="row">
        <!-- Cara Penggunaan -->
        <div class="col-lg-6">
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-book-open-page-variant mr-2"></i>Cara Penggunaan</h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li class="mb-2">
                            <strong>Melihat Detail Peserta:</strong><br>
                            <small class="text-muted">Klik tombol biru <i class="mdi mdi-eye"></i> untuk melihat
                                informasi lengkap peserta.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Mengkonfirmasi Pendaftaran:</strong><br>
                            <small class="text-muted">Klik tombol hijau <i class="mdi mdi-check"></i> untuk
                                mengkonfirmasi pendaftaran peserta.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Menandai Kehadiran:</strong><br>
                            <small class="text-muted">Saat event berlangsung, klik tombol <i
                                    class="mdi mdi-account-check"></i> untuk menandai peserta hadir.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Membatalkan Pendaftaran:</strong><br>
                            <small class="text-muted">Klik tombol merah <i class="mdi mdi-close"></i> untuk membatalkan
                                pendaftaran.</small>
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Tips & Praktik Terbaik -->
        <div class="col-lg-6">
            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Tips & Praktik Terbaik</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li class="mb-2">
                            <strong>Konfirmasi Tepat Waktu:</strong><br>
                            <small class="text-muted">Segera konfirmasi pendaftaran agar peserta mendapat kepastian
                                keikutsertaan.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Pantau Kuota:</strong><br>
                            <small class="text-muted">Pastikan jumlah peserta terkonfirmasi tidak melebihi kuota
                                event.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Verifikasi Pembayaran:</strong><br>
                            <small class="text-muted">Untuk event berbayar, verifikasi bukti pembayaran sebelum
                                mengkonfirmasi.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Data Kehadiran:</strong><br>
                            <small class="text-muted">Tandai kehadiran peserta saat check-in untuk laporan evaluasi
                                event.</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Penjelasan Status -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-information-outline mr-2"></i>Penjelasan Status Pendaftaran</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%"><span class="badge badge-warning p-2">⏳ Menunggu</span></td>
                                    <td>Pendaftaran baru, belum dikonfirmasi admin</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-success p-2">✅ Terkonfirmasi</span></td>
                                    <td>Pendaftaran sudah dikonfirmasi, peserta bisa hadir</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%"><span class="badge badge-info p-2">📍 Hadir</span></td>
                                    <td>Peserta sudah hadir di lokasi event</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-secondary p-2">❌ Dibatalkan</span></td>
                                    <td>Pendaftaran dibatalkan oleh admin/peserta</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Peserta -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="mdi mdi-account-card-details mr-2"></i>Detail Pendaftaran</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2">Memuat data...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="mdi mdi-close"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<?php echo $alert_script; ?>

<script>
    // View Detail Button
    document.querySelectorAll('.btn-view').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            $('#detailModal').modal('show');
            $('#detailModalBody').html('<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2">Memuat data...</p></div>');

            $.get('content/cms_home/ajax/get_registration_detail.php?id=' + id, function (data) {
                $('#detailModalBody').html(data);
            }).fail(function () {
                $('#detailModalBody').html('<div class="alert alert-danger"><i class="mdi mdi-alert"></i> Gagal memuat data</div>');
            });
        });
    });

    // Status Update Buttons
    document.querySelectorAll('.btn-status').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const status = this.dataset.status;
            const name = this.dataset.name;

            const statusTexts = {
                'confirmed': { action: 'mengkonfirmasi', icon: 'check-circle', color: '#28a745' },
                'attended': { action: 'menandai hadir', icon: 'account-check', color: '#17a2b8' },
                'cancelled': { action: 'membatalkan', icon: 'close-circle', color: '#dc3545' }
            };

            const info = statusTexts[status];

            Swal.fire({
                icon: status === 'cancelled' ? 'warning' : 'question',
                title: 'Konfirmasi Aksi',
                html: 'Anda akan <strong>' + info.action + '</strong> pendaftaran atas nama:<br><strong>"' + name + '"</strong>',
                showCancelButton: true,
                confirmButtonText: '<i class="mdi mdi-' + info.icon + '"></i> Ya, Lanjutkan',
                cancelButtonText: '<i class="mdi mdi-close"></i> Batal',
                confirmButtonColor: info.color,
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        html: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // AJAX request
                    $.post('content/cms_home/ajax/update_registration_status.php', { id: id, status: status }, function (response) {
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
                            text: 'Gagal mengupdate status. Silakan coba lagi.'
                        });
                    });
                }
            });
        });
    });

    // Delete Button Handler (Soft Delete)
    document.querySelectorAll('.btn-delete').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            
            Swal.fire({
                icon: 'warning',
                title: 'Hapus Pendaftaran?',
                html: 'Data pendaftaran <strong>"' + name + '"</strong> akan dipindahkan ke Trash.<br><small class="text-muted">Anda dapat memulihkan data dari halaman Trash.</small>',
                showCancelButton: true,
                confirmButtonText: '<i class="mdi mdi-delete"></i> Ya, Hapus',
                cancelButtonText: '<i class="mdi mdi-close"></i> Batal',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('content/cms_home/ajax/delete_registration.php', { id: id }, function(response) {
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
                    }, 'json').fail(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal menghapus data. Silakan coba lagi.'
                        });
                    });
                }
            });
        });
    });
</script>