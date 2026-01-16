<?php
include 'admin/koneksi.php';

$hero_query = mysqli_query($conn, "SELECT * FROM hero_section WHERE is_active = 1 ORDER BY order_position LIMIT 1");
$hero = mysqli_fetch_assoc($hero_query);

if (!$hero) {
    $hero = [
        'title' => 'Welcome to Our Website',
        'description' => 'Content not yet configured',
        'image' => 'assets/images/banner/01.png',
        'button_text' => 'Learn More',
        'button_link' => '#'
    ];
}

// Fetch Appearance Settings
$appearance_query = mysqli_query($conn, "SELECT * FROM appearance_settings LIMIT 1");
$appearance = mysqli_fetch_assoc($appearance_query);

// Default Fallback Values
$cms_primary = $appearance['primary_color'] ?? '#00997d';
$cms_secondary = $appearance['secondary_color'] ?? '#0a294a';
$cms_accent = $appearance['accent_color'] ?? '#fab702';
$cms_font = $appearance['font_family'] ?? 'Inter, sans-serif';
$btn_style = $appearance['button_style'] ?? 'pill';

// Determine Border Radius based on button style
$btn_radius = '50px'; // Default to pill
if ($btn_style == 'rounded') {
    $btn_radius = '8px';
} elseif ($btn_style == 'square') {
    $btn_radius = '0px';
}
?>

<!-- Home Page External Stylesheet -->
<link rel="stylesheet" href="assets/css/home.css">

<!-- Dynamic CSS Variables (from CMS settings) -->
<style>
    :root {
        --cms-primary:
            <?php echo $cms_primary; ?>
        ;
        --cms-secondary:
            <?php echo $cms_secondary; ?>
        ;
        --cms-accent:
            <?php echo $cms_accent; ?>
        ;
        --cms-font: '<?php echo $cms_font; ?>', sans-serif;
        --cms-btn-radius:
            <?php echo $btn_radius; ?>
        ;
        --hero-primary: var(--cms-primary);
        --hero-bg-dark: var(--cms-secondary);
        --hero-accent: var(--cms-accent);
    }
</style>

<!-- Hero Banner Section - World Class Design -->
<section class="hero-banner-premium" aria-label="Banner utama Yayasan Indonesia Bijak Bestari">
    <div class="hero-floating-shapes" aria-hidden="true">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
    </div>

    <div class="container hero-container">
        <div class="hero-row">
            <div class="hero-content">
                <div class="hero-badge" aria-hidden="true">
                    <i class="icofont-star" aria-hidden="true"></i>
                    <span>Yayasan Indonesia Bijak Bestari</span>
                </div>
                <h1 class="hero-title">
                    <?php
                    $title_words = explode(' ', $hero['title']);
                    $total_words = count($title_words);
                    $highlight_start = max(0, $total_words - 2);
                    for ($i = 0; $i < $total_words; $i++) {
                        if ($i == $highlight_start)
                            echo '<span>';
                        echo htmlspecialchars($title_words[$i]) . ' ';
                        if ($i == $total_words - 1 && $i >= $highlight_start)
                            echo '</span>';
                    }
                    ?>
                </h1>
                <?php if ($hero['description']): ?>
                    <p class="hero-description"><?php echo htmlspecialchars($hero['description']); ?></p>
                <?php endif; ?>
                <div class="hero-actions" role="group" aria-label="Tombol navigasi utama">
                    <?php if ($hero['button_text'] && $hero['button_link']): ?>
                        <a href="<?php echo htmlspecialchars($hero['button_link']); ?>" class="btn-hero-primary"
                            aria-label="<?php echo htmlspecialchars($hero['button_text']); ?>">
                            <?php echo htmlspecialchars($hero['button_text']); ?> <i class="icofont-arrow-right"
                                aria-hidden="true"></i>
                        </a>
                    <?php endif; ?>
                    <a href="about.php" class="btn-hero-outline" aria-label="Pelajari lebih lanjut tentang kami">Tentang
                        Kami</a>
                </div>
            </div>

            <div class="hero-visual">
                <div class="image-wrapper">
                    <img src="<?php echo htmlspecialchars($hero['image']); ?>"
                        alt="Ilustrasi kegiatan Yayasan Indonesia Bijak Bestari" class="main-hero-img" loading="lazy">
                    <div class="stats-card" role="complementary" aria-label="Statistik anggota komunitas">
                        <div class="stats-icon-box" aria-hidden="true"><i class="icofont-users-alt-5"></i></div>
                        <div class="stats-info">
                            <h4>1000+</h4><span>Anggota Komunitas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$about_query = mysqli_query($conn, "SELECT * FROM about_section WHERE is_active = 1 LIMIT 1");
