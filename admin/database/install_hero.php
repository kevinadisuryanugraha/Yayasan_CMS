<?php
// Database installer for Hero Section
include '../koneksi.php';

// Read SQL file
$sql_file = file_get_contents('hero_section.sql');

// Execute SQL
if (mysqli_multi_query($conn, $sql_file)) {
    echo "✅ Database table 'hero_section' created successfully!<br>";
    echo "✅ Sample data inserted!<br><br>";
    echo "<a href='../home.php?page=hero'>Go to Hero Management</a>";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>