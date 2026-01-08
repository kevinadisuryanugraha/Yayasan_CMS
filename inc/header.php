<?php
// Load site settings from database
include_once __DIR__ . '/site_settings.php';
?>
<!-- Professional Modern Header -->
<header class="hafsa-header">
    <!-- Top Bar -->
    <div class="header-topbar">
        <div class="container">
            <div class="topbar-inner">
                <!-- Left: Contact Info -->
                <div class="topbar-left">
                    <ul class="topbar-info">
                        <?php if (!empty($site_settings['phone_primary'])): ?>
                            <li>
                                <a href="tel:<?php echo htmlspecialchars($site_settings['phone_primary']); ?>">
                                    <i class="icofont-phone"></i>
                                    <span><?php echo htmlspecialchars($site_settings['phone_primary']); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($site_settings['email_primary'])): ?>
                            <li>
                                <a href="mailto:<?php echo htmlspecialchars($site_settings['email_primary']); ?>">
                                    <i class="icofont-email"></i>
                                    <span><?php echo htmlspecialchars($site_settings['email_primary']); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($site_settings['address'])): ?>
                            <li class="d-none d-lg-inline-flex address-marquee-container">
                                <i class="icofont-location-pin"></i>
                                <span class="address-text">
                                    <?php echo htmlspecialchars($site_settings['address']); ?>
                                </span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <!-- Right: Social Links -->
                <div class="topbar-right">
                    <ul class="social-links">
                        <?php if (!empty($site_settings['facebook_url'])): ?>
                            <li><a href="<?php echo htmlspecialchars($site_settings['facebook_url']); ?>" target="_blank"
                                    aria-label="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                        <?php endif; ?>
                        <?php if (!empty($site_settings['twitter_url'])): ?>
                            <li><a href="<?php echo htmlspecialchars($site_settings['twitter_url']); ?>" target="_blank"
                                    aria-label="Twitter"><i class="fab fa-twitter"></i></a></li>
                        <?php endif; ?>
                        <?php if (!empty($site_settings['instagram_url'])): ?>
                            <li><a href="<?php echo htmlspecialchars($site_settings['instagram_url']); ?>" target="_blank"
                                    aria-label="Instagram"><i class="fab fa-instagram"></i></a></li>
                        <?php endif; ?>
                        <?php if (!empty($site_settings['youtube_url'])): ?>
                            <li><a href="<?php echo htmlspecialchars($site_settings['youtube_url']); ?>" target="_blank"
                                    aria-label="YouTube"><i class="fab fa-youtube"></i></a></li>
                        <?php endif; ?>
                        <?php if (!empty($site_settings['whatsapp_number'])): ?>
                            <li><a href="https://wa.me/<?php echo htmlspecialchars($site_settings['whatsapp_number']); ?>"
                                    target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="header-navbar" id="mainNavbar">
        <div class="container">
            <div class="navbar-inner">
                <!-- Logo -->
                <div class="navbar-brand">
                    <a href="index.php" class="logo-link">
                        <img src="<?php echo htmlspecialchars($site_settings['logo_light'] ?? 'assets/images/logo/01.png'); ?>"
                            alt="<?php echo htmlspecialchars($site_settings['site_name'] ?? 'Hafsa'); ?>"
                            class="logo-img">
                    </a>
                </div>

                <!-- Mobile Toggle -->
                <button class="navbar-toggler" id="navToggler" aria-label="Toggle navigation">
                    <span class="toggler-icon"></span>
                    <span class="toggler-icon"></span>
                    <span class="toggler-icon"></span>
                </button>

                <!-- Navigation Menu -->
                <div class="navbar-menu" id="navMenu">
                    <div class="menu-backdrop" id="menuBackdrop"></div>
                    <div class="menu-container">
                        <button class="menu-close" id="menuClose" aria-label="Close menu">
                            <i class="icofont-close-line"></i>
                        </button>

                        <ul class="nav-links">
                            <li class="nav-item">
                                <a href="index.php" class="nav-link active">
                                    <i class="icofont-home"></i>
                                    <span>Beranda</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="about.php" class="nav-link">
                                    <i class="icofont-info-circle"></i>
                                    <span>Tentang Kami</span>
                                </a>
                            </li>
                            <li class="nav-item has-dropdown">
                                <a href="#0" class="nav-link dropdown-toggle">
                                    <i class="icofont-calendar"></i>
                                    <span>Acara</span>
                                    <i class="icofont-rounded-down dropdown-arrow"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="events.php">Semua Acara</a></li>
                                    <li><a href="events-single.php">Detail Acara</a></li>
                                </ul>
                            </li>
                            <li class="nav-item has-dropdown">
                                <a href="#0" class="nav-link dropdown-toggle">
                                    <i class="icofont-heart-alt"></i>
                                    <span>Program</span>
                                    <i class="icofont-rounded-down dropdown-arrow"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="programs.php">Semua Program</a></li>
                                    <li><a href="program-single.php">Detail Program</a></li>
                                </ul>
                            </li>
                            <li class="nav-item has-dropdown">
                                <a href="#0" class="nav-link dropdown-toggle">
                                    <i class="icofont-ui-file"></i>
                                    <span>Halaman</span>
                                    <i class="icofont-rounded-down dropdown-arrow"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="gallery.php">Galeri</a></li>
                                    <li><a href="scholar.php">Ulama Kami</a></li>
                                    <li><a href="blog.php">Blog</a></li>
                                    <li><a href="sermons.php">Ceramah</a></li>
                                    <li><a href="services.php">Layanan</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="contact.php" class="nav-link">
                                    <i class="icofont-envelope"></i>
                                    <span>Kontak</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>

