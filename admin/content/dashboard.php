<?php
// Dashboard - Panel Statistik Admin
// Hitung statistik dari database
$total_users = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users"));
$total_events = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM events WHERE is_active = 1"));
$total_programs = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM campaign_programs WHERE is_active = 1"));
$total_quotes = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM quotes"));
$total_features = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM feature_section WHERE is_active = 1"));
$total_services = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM service_section WHERE is_active = 1"));
?>

<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="home.php">Beranda</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>

    <!-- Kartu Selamat Datang -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30 bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <h4 class="text-white">Selamat Datang, <?php echo htmlspecialchars($_SESSION['NAME']); ?>!
                            </h4>
                            <p class="mb-0">Panel admin untuk mengelola konten website Hafsa. Gunakan menu di samping
                                untuk navigasi.</p>
                        </div>
                        <div class="col-lg-4 text-right">
                            <i class="bi bi-person-circle" style="font-size: 80px; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu Statistik -->
    <div class="row">
        <!-- Total Users -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary mini-stat text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <i class="bi bi-people-fill" style="font-size: 40px;"></i>
                        </div>
                        <h5 class="font-16 text-uppercase mt-0 text-white-50">Total Pengguna</h5>
                        <h4 class="font-500"><?php echo $total_users; ?></h4>
                    </div>
                    <div class="pt-2">
                        <a href="?page=users" class="text-white-50">Kelola Pengguna <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Events -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success mini-stat text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <i class="bi bi-calendar-check-fill" style="font-size: 40px;"></i>
                        </div>
                        <h5 class="font-16 text-uppercase mt-0 text-white-50">Acara Aktif</h5>
                        <h4 class="font-500"><?php echo $total_events; ?></h4>
                    </div>
                    <div class="pt-2">
                        <a href="?page=events" class="text-white-50">Kelola Acara <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Programs -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning mini-stat text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <i class="bi bi-heart-fill" style="font-size: 40px;"></i>
                        </div>
                        <h5 class="font-16 text-uppercase mt-0 text-white-50">Program</h5>
                        <h4 class="font-500"><?php echo $total_programs; ?></h4>
                    </div>
                    <div class="pt-2">
                        <a href="?page=programs" class="text-white-50">Kelola Program <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quotes -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger mini-stat text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <i class="bi bi-quote" style="font-size: 40px;"></i>
                        </div>
                        <h5 class="font-16 text-uppercase mt-0 text-white-50">Kutipan</h5>
                        <h4 class="font-500"><?php echo $total_quotes; ?></h4>
                    </div>
                    <div class="pt-2">
                        <a href="?page=quotes" class="text-white-50">Kelola Kutipan <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & System Info -->
    <div class="row">
        <!-- Quick Actions -->
        <div class="col-xl-6">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">
                        <i class="bi bi-lightning-fill text-warning mr-2"></i>Aksi Cepat
                    </h4>
                    <div class="row">
                        <div class="col-6 col-md-4 mb-3">
                            <a href="?page=add_event" class="btn btn-outline-success btn-block">
                                <i class="bi bi-calendar-plus d-block" style="font-size: 24px;"></i>
                                Tambah Acara
                            </a>
                        </div>
                        <div class="col-6 col-md-4 mb-3">
                            <a href="?page=add_program" class="btn btn-outline-primary btn-block">
                                <i class="bi bi-heart-pulse d-block" style="font-size: 24px;"></i>
                                Tambah Program
                            </a>
                        </div>
                        <div class="col-6 col-md-4 mb-3">
                            <a href="?page=add_quote" class="btn btn-outline-warning btn-block">
                                <i class="bi bi-chat-quote d-block" style="font-size: 24px;"></i>
                                Tambah Kutipan
                            </a>
                        </div>
                        <div class="col-6 col-md-4 mb-3">
                            <a href="?page=hero" class="btn btn-outline-info btn-block">
                                <i class="bi bi-image d-block" style="font-size: 24px;"></i>
                                Hero Section
                            </a>
                        </div>
                        <div class="col-6 col-md-4 mb-3">
                            <a href="?page=settings" class="btn btn-outline-secondary btn-block">
                                <i class="bi bi-gear d-block" style="font-size: 24px;"></i>
                                Pengaturan
                            </a>
                        </div>
                        <div class="col-6 col-md-4 mb-3">
                            <a href="?page=users" class="btn btn-outline-dark btn-block">
                                <i class="bi bi-people d-block" style="font-size: 24px;"></i>
                                Kelola Admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="col-xl-6">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">
                        <i class="bi bi-info-circle text-info mr-2"></i>Informasi Sistem
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                <tr>
                                    <td><i class="bi bi-hdd-rack text-primary mr-2"></i> Status Server</td>
                                    <td class="text-right"><span class="badge badge-success">Online</span></td>
                                </tr>
                                <tr>
                                    <td><i class="bi bi-database text-info mr-2"></i> Database</td>
                                    <td class="text-right"><span class="badge badge-success">Terhubung</span></td>
                                </tr>
                                <tr>
                                    <td><i class="bi bi-person-check text-warning mr-2"></i> Login sebagai</td>
                                    <td class="text-right">
                                        <strong><?php echo htmlspecialchars($_SESSION['NAME']); ?></strong></td>
                                </tr>
                                <tr>
                                    <td><i class="bi bi-envelope text-danger mr-2"></i> Email</td>
                                    <td class="text-right"><?php echo htmlspecialchars($_SESSION['EMAIL']); ?></td>
                                </tr>
                                <tr>
                                    <td><i class="bi bi-clock text-secondary mr-2"></i> Waktu Sekarang</td>
                                    <td class="text-right"><?php echo date('d M Y, H:i'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-3 align-self-center">
                            <div class="round bg-info">
                                <i class="bi bi-star-fill text-white"></i>
                            </div>
                        </div>
                        <div class="col-9 text-right">
                            <h3 class="card-title font-medium"><?php echo $total_features; ?></h3>
                            <h6 class="card-subtitle">Fitur</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-3 align-self-center">
                            <div class="round bg-purple">
                                <i class="bi bi-hand-thumbs-up-fill text-white"></i>
                            </div>
                        </div>
                        <div class="col-9 text-right">
                            <h3 class="card-title font-medium"><?php echo $total_services; ?></h3>
                            <h6 class="card-subtitle">Layanan</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-3 align-self-center">
                            <div class="round bg-success">
                                <i class="bi bi-check-lg text-white"></i>
                            </div>
                        </div>
                        <div class="col-9 text-right">
                            <h3 class="card-title font-medium">Aktif</h3>
                            <h6 class="card-subtitle">Status Sistem</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-3 align-self-center">
                            <div class="round bg-warning">
                                <i class="bi bi-globe text-white"></i>
                            </div>
                        </div>
                        <div class="col-9 text-right">
                            <h3 class="card-title font-medium">Online</h3>
                            <h6 class="card-subtitle">Website</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .round {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .round i {
        font-size: 24px;
    }

    .bg-purple {
        background-color: #7c4dff;
    }
</style>