<?php
/**
 * AJAX: Empty Trash - Delete all soft-deleted registrations permanently
 */
header('Content-Type: application/json');
include '../../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Get all soft-deleted registrations
$query = mysqli_query($conn, "SELECT * FROM event_registrations WHERE deleted_at IS NOT NULL");
$deleted_count = 0;

while ($row = mysqli_fetch_assoc($query)) {
    // Delete payment proof file if exists
    if (!empty($row['payment_proof'])) {
        $file_path = '../../../../' . $row['payment_proof'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    // Delete record
    mysqli_query($conn, "DELETE FROM event_registrations WHERE id = " . $row['id']);
    $deleted_count++;
}

echo json_encode([
    'success' => true,
    'message' => "$deleted_count pendaftaran berhasil dihapus permanen"
]);
