<?php
/**
 * Install Payment Methods Table with Dummy Data
 * Run this file once to create the table and sample data
 */
include 'admin/koneksi.php';

echo "<h2>🔧 Installing Payment Methods...</h2>";

// Create table
$sql_table = "
CREATE TABLE IF NOT EXISTS payment_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('bank', 'ewallet', 'qris') NOT NULL COMMENT 'Tipe: bank/ewallet/qris',
    name VARCHAR(100) NOT NULL COMMENT 'Nama bank/e-wallet',
    account_number VARCHAR(50) NULL COMMENT 'Nomor rekening/nomor HP',
    account_name VARCHAR(100) NULL COMMENT 'Nama pemilik rekening',
    icon VARCHAR(255) NULL COMMENT 'Path icon/logo',
    qr_image VARCHAR(255) NULL COMMENT 'Path gambar QR Code',
    instructions TEXT NULL COMMENT 'Instruksi pembayaran',
    is_active TINYINT(1) DEFAULT 1 COMMENT '1=aktif, 0=nonaktif',
    sort_order INT DEFAULT 0 COMMENT 'Urutan tampilan',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_type (type),
    INDEX idx_active (is_active),
    INDEX idx_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
";

if (mysqli_query($conn, $sql_table)) {
    echo "<p>✅ Tabel <code>payment_methods</code> berhasil dibuat!</p>";
} else {
    echo "<p>❌ Error membuat tabel: " . mysqli_error($conn) . "</p>";
    exit;
}

// Check if data already exists
$check = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM payment_methods");
$count = mysqli_fetch_assoc($check)['cnt'];

if ($count > 0) {
    echo "<p>⚠️ Data sudah ada ({$count} records). Skip insert dummy data.</p>";
} else {
    // Insert dummy data
    $dummy_data = [
        // Bank Transfer
        ['bank', 'Bank BCA', '1234567890', 'Yayasan Hafsa Indonesia', 'Transfer ke rekening BCA diatas, lalu konfirmasi pembayaran.', 1],
        ['bank', 'Bank Mandiri', '0987654321', 'Yayasan Hafsa Indonesia', 'Transfer ke rekening Mandiri diatas, lalu konfirmasi pembayaran.', 2],
        ['bank', 'Bank BNI', '1122334455', 'Yayasan Hafsa Indonesia', 'Transfer ke rekening BNI diatas, lalu konfirmasi pembayaran.', 3],
        ['bank', 'Bank BRI', '5566778899', 'Yayasan Hafsa Indonesia', 'Transfer ke rekening BRI diatas, lalu konfirmasi pembayaran.', 4],
        ['bank', 'Bank Syariah Indonesia (BSI)', '7788990011', 'Yayasan Hafsa Indonesia', 'Transfer ke rekening BSI diatas, lalu konfirmasi pembayaran.', 5],

        // E-Wallet
        ['ewallet', 'GoPay', '081234567890', 'Yayasan Hafsa', 'Transfer via GoPay ke nomor diatas atau scan QR Code.', 6],
        ['ewallet', 'Dana', '081234567890', 'Yayasan Hafsa', 'Transfer via DANA ke nomor diatas atau scan QR Code.', 7],
        ['ewallet', 'OVO', '081234567890', 'Yayasan Hafsa', 'Transfer via OVO ke nomor diatas.', 8],
        ['ewallet', 'ShopeePay', '081234567890', 'Yayasan Hafsa', 'Transfer via ShopeePay ke nomor diatas.', 9],
        ['ewallet', 'LinkAja', '081234567890', 'Yayasan Hafsa', 'Transfer via LinkAja ke nomor diatas.', 10],

        // QRIS
        ['qris', 'QRIS Universal', NULL, 'Yayasan Hafsa Indonesia', 'Scan QR Code menggunakan aplikasi e-wallet atau mobile banking Anda.', 11],
    ];

    $success_count = 0;
    foreach ($dummy_data as $data) {
        $type = $data[0];
        $name = mysqli_real_escape_string($conn, $data[1]);
        $account_number = $data[2] ? "'" . mysqli_real_escape_string($conn, $data[2]) . "'" : 'NULL';
        $account_name = mysqli_real_escape_string($conn, $data[3]);
        $instructions = mysqli_real_escape_string($conn, $data[4]);
        $sort_order = $data[5];

        $sql = "INSERT INTO payment_methods (type, name, account_number, account_name, instructions, is_active, sort_order) 
                VALUES ('$type', '$name', $account_number, '$account_name', '$instructions', 1, $sort_order)";

        if (mysqli_query($conn, $sql)) {
            $success_count++;
        }
    }

    echo "<p>✅ Berhasil menambahkan <strong>{$success_count}</strong> metode pembayaran dummy!</p>";
}

// Show summary
echo "<hr>";
echo "<h3>📊 Data Summary:</h3>";

$types = ['bank' => 'Bank Transfer', 'ewallet' => 'E-Wallet', 'qris' => 'QRIS'];
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>Tipe</th><th>Jumlah</th></tr>";

foreach ($types as $type => $label) {
    $q = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM payment_methods WHERE type = '$type'");
    $c = mysqli_fetch_assoc($q)['cnt'];
    echo "<tr><td>{$label}</td><td>{$c}</td></tr>";
}
echo "</table>";

echo "<hr>";
echo "<h3>📋 Daftar Metode Pembayaran:</h3>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>ID</th><th>Tipe</th><th>Nama</th><th>No. Rekening/HP</th><th>Pemilik</th><th>Status</th></tr>";

$all = mysqli_query($conn, "SELECT * FROM payment_methods ORDER BY sort_order ASC");
while ($row = mysqli_fetch_assoc($all)) {
    $status = $row['is_active'] ? '✅ Aktif' : '❌ Nonaktif';
    $acc = $row['account_number'] ?: '-';
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['type']}</td>";
    echo "<td><strong>{$row['name']}</strong></td>";
    echo "<td><code>{$acc}</code></td>";
    echo "<td>{$row['account_name']}</td>";
    echo "<td>{$status}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<hr>";
echo "<p>🎉 <strong>Instalasi selesai!</strong></p>";
echo "<p><a href='admin/?page=payment_methods' style='padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;'>Buka Halaman Payment Methods →</a></p>";
echo "<p style='color: #999; margin-top: 20px;'><small>Anda dapat menghapus file ini setelah selesai.</small></p>";
