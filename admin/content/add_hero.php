<?php
// Tambah Konten Hero Baru
// Proses pengiriman form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Validasi Judul (wajib)
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    if (empty($title)) {
        $errors[] = 'Judul wajib diisi';
    } elseif (strlen($title) > 255) {
        $errors[] = 'Judul maksimal 255 karakter';
    } elseif (strlen($title) < 3) {
        $errors[] = 'Judul minimal 3 karakter';
    }
    $title = mysqli_real_escape_string($conn, $title);

    // Validasi Sub Judul (opsional)
    $subtitle = isset($_POST['subtitle']) ? trim($_POST['subtitle']) : '';
    if (strlen($subtitle) > 255) {
        $errors[] = 'Sub judul maksimal 255 karakter';
    }
    $subtitle = mysqli_real_escape_string($conn, $subtitle);

    // Validasi Deskripsi (opsional)
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    if (strlen($description) > 1000) {
        $errors[] = 'Deskripsi maksimal 1000 karakter';
    }
    $description = mysqli_real_escape_string($conn, $description);

    // Validasi Teks Tombol (opsional tapi jika diisi harus valid)
    $button_text = isset($_POST['button_text']) ? trim($_POST['button_text']) : '';
    if (strlen($button_text) > 100) {
        $errors[] = 'Teks tombol maksimal 100 karakter';
    }
    $button_text = mysqli_real_escape_string($conn, $button_text);

    // Validasi Link Tombol
    $button_link = isset($_POST['button_link']) ? trim($_POST['button_link']) : '';
    if (!empty($button_text) && empty($button_link)) {
        $errors[] = 'Link tombol wajib diisi jika teks tombol ada';
    }
    if (strlen($button_link) > 500) {
        $errors[] = 'Link tombol maksimal 500 karakter';
    }
    // Validasi format URL jika diisi dan bukan anchor/relative
    if (!empty($button_link) && !preg_match('/^(#|\/|https?:\/\/)/', $button_link)) {
        $errors[] = 'Format link tombol tidak valid. Gunakan format: #anchor, /halaman, atau https://link';
    }
    $button_link = mysqli_real_escape_string($conn, $button_link);

    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Validasi dan upload gambar
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        $allowed_mime = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
        $filename = $_FILES['image']['name'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $file_mime = mime_content_type($_FILES['image']['tmp_name']);

        // Validasi ekstensi file
        if (!in_array($file_ext, $allowed)) {
            $errors[] = 'Format file tidak diizinkan. Hanya JPG, JPEG, PNG, GIF, WEBP yang diperbolehkan';
        }

        // Validasi MIME type untuk keamanan
        if (!in_array($file_mime, $allowed_mime)) {
            $errors[] = 'Tipe file tidak valid. Pastikan file adalah gambar yang benar';
        }

        // Validasi ukuran file (max 3MB)
        if ($_FILES['image']['size'] > 3145728) {
            $errors[] = 'Ukuran file terlalu besar. Maksimal 3MB';
        }

        // Validasi dimensi gambar (minimal 800x400)
        if (empty($errors)) {
            $img_info = getimagesize($_FILES['image']['tmp_name']);
            if ($img_info) {
                $width = $img_info[0];
                $height = $img_info[1];
                if ($width < 800 || $height < 400) {
                    $errors[] = "Dimensi gambar terlalu kecil ({$width}x{$height}). Minimal 800x400 piksel";
                }
            }
        }

        // Upload jika tidak ada error
        if (empty($errors)) {
            $new_filename = 'hero_' . time() . '_' . uniqid() . '.' . $file_ext;
            $upload_path = '../uploads/hero/' . $new_filename;

            if (!is_dir('../uploads/hero')) {
                mkdir('../uploads/hero', 0755, true);
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image_path = 'uploads/hero/' . $new_filename;
            } else {
                $errors[] = 'Gagal mengupload file gambar. Silakan coba lagi';
            }
        }
    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] != 4) {
        // Error selain "no file uploaded"
        $upload_errors = [
            1 => 'Ukuran file melebihi batas maksimal server',
            2 => 'Ukuran file melebihi batas maksimal form',
            3 => 'File hanya terupload sebagian',
            6 => 'Folder temporary tidak ditemukan',
            7 => 'Gagal menulis file ke disk',
            8 => 'Upload dihentikan oleh ekstensi PHP'
        ];
        $errors[] = $upload_errors[$_FILES['image']['error']] ?? 'Terjadi kesalahan saat upload';
    }

    // Simpan ke database jika tidak ada error
    if (empty($errors)) {
        $query = "INSERT INTO hero_section (title, subtitle, description, image, button_text, button_link, is_active) 
                  VALUES ('$title', '$subtitle', '$description', '$image_path', '$button_text', '$button_link', $is_active)";

        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Konten hero berhasil ditambahkan'
            ];
            header("Location: ?page=hero");
            exit;
        } else {
            $errors[] = 'Gagal menyimpan ke database: ' . mysqli_error($conn);
        }
    }

    // Set error alert jika ada
    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_data'] = $_POST; // Simpan data form untuk ditampilkan kembali
    }
}

