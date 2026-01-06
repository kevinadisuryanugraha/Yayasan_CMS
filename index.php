<?php
// Load database connection first
include 'admin/koneksi.php';

// Load site settings for header, meta, etc.
include 'inc/site_settings.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo htmlspecialchars($site_settings['site_name'] ?? 'Hafsa'); ?> -
        <?php echo htmlspecialchars($site_settings['site_tagline'] ?? 'Islamic Center'); ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($site_settings['site_description'] ?? ''); ?>">

    <!-- favicon -->
    <?php if (!empty($site_settings['favicon'])): ?>
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo htmlspecialchars($site_settings['favicon']); ?>">
    <?php else: ?>
        <link rel="shortcut icon" type="image/x-icon" href="assets/images/x-icon/01.png">
    <?php endif; ?>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/icofont.min.css">
    <link rel="stylesheet" href="assets/css/lightcase.css">
    <link rel="stylesheet" href="assets/css/swiper.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
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

    <!-- Header Section Starts Here -->
    <?php include 'inc/header.php'; ?>
    <!-- Header Section Ends Here-->

    <!-- Main Content -->
    <?php
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
    <!-- end Main Content -->

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