$about = mysqli_fetch_assoc($about_query);

if (!$about) {
    $about = [
        'subtitle' => 'About Our History',
        'title' => 'Islamic Center For Muslims To Achieve Spiritual Goals',
        'sub_heading' => 'Our Promise To Uphold The Trust Placed.',
        'description' => 'Content not yet configured',
        'image' => 'assets/images/about/02.png',
        'button_text' => 'Ask About Islam',
        'button_link' => '#'
    ];
}
?>

<!-- About section start here -->
<section class="about-premium-section" aria-label="Tentang Yayasan">
    <div class="container about-container">
        <div class="about-row">
            <div class="about-image-col">
                <div class="about-image-wrapper">
                    <img src="<?php echo htmlspecialchars($about['image']); ?>" alt="Foto kegiatan yayasan"
                        class="about-image-main" loading="lazy">
                    <div class="about-image-decorative" aria-hidden="true"></div>
                    <div class="about-shape-1" aria-hidden="true"></div>
                </div>
            </div>

            <div class="about-content-col">
                <?php if ($about['subtitle']): ?>
                    <span class="about-subtitle"><?php echo htmlspecialchars($about['subtitle']); ?></span>
                <?php endif; ?>

                <h2 class="about-title"><?php echo htmlspecialchars($about['title']); ?></h2>

                <?php if ($about['sub_heading']): ?>
                    <h5 class="about-heading-secondary"><?php echo htmlspecialchars($about['sub_heading']); ?></h5>
                <?php endif; ?>

                <?php if ($about['description']): ?>
                    <p class="about-desc"><?php echo htmlspecialchars($about['description']); ?></p>
                <?php endif; ?>

                <?php if ($about['button_text'] && $about['button_link']): ?>
                    <div class="hero-actions">
                        <a href="<?php echo htmlspecialchars($about['button_link']); ?>" class="btn-hero-primary">
                            <?php echo htmlspecialchars($about['button_text']); ?> <i class="icofont-arrow-right"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<!-- About section end here -->

<?php
// Fetch active feature cards
$features_query = mysqli_query($conn, "SELECT * FROM feature_section WHERE is_active = 1 ORDER BY order_position ASC");
$features = mysqli_fetch_all($features_query, MYSQLI_ASSOC);
?>

<!-- Feature Section Start Here -->
<section class="feature-premium-section">
    <div class="container feature-container">
        <div class="row justify-content-center">
            <?php foreach ($features as $feature): ?>
                <div class="col-lg-3 col-sm-6 col-12 mb-4">
                    <div class="feature-premium-card">
                        <div class="feature-inner">
                            <?php if ($feature['icon']): ?>
                                <div class="feature-icon-wrapper">
                                    <img src="<?php echo htmlspecialchars($feature['icon']); ?>" alt="feature-image">
                                </div>
                            <?php endif; ?>
                            <div class="feature-content">
                                <h5 class="feature-title-premium"><?php echo htmlspecialchars($feature['title']); ?></h5>
                                <?php if ($feature['description']): ?>
                                    <p class="feature-desc-premium"><?php echo htmlspecialchars($feature['description']); ?></p>
                                <?php endif; ?>
                                <?php if ($feature['link_text'] && $feature['link_url']): ?>
                                    <a href="<?php echo htmlspecialchars($feature['link_url']); ?>" class="feature-btn-premium">
                                        <?php echo htmlspecialchars($feature['link_text']); ?> <i
                                            class="icofont-long-arrow-right"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- Feature Section End Here -->

<?php
// Fetch service header (section title & subtitle)
$header_query = mysqli_query($conn, "SELECT * FROM service_header LIMIT 1");
$service_header = mysqli_fetch_assoc($header_query);

// Fetch active service cards
$services_query = mysqli_query($conn, "SELECT * FROM service_section WHERE is_active = 1 ORDER BY order_position ASC");
$services = mysqli_fetch_all($services_query, MYSQLI_ASSOC);
?>

