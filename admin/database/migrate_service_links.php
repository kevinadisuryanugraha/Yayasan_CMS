<?php
// Migration: Add link_url field to service_section table
include '../koneksi.php';

echo "<h3>Service Section Migration - Adding link_url field</h3>";

// Check if column already exists
$check = mysqli_query($conn, "SHOW COLUMNS FROM service_section LIKE 'link_url'");

if (mysqli_num_rows($check) > 0) {
    echo "✅ Column 'link_url' already exists!<br>";
} else {
    // Add link_url column
    $sql = "ALTER TABLE service_section ADD COLUMN link_url varchar(255) DEFAULT NULL AFTER icon";

    if (mysqli_query($conn, $sql)) {
        echo "✅ Column 'link_url' added successfully!<br>";
    } else {
        echo "❌ Error adding column: " . mysqli_error($conn) . "<br>";
    }
}

// Create service_header table
$header_sql = file_get_contents('service_header.sql');

if (mysqli_multi_query($conn, $header_sql)) {
    echo "✅ Service header table created successfully!<br>";
} else {
    echo "❌ Error creating header table: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);

echo "<br><a href='../home.php?page=services'>Go to Service Management</a>";
?>