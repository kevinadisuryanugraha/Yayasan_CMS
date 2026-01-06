<?php
// Edit Layanan
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'ID Tidak Valid!', 'message' => 'ID layanan diperlukan'];
    header("Location: ?page=services");
    exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM service_section WHERE id = $id");
if (mysqli_num_rows($query) == 0) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Tidak Ditemukan!', 'message' => 'Layanan tidak ditemukan'];
    header("Location: ?page=services");
    exit;
}

$service = mysqli_fetch_assoc($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    if (strlen($category) > 100)
        $errors[] = 'Kategori maksimal 100 karakter';
    $category = mysqli_real_escape_string($conn, $category);

    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    if (empty($title))
        $errors[] = 'Judul wajib diisi';
    elseif (strlen($title) > 150)
        $errors[] = 'Judul maksimal 150 karakter';
    elseif (strlen($title) < 3)
        $errors[] = 'Judul minimal 3 karakter';
    $title = mysqli_real_escape_string($conn, $title);

    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    if (strlen($description) > 1000)
        $errors[] = 'Deskripsi maksimal 1000 karakter';
    $description = mysqli_real_escape_string($conn, $description);

    $link_url = isset($_POST['link_url']) ? trim($_POST['link_url']) : '';
    if (strlen($link_url) > 500)
        $errors[] = 'Link URL maksimal 500 karakter';
    if (!empty($link_url) && !preg_match('/^(#|\/|https?:\/\/)/', $link_url)) {
        $errors[] = 'Format URL tidak valid';
    }
    $link_url = mysqli_real_escape_string($conn, $link_url);

    $order_position = isset($_POST['order_position']) ? intval($_POST['order_position']) : 1;
    if ($order_position < 1 || $order_position > 100)
        $errors[] = 'Urutan harus antara 1 - 100';

    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $main_image_path = $service['main_image'];
    $icon_path = $service['icon'];

    // Upload gambar utama baru
    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] == 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        $file_ext = strtolower(pathinfo($_FILES['main_image']['name'], PATHINFO_EXTENSION));
        if (in_array($file_ext, $allowed) && $_FILES['main_image']['size'] <= 2097152) {
            $new_filename = 'main_' . time() . '_' . uniqid() . '.' . $file_ext;
            if (!is_dir('../uploads/services'))
                mkdir('../uploads/services', 0755, true);
            if (move_uploaded_file($_FILES['main_image']['tmp_name'], '../uploads/services/' . $new_filename)) {
                if ($service['main_image'] && file_exists('../' . $service['main_image']))
                    unlink('../' . $service['main_image']);
                $main_image_path = 'uploads/services/' . $new_filename;
            }
        } elseif ($_FILES['main_image']['size'] > 2097152) {
            $errors[] = 'Ukuran gambar utama maksimal 2MB';
        }
    }

    // Upload ikon baru
    if (isset($_FILES['icon']) && $_FILES['icon']['error'] == 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'svg');
        $file_ext = strtolower(pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION));
        if (in_array($file_ext, $allowed) && $_FILES['icon']['size'] <= 1048576) {
            $new_filename = 'icon_' . time() . '_' . uniqid() . '.' . $file_ext;
            if (!is_dir('../uploads/services'))
                mkdir('../uploads/services', 0755, true);
            if (move_uploaded_file($_FILES['icon']['tmp_name'], '../uploads/services/' . $new_filename)) {
                if ($service['icon'] && file_exists('../' . $service['icon']))
                    unlink('../' . $service['icon']);
                $icon_path = 'uploads/services/' . $new_filename;
            }
        } elseif ($_FILES['icon']['size'] > 1048576) {
            $errors[] = 'Ukuran ikon maksimal 1MB';
        }
    }

    if (empty($errors)) {
        $update = "UPDATE service_section SET 
                   category = '$category', title = '$title', description = '$description',
                   main_image = '$main_image_path', icon = '$icon_path', link_url = '$link_url',
                   order_position = $order_position, is_active = $is_active, updated_at = NOW()
                   WHERE id = $id";

        if (mysqli_query($conn, $update)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Layanan berhasil diperbarui'];
            header("Location: ?page=services");
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
                        <li class="breadcrumb-item"><a href="?page=services">Layanan</a></li>
                        <li class="breadcrumb-item active">Ubah</li>
                    </ol>
                </div>
                <h4 class="page-title">Ubah Layanan</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mt-0 header-title">Detail Layanan</h4>
                            <p class="text-muted mb-0 font-14">Perbarui informasi layanan</p>
                        </div>
                        <span class="badge badge-<?php echo $service['is_active'] ? 'success' : 'secondary'; ?> p-2">
                            <?php echo $service['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                        </span>
                    </div>

                    <form method="POST" action="" enctype="multipart/form-data" id="serviceForm">
                        <div class="form-group row">
                            <label for="category" class="col-sm-3 col-form-label">Kategori</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="category" name="category"
                                    value="<?php echo htmlspecialchars($service['category'] ?? ''); ?>" maxlength="100">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-sm-3 col-form-label">Judul <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" name="title"
                                    value="<?php echo htmlspecialchars($service['title']); ?>" required minlength="3"
                                    maxlength="150">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="description" name="description" rows="4"
                                    maxlength="1000"><?php echo htmlspecialchars($service['description'] ?? ''); ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Gambar Utama</label>
                            <div class="col-sm-9">
                                <?php if (!empty($service['main_image'])): ?>
                                    <div class="mb-2 p-2 bg-light rounded">
                                        <small class="text-muted d-block mb-1">Gambar Saat Ini:</small>
                                        <img src="<?php echo '../' . $service['main_image']; ?>" class="img-fluid rounded"
                                            style="max-height: 120px;">
                                    </div>
                                <?php endif; ?>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="main_image" name="main_image"
                                        accept="image/*">
                                    <label class="custom-file-label" for="main_image">Pilih gambar baru...</label>
                                </div>
                                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah | Maks:
                                    2MB</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Ikon</label>
                            <div class="col-sm-9">
                                <?php if (!empty($service['icon'])): ?>
                                    <div class="mb-2 p-2 bg-light rounded">
                                        <small class="text-muted d-block mb-1">Ikon Saat Ini:</small>
                                        <img src="<?php echo '../' . $service['icon']; ?>" class="img-fluid rounded"
                                            style="max-height: 60px;">
                                    </div>
                                <?php endif; ?>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="icon" name="icon" accept="image/*">
                                    <label class="custom-file-label" for="icon">Pilih ikon baru...</label>
                                </div>
                                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah | Maks:
                                    1MB</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="link_url" class="col-sm-3 col-form-label">Link URL</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="link_url" name="link_url"
                                    value="<?php echo htmlspecialchars($service['link_url'] ?? ''); ?>" maxlength="500">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="order_position" class="col-sm-3 col-form-label">Urutan</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="order_position" name="order_position"
                                    value="<?php echo $service['order_position'] ?? 1; ?>" min="1" max="100"
                                    style="width: 100px;">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                        <?php echo $service['is_active'] ? 'checked' : ''; ?>>
                                    <label class="custom-control-label" for="is_active">
                                        <span class="badge badge-success">Aktif</span> - Tampilkan di halaman depan
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="mdi mdi-content-save"></i> Simpan Perubahan
                                </button>
                                <a href="?page=services" class="btn btn-secondary btn-lg btn-cancel">
                                    <i class="mdi mdi-arrow-left"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card m-b-30 border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-information mr-2"></i>Informasi Data</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">ID:</td>
                            <td><strong>#<?php echo $service['id']; ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Urutan:</td>
                            <td><span class="badge badge-info"><?php echo $service['order_position'] ?? 1; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Diperbarui:</td>
                            <td><?php echo isset($service['updated_at']) ? date('d M Y, H:i', strtotime($service['updated_at'])) : '-'; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-help-circle mr-2"></i>Panduan</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2">Ubah field yang ingin diperbarui</li>
                        <li class="mb-2">Kosongkan field gambar untuk tetap menggunakan yang ada</li>
                        <li class="mb-0">Klik "Simpan Perubahan" untuk menyimpan</li>
                    </ul>
                </div>
            </div>

            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Contoh Layanan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <span class="badge badge-primary p-2 m-1">Kajian Rutin</span>
                        <span class="badge badge-primary p-2 m-1">Konsultasi Syariah</span>
                        <span class="badge badge-primary p-2 m-1">Pendidikan Anak</span>
                        <span class="badge badge-primary p-2 m-1">Zakat & Infaq</span>
                        <span class="badge badge-primary p-2 m-1">Bimbingan Pernikahan</span>
                    </div>
                </div>
            </div>

            <div class="card m-b-30 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="mdi mdi-image mr-2"></i>Tips Gambar</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2"><strong>Gambar utama:</strong> 800x600px, maks 2MB</li>
                        <li class="mb-2"><strong>Ikon:</strong> 100x100px, maks 1MB</li>
                        <li class="mb-0"><strong>Format:</strong> JPG, PNG, WEBP</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $error_script; ?>

<script>
    document.getElementById('main_image').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            this.nextElementSibling.textContent = file.name;
            if (file.size > 2097152) {
                Swal.fire({ icon: 'error', title: 'File Terlalu Besar!', text: 'Maksimal 2MB.', confirmButtonColor: '#dc3545' });
                this.value = ''; this.nextElementSibling.textContent = 'Pilih gambar baru...';
            }
        }
    });

    document.getElementById('icon').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            this.nextElementSibling.textContent = file.name;
            if (file.size > 1048576) {
                Swal.fire({ icon: 'error', title: 'File Terlalu Besar!', text: 'Maksimal 1MB.', confirmButtonColor: '#dc3545' });
                this.value = ''; this.nextElementSibling.textContent = 'Pilih ikon baru...';
            }
        }
    });

    document.getElementById('serviceForm').addEventListener('submit', function (e) {
        const title = document.getElementById('title').value.trim();
        if (title.length < 3) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Judul Terlalu Pendek!', text: 'Minimal 3 karakter.', confirmButtonColor: '#dc3545' });
            return false;
        }
        Swal.fire({ title: 'Menyimpan Perubahan...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
    });

    document.querySelector('.btn-cancel').addEventListener('click', function (e) {
        e.preventDefault();
        Swal.fire({
            icon: 'question', title: 'Batalkan Perubahan?', text: 'Perubahan yang belum disimpan akan hilang.',
            showCancelButton: true, confirmButtonText: 'Ya, Batalkan', cancelButtonText: 'Lanjut Mengubah',
            confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then((result) => { if (result.isConfirmed) window.location.href = this.href; });
    });
</script>