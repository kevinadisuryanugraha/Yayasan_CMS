<?php
// Manajemen Pendaftaran Event - Trash (Tempat Sampah)
// Menampilkan pendaftaran yang sudah dihapus (soft deleted)

$alert_script = '';
if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    $alert_script = "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: '{$alert['type']}',
                    title: '{$alert['title']}',
                    text: '{$alert['message']}',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
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

// Fetch deleted registrations
$query = mysqli_query($conn, "
    SELECT r.*, e.title as event_title, e.event_date 
    FROM event_registrations r 
    JOIN events e ON r.event_id = e.id 
    WHERE r.deleted_at IS NOT NULL 
    ORDER BY r.deleted_at DESC
");
$rows = mysqli_fetch_all($query, MYSQLI_ASSOC);

// Count trash items
$count_trash = count($rows);
?>

<div class="container-fluid">
    <!-- Judul Halaman dengan Breadcrumb -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=event_registrations">Pendaftaran</a></li>
                        <li class="breadcrumb-item active">Trash</li>
                    </ol>
                </div>
                <h4 class="page-title">Tempat Sampah - Pendaftaran</h4>
            </div>
        </div>
    </div>

    <!-- Header Card -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30 bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-2"><i class="mdi mdi-delete text-danger mr-2"></i>Tempat Sampah (Trash)</h5>
                            <p class="mb-0 text-muted">
                                Menampilkan data pendaftaran yang telah dihapus. Anda dapat memulihkan data atau
                                menghapus secara permanen.
                            </p>
                        </div>
                        <div class="col-md-4 text-center mt-3 mt-md-0">
                            <i class="mdi mdi-delete text-danger" style="font-size: 80px; opacity: 0.5;"></i>
                            <small class="d-block text-muted mt-2"><?php echo $count_trash; ?> item di Trash</small>
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
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mt-0 header-title">Daftar Pendaftaran Terhapus</h4>
                            <p class="text-muted m-b-30 font-14">Total <?php echo $count_trash; ?> pendaftaran di tempat
                                sampah</p>
                        </div>
                        <div>
                            <?php if ($count_trash > 0): ?>
                                <button type="button" class="btn btn-outline-danger mr-2" id="btnEmptyTrash">
                                    <i class="mdi mdi-delete"></i> Kosongkan Trash
                                </button>
                            <?php endif; ?>
                            <a href="?page=event_registrations" class="btn btn-outline-primary">
                                <i class="mdi mdi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <?php if (count($rows) > 0): ?>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="50">No</th>
                                        <th>Kode</th>
                                        <th>Peserta</th>
                                        <th>Event</th>
                                        <th>Dihapus</th>
                                        <th width="200">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($rows as $row): ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><code
                                                    class="text-primary"><?php echo htmlspecialchars($row['registration_code']); ?></code>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($row['full_name']); ?></strong><br>
                                                <small class="text-muted"><i class="mdi mdi-email-outline"></i>
                                                    <?php echo htmlspecialchars($row['email']); ?></small><br>
                                                <small class="text-muted"><i class="mdi mdi-phone"></i>
                                                    <?php echo htmlspecialchars($row['phone']); ?></small>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['event_title']); ?><br>
                                                <small class="text-muted"><i class="mdi mdi-calendar"></i>
                                                    <?php echo date('d M Y', strtotime($row['event_date'])); ?></small>
                                            </td>
                                            <td>
                                                <small><?php echo date('d M Y', strtotime($row['deleted_at'])); ?></small><br>
                                                <small
                                                    class="text-muted"><?php echo date('H:i', strtotime($row['deleted_at'])); ?></small>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm btn-restore"
                                                    onclick="restoreRegistration(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars(addslashes($row['full_name'])); ?>')">
                                                    <i class="mdi mdi-undo"></i> Pulihkan
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm btn-permanent-delete"
                                                    onclick="permanentDeleteRegistration(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars(addslashes($row['full_name'])); ?>')">
                                                    <i class="mdi mdi-delete"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="mdi mdi-delete-empty text-muted" style="font-size: 64px;"></i>
                            <h5 class="mt-3 text-muted">Tempat Sampah Kosong</h5>
                            <p class="text-muted">Tidak ada pendaftaran yang terhapus.</p>
                            <a href="?page=event_registrations" class="btn btn-primary mt-2">
                                <i class="mdi mdi-arrow-left"></i> Kembali ke Pendaftaran
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $alert_script; ?>

