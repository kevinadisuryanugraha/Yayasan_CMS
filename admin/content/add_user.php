<?php
// Tambah Admin

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Validasi Username
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    if (empty($username)) {
        $errors[] = 'Username wajib diisi';
    } elseif (strlen($username) < 3) {
        $errors[] = 'Username minimal 3 karakter';
    } elseif (strlen($username) > 100) {
        $errors[] = 'Username maksimal 100 karakter';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = 'Username hanya boleh huruf, angka, dan underscore';
    }

    // Validasi Email
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    if (empty($email)) {
        $errors[] = 'Email wajib diisi';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid';
    } elseif (strlen($email) > 100) {
        $errors[] = 'Email maksimal 100 karakter';
    }

    // Validasi Password
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if (empty($password)) {
        $errors[] = 'Password wajib diisi';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password minimal 8 karakter';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password harus mengandung huruf besar';
    } elseif (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password harus mengandung huruf kecil';
    } elseif (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password harus mengandung angka';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Konfirmasi password tidak cocok';
    }

    $username_safe = mysqli_real_escape_string($conn, $username);
    $email_safe = mysqli_real_escape_string($conn, $email);

    // Cek username sudah ada
    if (empty($errors)) {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username_safe'");
        if (mysqli_num_rows($check) > 0) {
            $errors[] = 'Username sudah digunakan';
        }
    }

    // Cek email sudah ada
    if (empty($errors)) {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email_safe'");
        if (mysqli_num_rows($check) > 0) {
            $errors[] = 'Email sudah digunakan';
        }
    }

    // Simpan ke database
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, email, password) VALUES ('$username_safe', '$email_safe', '$password_hash')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Admin baru berhasil ditambahkan'];
            header("Location: ?page=users");
            exit;
        } else {
            $errors[] = 'Gagal menyimpan: ' . mysqli_error($conn);
        }
    }

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
    }
}

$form_errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);

$error_script = '';
if (!empty($form_errors)) {
    $error_list = '<ul style="text-align:left;margin:0;padding-left:20px;">';
    foreach ($form_errors as $error)
        $error_list .= '<li>' . htmlspecialchars($error) . '</li>';
    $error_list .= '</ul>';
    $error_script = "<script>document.addEventListener('DOMContentLoaded', function() { Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan!', html: '" . addslashes($error_list) . "', confirmButtonText: 'Mengerti', confirmButtonColor: '#dc3545' }); });</script>";
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=users">Pengguna</a></li>
                        <li class="breadcrumb-item active">Tambah Baru</li>
                    </ol>
                </div>
                <h4 class="page-title">Tambah Admin Baru</h4>
            </div>
        </div>
    </div>

    <!-- Kartu Petunjuk -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30 bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h5 class="mb-2"><i class="mdi mdi-account-plus text-success mr-2"></i>Membuat Admin Baru</h5>
                            <p class="mb-0 text-muted">
                                Lengkapi form berikut untuk menambahkan pengguna admin baru. 
                                Admin yang ditambahkan akan dapat login ke dashboard dan mengelola konten website.
                            </p>
                        </div>
                        <div class="col-md-3 text-center mt-3 mt-md-0">
                            <i class="mdi mdi-account-key text-success" style="font-size: 50px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Detail Admin</h4>
                    <p class="text-muted m-b-30 font-14">Isi informasi admin baru</p>

                    <form method="POST" action="" id="userForm">
                        <div class="form-group">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" required 
                                minlength="3" maxlength="100" pattern="[a-zA-Z0-9_]+"
                                placeholder="Contoh: admin_hafsa"
                                value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                            <small class="form-text text-muted">
                                <i class="mdi mdi-information-outline"></i> Huruf, angka, dan underscore (3-100 karakter)
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required maxlength="100"
                                placeholder="Contoh: admin@hafsa.com"
                                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                            <small class="form-text text-muted">
                                <i class="mdi mdi-information-outline"></i> Email valid untuk pemulihan akun
                            </small>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-lock mr-1"></i>Keamanan</h5>

                        <div class="form-group">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required minlength="8"
                                    placeholder="Minimal 8 karakter">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="mdi mdi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                <i class="mdi mdi-information-outline"></i> Min 8 karakter, huruf besar, kecil, dan angka
                            </small>
                            <div id="passwordStrength" class="mt-2"></div>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="8"
                                placeholder="Ulangi password">
                            <div id="passwordMatch" class="mt-1"></div>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="mdi mdi-account-plus"></i> Tambah Admin
                        </button>
                        <a href="?page=users" class="btn btn-secondary btn-lg btn-cancel">
                            <i class="mdi mdi-arrow-left"></i> Batal
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <!-- Syarat Password -->
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-shield-check mr-2"></i>Syarat Password</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0" id="passwordRules">
                        <li id="rule-length" class="text-muted"><i class="mdi mdi-close-circle"></i> Minimal 8 karakter</li>
                        <li id="rule-upper" class="text-muted"><i class="mdi mdi-close-circle"></i> Huruf besar (A-Z)</li>
                        <li id="rule-lower" class="text-muted"><i class="mdi mdi-close-circle"></i> Huruf kecil (a-z)</li>
                        <li id="rule-number" class="text-muted"><i class="mdi mdi-close-circle"></i> Angka (0-9)</li>
                    </ul>
                </div>
            </div>

            <!-- Tips -->
            <div class="card m-b-30 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Tips Keamanan</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2">Jangan gunakan password yang mudah ditebak</li>
                        <li class="mb-2">Hindari nama, tanggal lahir, atau info pribadi</li>
                        <li class="mb-2">Gunakan kombinasi unik untuk setiap akun</li>
                        <li class="mb-0">Simpan password di tempat yang aman</li>
                    </ul>
                </div>
            </div>

            <!-- Contoh Username -->
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-account mr-2"></i>Contoh Username</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <span class="badge badge-light p-2 m-1 border">admin_utama</span>
                        <span class="badge badge-light p-2 m-1 border">operator1</span>
                        <span class="badge badge-light p-2 m-1 border">staff_it</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $error_script; ?>

<script>
// Toggle password visibility
document.getElementById('togglePassword').addEventListener('click', function() {
    const password = document.getElementById('password');
    const icon = this.querySelector('i');
    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('mdi-eye');
        icon.classList.add('mdi-eye-off');
    } else {
        password.type = 'password';
        icon.classList.remove('mdi-eye-off');
        icon.classList.add('mdi-eye');
    }
});

