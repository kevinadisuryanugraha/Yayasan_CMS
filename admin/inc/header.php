<div class="topbar">
    <nav class="navbar-custom">
        <ul class="list-inline float-right mb-0">
            <!-- View Website Button -->
            <li class="list-inline-item dropdown notification-list d-none d-md-inline-block">
                <a class="nav-link waves-effect" href="../" target="_blank" style="color: #ffffffff; font-weight: 500;">
                    <i class="mdi mdi-earth" style="font-size: 18px; margin-right: 5px;"></i> Lihat Website
                </a>
            </li>

            <!-- Notification Bell -->
            <li class="list-inline-item dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#"
                    role="button">
                    <i class="mdi mdi-bell-outline noti-icon"></i>
                    <span class="badge badge-danger noti-icon-badge">3</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg">
                    <h6 class="dropdown-item-text">Notifikasi</h6>
                    <div class="slimscroll notification-item-list">
                        <a href="javascript:void(0);" class="dropdown-item notify-item active">
                            <div class="notify-icon bg-success"><i class="mdi mdi-check-all"></i></div>
                            <p class="notify-details">User baru terdaftar<small class="text-muted">5 menit lalu</small>
                            </p>
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <div class="notify-icon bg-info"><i class="mdi mdi-pencil"></i></div>
                            <p class="notify-details">Konten diupdate<small class="text-muted">1 jam lalu</small></p>
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <div class="notify-icon bg-warning"><i class="mdi mdi-database"></i></div>
                            <p class="notify-details">Backup diperlukan<small class="text-muted">2 jam lalu</small></p>
                        </a>
                    </div>
                    <a href="javascript:void(0);" class="dropdown-item text-center notify-all">
                        Lihat Semua <i class="fi-arrow-right"></i>
                    </a>
                </div>
            </li>

            <!-- User Profile Dropdown -->
            <li class="list-inline-item dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#"
                    role="button">
                    <span class="ml-1 nav-user-name d-none d-lg-inline-block text-white">
                        <?php echo htmlspecialchars($_SESSION['NAME'] ?? 'Admin'); ?>
                        <i class="mdi mdi-chevron-down"></i>
                    </span>
                    <img src="assets/images/users/avatar-1.jpg" alt="user" class="rounded-circle" width="36">
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown">
                    <div class="dropdown-item noti-title">
                        <h5 class="text-overflow"><small>Selamat
                                datang,</small><br><?php echo htmlspecialchars($_SESSION['NAME'] ?? 'Admin'); ?></h5>
                        <small class="text-muted"><?php echo htmlspecialchars($_SESSION['EMAIL'] ?? ''); ?></small>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="?page=settings"><i class="mdi mdi-cog m-r-5 text-muted"></i>
                        Pengaturan Situs</a>
                    <a class="dropdown-item" href="?page=appearance"><i class="mdi mdi-palette m-r-5 text-muted"></i>
                        Tampilan</a>
                    <a class="dropdown-item" href="?page=users"><i
                            class="mdi mdi-account-multiple m-r-5 text-muted"></i> Kelola User</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="logout.php"><i class="mdi mdi-power m-r-5"></i>
                        Keluar</a>
                </div>
            </li>
        </ul>

        <ul class="list-inline menu-left mb-0">
            <li class="float-left">
                <button class="button-menu-mobile open-left waves-light waves-effect">
                    <i class="mdi mdi-menu"></i>
                </button>
            </li>
            <li class="hide-phone app-search">
                <form role="search" class="pro-search-form">
                    <div class="pro-search-wrapper">
                        <i class="mdi mdi-magnify pro-search-icon"></i>
                        <input type="text" placeholder="Cari menu, konten, pengaturan..."
                            class="form-control pro-search-input">
                    </div>
                </form>
            </li>
        </ul>

        <div class="clearfix"></div>
    </nav>
</div>

<!-- Minimal Custom Styles - Compatible with Template -->
<style>
    .nav-user-name {
        font-weight: 500;
        color: #333;
        margin-right: 8px;
    }

    .profile-dropdown {
        min-width: 220px;
    }

    .profile-dropdown .noti-title h5 {
        font-size: 14px;
        margin-bottom: 5px;
    }

    .profile-dropdown .dropdown-item {
        padding: 8px 20px;
    }

    .profile-dropdown .dropdown-item i {
        width: 20px;
    }

    .notify-all {
        background: #f8f9fa;
        font-weight: 500;
    }

    /* Professional Search Bar Styles */
    .pro-search-form {
        margin-left: 15px;
    }

    .pro-search-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .pro-search-icon {
        position: absolute;
        left: 14px;
        font-size: 20px;
        color: #6c757d;
        z-index: 10;
        transition: color 0.3s ease;
    }

    .pro-search-input {
        width: 280px !important;
        height: 42px !important;
        padding: 10px 90px 10px 45px !important;
        background: #f4f5f7 !important;
        border: 2px solid transparent !important;
        border-radius: 10px !important;
        font-size: 14px !important;
        color: #333 !important;
        transition: all 0.3s ease !important;
        box-shadow: none !important;
    }

    .pro-search-input::placeholder {
        color: #9ca3af !important;
        font-weight: 400;
    }

    .pro-search-input:focus {
        width: 340px !important;
        background: #fff !important;
        border-color: #667eea !important;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15) !important;
        outline: none;
    }

    .pro-search-input:focus+.pro-search-kbd,
    .pro-search-wrapper:hover .pro-search-kbd {
        opacity: 0;
    }

    .pro-search-wrapper:hover .pro-search-icon,
    .pro-search-input:focus~.pro-search-icon {
        color: #667eea;
    }

    .pro-search-kbd {
        position: absolute;
        right: 12px;
        background: #e5e7eb;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        font-family: inherit;
        border: 1px solid #d1d5db;
        transition: opacity 0.3s ease;
    }

    /* Hide old search styles */
    .app-search a {
        display: none !important;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .pro-search-input {
            width: 200px !important;
        }

        .pro-search-input:focus {
            width: 240px !important;
        }

        .pro-search-kbd {
            display: none;
        }
    }
</style>