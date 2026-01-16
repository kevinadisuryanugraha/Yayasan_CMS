<?php
// Manajemen Pesan Kontak - Admin Panel
// Menampilkan dan mengelola pesan yang masuk dari form kontak

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

// Create table if not exists
$create_table = "CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    admin_notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    replied_at DATETIME,
    deleted_at DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
mysqli_query($conn, $create_table);

// Get filter status
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Build query
$where = "deleted_at IS NULL";
if ($status_filter != 'all') {
    $status_escaped = mysqli_real_escape_string($conn, $status_filter);
    $where .= " AND status = '$status_escaped'";
}

// Fetch messages
$query = mysqli_query($conn, "SELECT * FROM contact_messages WHERE $where ORDER BY created_at DESC");
$messages = mysqli_fetch_all($query, MYSQLI_ASSOC);

// Count by status
$count_new = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM contact_messages WHERE status = 'new' AND deleted_at IS NULL"))['c'];
$count_read = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM contact_messages WHERE status = 'read' AND deleted_at IS NULL"))['c'];
$count_replied = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM contact_messages WHERE status = 'replied' AND deleted_at IS NULL"))['c'];
$count_all = $count_new + $count_read + $count_replied;
?>

<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item active">Pesan Kontak</li>
                    </ol>
                </div>
                <h4 class="page-title">Pesan Kontak</h4>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="mdi mdi-email-multiple text-primary" style="font-size: 40px;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">
                                <?php echo $count_all; ?>
                            </h5>
                            <p class="mb-0 text-muted">Total Pesan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="mdi mdi-email-alert text-danger" style="font-size: 40px;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">
                                <?php echo $count_new; ?>
                            </h5>
                            <p class="mb-0 text-muted">Pesan Baru</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="mdi mdi-email-open text-warning" style="font-size: 40px;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">
                                <?php echo $count_read; ?>
                            </h5>
                            <p class="mb-0 text-muted">Sudah Dibaca</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="mdi mdi-email-check text-success" style="font-size: 40px;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">
                                <?php echo $count_replied; ?>
                            </h5>
                            <p class="mb-0 text-muted">Sudah Dibalas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <!-- Filter Tabs -->
                    <ul class="nav nav-tabs mb-4">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $status_filter == 'all' ? 'active' : ''; ?>"
                                href="?page=contact_messages&status=all">
                                Semua <span class="badge badge-secondary ml-1">
                                    <?php echo $count_all; ?>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $status_filter == 'new' ? 'active' : ''; ?>"
                                href="?page=contact_messages&status=new">
                                Baru <span class="badge badge-danger ml-1">
                                    <?php echo $count_new; ?>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $status_filter == 'read' ? 'active' : ''; ?>"
                                href="?page=contact_messages&status=read">
                                Dibaca <span class="badge badge-warning ml-1">
                                    <?php echo $count_read; ?>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $status_filter == 'replied' ? 'active' : ''; ?>"
                                href="?page=contact_messages&status=replied">
                                Dibalas <span class="badge badge-success ml-1">
                                    <?php echo $count_replied; ?>
                                </span>
                            </a>
                        </li>
                    </ul>

                    <?php if (count($messages) > 0): ?>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="50">No</th>
                                        <th>Pengirim</th>
                                        <th>Subjek</th>
                                        <th>Pesan</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th width="150">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($messages as $msg): ?>
                                        <tr class="<?php echo $msg['status'] == 'new' ? 'table-warning' : ''; ?>">
                                            <td>
                                                <?php echo $no++; ?>
                                            </td>
                                            <td>
                                                <strong>
                                                    <?php echo htmlspecialchars($msg['name']); ?>
                                                </strong><br>
                                                <small class="text-muted">
                                                    <i class="mdi mdi-email-outline"></i>
                                                    <?php echo htmlspecialchars($msg['email']); ?>
                                                </small>
                                                <?php if ($msg['phone']): ?>
                                                    <br><small class="text-muted">
                                                        <i class="mdi mdi-phone"></i>
                                                        <?php echo htmlspecialchars($msg['phone']); ?>
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($msg['subject'] ?: '-'); ?>
                                            </td>
                                            <td>
                                                <?php echo mb_substr(htmlspecialchars($msg['message']), 0, 80); ?>
                                                <?php echo strlen($msg['message']) > 80 ? '...' : ''; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $status_class = [
                                                    'new' => 'danger',
                                                    'read' => 'warning',
                                                    'replied' => 'success'
                                                ];
                                                $status_text = [
                                                    'new' => 'Baru',
                                                    'read' => 'Dibaca',
                                                    'replied' => 'Dibalas'
                                                ];
                                                ?>
                                                <span class="badge badge-<?php echo $status_class[$msg['status']]; ?>">
                                                    <?php echo $status_text[$msg['status']]; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <small>
                                                    <?php echo date('d M Y', strtotime($msg['created_at'])); ?>
                                                </small><br>
                                                <small class="text-muted">
                                                    <?php echo date('H:i', strtotime($msg['created_at'])); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-info btn-view"
                                                        onclick="viewMessage(<?php echo $msg['id']; ?>)" title="Lihat">
                                                        <i class="mdi mdi-eye"></i>
                                                    </button>
                                                    <?php if ($msg['phone']): ?>
                                                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $msg['phone']); ?>"
                                                            target="_blank" class="btn btn-success" title="WhatsApp">
                                                            <i class="mdi mdi-whatsapp"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-danger btn-delete"
                                                        onclick="deleteMessage(<?php echo $msg['id']; ?>, '<?php echo htmlspecialchars(addslashes($msg['name'])); ?>')"
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
                            <i class="mdi mdi-email-open-outline text-muted" style="font-size: 64px;"></i>
                            <h5 class="mt-3 text-muted">Tidak Ada Pesan</h5>
                            <p class="text-muted">Belum ada pesan kontak yang masuk.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Message Modal -->
