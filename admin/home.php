<?php
session_start();
ob_start();
include 'koneksi.php';

if (empty($_SESSION['ID_USER'])) {
    header("Location: index.php?access=failed");
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Annex - Responsive Bootstrap 4 Admin Dashboard</title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="Mannatthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- CSS -->
    <?php include 'inc/css.php'; ?>
    <!-- End CSS -->

</head>


<body class="fixed-left">

    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- ========== Left Sidebar Start ========== -->
        <?php include 'inc/sidebar.php'; ?>
        <!-- Left Sidebar End -->

        <!-- Start right Content here -->

        <div class="content-page">

            <div class="content">

                <!-- Top Bar Start -->
                <?php include 'inc/header.php'; ?>
                <!-- Top Bar End -->

                <div class="page-content-wrapper ">
                    <!-- Start content -->
                    <?php
                    if (isset($_GET['page'])) {
                        if (file_exists('content/' . $_GET['page'] . '.php')) {
                            include 'content/' . $_GET['page'] . '.php';
                        } else {
                            include 'content/404.php';
                        }
                    } else {
                        include 'content/dashboard.php';
                    }
                    ?>
                    <!-- End content -->
                </div>
            </div>

            <!-- Footer -->
            <?php include 'inc/footer.php'; ?>
            <!-- End Footer -->
        </div>
        <!-- End Right content here -->

    </div>
    <!-- END wrapper -->

    <!-- JS -->
    <?php include 'inc/js.php'; ?>
    <!-- End JS -->

</body>

</html>