// Password strength checker
document.getElementById('password').addEventListener('input', function() {
    const val = this.value;
    const rules = {
        length: val.length >= 8,
        upper: /[A-Z]/.test(val),
        lower: /[a-z]/.test(val),
        number: /[0-9]/.test(val)
    };

    Object.keys(rules).forEach(function(rule) {
        const el = document.getElementById('rule-' + rule);
        if (rules[rule]) {
            el.classList.remove('text-muted');
            el.classList.add('text-success');
            el.querySelector('i').classList.remove('mdi-close-circle');
            el.querySelector('i').classList.add('mdi-check-circle');
        } else {
            el.classList.remove('text-success');
            el.classList.add('text-muted');
            el.querySelector('i').classList.remove('mdi-check-circle');
            el.querySelector('i').classList.add('mdi-close-circle');
        }
    });

    // Check match
    checkPasswordMatch();
});

// Password match checker
document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);

function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    const matchEl = document.getElementById('passwordMatch');
    
    if (confirm.length === 0) {
        matchEl.innerHTML = '';
    } else if (password === confirm) {
        matchEl.innerHTML = '<small class="text-success"><i class="mdi mdi-check-circle"></i> Password cocok</small>';
    } else {
        matchEl.innerHTML = '<small class="text-danger"><i class="mdi mdi-close-circle"></i> Password tidak cocok</small>';
    }
}

// Form validation
document.getElementById('userForm').addEventListener('submit', function(e) {
    const username = document.getElementById('username').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    let errors = [];

    if (username.length < 3) errors.push('Username minimal 3 karakter');
    if (!/^[a-zA-Z0-9_]+$/.test(username)) errors.push('Username hanya boleh huruf, angka, dan underscore');
    if (!email || !/\S+@\S+\.\S+/.test(email)) errors.push('Format email tidak valid');
    if (password.length < 8) errors.push('Password minimal 8 karakter');
    if (!/[A-Z]/.test(password)) errors.push('Password harus mengandung huruf besar');
    if (!/[a-z]/.test(password)) errors.push('Password harus mengandung huruf kecil');
    if (!/[0-9]/.test(password)) errors.push('Password harus mengandung angka');
    if (password !== confirm) errors.push('Konfirmasi password tidak cocok');

    if (errors.length > 0) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal!',
            html: '<ul style="text-align:left;padding-left:20px;margin:0;">' + errors.map(e => '<li>' + e + '</li>').join('') + '</ul>',
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#dc3545'
        });
        return false;
    }

    Swal.fire({
        title: 'Menyimpan...',
        html: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => { Swal.showLoading(); }
    });

    return true;
});

// Konfirmasi batal
document.querySelector('.btn-cancel').addEventListener('click', function(e) {
    e.preventDefault();
    const link = this.href;
    
    Swal.fire({
        icon: 'question',
        title: 'Batalkan?',
        text: 'Data yang sudah diisi akan hilang.',
        showCancelButton: true,
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Lanjut Mengisi',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) window.location.href = link;
    });
});
</script>