<style>
    /* ============================================
   HAFSA HEADER - Professional Modern Design
   ============================================ */

    /* CSS Variables */
    .hafsa-header {
        --header-primary: var(--primary-color, #2E7D32);
        --header-secondary: var(--secondary-color, #1565C0);
        --header-accent: var(--accent-color, #FF9800);
        --header-dark: #1a1a2e;
        --header-light: #ffffff;
        --header-gray: #6c757d;
        --header-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --header-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        --header-radius: var(--btn-radius, 8px);
    }

    .hafsa-header {
        position: relative;
        z-index: 1000;
        font-family: var(--font-family, 'Poppins'), sans-serif;
    }

    /* ============================================
   TOP BAR
   ============================================ */
    .header-topbar {
        background: linear-gradient(135deg, var(--header-dark) 0%, #16213e 100%);
        padding: 10px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        width: 100%;
        box-sizing: border-box;
        margin: 0;
        position: relative;
        left: 0;
        right: 0;
    }

    .header-topbar .topbar-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
    }

    .header-topbar .topbar-info {
        display: flex;
        align-items: center;
        gap: 25px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .header-topbar .topbar-info li {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: rgba(255, 255, 255, 0.85);
        font-size: 13px;
    }

    .header-topbar .topbar-info li a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        transition: var(--header-transition);
    }

    .header-topbar .topbar-info li a:hover {
        color: var(--header-accent);
    }

    .header-topbar .topbar-info li i {
        color: var(--header-accent);
        font-size: 14px;
    }

    /* Address Marquee Animation - Edge to Edge */
    .header-topbar .address-marquee-container {
        max-width: 350px;
        overflow: hidden;
        display: flex;
        align-items: center;
    }

    .header-topbar .address-marquee {
        width: 250px;
        overflow: hidden;
        white-space: nowrap;
    }

    .header-topbar .address-marquee .address-text {
        display: inline-block;
        padding-left: 100%;
        animation: marquee-scroll 12s linear infinite;
    }

    @keyframes marquee-scroll {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-200%);
        }
    }

    .header-topbar .address-marquee:hover .address-text {
        animation-play-state: paused;
    }

    /* Social Links */
    .header-topbar .social-links {
        display: flex;
        align-items: center;
        gap: 5px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .header-topbar .social-links li a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.8);
        font-size: 13px;
        transition: var(--header-transition);
    }

    .header-topbar .social-links li a:hover {
        background: var(--header-accent);
        color: var(--header-light);
        transform: translateY(-2px);
    }

    /* ============================================
   MAIN NAVBAR
   ============================================ */
    .header-navbar {
        background: var(--header-primary);
        padding: 0;
        position: sticky;
        top: 0;
        z-index: 999;
        transition: var(--header-transition);
        box-shadow: var(--header-shadow);
    }

    .header-navbar.scrolled {
        background: var(--header-dark);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15);
    }

    .header-navbar .navbar-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 70px;
    }

    /* Logo */
    .header-navbar .navbar-brand .logo-link {
        display: block;
        padding: 10px 0;
    }

    .header-navbar .navbar-brand .logo-img {
        max-height: 50px;
        width: auto;
        transition: var(--header-transition);
    }

    .header-navbar .navbar-brand:hover .logo-img {
        transform: scale(1.02);
    }

    /* Mobile Toggler */
    .header-navbar .navbar-toggler {
        display: none;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 5px;
        width: 44px;
        height: 44px;
        padding: 10px;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        border-radius: var(--header-radius);
        cursor: pointer;
        transition: var(--header-transition);
    }

    .header-navbar .navbar-toggler:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .header-navbar .navbar-toggler .toggler-icon {
        display: block;
        width: 22px;
        height: 2px;
        background: var(--header-light);
        border-radius: 2px;
        transition: var(--header-transition);
    }

    .header-navbar .navbar-toggler.active .toggler-icon:nth-child(1) {
        transform: rotate(45deg) translate(5px, 5px);
    }

    .header-navbar .navbar-toggler.active .toggler-icon:nth-child(2) {
        opacity: 0;
    }

    .header-navbar .navbar-toggler.active .toggler-icon:nth-child(3) {
        transform: rotate(-45deg) translate(5px, -5px);
    }

    /* Navigation Menu Container */
    .header-navbar .navbar-menu {
        display: flex;
        align-items: center;
    }

    .header-navbar .menu-container {
        display: flex;
        align-items: center;
        gap: 30px;
    }

    .header-navbar .menu-close {
        display: none;
    }

    .header-navbar .menu-backdrop {
        display: none;
    }

    /* Navigation Links */
    .header-navbar .nav-links {
        display: flex;
        align-items: center;
        gap: 5px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .header-navbar .nav-item {
        position: relative;
    }

    .header-navbar .nav-link {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 12px 18px;
        color: rgba(255, 255, 255, 0.9);
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        border-radius: var(--header-radius);
        transition: var(--header-transition);
        white-space: nowrap;
    }

    .header-navbar .nav-link i:first-child {
        font-size: 16px;
        opacity: 0.8;
    }

    .header-navbar .nav-link .dropdown-arrow {
        font-size: 10px;
        margin-left: 2px;
        transition: var(--header-transition);
    }

    .header-navbar .nav-link:hover,
    .header-navbar .nav-link.active {
        background: rgba(255, 255, 255, 0.15);
        color: var(--header-light);
    }

    .header-navbar .nav-item:hover .nav-link .dropdown-arrow {
        transform: rotate(180deg);
    }

    /* Dropdown Menu - Override Bootstrap */
    .hafsa-header .header-navbar .nav-item .dropdown-menu {
        position: absolute !important;
        top: 100% !important;
        left: 0 !important;
        min-width: 220px !important;
        padding: 10px 0 !important;
        margin: 0 !important;
        list-style: none !important;
        background: #ffffff !important;
        border: none !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2) !important;
        opacity: 0;
        visibility: hidden;
        transform: translateY(15px);
        transition: all 0.3s ease !important;
        z-index: 99999 !important;
    }

    .hafsa-header .header-navbar .nav-item.has-dropdown:hover>.dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .hafsa-header .header-navbar .dropdown-menu li {
        list-style: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .hafsa-header .header-navbar .dropdown-menu li a {
        display: flex !important;
        align-items: center !important;
        padding: 12px 20px !important;
        color: var(--header-dark) !important;
        font-size: 14px !important;
        text-decoration: none !important;
        transition: var(--header-transition) !important;
        background: transparent !important;
    }

    .hafsa-header .header-navbar .dropdown-menu li a:hover {
        background: linear-gradient(90deg, var(--header-primary), transparent) !important;
        color: var(--header-light) !important;
        padding-left: 25px !important;
    }

    /* CTA Button */
    .header-navbar .nav-cta .cta-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: var(--header-accent);
        color: var(--header-light);
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border-radius: calc(var(--header-radius) * 3);
        box-shadow: 0 4px 15px rgba(255, 152, 0, 0.4);
        transition: var(--header-transition);
    }

    .header-navbar .nav-cta .cta-button:hover {
        background: var(--header-light);
        color: var(--header-primary);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }

    .header-navbar .nav-cta .cta-button i {
        font-size: 16px;
    }

    /* ============================================
   RESPONSIVE - TABLET & MOBILE
   ============================================ */
    @media (max-width: 1199px) {
        .header-navbar .nav-link {
            padding: 10px 12px;
            font-size: 13px;
        }

        .header-navbar .menu-container {
            gap: 15px;
        }
    }

    @media (max-width: 991px) {

        /* Show mobile toggler */
        .header-navbar .navbar-toggler {
            display: flex;
        }

        /* Mobile Menu Overlay */
        .header-navbar .navbar-menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
            pointer-events: none;
        }

        .header-navbar .navbar-menu.active {
            pointer-events: auto;
        }

        .header-navbar .menu-backdrop {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .header-navbar .navbar-menu.active .menu-backdrop {
            opacity: 1;
        }

        .header-navbar .menu-container {
            position: absolute;
            top: 0;
            right: -320px;
            width: 300px;
            max-width: 85%;
            height: 100%;
            flex-direction: column;
            align-items: stretch;
            gap: 0;
            padding: 20px;
            background: var(--header-dark);
            overflow-y: auto;
            transition: right 0.3s ease;
        }

        .header-navbar .navbar-menu.active .menu-container {
            right: 0;
        }

        .header-navbar .menu-close {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            width: 100%;
            padding: 10px 0 20px;
            background: none;
            border: none;
            color: var(--header-light);
            font-size: 24px;
            cursor: pointer;
        }

        .header-navbar .nav-links {
            flex-direction: column;
            align-items: stretch;
            gap: 5px;
            width: 100%;
        }

        .header-navbar .nav-link {
            padding: 14px 15px;
            border-radius: var(--header-radius);
        }

        .header-navbar .nav-link i:first-child {
            display: inline-block;
        }

        /* Mobile Dropdown Override */
        .hafsa-header .header-navbar .nav-item .dropdown-menu {
            position: static !important;
            opacity: 1 !important;
            visibility: visible !important;
            transform: none !important;
            padding: 0 0 0 20px !important;
            background: transparent !important;
            box-shadow: none !important;
            display: none !important;
            min-width: auto !important;
            z-index: auto !important;
        }

        .hafsa-header .header-navbar .nav-item.dropdown-open>.dropdown-menu {
            display: block !important;
        }

        .hafsa-header .header-navbar .dropdown-menu li a {
            color: rgba(255, 255, 255, 0.8) !important;
            padding: 10px 15px !important;
            background: transparent !important;
        }

        .hafsa-header .header-navbar .dropdown-menu li a:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            color: var(--header-accent) !important;
            padding-left: 20px !important;
        }

        .header-navbar .nav-cta {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .header-navbar .nav-cta .cta-button {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 575px) {
        .header-topbar .topbar-inner {
            justify-content: center;
        }

        .header-topbar .topbar-info {
            flex-direction: column;
            gap: 8px;
            align-items: center;
        }

        .header-topbar .topbar-right {
            display: none;
        }

        .header-navbar .navbar-brand .logo-img {
            max-height: 40px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navbar = document.getElementById('mainNavbar');
        const navToggler = document.getElementById('navToggler');
        const navMenu = document.getElementById('navMenu');
        const menuClose = document.getElementById('menuClose');
        const menuBackdrop = document.getElementById('menuBackdrop');
        const dropdownItems = document.querySelectorAll('.has-dropdown');

        // Scroll effect
        window.addEventListener('scroll', function () {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Mobile menu toggle
        if (navToggler) {
            navToggler.addEventListener('click', function () {
                this.classList.toggle('active');
                navMenu.classList.toggle('active');
                document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
            });
        }

        // Close menu
        function closeMenu() {
            navToggler.classList.remove('active');
            navMenu.classList.remove('active');
            document.body.style.overflow = '';
        }

        if (menuClose) {
            menuClose.addEventListener('click', closeMenu);
        }

        if (menuBackdrop) {
            menuBackdrop.addEventListener('click', closeMenu);
        }

        // Dropdown hover for desktop & click for mobile
        dropdownItems.forEach(function (item) {
            const link = item.querySelector('.dropdown-toggle');
            const menu = item.querySelector('.dropdown-menu');

            if (link && menu) {
                // Desktop: hover behavior
                item.addEventListener('mouseenter', function () {
                    if (window.innerWidth > 991) {
                        menu.style.display = 'block';
                        menu.style.opacity = '1';
                        menu.style.visibility = 'visible';
                        menu.style.transform = 'translateY(0)';
                    }
                });

                item.addEventListener('mouseleave', function () {
                    if (window.innerWidth > 991) {
                        menu.style.display = '';
                        menu.style.opacity = '';
                        menu.style.visibility = '';
                        menu.style.transform = '';
                    }
                });

                // Mobile: click behavior
                link.addEventListener('click', function (e) {
                    if (window.innerWidth <= 991) {
                        e.preventDefault();
                        item.classList.toggle('dropdown-open');
                    }
                });
            }
        });

        // Set active link based on current page
        const currentPage = window.location.pathname.split('/').pop() || 'index.php';
        const navLinks = document.querySelectorAll('.hafsa-header .nav-link');
        navLinks.forEach(function (link) {
            const href = link.getAttribute('href');
            if (href === currentPage || (currentPage === '' && href === 'index.php')) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    });
</script>