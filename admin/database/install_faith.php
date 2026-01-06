<?php
session_start();
include '../koneksi.php';

// Read and execute SQL file
$sql_file = __DIR__ . '/faith_section.sql';
$sql = file_get_contents($sql_file);

if ($sql === false) {
    die("Error: Could not read SQL file");
}

// Execute SQL (split by semicolon for multiple statements)
$statements = array_filter(array_map('trim', explode(';', $sql)));

$success = true;
$errors = [];

foreach ($statements as $statement) {
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
    <title>Faith Section - Database Installer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }

        .success {
            color: green;
            padding: 15px;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
        }

        .error {
            color: red;
            padding: 15px;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
        }

        .info {
            padding: 10px;
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 4px;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <h1>Faith Section - Database Installation</h1>

    <?php if ($success): ?>
        <div class="success">
            <h2>✓ Installation Successful!</h2>
            <p>Database tables created:</p>
            <ul>
                <li><strong>faith_header</strong> - Section header (subtitle, title)</li>
                <li><strong>faith_pillars</strong> - 5 Islamic Pillars with dual images</li>
            </ul>
            <p>Sample data has been inserted for all 5 pillars.</p>
        </div>

        <div class="info">
            <h3>Next Steps:</h3>
            <ol>
                <li>Go to Admin Panel → Faith Section</li>
                <li>Edit header text if needed</li>
                <li>Upload custom images for each pillar</li>
                <li>Update descriptions as needed</li>
            </ol>
        </div>

        <p><a href="../home.php?page=faith_pillars">→ Go to Faith Section Admin</a></p>

    <?php else: ?>
        <div class="error">
            <h2>✗ Installation Failed</h2>
            <p>The following errors occurred:</p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li>
                        <?php echo htmlspecialchars($error); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <p><a href="install_faith.php">← Try Again</a></p>
    <?php endif; ?>
</body>

</html>