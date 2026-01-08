<?php
/**
 * Pengaturan Tampilan - Appearance Settings
 * Kustomisasi Warna, Tipografi, Gaya Tombol, dan Lainnya
 */

// Ambil pengaturan saat ini
$app_query = mysqli_query($conn, "SELECT * FROM appearance_settings WHERE id = 1");
$appearance = mysqli_fetch_assoc($app_query);

// Nilai default jika belum ada
if (!$appearance) {
    $appearance = [
        'primary_color' => '#2E7D32',
        'secondary_color' => '#1565C0',
        'accent_color' => '#FF9800',
        'font_family' => 'Poppins',
        'button_style' => 'rounded'
    ];
}

// Opsi Font dengan deskripsi lengkap
$font_options = [
    'Poppins' => [
        'label' => 'Poppins',
        'desc' => 'Modern, bersih, cocok untuk web profesional',
        'weight' => '300-700'
    ],
    'Roboto' => [
        'label' => 'Roboto',
        'desc' => 'Desain Material Google, sangat mudah dibaca',
        'weight' => '300-700'
    ],
    'Open Sans' => [
        'label' => 'Open Sans',
        'desc' => 'Netral, cocok untuk paragraf panjang',
        'weight' => '300-700'
    ],
    'Lato' => [
        'label' => 'Lato',
        'desc' => 'Elegan, karakteristik hangat & serius',
        'weight' => '300-700'
    ],
    'Montserrat' => [
        'label' => 'Montserrat',
        'desc' => 'Tebal & bold, bagus untuk heading/judul',
        'weight' => '300-700'
    ],
    'Nunito' => [
        'label' => 'Nunito',
        'desc' => 'Lembut & rounded, tampilan ramah',
        'weight' => '300-700'
    ],
    'Inter' => [
        'label' => 'Inter',
        'desc' => 'Optimized untuk layar, sangat jelas',
        'weight' => '300-700'
    ]
];

// Opsi Gaya Tombol dengan deskripsi
$button_styles = [
    'square' => [
        'label' => 'Kotak',
        'radius' => '0px',
        'desc' => 'Tampilan tegas & profesional'
    ],
    'rounded' => [
        'label' => 'Melengkung',
        'radius' => '8px',
        'desc' => 'Modern & ramah pengguna'
    ],
    'pill' => [
        'label' => 'Kapsul',
        'radius' => '50px',
        'desc' => 'Playful & eye-catching'
    ]
];

// Preset Warna dengan nama Indonesia
$color_presets = [
    'islamic_green' => [
        'name' => '🌙 Hijau Islami',
        'primary' => '#2E7D32',
        'secondary' => '#1565C0',
        'accent' => '#FF9800',
        'desc' => 'Warna klasik untuk lembaga keagamaan'
    ],
    'ocean_blue' => [
        'name' => '🔵 Biru Samudra',
        'primary' => '#1976D2',
        'secondary' => '#0D47A1',
        'accent' => '#FFC107',
        'desc' => 'Profesional & terpercaya'
    ],
    'desert_gold' => [
        'name' => '🏜️ Emas Gurun',
        'primary' => '#8D6E63',
        'secondary' => '#5D4037',
        'accent' => '#FFB300',
        'desc' => 'Hangat & mewah'
    ],
    'modern_dark' => [
        'name' => '⚫ Gelap Modern',
        'primary' => '#37474F',
        'secondary' => '#263238',
        'accent' => '#26A69A',
        'desc' => 'Minimalis & elegan'
    ],
    'nature_green' => [
        'name' => '🌿 Hijau Alam',
        'primary' => '#4CAF50',
        'secondary' => '#388E3C',
        'accent' => '#8BC34A',
        'desc' => 'Segar & natural'
    ],
    'royal_purple' => [
        'name' => '👑 Ungu Kerajaan',
        'primary' => '#7B1FA2',
        'secondary' => '#512DA8',
        'accent' => '#E91E63',
        'desc' => 'Premium & istimewa'
    ]
];

