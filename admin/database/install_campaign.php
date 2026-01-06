<?php
// Database installer for Campaign Section
include '../koneksi.php';

// Read SQL file
$sql_file = file_get_contents('campaign_section.sql');

// Execute SQL
if (mysqli_multi_query($conn, $sql_file)) {
    echo "✅ Database tables created successfully!<br>";
    echo "✅ campaign_main table initialized<br>";
    echo "✅ campaign_sidebar table initialized<br>";
    echo "✅ campaign_programs table initialized with 2 sample cards<br><br>";
    echo "<a href='../home.php?page=edit_campaign_main'>Go to Campaign Management</a>";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>