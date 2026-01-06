<?php
// Hapus Konten About
// Cek apakah parameter ID ada
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'ID Tidak Valid!',
        'message' => 'ID konten about diperlukan'
    ];
    header("Location: ?page=about");
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
    header("Location: ?page=about");
    exit;
}

$id = intval($id);

// Cek apakah konten about ada
$check = mysqli_query($conn, "SELECT * FROM about_section WHERE id = $id");
if (mysqli_num_rows($check) == 0) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Tidak Ditemukan!',
        'message' => 'Konten about tidak ditemukan di database'
    ];
    header("Location: ?page=about");
    exit;
}

$about = mysqli_fetch_assoc($check);
$about_title = $about['title'] ?? 'Tanpa Judul';

// Cek konfirmasi dari SweetAlert
if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
    $_SESSION['alert'] = [
        'type' => 'warning',
        'title' => 'Konfirmasi Diperlukan!',
        'message' => 'Silakan konfirmasi penghapusan melalui tombol Hapus'
    ];
    header("Location: ?page=about");
    exit;
}

// Hapus file gambar jika ada
$image_deleted = false;
if (!empty($about['image']) && file_exists('../' . $about['image'])) {
    if (unlink('../' . $about['image'])) {
        $image_deleted = true;
    }
}

// Hapus dari database
$delete = mysqli_query($conn, "DELETE FROM about_section WHERE id = $id");

if ($delete) {
    $message = 'Konten about "' . htmlspecialchars($about_title) . '" berhasil dihapus';
    if ($image_deleted) {
        $message .= ' beserta file gambarnya';
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
        'message' => 'Gagal menghapus konten about: ' . mysqli_error($conn)
    ];
}

header("Location: ?page=about");
exit;
?>