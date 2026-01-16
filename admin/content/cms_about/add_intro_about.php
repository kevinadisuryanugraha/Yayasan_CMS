<?php
// Tambah Intro About Baru
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

    // Validasi Subjudul
    $subtitle = isset($_POST['subtitle']) ? trim($_POST['subtitle']) : '';
    if (strlen($subtitle) > 255) {
        $errors[] = 'Subjudul maksimal 255 karakter';
    }
    $subtitle = mysqli_real_escape_string($conn, $subtitle);

    // Validasi Sub-heading
    $sub_heading = isset($_POST['sub_heading']) ? trim($_POST['sub_heading']) : '';
    $sub_heading = mysqli_real_escape_string($conn, $sub_heading);

    // Validasi Deskripsi
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $description = mysqli_real_escape_string($conn, $description);

    // Validasi Statistik
    $stat_number = isset($_POST['stat_number']) ? trim($_POST['stat_number']) : '';
    $stat_text = isset($_POST['stat_text']) ? trim($_POST['stat_text']) : '';
    $stat_icon = isset($_POST['stat_icon']) ? trim($_POST['stat_icon']) : '';

    $stat_number = mysqli_real_escape_string($conn, $stat_number);
    $stat_text = mysqli_real_escape_string($conn, $stat_text);
    $stat_icon = mysqli_real_escape_string($conn, $stat_icon);

    // Validasi Tombol
    $button_text = isset($_POST['button_text']) ? trim($_POST['button_text']) : '';
    $button_link = isset($_POST['button_link']) ? trim($_POST['button_link']) : '';

    if (strlen($button_text) > 100) {
        $errors[] = 'Teks tombol maksimal 100 karakter';
    }
    if (!empty($button_text) && empty($button_link)) {
        $errors[] = 'Link tombol wajib diisi jika teks tombol ada';
    }
    if (strlen($button_link) > 500) {
        $errors[] = 'Link tombol maksimal 500 karakter';
    }

    $button_text = mysqli_real_escape_string($conn, $button_text);
    $button_link = mysqli_real_escape_string($conn, $button_link);

    // Validasi Gambar
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        $allowed_mime = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
        $filename = $_FILES['image']['name'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $file_mime = mime_content_type($_FILES['image']['tmp_name']);

        if (!in_array($file_ext, $allowed)) {
            $errors[] = 'Format file tidak diizinkan. Hanya JPG, JPEG, PNG, GIF, WEBP yang diperbolehkan';
        }

        if (!in_array($file_mime, $allowed_mime)) {
            $errors[] = 'Tipe file tidak valid. Pastikan file adalah gambar yang benar';
        }

        if ($_FILES['image']['size'] > 3145728) { // Max 3MB
            $errors[] = 'Ukuran file terlalu besar. Maksimal 3MB';
        }

        // Cek dimensi (opsional warning, tapi server save tetap jalan jika valid file)
        // Disini kita proceed saja asalkan file valid

        if (empty($errors)) {
            $new_filename = 'about_intro_' . time() . '_' . uniqid() . '.' . $file_ext;
            $target_dir = '../uploads/about/';

            if (!is_dir($target_dir))
                mkdir($target_dir, 0755, true);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $new_filename)) {
                $image_path = 'uploads/about/' . $new_filename;
            } else {
                $errors[] = 'Gagal mengupload gambar. Silakan coba lagi.';
            }
        }
    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] != 4) {
        $errors[] = 'Terjadi kesalahan saat upload gambar.';
    }

    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Simpan ke database jika tidak ada error
    if (empty($errors)) {
        $query = "INSERT INTO about_section 
            (title, subtitle, sub_heading, description, image, button_text, button_link, stat_number, stat_text, stat_icon, is_active) 
            VALUES 
            ('$title', '$subtitle', '$sub_heading', '$description', '$image_path', '$button_text', '$button_link', '$stat_number', '$stat_text', '$stat_icon', $is_active)";

        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Konten Intro berhasil ditambahkan'
            ];
            header("Location: ?page=intro_section_about");
            exit;
        } else {
            $errors[] = 'Gagal menyimpan ke database: ' . mysqli_error($conn);
        }
    }

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
    }
}

// Data form sebelumnya
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);
$form_errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);

