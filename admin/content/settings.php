<?php
// Pengaturan Website

$query = mysqli_query($conn, "SELECT * FROM site_settings WHERE id = 1 LIMIT 1");
$settings = mysqli_fetch_assoc($query);

// Jika belum ada data, buat default
if (!$settings) {
    mysqli_query($conn, "INSERT INTO site_settings (id, site_name) VALUES (1, 'Hafsa Islamic Center')");
    $query = mysqli_query($conn, "SELECT * FROM site_settings WHERE id = 1 LIMIT 1");
    $settings = mysqli_fetch_assoc($query);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Validasi Nama Situs
    $site_name = isset($_POST['site_name']) ? trim($_POST['site_name']) : '';
    if (empty($site_name)) {
        $errors[] = 'Nama situs wajib diisi';
    } elseif (strlen($site_name) > 255) {
        $errors[] = 'Nama situs maksimal 255 karakter';
    }
    $site_name = mysqli_real_escape_string($conn, $site_name);

    // Validasi Tagline
    $site_tagline = isset($_POST['site_tagline']) ? trim($_POST['site_tagline']) : '';
    if (strlen($site_tagline) > 255) {
        $errors[] = 'Tagline maksimal 255 karakter';
    }
    $site_tagline = mysqli_real_escape_string($conn, $site_tagline);

    // Validasi Deskripsi
    $site_description = isset($_POST['site_description']) ? trim($_POST['site_description']) : '';
    if (strlen($site_description) > 1000) {
        $errors[] = 'Deskripsi situs maksimal 1000 karakter';
    }
    $site_description = mysqli_real_escape_string($conn, $site_description);

    // Validasi Email
    $email_primary = isset($_POST['email_primary']) ? trim($_POST['email_primary']) : '';
    if (!empty($email_primary) && !filter_var($email_primary, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email utama tidak valid';
    }
    $email_primary = mysqli_real_escape_string($conn, $email_primary);

    $email_secondary = isset($_POST['email_secondary']) ? trim($_POST['email_secondary']) : '';
    if (!empty($email_secondary) && !filter_var($email_secondary, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email sekunder tidak valid';
    }
    $email_secondary = mysqli_real_escape_string($conn, $email_secondary);

    // Validasi URL
    $facebook_url = isset($_POST['facebook_url']) ? trim($_POST['facebook_url']) : '';
    if (!empty($facebook_url) && !filter_var($facebook_url, FILTER_VALIDATE_URL)) {
        $errors[] = 'Format URL Facebook tidak valid';
    }
    $instagram_url = isset($_POST['instagram_url']) ? trim($_POST['instagram_url']) : '';
    if (!empty($instagram_url) && !filter_var($instagram_url, FILTER_VALIDATE_URL)) {
        $errors[] = 'Format URL Instagram tidak valid';
    }
    $twitter_url = isset($_POST['twitter_url']) ? trim($_POST['twitter_url']) : '';
    if (!empty($twitter_url) && !filter_var($twitter_url, FILTER_VALIDATE_URL)) {
        $errors[] = 'Format URL Twitter tidak valid';
    }
    $youtube_url = isset($_POST['youtube_url']) ? trim($_POST['youtube_url']) : '';
    if (!empty($youtube_url) && !filter_var($youtube_url, FILTER_VALIDATE_URL)) {
        $errors[] = 'Format URL YouTube tidak valid';
    }

    $phone_primary = mysqli_real_escape_string($conn, $_POST['phone_primary'] ?? '');
    $phone_secondary = mysqli_real_escape_string($conn, $_POST['phone_secondary'] ?? '');
    $address = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $facebook_url = mysqli_real_escape_string($conn, $facebook_url);
    $instagram_url = mysqli_real_escape_string($conn, $instagram_url);
    $twitter_url = mysqli_real_escape_string($conn, $twitter_url);
    $youtube_url = mysqli_real_escape_string($conn, $youtube_url);
    $whatsapp_number = mysqli_real_escape_string($conn, $_POST['whatsapp_number'] ?? '');
    $working_hours = mysqli_real_escape_string($conn, $_POST['working_hours'] ?? '');
    $map_embed_url = mysqli_real_escape_string($conn, $_POST['map_embed_url'] ?? '');
    $latitude = mysqli_real_escape_string($conn, $_POST['latitude'] ?? '');
    $longitude = mysqli_real_escape_string($conn, $_POST['longitude'] ?? '');
    $footer_text = mysqli_real_escape_string($conn, $_POST['footer_text'] ?? '');
    $copyright_text = mysqli_real_escape_string($conn, $_POST['copyright_text'] ?? '');

    // Handle file uploads
    $logo_light = $settings['logo_light'];
    $logo_dark = $settings['logo_dark'];
    $favicon = $settings['favicon'];

    $allowed_img = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
    $allowed_mime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];

    if (isset($_FILES['logo_light']) && $_FILES['logo_light']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['logo_light']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_img)) {
            $errors[] = 'Format Logo Terang tidak diizinkan';
        } elseif ($_FILES['logo_light']['size'] > 2097152) {
            $errors[] = 'Ukuran Logo Terang maksimal 2MB';
        } else {
            $name = 'logo_light_' . time() . '_' . uniqid() . '.' . $ext;
            if (!is_dir('../uploads/settings'))
                mkdir('../uploads/settings', 0755, true);
            if (move_uploaded_file($_FILES['logo_light']['tmp_name'], '../uploads/settings/' . $name)) {
                $logo_light = 'uploads/settings/' . $name;
            }
        }
    }

    if (isset($_FILES['logo_dark']) && $_FILES['logo_dark']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['logo_dark']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_img)) {
            $errors[] = 'Format Logo Gelap tidak diizinkan';
        } elseif ($_FILES['logo_dark']['size'] > 2097152) {
            $errors[] = 'Ukuran Logo Gelap maksimal 2MB';
        } else {
            $name = 'logo_dark_' . time() . '_' . uniqid() . '.' . $ext;
            if (!is_dir('../uploads/settings'))
                mkdir('../uploads/settings', 0755, true);
            if (move_uploaded_file($_FILES['logo_dark']['tmp_name'], '../uploads/settings/' . $name)) {
                $logo_dark = 'uploads/settings/' . $name;
            }
        }
    }

    if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['favicon']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['ico', 'png', 'svg'])) {
            $errors[] = 'Format Favicon harus ICO, PNG, atau SVG';
        } elseif ($_FILES['favicon']['size'] > 512000) {
            $errors[] = 'Ukuran Favicon maksimal 500KB';
        } else {
            $name = 'favicon_' . time() . '.' . $ext;
            if (!is_dir('../uploads/settings'))
                mkdir('../uploads/settings', 0755, true);
            if (move_uploaded_file($_FILES['favicon']['tmp_name'], '../uploads/settings/' . $name)) {
                $favicon = 'uploads/settings/' . $name;
            }
        }
    }

    // Simpan ke database
    if (empty($errors)) {
        $update = "UPDATE site_settings SET 
            site_name = '$site_name', site_tagline = '$site_tagline', site_description = '$site_description',
            phone_primary = '$phone_primary', phone_secondary = '$phone_secondary',
            email_primary = '$email_primary', email_secondary = '$email_secondary', address = '$address',
            facebook_url = '$facebook_url', instagram_url = '$instagram_url', twitter_url = '$twitter_url',
            youtube_url = '$youtube_url', whatsapp_number = '$whatsapp_number',
            logo_light = '$logo_light', logo_dark = '$logo_dark', favicon = '$favicon',
            working_hours = '$working_hours', map_embed_url = '$map_embed_url',
            latitude = '$latitude', longitude = '$longitude',
            footer_text = '$footer_text', copyright_text = '$copyright_text'
            WHERE id = 1";

        if (mysqli_query($conn, $update)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Pengaturan berhasil disimpan'];
            header("Location: ?page=settings");
            exit;
        } else {
            $errors[] = 'Gagal menyimpan: ' . mysqli_error($conn);
        }
    }

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
    }
}

