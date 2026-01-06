<?php
// Database installer for Feature Section
include '../koneksi.php';

// Read SQL file
$sql_file = file_get_contents('feature_section.sql');

// Execute SQL
if (mysqli_multi_query($conn, $sql_file)) {
    echo "✅ Database table 'feature_section' created successfully!<br>";
    echo "✅ 4 sample feature cards inserted!<br><br>";
    echo "<a href='../home.php?page=features'>Go to Feature Management</a>";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>