// Script Error
$error_script = '';
if (!empty($form_errors)) {
    $error_list = '<ul style="text-align:left;margin:0;padding-left:20px;">';
    foreach ($form_errors as $error)
        $error_list .= '<li>' . htmlspecialchars($error) . '</li>';
    $error_list .= '</ul>';
    $error_script = "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                html: '" . addslashes($error_list) . "',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#dc3545'
            });
        });
    </script>";
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=intro_section_about">Intro About</a></li>
                        <li class="breadcrumb-item active">Tambah Baru</li>
                    </ol>
                </div>
                <h4 class="page-title">Tambah Konten Intro Baru</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Form Utama -->
        <div class="col-lg-8">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Detail Konten Intro</h4>
                    <p class="text-muted m-b-30 font-14">
                        Isi formulir di bawah ini untuk menambahkan konten pengantar baru.
                    </p>

                    <form method="POST" enctype="multipart/form-data" id="introForm">

                        <!-- Judul -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Judul (Title) <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" name="title" required
                                    placeholder="Contoh: Selamat Datang di Yayasan Kami" minlength="3" maxlength="255"
                                    value="<?php echo htmlspecialchars($form_data['title'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Judul utama section (Wajib, 3-255
                                    karakter)
                                </small>
                            </div>
                        </div>

                        <!-- Subjudul -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Subjudul</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="subtitle"
                                    placeholder="Contoh: Dedikasi Untuk Negeri" maxlength="255"
                                    value="<?php echo htmlspecialchars($form_data['subtitle'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Teks kecil di atas judul (Opsional)
                                </small>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="description" name="description" rows="5"
                                    placeholder="Tuliskan deskripsi singkat profil yayasan..."><?php echo htmlspecialchars($form_data['description'] ?? ''); ?></textarea>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Penjelasan detail yang akan muncul.
                                    Disarankan maksimal 3 paragraf.
                                </small>
                            </div>
                        </div>

                        <!-- Gambar -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Gambar Ilustrasi</label>
                            <div class="col-sm-9">
                                <div class="custom-file mb-2">
                                    <input type="file" class="custom-file-input" id="image" name="image"
                                        accept="image/jpeg,image/png,image/gif,image/webp">
                                    <label class="custom-file-label" for="image">Pilih Gambar...</label>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i>
                                    <strong>Format:</strong> JPG, PNG, WEBP | <strong>Max:</strong> 3MB | <strong>Min
                                        Dimensi:</strong> 800x400px
                                </small>
                                <div id="imagePreview" class="mt-2" style="display:none;">
                                    <img id="previewImg" src="" class="img-fluid rounded shadow-sm"
                                        style="max-height: 200px;">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-chart-bar mr-1"></i>Kartu Statistik (Opsional)</h5>

                        <!-- Stats Number -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Angka Statistik</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="stat_number"
                                    placeholder="Contoh: 150+, 10 Th"
                                    value="<?php echo htmlspecialchars($form_data['stat_number'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    Angka pencapaian yang ingin ditonjolkan.
                                </small>
                            </div>
                        </div>

                        <!-- Stats Text -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Label Statistik</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="stat_text"
                                    placeholder="Contoh: Tahun Pengalaman, Siswa Terdaftar"
                                    value="<?php echo htmlspecialchars($form_data['stat_text'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    Keterangan untuk angka statistik tersebut.
                                </small>
                            </div>
                        </div>

                        <!-- Stats Icon -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Ikon Statistik</label>
                            <div class="col-sm-9">
                                <select class="form-control select2" id="stat_icon" name="stat_icon">
                                    <option value="">-- Pilih Ikon --</option>
                                    <option value="icofont-calendar">Calendar</option>
                                    <option value="icofont-users">Users</option>
                                    <option value="icofont-star">Star</option>
                                    <option value="icofont-globe">Globe</option>
                                    <option value="icofont-building-alt">Building</option>
                                    <option value="icofont-chart-growth">Growth</option>
                                    <option value="icofont-award">Award</option>
                                    <option value="icofont-education">Education</option>
                                </select>
                                <small class="form-text text-muted">
                                    Pilih ikon yang paling merepresentasikan statistik Anda.
                                </small>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-cursor-default-click mr-1"></i>Tombol Aksi (CTA)</h5>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Teks Tombol</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="button_text" name="button_text"
                                    placeholder="Contoh: Selengkapnya"
                                    value="<?php echo htmlspecialchars($form_data['button_text'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Link Tombol</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="button_link" name="button_link"
                                    placeholder="Contoh: #visi-misi"
                                    value="<?php echo htmlspecialchars($form_data['button_link'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    Wajib diisi jika Teks Tombol diisi.
                                </small>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                        checked>
                                    <label class="custom-control-label" for="is_active">
                                        <span class="badge badge-success">Aktif</span> - Tampilkan di halaman depan
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row m-t-20">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="mdi mdi-content-save"></i> Simpan Data
                                </button>
                                <a href="?page=intro_section_about" class="btn btn-secondary btn-lg btn-cancel">
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
            <!-- Panduan Pengisian -->
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-help-circle mr-2"></i>Panduan Pengisian</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-1-circle text-primary mr-1"></i>Judul & Deskripsi</strong>
                        <p class="text-muted small mb-0">Bagian ini menjelaskan siapa Anda. Buatlah menarik dan
                            informatif.</p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-2-circle text-primary mr-1"></i>Statistik Valid</strong>
                        <p class="text-muted small mb-0">Gunakan data nyata (misal: jumlah alumni, tahun berdiri) untuk
                            membangun kepercayaan.</p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-3-circle text-primary mr-1"></i>Visual</strong>
                        <p class="text-muted small mb-0">Gambar di sebelah teks intro sangat mempengaruhi <i>First
                                Impression</i>.</p>
                    </div>
                </div>
            </div>

            <!-- Preview Statistik -->
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-eye mr-2"></i>Live Preview Statistik</h5>
                </div>
                <div class="card-body text-center">
                    <div class="p-3 border rounded bg-light">
                        <i id="preview-stat-icon" class="icofont-calendar display-4 text-primary"></i>
                        <h2 id="preview-stat-number" class="font-weight-bold mt-2 text-dark">150+</h2>
                        <p id="preview-stat-text" class="mb-0 text-muted">Contoh Label</p>
                    </div>
                    <small class="text-muted mt-2 d-block">Simulasi tampilan kartu statistik</small>
                </div>
            </div>

            <!-- Tips Gambar -->
            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-image-filter-hdr mr-2"></i>Tips Gambar</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2"><strong>Orientasi:</strong> Landscape atau Persegi Panjang.</li>
                        <li class="mb-2"><strong>Resolusi:</strong> Disarankan HD (1280x720) atau Full HD.</li>
                        <li class="mb-2"><strong>Fokus:</strong> Pastikan subjek utama gambar terlihat jelas.</li>
                        <li class="mb-0"><strong>File:</strong> Kompres gambar agar loading website tetap cepat.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $error_script; ?>

