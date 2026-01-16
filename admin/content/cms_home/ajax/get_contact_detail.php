<?php
/**
 * AJAX: Get Contact Message Detail
 */
header('Content-Type: application/json');
include '../../../koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM contact_messages WHERE id = $id AND deleted_at IS NULL");
$message = mysqli_fetch_assoc($query);

if (!$message) {
    echo json_encode(['success' => false, 'message' => 'Pesan tidak ditemukan']);
    exit;
}

// Auto mark as read if new
if ($message['status'] == 'new') {
    mysqli_query($conn, "UPDATE contact_messages SET status = 'read' WHERE id = $id");
    $message['status'] = 'read';
}

// Format date
$message['created_at'] = date('d M Y, H:i', strtotime($message['created_at']));

echo json_encode(['success' => true, 'data' => $message]);
