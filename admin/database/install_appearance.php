<?php
/**
 * Appearance Settings Database Installation
 * Table: appearance_settings
 */

// Include connection if not already available
if (!isset($conn) || !$conn) {
    include '../koneksi.php';
}

if (!isset($conn) || !$conn) {
    die("Database connection failed.");
}

echo "<h2>🎨 Installing Appearance Settings</h2>";

// Create appearance_settings table
$sql = "
CREATE TABLE IF NOT EXISTS appearance_settings (
    id INT PRIMARY KEY DEFAULT 1,
    primary_color VARCHAR(20) DEFAULT '#2E7D32',
    secondary_color VARCHAR(20) DEFAULT '#1565C0',
    accent_color VARCHAR(20) DEFAULT '#FF9800',
    font_family VARCHAR(100) DEFAULT 'Poppins',
    button_style ENUM('rounded', 'square', 'pill') DEFAULT 'rounded',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

if (mysqli_query($conn, $sql)) {
    echo "<p>✅ Tabel <code>appearance_settings</code> berhasil dibuat</p>";
} else {
    echo "<p>❌ Error: " . mysqli_error($conn) . "</p>";
}

// Insert default values if not exists
$check = mysqli_query($conn, "SELECT id FROM appearance_settings WHERE id = 1");
if (mysqli_num_rows($check) == 0) {
    $insert = "INSERT INTO appearance_settings (id, primary_color, secondary_color, accent_color, font_family, button_style) 
               VALUES (1, '#2E7D32', '#1565C0', '#FF9800', 'Poppins', 'rounded')";
    if (mysqli_query($conn, $insert)) {
        echo "<p>✅ Default values inserted</p>";
    } else {
        echo "<p>❌ Error inserting defaults: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p>⏭️ Default values already exist</p>";
}

echo "<hr>";
echo "<h3>✅ Instalasi Selesai!</h3>";
echo "<p><a href='?page=appearance' class='btn btn-primary'>Buka Pengaturan Tampilan</a></p>";
?>