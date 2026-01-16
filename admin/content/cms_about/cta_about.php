<?php
// CTA Section CMS
if (!defined('INDEX_AUTH')) {
    header("Location: index.php");
    exit;
}

// 1. Cek & Buat Tabel Database (Auto-Seeding)
$check_table = mysqli_query($conn, "SHOW TABLES LIKE 'about_cta_section'");
if (mysqli_num_rows($check_table) == 0) {
    mysqli_query($conn, "CREATE TABLE `about_cta_section` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(255) DEFAULT NULL,
        `description` text,
        `btn_primary_text` varchar(100) DEFAULT NULL,
        `btn_primary_link` varchar(255) DEFAULT NULL,
        `btn_outline_text` varchar(100) DEFAULT NULL,
        `btn_outline_link` varchar(255) DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Seed Default Data
    $title = "Mari Bergabung Bersama Kami";
    $desc = "Jadilah bagian dari perubahan positif. Bersama kita wujudkan generasi Indonesia yang lebih baik.";
    mysqli_query($conn, "INSERT INTO about_cta_section VALUES (1, '$title', '$desc', 'Hubungi Kami', '?page=contact', 'Donasi Sekarang', '?page=donate')");
}

// Fetch Data
$query = mysqli_query($conn, "SELECT * FROM about_cta_section WHERE id = 1");
$data = mysqli_fetch_assoc($query);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=intro_section_about">About</a></li>
                        <li class="breadcrumb-item active">CTA Section</li>
                    </ol>
                </div>
                <h4 class="page-title">Kelola Call to Action (CTA)</h4>
            </div>
        </div>
    </div>

    <!-- Alert System -->
    <?php if (isset($_SESSION['alert'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: '<?php echo $_SESSION['alert']['type']; ?>',
                    title: '<?php echo $_SESSION['alert']['title']; ?>',
                    text: '<?php echo $_SESSION['alert']['message']; ?>',
                    showConfirmButton: false,
                    timer: 1500
                });
            });
        </script>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>

    <!-- Kartu Petunjuk -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30 bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h5 class="mb-2"><i class="mdi mdi-bullhorn text-primary mr-2"></i>Tentang CTA Section</h5>
                            <p class="mb-0 text-muted">
                                Bagian ini adalah ajakan bertindak (Call to Action) yang terletak di bagian bawah
                                halaman About.
                                Biasanya berisi tombol untuk menghubungi yayasan atau berdonasi.
                            </p>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="p-3 bg-white rounded shadow-sm">
                                <i class="icofont-megaphone-alt text-warning" style="font-size: 30px;"></i>
                                <h6 class="mt-2 mb-0">Action!</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Display -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-header bg-white row align-items-center m-0">
                    <div class="col-md-9">
                        <h5 class="m-0 text-primary"><i class="mdi mdi-monitor-dashboard mr-2"></i>Konten CTA Saat Ini
                        </h5>
                    </div>
                    <div class="col-md-3 text-right">
                        <a href="?page=edit_cta" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-pencil"></i> Edit Konten
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-8 text-center p-5 rounded text-white"
                            style="background: linear-gradient(135deg, #0a294a 0%, #00997d 100%);">

                            <!-- Title Preview -->
                            <h2 class="mb-3">
                                <?php echo htmlspecialchars($data['title']); ?>
                            </h2>

                            <!-- Desc Preview -->
                            <p class="mb-4 text-white-50" style="font-size: 1.1rem;">
                                <?php echo htmlspecialchars($data['description']); ?>
                            </p>

                            <!-- Buttons Preview -->
                            <div class="d-flex justify-content-center gap-3">
                                <button class="btn btn-light rounded-pill px-4 font-weight-bold mx-2" disabled>
                                    <?php echo htmlspecialchars($data['btn_primary_text']); ?>
                                </button>
                                <button class="btn btn-outline-light rounded-pill px-4 font-weight-bold mx-2" disabled>
                                    <?php echo htmlspecialchars($data['btn_outline_text']); ?>
                                </button>
                            </div>

                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6 border-right">
                            <strong>Link Tombol Utama:</strong><br>
                            <code class="text-primary"><?php echo htmlspecialchars($data['btn_primary_link']); ?></code>
                        </div>
                        <div class="col-md-6">
                            <strong>Link Tombol Kedua:</strong><br>
                            <code class="text-primary"><?php echo htmlspecialchars($data['btn_outline_link']); ?></code>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>