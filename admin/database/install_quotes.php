<?php
session_start();
include '../koneksi.php';

// Read and execute SQL file
$sql_file = __DIR__ . '/quotes_section.sql';
$sql = file_get_contents($sql_file);

if ($sql === false) {
    die("Error: Could not read SQL file");
}

// Execute SQL
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
    <title>Quote Section - Database Installer</title>
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
    </style>
</head>

<body>
    <h1>Quote Section - Database Installation</h1>

    <?php if ($success): ?>
        <div class="success">
            <h2>✓ Installation Successful!</h2>
            <p>Database table created: <strong>quotes</strong></p>
            <p>3 sample hadith/quotes have been inserted.</p>
        </div>
        <p><a href="../home.php?page=quotes">→ Go to Quotes Admin</a></p>
    <?php else: ?>
        <div class="error">
            <h2>✗ Installation Failed</h2>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li>
                        <?php echo htmlspecialchars($error); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</body>

</html>