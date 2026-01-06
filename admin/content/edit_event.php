<?php
// Edit Acara

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'ID Tidak Valid!', 'message' => 'ID acara diperlukan'];
    header("Location: ?page=events");
    exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM events WHERE id = $id");
if (mysqli_num_rows($query) == 0) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Tidak Ditemukan!', 'message' => 'Acara tidak ditemukan'];
    header("Location: ?page=events");
    exit;
}

$event = mysqli_fetch_assoc($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Validasi Judul (wajib)
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    if (empty($title)) {
        $errors[] = 'Judul acara wajib diisi';
    } elseif (strlen($title) > 255) {
        $errors[] = 'Judul acara maksimal 255 karakter';
    } elseif (strlen($title) < 5) {
        $errors[] = 'Judul acara minimal 5 karakter';
    }
    $title = mysqli_real_escape_string($conn, $title);

    // Validasi Deskripsi
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    if (strlen($description) > 2000) {
        $errors[] = 'Deskripsi maksimal 2000 karakter';
    }
    $description = mysqli_real_escape_string($conn, $description);

    // Validasi Tanggal (wajib)
    $event_date = isset($_POST['event_date']) ? trim($_POST['event_date']) : '';
    if (empty($event_date)) {
        $errors[] = 'Tanggal acara wajib diisi';
    }
    $event_date = mysqli_real_escape_string($conn, $event_date);

    $event_time = !empty($_POST['event_time']) ? mysqli_real_escape_string($conn, $_POST['event_time']) : null;

    // Validasi Lokasi
    $location = isset($_POST['location']) ? trim($_POST['location']) : '';
    if (strlen($location) > 255) {
        $errors[] = 'Lokasi maksimal 255 karakter';
    }
    $location = mysqli_real_escape_string($conn, $location);

    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $countdown_enabled = isset($_POST['countdown_enabled']) ? 1 : 0;
    $countdown_date = !empty($_POST['countdown_date']) ? mysqli_real_escape_string($conn, $_POST['countdown_date']) : null;

    // Validasi Urutan
    $order_position = isset($_POST['order_position']) ? intval($_POST['order_position']) : 1;
    if ($order_position < 1 || $order_position > 100) {
        $errors[] = 'Urutan harus antara 1 - 100';
    }

    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $image_path = $event['image'];

    // Validasi dan upload gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        $allowed_mime = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $file_mime = mime_content_type($_FILES['image']['tmp_name']);

        if (!in_array($file_ext, $allowed)) {
            $errors[] = 'Format gambar tidak diizinkan';
        }
        if (!in_array($file_mime, $allowed_mime)) {
            $errors[] = 'Tipe file gambar tidak valid';
        }
        if ($_FILES['image']['size'] > 2097152) {
            $errors[] = 'Ukuran gambar maksimal 2MB';
        }

        if (empty($errors)) {
            $new_filename = 'event_' . $id . '_' . time() . '.' . $file_ext;
            if (!is_dir('../uploads/events'))
                mkdir('../uploads/events', 0755, true);
            if (move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/events/' . $new_filename)) {
                if ($event['image'] && file_exists('../' . $event['image']))
                    unlink('../' . $event['image']);
                $image_path = 'uploads/events/' . $new_filename;
            } else {
                $errors[] = 'Gagal mengupload gambar';
            }
        }
    }

    // Simpan ke database
    if (empty($errors)) {
        $event_time_sql = $event_time ? "'$event_time'" : "NULL";
        $countdown_date_sql = $countdown_date ? "'$countdown_date'" : "NULL";

        $update = "UPDATE events SET 
                   title = '$title', description = '$description', event_date = '$event_date',
                   event_time = $event_time_sql, location = '$location', image = '$image_path',
                   is_featured = $is_featured, countdown_enabled = $countdown_enabled, countdown_date = $countdown_date_sql,
                   order_position = $order_position, is_active = $is_active
                   WHERE id = $id";

        if (mysqli_query($conn, $update)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Acara berhasil diperbarui'];
            header("Location: ?page=events");
            exit;
        } else {
            $errors[] = 'Gagal menyimpan: ' . mysqli_error($conn);
        }
    }

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
    }
}

$form_errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);

