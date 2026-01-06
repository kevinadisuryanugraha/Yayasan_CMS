<?php
// Hapus Admin

// Validasi ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'ID Tidak Valid!', 'message' => 'ID pengguna diperlukan'];
    header("Location: ?page=users");
    exit;
}

$id = intval($_GET['id']);

// Validasi ID harus positif
if ($id <= 0) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'ID Tidak Valid!', 'message' => 'ID pengguna harus berupa angka positif'];
    header("Location: ?page=users");
    exit;
}

// Cegah hapus diri sendiri
if (isset($_SESSION['id']) && $id == $_SESSION['id']) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Tidak Diizinkan!', 'message' => 'Anda tidak dapat menghapus akun Anda sendiri'];
    header("Location: ?page=users");
    exit;
}

// Juga cek dengan session user_id (backward compatibility)
if (isset($_SESSION['user_id']) && $id == $_SESSION['user_id']) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Tidak Diizinkan!', 'message' => 'Anda tidak dapat menghapus akun Anda sendiri'];
    header("Location: ?page=users");
    exit;
}

// Periksa apakah user ada
$check = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
if (mysqli_num_rows($check) == 0) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Tidak Ditemukan!', 'message' => 'Pengguna tidak ditemukan atau sudah dihapus'];
    header("Location: ?page=users");
    exit;
}

$user = mysqli_fetch_assoc($check);

// Validasi konfirmasi dari SweetAlert
if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
    $_SESSION['alert'] = ['type' => 'warning', 'title' => 'Dibatalkan!', 'message' => 'Penghapusan admin dibatalkan'];
    header("Location: ?page=users");
    exit;
}

// Hapus dari database
$delete = mysqli_query($conn, "DELETE FROM users WHERE id = $id");

if ($delete) {
    $_SESSION['alert'] = [
        'type' => 'success',
        'title' => 'Berhasil Dihapus!',
        'message' => 'Admin "' . htmlspecialchars($user['username']) . '" berhasil dihapus'
    ];
} else {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Gagal Menghapus!',
        'message' => 'Terjadi kesalahan: ' . mysqli_error($conn)
    ];
}

header("Location: ?page=users");
exit;
?>