// Ambil data form sebelumnya jika ada error
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);
$form_errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);

// Siapkan script SweetAlert untuk error
$error_script = '';
if (!empty($form_errors)) {
    $error_list = '<ul style="text-align:left;margin:0;padding-left:20px;">';
    foreach ($form_errors as $error) {
        $error_list .= '<li>' . htmlspecialchars($error) . '</li>';
    }
    $error_list .= '</ul>';
    $error_script = "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                html: '" . addslashes($error_list) . "',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#dc3545'
            });
        });
    </script>
    ";
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
                        <li class="breadcrumb-item"><a href="?page=hero">Hero Section</a></li>
                        <li class="breadcrumb-item active">Tambah Baru</li>
                    </ol>
                </div>
                <h4 class="page-title">Tambah Konten Hero Baru</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Form Utama -->
        <div class="col-lg-8">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Detail Banner Hero</h4>
                    <p class="text-muted m-b-30 font-14">
                        Buat banner hero baru untuk ditampilkan di header halaman utama
                    </p>

                    <form method="POST" action="" enctype="multipart/form-data" id="heroForm">
                        <!-- Judul -->
                        <div class="form-group row">
                            <label for="title" class="col-sm-3 col-form-label">
                                Judul <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Contoh: Selamat Datang di Hafsa Islamic Center" required minlength="3"
                                    maxlength="255" value="<?php echo htmlspecialchars($form_data['title'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Teks utama yang besar dan mencolok
                                    (3-255 karakter)
                                </small>
                            </div>
                        </div>

                        <!-- Sub Judul -->
                        <div class="form-group row">
                            <label for="subtitle" class="col-sm-3 col-form-label">Sub Judul</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="subtitle" name="subtitle"
                                    placeholder="Contoh: Bersama Membangun Umat" maxlength="255"
                                    value="<?php echo htmlspecialchars($form_data['subtitle'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Teks pendukung di bawah judul (opsional)
                                </small>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group row">
                            <label for="description" class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="description" name="description" rows="3"
                                    placeholder="Deskripsi singkat tentang hero banner ini (opsional)"
                                    maxlength="1000"><?php echo htmlspecialchars($form_data['description'] ?? ''); ?></textarea>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Penjelasan tambahan, maksimal 1000
                                    karakter
                                </small>
                            </div>
                        </div>

                        <!-- Gambar -->
                        <div class="form-group row">
                            <label for="image" class="col-sm-3 col-form-label">Gambar Hero</label>
                            <div class="col-sm-9">
                                <div class="custom-file mb-2">
                                    <input type="file" class="custom-file-input" id="image" name="image"
                                        accept="image/jpeg,image/png,image/gif,image/webp">
                                    <label class="custom-file-label" for="image">Pilih gambar...</label>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i>
                                    <strong>Format:</strong> JPG, PNG, GIF, WEBP |
                                    <strong>Ukuran:</strong> Maks 3MB |
                                    <strong>Dimensi:</strong> Min 800x400 piksel (disarankan 1920x600)
                                </small>
                                <!-- Preview gambar -->
                                <div id="imagePreview" class="mt-2" style="display:none;">
                                    <img id="previewImg" src="" alt="Preview" class="img-fluid rounded"
                                        style="max-height: 200px;">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-cursor-default-click mr-1"></i>Tombol Aksi (CTA)</h5>

                        <!-- Teks Tombol -->
                        <div class="form-group row">
                            <label for="button_text" class="col-sm-3 col-form-label">Teks Tombol</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="button_text" name="button_text"
                                    placeholder="Contoh: Donasi Sekarang, Daftar Event, Pelajari Lebih Lanjut"
                                    maxlength="100"
                                    value="<?php echo htmlspecialchars($form_data['button_text'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Teks yang muncul di tombol (opsional)
                                </small>
                            </div>
                        </div>

                        <!-- Link Tombol -->
                        <div class="form-group row">
                            <label for="button_link" class="col-sm-3 col-form-label">Link Tombol</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="button_link" name="button_link"
                                    placeholder="Contoh: #donasi, /kontak, https://link-tujuan.com" maxlength="500"
                                    value="<?php echo htmlspecialchars($form_data['button_link'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i>
                                    URL tujuan saat tombol diklik. Format: <code>#anchor</code>, <code>/halaman</code>,
                                    atau <code>https://...</code>
                                </small>
                            </div>
                        </div>

                        <hr>

                        <!-- Status -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                        <?php echo (!isset($form_data['is_active']) || isset($form_data['is_active'])) ? 'checked' : ''; ?>>
                                    <label class="custom-control-label" for="is_active">
                                        <span class="badge badge-success">Aktif</span> - Tampilkan di halaman depan
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Hanya hero dengan status "Aktif" yang
                                    ditampilkan di website
                                </small>
                            </div>
                        </div>

                        <hr>

                        <!-- Tombol Submit -->
                        <div class="form-group row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="mdi mdi-plus"></i> Tambah Konten Hero
                                </button>
                                <a href="?page=hero" class="btn btn-secondary btn-lg btn-cancel">
                                    <i class="mdi mdi-arrow-left"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel Informasi -->
        <div class="col-lg-4">
            <!-- Panduan Cepat -->
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-help-circle mr-2"></i>Panduan Pengisian</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-1-circle text-primary mr-1"></i>Judul (Wajib)</strong>
                        <p class="text-muted small mb-0">Buat judul yang menarik perhatian. Contoh: "Berbagi Kebaikan
                            Bersama Hafsa"</p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-2-circle text-primary mr-1"></i>Sub Judul</strong>
                        <p class="text-muted small mb-0">Tambahkan kalimat pendukung yang menjelaskan judul</p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-3-circle text-primary mr-1"></i>Gambar</strong>
                        <p class="text-muted small mb-0">Upload gambar berkualitas tinggi untuk tampilan terbaik</p>
                    </div>
                    <div class="mb-0">
                        <strong><i class="mdi mdi-numeric-4-circle text-primary mr-1"></i>Tombol Aksi</strong>
                        <p class="text-muted small mb-0">Arahkan pengunjung ke halaman penting seperti donasi atau
                            pendaftaran</p>
                    </div>
                </div>
            </div>

            <!-- Tips Gambar -->
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-image mr-2"></i>Tips Gambar</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2"><strong>Ukuran ideal:</strong> 1920 x 600 piksel</li>
                        <li class="mb-2"><strong>Rasio aspek:</strong> 16:9 atau 3:1</li>
                        <li class="mb-2"><strong>Format terbaik:</strong> JPG untuk foto, PNG untuk grafis</li>
                        <li class="mb-2"><strong>Ukuran file:</strong> Kompres di bawah 500KB untuk loading cepat</li>
                        <li class="mb-0"><strong>Konten:</strong> Hindari teks penting di tepi gambar</li>
                    </ul>
                </div>
            </div>

            <!-- Contoh Tombol CTA -->
            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-cursor-pointer mr-2"></i>Contoh Teks Tombol</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge badge-primary p-2 m-1">Donasi Sekarang</span>
                        <span class="badge badge-primary p-2 m-1">Daftar Event</span>
                        <span class="badge badge-primary p-2 m-1">Pelajari Lebih Lanjut</span>
                        <span class="badge badge-primary p-2 m-1">Hubungi Kami</span>
                        <span class="badge badge-primary p-2 m-1">Lihat Program</span>
                        <span class="badge badge-primary p-2 m-1">Bergabung Sekarang</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $error_script; ?>

