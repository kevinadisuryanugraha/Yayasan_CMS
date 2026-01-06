<?php
// Hapus Kartu Fitur
// Cek apakah parameter ID ada
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'ID Tidak Valid!',
        'message' => 'ID fitur diperlukan'
    ];
    header("Location: ?page=features");
    exit;
}

$id = $_GET['id'];

// Validasi ID harus angka positif
if (!is_numeric($id) || intval($id) <= 0) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'ID Tidak Valid!',
        'message' => 'Format ID tidak valid'
    ];
    header("Location: ?page=features");
    exit;
}

$id = intval($id);

// Cek apakah fitur ada
$check = mysqli_query($conn, "SELECT * FROM feature_section WHERE id = $id");
if (mysqli_num_rows($check) == 0) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Tidak Ditemukan!',
        'message' => 'Kartu fitur tidak ditemukan di database'
    ];
    header("Location: ?page=features");
    exit;
}

$feature = mysqli_fetch_assoc($check);
$feature_title = $feature['title'] ?? 'Tanpa Judul';

// Cek konfirmasi dari SweetAlert
if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
    $_SESSION['alert'] = [
        'type' => 'warning',
        'title' => 'Konfirmasi Diperlukan!',
        'message' => 'Silakan konfirmasi penghapusan melalui tombol Hapus'
    ];
    header("Location: ?page=features");
    exit;
}

// Hapus file ikon jika ada
$icon_deleted = false;
if (!empty($feature['icon']) && file_exists('../' . $feature['icon'])) {
    if (unlink('../' . $feature['icon'])) {
        $icon_deleted = true;
    }
}

// Hapus dari database
$delete = mysqli_query($conn, "DELETE FROM feature_section WHERE id = $id");

if ($delete) {
    $message = 'Kartu fitur "' . htmlspecialchars($feature_title) . '" berhasil dihapus';
    if ($icon_deleted) {
        $message .= ' beserta file ikonnya';
    }
    $_SESSION['alert'] = [
        'type' => 'success',
        'title' => 'Berhasil Dihapus!',
        'message' => $message
    ];
} else {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Gagal Menghapus!',
        'message' => 'Gagal menghapus kartu fitur: ' . mysqli_error($conn)
    ];
}

header("Location: ?page=features");
exit;
?>