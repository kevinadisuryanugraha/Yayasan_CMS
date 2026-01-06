<?php
// Database installer for About Section
include '../koneksi.php';

// Read SQL file
$sql_file = file_get_contents('about_section.sql');

// Execute SQL
if (mysqli_multi_query($conn, $sql_file)) {
    echo "✅ Database table 'about_section' created successfully!<br>";
    echo "✅ Sample data inserted!<br><br>";
    echo "<a href='../home.php?page=about'>Go to About Management</a>";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>