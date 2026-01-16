<?php
// Note: Database connection ($conn) is already available via index.php
// Appearance settings are also loaded via inc/appearance_settings.php
// Fetch Appearance Settings
$appearance_query = mysqli_query($conn, "SELECT * FROM appearance_settings LIMIT 1");
$appearance = mysqli_fetch_assoc($appearance_query);

$cms_primary = $appearance['primary_color'] ?? '#00997d';
$cms_secondary = $appearance['secondary_color'] ?? '#0a294a';
$cms_accent = $appearance['accent_color'] ?? '#fab702';

// Fetch Events Header (table only has: id, subtitle, title, updated_at)
$events_header_query = mysqli_query($conn, "SELECT * FROM events_header LIMIT 1");
$events_header = mysqli_fetch_assoc($events_header_query);

if (!$events_header) {
    $events_header = [
        'subtitle' => 'Jadwal Kegiatan',
        'title' => 'Acara & Kegiatan Kami',
        'description' => 'Bergabunglah dalam berbagai kegiatan positif bersama Yayasan Indonesia Bijak Bestari'
    ];
}

// Get filter parameters
$category_filter = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'upcoming';

// Build query
$where_conditions = ["is_active = 1", "status = 'published'"];

if (!empty($category_filter)) {
    $category_filter_escaped = mysqli_real_escape_string($conn, $category_filter);
    $where_conditions[] = "category = '$category_filter_escaped'";
}

if ($status_filter == 'upcoming') {
    $where_conditions[] = "event_date >= CURDATE()";
} elseif ($status_filter == 'past') {
    $where_conditions[] = "event_date < CURDATE()";
}

$where_clause = implode(' AND ', $where_conditions);
$events_query = mysqli_query($conn, "SELECT * FROM events WHERE $where_clause ORDER BY event_date ASC, order_position ASC");

// Fetch categories for filter
$categories_query = mysqli_query($conn, "SELECT DISTINCT category FROM events WHERE is_active = 1 AND status = 'published'");
$categories = [];
while ($row = mysqli_fetch_assoc($categories_query)) {
    $categories[] = $row['category'];
}

// Fetch featured event
$featured_query = mysqli_query($conn, "SELECT * FROM events WHERE is_active = 1 AND status = 'published' AND is_featured = 1 AND event_date >= CURDATE() ORDER BY event_date ASC LIMIT 1");
$featured_event = mysqli_fetch_assoc($featured_query);

// Fetch site settings
$site_query = mysqli_query($conn, "SELECT * FROM site_settings LIMIT 1");
$site_settings = mysqli_fetch_assoc($site_query);
?>

<!-- Event Page External Stylesheet -->
<link rel="stylesheet" href="assets/css/event.css">

<!-- Dynamic CSS Variables -->
<style>
    :root {
        --event-primary:
            <?php echo $cms_primary; ?>
        ;
        --event-secondary:
            <?php echo $cms_secondary; ?>
        ;
        --event-accent:
            <?php echo $cms_accent; ?>
        ;
    }
</style>

<!-- ========================================
     HERO BANNER
======================================== -->
<section class="event-hero-section" aria-label="Banner Halaman Acara">
    <div class="event-hero-float float-1" aria-hidden="true"></div>
    <div class="event-hero-float float-2" aria-hidden="true"></div>

    <div class="container">
        <div class="event-hero-content">
            <div class="event-hero-badge" aria-label="Identitas Halaman">
                <i class="icofont-calendar" aria-hidden="true"></i>
                <span>
                    <?php echo htmlspecialchars($site_settings['site_name'] ?? 'Yayasan Indonesia Bijak Bestari'); ?>
                </span>
            </div>
            <h1 class="event-hero-title">
                <?php echo htmlspecialchars($events_header['title']); ?>
            </h1>
            <p class="event-hero-desc">
                <?php echo htmlspecialchars($events_header['description'] ?? 'Bergabunglah dalam berbagai kegiatan positif bersama kami'); ?>
            </p>
            <nav class="event-breadcrumb" aria-label="Navigasi Breadcrumb">
                <a href="index.php" aria-label="Kembali ke Beranda"><i class="icofont-home" aria-hidden="true"></i>
                    Beranda</a>
                <i class="icofont-rounded-right" aria-hidden="true"></i>
                <span aria-current="page">Acara</span>
            </nav>
        </div>
    </div>
</section>

<!-- ========================================
     FEATURED EVENT (If Available)