<!-- Service section start here -->
<section class="service-premium-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="service-premium-header">
                    <span
                        class="service-premium-subtitle"><?php echo htmlspecialchars($service_header['subtitle'] ?? 'Islamic Center Services'); ?></span>
                    <h2 class="service-premium-title">
                        <?php echo htmlspecialchars($service_header['title'] ?? 'Ethical And Moral Beliefs That Guides To The Straight Path!'); ?>
                    </h2>
                </div>
            </div>

            <div class="col-12">
                <div class="row g-4 justify-content-center">
                    <?php foreach ($services as $service): ?>
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="service-premium-card">
                                <?php if ($service['main_image']): ?>
                                    <div class="service-img-wrapper">
                                        <img src="<?php echo htmlspecialchars($service['main_image']); ?>" alt="Service Image"
                                            class="service-img-main">
                                        <?php if ($service['icon']): ?>
                                            <div class="service-icon-floating">
                                                <img src="<?php echo htmlspecialchars($service['icon']); ?>" alt="Icon">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="service-card-content">
                                    <?php if ($service['category']): ?>
                                        <span
                                            class="service-category"><?php echo htmlspecialchars($service['category']); ?></span>
                                    <?php endif; ?>

                                    <h4 class="service-card-title">
                                        <a href="<?php echo htmlspecialchars($service['link_url'] ?? '#'); ?>">
                                            <?php echo htmlspecialchars($service['title']); ?>
                                        </a>
                                    </h4>

                                    <?php if ($service['description']): ?>
                                        <p class="service-card-desc"><?php echo htmlspecialchars($service['description']); ?>
                                        </p>
                                    <?php endif; ?>

                                    <a href="<?php echo htmlspecialchars($service['link_url'] ?? '#'); ?>"
                                        class="service-read-more">
                                        Read More <i class="icofont-long-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Service section end here -->

<?php
// Include helper functions
include 'admin/inc/campaign_helpers.php';
// Fetch main campaign
$campaign_query = mysqli_query($conn, "SELECT * FROM campaign_main WHERE is_active = 1 LIMIT 1");
$campaign = mysqli_fetch_assoc($campaign_query);
$main_progress = calculate_progress($campaign['amount_raised'], $campaign['goal_amount']);
// Fetch sidebar
$sidebar_query = mysqli_query($conn, "SELECT * FROM campaign_sidebar LIMIT 1");
$sidebar = mysqli_fetch_assoc($sidebar_query);
// Fetch programs for slider
$programs_query = mysqli_query($conn, "SELECT * FROM campaign_programs WHERE is_active = 1 ORDER BY order_position ASC");
$programs = mysqli_fetch_all($programs_query, MYSQLI_ASSOC);
?>

<!-- Program section start Here -->
<section class="program-premium-section">
    <div class="container">
        <div class="program-glass-card">
            <?php if ($campaign['subtitle']): ?>
                <span class="program-subtitle"><?php echo htmlspecialchars($campaign['subtitle']); ?></span>
            <?php endif; ?>

            <h2 class="program-title"><?php echo htmlspecialchars($campaign['title']); ?></h2>

            <div class="program-content-wrapper">
                <div class="premium-progress-wrapper" title="<?php echo round($main_progress); ?>%">
                    <div class="premium-progress-bar" style="width: <?php echo $main_progress; ?>%;">
                        <span class="progress-percent-label"><?php echo round($main_progress); ?>%</span>
                    </div>
                </div>

                <div class="program-stats">
                    <div class="stat-item raised">
                        Raised <span
                            class="stat-value"><?php echo format_currency($campaign['amount_raised']); ?></span>
                    </div>
                    <div class="stat-item goal">
                        Goal <span class="stat-value"><?php echo format_currency($campaign['goal_amount']); ?></span>
                    </div>
                </div>

                <?php if ($campaign['button_text'] && $campaign['button_link']): ?>
                    <div class="hero-actions" style="justify-content: center;"> <!-- Centered for program section -->
                        <a href="<?php echo htmlspecialchars($campaign['button_link']); ?>" class="btn-hero-primary">
                            <?php echo htmlspecialchars($campaign['button_text']); ?> <i class="icofont-arrow-right"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<!-- Program section end Here -->

