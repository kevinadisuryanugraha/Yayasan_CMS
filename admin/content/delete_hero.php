<?php
// Hapus Konten Hero
// Cek apakah parameter ID ada
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'ID Tidak Valid!',
        'message' => 'ID konten hero diperlukan untuk menghapus data'
    ];
    header("Location: ?page=hero");
    exit;
}

$id = intval($_GET['id']);

// Validasi ID harus positif
if ($id <= 0) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'ID Tidak Valid!',
        'message' => 'ID konten hero harus berupa angka positif'
    ];
    header("Location: ?page=hero");
    exit;
}

// Cek apakah konten hero ada
$check = mysqli_query($conn, "SELECT * FROM hero_section WHERE id = $id");
if (mysqli_num_rows($check) == 0) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Data Tidak Ditemukan!',
        'message' => 'Konten hero dengan ID tersebut tidak ditemukan dalam database'
    ];
    header("Location: ?page=hero");
    exit;
}

$hero = mysqli_fetch_assoc($check);
$hero_title = $hero['title'] ?? 'Tanpa Judul';

// Cek apakah ada konfirmasi
if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
    // Redirect kembali - konfirmasi dilakukan via JavaScript di halaman hero.php
    $_SESSION['alert'] = [
        'type' => 'warning',
        'title' => 'Konfirmasi Diperlukan!',
        'message' => 'Silakan konfirmasi penghapusan melalui tombol hapus'
    ];
    header("Location: ?page=hero");
    exit;
}

// Hapus file gambar jika ada
$image_deleted = false;
if (!empty($hero['image'])) {
    $image_path = '../' . $hero['image'];
    if (file_exists($image_path)) {
        if (unlink($image_path)) {
            $image_deleted = true;
        }
    }
}

// Hapus dari database
$delete = mysqli_query($conn, "DELETE FROM hero_section WHERE id = $id");

if ($delete) {
    $message = 'Konten hero "' . htmlspecialchars($hero_title) . '" berhasil dihapus';
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
        'message' => 'Terjadi kesalahan saat menghapus konten hero: ' . mysqli_error($conn)
    ];
}

header("Location: ?page=hero");
exit;
?>