<?php
/**
 * AJAX: Restore Registration from Trash
 * Clears deleted_at to restore the record
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

// Restore - clear deleted_at
$query = mysqli_query($conn, "UPDATE event_registrations SET deleted_at = NULL WHERE id = $id");

if ($query) {
    echo json_encode(['success' => true, 'message' => 'Pendaftaran berhasil dipulihkan']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal memulihkan: ' . mysqli_error($conn)]);
}