<!-- upcoming program -->
<div class="upcoming-premium-section">
    <div class="container">
        <div class="row">
            <!-- Sidebar: Donation CTA -->
            <div class="col-xl-4 mb-4 mb-xl-0">
                <div class="donation-premium-card" <?php if (isset($sidebar['background_image']) && $sidebar['background_image']): ?>
                        style="background-image: url('<?php echo htmlspecialchars($sidebar['background_image']); ?>');"
                    <?php endif; ?>>

                    <div class="donation-content-premium">
                        <span class="donation-subtitle"><?php echo htmlspecialchars($sidebar['title']); ?></span>
                        <h2 class="donation-title-premium"><?php echo htmlspecialchars($sidebar['headline']); ?></h2>
                        <p class="donation-desc-premium"><?php echo htmlspecialchars($sidebar['description']); ?></p>
                        <div class="hero-actions" style="justify-content: flex-start;">
                            <a href="<?php echo htmlspecialchars($sidebar['button_link']); ?>" class="btn-hero-primary">
                                <?php echo htmlspecialchars($sidebar['button_text']); ?> <i
                                    class="icofont-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slider: Programs -->
            <div class="col-xl-8">
                <div class="program-header-controls">
                    <p class="program-header-text">Support these programs and make a difference.</p>
                    <div class="program-nav">
                        <div class="program-nav-btn program-prev"><i class="icofont-arrow-left"></i></div>
                        <div class="program-nav-btn program-next"><i class="icofont-arrow-right"></i></div>
                    </div>
                </div>

                <div class="program-item-container">
                    <div class="program-item-wrapper swiper-container">
                        <div class="swiper-wrapper">
                            <?php foreach ($programs as $program): ?>
                                <?php $prog_percent = calculate_progress($program['amount_raised'], $program['goal_amount']); ?>
                                <div class="swiper-slide">
                                    <div class="program-premium-card">
                                        <?php if ($program['image']): ?>
                                            <div class="program-img-wrapper">
                                                <a href="<?php echo htmlspecialchars($program['link_url']); ?>">
                                                    <img src="<?php echo htmlspecialchars($program['image']); ?>"
                                                        alt="program-image" class="program-img-main">
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <div class="program-content-premium">
                                            <?php if ($program['category']): ?>
                                                <span
                                                    class="program-category-badge"><?php echo htmlspecialchars($program['category']); ?></span>
                                            <?php endif; ?>

                                            <h5 class="program-title-card">
                                                <a href="<?php echo htmlspecialchars($program['link_url']); ?>">
                                                    <?php echo htmlspecialchars($program['title']); ?>
                                                </a>
                                            </h5>

                                            <div class="program-footer-stats">
                                                <ul class="program-stat-row list-unstyled">
                                                    <li>Raised: <span
                                                            class="stat-val"><?php echo format_currency($program['amount_raised']); ?></span>
                                                    </li>
                                                    <li>Goal: <span
                                                            class="stat-val"><?php echo format_currency($program['goal_amount']); ?></span>
                                                    </li>
                                                </ul>

                                                <div class="progress-bar-wrapper progress"
                                                    data-percent="<?php echo $prog_percent; ?>%">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                        style="width: <?php echo $prog_percent; ?>%;"></div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <span
                                                        style="font-size: 13px; font-weight: 600; color: #777;"><?php echo round($prog_percent); ?>%
                                                        Reached</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Program section end Here -->

<?php
// Fetch faith section header
$faith_header_query = mysqli_query($conn, "SELECT * FROM faith_header WHERE id = 1");
$faith_header = mysqli_fetch_assoc($faith_header_query);

// Fetch active pillars ordered by position
$pillars_query = mysqli_query($conn, "SELECT * FROM faith_pillars WHERE is_active = 1 ORDER BY order_position ASC");
$pillars = mysqli_fetch_all($pillars_query, MYSQLI_ASSOC);
?>