======================================== -->
<?php if ($featured_event): ?>
    <section class="event-featured-section">
        <div class="container">
            <div class="featured-event-card">
                <div class="featured-event-badge">
                    <i class="icofont-star"></i> Acara Unggulan
                </div>
                <div class="featured-event-inner">
                    <div class="featured-event-image">
                        <?php
                        $img_src = 'assets/images/placeholder_landscape.jpg';
                        if (!empty($featured_event['image'])) {
                            if (file_exists($featured_event['image']))
                                $img_src = $featured_event['image'];
                            elseif (file_exists('admin/' . $featured_event['image']))
                                $img_src = 'admin/' . $featured_event['image'];
                        }
                        ?>
                        <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($featured_event['title']); ?>"
                            loading="lazy">
                        <?php if ($featured_event['countdown_enabled'] && $featured_event['countdown_date']): ?>
                            <div class="featured-countdown"
                                data-date="<?php echo date('Y-m-d H:i:s', strtotime($featured_event['countdown_date'])); ?>">
                                <div class="countdown-item"><span class="days">00</span><small>Hari</small></div>
                                <div class="countdown-item"><span class="hours">00</span><small>Jam</small></div>
                                <div class="countdown-item"><span class="minutes">00</span><small>Menit</small></div>
                                <div class="countdown-item"><span class="seconds">00</span><small>Detik</small></div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="featured-event-content">
                        <span class="featured-event-category">
                            <?php echo htmlspecialchars($featured_event['category'] ?? 'General'); ?>
                        </span>
                        <h2 class="featured-event-title">
                            <?php echo htmlspecialchars($featured_event['title']); ?>
                        </h2>
                        <div class="featured-event-meta">
                            <div class="meta-item">
                                <i class="icofont-calendar"></i>
                                <span>
                                    <?php echo date('l, d F Y', strtotime($featured_event['event_date'])); ?>
                                </span>
                            </div>
                            <?php if ($featured_event['event_time']): ?>
                                <div class="meta-item">
                                    <i class="icofont-clock-time"></i>
                                    <span>
                                        <?php echo date('H:i', strtotime($featured_event['event_time'])); ?> WIB
                                    </span>
                                </div>
                            <?php endif; ?>
                            <?php if ($featured_event['location']): ?>
                                <div class="meta-item">
                                    <i class="icofont-location-pin"></i>
                                    <span>
                                        <?php echo htmlspecialchars($featured_event['location']); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($featured_event['description']): ?>
                            <p class="featured-event-desc">
                                <?php echo mb_substr(strip_tags($featured_event['description']), 0, 200); ?>...
                            </p>
                        <?php endif; ?>
                        <div class="featured-event-actions">
                            <a href="#" class="btn-event-primary"
                                onclick="openEventModal(<?php echo $featured_event['id']; ?>)">
                                <i class="icofont-eye"></i> Lihat Detail
                            </a>
                            <?php if ($featured_event['contact_whatsapp']): ?>
                                <a href="https://wa.me/<?php echo $featured_event['contact_whatsapp']; ?>?text=Halo, saya tertarik dengan acara <?php echo urlencode($featured_event['title']); ?>"
                                    target="_blank" class="btn-event-whatsapp">
                                    <i class="icofont-whatsapp"></i> Hubungi
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- ========================================
     FILTER & EVENTS GRID
