<?php
// Hapus Program Kampanye

// Validasi ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'ID Tidak Valid!', 'message' => 'ID program diperlukan'];
    header("Location: ?page=programs");
    exit;
}

$id = intval($_GET['id']);

// Validasi ID harus numerik
if ($id <= 0) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'ID Tidak Valid!', 'message' => 'Format ID tidak valid'];
    header("Location: ?page=programs");
    exit;
}

// Cek apakah program ada
$check = mysqli_query($conn, "SELECT * FROM campaign_programs WHERE id = $id");
if (mysqli_num_rows($check) == 0) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Tidak Ditemukan!', 'message' => 'Program tidak ditemukan'];
    header("Location: ?page=programs");
    exit;
}

$program = mysqli_fetch_assoc($check);

// Cek konfirmasi
if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
    $_SESSION['alert'] = ['type' => 'warning', 'title' => 'Dibatalkan!', 'message' => 'Penghapusan program dibatalkan'];
    header("Location: ?page=programs");
    exit;
}

// Hapus file gambar jika ada
if (!empty($program['image']) && file_exists('../' . $program['image'])) {
    if (unlink('../' . $program['image'])) {
        // Gambar berhasil dihapus
    }
}

// Hapus dari database
$delete = mysqli_query($conn, "DELETE FROM campaign_programs WHERE id = $id");

if ($delete) {
    $_SESSION['alert'] = [
        'type' => 'success',
        'title' => 'Berhasil Dihapus!',
        'message' => 'Program "' . htmlspecialchars(substr($program['title'], 0, 50)) . '" berhasil dihapus'
    ];
} else {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Gagal Menghapus!',
        'message' => 'Terjadi kesalahan: ' . mysqli_error($conn)
    ];
}

header("Location: ?page=programs");
exit;
?>