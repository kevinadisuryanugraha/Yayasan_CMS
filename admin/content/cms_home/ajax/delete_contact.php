<?php
/**
 * AJAX: Delete Contact Message (Soft Delete)
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

// Soft delete - set deleted_at
$query = mysqli_query($conn, "UPDATE contact_messages SET deleted_at = NOW() WHERE id = $id");

if ($query) {
    echo json_encode(['success' => true, 'message' => 'Pesan berhasil dihapus']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menghapus pesan']);
}