<!-- Faith section start here -->
<section class="faith-section padding-tb shape-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="header-title">
                    <h5><?php echo htmlspecialchars($faith_header['subtitle']); ?></h5>
                    <h2><?php echo htmlspecialchars($faith_header['title']); ?></h2>
                </div>
            </div>
            <div class="col-12">
                <div class="faith-content">
                    <div class="tab-content" id="pills-tabContent">
                        <?php foreach ($pillars as $index => $pillar): ?>
                            <div class="tab-pane fade <?php echo $index == 0 ? 'show active' : ''; ?>"
                                id="pillar-<?php echo $pillar['id']; ?>" role="tabpanel"
                                aria-labelledby="pillar-tab-<?php echo $pillar['id']; ?>">
                                <div class="lab-item faith-item tri-shape-1 pattern-2">
                                    <div class="lab-inner d-flex align-items-center">
                                        <?php if ($pillar['main_image']): ?>
                                            <div class="lab-thumb">
                                                <img src="<?php echo htmlspecialchars($pillar['main_image']); ?>"
                                                    alt="<?php echo htmlspecialchars($pillar['pillar_name']); ?>"
                                                    style="width: 100%; max-width: 500px; height: 350px; object-fit: contain;">
                                            </div>
                                        <?php endif; ?>
                                        <div class="lab-content">
                                            <h4><?php echo htmlspecialchars($pillar['pillar_name']); ?>
                                                <?php if ($pillar['subtitle']): ?>
                                                    <span>(<?php echo htmlspecialchars($pillar['subtitle']); ?>)</span>
                                                <?php endif; ?>
                                            </h4>
                                            <p><?php echo htmlspecialchars($pillar['description']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <ul class="nav nav-pills mb-3 align-items-center justify-content-center" id="pills-tab"
                        role="tablist">
                        <?php foreach ($pillars as $index => $pillar): ?>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link <?php echo $index == 0 ? 'active' : ''; ?>"
                                    id="pillar-tab-<?php echo $pillar['id']; ?>" data-bs-toggle="pill"
                                    href="#pillar-<?php echo $pillar['id']; ?>" role="tab"
                                    aria-controls="pillar-<?php echo $pillar['id']; ?>"
                                    aria-selected="<?php echo $index == 0 ? 'true' : 'false'; ?>">
                                    <?php if ($pillar['tab_icon']): ?>
                                        <img src="<?php echo htmlspecialchars($pillar['tab_icon']); ?>"
                                            alt="<?php echo htmlspecialchars($pillar['pillar_name']); ?> icon"
                                            style="width: 80px; height: 80px; object-fit: contain;">
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Faith section end here -->

<?php
// Fetch active quotes for slider
$quotes_query = mysqli_query($conn, "SELECT * FROM quotes WHERE is_active = 1 ORDER BY order_position ASC");
$quotes_data = mysqli_fetch_all($quotes_query, MYSQLI_ASSOC);

// Fetch quote section settings
$quote_bg_style = '';
$quote_settings_query = @mysqli_query($conn, "SELECT * FROM quote_settings WHERE id = 1");
if ($quote_settings_query) {
    $quote_settings = mysqli_fetch_assoc($quote_settings_query);
    if ($quote_settings && !empty($quote_settings['background_image'])) {
        $bg_url = htmlspecialchars($quote_settings['background_image']);
        $quote_bg_style = "style=\"background-image: url('$bg_url'); background-size: cover; background-position: center;\"";
    }
}
?>

<!-- Quote Premium Section Start -->
<div class="quote-premium-section"><!-- Parallax Background -->
    <div class="quote-bg-parallax" <?php echo $quote_bg_style; ?>></div>
    <div class="quote-premium-overlay"></div>

    <div class="container">
        <div class="quote-content-wrapper">
            <div class="quote-premium-slider swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($quotes_data as $quote_item): ?>
                        <div class="swiper-slide">
                            <div class="quote-glass-card">
                                <div class="quote-icon-premium">
                                    <i class="icofont-quote-left"></i>
                                </div>

                                <blockquote class="quote-text-premium">
                                    "<?php echo htmlspecialchars($quote_item['quote_text']); ?>"
                                </blockquote>

                                <div class="quote-decoration-line"></div>

                                <div class="quote-author-premium">
                                    <?php echo htmlspecialchars($quote_item['author']); ?>
                                </div>

                                <?php if ($quote_item['source']): ?>
                                    <div class="quote-source-premium">
                                        <?php echo htmlspecialchars($quote_item['source']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Pagination -->
                <div class="swiper-pagination mt-5"></div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var quoteSwiper = new Swiper('.quote-premium-slider', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.quote-premium-slider .swiper-pagination',
                clickable: true,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            speed: 1000
        });
    });
