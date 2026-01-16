<?php
/**
 * Test Event Registration - Direct Test
 * Access this file directly to test registration
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'admin/koneksi.php';

echo "<h2>Event Registration Test</h2>";

// Check database connection
echo "<h3>1. Database Connection</h3>";
if ($conn) {
    echo "✅ Database connected successfully<br>";
} else {
    echo "❌ Database connection failed: " . mysqli_connect_error() . "<br>";
    exit;
}

// Check if table exists
echo "<h3>2. Table Check</h3>";
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'event_registrations'");
if (mysqli_num_rows($table_check) > 0) {
    echo "✅ Table 'event_registrations' exists<br>";
} else {
    echo "❌ Table 'event_registrations' does NOT exist!<br>";
    echo "<strong>Run this SQL file:</strong> database/event_registrations.sql<br>";
    exit;
}

// Check table structure
echo "<h3>3. Table Structure</h3>";
$cols = mysqli_query($conn, "SHOW COLUMNS FROM event_registrations");
echo "<pre>";
while ($col = mysqli_fetch_assoc($cols)) {
    echo $col['Field'] . " - " . $col['Type'] . "\n";
}
echo "</pre>";

// Check events
echo "<h3>4. Active Events</h3>";
$events = mysqli_query($conn, "SELECT id, title, event_date, price FROM events WHERE is_active = 1 AND status = 'published' LIMIT 5");
if (mysqli_num_rows($events) > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Title</th><th>Date</th><th>Price</th></tr>";
    while ($e = mysqli_fetch_assoc($events)) {
        echo "<tr><td>{$e['id']}</td><td>{$e['title']}</td><td>{$e['event_date']}</td><td>{$e['price']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "❌ No active events found<br>";
}

// Test form
echo "<h3>5. Test Registration (POST Form)</h3>";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "<strong>POST Data Received:</strong><pre>";
    print_r($_POST);
    echo "</pre>";

    // Try to insert
    $event_id = intval($_POST['event_id'] ?? 1);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name'] ?? 'Test');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? 'test@test.com');
    $phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '08123456789');
    $registration_code = 'TEST' . date('ymdHis');

    $sql = "INSERT INTO event_registrations (event_id, registration_code, full_name, email, phone, status, payment_status, payment_amount) 
            VALUES ($event_id, '$registration_code', '$full_name', '$email', '$phone', 'pending', 'unpaid', 0)";

    echo "<strong>SQL:</strong><pre>$sql</pre>";

    if (mysqli_query($conn, $sql)) {
        $id = mysqli_insert_id($conn);
        echo "✅ <strong style='color:green'>INSERT SUCCESS! ID: $id, Code: $registration_code</strong><br>";
    } else {
        echo "❌ <strong style='color:red'>INSERT FAILED: " . mysqli_error($conn) . "</strong><br>";
    }
}

// Show existing registrations
echo "<h3>6. Existing Registrations</h3>";
$regs = mysqli_query($conn, "SELECT * FROM event_registrations ORDER BY id DESC LIMIT 5");
if ($regs && mysqli_num_rows($regs) > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Code</th><th>Name</th><th>Email</th><th>Status</th><th>Created</th></tr>";
    while ($r = mysqli_fetch_assoc($regs)) {
        echo "<tr><td>{$r['id']}</td><td>{$r['registration_code']}</td><td>{$r['full_name']}</td><td>{$r['email']}</td><td>{$r['status']}</td><td>{$r['created_at']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "No registrations yet.<br>";
}
?>

<h3>7. Test Form</h3>
<form method="POST" style="background:#f5f5f5;padding:20px;border-radius:10px;max-width:400px;">
    <input type="hidden" name="event_id" value="1">
    <p>
        <label>Nama:</label><br>
        <input type="text" name="full_name" value="Test User" style="width:100%;padding:8px;">
    </p>
    <p>
        <label>Email:</label><br>
        <input type="email" name="email" value="test@example.com" style="width:100%;padding:8px;">
    </p>
    <p>
        <label>Phone:</label><br>
        <input type="tel" name="phone" value="08123456789" style="width:100%;padding:8px;">
    </p>
    <p>
        <button type="submit" style="background:#00997d;color:#fff;padding:10px 20px;border:none;cursor:pointer;">
            Test Insert
        </button>
    </p>
</form>