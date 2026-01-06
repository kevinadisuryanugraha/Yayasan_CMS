<?php
session_start();
include '../koneksi.php';

$sql_file = __DIR__ . '/events_section.sql';
$sql = file_get_contents($sql_file);

if ($sql === false) {
    die("Error: Could not read SQL file");
}

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
    <title>Events Section - Database Installer</title>
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
    <h1>Events Section - Database Installation</h1>

    <?php if ($success): ?>
        <div class="success">
            <h2>✓ Installation Successful!</h2>
            <p>Database tables created:</p>
            <ul>
                <li><strong>events_header</strong> - Section header (subtitle, title)</li>
                <li><strong>events</strong> - Event items with featured support & countdown</li>
            </ul>
            <p>Sample data: 1 featured event + 3 regular events</p>
        </div>
        <p><a href="../home.php?page=events">→ Go to Events Admin</a></p>
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