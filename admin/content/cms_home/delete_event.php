<?php
// Hapus Acara

// Validasi ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'ID Tidak Valid!', 'message' => 'ID acara diperlukan'];
    header("Location: ?page=events");
    exit;
}

$id = intval($_GET['id']);

// Validasi ID harus positif
if ($id <= 0) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'ID Tidak Valid!', 'message' => 'ID acara harus berupa angka positif'];
    header("Location: ?page=events");
    exit;
}

// Periksa apakah acara ada
$check = mysqli_query($conn, "SELECT * FROM events WHERE id = $id");
if (mysqli_num_rows($check) == 0) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Tidak Ditemukan!', 'message' => 'Acara tidak ditemukan atau sudah dihapus'];
    header("Location: ?page=events");
    exit;
}

$event = mysqli_fetch_assoc($check);

// Validasi konfirmasi dari SweetAlert
if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
    $_SESSION['alert'] = ['type' => 'warning', 'title' => 'Dibatalkan!', 'message' => 'Penghapusan acara dibatalkan'];
    header("Location: ?page=events");
    exit;
}

// Hapus gambar jika ada
if (!empty($event['image']) && file_exists('../' . $event['image'])) {
    if (!unlink('../' . $event['image'])) {
        // Log error tapi tetap lanjutkan hapus data
        error_log("Gagal menghapus file gambar acara: " . $event['image']);
    }
}

// Hapus dari database
$delete = mysqli_query($conn, "DELETE FROM events WHERE id = $id");

if ($delete) {
    $_SESSION['alert'] = [
        'type' => 'success',
        'title' => 'Berhasil Dihapus!',
        'message' => 'Acara "' . htmlspecialchars($event['title']) . '" berhasil dihapus'
    ];
} else {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Gagal Menghapus!',
        'message' => 'Terjadi kesalahan: ' . mysqli_error($conn)
    ];
}

header("Location: ?page=events");
exit;
?>