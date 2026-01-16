<?php
/**
 * AJAX: Soft Delete Registration
 * Sets deleted_at timestamp instead of removing record
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

// Check if column exists, if not, add it
$check_column = mysqli_query($conn, "SHOW COLUMNS FROM event_registrations LIKE 'deleted_at'");
if (mysqli_num_rows($check_column) == 0) {
    mysqli_query($conn, "ALTER TABLE event_registrations ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL");
}

// Soft delete - set deleted_at timestamp
$query = mysqli_query($conn, "UPDATE event_registrations SET deleted_at = NOW() WHERE id = $id");

if ($query) {
    echo json_encode(['success' => true, 'message' => 'Pendaftaran berhasil dihapus (dipindahkan ke Trash)']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menghapus: ' . mysqli_error($conn)]);
}
