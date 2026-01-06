<?php
session_start();
include 'koneksi.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $terms = isset($_POST['terms']) ? $_POST['terms'] : '';

    // Validasi input
    if (empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        $error = 'Semua field harus diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } elseif (strlen($username) < 3) {
        $error = 'Username minimal 3 karakter!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } elseif ($password !== $confirm_password) {
        $error = 'Password dan konfirmasi password tidak cocok!';
    } elseif (empty($terms)) {
        $error = 'Anda harus menyetujui syarat dan ketentuan!';
    } else {
        // Cek apakah email sudah terdaftar
        $check_email = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
        if (mysqli_num_rows($check_email) > 0) {
            $error = 'Email sudah terdaftar!';
        } else {
            // Cek apakah username sudah terdaftar
            $check_username = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
            if (mysqli_num_rows($check_username) > 0) {
                $error = 'Username sudah digunakan!';
            } else {
                // Hash password untuk keamanan
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert data ke database
                $insert = mysqli_query($conn, "INSERT INTO users (username, email, password, created_at) 
                                               VALUES ('$username', '$email', '$hashed_password', NOW())");

                if ($insert) {
                    $success = 'Registrasi berhasil! Silakan login.';
                    // Redirect ke halaman login setelah 2 detik
                    header("refresh:2;url=index.php");
                } else {
                    $error = 'Registrasi gagal! Silakan coba lagi.';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Register - Admin Dashboard</title>
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
                    <a href="index.php" class="logo logo-admin"><img src="assets/images/logo.png" height="24"
                            alt="logo"></a>
                </h3>

                <div class="p-3">
                    <form class="form-horizontal" method="POST" action="">

                        <div class="form-group row">
                            <div class="col-12">
                                <input class="form-control" type="email" name="email" required="" placeholder="Email"
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12">
                                <input class="form-control" type="text" name="username" required=""
                                    placeholder="Username"
                                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12">
                                <input class="form-control" type="password" name="password" required=""
                                    placeholder="Password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12">
                                <input class="form-control" type="password" name="confirm_password" required=""
                                    placeholder="Konfirmasi Password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="terms" id="customCheck1"
                                        required="">
                                    <label class="custom-control-label font-weight-normal" for="customCheck1">Saya
                                        menyetujui
                                        <a href="#" class="text-muted">Syarat dan Ketentuan</a></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center row m-t-20">
                            <div class="col-12">
                                <button class="btn btn-danger btn-block waves-effect waves-light"
                                    type="submit">Daftar</button>
                            </div>
                        </div>

                        <div class="form-group m-t-10 mb-0 row">
                            <div class="col-12 m-t-20 text-center">
                                <a href="index.php" class="text-muted">Sudah punya akun?</a>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <!-- SweetAlert Notifications -->
    <?php if (!empty($error)): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?php echo $error; ?>',
            });
        </script>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?php echo $success; ?>',
                timer: 2000,
                showConfirmButton: false
            });
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