<?php
/**
 * AJAX: Update Contact Message Status
 */
header('Content-Type: application/json');
include '../../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
    exit;
}

$valid_statuses = ['new', 'read', 'replied'];
if (!in_array($status, $valid_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Status tidak valid']);
    exit;
}

$status_escaped = mysqli_real_escape_string($conn, $status);

// Update with replied_at timestamp if status is replied
$extra = '';
if ($status == 'replied') {
    $extra = ", replied_at = NOW()";
}

$query = mysqli_query($conn, "UPDATE contact_messages SET status = '$status_escaped' $extra WHERE id = $id");

if ($query) {
    $status_text = [
        'new' => 'baru',
        'read' => 'dibaca',
        'replied' => 'dibalas'
    ];
    echo json_encode(['success' => true, 'message' => 'Status berhasil diubah ke ' . $status_text[$status]]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal mengubah status']);
}
