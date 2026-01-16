<?php
/**
 * AJAX: Submit Contact Form
 * Saves contact message to database
 */
header('Content-Type: application/json');

// Include database connection
include '../admin/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get and sanitize input
$name = isset($_POST['name']) ? trim(mysqli_real_escape_string($conn, $_POST['name'])) : '';
$email = isset($_POST['email']) ? trim(mysqli_real_escape_string($conn, $_POST['email'])) : '';
$phone = isset($_POST['phone']) ? trim(mysqli_real_escape_string($conn, $_POST['phone'])) : '';
$subject = isset($_POST['subject']) ? trim(mysqli_real_escape_string($conn, $_POST['subject'])) : '';
$message = isset($_POST['message']) ? trim(mysqli_real_escape_string($conn, $_POST['message'])) : '';

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = 'Nama lengkap wajib diisi';
}

if (empty($email)) {
    $errors[] = 'Email wajib diisi';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Format email tidak valid';
}

if (empty($message)) {
    $errors[] = 'Pesan wajib diisi';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Create table if not exists
$create_table = "CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    admin_notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    replied_at DATETIME,
    deleted_at DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
mysqli_query($conn, $create_table);

// Insert message
$query = mysqli_query($conn, "INSERT INTO contact_messages (name, email, phone, subject, message, status, created_at) 
                              VALUES ('$name', '$email', '$phone', '$subject', '$message', 'new', NOW())");

if ($query) {
    echo json_encode([
        'success' => true,
        'message' => 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal mengirim pesan. Silakan coba lagi.'
    ]);
}