$error_script = '';
if (!empty($form_errors)) {
    $error_list = '<ul style="text-align:left;margin:0;padding-left:20px;">';
    foreach ($form_errors as $error)
        $error_list .= '<li>' . htmlspecialchars($error) . '</li>';
    $error_list .= '</ul>';
    $error_script = "<script>document.addEventListener('DOMContentLoaded', function() { Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan!', html: '" . addslashes($error_list) . "', confirmButtonText: 'Mengerti', confirmButtonColor: '#dc3545' }); });</script>";
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=events">Acara</a></li>
                        <li class="breadcrumb-item active">Ubah</li>
                    </ol>
                </div>
                <h4 class="page-title">Ubah Acara</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mt-0 header-title">Detail Acara</h4>
                            <p class="text-muted mb-0 font-14">Perbarui informasi acara</p>
                        </div>
                        <div>
                            <?php if ($event['is_featured']): ?>
                                <span class="badge badge-warning p-2 mr-1"><i class="mdi mdi-star"></i> Unggulan</span>
                            <?php endif; ?>
                            <span class="badge badge-<?php echo $event['is_active'] ? 'success' : 'secondary'; ?> p-2">
                                <?php echo $event['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="" enctype="multipart/form-data" id="eventForm">
                        <div class="form-group">
                            <label for="title">Judul Acara <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required minlength="5" maxlength="255"
                                value="<?php echo htmlspecialchars($event['title']); ?>">
                            <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Nama acara (5-255 karakter)</small>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="4" maxlength="2000"><?php echo htmlspecialchars($event['description'] ?? ''); ?></textarea>
                            <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Penjelasan lengkap (maks 2000 karakter)</small>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-calendar-clock mr-1"></i>Waktu & Lokasi</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_date">Tanggal Acara <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="event_date" name="event_date" required
                                        value="<?php echo $event['event_date']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_time">Waktu Acara</label>
                                    <input type="time" class="form-control" id="event_time" name="event_time"
                                        value="<?php echo $event['event_time'] ?? ''; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="location">Lokasi</label>
                            <input type="text" class="form-control" id="location" name="location" maxlength="255"
                                placeholder="Contoh: Masjid Istiqlal, Jakarta"
                                value="<?php echo htmlspecialchars($event['location'] ?? ''); ?>">
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-image mr-1"></i>Gambar Acara</h5>

                        <div class="form-group">
                            <?php if (!empty($event['image'])): ?>
                                <div class="mb-3 p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-2"><i class="mdi mdi-image mr-1"></i>Gambar Saat Ini:</small>
                                    <img src="<?php echo '../' . $event['image']; ?>" class="img-fluid rounded shadow-sm" style="max-height: 150px;">
                                </div>
                            <?php endif; ?>
                            <label for="image">Upload Gambar Baru</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
                                <label class="custom-file-label" for="image">Pilih gambar baru...</label>
                            </div>
                            <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Kosongkan jika tidak ingin mengubah | Maks: 2MB</small>
                            <div id="imagePreview" class="mt-3" style="display:none;">
                                <small class="text-success d-block mb-2"><i class="mdi mdi-check-circle mr-1"></i>Preview Gambar Baru:</small>
                                <img id="previewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 150px;">
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-cog mr-1"></i>Pengaturan Tampilan</h5>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured"
                                            <?php echo $event['is_featured'] ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="is_featured">
                                            <span class="badge badge-warning"><i class="mdi mdi-star"></i></span> Acara Unggulan
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="countdown_enabled" name="countdown_enabled"
                                            <?php echo $event['countdown_enabled'] ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="countdown_enabled">
                                            <span class="badge badge-info"><i class="mdi mdi-timer"></i></span> Hitung Mundur
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                            <?php echo $event['is_active'] ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="is_active">
                                            <span class="badge badge-success">Aktif</span> Tampilkan
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="countdown_date_group" style="<?php echo $event['countdown_enabled'] ? '' : 'display:none;'; ?>">
                            <label for="countdown_date">Tanggal & Waktu Countdown</label>
                            <input type="datetime-local" class="form-control" id="countdown_date" name="countdown_date" style="max-width: 300px;"
                                value="<?php echo $event['countdown_date'] ? date('Y-m-d\TH:i', strtotime($event['countdown_date'])) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="order_position">Urutan Tampilan</label>
                            <input type="number" class="form-control" id="order_position" name="order_position"
                                value="<?php echo $event['order_position'] ?? 1; ?>" min="1" max="100" style="width: 100px;">
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="mdi mdi-content-save"></i> Simpan Perubahan
                        </button>
                        <a href="?page=events" class="btn btn-secondary btn-lg btn-cancel">
                            <i class="mdi mdi-arrow-left"></i> Batal
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Informasi Data -->
            <div class="card m-b-30 border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-information mr-2"></i>Informasi Data</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">ID:</td>
                            <td><strong>#<?php echo $event['id']; ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal:</td>
                            <td><i class="mdi mdi-calendar text-primary"></i> <?php echo date('d M Y', strtotime($event['event_date'])); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Urutan:</td>
                            <td><span class="badge badge-info"><?php echo $event['order_position'] ?? 1; ?></span></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Panduan -->
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-help-circle mr-2"></i>Panduan</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2"><strong>Judul:</strong> Nama acara (wajib)</li>
                        <li class="mb-2"><strong>Tanggal:</strong> Kapan acara berlangsung</li>
                        <li class="mb-2"><strong>Lokasi:</strong> Tempat pelaksanaan</li>
                        <li class="mb-0"><strong>Gambar:</strong> Kosongkan jika tidak mengubah</li>
                    </ul>
                </div>
            </div>

            <!-- Tips -->
            <div class="card m-b-30 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2">Gambar lama dihapus otomatis saat upload baru</li>
                        <li class="mb-2">Nonaktifkan jika acara sudah selesai</li>
                        <li class="mb-0">Tandai sebagai unggulan untuk highlight</li>
                    </ul>
                </div>
            </div>

            <!-- Penjelasan Field -->
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-form-textbox mr-2"></i>Penjelasan Field</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0 small">
                        <tr>
                            <td><strong>Unggulan</strong></td>
                            <td>Acara yang ditonjolkan</td>
                        </tr>
                        <tr>
                            <td><strong>Countdown</strong></td>
                            <td>Tampilkan hitung mundur</td>
                        </tr>
                        <tr>
                            <td><strong>Urutan</strong></td>
                            <td>Posisi tampilan (1-100)</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $error_script; ?>

<style>
.custom-file-label {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    padding-right: 90px;
}
.custom-file-label::after {
    content: "Telusuri";
}
</style>

<script>
// Fungsi untuk memotong nama file
function truncateFilename(filename, maxLength = 25) {
    if (filename.length <= maxLength) return filename;
    const ext = filename.split('.').pop();
    const name = filename.substring(0, filename.lastIndexOf('.'));
    const truncatedName = name.substring(0, maxLength - ext.length - 4) + '...';
    return truncatedName + '.' + ext;
}

// Toggle countdown date
document.getElementById('countdown_enabled').addEventListener('change', function() {
    document.getElementById('countdown_date_group').style.display = this.checked ? 'block' : 'none';
});

// Preview gambar
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const label = this.nextElementSibling;

    if (file) {
        label.textContent = truncateFilename(file.name);

        if (file.size > 2097152) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar!',
                text: 'Ukuran gambar maksimal 2MB.',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#dc3545'
            });
            this.value = '';
            label.textContent = 'Pilih gambar baru...';
            preview.style.display = 'none';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';

            const img = new Image();
            img.onload = function() {
                if (this.width < 400 || this.height < 250) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Dimensi Kecil!',
                        html: 'Dimensi: <strong>' + this.width + 'x' + this.height + '</strong>px<br>Disarankan minimal 800×500px',
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#ffc107'
                    });
                } else {
                    Swal.fire({ icon: 'success', title: 'Gambar Siap!', text: 'Dimensi: ' + this.width + 'x' + this.height + 'px', toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
                }
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        label.textContent = 'Pilih gambar baru...';
        preview.style.display = 'none';
    }
});

// Validasi form
document.getElementById('eventForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const eventDate = document.getElementById('event_date').value;

    if (title.length < 5) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Judul Terlalu Pendek!',
            text: 'Judul acara harus minimal 5 karakter.',
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#dc3545'
        });
        return false;
    }

    if (!eventDate) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Tanggal Belum Diisi!',
            text: 'Tanggal acara wajib diisi.',
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#dc3545'
        });
        return false;
    }

    Swal.fire({
        title: 'Menyimpan Perubahan...',
        html: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => { Swal.showLoading(); }
    });

    return true;
});

// Konfirmasi batal
document.querySelector('.btn-cancel').addEventListener('click', function(e) {
    e.preventDefault();
    const link = this.href;
    
    Swal.fire({
        icon: 'question',
        title: 'Batalkan Perubahan?',
        text: 'Perubahan yang belum disimpan akan hilang.',
        showCancelButton: true,
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Lanjut Mengubah',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) window.location.href = link;
    });
});
</script>