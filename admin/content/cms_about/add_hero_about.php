<?php
// Tambah Hero About Baru
// Proses pengiriman form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Validasi Badge Text (wajib)
    $badge_text = isset($_POST['badge_text']) ? trim($_POST['badge_text']) : '';
    if (empty($badge_text)) {
        $errors[] = 'Badge Text wajib diisi';
    } elseif (strlen($badge_text) > 50) {
        $errors[] = 'Badge Text maksimal 50 karakter';
    }
    $badge_text = mysqli_real_escape_string($conn, $badge_text);

    // Validasi Badge Icon (wajib)
    $badge_icon = isset($_POST['badge_icon']) ? trim($_POST['badge_icon']) : '';
    if (empty($badge_icon)) {
        $errors[] = 'Badge Icon wajib diisi';
    } elseif (strlen($badge_icon) > 50) {
        $errors[] = 'Badge Icon maksimal 50 karakter';
    }
    $badge_icon = mysqli_real_escape_string($conn, $badge_icon);

    // Validasi Judul Utama (wajib)
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    if (empty($title)) {
        $errors[] = 'Judul Utama wajib diisi';
    } elseif (strlen($title) > 255) {
        $errors[] = 'Judul Utama maksimal 255 karakter';
    }
    $title = mysqli_real_escape_string($conn, $title);

    // Simpan ke database jika tidak ada error
    if (empty($errors)) {
        $query = "INSERT INTO about_hero (badge_text, badge_icon, title) VALUES ('$badge_text', '$badge_icon', '$title')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Konten Hero About berhasil ditambahkan'
            ];
            header("Location: ?page=hero_section_about");
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
                        <li class="breadcrumb-item"><a href="?page=hero_section_about">Hero About</a></li>
                        <li class="breadcrumb-item active">Tambah Baru</li>
                    </ol>
                </div>
                <h4 class="page-title">Tambah Hero About Baru</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Form Utama -->
        <div class="col-lg-8">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Detail Hero About</h4>
                    <p class="text-muted m-b-30 font-14">
                        Isi form di bawah ini untuk menambahkan data banner hero baru pada halaman Tentang Kami.
                    </p>

                    <form method="POST" action="" id="heroAboutForm">
                        <!-- Badge Text -->
                        <div class="form-group row">
                            <label for="badge_text" class="col-sm-3 col-form-label">
                                Badge Text <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="badge_text" name="badge_text"
                                    placeholder="Contoh: Yayasan Indonesia Bijak Bestari" required maxlength="50"
                                    value="<?php echo htmlspecialchars($form_data['badge_text'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Teks kecil di atas judul (Maks. 50
                                    karakter)
                                </small>
                            </div>
                        </div>

                        <!-- Badge Icon (Dropdown) -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                Badge Icon <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <select class="form-control select2" id="badge_icon" name="badge_icon" required>
                                    <option value="">-- Pilih Ikon --</option>
                                    <?php
                                    $icons = [
                                        'Education' => ['icofont-hat-alt', 'icofont-education', 'icofont-read-book', 'icofont-book'],
                                        'Charity' => ['icofont-heart-alt', 'icofont-charity', 'icofont-holding-hands', 'icofont-unity-hand'],
                                        'Building' => ['icofont-building-alt', 'icofont-institution', 'icofont-bank-alt'],
                                        'People' => ['icofont-users-alt-5', 'icofont-users', 'icofont-people'],
                                        'Awards' => ['icofont-star', 'icofont-badge', 'icofont-award'],
                                        'Status' => ['icofont-check-circled', 'icofont-info-circle', 'icofont-question-circle'],
                                        'Others' => ['icofont-globe', 'icofont-leaf', 'icofont-ui-calendar']
                                    ];

                                    $current_icon = $form_data['badge_icon'] ?? '';

                                    foreach ($icons as $category => $category_icons):
                                        ?>
                                        <optgroup label="<?php echo $category; ?>">
                                            <?php foreach ($category_icons as $icon): ?>
                                                <option value="<?php echo $icon; ?>" <?php echo ($current_icon == $icon) ? 'selected' : ''; ?>>
                                                    <?php echo $icon; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted mt-2">
                                    <i class="mdi mdi-information-outline"></i> Pilih kode ikon dari daftar
                                    (dikelompokkan berdasarkan kategori).
                                </small>
                            </div>
                        </div>

                        <!-- Judul Utama -->
                        <div class="form-group row">
                            <label for="title" class="col-sm-3 col-form-label">
                                Judul Utama <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Contoh: Membangun Generasi yang Berilmu" required maxlength="255"
                                    value="<?php echo htmlspecialchars($form_data['title'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Headline utama yang mencolok
                                    (Maks. 255 karakter)
                                </small>
                            </div>
                        </div>

                        <hr>

                        <!-- Tombol Submit -->
                        <div class="form-group row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="mdi mdi-plus"></i> Simpan Data
                                </button>
                                <a href="?page=hero_section_about" class="btn btn-secondary btn-lg btn-cancel">
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
                        <strong><i class="mdi mdi-numeric-1-circle text-primary mr-1"></i>Badge Text</strong>
                        <p class="text-muted small mb-0">Label singkat untuk identitas topik. Contoh: "Profile",
                            "Tentang Kami".</p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-2-circle text-primary mr-1"></i>Badge Icon</strong>
                        <p class="text-muted small mb-0">Ikon visual pendamping teks. Pastikan kode ikon valid.</p>
                    </div>
                    <div class="mb-0">
                        <strong><i class="mdi mdi-numeric-3-circle text-primary mr-1"></i>Judul Utama</strong>
                        <p class="text-muted small mb-0">Pesan inti yang ingin disampaikan kepada pengunjung.</p>
                    </div>
                </div>
            </div>

            <!-- Preview Pilihan -->
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-eye mr-2"></i>Preview Pilihan</h5>
                </div>
                <div class="card-body text-center">
                    <div class="p-4 bg-light rounded">
                        <i id="preview-icon"
                            class="<?php echo htmlspecialchars($form_data['badge_icon'] ?? 'icofont-hat-alt'); ?> text-primary"
                            style="font-size: 48px;"></i>
                        <h5 id="preview-text" class="mt-3 mb-2 text-primary text-uppercase"
                            style="letter-spacing: 1px; font-weight: 600; font-size: 14px;">
                            <?php echo htmlspecialchars($form_data['badge_text'] ?? 'Contoh Label'); ?></h5>
                        <h2 id="preview-title" class="mb-0 text-dark" style="font-weight: 700; font-size: 24px;">
                            <?php echo htmlspecialchars($form_data['title'] ?? 'Contoh Judul Utama'); ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $error_script; ?>

