<?php
/**
 * AJAX: Update Registration Status
 * 
 * LOGIC:
 * - When status = confirmed or attended AND event is paid:
 *   → payment_status also becomes 'paid'
 * - When status = cancelled:
 *   → confirmed_at is cleared
 */
header('Content-Type: application/json');
include '../../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';

if ($id <= 0 || !in_array($status, ['pending', 'confirmed', 'attended', 'cancelled'])) {
    echo json_encode(['success' => false, 'message' => 'Parameter tidak valid']);
    exit;
}

// Check if this registration is for a paid event
$reg_query = mysqli_query($conn, "
    SELECT r.*, e.price 
    FROM event_registrations r 
    JOIN events e ON r.event_id = e.id 
    WHERE r.id = $id
");
$registration = mysqli_fetch_assoc($reg_query);

if (!$registration) {
    echo json_encode(['success' => false, 'message' => 'Pendaftaran tidak ditemukan']);
    exit;
}

$is_paid_event = ($registration['price'] > 0);

// Build update query based on status
$extra = '';
if ($status == 'confirmed') {
    $extra = ", confirmed_at = NOW()";
    // If confirmed AND paid event, set payment_status to 'paid'
    if ($is_paid_event) {
        $extra .= ", payment_status = 'paid'";
    }
} elseif ($status == 'attended') {
    // If attended (hadir), also mark as paid for paid events
    if ($is_paid_event) {
        $extra = ", payment_status = 'paid'";
    }
    // Also set confirmed_at if not already set
    if (!$registration['confirmed_at']) {
        $extra .= ", confirmed_at = NOW()";
    }
} elseif ($status == 'cancelled') {
    $extra = ", confirmed_at = NULL";
}

$query = mysqli_query($conn, "UPDATE event_registrations SET status = '$status' $extra WHERE id = $id");

if ($query) {
    $status_texts = [
        'pending' => 'Menunggu',
        'confirmed' => 'Terkonfirmasi',
        'attended' => 'Hadir',
        'cancelled' => 'Dibatalkan'
    ];

    $message = 'Status berhasil diubah menjadi ' . $status_texts[$status];

    // Add note about payment status if applicable
    if (($status == 'confirmed' || $status == 'attended') && $is_paid_event) {
        $message .= ' (Pembayaran: Lunas)';
    }

    echo json_encode(['success' => true, 'message' => $message]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal mengubah status: ' . mysqli_error($conn)]);
}
