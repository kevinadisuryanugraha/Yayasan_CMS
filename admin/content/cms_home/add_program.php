<?php
// Tambah Program Kampanye

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Validasi Kategori
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    if (strlen($category) > 100) {
        $errors[] = 'Kategori maksimal 100 karakter';
    }
    $category = mysqli_real_escape_string($conn, $category);

    // Validasi Judul (wajib)
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    if (empty($title)) {
        $errors[] = 'Judul program wajib diisi';
    } elseif (strlen($title) > 255) {
        $errors[] = 'Judul maksimal 255 karakter';
    } elseif (strlen($title) < 5) {
        $errors[] = 'Judul minimal 5 karakter';
    }
    $title = mysqli_real_escape_string($conn, $title);

    // Validasi Jumlah Terkumpul
    $amount_raised = isset($_POST['amount_raised']) ? floatval($_POST['amount_raised']) : 0;
    if ($amount_raised < 0) {
        $errors[] = 'Jumlah terkumpul tidak boleh negatif';
    }

    // Validasi Target Donasi (wajib)
    $goal_amount = isset($_POST['goal_amount']) ? floatval($_POST['goal_amount']) : 0;
    if ($goal_amount <= 0) {
        $errors[] = 'Target donasi harus lebih dari 0';
    }
    if ($amount_raised > $goal_amount) {
        $errors[] = 'Jumlah terkumpul tidak boleh melebihi target';
    }

    // Validasi Link URL
    $link_url = isset($_POST['link_url']) ? trim($_POST['link_url']) : '';
    if (strlen($link_url) > 500) {
        $errors[] = 'Link URL maksimal 500 karakter';
    }
    if (!empty($link_url) && !preg_match('/^(#|\/|https?:\/\/)/', $link_url)) {
        $errors[] = 'Format link URL tidak valid';
    }
    $link_url = mysqli_real_escape_string($conn, $link_url);

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
            $errors[] = 'Format gambar tidak diizinkan. Hanya JPG, PNG, GIF, WEBP';
        }
        if (!in_array($file_mime, $allowed_mime)) {
            $errors[] = 'Tipe file gambar tidak valid';
        }
        if ($_FILES['image']['size'] > 2097152) {
            $errors[] = 'Ukuran gambar maksimal 2MB';
        }

        if (empty($errors)) {
            $new_filename = 'program_' . time() . '_' . uniqid() . '.' . $file_ext;
            if (!is_dir('../uploads/campaigns'))
                mkdir('../uploads/campaigns', 0755, true);
            if (move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/campaigns/' . $new_filename)) {
                $image_path = 'uploads/campaigns/' . $new_filename;
            } else {
                $errors[] = 'Gagal mengupload gambar';
            }
        }
    }

    // Simpan ke database
    if (empty($errors)) {
        $query = "INSERT INTO campaign_programs (image, category, title, amount_raised, goal_amount, link_url, order_position, is_active) 
                  VALUES ('$image_path', '$category', '$title', $amount_raised, $goal_amount, '$link_url', $order_position, $is_active)";

        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Program berhasil ditambahkan'];
            header("Location: ?page=programs");
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
                        <li class="breadcrumb-item"><a href="?page=programs">Program</a></li>
                        <li class="breadcrumb-item active">Tambah Baru</li>
                    </ol>
                </div>
                <h4 class="page-title">Tambah Program Baru</h4>
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
                            <h5 class="mb-2"><i class="mdi mdi-plus-circle text-success mr-2"></i>Membuat Program Donasi
                                Baru</h5>
                            <p class="mb-0 text-muted">
                                Lengkapi form berikut untuk membuat program kampanye donasi baru.
                                Pastikan judul program jelas dan menarik, serta tentukan target donasi yang realistis.
                            </p>
                        </div>
                        <div class="col-md-3 text-center mt-3 mt-md-0">
                            <i class="mdi mdi-charity text-success" style="font-size: 50px;"></i>
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
                    <h4 class="mt-0 header-title">Detail Program</h4>
                    <p class="text-muted m-b-30 font-14">Isi informasi program donasi</p>

                    <form method="POST" action="" enctype="multipart/form-data" id="programForm">
                        <div class="form-group">
                            <label for="category">Kategori</label>
                            <input type="text" class="form-control" id="category" name="category"
                                placeholder="Contoh: Sosial, Pendidikan, Kesehatan" maxlength="100">
                            <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Kelompokkan
                                program berdasarkan kategori (opsional)</small>
                        </div>

                        <div class="form-group">
                            <label for="title">Judul Program <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="Contoh: Bantuan Yatim Piatu, Sedekah Pangan" required minlength="5"
                                maxlength="255">
                            <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Minimal 5,
                                maksimal 255 karakter</small>
                        </div>

                        <div class="form-group">
                            <label for="image">Gambar Program</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="image"
                                    accept="image/jpeg,image/png,image/gif,image/webp">
                                <label class="custom-file-label" for="image">Pilih gambar...</label>
                            </div>
                            <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Maks 2MB |
                                Ideal: 400x300px (landscape)</small>
                            <div id="imagePreview" class="mt-2" style="display:none;">
                                <img id="previewImg" src="" alt="Preview" class="img-fluid rounded"
                                    style="max-height: 150px;">
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-cash-multiple mr-1"></i>Progress Donasi</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount_raised">Jumlah Terkumpul (Rp)</label>
                                    <input type="number" class="form-control" id="amount_raised" name="amount_raised"
                                        step="1" min="0" value="0">
                                    <small class="form-text text-muted">Biarkan 0 untuk program baru</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="goal_amount">Target Donasi (Rp) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="goal_amount" name="goal_amount"
                                        step="1" min="1" required value="10000000">
                                    <small class="form-text text-muted">Target yang ingin dicapai</small>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-link mr-1"></i>Link & Tampilan</h5>

                        <div class="form-group">
                            <label for="link_url">Link URL</label>
                            <input type="text" class="form-control" id="link_url" name="link_url"
                                placeholder="Contoh: #program1, /donasi/program-1, https://...">
                            <small class="form-text text-muted">Format: <code>#anchor</code>, <code>/halaman</code>,
                                atau <code>https://...</code></small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order_position">Urutan Tampilan</label>
                                    <input type="number" class="form-control" id="order_position" name="order_position"
                                        value="1" min="1" max="100" style="width: 100px;">
                                    <small class="form-text text-muted">Nilai kecil tampil lebih dulu</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <div class="custom-control custom-switch mt-2">
                                        <input type="checkbox" class="custom-control-input" id="is_active"
                                            name="is_active" checked>
                                        <label class="custom-control-label" for="is_active">
                                            <span class="badge badge-success">Aktif</span> - Tampilkan di halaman depan
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="mdi mdi-plus"></i> Tambah Program
                        </button>
                        <a href="?page=programs" class="btn btn-secondary btn-lg btn-cancel">
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
                        <li class="mb-2"><strong>Kategori:</strong> Kelompokkan program, misal: Sosial, Pendidikan</li>
                        <li class="mb-2"><strong>Judul:</strong> Nama program yang jelas dan menarik</li>
                        <li class="mb-2"><strong>Target:</strong> Tentukan target yang realistis dan terukur</li>
                        <li class="mb-0"><strong>Status Aktif:</strong> Program akan tampil di halaman depan</li>
                    </ul>
                </div>
            </div>

            <!-- Contoh Program -->
            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Contoh Program</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <span class="badge badge-light p-2 m-1 border">Bantuan Yatim Piatu</span>
                        <span class="badge badge-light p-2 m-1 border">Sedekah Pangan</span>
                        <span class="badge badge-light p-2 m-1 border">Beasiswa Pendidikan</span>
                        <span class="badge badge-light p-2 m-1 border">Pembangunan Masjid</span>
                        <span class="badge badge-light p-2 m-1 border">Santunan Dhuafa</span>
                    </div>
                </div>
            </div>

            <!-- Tips Gambar -->
            <div class="card m-b-30 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="mdi mdi-image mr-2"></i>Tips Gambar</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2"><strong>Ukuran ideal:</strong> 400 x 300 piksel</li>
                        <li class="mb-2"><strong>Orientasi:</strong> Landscape (horizontal)</li>
                        <li class="mb-2"><strong>Format:</strong> JPG, PNG, GIF, WEBP</li>
                        <li class="mb-0"><strong>Maks file:</strong> 2MB</li>
                    </ul>
                </div>
            </div>

            <!-- Contoh Kategori -->
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-tag-multiple mr-2"></i>Contoh Kategori</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <span class="badge badge-primary p-2 m-1">Sosial</span>
                        <span class="badge badge-primary p-2 m-1">Pendidikan</span>
                        <span class="badge badge-primary p-2 m-1">Kesehatan</span>
                        <span class="badge badge-primary p-2 m-1">Infrastruktur</span>
                        <span class="badge badge-primary p-2 m-1">Kemanusiaan</span>
                        <span class="badge badge-primary p-2 m-1">Bencana Alam</span>
                    </div>
                </div>
            </div>

            <!-- Penjelasan Field -->
            <div class="card m-b-30 border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-form-textbox mr-2"></i>Penjelasan Field</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0 small">
                        <tr>
                            <td width="35%"><strong>Kategori</strong></td>
                            <td>Jenis/kelompok program (opsional)</td>
                        </tr>
                        <tr>
                            <td><strong>Judul</strong> <span class="text-danger">*</span></td>
                            <td>Nama program donasi (wajib)</td>
                        </tr>
                        <tr>
                            <td><strong>Target</strong> <span class="text-danger">*</span></td>
                            <td>Total donasi yang ingin dicapai</td>
                        </tr>
                        <tr>
                            <td><strong>Terkumpul</strong></td>
                            <td>Donasi yang sudah masuk</td>
                        </tr>
                        <tr>
                            <td><strong>Urutan</strong></td>
                            <td>Posisi tampilan (kecil = duluan)</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $error_script; ?>

<script>
    // Preview gambar
    document.getElementById('image').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const label = this.nextElementSibling;

        if (file) {
            label.textContent = file.name;

            if (file.size > 2097152) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar!',
                    text: 'Ukuran file maksimal 2MB.',
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

                // Validasi dimensi gambar
                const img = new Image();
                img.onload = function() {
                    const width = this.width;
                    const height = this.height;

                    if (width < 300 || height < 200) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Dimensi Gambar Kecil!',
                            html: 'Dimensi gambar: <strong>' + width + ' x ' + height + '</strong> piksel.<br><br>Disarankan minimal <strong>400 x 300</strong> piksel untuk hasil terbaik.',
                            confirmButtonText: 'Mengerti',
                            confirmButtonColor: '#ffc107'
                        });
                    } else if (height > width) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Orientasi Portrait',
                            html: 'Gambar program disarankan menggunakan orientasi <strong>Landscape</strong> (lebar lebih besar dari tinggi).<br><br>Dimensi saat ini: <strong>' + width + ' x ' + height + '</strong> piksel.',
                            confirmButtonText: 'Mengerti',
                            confirmButtonColor: '#17a2b8'
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Gambar Siap!',
                            text: 'Dimensi: ' + width + ' x ' + height + ' piksel',
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

    // Validasi form
    document.getElementById('programForm').addEventListener('submit', function (e) {
        const title = document.getElementById('title').value.trim();
        const goalAmount = parseFloat(document.getElementById('goal_amount').value);
        const amountRaised = parseFloat(document.getElementById('amount_raised').value);

        if (title.length < 5) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Judul Terlalu Pendek!',
                text: 'Judul harus minimal 5 karakter.',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#dc3545'
            });
            return false;
        }

        if (goalAmount <= 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Target Tidak Valid!',
                text: 'Target donasi harus lebih dari 0.',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#dc3545'
            });
            return false;
        }

        if (amountRaised > goalAmount) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Jumlah Melebihi Target!',
                text: 'Jumlah terkumpul tidak boleh melebihi target donasi.',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#ffc107'
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
    document.querySelector('.btn-cancel').addEventListener('click', function (e) {
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