<?php
// Hapus Layanan
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'ID Tidak Valid!', 'message' => 'ID layanan diperlukan'];
    header("Location: ?page=services");
    exit;
}

$id = $_GET['id'];

if (!is_numeric($id) || intval($id) <= 0) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'ID Tidak Valid!', 'message' => 'Format ID tidak valid'];
    header("Location: ?page=services");
    exit;
}

$id = intval($id);

$check = mysqli_query($conn, "SELECT * FROM service_section WHERE id = $id");
if (mysqli_num_rows($check) == 0) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Tidak Ditemukan!', 'message' => 'Layanan tidak ditemukan'];
    header("Location: ?page=services");
    exit;
}

$service = mysqli_fetch_assoc($check);
$service_title = $service['title'] ?? 'Tanpa Judul';

// Cek konfirmasi dari SweetAlert
if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
    $_SESSION['alert'] = ['type' => 'warning', 'title' => 'Konfirmasi Diperlukan!', 'message' => 'Silakan konfirmasi penghapusan melalui tombol Hapus'];
    header("Location: ?page=services");
    exit;
}

// Hapus file gambar utama
$main_deleted = false;
if (!empty($service['main_image']) && file_exists('../' . $service['main_image'])) {
    if (unlink('../' . $service['main_image']))
        $main_deleted = true;
}

// Hapus file ikon
$icon_deleted = false;
if (!empty($service['icon']) && file_exists('../' . $service['icon'])) {
    if (unlink('../' . $service['icon']))
        $icon_deleted = true;
}

// Hapus dari database
$delete = mysqli_query($conn, "DELETE FROM service_section WHERE id = $id");

if ($delete) {
    $message = 'Layanan "' . htmlspecialchars($service_title) . '" berhasil dihapus';
    if ($main_deleted || $icon_deleted) {
        $message .= ' beserta file gambarnya';
    }
    $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil Dihapus!', 'message' => $message];
} else {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Gagal Menghapus!', 'message' => 'Gagal menghapus layanan: ' . mysqli_error($conn)];
}

header("Location: ?page=services");
exit;
?>