<?php
// Edit Acara - Complete Form with All Database Fields

// Generate Slug Function
function generateEventSlug($title)
{
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

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
    $slug = generateEventSlug($title);

    // Kategori
    $category = isset($_POST['category']) ? trim($_POST['category']) : 'General';
    $category = mysqli_real_escape_string($conn, $category);

    // Validasi Deskripsi
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    if (strlen($description) > 5000) {
        $errors[] = 'Deskripsi maksimal 5000 karakter';
    }
    $description = mysqli_real_escape_string($conn, $description);

    // Validasi Tanggal (wajib)
    $event_date = isset($_POST['event_date']) ? trim($_POST['event_date']) : '';
    if (empty($event_date)) {
        $errors[] = 'Tanggal acara wajib diisi';
    }
    $event_date = mysqli_real_escape_string($conn, $event_date);

    // Tanggal Selesai
    $date_end = !empty($_POST['date_end']) ? mysqli_real_escape_string($conn, $_POST['date_end']) : null;

    // Waktu
    $event_time = !empty($_POST['event_time']) ? mysqli_real_escape_string($conn, $_POST['event_time']) : null;

    // Lokasi
    $location = isset($_POST['location']) ? trim($_POST['location']) : '';
    if (strlen($location) > 255) {
        $errors[] = 'Lokasi maksimal 255 karakter';
    }
    $location = mysqli_real_escape_string($conn, $location);

    // Alamat & Maps
    $location_address = isset($_POST['location_address']) ? trim($_POST['location_address']) : '';
    $location_address = mysqli_real_escape_string($conn, $location_address);

    $location_maps = isset($_POST['location_maps']) ? trim($_POST['location_maps']) : '';
    $location_maps = mysqli_real_escape_string($conn, $location_maps);

    // Pembicara
    $speaker_name = isset($_POST['speaker_name']) ? trim($_POST['speaker_name']) : '';
    $speaker_name = mysqli_real_escape_string($conn, $speaker_name);

    $speaker_title = isset($_POST['speaker_title']) ? trim($_POST['speaker_title']) : '';
    $speaker_title = mysqli_real_escape_string($conn, $speaker_title);

    $speaker_bio = isset($_POST['speaker_bio']) ? trim($_POST['speaker_bio']) : '';
    $speaker_bio = mysqli_real_escape_string($conn, $speaker_bio);

    // Kapasitas & Harga
    $quota = isset($_POST['quota']) ? intval($_POST['quota']) : 100;
    if ($quota < 1)
        $quota = 100;

    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.00;
    if ($price < 0)
        $price = 0.00;

    // Kontak
    $contact_phone = isset($_POST['contact_phone']) ? trim($_POST['contact_phone']) : '';
    $contact_phone = mysqli_real_escape_string($conn, $contact_phone);

    $contact_whatsapp = isset($_POST['contact_whatsapp']) ? trim($_POST['contact_whatsapp']) : '';
    $contact_whatsapp = mysqli_real_escape_string($conn, $contact_whatsapp);

    // Status & Tampilan
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $countdown_enabled = isset($_POST['countdown_enabled']) ? 1 : 0;
    $countdown_date = !empty($_POST['countdown_date']) ? mysqli_real_escape_string($conn, $_POST['countdown_date']) : null;
    $order_position = isset($_POST['order_position']) ? intval($_POST['order_position']) : 1;
    if ($order_position < 1 || $order_position > 100)
        $order_position = 1;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : 'published';

    // Existing images
    $image_path = $event['image'];
    $image_cover_path = $event['image_cover'];
    $speaker_image_path = $event['speaker_image'];

    // Upload Gambar Utama
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        $allowed_mime = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $file_mime = mime_content_type($_FILES['image']['tmp_name']);

        if (!in_array($file_ext, $allowed)) {
            $errors[] = 'Format gambar utama tidak diizinkan';
        }
        if (!in_array($file_mime, $allowed_mime)) {
            $errors[] = 'Tipe file gambar utama tidak valid';
        }
        if ($_FILES['image']['size'] > 2097152) {
            $errors[] = 'Ukuran gambar utama maksimal 2MB';
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
                $errors[] = 'Gagal mengupload gambar utama';
            }
        }
    }

    // Upload Gambar Cover
    if (isset($_FILES['image_cover']) && $_FILES['image_cover']['error'] == 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        $file_ext = strtolower(pathinfo($_FILES['image_cover']['name'], PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed) && $_FILES['image_cover']['size'] <= 2097152) {
            $new_filename = 'event_cover_' . $id . '_' . time() . '.' . $file_ext;
            if (move_uploaded_file($_FILES['image_cover']['tmp_name'], '../uploads/events/' . $new_filename)) {
                if ($event['image_cover'] && file_exists('../' . $event['image_cover']))
                    unlink('../' . $event['image_cover']);
                $image_cover_path = 'uploads/events/' . $new_filename;
            }
        }
    }

    // Upload Gambar Pembicara
    if (isset($_FILES['speaker_image']) && $_FILES['speaker_image']['error'] == 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        $file_ext = strtolower(pathinfo($_FILES['speaker_image']['name'], PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed) && $_FILES['speaker_image']['size'] <= 2097152) {
            $new_filename = 'speaker_' . $id . '_' . time() . '.' . $file_ext;
            if (move_uploaded_file($_FILES['speaker_image']['tmp_name'], '../uploads/events/' . $new_filename)) {
                if ($event['speaker_image'] && file_exists('../' . $event['speaker_image']))
                    unlink('../' . $event['speaker_image']);
                $speaker_image_path = 'uploads/events/' . $new_filename;
            }
        }
    }

    // Simpan ke database
    if (empty($errors)) {
        $event_time_sql = $event_time ? "'$event_time'" : "NULL";
        $date_end_sql = $date_end ? "'$date_end'" : "NULL";
        $countdown_date_sql = $countdown_date ? "'$countdown_date'" : "NULL";
        $image_cover_sql = $image_cover_path ? "'$image_cover_path'" : "NULL";
        $speaker_image_sql = $speaker_image_path ? "'$speaker_image_path'" : "NULL";

        $update = "UPDATE events SET 
            title = '$title', slug = '$slug', category = '$category', description = '$description',
            event_date = '$event_date', date_end = $date_end_sql, event_time = $event_time_sql,
            location = '$location', location_address = '$location_address', location_maps = '$location_maps',
            image = '$image_path', image_cover = $image_cover_sql,
            speaker_name = '$speaker_name', speaker_title = '$speaker_title', speaker_bio = '$speaker_bio', speaker_image = $speaker_image_sql,
            quota = $quota, price = $price, contact_phone = '$contact_phone', contact_whatsapp = '$contact_whatsapp',
            is_featured = $is_featured, countdown_enabled = $countdown_enabled, countdown_date = $countdown_date_sql,
            order_position = $order_position, is_active = $is_active, status = '$status'
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
        // Refresh event data for form
        $query = mysqli_query($conn, "SELECT * FROM events WHERE id = $id");
        $event = mysqli_fetch_assoc($query);
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
                        <li class="breadcrumb-item active">Edit #<?php echo $id; ?></li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Acara</h4>
            </div>
        </div>
    </div>

    <!-- Info Bar -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30 bg-light">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-1"><i class="mdi mdi-pencil text-primary mr-2"></i>Edit:
                                <?php echo htmlspecialchars($event['title']); ?></h5>
                            <small class="text-muted">ID #<?php echo $event['id']; ?> | Dibuat:
                                <?php echo date('d M Y', strtotime($event['created_at'])); ?></small>
                        </div>
                        <div>
                            <?php if ($event['is_featured']): ?>
                                <span class="badge badge-warning p-2 mr-1"><i class="mdi mdi-star"></i> Unggulan</span>
                            <?php endif; ?>
                            <span
                                class="badge badge-<?php echo $event['status'] == 'published' ? 'success' : ($event['status'] == 'draft' ? 'secondary' : 'dark'); ?> p-2 mr-1">
                                <?php echo ucfirst($event['status']); ?>
                            </span>
                            <span class="badge badge-<?php echo $event['is_active'] ? 'info' : 'light'; ?> p-2">
                                <?php echo $event['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form method="POST" action="" enctype="multipart/form-data" id="eventForm">

                <!-- Section 1: Informasi Dasar -->
                <div class="card m-b-30">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="mdi mdi-information mr-2"></i>Informasi Dasar</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Judul Acara <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required minlength="5"
                                maxlength="255" value="<?php echo htmlspecialchars($event['title']); ?>">
                            <small class="form-text text-muted">Nama acara (5-255 karakter)</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">Kategori</label>
                                    <select class="form-control" id="category" name="category">
                                        <?php
                                        $categories = ['General', 'Kajian', 'Seminar', 'Workshop', 'Pelatihan', 'Sosial'];
                                        foreach ($categories as $cat): ?>
                                            <option value="<?php echo $cat; ?>" <?php echo ($event['category'] ?? 'General') == $cat ? 'selected' : ''; ?>><?php echo $cat; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control select2" id="status" name="status">
                                        <option value="published" <?php echo ($event['status'] ?? 'published') == 'published' ? 'selected' : ''; ?>>✅ Published (Tampil)</option>
                                        <option value="draft" <?php echo ($event['status'] ?? '') == 'draft' ? 'selected' : ''; ?>>📝 Draft (Konsep)</option>
                                        <option value="ended" <?php echo ($event['status'] ?? '') == 'ended' ? 'selected' : ''; ?>>🔒 Ended (Selesai)</option>
                                    </select>
                                    <small class="form-text text-muted">Status publikasi acara</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control tinymce-editor" id="description" name="description"><?php echo htmlspecialchars($event['description'] ?? ''); ?></textarea>
                            <small class="form-text text-muted">Penjelasan lengkap tentang acara</small>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Waktu & Lokasi -->
                <div class="card m-b-30">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="mdi mdi-calendar-clock mr-2"></i>Waktu & Lokasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="event_date">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="event_date" name="event_date" required
                                        value="<?php echo $event['event_date']; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_end">Tanggal Selesai</label>
                                    <input type="datetime-local" class="form-control" id="date_end" name="date_end"
                                        value="<?php echo $event['date_end'] ? date('Y-m-d\TH:i', strtotime($event['date_end'])) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="event_time">Waktu Mulai</label>
                                    <input type="time" class="form-control" id="event_time" name="event_time"
                                        value="<?php echo $event['event_time'] ?? ''; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="location">Lokasi (Nama Tempat)</label>
                            <input type="text" class="form-control" id="location" name="location" maxlength="255"
                                value="<?php echo htmlspecialchars($event['location'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="location_address">Alamat Lengkap</label>
                            <textarea class="form-control tinymce-editor-mini" id="location_address" name="location_address"><?php echo htmlspecialchars($event['location_address'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="location_maps">Link Google Maps</label>
                            <input type="url" class="form-control" id="location_maps" name="location_maps"
                                value="<?php echo htmlspecialchars($event['location_maps'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Gambar -->
                <div class="card m-b-30">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="mdi mdi-image mr-2"></i>Gambar Acara</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gambar Utama (Thumbnail)</label>
                                    <?php if (!empty($event['image'])): ?>
                                        <div class="mb-2 p-2 bg-light rounded">
                                            <img src="<?php echo '../' . $event['image']; ?>" class="img-fluid rounded"
                                                style="max-height: 100px;">
                                        </div>
                                    <?php endif; ?>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="image" name="image"
                                            accept="image/*">
                                        <label class="custom-file-label" for="image">Pilih gambar baru...</label>
                                    </div>
                                    <small class="form-text text-muted">Kosongkan jika tidak mengubah</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gambar Cover (Header)</label>
                                    <?php if (!empty($event['image_cover'])): ?>
                                        <div class="mb-2 p-2 bg-light rounded">
                                            <img src="<?php echo '../' . $event['image_cover']; ?>"
                                                class="img-fluid rounded" style="max-height: 100px;">
                                        </div>
                                    <?php endif; ?>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="image_cover" name="image_cover"
                                            accept="image/*">
                                        <label class="custom-file-label" for="image_cover">Pilih gambar baru...</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Pembicara -->
                <div class="card m-b-30">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="mdi mdi-account-star mr-2"></i>Informasi Pembicara</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="speaker_name">Nama Pembicara</label>
                                    <input type="text" class="form-control" id="speaker_name" name="speaker_name"
                                        value="<?php echo htmlspecialchars($event['speaker_name'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="speaker_title">Jabatan/Gelar</label>
                                    <input type="text" class="form-control" id="speaker_title" name="speaker_title"
                                        value="<?php echo htmlspecialchars($event['speaker_title'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="speaker_bio">Biografi Singkat</label>
                            <textarea class="form-control tinymce-editor-mini" id="speaker_bio" name="speaker_bio"><?php echo htmlspecialchars($event['speaker_bio'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Foto Pembicara</label>
                            <?php if (!empty($event['speaker_image'])): ?>
                                <div class="mb-2 p-2 bg-light rounded">
                                    <img src="<?php echo '../' . $event['speaker_image']; ?>" class="img-fluid rounded"
                                        style="max-height: 80px;">
                                </div>
                            <?php endif; ?>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="speaker_image" name="speaker_image"
                                    accept="image/*">
                                <label class="custom-file-label" for="speaker_image">Pilih foto baru...</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 5: Kapasitas & Kontak -->
                <div class="card m-b-30">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="mdi mdi-account-multiple mr-2"></i>Kapasitas & Kontak</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="quota">Kuota Peserta</label>
                                    <input type="number" class="form-control" id="quota" name="quota" min="1"
                                        value="<?php echo $event['quota'] ?? 100; ?>">
                                    <small class="form-text text-muted">Terdaftar:
                                        <?php echo $event['registered'] ?? 0; ?></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price">Harga Tiket (Rp)</label>
                                    <input type="number" class="form-control" id="price" name="price" min="0"
                                        step="1000" value="<?php echo $event['price'] ?? 0; ?>">
                                    <small class="form-text text-muted">0 = Gratis</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="order_position">Urutan Tampilan</label>
                                    <input type="number" class="form-control" id="order_position" name="order_position"
                                        value="<?php echo $event['order_position'] ?? 1; ?>" min="1" max="100">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone">Nomor Telepon</label>
                                    <input type="tel" class="form-control" id="contact_phone" name="contact_phone"
                                        value="<?php echo htmlspecialchars($event['contact_phone'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_whatsapp">Nomor WhatsApp</label>
                                    <input type="tel" class="form-control" id="contact_whatsapp" name="contact_whatsapp"
                                        value="<?php echo htmlspecialchars($event['contact_whatsapp'] ?? ''); ?>">
                                    <small class="form-text text-muted">Format: 62xxxx</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 6: Pengaturan Tampilan -->
                <div class="card m-b-30">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="mdi mdi-cog mr-2"></i>Pengaturan Tampilan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_featured"
                                            name="is_featured" <?php echo $event['is_featured'] ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="is_featured">
                                            <span class="badge badge-warning"><i class="mdi mdi-star"></i></span> Acara
                                            Unggulan
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="countdown_enabled"
                                            name="countdown_enabled" <?php echo $event['countdown_enabled'] ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="countdown_enabled">
                                            <span class="badge badge-info"><i class="mdi mdi-timer"></i></span> Hitung
                                            Mundur
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active"
                                            name="is_active" <?php echo $event['is_active'] ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="is_active">
                                            <span class="badge badge-success">Aktif</span> Tampilkan
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="countdown_date_group"
                            style="<?php echo $event['countdown_enabled'] ? '' : 'display:none;'; ?>">
                            <label for="countdown_date">Tanggal & Waktu Countdown</label>
                            <input type="datetime-local" class="form-control" id="countdown_date" name="countdown_date"
                                style="max-width: 300px;"
                                value="<?php echo $event['countdown_date'] ? date('Y-m-d\TH:i', strtotime($event['countdown_date'])) : ''; ?>">
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="card m-b-30">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-primary btn-lg mr-2">
                            <i class="mdi mdi-content-save"></i> Simpan Perubahan
                        </button>
                        <a href="?page=events" class="btn btn-secondary btn-lg btn-cancel">
                            <i class="mdi mdi-arrow-left"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <!-- Preview Card -->
            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-eye mr-2"></i>Preview</h5>
                </div>
                <div class="card-body">
                    <div class="event-preview-card"
                        style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                        <div id="preview_image_container"
                            style="height: 120px; background: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                            <?php if ($event['image']): ?>
                                <img src="<?php echo '../' . $event['image']; ?>"
                                    style="width: 100%; height: 120px; object-fit: cover;">
                            <?php else: ?>
                                <i class="mdi mdi-image text-muted" style="font-size: 40px;"></i>
                            <?php endif; ?>
                        </div>
                        <div class="p-3">
                            <span id="preview_category"
                                class="badge badge-primary mb-2"><?php echo htmlspecialchars($event['category'] ?? 'General'); ?></span>
                            <h6 id="preview_title" class="mb-2"><?php echo htmlspecialchars($event['title']); ?></h6>
                            <div class="small text-muted">
                                <i class="mdi mdi-calendar"></i> <span
                                    id="preview_date"><?php echo date('d M Y', strtotime($event['event_date'])); ?></span><br>
                                <i class="mdi mdi-clock"></i> <span
                                    id="preview_time"><?php echo $event['event_time'] ?? '-'; ?></span><br>
                                <i class="mdi mdi-map-marker"></i> <span
                                    id="preview_location"><?php echo htmlspecialchars($event['location'] ?? 'Lokasi'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Data -->
            <div class="card m-b-30 border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-information mr-2"></i>Info Data</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0 small">
                        <tr>
                            <td class="text-muted">ID:</td>
                            <td><strong>#<?php echo $event['id']; ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Slug:</td>
                            <td><code><?php echo $event['slug'] ?? 'auto'; ?></code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Terdaftar:</td>
                            <td><?php echo $event['registered'] ?? 0; ?> / <?php echo $event['quota'] ?? 100; ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dibuat:</td>
                            <td><?php echo date('d M Y H:i', strtotime($event['created_at'])); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Diupdate:</td>
                            <td><?php echo date('d M Y H:i', strtotime($event['updated_at'])); ?></td>
                        </tr>
                    </table>
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
                        <li class="mb-2">Ubah status ke "Ended" jika acara selesai</li>
                        <li class="mb-0">Nonaktifkan untuk sembunyikan dari website</li>
                    </ul>
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
    // Toggle countdown date
    document.getElementById('countdown_enabled').addEventListener('change', function () {
        document.getElementById('countdown_date_group').style.display = this.checked ? 'block' : 'none';
    });

    // Live Preview
    document.getElementById('title').addEventListener('input', function () {
        document.getElementById('preview_title').textContent = this.value || 'Judul Acara';
    });

    document.getElementById('category').addEventListener('change', function () {
        document.getElementById('preview_category').textContent = this.value;
    });

    document.getElementById('event_date').addEventListener('change', function () {
        if (this.value) {
            const date = new Date(this.value);
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            document.getElementById('preview_date').textContent = date.toLocaleDateString('id-ID', options);
        }
    });

    document.getElementById('event_time').addEventListener('change', function () {
        document.getElementById('preview_time').textContent = this.value || '-';
    });

    document.getElementById('location').addEventListener('input', function () {
        document.getElementById('preview_location').textContent = this.value || 'Lokasi';
    });

    // Custom file input labels
    ['image', 'image_cover', 'speaker_image'].forEach(function (id) {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('change', function () {
                const label = this.nextElementSibling;
                if (this.files[0]) {
                    label.textContent = this.files[0].name.length > 25 ? this.files[0].name.substring(0, 22) + '...' : this.files[0].name;
                }
            });
        }
    });

    // Validasi form
    document.getElementById('eventForm').addEventListener('submit', function (e) {
        const title = document.getElementById('title').value.trim();
        const eventDate = document.getElementById('event_date').value;

        if (title.length < 5) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Judul Terlalu Pendek!', text: 'Judul acara minimal 5 karakter.', confirmButtonColor: '#dc3545' });
            return false;
        }

        if (!eventDate) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Tanggal Belum Diisi!', text: 'Tanggal acara wajib diisi.', confirmButtonColor: '#dc3545' });
            return false;
        }

        Swal.fire({ title: 'Menyimpan...', html: 'Mohon tunggu sebentar', allowOutsideClick: false, allowEscapeKey: false, didOpen: () => { Swal.showLoading(); } });
        return true;
    });

    // Konfirmasi batal
    document.querySelector('.btn-cancel').addEventListener('click', function (e) {
        e.preventDefault();
        Swal.fire({
            icon: 'question',
            title: 'Batalkan Perubahan?',
            text: 'Perubahan yang belum disimpan akan hilang.',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Lanjut Mengubah',
            confirmButtonColor: '#dc3545',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) window.location.href = '?page=events';
        });
    });
</script>

<!-- TinyMCE CDN (Free - No API Key Required) -->
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js"></script>
<script>
// TinyMCE Full Editor for Description
tinymce.init({
    selector: '.tinymce-editor',
    height: 300,
    menubar: false,
    plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
    toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code help',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; }',
    branding: false,
    promotion: false,
    license_key: 'gpl'
});

// TinyMCE Mini Editor for Short Descriptions
tinymce.init({
    selector: '.tinymce-editor-mini',
    height: 150,
    menubar: false,
    plugins: 'autolink lists link',
    toolbar: 'bold italic | bullist numlist | link | removeformat',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; }',
    branding: false,
    promotion: false,
    license_key: 'gpl'
});
</script>