<script>
    // Preview gambar sebelum upload
    document.getElementById('image').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const label = document.querySelector('.custom-file-label');

        if (file) {
            label.textContent = file.name;

            // Validasi ukuran file
            if (file.size > 3145728) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar!',
                    text: 'Ukuran file maksimal 3MB. Silakan kompres atau pilih file lain.',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#dc3545'
                });
                this.value = '';
                label.textContent = 'Pilih gambar...';
                preview.style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';

                // Cek dimensi gambar
                const img = new Image();
                img.onload = function () {
                    if (this.width < 800 || this.height < 400) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Dimensi Gambar Kecil',
                            html: 'Dimensi gambar Anda <strong>' + this.width + 'x' + this.height + '</strong> piksel.<br>Disarankan minimal <strong>800x400</strong> piksel untuk hasil terbaik.',
                            confirmButtonText: 'Mengerti',
                            confirmButtonColor: '#ffc107'
                        });
                    } else {
                        // Toast sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Gambar Siap!',
                            text: 'Dimensi: ' + this.width + 'x' + this.height + ' piksel',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
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

    // Validasi form sebelum submit
    document.getElementById('heroForm').addEventListener('submit', function (e) {
        const title = document.getElementById('title').value.trim();
        const buttonText = document.getElementById('button_text').value.trim();
        const buttonLink = document.getElementById('button_link').value.trim();

        if (title.length < 3) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Judul Terlalu Pendek!',
                text: 'Judul harus minimal 3 karakter.',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#dc3545'
            });
            return false;
        }

        if (buttonText && !buttonLink) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Link Tombol Kosong!',
                text: 'Jika teks tombol diisi, link tombol juga harus diisi.',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#dc3545'
            });
            return false;
        }

        // Tampilkan loading saat submit
        Swal.fire({
            title: 'Menyimpan Data...',
            html: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        return true;
    });

    // Konfirmasi batal dengan SweetAlert
    document.querySelector('.btn-cancel').addEventListener('click', function (e) {
        e.preventDefault();
        const link = this.href;

        // Cek apakah form ada isinya
        const title = document.getElementById('title').value.trim();
        const subtitle = document.getElementById('subtitle').value.trim();
        const description = document.getElementById('description').value.trim();

        if (title || subtitle || description) {
            Swal.fire({
                icon: 'warning',
                title: 'Batalkan Pengisian?',
                text: 'Data yang sudah diisi akan hilang. Yakin ingin membatalkan?',
                showCancelButton: true,
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Lanjut Mengisi',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = link;
                }
            });
        } else {
            window.location.href = link;
        }
    });
</script>