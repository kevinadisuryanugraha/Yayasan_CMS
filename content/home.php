<?php
// Load database connection
include 'admin/koneksi.php';

// Fetch active hero content
$hero_query = mysqli_query($conn, "SELECT * FROM hero_section WHERE is_active = 1 ORDER BY order_position LIMIT 1");
$hero = mysqli_fetch_assoc($hero_query);

// Set default values if no active hero found
if (!$hero) {
    $hero = [
        'title' => 'Welcome to Our Website',
        'description' => 'Content not yet configured',
        'image' => 'assets/images/banner/01.png',
        'button_text' => 'Learn More',
        'button_link' => '#'
    ];
}
?>

<!-- Banner Section start here -->
<section class="banner-section">
    <div class="container">
        <div class="row align-items-center flex-column-reverse flex-md-row">
            <div class="col-md-6">
                <div class="banner-item">
                    <div class="banner-inner">
                        <div class="banner-thumb">
                            <img src="<?php echo htmlspecialchars($hero['image']); ?>" alt="Banner-image">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="banner-item">
                    <div class="banner-inner">
                        <div class="banner-content align-middle">
                            <h1><?php echo htmlspecialchars($hero['title']); ?></h1>
                            <?php if ($hero['description']): ?>
                                <p><?php echo htmlspecialchars($hero['description']); ?></p>
                            <?php endif; ?>
                            <?php if ($hero['button_text'] && $hero['button_link']): ?>
                                <a href="<?php echo htmlspecialchars($hero['button_link']); ?>" class="lab-btn mt-3">
                                    <?php echo htmlspecialchars($hero['button_text']); ?> <i class="icofont-heart-alt"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Banner Section end here -->

<?php
// Fetch active about content
$about_query = mysqli_query($conn, "SELECT * FROM about_section WHERE is_active = 1 LIMIT 1");
$about = mysqli_fetch_assoc($about_query);

// Set default values if no active about found
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
<section class="about-section padding-tb shape-1">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-12">
                <div class="lab-item">
                    <div class="lab-inner">
                        <div class="lab-content">
                            <div class="header-title text-start m-0">
                                <?php if ($about['subtitle']): ?>
                                    <h5><?php echo htmlspecialchars($about['subtitle']); ?></h5>
                                <?php endif; ?>
                                <h2 class="mb-0"><?php echo htmlspecialchars($about['title']); ?></h2>
                            </div>
                            <?php if ($about['sub_heading']): ?>
                                <h5 class="my-4"><?php echo htmlspecialchars($about['sub_heading']); ?></h5>
                            <?php endif; ?>
                            <?php if ($about['description']): ?>
                                <p><?php echo htmlspecialchars($about['description']); ?></p>
                            <?php endif; ?>
                            <?php if ($about['button_text'] && $about['button_link']): ?>
                                <a href="<?php echo htmlspecialchars($about['button_link']); ?>" class="lab-btn mt-4">
                                    <?php echo htmlspecialchars($about['button_text']); ?> <i class="icofont-heart-alt"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="lab-item">
                    <div class="lab-inner">
                        <div class="lab-thumb">
                            <div class="img-grp">
                                <div class="about-circle-wrapper">
                                    <div class="about-circle-2"></div>
                                    <div class="about-circle"></div>
                                </div>
                                <div class="about-fg-img">
                                    <img src="<?php echo htmlspecialchars($about['image']); ?>" alt="about-image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