// Array error untuk validasi
$errors = [];
$success = false;

// Handle form submission dengan validasi kuat
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil dan sanitasi input
    $primary = trim($_POST['primary_color'] ?? '');
    $secondary = trim($_POST['secondary_color'] ?? '');
    $accent = trim($_POST['accent_color'] ?? '');
    $font = trim($_POST['font_family'] ?? '');
    $btn_style = trim($_POST['button_style'] ?? '');

    // VALIDASI WARNA - format hex yang valid
    $hex_pattern = '/^#[0-9A-Fa-f]{6}$/';

    if (empty($primary)) {
        $errors['primary_color'] = 'Warna utama wajib diisi';
    } elseif (!preg_match($hex_pattern, $primary)) {
        $errors['primary_color'] = 'Format warna utama tidak valid (harus #RRGGBB)';
    }

    if (empty($secondary)) {
        $errors['secondary_color'] = 'Warna sekunder wajib diisi';
    } elseif (!preg_match($hex_pattern, $secondary)) {
        $errors['secondary_color'] = 'Format warna sekunder tidak valid (harus #RRGGBB)';
    }

    if (empty($accent)) {
        $errors['accent_color'] = 'Warna aksen wajib diisi';
    } elseif (!preg_match($hex_pattern, $accent)) {
        $errors['accent_color'] = 'Format warna aksen tidak valid (harus #RRGGBB)';
    }

    // VALIDASI FONT - harus ada di daftar
    if (empty($font)) {
        $errors['font_family'] = 'Jenis huruf wajib dipilih';
    } elseif (!array_key_exists($font, $font_options)) {
        $errors['font_family'] = 'Jenis huruf tidak valid';
    }

    // VALIDASI BUTTON STYLE - harus ada di daftar
    if (empty($btn_style)) {
        $errors['button_style'] = 'Gaya tombol wajib dipilih';
    } elseif (!array_key_exists($btn_style, $button_styles)) {
        $errors['button_style'] = 'Gaya tombol tidak valid';
    }

    // Jika tidak ada error, simpan ke database
    if (empty($errors)) {
        $primary = mysqli_real_escape_string($conn, $primary);
        $secondary = mysqli_real_escape_string($conn, $secondary);
        $accent = mysqli_real_escape_string($conn, $accent);
        $font = mysqli_real_escape_string($conn, $font);
        $btn_style = mysqli_real_escape_string($conn, $btn_style);

        // Cek apakah record sudah ada
        $check = mysqli_query($conn, "SELECT id FROM appearance_settings WHERE id = 1");
        if (mysqli_num_rows($check) == 0) {
            $sql = "INSERT INTO appearance_settings (id, primary_color, secondary_color, accent_color, font_family, button_style) 
                    VALUES (1, '$primary', '$secondary', '$accent', '$font', '$btn_style')";
        } else {
            $sql = "UPDATE appearance_settings SET 
                    primary_color = '$primary',
                    secondary_color = '$secondary',
                    accent_color = '$accent',
                    font_family = '$font',
                    button_style = '$btn_style',
                    updated_at = NOW()
                    WHERE id = 1";
        }

        if (mysqli_query($conn, $sql)) {
            $_SESSION['alert'] = [
                'type' => 'success',
                'title' => 'Berhasil Disimpan!',
                'message' => 'Pengaturan tampilan berhasil diperbarui. Silakan refresh website untuk melihat perubahan.',
                'icon' => 'success'
            ];
            header("Location: ?page=appearance");
            exit;
        } else {
            $errors['database'] = 'Gagal menyimpan ke database: ' . mysqli_error($conn);
        }
    }
}

// Script untuk menampilkan alert
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
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#28a745',
                timer: 5000,
                timerProgressBar: true
            });
        });
    </script>
    ";
    unset($_SESSION['alert']);
}