======================================== -->
<section class="event-list-section">
    <div class="container">
        <!-- Filter Bar -->
        <div class="event-filter-bar">
            <div class="filter-left">
                <h3 class="filter-title">
                    <i class="icofont-listine-dots"></i>
                    Daftar Acara (
                    <?php echo mysqli_num_rows($events_query); ?> acara)
                </h3>
            </div>
            <div class="filter-right">
                <div class="filter-group">
                    <label>Status:</label>
                    <select id="filterStatus" onchange="applyFilter()">
                        <option value="upcoming" <?php echo $status_filter == 'upcoming' ? 'selected' : ''; ?>>Akan
                            Datang</option>
                        <option value="past" <?php echo $status_filter == 'past' ? 'selected' : ''; ?>>Sudah Lewat
                        </option>
                        <option value="all" <?php echo $status_filter == 'all' ? 'selected' : ''; ?>>Semua</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Kategori:</label>
                    <select id="filterCategory" onchange="applyFilter()">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category_filter == $cat ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="events-grid">
            <?php if (mysqli_num_rows($events_query) > 0): ?>
                <?php while ($event = mysqli_fetch_assoc($events_query)): ?>
                    <?php
                    $img_src = 'assets/images/placeholder_landscape.jpg';
                    if (!empty($event['image'])) {
                        if (file_exists($event['image']))
                            $img_src = $event['image'];
                        elseif (file_exists('admin/' . $event['image']))
                            $img_src = 'admin/' . $event['image'];
                    }
                    $is_past = strtotime($event['event_date']) < strtotime('today');
                    ?>
                    <div class="event-card <?php echo $is_past ? 'event-past' : ''; ?>"
                        data-event-id="<?php echo $event['id']; ?>">
                        <div class="event-card-image">
                            <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($event['title']); ?>"
                                loading="lazy">
                            <span class="event-card-category">
                                <?php echo htmlspecialchars($event['category'] ?? 'General'); ?>
                            </span>
                            <?php if ($event['is_featured']): ?>
                                <span class="event-card-featured"><i class="icofont-star"></i></span>
                            <?php endif; ?>
                            <?php if ($is_past): ?>
                                <div class="event-card-overlay-past">Sudah Selesai</div>
                            <?php endif; ?>
                        </div>
                        <div class="event-card-content">
                            <div class="event-card-date">
                                <div class="date-box">
                                    <span class="date-day">
                                        <?php echo date('d', strtotime($event['event_date'])); ?>
                                    </span>
                                    <span class="date-month">
                                        <?php echo date('M', strtotime($event['event_date'])); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="event-card-info">
                                <h4 class="event-card-title">
                                    <?php echo htmlspecialchars($event['title']); ?>
                                </h4>
                                <div class="event-card-meta">
                                    <?php if ($event['event_time']): ?>
                                        <span><i class="icofont-clock-time"></i>
                                            <?php echo date('H:i', strtotime($event['event_time'])); ?> WIB
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($event['location']): ?>
                                        <span><i class="icofont-location-pin"></i>
                                            <?php echo htmlspecialchars(mb_substr($event['location'], 0, 25)); ?>
                                            <?php echo strlen($event['location']) > 25 ? '...' : ''; ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="event-card-footer">
                                    <?php if ($event['price'] > 0): ?>
                                        <span class="event-price">Rp
                                            <?php echo number_format($event['price'], 0, ',', '.'); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="event-price free">Gratis</span>
                                    <?php endif; ?>
                                    <a href="#" class="btn-event-detail" onclick="openEventModal(<?php echo $event['id']; ?>)">
                                        Detail <i class="icofont-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="events-empty">
                    <i class="icofont-calendar"></i>
                    <h4>Belum Ada Acara</h4>
                    <p>Tidak ada acara yang ditemukan untuk filter yang dipilih.</p>
                    <a href="?page=event" class="btn-event-primary">
                        <i class="icofont-refresh"></i> Reset Filter
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ========================================
     CTA SECTION
======================================== -->
<section class="event-cta-section">
    <div class="container">
        <div class="event-cta-content">
            <div class="cta-icon"><i class="icofont-megaphone-alt"></i></div>
            <h2 class="text-white">Ingin Mengadakan Acara Bersama Kami?</h2>
            <p class="text-white">Hubungi kami untuk kolaborasi atau partnership dalam mengadakan kegiatan yang
                bermanfaat</p>
            <a href="?page=contact" class="btn-event-cta text-white">
                <i class="icofont-envelope"></i> Hubungi Kami
            </a>
        </div>
    </div>
</section>

<!-- ========================================
     EVENT DETAIL MODAL
======================================== -->
<div class="event-modal" id="eventModal" role="dialog" aria-modal="true">
    <div class="event-modal-overlay" onclick="closeEventModal()"></div>
    <div class="event-modal-container">
        <button class="event-modal-close" onclick="closeEventModal()" aria-label="Tutup">
            <i class="icofont-close-line"></i>
        </button>
        <div class="event-modal-content" id="eventModalContent">
            <div class="modal-loading">
                <div class="spinner"></div>
                <p>Memuat detail acara...</p>
            </div>
        </div>
    </div>
</div>

<!-- ========================================
     REGISTRATION MODAL
======================================== -->
<div class="event-modal" id="registrationModal" role="dialog" aria-modal="true">
    <div class="event-modal-overlay" onclick="closeRegistrationModal()"></div>
    <div class="event-modal-container">
        <button class="event-modal-close" onclick="closeRegistrationModal()" aria-label="Tutup">
            <i class="icofont-close-line"></i>
        </button>
        <div class="event-modal-content" id="registrationModalContent">
            <div class="modal-loading">
                <div class="spinner"></div>
                <p>Memuat form pendaftaran...</p>
            </div>
        </div>
    </div>