<script>
    $(document).ready(function () {
        // Init Select2
        function formatIcon(state) {
            if (!state.id) return state.text;
            return $('<span><i class="' + state.element.value + ' mr-2"></i> ' + state.text + '</span>');
        }
        $('.select2').select2({
            templateResult: formatIcon,
            templateSelection: formatIcon,
            width: '100%'
        });

        // Image Preview & Validation
        $('#image').change(function (e) {
            const file = this.files[0];
            const label = $(this).next('.custom-file-label');
            const preview = $('#imagePreview');
            const previewImg = $('#previewImg');

            if (file) {
                label.text(file.name);

                // Validate Size (>3MB)
                if (file.size > 3145728) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Terlalu Besar!',
                        text: 'Maksimal ukuran file adalah 3MB.',
                        confirmButtonColor: '#dc3545'
                    });
                    this.value = '';
                    label.text('Pilih Gambar...');
                    preview.hide();
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (event) {
                    previewImg.attr('src', event.target.result);
                    preview.show();

                    // Check Dimensions
                    const img = new Image();
                    img.onload = function () {
                        if (this.width < 800) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Resolusi Rendah',
                                text: 'Lebar gambar kurang dari 800px. Hasil mungkin pecah.',
                                confirmButtonColor: '#ffc107'
                            });
                        }
                    }
                    img.src = event.target.result;
                }
                reader.readAsDataURL(file);
            } else {
                label.text('Pilih Gambar...');
                preview.hide();
            }
        });

        // Form Validation on Submit
        $('#introForm').on('submit', function (e) {
            const title = $('#title').val().trim();
            const btnText = $('#button_text').val().trim();
            const btnLink = $('#button_link').val().trim();

            if (title.length < 3) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Judul Terlalu Pendek',
                    text: 'Judul minimal 3 karakter',
                    confirmButtonColor: '#dc3545'
                });
                return false;
            }

            if (btnText !== '' && btnLink === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Link Tombol Hilang',
                    text: 'Anda mengisi Teks Tombol, maka Link Tombol wajib diisi.',
                    confirmButtonColor: '#dc3545'
                });
                return false;
            }

            // Show Loading
            Swal.fire({
                title: 'Menyimpan Data...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
        });

        // Cancel Button Confirmation
        $('.btn-cancel').on('click', function (e) {
            e.preventDefault();
            const href = $(this).attr('href');
            const title = $('#title').val();
            const desc = $('#description').val();

            if (title || desc) {
                Swal.fire({
                    icon: 'question',
                    title: 'Batalkan Pengisian?',
                    text: 'Data yang sudah anda ketik akan hilang.',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Batalkan',
                    cancelButtonText: 'Lanjut Mengisi',
                    confirmButtonColor: '#dc3545',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            } else {
                window.location.href = href;
            }
        });

        // Live Preview Stats
        $('input[name="stat_number"]').on('input', function () {
            $('#preview-stat-number').text($(this).val() || '150+');
        });
        $('input[name="stat_text"]').on('input', function () {
            $('#preview-stat-text').text($(this).val() || 'Contoh Label');
        });
        $('#stat_icon').on('change select2:select', function () {
            var iconClass = $(this).val() || 'icofont-calendar';
            $('#preview-stat-icon').attr('class', iconClass + ' display-4 text-primary');
        });
    });
</script>