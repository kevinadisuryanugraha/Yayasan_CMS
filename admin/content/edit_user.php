<?php
// Edit Admin

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'ID Tidak Valid!', 'message' => 'ID pengguna diperlukan'];
    header("Location: ?page=users");
    exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
if (mysqli_num_rows($query) == 0) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Tidak Ditemukan!', 'message' => 'Pengguna tidak ditemukan'];
    header("Location: ?page=users");
    exit;
}

$user = mysqli_fetch_assoc($query);

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

    // Validasi Password (opsional)
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if (!empty($password)) {
        if (strlen($password) < 8) {
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
    }

    $username_safe = mysqli_real_escape_string($conn, $username);
    $email_safe = mysqli_real_escape_string($conn, $email);

    // Cek username sudah ada (kecuali user ini)
    if (empty($errors)) {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username_safe' AND id != $id");
        if (mysqli_num_rows($check) > 0) {
            $errors[] = 'Username sudah digunakan';
        }
    }

    // Cek email sudah ada (kecuali user ini)
    if (empty($errors)) {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email_safe' AND id != $id");
        if (mysqli_num_rows($check) > 0) {
            $errors[] = 'Email sudah digunakan';
        }
    }

    // Simpan ke database
    if (empty($errors)) {
        $password_sql = '';
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $password_sql = ", password = '$password_hash'";
        }

        $update = "UPDATE users SET username = '$username_safe', email = '$email_safe' $password_sql, updated_at = NOW() WHERE id = $id";

        if (mysqli_query($conn, $update)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Admin berhasil diperbarui'];
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
                        <li class="breadcrumb-item active">Ubah</li>
                    </ol>
                </div>
                <h4 class="page-title">Ubah Admin</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mt-0 header-title">Detail Admin</h4>
                            <p class="text-muted mb-0 font-14">Perbarui informasi admin</p>
                        </div>
                        <?php if (isset($_SESSION['id']) && $user['id'] == $_SESSION['id']): ?>
                            <span class="badge badge-info p-2">Akun Anda</span>
                        <?php endif; ?>
                    </div>

                    <form method="POST" action="" id="userForm">
                        <div class="form-group">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" required 
                                minlength="3" maxlength="100" pattern="[a-zA-Z0-9_]+"
                                value="<?php echo htmlspecialchars($user['username']); ?>">
                            <small class="form-text text-muted">
                                <i class="mdi mdi-information-outline"></i> Huruf, angka, dan underscore (3-100 karakter)
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required maxlength="100"
                                value="<?php echo htmlspecialchars($user['email']); ?>">
                            <small class="form-text text-muted">
                                <i class="mdi mdi-information-outline"></i> Email valid untuk pemulihan akun
                            </small>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-lock mr-1"></i>Ubah Password (Opsional)</h5>
                        <div class="alert alert-info small">
                            <i class="mdi mdi-information mr-1"></i> Kosongkan jika tidak ingin mengubah password
                        </div>

                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" minlength="8"
                                    placeholder="Kosongkan jika tidak mengubah">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="mdi mdi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted">Min 8 karakter, huruf besar, kecil, dan angka</small>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="8"
                                placeholder="Ulangi password baru">
                            <div id="passwordMatch" class="mt-1"></div>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="mdi mdi-content-save"></i> Simpan Perubahan
                        </button>
                        <a href="?page=users" class="btn btn-secondary btn-lg btn-cancel">
                            <i class="mdi mdi-arrow-left"></i> Batal
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <!-- Info Admin -->
            <div class="card m-b-30 border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-account-details mr-2"></i>Informasi Admin</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">ID:</td>
                            <td><strong>#<?php echo $user['id']; ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Username:</td>
                            <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dibuat:</td>
                            <td><small><i class="mdi mdi-calendar text-muted"></i> <?php echo date('d M Y H:i', strtotime($user['created_at'])); ?></small></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Diubah:</td>
                            <td><small><i class="mdi mdi-calendar-edit text-muted"></i> <?php echo $user['updated_at'] ? date('d M Y H:i', strtotime($user['updated_at'])) : '-'; ?></small></td>
                        </tr>
                    </table>
                </div>
            </div>

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
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2">Username tidak boleh sama dengan admin lain</li>
                        <li class="mb-2">Kosongkan password jika tidak ingin mengubah</li>
                        <li class="mb-0">Pastikan email valid untuk pemulihan</li>
                    </ul>
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
    if (val.length === 0) {
        // Reset all rules
        ['length', 'upper', 'lower', 'number'].forEach(function(rule) {
            const el = document.getElementById('rule-' + rule);
            el.classList.remove('text-success');
            el.classList.add('text-muted');
            el.querySelector('i').classList.remove('mdi-check-circle');
            el.querySelector('i').classList.add('mdi-close-circle');
        });
        return;
    }

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

    checkPasswordMatch();
});

// Password match checker
document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);

function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    const matchEl = document.getElementById('passwordMatch');
    
    if (confirm.length === 0 || password.length === 0) {
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
    
    if (password.length > 0) {
        if (password.length < 8) errors.push('Password minimal 8 karakter');
        if (!/[A-Z]/.test(password)) errors.push('Password harus mengandung huruf besar');
        if (!/[a-z]/.test(password)) errors.push('Password harus mengandung huruf kecil');
        if (!/[0-9]/.test(password)) errors.push('Password harus mengandung angka');
        if (password !== confirm) errors.push('Konfirmasi password tidak cocok');
    }

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
        title: 'Menyimpan Perubahan...',
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
        title: 'Batalkan Perubahan?',
        text: 'Perubahan yang belum disimpan akan hilang.',
        showCancelButton: true,
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Lanjut Mengubah',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) window.location.href = link;
    });
});
</script>