</script>
<!-- Quote Premium Section End -->

<?php
// Fetch events section header
$events_header_query = @mysqli_query($conn, "SELECT * FROM events_header WHERE id = 1");
$events_header = $events_header_query ? mysqli_fetch_assoc($events_header_query) : null;
if (!$events_header) {
    $events_header = ['subtitle' => 'Upcoming Events', 'title' => 'Join Our Community Events'];
}

// Fetch featured event
$featured_query = @mysqli_query($conn, "SELECT * FROM events WHERE is_featured = 1 AND is_active = 1 ORDER BY event_date ASC LIMIT 1");
$featured_event = $featured_query ? mysqli_fetch_assoc($featured_query) : null;

// Fetch regular events (non-featured, limit 3)
$events_query = @mysqli_query($conn, "SELECT * FROM events WHERE is_featured = 0 AND is_active = 1 ORDER BY order_position ASC, event_date ASC LIMIT 3");
$regular_events = $events_query ? mysqli_fetch_all($events_query, MYSQLI_ASSOC) : [];
?>

<!-- Events Section start here -->
<section class="event-section padding-tb padding-b shape-4">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="header-title">
                    <h5><?php echo htmlspecialchars($events_header['subtitle']); ?></h5>
                    <h2><?php echo htmlspecialchars($events_header['title']); ?></h2>
                </div>
            </div>
            <div class="col-12">
                <div class="event-content">
                    <?php if ($featured_event): ?>
                        <div class="event-top tri-shape-2 pattern-2">
                            <div class="event-top-thumb">
                                <img src="<?php echo htmlspecialchars($featured_event['image']); ?>"
                                    alt="<?php echo htmlspecialchars($featured_event['title']); ?>">
                            </div>
                            <div class="event-top-content">
                                <div class="event-top-content-wrapper">
                                    <h3><a href="#"><?php echo htmlspecialchars($featured_event['title']); ?></a></h3>
                                    <div class="date-count-wrapper">
                                        <ul class="lab-ul event-date">
                                            <li><i class="icofont-calendar"></i>
                                                <span><?php echo date('F d, Y', strtotime($featured_event['event_date'])); ?></span>
                                            </li>
                                            <?php if ($featured_event['location']): ?>
                                                <li><i class="icofont-location-pin"></i>
                                                    <span><?php echo htmlspecialchars($featured_event['location']); ?></span>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                        <?php if ($featured_event['countdown_enabled'] && $featured_event['countdown_date']): ?>
                                            <ul class="lab-ul event-count"
                                                data-date="<?php echo date('F d, Y H:i:s', strtotime($featured_event['countdown_date'])); ?>">
                                                <li><span class="days">0</span>
                                                    <div class="count-text">Days</div>
                                                </li>
                                                <li><span class="hours">0</span>
                                                    <div class="count-text">Hours</div>
                                                </li>
                                                <li><span class="minutes">0</span>
                                                    <div class="count-text">Mins</div>
                                                </li>
                                                <li><span class="seconds">0</span>
                                                    <div class="count-text">Secs</div>
                                                </li>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($regular_events)): ?>
                        <div class="event-bottom">
                            <div class="row justify-content-center">
                                <?php foreach ($regular_events as $event): ?>
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <div class="event-item lab-item">
                                            <div class="lab-inner">
                                                <?php if ($event['image']): ?>
                                                    <div class="lab-thumb">
                                                        <img src="<?php echo htmlspecialchars($event['image']); ?>"
                                                            alt="<?php echo htmlspecialchars($event['title']); ?>"
                                                            style="width: 100%; height: 200px; object-fit: cover;">
                                                    </div>
                                                <?php endif; ?>
                                                <div class="lab-content">
                                                    <h5><a href="#"><?php echo htmlspecialchars($event['title']); ?></a></h5>
                                                    <ul class="lab-ul event-date">
                                                        <li><i class="icofont-calendar"></i>
                                                            <span><?php echo date('F d, Y', strtotime($event['event_date'])); ?></span>
                                                        </li>
                                                        <?php if ($event['location']): ?>
                                                            <li><i class="icofont-location-pin"></i>
                                                                <span><?php echo htmlspecialchars($event['location']); ?></span>
                                                            </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Events Section end here -->