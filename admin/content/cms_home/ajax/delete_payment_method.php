<?php
/**
 * AJAX: Delete Payment Method
 */
header('Content-Type: application/json');
include '../../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
    exit;
}

// Get payment method data first (to delete files)
$query = mysqli_query($conn, "SELECT * FROM payment_methods WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    exit;
}

// Delete the record
$delete = mysqli_query($conn, "DELETE FROM payment_methods WHERE id = $id");

if ($delete) {
    // Delete associated files
    if ($data['icon'] && file_exists('../../../' . $data['icon'])) {
        unlink('../../../' . $data['icon']);
    }
    if ($data['qr_image'] && file_exists('../../../' . $data['qr_image'])) {
        unlink('../../../' . $data['qr_image']);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Metode pembayaran "' . $data['name'] . '" berhasil dihapus'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menghapus: ' . mysqli_error($conn)
    ]);
}
