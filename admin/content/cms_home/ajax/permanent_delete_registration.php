<?php
/**
 * AJAX: Permanent Delete Registration
 * Actually removes the record from database (and payment proof file if exists)
 */
header('Content-Type: application/json');
include '../../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
    exit;
}

// Get registration data to check for payment proof file
$reg = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM event_registrations WHERE id = $id"));

if (!$reg) {
    echo json_encode(['success' => false, 'message' => 'Pendaftaran tidak ditemukan']);
    exit;
}

// Delete payment proof file if exists
if (!empty($reg['payment_proof'])) {
    $file_path = '../../../../' . $reg['payment_proof'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}

// Permanent delete
$query = mysqli_query($conn, "DELETE FROM event_registrations WHERE id = $id");

if ($query) {
    echo json_encode(['success' => true, 'message' => 'Pendaftaran berhasil dihapus permanen']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menghapus: ' . mysqli_error($conn)]);
}