// Script untuk validasi errors
$validation_script = '';
if (!empty($errors)) {
    $error_list = implode('\\n• ', array_values($errors));
    $validation_script = "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal!',
                html: '<div class=\"text-left\"><strong>Mohon perbaiki kesalahan berikut:</strong><br><br>• {$error_list}</div>',
                confirmButtonText: 'Perbaiki',
                confirmButtonColor: '#dc3545'
            });
        });
    </script>
    ";
}
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=settings">Pengaturan</a></li>
                        <li class="breadcrumb-item active">Tampilan</li>
                    </ol>
                </div>
                <h4 class="page-title">🎨 Pengaturan Tampilan Website</h4>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30 border-left-primary" style="border-left: 4px solid #2E7D32;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="avatar-lg bg-soft-primary rounded-circle">
                                <i class="mdi mdi-palette-outline text-primary"
                                    style="font-size: 40px; line-height: 70px;"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h5 class="mb-1">Kustomisasi Tampilan Website</h5>
                            <p class="text-muted mb-0">
                                Sesuaikan warna, jenis huruf, dan gaya tombol untuk branding website Anda.
                                Perubahan akan diterapkan secara otomatis ke seluruh halaman website setelah disimpan.
                            </p>
                        </div>
                        <div class="col-auto">
                            <span class="badge badge-soft-success px-3 py-2">
                                <i class="mdi mdi-check-circle mr-1"></i>
                                Terakhir diperbarui:
                                <?php echo isset($appearance['updated_at']) ? date('d M Y, H:i', strtotime($appearance['updated_at'])) : 'Belum pernah'; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" id="appearanceForm" novalidate>
        <div class="row">
            <!-- ==================== KOLOM KIRI ==================== -->
            <div class="col-lg-6">

                <!-- CARD: Skema Warna -->
                <div class="card m-b-30">
                    <div class="card-header bg-gradient-primary text-white">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-palette mdi-24px mr-2"></i>
                            <div>
                                <h5 class="mb-0">Skema Warna</h5>
                                <small class="opacity-75">Pilih warna tema untuk website</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Penjelasan Warna -->
                        <div class="alert alert-light border mb-4">
                            <h6 class="alert-heading mb-2"><i class="mdi mdi-information-outline mr-1"></i> Panduan
                                Warna</h6>
                            <ul class="mb-0 small">
                                <li><strong>Warna Utama:</strong> Header, tombol utama, link aktif</li>
                                <li><strong>Warna Sekunder:</strong> Tombol sekunder, elemen pendukung</li>
                                <li><strong>Warna Aksen:</strong> Highlight, badge, notifikasi</li>
                            </ul>
                        </div>

                        <div class="row">
                            <!-- Warna Utama -->
                            <div class="col-md-4">
                                <div class="form-group text-center">
                                    <label class="font-weight-bold d-block mb-2">
                                        <i class="mdi mdi-circle text-primary mr-1"></i>Warna Utama
                                    </label>
                                    <input type="color" class="form-control-color mx-auto" name="primary_color"
                                        id="primaryColor"
                                        value="<?php echo htmlspecialchars($appearance['primary_color']); ?>"
                                        title="Pilih warna utama" required>
                                    <input type="text"
                                        class="form-control form-control-sm mt-2 text-center color-hex-input"
                                        id="primaryColorHex" value="<?php echo $appearance['primary_color']; ?>"
                                        pattern="^#[0-9A-Fa-f]{6}$" maxlength="7" placeholder="#RRGGBB">
                                    <?php if (isset($errors['primary_color'])): ?>
                                        <small class="text-danger"><?php echo $errors['primary_color']; ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Warna Sekunder -->
                            <div class="col-md-4">
                                <div class="form-group text-center">
                                    <label class="font-weight-bold d-block mb-2">
                                        <i class="mdi mdi-circle text-info mr-1"></i>Warna Sekunder
                                    </label>
                                    <input type="color" class="form-control-color mx-auto" name="secondary_color"
                                        id="secondaryColor"
                                        value="<?php echo htmlspecialchars($appearance['secondary_color']); ?>"
                                        title="Pilih warna sekunder" required>
                                    <input type="text"
                                        class="form-control form-control-sm mt-2 text-center color-hex-input"
                                        id="secondaryColorHex" value="<?php echo $appearance['secondary_color']; ?>"
                                        pattern="^#[0-9A-Fa-f]{6}$" maxlength="7" placeholder="#RRGGBB">
                                    <?php if (isset($errors['secondary_color'])): ?>
                                        <small class="text-danger"><?php echo $errors['secondary_color']; ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Warna Aksen -->
                            <div class="col-md-4">
                                <div class="form-group text-center">
                                    <label class="font-weight-bold d-block mb-2">
                                        <i class="mdi mdi-circle text-warning mr-1"></i>Warna Aksen
                                    </label>
                                    <input type="color" class="form-control-color mx-auto" name="accent_color"
                                        id="accentColor"
                                        value="<?php echo htmlspecialchars($appearance['accent_color']); ?>"
                                        title="Pilih warna aksen" required>
                                    <input type="text"
                                        class="form-control form-control-sm mt-2 text-center color-hex-input"
                                        id="accentColorHex" value="<?php echo $appearance['accent_color']; ?>"
                                        pattern="^#[0-9A-Fa-f]{6}$" maxlength="7" placeholder="#RRGGBB">
                                    <?php if (isset($errors['accent_color'])): ?>
                                        <small class="text-danger"><?php echo $errors['accent_color']; ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Preset Warna -->
                        <h6 class="mb-3"><i class="mdi mdi-star mr-1"></i>Preset Warna Siap Pakai</h6>
                        <div class="row">
                            <?php foreach ($color_presets as $key => $preset): ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <button type="button" class="btn btn-outline-secondary btn-block preset-color p-2 h-100"
                                        data-primary="<?php echo $preset['primary']; ?>"
                                        data-secondary="<?php echo $preset['secondary']; ?>"
                                        data-accent="<?php echo $preset['accent']; ?>">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="color-dots mr-2">
                                                <span class="color-dot"
                                                    style="background: <?php echo $preset['primary']; ?>;"></span>
                                                <span class="color-dot"
                                                    style="background: <?php echo $preset['secondary']; ?>;"></span>
                                                <span class="color-dot"
                                                    style="background: <?php echo $preset['accent']; ?>;"></span>
                                            </div>
                                            <strong class="small"><?php echo $preset['name']; ?></strong>
                                        </div>
                                        <small class="text-muted d-block"><?php echo $preset['desc']; ?></small>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- CARD: Pratinjau Langsung -->
                <div class="card m-b-30">
                    <div class="card-header bg-gradient-dark text-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-eye mdi-24px mr-2"></i>
                                <div>
                                    <h5 class="mb-0">Pratinjau Langsung</h5>
                                    <small class="opacity-75">Lihat perubahan secara real-time</small>
                                </div>
                            </div>
                            <button type="button" class="btn btn-light btn-sm" onclick="resetPreview()">
                                <i class="mdi mdi-refresh"></i> Reset
                            </button>
                        </div>
                    </div>
                    <div class="card-body" id="livePreview">
                        <!-- Preview Header -->
                        <div class="mb-3">
                            <label class="small text-muted mb-1">Pratinjau Header:</label>
                            <div class="preview-header p-3 rounded" id="previewHeader"
                                style="background: <?php echo $appearance['primary_color']; ?>; color: white;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="font-weight-bold">🕌 Nama Website</span>
                                    <div>
                                        <span class="mr-3">Menu 1</span>
                                        <span class="mr-3">Menu 2</span>
                                        <span>Menu 3</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Buttons -->
                        <div class="mb-3">
                            <label class="small text-muted mb-1">Pratinjau Tombol:</label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn" id="previewBtn"
                                    style="background: <?php echo $appearance['primary_color']; ?>; color: white; border-radius: <?php echo $button_styles[$appearance['button_style']]['radius']; ?>;">
                                    Tombol Utama
                                </button>
                                <button type="button" class="btn" id="previewBtnSecondary"
                                    style="background: <?php echo $appearance['secondary_color']; ?>; color: white; border-radius: <?php echo $button_styles[$appearance['button_style']]['radius']; ?>;">
                                    Tombol Sekunder
                                </button>
                                <button type="button" class="btn" id="previewBtnAccent"
                                    style="background: <?php echo $appearance['accent_color']; ?>; color: white; border-radius: <?php echo $button_styles[$appearance['button_style']]['radius']; ?>;">
                                    Tombol Aksen
                                </button>
                            </div>
                        </div>

                        <!-- Preview Links & Text -->
                        <div class="mb-3">
                            <label class="small text-muted mb-1">Pratinjau Teks & Link:</label>
                            <div class="p-3 bg-light rounded" id="fontPreviewBox"
                                style="font-family: '<?php echo $appearance['font_family']; ?>', sans-serif;">
                                <p class="mb-1" style="font-size: 18px; font-weight: 600;">Judul Contoh dengan Font
                                    Terpilih</p>
                                <p class="mb-2 text-muted" style="font-size: 14px;">Lorem ipsum dolor sit amet,
                                    consectetur adipiscing elit. Ini adalah contoh paragraf.</p>
                                <a href="#" id="previewLink"
                                    style="color: <?php echo $appearance['primary_color']; ?>;">Contoh Link</a>
                                <span class="mx-2">|</span>
                                <span id="previewAccent"
                                    style="color: <?php echo $appearance['accent_color']; ?>; font-weight: 600;">★ Teks
                                    Aksen</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== KOLOM KANAN ==================== -->
            <div class="col-lg-6">

                <!-- CARD: Tipografi -->
                <div class="card m-b-30">
                    <div class="card-header bg-gradient-info text-white">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-format-font mdi-24px mr-2"></i>
                            <div>
                                <h5 class="mb-0">Tipografi</h5>
                                <small class="opacity-75">Pilih jenis huruf untuk website</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Penjelasan -->
                        <div class="alert alert-light border mb-4">
                            <h6 class="alert-heading mb-2"><i class="mdi mdi-information-outline mr-1"></i> Tentang
                                Tipografi</h6>
                            <p class="mb-0 small">Jenis huruf (font) mempengaruhi keterbacaan dan kesan profesional
                                website. Semua font dari Google Fonts dan gratis digunakan.</p>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold"><i class="mdi mdi-format-text mr-1"></i>Jenis Huruf</label>
                            <select class="form-control form-control-lg" name="font_family" id="fontFamily" required>
                                <?php foreach ($font_options as $value => $font): ?>
                                    <option value="<?php echo $value; ?>" <?php echo ($appearance['font_family'] == $value) ? 'selected' : ''; ?> data-desc="<?php echo $font['desc']; ?>"
                                        style="font-family: '<?php echo $value; ?>', sans-serif;">
                                        <?php echo $font['label']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['font_family'])): ?>
                                <small class="text-danger"><?php echo $errors['font_family']; ?></small>
                            <?php endif; ?>
                        </div>

                        <!-- Font Info Box -->
                        <div class="p-3 bg-light rounded mb-3" id="fontInfo">
                            <div class="d-flex align-items-start">
                                <i class="mdi mdi-format-font mdi-24px text-info mr-2"></i>
                                <div>
                                    <strong
                                        id="fontInfoLabel"><?php echo $font_options[$appearance['font_family']]['label']; ?></strong>
                                    <p class="mb-0 small text-muted" id="fontInfoDesc">
                                        <?php echo $font_options[$appearance['font_family']]['desc']; ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Font Preview -->
                        <div class="p-3 border rounded" id="fontPreview"
                            style="font-family: '<?php echo $appearance['font_family']; ?>', sans-serif;">
                            <h5 class="mb-2">Pratinjau Jenis Huruf</h5>
                            <p class="mb-1">ABCDEFGHIJKLMNOPQRSTUVWXYZ</p>
                            <p class="mb-1">abcdefghijklmnopqrstuvwxyz</p>
                            <p class="mb-1">0123456789 !@#$%^&*()</p>
                            <p class="mb-0 text-muted" style="font-size: 14px;">The quick brown fox jumps over the lazy
                                dog.</p>
                        </div>
                    </div>
                </div>

                <!-- CARD: Gaya Tombol -->
                <div class="card m-b-30">
                    <div class="card-header bg-gradient-success text-white">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-gesture-tap-button mdi-24px mr-2"></i>
                            <div>
                                <h5 class="mb-0">Gaya Tombol</h5>
                                <small class="opacity-75">Pilih bentuk sudut tombol</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Penjelasan -->
                        <div class="alert alert-light border mb-4">
                            <h6 class="alert-heading mb-2"><i class="mdi mdi-information-outline mr-1"></i> Tentang Gaya
                                Tombol</h6>
                            <p class="mb-0 small">Gaya tombol mempengaruhi kesan visual website. Pilih yang sesuai
                                dengan karakter brand Anda.</p>
                        </div>

                        <div class="row">
                            <?php foreach ($button_styles as $value => $style): ?>
                                <div class="col-4">
                                    <div class="form-check btn-style-option text-center p-3 border rounded h-100 <?php echo ($appearance['button_style'] == $value) ? 'border-success bg-soft-success selected' : ''; ?>"
                                        onclick="selectButtonStyle('<?php echo $value; ?>')">
                                        <input class="form-check-input d-none" type="radio" name="button_style"
                                            id="btn_<?php echo $value; ?>" value="<?php echo $value; ?>" <?php echo ($appearance['button_style'] == $value) ? 'checked' : ''; ?> required>
                                        <label class="form-check-label d-block cursor-pointer"
                                            for="btn_<?php echo $value; ?>">
                                            <button type="button" class="btn btn-primary btn-sm mb-2"
                                                style="border-radius: <?php echo $style['radius']; ?>; pointer-events: none; min-width: 80px;">
                                                Tombol
                                            </button>
                                            <br>
                                            <strong class="d-block"><?php echo $style['label']; ?></strong>
                                            <small class="text-muted"><?php echo $style['desc']; ?></small>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (isset($errors['button_style'])): ?>
                            <small class="text-danger d-block mt-2"><?php echo $errors['button_style']; ?></small>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- CARD: Tombol Aksi -->
                <div class="card m-b-30 border-success" style="border: 2px solid #28a745;">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-1"><i class="mdi mdi-content-save mr-1"></i>Simpan Perubahan</h5>
                                <p class="text-muted mb-0 small">Pastikan semua pengaturan sudah sesuai sebelum
                                    menyimpan</p>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-outline-secondary mr-2" onclick="resetForm()">
                                    <i class="mdi mdi-undo mr-1"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                    <i class="mdi mdi-check-circle mr-1"></i>Simpan Pengaturan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD: Informasi Tambahan -->
                <div class="card m-b-30 bg-light">
                    <div class="card-body">
                        <h6 class="mb-3"><i class="mdi mdi-help-circle-outline mr-1"></i>Informasi Penting</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex mb-2">
                                    <i class="mdi mdi-check-circle text-success mr-2"></i>
                                    <small>Perubahan berlaku setelah disimpan</small>
                                </div>
                                <div class="d-flex mb-2">
                                    <i class="mdi mdi-check-circle text-success mr-2"></i>
                                    <small>Refresh browser untuk melihat hasil</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex mb-2">
                                    <i class="mdi mdi-check-circle text-success mr-2"></i>
                                    <small>Font diambil dari Google Fonts</small>
                                </div>
                                <div class="d-flex mb-2">
                                    <i class="mdi mdi-check-circle text-success mr-2"></i>
                                    <small>Warna valid format HEX (#RRGGBB)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php echo $alert_script; ?>
<?php echo $validation_script; ?>

<style>
    /* Color Input Styling */
    .form-control-color {
        width: 80px;
        height: 60px;
        padding: 5px;
        border: 3px solid #dee2e6;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-control-color:hover {
        border-color: #28a745;
        transform: scale(1.05);
    }

    .form-control-color::-webkit-color-swatch-wrapper {
        padding: 3px;
    }

    .form-control-color::-webkit-color-swatch {
        border-radius: 6px;
        border: none;
    }

    /* Color Hex Input */
    .color-hex-input {
        font-family: 'Courier New', monospace;
        font-size: 12px;
        max-width: 90px;
        margin: 0 auto;
    }

    .color-hex-input.is-invalid {
        border-color: #dc3545;
    }

    /* Color Dots for Presets */
    .color-dots {
        display: flex;
        gap: 3px;
    }

    .color-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    /* Preset Color Button */
    .preset-color {
        text-align: left;
        transition: all 0.3s ease;
    }

    .preset-color:hover {
        border-color: #28a745 !important;
        background-color: #f8f9fa !important;
        transform: translateY(-2px);
    }

    .preset-color.active {
        border-color: #28a745 !important;
        background-color: #e8f5e9 !important;
    }

    /* Button Style Option */
    .btn-style-option {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-style-option:hover {
        border-color: #28a745 !important;
        background-color: #f8f9fa;
    }

    .btn-style-option.selected {
        border-color: #28a745 !important;
        border-width: 2px;
    }

    .bg-soft-success {
        background-color: rgba(40, 167, 69, 0.1);
    }

    /* Gradient Backgrounds */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #2E7D32, #4CAF50);
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #1565C0, #42A5F5);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #388E3C, #66BB6A);
    }

    .bg-gradient-dark {
        background: linear-gradient(135deg, #37474F, #546E7A);
    }

    /* Preview Box */
    .preview-header {
        transition: all 0.3s ease;
    }

    #livePreview .btn {
        transition: all 0.3s ease;
        margin: 2px;
    }

    /* Cursor */
    .cursor-pointer {
        cursor: pointer;
    }

    /* Gap utility */
    .gap-2 {
        gap: 0.5rem;
    }
</style>

<script>
    // Font data for info display
    const fontData = <?php echo json_encode($font_options); ?>;
    const buttonData = <?php echo json_encode($button_styles); ?>;

    // Preset colors click handler
    document.querySelectorAll('.preset-color').forEach(btn => {
        btn.addEventListener('click', function () {
            // Remove active from all
            document.querySelectorAll('.preset-color').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Set colors
            const primary = this.dataset.primary;
            const secondary = this.dataset.secondary;
            const accent = this.dataset.accent;

            document.getElementById('primaryColor').value = primary;
            document.getElementById('secondaryColor').value = secondary;
            document.getElementById('accentColor').value = accent;

            document.getElementById('primaryColorHex').value = primary;
            document.getElementById('secondaryColorHex').value = secondary;
            document.getElementById('accentColorHex').value = accent;

            updatePreview();

            // Show toast
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Preset warna diterapkan!',
                showConfirmButton: false,
                timer: 1500
            });
        });
    });

    // Color picker change - sync with hex input
    document.querySelectorAll('.form-control-color').forEach(input => {
        input.addEventListener('input', function () {
            const hexInput = document.getElementById(this.id + 'Hex');
            if (hexInput) {
                hexInput.value = this.value.toUpperCase();
                hexInput.classList.remove('is-invalid');
            }
            updatePreview();
        });
    });

    // Hex input change - sync with color picker
    document.querySelectorAll('.color-hex-input').forEach(input => {
        input.addEventListener('input', function () {
            const value = this.value.trim();
            const colorInput = document.getElementById(this.id.replace('Hex', ''));

            // Validate hex format
            if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                colorInput.value = value;
                this.classList.remove('is-invalid');
                updatePreview();
            } else {
                this.classList.add('is-invalid');
            }
        });

        // Auto uppercase
        input.addEventListener('blur', function () {
            this.value = this.value.toUpperCase();
        });
    });

    // Font family change
    document.getElementById('fontFamily').addEventListener('change', function () {
        const selectedFont = this.value;
        const fontInfo = fontData[selectedFont];

        document.getElementById('fontPreview').style.fontFamily = "'" + selectedFont + "', sans-serif";
        document.getElementById('fontPreviewBox').style.fontFamily = "'" + selectedFont + "', sans-serif";
        document.getElementById('fontInfoLabel').textContent = fontInfo.label;
        document.getElementById('fontInfoDesc').textContent = fontInfo.desc;
    });

    // Button style selection
    function selectButtonStyle(value) {
        document.querySelectorAll('.btn-style-option').forEach(el => {
            el.classList.remove('border-success', 'bg-soft-success', 'selected');
        });

        const option = document.querySelector(`#btn_${value}`).closest('.btn-style-option');
        option.classList.add('border-success', 'bg-soft-success', 'selected');
        document.getElementById(`btn_${value}`).checked = true;

        updatePreview();
    }

    // Update live preview
    function updatePreview() {
        const primary = document.getElementById('primaryColor').value;
        const secondary = document.getElementById('secondaryColor').value;
        const accent = document.getElementById('accentColor').value;
        const btnStyleRadio = document.querySelector('input[name="button_style"]:checked');
        const btnStyle = btnStyleRadio ? btnStyleRadio.value : 'rounded';

        const radius = buttonData[btnStyle].radius;

        document.getElementById('previewHeader').style.background = primary;
        document.getElementById('previewBtn').style.background = primary;
        document.getElementById('previewBtn').style.borderRadius = radius;
        document.getElementById('previewBtnSecondary').style.background = secondary;
        document.getElementById('previewBtnSecondary').style.borderRadius = radius;
        document.getElementById('previewBtnAccent').style.background = accent;
        document.getElementById('previewBtnAccent').style.borderRadius = radius;
        document.getElementById('previewLink').style.color = primary;
        document.getElementById('previewAccent').style.color = accent;
    }

    // Reset form to original values
    function resetForm() {
        Swal.fire({
            title: 'Reset Pengaturan?',
            text: 'Semua perubahan yang belum disimpan akan hilang.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Reset',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('appearanceForm').reset();
                location.reload();
            }
        });
    }

    // Reset preview only
    function resetPreview() {
        updatePreview();
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'info',
            title: 'Pratinjau direset',
            showConfirmButton: false,
            timer: 1000
        });
    }

    // Form submission validation
    document.getElementById('appearanceForm').addEventListener('submit', function (e) {
        const hexPattern = /^#[0-9A-Fa-f]{6}$/;
        const errors = [];

        // Validate colors
        const primaryHex = document.getElementById('primaryColorHex').value;
        const secondaryHex = document.getElementById('secondaryColorHex').value;
        const accentHex = document.getElementById('accentColorHex').value;

        if (!hexPattern.test(primaryHex)) {
            errors.push('Format warna utama tidak valid');
            document.getElementById('primaryColorHex').classList.add('is-invalid');
        }
        if (!hexPattern.test(secondaryHex)) {
            errors.push('Format warna sekunder tidak valid');
            document.getElementById('secondaryColorHex').classList.add('is-invalid');
        }
        if (!hexPattern.test(accentHex)) {
            errors.push('Format warna aksen tidak valid');
            document.getElementById('accentColorHex').classList.add('is-invalid');
        }

        // Validate font
        if (!document.getElementById('fontFamily').value) {
            errors.push('Jenis huruf wajib dipilih');
        }

        // Validate button style
        if (!document.querySelector('input[name="button_style"]:checked')) {
            errors.push('Gaya tombol wajib dipilih');
        }

        if (errors.length > 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal!',
                html: '<strong>Mohon perbaiki kesalahan berikut:</strong><br><br>• ' + errors.join('<br>• '),
                confirmButtonText: 'Perbaiki',
                confirmButtonColor: '#dc3545'
            });
            return false;
        }

        // Show loading
        Swal.fire({
            title: 'Menyimpan...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    });

    // Initial preview update
    updatePreview();
</script>