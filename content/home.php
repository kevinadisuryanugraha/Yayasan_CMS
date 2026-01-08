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

<!-- Dynamic Appearance Styles -->
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

        /* Map to existing variables if needed, or use directly */
        --hero-primary: var(--cms-primary);
        --hero-bg-dark: var(--cms-secondary);
        --hero-accent: var(--cms-accent);
    }

    body {
        font-family: var(--cms-font) !important;
    }

    /* Global Button Styles - Standardized */
    .btn-hero-primary {
        padding: 15px 35px;
        background: var(--hero-primary);
        color: #fff;
        font-weight: 600;
        border-radius: var(--cms-btn-radius);
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border: none;
    }

    .btn-hero-primary:hover {
        background: #145e42;
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        color: #fff;
    }

    .btn-hero-outline {
        padding: 15px 35px;
        background: transparent;
        color: #fff;
        border: 2px solid rgba(255, 255, 255, 0.3);
        font-weight: 600;
        border-radius: var(--cms-btn-radius);
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-hero-outline:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: #fff;
        color: #fff;
        transform: translateY(-3px);
    }
</style>

<!-- Hero Banner Section - World Class Design -->
<section class="hero-banner-premium">
    <style>
        .hero-banner-premium {
            --hero-bg-gradient: linear-gradient(135deg, var(--cms-secondary) 0%, #16213e 100%);
            --hero-accent: var(--cms-accent);
            --hero-primary: var(--cms-primary);
            position: relative;
            min-height: 500px;
            display: flex;
            align-items: center;
            overflow: hidden;
            background: var(--hero-bg-gradient);
            padding: 80px 0;
            color: #ffffff;
        }

        .hero-banner-premium::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: radial-gradient(circle at 10% 20%, rgba(255, 255, 255, 0.03) 0%, transparent 20%), radial-gradient(circle at 90% 80%, rgba(255, 255, 255, 0.03) 0%, transparent 20%);
            z-index: 1;
            pointer-events: none;
        }

        .hero-floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
            pointer-events: none;
        }

        .floating-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.6;
            animation: float-gentle 10s ease-in-out infinite;
        }



        @keyframes float-gentle {

            0%,
            100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(20px, -20px);
            }
        }

        .hero-container {
            position: relative;
            z-index: 10;
        }

        .hero-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 50px;
            flex-wrap: wrap;
        }

        .hero-content {
            flex: 1;
            min-width: 300px;
            max-width: 650px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 25px;
            backdrop-filter: blur(5px);
            color: #ffffff;
        }

        .hero-badge i {
            color: var(--hero-accent);
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 3.8rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
            text-transform: capitalize;
            color: #ffffff !important;
        }

        .hero-title span {
            color: var(--hero-primary);
            position: relative;
            z-index: 1;
            display: inline-block;
        }

        .hero-title span::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 0;
            width: 100%;
            height: 12px;
            background: rgba(255, 255, 255, 0.1);
            z-index: -1;
            transform: skewX(-15deg);
        }

        .hero-description {
            font-size: 1.1rem;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 35px;
            max-width: 90%;
        }

        .hero-actions {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        /* Buttons moved to global scope */

        .hero-visual {
            flex: 1;
            min-width: 300px;
            position: relative;
            display: flex;
            justify-content: center;
        }

        .image-wrapper {
            position: relative;
            border-radius: 30px;
            width: 100%;
            max-width: 550px;
        }

        .main-hero-img {
            width: 100%;
            height: auto;
            border-radius: 30px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 5;
            transition: transform 0.5s ease;
        }

        .image-wrapper:hover .main-hero-img {
            transform: scale(1.02);
        }

        .hero-visual::after {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100%;
            height: 100%;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            z-index: 1;
            transform: translate(15px, 15px);
        }

        .stats-card {
            position: absolute;
            bottom: 40px;
            left: -20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 25px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 15px;
            max-width: 280px;
            animation: bounceIn 4s infinite;
        }

        .stats-icon-box {
            width: 50px;
            height: 50px;
            background: var(--hero-accent);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 24px;
        }

        .stats-info h4 {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin: 0;
            line-height: 1;
        }

        .stats-info span {
            font-size: 13px;
            color: #555;
            margin-top: 5px;
            display: block;
            font-weight: 500;
        }

        @keyframes bounceIn {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @media (max-width: 991px) {
            .hero-banner-premium {
                padding: 60px 0;
                min-height: auto;
                text-align: center;
            }

            .hero-row {
                flex-direction: column-reverse;
                gap: 40px;
            }

            .hero-actions {
                justify-content: center;
            }

            .hero-description {
                margin-left: auto;
                margin-right: auto;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .stats-card {
                left: 50%;
                transform: translateX(-50%);
                bottom: -25px;
                width: 90%;
            }

            .hero-visual::after {
                display: none;
            }
        }
    </style>

    <div class="hero-floating-shapes">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
    </div>

    <div class="container hero-container">
        <div class="hero-row">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="icofont-star"></i>
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
                <div class="hero-actions">
                    <?php if ($hero['button_text'] && $hero['button_link']): ?>
                        <a href="<?php echo htmlspecialchars($hero['button_link']); ?>" class="btn-hero-primary">
                            <?php echo htmlspecialchars($hero['button_text']); ?> <i class="icofont-arrow-right"></i>
                        </a>
                    <?php endif; ?>
                    <a href="about.php" class="btn-hero-outline">Tentang Kami</a>
                </div>
            </div>

            <div class="hero-visual">
                <div class="image-wrapper">
                    <img src="<?php echo htmlspecialchars($hero['image']); ?>" alt="Banner Image" class="main-hero-img">
                    <div class="stats-card">
                        <div class="stats-icon-box"><i class="icofont-users-alt-5"></i></div>
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
<section class="about-premium-section">
    <style>
        .about-premium-section {
            position: relative;
            padding: 120px 0;
            background: #ffffff;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }

        /* Decorative Background Pattern */
        .about-premium-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 30%;
            height: 100%;
            background: #f9fbfd;
            z-index: 1;
            clip-path: polygon(20% 0%, 100% 0, 100% 100%, 0% 100%);
        }

        .about-container {
            position: relative;
            z-index: 2;
        }

        .about-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 60px;
        }

        /* Content Column */
        .about-content-col {
            flex: 1;
            max-width: 600px;
        }

        .about-subtitle {
            display: inline-block;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--hero-primary);
            margin-bottom: 20px;
            background: rgba(0, 153, 125, 0.1);
            padding: 8px 16px;
            border-radius: 4px;
        }

        .about-title {
            font-size: 36px;
            font-weight: 800;
            color: #0a294a;
            line-height: 1.3;
            margin-bottom: 30px;
            position: relative;
        }

        .about-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--hero-accent);
            margin-top: 15px;
            border-radius: 2px;
        }

        .about-heading-secondary {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            font-style: italic;
            border-left: 3px solid #0a294a;
            padding-left: 20px;
        }

        .about-desc {
            font-size: 16px;
            line-height: 1.8;
            color: #555;
            margin-bottom: 40px;
        }

        /* Image Column */
        .about-image-col {
            flex: 1;
            position: relative;
        }

        .about-image-wrapper {
            position: relative;
            z-index: 5;
            border-radius: 20px;
        }

        .about-image-main {
            width: 100%;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 2;
        }

        .about-image-decorative {
            position: absolute;
            top: -30px;
            right: -30px;
            width: 100%;
            height: 100%;
            border: 2px solid var(--hero-accent, #fab702);
            border-radius: 20px;
            z-index: 1;
            opacity: 0.5;
        }

        .about-shape-1 {
            position: absolute;
            bottom: -40px;
            left: -40px;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, var(--hero-primary, #00997d) 0%, transparent 70%);
            opacity: 0.1;
            z-index: 0;
        }

        @media (max-width: 991px) {
            .about-premium-section::before {
                width: 100%;
                height: 30%;
                bottom: 0;
                top: auto;
                clip-path: none;
            }

            .about-row {
                flex-direction: column;
                gap: 40px;
            }

            .about-content-col {
                text-align: center;
            }

            .about-title::after {
                margin: 15px auto 0;
            }

            .about-heading-secondary {
                border-left: none;
                border-bottom: 2px solid #0a294a;
                padding-bottom: 10px;
                padding-left: 0;
                display: inline-block;
            }

            .about-image-decorative {
                top: 20px;
                right: -20px;
            }
        }
    </style>

    <div class="container about-container">
        <div class="about-row">
            <div class="about-image-col">
                <div class="about-image-wrapper">
                    <img src="<?php echo htmlspecialchars($about['image']); ?>" alt="About Image"
                        class="about-image-main">
                    <div class="about-image-decorative"></div>
                    <div class="about-shape-1"></div>
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
    <style>
        .feature-premium-section {
            padding: 100px 0;
            background: #f8faff;
            position: relative;
        }

        .feature-premium-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(#e6eff8 1px, transparent 1px);
            background-size: 20px 20px;
            opacity: 0.5;
            z-index: 1;
        }

        .feature-container {
            position: relative;
            z-index: 2;
        }

        .feature-premium-card {
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            height: 100%;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .feature-premium-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 153, 125, 0.15);
            border-color: rgba(0, 153, 125, 0.2);
        }

        .feature-premium-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--hero-primary, #00997d);
            transform: scaleX(0);
            transition: transform 0.4s ease;
            transform-origin: left;
        }

        .feature-premium-card:hover::after {
            transform: scaleX(1);
        }

        .feature-icon-wrapper {
            width: 90px;
            height: 90px;
            margin: 0 auto 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(0, 153, 125, 0.05) 0%, rgba(10, 41, 74, 0.05) 100%);
            border-radius: 50%;
            transition: all 0.4s ease;
        }

        .feature-premium-card:hover .feature-icon-wrapper {
            background: var(--hero-primary, #00997d);
            transform: rotateY(180deg);
        }

        .feature-icon-wrapper img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            transition: all 0.4s ease;
        }

        .feature-premium-card:hover .feature-icon-wrapper img {
            filter: brightness(0) invert(1);
            transform: rotateY(180deg);
        }

        .feature-title-premium {
            font-size: 22px;
            font-weight: 700;
            color: var(--hero-bg-dark);
            margin-bottom: 15px;
            font-family: 'Inter', sans-serif;
        }

        .feature-desc-premium {
            color: #666;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 25px;
            font-family: sans-serif;
        }

        .feature-btn-premium {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--hero-primary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .feature-btn-premium:hover {
            color: #0a294a;
            letter-spacing: 2px;
        }

        .feature-btn-premium i {
            transition: transform 0.3s ease;
        }

        .feature-btn-premium:hover i {
            transform: translateX(5px);
        }

        @media (max-width: 991px) {
            .feature-premium-section {
                padding: 60px 0;
            }

            .feature-premium-card {
                margin-bottom: 20px;
            }
        }
    </style>

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
    <style>
        .service-premium-section {
            padding: 120px 0;
            background: #ffffff;
            font-family: 'Inter', sans-serif;
            position: relative;
        }

        .service-premium-header {
            text-align: center;
            margin-bottom: 60px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .service-premium-subtitle {
            color: var(--hero-primary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 14px;
            display: block;
            margin-bottom: 10px;
        }

        .service-premium-title {
            font-size: 36px;
            font-weight: 800;
            color: var(--hero-bg-dark);
            line-height: 1.3;
        }

        .service-premium-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(0, 0, 0, 0.03);
        }

        .service-premium-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
        }

        .service-img-wrapper {
            position: relative;
            height: 240px;
            overflow: hidden;
        }

        .service-img-main {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .service-premium-card:hover .service-img-main {
            transform: scale(1.1);
        }

        .service-icon-floating {
            position: absolute;
            bottom: -30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            z-index: 2;
        }

        .service-icon-floating img {
            width: 35px;
            height: 35px;
            object-fit: contain;
        }

        .service-card-content {
            padding: 40px 30px 30px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .service-category {
            font-size: 13px;
            font-weight: 600;
            color: #777;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .service-card-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--hero-bg-dark);
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .service-card-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .service-card-title a:hover {
            color: var(--hero-primary);
        }

        .service-card-desc {
            font-size: 15px;
            color: #555;
            line-height: 1.7;
            margin-bottom: 25px;
            flex-grow: 1;
        }

        .service-read-more {
            font-weight: 600;
            color: var(--hero-primary);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: gap 0.3s ease;
        }

        .service-read-more:hover {
            gap: 10px;
            color: var(--hero-bg-dark);
        }
    </style>

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
    <style>
        .program-premium-section {
            padding: 140px 0;
            background-image: url(<?php echo htmlspecialchars($campaign['background_image']); ?>);
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
            z-index: 1;
        }

        .program-premium-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(10, 41, 74, 0.9) 0%, rgba(5, 21, 57, 0.8) 100%);
            z-index: -1;
        }

        .program-glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 60px;
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        }

        .program-subtitle {
            color: var(--hero-accent);
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 20px;
            display: inline-block;
        }

        .program-title {
            color: #ffffff;
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 50px;
            line-height: 1.3;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            font-family: 'Inter', sans-serif;
        }

        /* Progress Bar Styles */
        .premium-progress-wrapper {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50px;
            height: 40px;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .premium-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #ffc107, #ff9800);
            border-radius: 50px;
            position: relative;
            transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 15px rgba(255, 152, 0, 0.5);
        }

        /* Animated stripes */
        .premium-progress-bar::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background-image: linear-gradient(-45deg,
                    rgba(255, 255, 255, .2) 25%,
                    transparent 25%,
                    transparent 50%,
                    rgba(255, 255, 255, .2) 50%,
                    rgba(255, 255, 255, .2) 75%,
                    transparent 75%,
                    transparent);
            z-index: 1;
            background-size: 50px 50px;
            animation: move 2s linear infinite;
            border-radius: 50px;
            overflow: hidden;
        }

        @keyframes move {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 50px 50px;
            }
        }

        .progress-percent-label {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #0a294a;
            font-weight: 800;
            font-size: 14px;
            z-index: 2;
        }

        .program-stats {
            display: flex;
            justify-content: space-between;
            color: #ffffff;
            margin-bottom: 40px;
            font-size: 18px;
            font-weight: 500;
        }

        .stat-value {
            font-weight: 800;
            font-size: 24px;
            margin-left: 10px;
            color: var(--hero-accent);
            font-family: monospace;
            /* Monospaced for number alignment */
        }



        @media (max-width: 768px) {
            .program-glass-card {
                padding: 30px;
            }

            .program-title {
                font-size: 28px;
            }

            .program-stats {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .stat-value {
                display: block;
                margin-left: 0;
                margin-top: 5px;
            }
        }
    </style>

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
    <style>
        .upcoming-premium-section {
            padding: 100px 0;
            background: #f9fbfc;
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }

        /* Sidebar Styling */
        .donation-premium-card {
            background-size: cover;
            background-position: center;
            border-radius: 20px;
            padding: 40px;
            color: #fff;
            position: relative;
            overflow: hidden;
            height: 100%;
            min-height: 480px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .donation-premium-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(10, 41, 74, 0.1) 0%, rgba(10, 41, 74, 0.9) 100%);
            z-index: 1;
        }

        .donation-content-premium {
            position: relative;
            z-index: 2;
        }

        .donation-subtitle {
            color: var(--hero-accent);
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
            display: block;
        }

        .donation-title-premium {
            font-size: 32px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
            color: #fff;
        }

        .donation-desc-premium {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        /* Slider Controls Header */
        .program-header-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 0 15px;
        }

        .program-header-text {
            font-size: 16px;
            color: #555;
            font-style: italic;
        }

        .program-nav-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 2px solid #e1e1e1;
            background: #fff;
            color: #0a294a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-left: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .program-nav-btn:hover {
            background: var(--hero-primary);
            border-color: var(--hero-primary);
            color: #fff;
        }

        /* Program Card Styling */
        .program-premium-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            transition: all 0.4s ease;
            height: 520px;
            /* Fixed height as requested */
            border: 1px solid rgba(0, 0, 0, 0.03);
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }

        .program-premium-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .program-img-wrapper {
            position: relative;
            height: 220px;
            flex-shrink: 0;
            /* Prevent image from shrinking */
            overflow: hidden;
        }

        .program-img-main {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .program-premium-card:hover .program-img-main {
            transform: scale(1.1);
        }

        .program-content-premium {
            padding: 25px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            /* Occupy remaining space */
        }

        .program-category-badge {
            font-size: 12px;
            font-weight: 700;
            color: var(--hero-primary);
            background: rgba(0, 153, 125, 0.1);
            padding: 5px 12px;
            border-radius: 30px;
            display: inline-block;
            margin-bottom: 12px;
            align-self: flex-start;
        }

        .program-title-card {
            font-size: 18px;
            font-weight: 700;
            color: #0a294a;
            margin-bottom: 15px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* Limit to 2 lines */
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .program-title-card a {
            color: inherit;
            text-decoration: none;
            transition: color 0.3s;
        }

        .program-title-card a:hover {
            color: var(--hero-primary);
        }

        .program-footer-stats {
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: auto;
            /* Push to bottom */
        }

        .program-stat-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
        }

        .stat-val {
            color: #0a294a;
            font-weight: 700;
        }

        .program-btn-premium {
            display: inline-block;
            background: #fff;
            color: var(--hero-bg-dark);
            padding: 12px 30px;
            border-radius: var(--cms-btn-radius);
            font-weight: 700;
            text-decoration: none;
            margin-top: 10px;
            transition: all 0.3s;
        }

        .program-btn-premium:hover {
            background: var(--hero-accent);
            color: var(--hero-bg-dark);
        }
    </style>

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
<div class="quote-premium-section">
    <style>
        .quote-premium-section {
            position: relative;
            padding: 120px 0;
            overflow: hidden;
        }

        .quote-bg-parallax {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            z-index: 0;
        }

        .quote-premium-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(10, 41, 74, 0.95), rgba(0, 153, 125, 0.85));
            z-index: 1;
        }

        .quote-content-wrapper {
            position: relative;
            z-index: 2;
        }

        .quote-premium-slider {
            overflow: hidden;
            padding: 20px;
        }

        .quote-glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            padding: 60px 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
            position: relative;
        }

        .quote-icon-premium {
            font-size: 60px;
            color: var(--hero-accent);
            margin-bottom: 40px;
            opacity: 1;
            display: inline-block;
            filter: drop-shadow(0 5px 15px rgba(250, 183, 2, 0.3));
        }

        .quote-text-premium {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 32px;
            line-height: 1.6;
            color: #fff;
            font-style: italic;
            margin-bottom: 40px;
        }

        .quote-text-premium span {
            display: block;
            margin-top: 15px;
        }

        .quote-author-premium {
            font-size: 20px;
            font-weight: 700;
            color: var(--hero-accent);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }

        .quote-source-premium {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 400;
            font-style: italic;
        }

        .quote-decoration-line {
            width: 80px;
            height: 3px;
            background: var(--hero-accent);
            margin: 30px auto;
            border-radius: 2px;
        }

        /* Swiper Pagination Customization if needed */
        .swiper-pagination-bullet {
            background: #fff;
            opacity: 0.5;
        }

        .swiper-pagination-bullet-active {
            background: var(--hero-accent);
            opacity: 1;
        }

        @media (max-width: 768px) {
            .quote-text-premium {
                font-size: 24px;
            }

            .quote-glass-card {
                padding: 40px 20px;
            }
        }
    </style>

    <!-- Parallax Background -->
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