<?php
// Database installer for Service Section
include '../koneksi.php';

// Read SQL file
$sql_file = file_get_contents('service_section.sql');

// Execute SQL
if (mysqli_multi_query($conn, $sql_file)) {
    echo "✅ Database table 'service_section' created successfully!<br>";
    echo "✅ 3 sample service cards inserted!<br><br>";
    echo "<a href='../home.php?page=services'>Go to Service Management</a>";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>