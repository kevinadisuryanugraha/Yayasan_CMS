<?php
// Load database connection first
include 'admin/koneksi.php';

// Load site settings for header, meta, etc.
include 'inc/site_settings.php';

// Load appearance settings for theming
include 'inc/appearance_settings.php';

// Dynamic SEO Meta Tags based on current page
$current_page = $_GET['page'] ?? 'home';
$site_name = $site_settings['site_name'] ?? 'Yayasan Indonesia Bijak Bestari';
$default_desc = $site_settings['site_description'] ?? 'Yayasan pendidikan dan sosial yang berkomitmen membangun generasi Indonesia yang berilmu dan berakhlak mulia.';

// Page-specific SEO settings
$page_seo = [
    'home' => [
        'title' => $site_name . ' - ' . ($site_settings['site_tagline'] ?? 'Bersama Sucikan Hati'),
        'description' => $default_desc,
        'keywords' => 'yayasan, pendidikan, sosial, indonesia, beasiswa, pembinaan karakter'
    ],
    'about' => [
        'title' => 'Tentang Kami - ' . $site_name,
        'description' => 'Kenali lebih dekat ' . $site_name . '. Visi, misi, sejarah, dan tim pengurus yang berdedikasi membangun generasi Indonesia yang berilmu dan berakhlak mulia.',
        'keywords' => 'tentang yayasan, visi misi, sejarah yayasan, profil yayasan, pengurus yayasan'
    ],
    'contact' => [
        'title' => 'Hubungi Kami - ' . $site_name,
        'description' => 'Hubungi ' . $site_name . ' untuk informasi lebih lanjut tentang program pendidikan, donasi, dan kerjasama.',
        'keywords' => 'kontak yayasan, alamat yayasan, hubungi kami, telepon, email'
    ],
    'donate' => [
        'title' => 'Donasi - ' . $site_name,
        'description' => 'Berdonasi untuk mendukung program pendidikan dan sosial ' . $site_name . '. Setiap kontribusi Anda sangat berarti.',
        'keywords' => 'donasi, sedekah, zakat, wakaf, bantuan sosial'
    ],
    'programs' => [
        'title' => 'Program Kami - ' . $site_name,
        'description' => 'Jelajahi berbagai program pendidikan, pembinaan karakter, dan kegiatan sosial dari ' . $site_name . '.',
        'keywords' => 'program yayasan, beasiswa, pendidikan, pembinaan, kegiatan sosial'
    ],
    'events' => [
        'title' => 'Kegiatan & Acara - ' . $site_name,
        'description' => 'Informasi kegiatan dan acara terbaru dari ' . $site_name . '. Ikuti berbagai program menarik kami.',
        'keywords' => 'kegiatan yayasan, acara, event, jadwal, pengajian'
    ]
];

// Get current page SEO or use defaults
$seo = $page_seo[$current_page] ?? [
    'title' => ucfirst($current_page) . ' - ' . $site_name,
    'description' => $default_desc,
    'keywords' => 'yayasan, pendidikan, sosial'
];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title><?php echo htmlspecialchars($seo['title']); ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($seo['description']); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($seo['keywords']); ?>">
    <meta name="author" content="<?php echo htmlspecialchars($site_name); ?>">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo htmlspecialchars($seo['title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($seo['description']); ?>">
    <meta property="og:site_name" content="<?php echo htmlspecialchars($site_name); ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($seo['title']); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($seo['description']); ?>">


    <!-- favicon -->
    <?php if (!empty($site_settings['favicon'])): ?>
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo htmlspecialchars($site_settings['favicon']); ?>">
    <?php else: ?>
        <link rel="shortcut icon" type="image/x-icon" href="assets/images/x-icon/01.png">
    <?php endif; ?>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="<?php echo $google_fonts_url; ?>" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/icofont.min.css">
    <link rel="stylesheet" href="assets/css/lightcase.css">
    <link rel="stylesheet" href="assets/css/swiper.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Dynamic Appearance Styles -->
    <?php echo getAppearanceCss($appearance_settings, $btn_radius, $font_family); ?>
</head>

<body>

    <!-- preloader start here -->
    <div class="preloader">
        <div class="preloader-inner">
            <div class="preloader-icon">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- preloader ending here -->

    <!-- Header Section -->
    <?php include 'inc/header.php'; ?><?php
       // Main Content - No whitespace between header and content to avoid gaps
       if (isset($_GET['page'])) {
           if (file_exists('content/' . $_GET['page'] . '.php')) {
               include 'content/' . $_GET['page'] . '.php';
           } else {
               include 'content/404.php';
           }
       } else {
           include 'content/home.php';
       }
       ?>

    <!-- Footer Section start here -->
    <?php include 'inc/footer.php'; ?>
    <!-- Footer Section end here -->

    <!-- scrollToTop start here -->
    <a href="#" class="scrollToTop"><i class="icofont-bubble-up"></i><span class="pluse_1"></span><span
            class="pluse_2"></span></a>
    <!-- scrollToTop ending here -->


    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/fontawesome.min.js"></script>
    <script src="assets/js/waypoints.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/swiper.min.js"></script>
    <script src="assets/js/circularProgressBar.min.js"></script>
    <script src="assets/js/isotope.pkgd.min.js"></script>
    <script src="assets/js/lightcase.js"></script>
    <script src="assets/js/functions.js"></script>
</body>

</html>