<div class="modal fade" id="viewMessageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pesan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="messageModalContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Memuat...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?php echo $alert_script; ?>

<script>
    // View Message
    function viewMessage(id) {
        var modal = $('#viewMessageModal');
        var content = $('#messageModalContent');

        modal.modal('show');
        content.html('<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2">Memuat...</p></div>');

        $.get('content/cms_home/ajax/get_contact_detail.php', { id: id }, function (response) {
            if (response.success) {
                var msg = response.data;
                var statusBadge = {
                    'new': '<span class="badge badge-danger">Baru</span>',
                    'read': '<span class="badge badge-warning">Dibaca</span>',
                    'replied': '<span class="badge badge-success">Dibalas</span>'
                };

                var html = `
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama:</strong><br>${msg.name}</p>
                        <p><strong>Email:</strong><br><a href="mailto:${msg.email}">${msg.email}</a></p>
                        ${msg.phone ? `<p><strong>Telepon:</strong><br><a href="https://wa.me/${msg.phone.replace(/[^0-9]/g, '')}" target="_blank">${msg.phone}</a></p>` : ''}
                    </div>
                    <div class="col-md-6">
                        <p><strong>Subjek:</strong><br>${msg.subject || '-'}</p>
                        <p><strong>Status:</strong><br>${statusBadge[msg.status]}</p>
                        <p><strong>Tanggal:</strong><br>${msg.created_at}</p>
                    </div>
                </div>
                <hr>
                <p><strong>Pesan:</strong></p>
                <div class="p-3 bg-light rounded" style="white-space: pre-wrap;">${msg.message}</div>
                <hr>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-warning btn-sm mr-2" onclick="updateStatus(${msg.id}, 'read')">
                        <i class="mdi mdi-email-open"></i> Tandai Dibaca
                    </button>
                    <button type="button" class="btn btn-success btn-sm mr-2" onclick="updateStatus(${msg.id}, 'replied')">
                        <i class="mdi mdi-email-check"></i> Tandai Dibalas
                    </button>
                    ${msg.phone ? `<a href="https://wa.me/${msg.phone.replace(/[^0-9]/g, '')}" target="_blank" class="btn btn-success btn-sm">
                        <i class="mdi mdi-whatsapp"></i> Balas via WhatsApp
                    </a>` : ''}
                </div>
            `;
                content.html(html);
            } else {
                content.html('<div class="alert alert-danger">Gagal memuat pesan</div>');
            }
        }, 'json').fail(function () {
            content.html('<div class="alert alert-danger">Terjadi kesalahan</div>');
        });
    }

    // Update Status
    function updateStatus(id, status) {
        $.post('content/cms_home/ajax/update_contact_status.php', { id: id, status: status }, function (response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(function () {
                    location.reload();
                });
            } else {
                Swal.fire('Gagal!', response.message, 'error');
            }
        }, 'json').fail(function () {
            Swal.fire('Error!', 'Terjadi kesalahan', 'error');
        });
    }

    // Delete Message
    function deleteMessage(id, name) {
        Swal.fire({
            title: 'Hapus Pesan?',
            html: 'Hapus pesan dari <strong>' + name + '</strong>?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.post('content/cms_home/ajax/delete_contact.php', { id: id }, function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function () {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal!', response.message, 'error');
                    }
                }, 'json').fail(function () {
                    Swal.fire('Error!', 'Terjadi kesalahan', 'error');
                });
            }
        });
    }
</script>