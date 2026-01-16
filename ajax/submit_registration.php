<?php
/**
 * AJAX Handler: Submit Event Registration
 * Handles form submission, file upload, and validates quota
 * 
 * REVISED FLOW:
 * - Free events: status = pending (admin confirms manually)
 * - Paid events: status = pending, payment_status = unpaid (admin verifies payment proof then confirms)
 */

header('Content-Type: application/json');
include '../admin/koneksi.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get and validate event_id
$event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
if ($event_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Event ID tidak valid']);
    exit;
}

// Fetch event details
$event_query = mysqli_query($conn, "SELECT * FROM events WHERE id = $event_id AND is_active = 1 AND status = 'published'");
$event = mysqli_fetch_assoc($event_query);

if (!$event) {
    echo json_encode(['success' => false, 'message' => 'Event tidak ditemukan']);
    exit;
}

// Check if event date has passed
if (strtotime($event['event_date']) < strtotime('today')) {
    echo json_encode(['success' => false, 'message' => 'Pendaftaran sudah ditutup (event sudah lewat)']);
    exit;
}

// Check quota
$registered = intval($event['registered']);
$quota = intval($event['quota']);
if ($quota > 0 && $registered >= $quota) {
    echo json_encode(['success' => false, 'message' => 'Kuota pendaftaran sudah penuh']);
    exit;
}

// Validate required fields
$required = ['full_name', 'email', 'phone'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => 'Field ' . $field . ' wajib diisi']);
        exit;
    }
}

// Validate email format
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Format email tidak valid']);
    exit;
}

// Check if email already registered for this event
$check_query = mysqli_query($conn, "SELECT id FROM event_registrations WHERE event_id = $event_id AND email = '" . mysqli_real_escape_string($conn, $email) . "' AND status != 'cancelled'");
if (mysqli_num_rows($check_query) > 0) {
    echo json_encode(['success' => false, 'message' => 'Email sudah terdaftar untuk event ini']);
    exit;
}

// Handle payment proof upload for PAID events
$payment_proof_path = '';
$payment_date = null;
$is_paid_event = ($event['price'] > 0);

if ($is_paid_event) {
    // Validate payment_proof is uploaded
    if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] === UPLOAD_ERR_NO_FILE) {
        echo json_encode(['success' => false, 'message' => 'Bukti pembayaran wajib diupload untuk event berbayar']);
        exit;
    }

    if ($_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Error upload file: ' . $_FILES['payment_proof']['error']]);
        exit;
    }

    // Validate file size (max 5MB)
    if ($_FILES['payment_proof']['size'] > 5 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'Ukuran file terlalu besar. Maksimal 5MB.']);
        exit;
    }

    // Validate file type
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $file_type = $_FILES['payment_proof']['type'];
    if (!in_array($file_type, $allowed_types)) {
        echo json_encode(['success' => false, 'message' => 'Format file tidak valid. Gunakan JPG, PNG, GIF, atau WebP.']);
        exit;
    }

    // Create upload directory if not exists
    $upload_dir = '../uploads/payment_proofs/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Generate unique filename
    $ext = strtolower(pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION));
    $filename = 'payment_' . date('Ymd_His') . '_' . uniqid() . '.' . $ext;
    $target_path = $upload_dir . $filename;

    // Move uploaded file
    if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $target_path)) {
        $payment_proof_path = 'uploads/payment_proofs/' . $filename;

        // Get payment_date from POST or use current time
        $payment_date = !empty($_POST['payment_date']) ? $_POST['payment_date'] : date('Y-m-d H:i:s');
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan file bukti pembayaran']);
        exit;
    }
}

// Generate unique registration code
$registration_code = 'REG' . date('ymd') . strtoupper(substr(md5(uniqid()), 0, 6));

// Sanitize inputs
$full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
$phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
$whatsapp = mysqli_real_escape_string($conn, trim($_POST['whatsapp'] ?? ''));
$gender = in_array($_POST['gender'] ?? '', ['male', 'female']) ? $_POST['gender'] : null;
$age = isset($_POST['age']) && is_numeric($_POST['age']) ? intval($_POST['age']) : null;
$address = mysqli_real_escape_string($conn, trim($_POST['address'] ?? ''));
$city = mysqli_real_escape_string($conn, trim($_POST['city'] ?? ''));
$institution = mysqli_real_escape_string($conn, trim($_POST['institution'] ?? ''));
$notes = mysqli_real_escape_string($conn, trim($_POST['notes'] ?? ''));

// REVISED FLOW: ALL registrations start as 'pending'
// Admin must manually confirm both free and paid events
$initial_status = 'pending';
$payment_status = $is_paid_event ? 'unpaid' : 'paid';
$payment_amount = floatval($event['price']);

// Build INSERT query
$query = "INSERT INTO event_registrations (
    event_id, registration_code, full_name, email, phone, whatsapp,
    gender, age, address, city, institution, notes,
    status, payment_status, payment_amount, payment_proof, payment_date
) VALUES (
    $event_id, '$registration_code', '$full_name', '" . mysqli_real_escape_string($conn, $email) . "', '$phone', '$whatsapp',
    " . ($gender ? "'$gender'" : "NULL") . ", " . ($age ? $age : "NULL") . ", '$address', '$city', '$institution', '$notes',
    '$initial_status', '$payment_status', $payment_amount, 
    " . ($payment_proof_path ? "'$payment_proof_path'" : "NULL") . ",
    " . ($payment_date ? "'" . mysqli_real_escape_string($conn, $payment_date) . "'" : "NULL") . "
)";

if (mysqli_query($conn, $query)) {
    $registration_id = mysqli_insert_id($conn);

    // Build success message based on event type
    if ($is_paid_event) {
        $success_message = 'Pendaftaran berhasil! Mohon tunggu verifikasi pembayaran dari admin.';
    } else {
        $success_message = 'Pendaftaran berhasil! Mohon tunggu konfirmasi dari admin.';
    }

    echo json_encode([
        'success' => true,
        'message' => $success_message,
        'data' => [
            'registration_code' => $registration_code,
            'event_title' => $event['title'],
            'event_date' => date('d F Y', strtotime($event['event_date'])),
            'status' => $initial_status,
            'payment_required' => $is_paid_event,
            'payment_amount' => $event['price'],
            'payment_proof_uploaded' => !empty($payment_proof_path)
        ]
    ]);
} else {
    // If insert failed and we uploaded a file, clean up
    if ($payment_proof_path && file_exists('../' . $payment_proof_path)) {
        unlink('../' . $payment_proof_path);
    }

    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan pendaftaran: ' . mysqli_error($conn)]);
}