<!-- Trash Action Scripts - Using onclick handlers which will be called after jQuery/Swal are loaded -->
<script>
// Global functions that will be called from onclick
function restoreRegistration(id, name) {
    // Check if Swal is available
    if (typeof Swal === 'undefined') {
        alert('SweetAlert not loaded. Please refresh the page.');
        return;
    }
    
    Swal.fire({
        title: 'Pulihkan Pendaftaran?',
        html: 'Pulihkan pendaftaran dari <strong>' + name + '</strong>?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Pulihkan!',
        cancelButtonText: 'Batal'
    }).then(function(result) {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Memproses...',
                allowOutsideClick: false,
                didOpen: function() { Swal.showLoading(); }
            });
            
            // Use jQuery if available, otherwise fallback to fetch
            if (typeof $ !== 'undefined') {
                $.post('content/cms_home/ajax/restore_registration.php', { id: id }, function(response) {
                    handleResponse(response, 'Berhasil dipulihkan!');
                }, 'json').fail(function(xhr, status, error) {
                    Swal.fire('Error!', 'Gagal: ' + error, 'error');
                });
            } else {
                fetchPost('content/cms_home/ajax/restore_registration.php', { id: id });
            }
        }
    });
}

function permanentDeleteRegistration(id, name) {
    if (typeof Swal === 'undefined') {
        alert('SweetAlert not loaded. Please refresh the page.');
        return;
    }
    
    Swal.fire({
        title: 'Hapus Permanen?',
        html: '<strong class="text-danger">PERINGATAN:</strong> Hapus permanen <strong>' + name + '</strong>?<br><small>Data tidak dapat dipulihkan!</small>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        input: 'text',
        inputPlaceholder: 'Ketik "HAPUS" untuk konfirmasi',
        inputValidator: function(value) {
            if (value !== 'HAPUS') return 'Ketik "HAPUS" untuk mengkonfirmasi';
        }
    }).then(function(result) {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Menghapus...',
                allowOutsideClick: false,
                didOpen: function() { Swal.showLoading(); }
            });
            
            if (typeof $ !== 'undefined') {
                $.post('content/cms_home/ajax/permanent_delete_registration.php', { id: id }, function(response) {
                    handleResponse(response, 'Berhasil dihapus!');
                }, 'json').fail(function(xhr, status, error) {
                    Swal.fire('Error!', 'Gagal: ' + error, 'error');
                });
            } else {
                fetchPost('content/cms_home/ajax/permanent_delete_registration.php', { id: id });
            }
        }
    });
}

function emptyTrash() {
    if (typeof Swal === 'undefined') {
        alert('SweetAlert not loaded. Please refresh the page.');
        return;
    }
    
    Swal.fire({
        title: 'Kosongkan Trash?',
        html: '<strong class="text-danger">PERINGATAN:</strong> Semua data akan dihapus permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Kosongkan!',
        cancelButtonText: 'Batal',
        input: 'text',
        inputPlaceholder: 'Ketik "KOSONGKAN" untuk konfirmasi',
        inputValidator: function(value) {
            if (value !== 'KOSONGKAN') return 'Ketik "KOSONGKAN" untuk mengkonfirmasi';
        }
    }).then(function(result) {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Mengosongkan...',
                allowOutsideClick: false,
                didOpen: function() { Swal.showLoading(); }
            });
            
            if (typeof $ !== 'undefined') {
                $.post('content/cms_home/ajax/empty_trash_registrations.php', {}, function(response) {
                    handleResponse(response, 'Trash berhasil dikosongkan!');
                }, 'json').fail(function(xhr, status, error) {
                    Swal.fire('Error!', 'Gagal: ' + error, 'error');
                });
            } else {
                fetchPost('content/cms_home/ajax/empty_trash_registrations.php', {});
            }
        }
    });
}

// Helper function to handle AJAX response
function handleResponse(response, successMsg) {
    if (response.success) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: response.message || successMsg,
            showConfirmButton: false,
            timer: 1500
        }).then(function() { location.reload(); });
    } else {
        Swal.fire('Gagal!', response.message || 'Terjadi kesalahan', 'error');
    }
}

// Fallback fetch function if jQuery not available
function fetchPost(url, data) {
    var formData = new FormData();
    for (var key in data) {
        formData.append(key, data[key]);
    }
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(function(response) { return response.json(); })
    .then(function(data) { handleResponse(data, 'Berhasil!'); })
    .catch(function(error) { Swal.fire('Error!', 'Gagal: ' + error, 'error'); });
}

// Attach empty trash button after page loads
document.addEventListener('DOMContentLoaded', function() {
    var emptyBtn = document.getElementById('btnEmptyTrash');
    if (emptyBtn) {
        emptyBtn.onclick = emptyTrash;
    }
});
</script>