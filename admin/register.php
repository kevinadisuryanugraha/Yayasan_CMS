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
<htmllang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Yayasan CMS Admin</title>
    <meta name="description" content="Create Admin Account">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
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
            background: linear-gradient(45deg, rgba(16, 185, 129, 0.15), rgba(59, 130, 246, 0.1));
            animation: float 20s infinite ease-in-out;
        }

        .bg-shapes .shape:nth-child(1) {
            width: 600px;
            height: 600px;
            top: -200px;
            left: -200px;
            animation-delay: 0s;
        }

        .bg-shapes .shape:nth-child(2) {
            width: 400px;
            height: 400px;
            bottom: -150px;
            right: -150px;
            animation-delay: -5s;
        }

        .bg-shapes .shape:nth-child(3) {
            width: 300px;
            height: 300px;
            top: 30%;
            right: 5%;
            animation-delay: -10s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg) scale(1); }
            25% { transform: translateY(-30px) rotate(5deg) scale(1.05); }
            50% { transform: translateY(0) rotate(0deg) scale(1); }
            75% { transform: translateY(30px) rotate(-5deg) scale(0.95); }
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
            0% { opacity: 0; transform: translateY(100vh) scale(0); }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { opacity: 0; transform: translateY(-100vh) scale(1); }
        }

        /* Main Container */
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
            0% { opacity: 0; transform: translateY(30px) scale(0.95); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Left Panel - Information */
        .info-panel {
            flex: 1;
            background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
            border-radius: 24px 0 0 24px;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
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
            margin-bottom: 35px;
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

        .info-header h2 {
            font-size: 30px;
            font-weight: 800;
            color: white;
            line-height: 1.3;
            margin-bottom: 16px;
        }

        .info-header p {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.7;
        }

        /* Benefits List */
        .benefits-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 35px;
        }

        .benefit-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.3s ease;
        }

        .benefit-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }

        .benefit-icon {
            width: 44px;
            height: 44px;
            min-width: 44px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
        }

        .benefit-content h4 {
            font-size: 15px;
            font-weight: 600;
            color: white;
            margin-bottom: 4px;
        }

        .benefit-content p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.75);
            line-height: 1.4;
        }

        /* Requirements */
        .requirements {
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .requirements h4 {
            font-size: 14px;
            font-weight: 600;
            color: white;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .requirements ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .requirements li {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .requirements li i {
            color: #4ade80;
            font-size: 12px;
        }

        /* Right Panel - Form */
        .register-panel {
            flex: 1;
            max-width: 480px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 0 24px 24px 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-left: none;
            padding: 45px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Logo Section */
        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 12px 30px rgba(16, 185, 129, 0.4);
            animation: pulse 3s infinite ease-in-out;
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 12px 30px rgba(16, 185, 129, 0.4); }
            50% { box-shadow: 0 18px 40px rgba(16, 185, 129, 0.6); }
        }

        .logo-icon i {
            font-size: 28px;
            color: white;
        }

        .logo-section h1 {
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 6px;
        }

        .logo-section p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Form Styling */
        .register-form {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .form-group {
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 8px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 46px;
            font-size: 14px;
            font-family: inherit;
            font-weight: 500;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.35);
            font-weight: 400;
        }

        .form-input:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(16, 185, 129, 0.6);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
        }

        .form-input:focus + .input-icon,
        .input-wrapper:hover .input-icon {
            color: rgba(16, 185, 129, 1);
        }

        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            cursor: pointer;
            font-size: 16px;
            transition: color 0.3s ease;
            background: none;
            border: none;
            padding: 5px;
        }

        .password-toggle:hover {
            color: rgba(255, 255, 255, 0.8);
        }

        /* Row Two Columns */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        /* Terms Checkbox */
        .terms-group {
            margin-top: 4px;
        }

        .terms-label {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            cursor: pointer;
        }

        .terms-label input[type="checkbox"] {
            display: none;
        }

        .custom-checkbox {
            width: 20px;
            height: 20px;
            min-width: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            margin-top: 2px;
        }

        .custom-checkbox i {
            font-size: 11px;
            color: transparent;
            transition: color 0.3s ease;
        }

        .terms-label input:checked + .custom-checkbox {
            background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
            border-color: transparent;
        }

        .terms-label input:checked + .custom-checkbox i {
            color: white;
        }

        .terms-text {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.5;
        }

        .terms-text a {
            color: #10b981;
            text-decoration: none;
            font-weight: 500;
        }

        .terms-text a:hover {
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-register {
            width: 100%;
            padding: 15px 24px;
            font-size: 15px;
            font-weight: 600;
            font-family: inherit;
            color: #ffffff;
            background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.35);
            margin-top: 8px;
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.5);
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .btn-register .btn-text {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        /* Login Link */
        .login-section {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .login-section p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
        }

        .login-section a {
            color: #10b981;
            text-decoration: none;
            font-weight: 600;
            margin-left: 5px;
            transition: color 0.3s ease;
        }

        .login-section a:hover {
            color: #34d399;
        }

        /* Loading State */
        .btn-register.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-register.loading .btn-text {
            visibility: hidden;
        }

        .btn-register.loading::after {
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
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 900px) {
            .main-container {
                flex-direction: column;
                max-width: 500px;
            }

            .info-panel {
                border-radius: 24px 24px 0 0;
                padding: 35px 28px;
            }

            .register-panel {
                border-radius: 0 0 24px 24px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-top: none;
                max-width: 100%;
            }

            .info-header h2 {
                font-size: 24px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 18px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 15px;
            }

            .info-panel {
                padding: 28px 22px;
            }

            .register-panel {
                padding: 32px 22px;
            }

            .info-header h2 {
                font-size: 22px;
            }

            .form-input {
                padding: 13px 14px 13px 42px;
            }

            .btn-register {
                padding: 14px 20px;
            }

            .benefit-item {
                padding: 14px 16px;
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
        <!-- Left Panel - Information -->
        <div class="info-panel">
            <div class="info-content">
                <!-- Header -->
                <div class="info-header">
                    <div class="welcome-badge">
                        <i class="fas fa-rocket"></i>
                        Bergabung Sekarang!
                    </div>
                    <h2>Buat Akun Admin Yayasan CMS</h2>
                    <p>Daftarkan diri Anda untuk mendapatkan akses penuh ke panel admin dan mulai kelola website yayasan.</p>
                </div>

                <!-- Benefits -->
                <div class="benefits-list">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Akun Aman & Terenkripsi</h4>
                            <p>Password terproteksi dengan enkripsi tingkat tinggi</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-sliders"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Kelola Konten Mudah</h4>
                            <p>Dashboard intuitif untuk semua kebutuhan Anda</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-clock-rotate-left"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Akses 24/7</h4>
                            <p>Login kapan saja dan di mana saja</p>
                        </div>
                    </div>
                </div>

                <!-- Requirements -->
                <div class="requirements">
                    <h4><i class="fas fa-list-check"></i> Persyaratan Password</h4>
                    <ul>
                        <li><i class="fas fa-check"></i> Minimal 6 karakter</li>
                        <li><i class="fas fa-check"></i> Username minimal 3 karakter</li>
                        <li><i class="fas fa-check"></i> Email yang valid dan aktif</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right Panel - Registration Form -->
        <div class="register-panel">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1>Buat Akun Baru</h1>
                <p>Isi formulir di bawah ini</p>
            </div>

            <!-- Registration Form -->
            <form class="register-form" action="" method="POST" id="registerForm">
                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input" 
                            placeholder="Masukkan email Anda"
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                            required
                        >
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>

                <!-- Username -->
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            class="form-input" 
                            placeholder="Pilih username Anda"
                            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                            required
                        >
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>

                <!-- Password Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-input" 
                                placeholder="Min. 6 karakter"
                                required
                            >
                            <i class="fas fa-lock input-icon"></i>
                            <button type="button" class="password-toggle" onclick="togglePassword('password', 'toggleIcon1')">
                                <i class="fas fa-eye" id="toggleIcon1"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi</label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                class="form-input" 
                                placeholder="Ulangi password"
                                required
                            >
                            <i class="fas fa-lock input-icon"></i>
                            <button type="button" class="password-toggle" onclick="togglePassword('confirm_password', 'toggleIcon2')">
                                <i class="fas fa-eye" id="toggleIcon2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Terms -->
                <div class="form-group terms-group">
                    <label class="terms-label">
                        <input type="checkbox" name="terms" required>
                        <span class="custom-checkbox"><i class="fas fa-check"></i></span>
                        <span class="terms-text">
                            Saya menyetujui <a href="#">Syarat dan Ketentuan</a> serta <a href="#">Kebijakan Privasi</a>
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-register" id="btnRegister">
                    <span class="btn-text">
                        <i class="fas fa-user-plus"></i>
                        Daftar Sekarang
                    </span>
                </button>
            </form>

            <!-- Login Link -->
            <div class="login-section">
                <p>Sudah punya akun?<a href="index.php">Login Sekarang</a></p>
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
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);
            
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
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('btnRegister');
            btn.classList.add('loading');
        });

        // Input animations
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
    </script>

    <?php if (!empty($error)): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Registrasi Gagal',
                text: '<?php echo $error; ?>',
                background: 'rgba(30, 30, 50, 0.95)',
                color: '#ffffff',
                confirmButtonColor: '#10b981',
                backdrop: 'rgba(0, 0, 0, 0.6)'
            });
        </script>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?php echo $success; ?>',
                background: 'rgba(30, 30, 50, 0.95)',
                color: '#ffffff',
                confirmButtonColor: '#10b981',
                backdrop: 'rgba(0, 0, 0, 0.6)',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    <?php endif; ?>

</body>

</html>