<script>
    // Logic Icon Picker
    $(document).ready(function () {
        const badgeIconSelect = $('#badge_icon');
        const previewIcon = $('#preview-icon');
        const previewText = $('#preview-text');
        const badgeTextInput = $('#badge_text');
        const titleInput = $('#title');
        const previewTitle = $('#preview-title');

        // Fungsi format option untuk Select2 (menampilkan ikon)
        function formatIcon(state) {
            if (!state.id) {
                return state.text;
            }
            // Buat elemen span dengan ikon
            var $state = $(
                '<span><i class="' + state.element.value + ' mr-2"></i> ' + state.text + '</span>'
            );
            return $state;
        }

        // Inisialisasi Select2
        badgeIconSelect.select2({
            templateResult: formatIcon,
            templateSelection: formatIcon,
            width: '100%'
        });

        // Function to update preview
        function updatePreview() {
            var selectedIcon = badgeIconSelect.val();
            if (selectedIcon) {
                // Update class using vanilla JS or jQuery
                document.getElementById('preview-icon').className = selectedIcon + ' text-primary';
            } else {
                document.getElementById('preview-icon').className = 'icofont-hat-alt text-primary'; // Default
            }
        }

        // Initialize preview
        updatePreview();
        // previewText is a jQuery object, use .text()
        if (badgeTextInput.val()) {
            previewText.text(badgeTextInput.val());
        }
        if (titleInput.val()) {
            previewTitle.text(titleInput.val());
        }

        // Listen for changes on Select2 dropdown (covering multiple events)
        badgeIconSelect.on('change select2:select', function (e) {
            updatePreview();
        });

        // Live Preview Text
        badgeTextInput.on('input', function () {
            var text = $(this).val() || 'Contoh Label';
            previewText.text(text);
        });

        // Live Preview Title
        titleInput.on('input', function () {
            var text = $(this).val() || 'Contoh Judul Utama';
            previewTitle.text(text);
        });
    });

    // Validasi form sebelum submit
    document.getElementById('heroAboutForm').addEventListener('submit', function (e) {
        const badgeText = document.getElementById('badge_text').value.trim();
        const badgeIcon = document.getElementById('badge_icon').value.trim();
        const title = document.getElementById('title').value.trim();

        if (!badgeText || !badgeIcon || !title) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Data Belum Lengkap!',
                text: 'Harap isi semua field bertanda bintang (*).',
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
        const badgeText = document.getElementById('badge_text').value.trim();
        const badgeIcon = document.getElementById('badge_icon').value.trim();
        const title = document.getElementById('title').value.trim();

        if (badgeText || badgeIcon || title) {
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