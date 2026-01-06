<?php
// Tambah Acara

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

    // Validasi Waktu
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

    $image_path = '';

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
            $new_filename = 'event_' . time() . '_' . uniqid() . '.' . $file_ext;
            if (!is_dir('../uploads/events'))
                mkdir('../uploads/events', 0755, true);
            if (move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/events/' . $new_filename)) {
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

        $query = "INSERT INTO events (title, description, event_date, event_time, location, image, is_featured, countdown_enabled, countdown_date, order_position, is_active) 
                  VALUES ('$title', '$description', '$event_date', $event_time_sql, '$location', '$image_path', $is_featured, $countdown_enabled, $countdown_date_sql, $order_position, $is_active)";

        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Acara berhasil ditambahkan'];
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
                        <li class="breadcrumb-item active">Tambah Baru</li>
                    </ol>
                </div>
                <h4 class="page-title">Tambah Acara Baru</h4>
            </div>
        </div>
    </div>

    <!-- Kartu Petunjuk -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30 bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h5 class="mb-2"><i class="mdi mdi-plus-circle text-success mr-2"></i>Membuat Acara Baru</h5>
                            <p class="mb-0 text-muted">
                                Lengkapi form berikut untuk menambahkan acara baru. 
                                Acara yang ditambahkan akan ditampilkan di halaman utama website.
                            </p>
                        </div>
                        <div class="col-md-3 text-center mt-3 mt-md-0">
                            <i class="mdi mdi-calendar-plus text-success" style="font-size: 50px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Detail Acara</h4>
                    <p class="text-muted m-b-30 font-14">Isi informasi acara yang akan datang</p>

                    <form method="POST" action="" enctype="multipart/form-data" id="eventForm">
                        <div class="form-group">
                            <label for="title">Judul Acara <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required minlength="5" maxlength="255"
                                placeholder="Contoh: Pengajian Akbar Ramadhan 1446H"
                                value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                            <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Nama acara (5-255 karakter)</small>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="4" maxlength="2000"
                                placeholder="Jelaskan tentang acara ini..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                            <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Penjelasan lengkap (maks 2000 karakter)</small>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-calendar-clock mr-1"></i>Waktu & Lokasi</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_date">Tanggal Acara <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="event_date" name="event_date" required
                                        value="<?php echo htmlspecialchars($_POST['event_date'] ?? ''); ?>">
                                    <small class="form-text text-muted">Kapan acara akan berlangsung</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_time">Waktu Acara</label>
                                    <input type="time" class="form-control" id="event_time" name="event_time"
                                        value="<?php echo htmlspecialchars($_POST['event_time'] ?? ''); ?>">
                                    <small class="form-text text-muted">Jam mulai acara (opsional)</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="location">Lokasi</label>
                            <input type="text" class="form-control" id="location" name="location" maxlength="255"
                                placeholder="Contoh: Masjid Istiqlal, Jakarta"
                                value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>">
                            <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Tempat pelaksanaan acara</small>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-image mr-1"></i>Gambar Acara</h5>

                        <div class="form-group">
                            <label for="image">Upload Gambar</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
                                <label class="custom-file-label" for="image">Pilih gambar...</label>
                            </div>
                            <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Format: JPG, PNG, GIF, WEBP | Maks: 2MB | Ideal: 800×500px</small>
                            <div id="imagePreview" class="mt-3" style="display:none;">
                                <img id="previewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 150px;">
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-cog mr-1"></i>Pengaturan Tampilan</h5>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured">
                                        <label class="custom-control-label" for="is_featured">
                                            <span class="badge badge-warning"><i class="mdi mdi-star"></i></span> Acara Unggulan
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1">Tonjolkan acara ini</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="countdown_enabled" name="countdown_enabled">
                                        <label class="custom-control-label" for="countdown_enabled">
                                            <span class="badge badge-info"><i class="mdi mdi-timer"></i></span> Hitung Mundur
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1">Tampilkan countdown</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" checked>
                                        <label class="custom-control-label" for="is_active">
                                            <span class="badge badge-success">Aktif</span> Tampilkan
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1">Tampilkan di website</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="countdown_date_group" style="display:none;">
                            <label for="countdown_date">Tanggal & Waktu Countdown</label>
                            <input type="datetime-local" class="form-control" id="countdown_date" name="countdown_date" style="max-width: 300px;">
                            <small class="form-text text-muted">Countdown akan berhenti pada waktu ini</small>
                        </div>

                        <div class="form-group">
                            <label for="order_position">Urutan Tampilan</label>
                            <input type="number" class="form-control" id="order_position" name="order_position" value="1" min="1" max="100" style="width: 100px;">
                            <small class="form-text text-muted">Nilai kecil tampil lebih dulu</small>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="mdi mdi-plus"></i> Tambah Acara
                        </button>
                        <a href="?page=events" class="btn btn-secondary btn-lg btn-cancel">
                            <i class="mdi mdi-arrow-left"></i> Batal
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Panduan -->
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-help-circle mr-2"></i>Panduan Pengisian</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2"><strong>Judul:</strong> Nama acara (wajib)</li>
                        <li class="mb-2"><strong>Tanggal:</strong> Kapan acara berlangsung (wajib)</li>
                        <li class="mb-2"><strong>Waktu:</strong> Jam mulai (opsional)</li>
                        <li class="mb-2"><strong>Lokasi:</strong> Tempat pelaksanaan</li>
                        <li class="mb-0"><strong>Status:</strong> Aktif = tampil di website</li>
                    </ul>
                </div>
            </div>

            <!-- Tips Gambar -->
            <div class="card m-b-30 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="mdi mdi-image mr-2"></i>Tips Gambar</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2"><strong>Ukuran:</strong> 800×500px ideal</li>
                        <li class="mb-2"><strong>Maks:</strong> 2MB</li>
                        <li class="mb-2"><strong>Format:</strong> JPG, PNG, GIF, WEBP</li>
                        <li class="mb-0"><strong>Orientasi:</strong> Landscape</li>
                    </ul>
                </div>
            </div>

            <!-- Contoh Acara -->
            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Contoh Acara</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <span class="badge badge-light p-2 m-1 border">Pengajian Akbar</span>
                        <span class="badge badge-light p-2 m-1 border">Buka Puasa Bersama</span>
                        <span class="badge badge-light p-2 m-1 border">Kajian Rutin</span>
                        <span class="badge badge-light p-2 m-1 border">Shalat Idul Fitri</span>
                    </div>
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
            label.textContent = 'Pilih gambar...';
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
        label.textContent = 'Pilih gambar...';
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
        title: 'Menyimpan...',
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
        title: 'Batalkan?',
        text: 'Data yang sudah diisi akan hilang.',
        showCancelButton: true,
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Lanjut Mengisi',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) window.location.href = link;
    });
});
</script>