// Handle alerts
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
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#28a745',
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
    ";
    unset($_SESSION['alert']);
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
                        <li class="breadcrumb-item active">Pengaturan Website</li>
                    </ol>
                </div>
                <h4 class="page-title">Pengaturan Website</h4>
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
                            <h5 class="mb-2"><i class="mdi mdi-cog text-primary mr-2"></i>Pengaturan Website</h5>
                            <p class="mb-0 text-muted">
                                Kelola pengaturan umum website seperti nama situs, informasi kontak, media sosial, 
                                logo, dan teks footer. Perubahan akan diterapkan ke seluruh halaman website.
                            </p>
                        </div>
                        <div class="col-md-3 text-center mt-3 mt-md-0">
                            <i class="mdi mdi-settings text-primary" style="font-size: 50px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="" enctype="multipart/form-data" id="settingsForm">
        <div class="row">
            <div class="col-lg-9">
                <div class="card m-b-30">
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#general">
                                    <i class="mdi mdi-cog"></i> Umum
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#contact">
                                    <i class="mdi mdi-phone"></i> Kontak
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#social">
                                    <i class="mdi mdi-share-variant"></i> Media Sosial
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#branding">
                                    <i class="mdi mdi-image"></i> Branding
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#footer">
                                    <i class="mdi mdi-page-layout-footer"></i> Footer
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content p-3">
                            <!-- Tab Umum -->
                            <div class="tab-pane fade show active" id="general">
                                <h5 class="mb-3"><i class="mdi mdi-web mr-1"></i>Informasi Website</h5>
                                <div class="form-group">
                                    <label for="site_name">Nama Situs <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="site_name" name="site_name" 
                                        value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>" 
                                        maxlength="255" required placeholder="Contoh: Hafsa Islamic Center">
                                    <small class="form-text text-muted">Nama website yang akan ditampilkan di judul browser</small>
                                </div>
                                <div class="form-group">
                                    <label for="site_tagline">Tagline / Slogan</label>
                                    <input type="text" class="form-control" id="site_tagline" name="site_tagline" 
                                        value="<?php echo htmlspecialchars($settings['site_tagline'] ?? ''); ?>" 
                                        maxlength="255" placeholder="Contoh: Menebar Kebaikan untuk Semua">
                                    <small class="form-text text-muted">Kalimat singkat yang menggambarkan misi</small>
                                </div>
                                <div class="form-group">
                                    <label for="site_description">Deskripsi Website</label>
                                    <textarea class="form-control" id="site_description" name="site_description" rows="3" 
                                        maxlength="1000" placeholder="Deskripsi singkat tentang website..."><?php echo htmlspecialchars($settings['site_description'] ?? ''); ?></textarea>
                                    <small class="form-text text-muted">Untuk SEO dan meta description (maks 1000 karakter)</small>
                                </div>
                            </div>

                            <!-- Tab Kontak -->
                            <div class="tab-pane fade" id="contact">
                                <h5 class="mb-3"><i class="mdi mdi-phone mr-1"></i>Informasi Kontak</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone_primary"><i class="mdi mdi-phone text-success"></i> Telepon Utama</label>
                                            <input type="text" class="form-control" id="phone_primary" name="phone_primary" 
                                                value="<?php echo htmlspecialchars($settings['phone_primary'] ?? ''); ?>"
                                                placeholder="Contoh: +62 21 12345678">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone_secondary"><i class="mdi mdi-phone-outline text-muted"></i> Telepon Sekunder</label>
                                            <input type="text" class="form-control" id="phone_secondary" name="phone_secondary" 
                                                value="<?php echo htmlspecialchars($settings['phone_secondary'] ?? ''); ?>"
                                                placeholder="Opsional">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email_primary"><i class="mdi mdi-email text-primary"></i> Email Utama</label>
                                            <input type="email" class="form-control" id="email_primary" name="email_primary" 
                                                value="<?php echo htmlspecialchars($settings['email_primary'] ?? ''); ?>"
                                                placeholder="info@example.com">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email_secondary"><i class="mdi mdi-email-outline text-muted"></i> Email Sekunder</label>
                                            <input type="email" class="form-control" id="email_secondary" name="email_secondary" 
                                                value="<?php echo htmlspecialchars($settings['email_secondary'] ?? ''); ?>"
                                                placeholder="Opsional">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address"><i class="mdi mdi-map-marker text-danger"></i> Alamat</label>
                                    <textarea class="form-control" id="address" name="address" rows="2"
                                        placeholder="Alamat lengkap..."><?php echo htmlspecialchars($settings['address'] ?? ''); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="working_hours"><i class="mdi mdi-clock text-info"></i> Jam Operasional</label>
                                    <textarea class="form-control" id="working_hours" name="working_hours" rows="2" 
                                        placeholder="Contoh: Senin-Jumat: 08:00-17:00"><?php echo htmlspecialchars($settings['working_hours'] ?? ''); ?></textarea>
                                </div>

                                <hr>
                                <h5 class="mb-3"><i class="mdi mdi-google-maps mr-1"></i>Lokasi Peta</h5>
                                <div class="form-group">
                                    <label for="map_embed_url">URL Embed Google Maps</label>
                                    <textarea class="form-control" id="map_embed_url" name="map_embed_url" rows="2" 
                                        placeholder="Salin URL embed dari Google Maps..."><?php echo htmlspecialchars($settings['map_embed_url'] ?? ''); ?></textarea>
                                    <small class="form-text text-muted">Dapatkan dari: Google Maps → Bagikan → Sematkan peta</small>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="latitude">Latitude</label>
                                            <input type="text" class="form-control" id="latitude" name="latitude" 
                                                value="<?php echo htmlspecialchars($settings['latitude'] ?? ''); ?>"
                                                placeholder="Contoh: -6.175392">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="longitude">Longitude</label>
                                            <input type="text" class="form-control" id="longitude" name="longitude" 
                                                value="<?php echo htmlspecialchars($settings['longitude'] ?? ''); ?>"
                                                placeholder="Contoh: 106.827153">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Media Sosial -->
                            <div class="tab-pane fade" id="social">
                                <h5 class="mb-3"><i class="mdi mdi-share-variant mr-1"></i>Akun Media Sosial</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="facebook_url"><i class="mdi mdi-facebook text-primary"></i> Facebook</label>
                                            <input type="url" class="form-control" id="facebook_url" name="facebook_url" 
                                                value="<?php echo htmlspecialchars($settings['facebook_url'] ?? ''); ?>"
                                                placeholder="https://facebook.com/username">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="instagram_url"><i class="mdi mdi-instagram text-danger"></i> Instagram</label>
                                            <input type="url" class="form-control" id="instagram_url" name="instagram_url" 
                                                value="<?php echo htmlspecialchars($settings['instagram_url'] ?? ''); ?>"
                                                placeholder="https://instagram.com/username">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="twitter_url"><i class="mdi mdi-twitter text-info"></i> Twitter / X</label>
                                            <input type="url" class="form-control" id="twitter_url" name="twitter_url" 
                                                value="<?php echo htmlspecialchars($settings['twitter_url'] ?? ''); ?>"
                                                placeholder="https://twitter.com/username">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="youtube_url"><i class="mdi mdi-youtube text-danger"></i> YouTube</label>
                                            <input type="url" class="form-control" id="youtube_url" name="youtube_url" 
                                                value="<?php echo htmlspecialchars($settings['youtube_url'] ?? ''); ?>"
                                                placeholder="https://youtube.com/c/channel">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="whatsapp_number"><i class="mdi mdi-whatsapp text-success"></i> Nomor WhatsApp</label>
                                    <input type="text" class="form-control" id="whatsapp_number" name="whatsapp_number" 
                                        value="<?php echo htmlspecialchars($settings['whatsapp_number'] ?? ''); ?>" 
                                        placeholder="6281234567890 (tanpa + atau -)">
                                    <small class="form-text text-muted">Format internasional tanpa tanda + atau -</small>
                                </div>
                            </div>

                            <!-- Tab Branding -->
                            <div class="tab-pane fade" id="branding">
                                <h5 class="mb-3"><i class="mdi mdi-palette mr-1"></i>Logo & Branding</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Logo Terang (untuk latar gelap)</label>
                                            <?php if (!empty($settings['logo_light'])): ?>
                                                <div class="mb-2 p-3 bg-dark rounded text-center">
                                                    <img src="<?php echo '../' . $settings['logo_light']; ?>" style="max-height: 60px;">
                                                </div>
                                            <?php endif; ?>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="logo_light" name="logo_light" accept="image/*">
                                                <label class="custom-file-label" for="logo_light">Pilih file...</label>
                                            </div>
                                            <small class="text-muted">Maks 2MB</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Logo Gelap (untuk latar terang)</label>
                                            <?php if (!empty($settings['logo_dark'])): ?>
                                                <div class="mb-2 p-3 bg-light rounded text-center">
                                                    <img src="<?php echo '../' . $settings['logo_dark']; ?>" style="max-height: 60px;">
                                                </div>
                                            <?php endif; ?>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="logo_dark" name="logo_dark" accept="image/*">
                                                <label class="custom-file-label" for="logo_dark">Pilih file...</label>
                                            </div>
                                            <small class="text-muted">Maks 2MB</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Favicon</label>
                                            <?php if (!empty($settings['favicon'])): ?>
                                                <div class="mb-2 p-2 bg-light rounded">
                                                    <img src="<?php echo '../' . $settings['favicon']; ?>" style="max-height: 32px;">
                                                </div>
                                            <?php endif; ?>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="favicon" name="favicon" accept=".ico,.png,.svg">
                                                <label class="custom-file-label" for="favicon">Pilih file...</label>
                                            </div>
                                            <small class="text-muted">ICO, PNG, SVG. Maks 500KB</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Footer -->
                            <div class="tab-pane fade" id="footer">
                                <h5 class="mb-3"><i class="mdi mdi-page-layout-footer mr-1"></i>Pengaturan Footer</h5>
                                <div class="form-group">
                                    <label for="footer_text">Teks Footer</label>
                                    <textarea class="form-control" id="footer_text" name="footer_text" rows="3"
                                        placeholder="Teks deskripsi di footer..."><?php echo htmlspecialchars($settings['footer_text'] ?? ''); ?></textarea>
                                    <small class="form-text text-muted">Teks yang muncul di bagian footer website</small>
                                </div>
                                <div class="form-group">
                                    <label for="copyright_text">Teks Hak Cipta</label>
                                    <input type="text" class="form-control" id="copyright_text" name="copyright_text" 
                                        value="<?php echo htmlspecialchars($settings['copyright_text'] ?? ''); ?>" 
                                        maxlength="255" placeholder="© 2024 Nama Organisasi. All Rights Reserved.">
                                    <small class="form-text text-muted">Teks copyright di bawah footer</small>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="mdi mdi-content-save"></i> Simpan Semua Pengaturan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Panel Bantuan -->
            <div class="col-lg-3">
                <!-- Panduan -->
                <div class="card m-b-30 border-primary">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="mdi mdi-help-circle mr-2"></i>Panduan Tab</h6>
                    </div>
                    <div class="card-body">
                        <ul class="small mb-0 pl-3">
                            <li class="mb-2"><strong>Umum:</strong> Nama & deskripsi situs</li>
                            <li class="mb-2"><strong>Kontak:</strong> Telepon, email, alamat</li>
                            <li class="mb-2"><strong>Media Sosial:</strong> Link akun sosmed</li>
                            <li class="mb-2"><strong>Branding:</strong> Logo & favicon</li>
                            <li class="mb-0"><strong>Footer:</strong> Teks footer & copyright</li>
                        </ul>
                    </div>
                </div>

                <!-- Tips Logo -->
                <div class="card m-b-30 border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Tips Logo</h6>
                    </div>
                    <div class="card-body">
                        <ul class="small mb-0 pl-3">
                            <li class="mb-2">Format: PNG/SVG transparan</li>
                            <li class="mb-2">Logo terang untuk header gelap</li>
                            <li class="mb-2">Logo gelap untuk header terang</li>
                            <li class="mb-0">Ukuran ideal: 200×60px</li>
                        </ul>
                    </div>
                </div>

                <!-- Info SEO -->
                <div class="card m-b-30 border-info">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="mdi mdi-magnify mr-2"></i>SEO</h6>
                    </div>
                    <div class="card-body">
                        <p class="small mb-0">
                            <strong>Nama Situs</strong> dan <strong>Deskripsi</strong> 
                            akan digunakan untuk meningkatkan visibilitas di mesin pencari.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php echo $alert_script; ?>
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
// Update label file input
document.querySelectorAll('.custom-file-input').forEach(function(input) {
    input.addEventListener('change', function(e) {
        const fileName = this.files[0] ? this.files[0].name : 'Pilih file...';
        const label = this.nextElementSibling;
        label.textContent = fileName.length > 20 ? fileName.substring(0, 17) + '...' : fileName;
    });
});

// Validasi form
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    const siteName = document.getElementById('site_name').value.trim();

    if (!siteName) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Nama Situs Kosong!',
            text: 'Nama situs wajib diisi.',
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#dc3545'
        });
        return false;
    }

    Swal.fire({
        title: 'Menyimpan Pengaturan...',
        html: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => { Swal.showLoading(); }
    });

    return true;
});
</script>