</div>

<!-- Registration Form Handler Script -->
<script src="assets/js/registration-form.js"></script>

<!-- Registration Success Styles -->
<style>
    .registration-success {
        text-align: center;
        padding: 40px 30px;
    }

    .registration-success i {
        font-size: 70px;
        color: var(--event-primary);
        margin-bottom: 20px;
    }

    .registration-success h3 {
        font-size: 24px;
        color: var(--event-secondary);
        margin-bottom: 10px;
    }

    .registration-success>p {
        color: #666;
        margin-bottom: 25px;
    }

    .success-details {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 15px;
        text-align: left;
        margin-bottom: 25px;
    }

    .success-details .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .success-details .detail-item:last-child {
        border-bottom: none;
    }

    .success-details .detail-item strong {
        color: #666;
        font-weight: 500;
    }

    .registration-code {
        background: linear-gradient(135deg, var(--event-primary), var(--event-secondary));
        color: #fff;
        padding: 5px 12px;
        border-radius: 6px;
        font-family: monospace;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .success-details .payment-info {
        margin-top: 15px;
        padding: 15px;
        background: #fff3cd;
        border-radius: 10px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .payment-info i {
        font-size: 24px;
        color: #856404;
        margin: 0;
    }

    .payment-info p {
        margin: 0;
        font-size: 14px;
        color: #856404;
    }

    .btn-close-success {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--event-primary);
        color: #fff;
        border: none;
        padding: 14px 35px;
        border-radius: 50px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-close-success:hover {
        background: var(--event-secondary);
        transform: translateY(-2px);
    }
</style>

<script>
    // Filter functionality
    function applyFilter() {
        const status = document.getElementById('filterStatus').value;
        const category = document.getElementById('filterCategory').value;
        let url = '?page=event';
        if (status) url += '&status=' + status;
        if (category) url += '&kategori=' + encodeURIComponent(category);
        window.location.href = url;
    }

    // Event Detail Modal
    function openEventModal(eventId) {
        const modal = document.getElementById('eventModal');
        const content = document.getElementById('eventModalContent');

        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        content.innerHTML = '<div class="modal-loading"><div class="spinner"></div><p>Memuat detail acara...</p></div>';

        fetch('ajax/get_event_detail.php?id=' + eventId)
            .then(response => response.text())
            .then(html => {
                content.innerHTML = html;
            })
            .catch(error => {
                content.innerHTML = '<div class="modal-error"><i class="icofont-warning"></i><p>Gagal memuat detail acara</p></div>';
            });
    }

    function closeEventModal() {
        const modal = document.getElementById('eventModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Registration Modal
    function openRegistrationModal(eventId) {
        // Close event detail modal first
        closeEventModal();

        const modal = document.getElementById('registrationModal');
        const content = document.getElementById('registrationModalContent');

        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        content.innerHTML = '<div class="modal-loading"><div class="spinner"></div><p>Memuat form pendaftaran...</p></div>';

        fetch('ajax/get_registration_form.php?id=' + eventId)
            .then(response => response.text())
            .then(html => {
                content.innerHTML = html;
            })
            .catch(error => {
                content.innerHTML = '<div class="modal-error"><i class="icofont-warning"></i><p>Gagal memuat form pendaftaran</p></div>';
            });
    }

    function closeRegistrationModal() {
        const modal = document.getElementById('registrationModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close modals with Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeEventModal();
            closeRegistrationModal();
        }
    });

    // Countdown timer for featured event
    document.addEventListener('DOMContentLoaded', function () {
        const countdownEl = document.querySelector('.featured-countdown');
        if (countdownEl) {
            const targetDate = new Date(countdownEl.dataset.date).getTime();

            setInterval(function () {
                const now = new Date().getTime();
                const distance = targetDate - now;

                if (distance > 0) {
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    countdownEl.querySelector('.days').textContent = String(days).padStart(2, '0');
                    countdownEl.querySelector('.hours').textContent = String(hours).padStart(2, '0');
                    countdownEl.querySelector('.minutes').textContent = String(minutes).padStart(2, '0');
                    countdownEl.querySelector('.seconds').textContent = String(seconds).padStart(2, '0');
                }
            }, 1000);
        }
    });
</script>