<section class="feature-section bg-ash padding-tb">
    <div class="container">
        <div class="row justify-content-center">
            <?php foreach ($features as $feature): ?>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="lab-item feature-item text-xs-center">
                        <div class="lab-inner">
                            <?php if ($feature['icon']): ?>
                                <div class="lab-thumb">
                                    <img src="<?php echo htmlspecialchars($feature['icon']); ?>" alt="feature-image"
                                        style="width: 120px; height: 120px; object-fit: contain; object-position: center;">
                                </div>
                            <?php endif; ?>
                            <div class="lab-content">
                                <h5>
                                    <?php echo htmlspecialchars($feature['title']); ?>
                                </h5>
                                <?php if ($feature['description']): ?>
                                    <p>
                                        <?php echo htmlspecialchars($feature['description']); ?>
                                    </p>
                                <?php endif; ?>
                                <?php if ($feature['link_text'] && $feature['link_url']): ?>
                                    <a href="<?php echo htmlspecialchars($feature['link_url']); ?>" class="text-btn">
                                        <?php echo htmlspecialchars($feature['link_text']); ?>
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
<section class="service-section padding-tb padding-b shape-2">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="header-title">
                    <h5><?php echo htmlspecialchars($service_header['subtitle'] ?? 'Islamic Center Services'); ?></h5>
                    <h2><?php echo htmlspecialchars($service_header['title'] ?? 'Ethical And Moral Beliefs That Guides To The Straight Path!'); ?>
                    </h2>
                </div>
            </div>
            <div class="col-12">
                <div class="row g-0 justify-content-center service-wrapper">
                    <?php foreach ($services as $service): ?>
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="lab-item service-item">
                                <div class="lab-inner">
                                    <?php if ($service['main_image']): ?>
                                        <div class="lab-thumb">
                                            <img src="<?php echo htmlspecialchars($service['main_image']); ?>"
                                                alt="Service-image" style="width: 100%; height: 250px; object-fit: cover;">
                                        </div>
                                    <?php endif; ?>
                                    <div class="lab-content pattern-2">
                                        <div class="lab-content-wrapper">
                                            <div class="content-top">
                                                <?php if ($service['icon']): ?>
                                                    <div class="service-top-thumb">
                                                        <img src="<?php echo htmlspecialchars($service['icon']); ?>"
                                                            alt="service-icon"
                                                            style="width: 60px; height: 60px; object-fit: contain;">
                                                    </div>
                                                <?php endif; ?>
                                                <div class="service-top-content">
                                                    <?php if ($service['category']): ?>
                                                        <span><?php echo htmlspecialchars($service['category']); ?></span>
                                                    <?php endif; ?>
                                                    <h5>
                                                        <a
                                                            href="<?php echo htmlspecialchars($service['link_url'] ?? '#'); ?>">
                                                            <?php echo htmlspecialchars($service['title']); ?>
                                                        </a>
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="content-bottom">
                                                <?php if ($service['description']): ?>
                                                    <p><?php echo htmlspecialchars($service['description']); ?></p>
                                                <?php endif; ?>
                                                <a href="<?php echo htmlspecialchars($service['link_url'] ?? '#'); ?>"
                                                    class="text-btn">Read More +</a>
                                            </div>
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
<section class="program-section padding-tb bg-img"
    style="background: url(<?php echo htmlspecialchars($campaign['background_image']); ?>) rgba(5, 21, 57, 0.7); background-blend-mode: overlay;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="header-title">
                    <h5><?php echo htmlspecialchars($campaign['subtitle']); ?></h5>
                    <h2 class="mb-4"><?php echo htmlspecialchars($campaign['title']); ?></h2>
                </div>
            </div>
            <div class="col-12">
                <div class="progress-item-wrapper text-center">
                    <div class="progress-item mb-4">
                        <div class="progress-bar-wrapper progress" data-percent="<?php echo $main_progress; ?>%">
                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                style="width: <?php echo $main_progress; ?>%;"></div>
                        </div>
                        <div class="progress-bar-percent d-flex align-items-center justify-content-center">
                            <?php echo round($main_progress); ?><sup>%</sup>
                        </div>

                        <ul class="progress-item-status lab-ul d-flex justify-content-between">
                            <li>Raised<span><?php echo format_currency($campaign['amount_raised']); ?></span></li>
                            <li>Goal<span><?php echo format_currency($campaign['goal_amount']); ?></span></li>
                        </ul>
                    </div>
                    <a href="<?php echo htmlspecialchars($campaign['button_link']); ?>" class="lab-btn">
                        <?php echo htmlspecialchars($campaign['button_text']); ?> <i class="icofont-heart-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- upcoming program -->
