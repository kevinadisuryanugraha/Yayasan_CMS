<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

    if (mysqli_num_rows($query) == 1) {
        $row = mysqli_fetch_assoc($query);
        if (password_verify($password, $row['password'])) {
            $_SESSION['ID_USER'] = $row['id'];
            $_SESSION['NAME'] = $row['username'];
            $_SESSION['EMAIL'] = $row['email'];
            header('Location: home.php');
            exit;
        } else {
            header("location:index.php?error=password");
            exit;
        }
    } else {
        header("location:index.php?error=email");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Yayasan CMS Admin</title>
    <meta name="description" content="Admin Dashboard Login">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            position: relative;
            overflow-x: hidden;
            padding: 20px;
        }

        /* Animated Background */
        .bg-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .bg-shapes .shape {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, rgba(120, 80, 255, 0.15), rgba(255, 120, 200, 0.1));
            animation: float 20s infinite ease-in-out;
        }

        .bg-shapes .shape:nth-child(1) {
            width: 600px;
            height: 600px;
            top: -200px;
            right: -200px;
            animation-delay: 0s;
        }

        .bg-shapes .shape:nth-child(2) {
            width: 400px;
            height: 400px;
            bottom: -150px;
            left: -150px;
            animation-delay: -5s;
        }

        .bg-shapes .shape:nth-child(3) {
            width: 300px;
            height: 300px;
            bottom: 20%;
            right: 10%;
            animation-delay: -10s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg) scale(1);
            }

            25% {
                transform: translateY(-30px) rotate(5deg) scale(1.05);
            }

            50% {
                transform: translateY(0) rotate(0deg) scale(1);
            }

            75% {
                transform: translateY(30px) rotate(-5deg) scale(0.95);
            }
        }

        /* Floating Particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: rise 8s infinite ease-in;
        }

        @keyframes rise {
            0% {
                opacity: 0;
                transform: translateY(100vh) scale(0);
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                opacity: 0;
                transform: translateY(-100vh) scale(1);
            }
        }

        /* Main Container - Two Column Layout */
        .main-container {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: stretch;
            max-width: 1100px;
            width: 100%;
            gap: 0;
            animation: containerAppear 0.8s ease-out;
        }

        @keyframes containerAppear {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Left Panel - Information/Guide */
        .info-panel {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 24px 0 0 24px;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
            min-height: 650px;
        }

        .info-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        .info-content {
            position: relative;
            z-index: 1;
        }

        .info-header {
            margin-bottom: 40px;
        }

        .info-header .welcome-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            color: white;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
        }

        .info-header .welcome-badge i {
            font-size: 14px;
        }

        .info-header h2 {
            font-size: 32px;
            font-weight: 800;
            color: white;
            line-height: 1.3;
            margin-bottom: 16px;
        }

        .info-header p {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.7;
        }

        /* Guide Steps */
        .guide-steps {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 40px;
        }

        .guide-step {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.3s ease;
        }

        .guide-step:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }

        .step-number {
            width: 40px;
            height: 40px;
            min-width: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 700;
            color: white;
        }

        .step-content h4 {
            font-size: 16px;
            font-weight: 600;
            color: white;
            margin-bottom: 6px;
        }

        .step-content p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.75);
            line-height: 1.5;
        }

        /* Info Footer */
        .info-footer {
            display: flex;
            align-items: center;
            gap: 16px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .info-footer .security-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
        }

        .info-footer .security-badge i {
            font-size: 18px;
            color: #4ade80;
        }

        /* Right Panel - Login Form */
        .login-panel {
            flex: 1;
            max-width: 480px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 0 24px 24px 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-left: none;
            padding: 50px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Logo Section */
        .logo-section {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
            animation: pulse 3s infinite ease-in-out;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
            }

            50% {
                box-shadow: 0 18px 40px rgba(102, 126, 234, 0.6);
            }
        }

        .logo-icon i {
            font-size: 32px;
            color: white;
        }

        .logo-section h1 {
            font-size: 26px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 6px;
            letter-spacing: -0.3px;
        }

        .logo-section p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Form Styling */
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        .form-group {
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 10px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            font-size: 17px;
            transition: color 0.3s ease;
        }

        .form-input {
            width: 100%;
            padding: 15px 18px 15px 50px;
            font-size: 15px;
            font-family: inherit;
            font-weight: 500;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.35);
            font-weight: 400;
        }

        .form-input:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(102, 126, 234, 0.6);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        }

        .form-input:focus+.input-icon,
        .input-wrapper:hover .input-icon {
            color: rgba(102, 126, 234, 1);
        }

        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            cursor: pointer;
            font-size: 17px;
            transition: color 0.3s ease;
            background: none;
            border: none;
            padding: 5px;
        }

        .password-toggle:hover {
            color: rgba(255, 255, 255, 0.8);
        }

        /* Remember & Forgot */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: -6px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            display: none;
        }

        .custom-checkbox {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .custom-checkbox i {
            font-size: 11px;
            color: transparent;
            transition: color 0.3s ease;
        }

        .remember-me input:checked+.custom-checkbox {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
        }

        .remember-me input:checked+.custom-checkbox i {
            color: white;
        }

        .remember-me span {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
        }

        .forgot-link {
            font-size: 14px;
            color: rgba(102, 126, 234, 1);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: rgba(138, 158, 255, 1);
        }

        /* Submit Button */
        .btn-login {
            width: 100%;
            padding: 16px 24px;
            font-size: 16px;
            font-weight: 600;
            font-family: inherit;
            color: #ffffff;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 14px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.35);
            margin-top: 6px;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.5);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login .btn-text {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-login i {
            font-size: 17px;
        }

        /* Register Link */
        .register-section {
            text-align: center;
            margin-top: 28px;
            padding-top: 28px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .register-section p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
        }

        .register-section a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            margin-left: 5px;
            transition: color 0.3s ease;
        }

        .register-section a:hover {
            color: #8a9eff;
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 24px;
        }

        .login-footer p {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.4);
        }

        /* Loading State */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-login.loading .btn-text {
            visibility: hidden;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 24px;
            height: 24px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive Design */
        @media (max-width: 900px) {
            .main-container {
                flex-direction: column;
                max-width: 500px;
            }

            .info-panel {
                border-radius: 24px 24px 0 0;
                min-height: auto;
                padding: 40px 30px;
            }

            .login-panel {
                border-radius: 0 0 24px 24px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-top: none;
                max-width: 100%;
            }

            .info-header h2 {
                font-size: 26px;
            }

            .guide-steps {
                gap: 14px;
            }

            .guide-step {
                padding: 16px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 15px;
            }

            .info-panel {
                padding: 30px 24px;
            }

            .login-panel {
                padding: 35px 24px;
            }

            .info-header h2 {
                font-size: 22px;
            }

            .logo-section h1 {
                font-size: 22px;
            }

            .form-input {
                padding: 14px 16px 14px 46px;
            }

            .btn-login {
                padding: 15px 20px;
            }

            .form-options {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }

            .step-number {
                width: 36px;
                height: 36px;
                min-width: 36px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <!-- Animated Background Shapes -->
    <div class="bg-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <!-- Floating Particles -->
    <div class="particles" id="particles"></div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Left Panel - Information/Guide -->
        <div class="info-panel">
            <div class="info-content">
                <!-- Header -->
                <div class="info-header">
                    <div class="welcome-badge">
                        <i class="fas fa-hand-sparkles"></i>
                        Selamat Datang!
                    </div>
                    <h2>Akses Panel Admin Yayasan CMS</h2>
                    <p>Kelola konten website yayasan Anda dengan mudah melalui panel admin yang modern dan intuitif.</p>
                </div>

                <!-- Guide Steps -->
                <div class="guide-steps">
                    <div class="guide-step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h4>Masukkan Email</h4>
                            <p>Gunakan email yang terdaftar sebagai administrator sistem.</p>
                        </div>
                    </div>
                    <div class="guide-step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h4>Masukkan Password</h4>
                            <p>Ketik password akun Anda dengan benar. Klik ikon mata untuk melihat password.</p>
                        </div>
                    </div>
                    <div class="guide-step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h4>Klik Sign In</h4>
                            <p>Tekan tombol Sign In untuk masuk ke dashboard admin.</p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="info-footer">
                    <div class="security-badge">
                        <i class="fas fa-shield-halved"></i>
                        <span>Koneksi Anda aman dan terenkripsi</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Login Form -->
        <div class="login-panel">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-mosque"></i>
                </div>
                <h1>Yayasan CMS</h1>
                <p>Admin Panel Login</p>
            </div>

            <!-- Login Form -->
            <form class="login-form" action="" method="POST" id="loginForm">
                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" class="form-input" placeholder="Masukkan email Anda"
                            required autofocus>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" class="form-input"
                            placeholder="Masukkan password Anda" required>
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword()" title="Lihat Password">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember & Forgot -->
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        <span class="custom-checkbox"><i class="fas fa-check"></i></span>
                        <span>Ingat saya</span>
                    </label>
                    <a href="#" class="forgot-link">Lupa password?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-login" id="btnLogin">
                    <span class="btn-text">
                        <i class="fas fa-sign-in-alt"></i>
                        Sign In
                    </span>
                </button>
            </form>

            <!-- Register Link -->
            <div class="register-section">
                <p>Belum punya akun?<a href="register.php">Daftar Sekarang</a></p>
            </div>

            <!-- Footer -->
            <div class="login-footer">
                <p>&copy; 2026 Yayasan CMS. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Create floating particles
        function createParticles() {
            const container = document.getElementById('particles');
            const particleCount = 30;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 8 + 's';
                particle.style.animationDuration = (Math.random() * 4 + 6) + 's';
                container.appendChild(particle);
            }
        }
        createParticles();

        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form submit with loading state
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            const btn = document.getElementById('btnLogin');
            btn.classList.add('loading');
        });

        // Input animations
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function () {
                this.parentElement.classList.add('focused');
            });
            input.addEventListener('blur', function () {
                this.parentElement.classList.remove('focused');
            });
        });
    </script>

    <?php if (isset($_GET['error'])): ?>
        <script>
            <?php if ($_GET['error'] == 'password'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal',
                    text: 'Password yang Anda masukkan salah!',
                    background: 'rgba(30, 30, 50, 0.95)',
                    color: '#ffffff',
                    confirmButtonColor: '#667eea',
                    backdrop: 'rgba(0, 0, 0, 0.6)'
                });
            <?php elseif ($_GET['error'] == 'email'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal',
                    text: 'Email tidak ditemukan dalam sistem!',
                    background: 'rgba(30, 30, 50, 0.95)',
                    color: '#ffffff',
                    confirmButtonColor: '#667eea',
                    backdrop: 'rgba(0, 0, 0, 0.6)'
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>

</body>

</html>