<?php
session_start();
include '../koneksi.php';

// Check if already installed
$check = mysqli_query($conn, "SHOW TABLES LIKE 'site_settings'");
if (mysqli_num_rows($check) > 0) {
    echo "<h2 style='color: orange;'>⚠️ Site Settings table already exists!</h2>";
    echo "<p>If you want to reinstall, please drop the table first.</p>";
    echo "<a href='../home.php'>Go to Admin Dashboard</a>";
    exit;
}

// Read SQL file
$sql_file = file_get_contents('settings_schema.sql');

if ($sql_file === false) {
    die("<h2 style='color: red;'>❌ Error: Cannot read settings_schema.sql file</h2>");
}

// Execute SQL (split by semicolon for multiple statements)
$statements = explode(';', $sql_file);
$success = true;
$errors = [];

foreach ($statements as $statement) {
    $statement = trim($statement);
    if (!empty($statement)) {
        if (!mysqli_query($conn, $statement)) {
            $success = false;
            $errors[] = mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Settings Installation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>
    <?php if ($success): ?>
        <h2 class="success">✅ Site Settings Installation Successful!</h2>
        <p><strong>Created:</strong></p>
        <ul>
            <li>Table: <code>site_settings</code></li>
            <li>Default configuration record</li>
        </ul>
        <p><strong>Next Steps:</strong></p>
        <ol>
            <li>Go to Admin Dashboard</li>
            <li>Navigate to "Website Settings"</li>
            <li>Update your site information</li>
        </ol>
        <a href="../home.php" class="btn">Go to Admin Dashboard</a>
    <?php else: ?>
        <h2 class="error">❌ Installation Failed!</h2>
        <p><strong>Errors:</strong></p>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li>
                    <?php echo htmlspecialchars($error); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="install_settings.php" class="btn">Try Again</a>
    <?php endif; ?>
</body>

</html>