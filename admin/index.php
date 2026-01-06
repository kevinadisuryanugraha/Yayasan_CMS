<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

    if (mysqli_num_rows($query) == 1) {
        $row = mysqli_fetch_assoc($query);
        // Verifikasi password yang di-hash
        if (password_verify($password, $row['password'])) {
            $_SESSION['ID_USER'] = $row['id'];
            $_SESSION['NAME'] = $row['username'];
            $_SESSION['EMAIL'] = $row['email'];
            header('Location: home.php');
        } else {
            header("location:index.php?error=password");
        }
    } else {
        header("location:index.php?error=email");
    }
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

    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

</head>

<body class="fixed-left">

    <!-- Begin page -->
    <div class="accountbg"></div>
    <div class="wrapper-page">

        <div class="card">
            <div class="card-body">

                <h3 class="text-center mt-0 m-b-15">
                    <a href="index.html" class="logo logo-admin"><img src="assets/images/logo.png" height="24"
                            alt="logo"></a>
                </h3>

                <div class="p-3">
                    <form class="form-horizontal m-t-20" action="" method="POST">

                        <div class="form-group row">
                            <div class="col-12">
                                <input class="form-control" name="email" type="email" placeholder="Email" required
                                    autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12">
                                <input class="form-control" name="password" type="password" placeholder="Password"
                                    required>
                            </div>
                        </div>

                        <div class="form-group row m-t-20">
                            <div class="col-12">
                                <button class="btn btn-danger btn-block waves-effect waves-light" name="submit"
                                    type="submit">Log
                                    In</button>
                            </div>
                        </div>

                        <div class="form-group m-t-10 mb-0 row">
                            <div class="col-sm-7 m-t-20">
                                <a href="" class="text-muted"><i class="mdi mdi-lock"></i>
                                    <small>Forgot your password ?</small></a>
                            </div>
                            <div class="col-sm-5 m-t-20">
                                <a href="register.php" class="text-muted"><i class="mdi mdi-account-circle"></i>
                                    <small>Create an account ?</small></a>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (isset($_GET['error'])): ?>
        <script>
            <?php if ($_GET['error'] == 'password'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Password salah!',
                });
            <?php elseif ($_GET['error'] == 'email'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Email tidak ditemukan!',
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>


    <!-- jQuery  -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/modernizr.min.js"></script>
    <script src="assets/js/detect.js"></script>
    <script src="assets/js/fastclick.js"></script>
    <script src="assets/js/jquery.slimscroll.js"></script>
    <script src="assets/js/jquery.blockUI.js"></script>
    <script src="assets/js/waves.js"></script>
    <script src="assets/js/jquery.nicescroll.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

</body>

</html>