<div class="upcoming-programs">
    <div class="container">
        <div class="row">
            <div class="col-xl-4">
                <div class="donation-part bg-img" <?php if (isset($sidebar['background_image']) && $sidebar['background_image']): ?>
                        style="background-image: url('<?php echo htmlspecialchars($sidebar['background_image']); ?>');"
                    <?php endif; ?>>
                    <div class="donation-content">
                        <h5><?php echo htmlspecialchars($sidebar['title']); ?></h5>
                        <h2><?php echo htmlspecialchars($sidebar['headline']); ?></h2>
                        <p><?php echo htmlspecialchars($sidebar['description']); ?></p>
                        <a href="<?php echo htmlspecialchars($sidebar['button_link']); ?>" class="lab-btn">
                            <?php echo htmlspecialchars($sidebar['button_text']); ?> <i class="icofont-heart-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="programs-item-part">
                    <div class="program-desc d-flex justify-content-between">
                        <p>Support these programs and make a difference in our community</p>
                        <ul class="lab-ul">
                            <li><a href="#" class="program-next"><i class="icofont-arrow-left"></i></a></li>
                            <li><a href="#" class="program-prev"><i class="icofont-arrow-right"></i></a></li>
                        </ul>
                    </div>
                    <div class="program-item-container">
                        <div class="program-item-wrapper">
                            <div class="swiper-wrapper">
                                <?php foreach ($programs as $program): ?>
                                    <?php $prog_percent = calculate_progress($program['amount_raised'], $program['goal_amount']); ?>
                                    <div class="swiper-slide">
                                        <div class="program-item">
                                            <div class="lab-inner">
                                                <?php if ($program['image']): ?>
                                                    <div class="lab-thumb">
                                                        <a href="<?php echo htmlspecialchars($program['link_url']); ?>">
                                                            <img src="<?php echo htmlspecialchars($program['image']); ?>"
                                                                alt="program-image"
                                                                style="width: 100%; height: 250px; object-fit: cover;">
                                                        </a>
                                                        <div class="lab-thumb-content">
                                                            <div class="progress-item">
                                                                <ul
                                                                    class="progress-item-status lab-ul d-flex justify-content-between mb-2">
                                                                    <li>Raised<span><?php echo format_currency($program['amount_raised']); ?></span>
                                                                    </li>
                                                                    <li>Goal<span><?php echo format_currency($program['goal_amount']); ?></span>
                                                                    </li>
                                                                </ul>
                                                                <div class="progress-bar-wrapper progress"
                                                                    data-percent="<?php echo $prog_percent; ?>%">
                                                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                                        style="width: <?php echo $prog_percent; ?>%;"></div>
                                                                </div>
                                                                <div
                                                                    class="progress-bar-percent d-flex align-items-center justify-content-center">
                                                                    <?php echo round($prog_percent); ?> <sup>%</sup>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="lab-content">
                                                    <?php if ($program['category']): ?>
                                                        <span><?php echo htmlspecialchars($program['category']); ?></span>
                                                    <?php endif; ?>
                                                    <h5>
                                                        <a href="<?php echo htmlspecialchars($program['link_url']); ?>">
                                                            <?php echo htmlspecialchars($program['title']); ?>
                                                        </a>
                                                    </h5>
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

// Fetch quote section settings (background image) - with error handling
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

<!-- Qoute Section start Here -->
<div class="qoute-section padding-tb">
    <div class="qoute-section-wrapper" <?php echo $quote_bg_style; ?>>
        <div class="qoute-overlay"></div>
        <div class="container">
            <div class="qoute-container">
                <div class="swiper-wrapper">
                    <?php foreach ($quotes_data as $quote_item): ?>
                        <div class="swiper-slide">
                            <div class="lab-item qoute-item">
                                <div class="lab-inner d-flex align-items-center">
                                    <div class="lab-thumb">
                                        <span>Quote From Prophet</span>
                                        <i class="icofont-quote-left"></i>
                                    </div>
                                    <div class="lab-content">
                                        <blockquote class="blockquote">
                                            <p><?php echo htmlspecialchars($quote_item['author']); ?> Said
                                                <span>"<?php echo htmlspecialchars($quote_item['quote_text']); ?>"</span>
                                            </p>
                                            <?php if ($quote_item['source']): ?>
                                                <footer class="blockquote-footer bg-transparent">
                                                    <?php echo htmlspecialchars($quote_item['source']); ?>
                                                </footer>
                                            <?php endif; ?>
                                        </blockquote>
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
<!-- Qoute Section end Here -->

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
                                                <li>
                                                    <span class="days">0</span>
                                                    <div class="count-text">Days</div>
                                                </li>
                                                <li>
                                                    <span class="hours">0</span>
                                                    <div class="count-text">Hours</div>
                                                </li>
                                                <li>
                                                    <span class="minutes">0</span>
                                                    <div class="count-text">Mins</div>
                                                </li>
                                                <li>
                                                    <span class="seconds">0</span>
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