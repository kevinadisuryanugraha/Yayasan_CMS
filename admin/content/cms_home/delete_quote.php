<?php
// Delete Quote
// Table: quotes

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Error!', 'message' => 'Quote ID is required'];
    header("Location: ?page=quotes");
    exit;
}

$id = intval($_GET['id']);

$check = mysqli_query($conn, "SELECT * FROM quotes WHERE id = $id");
if (mysqli_num_rows($check) == 0) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Not Found!', 'message' => 'Quote does not exist'];
    header("Location: ?page=quotes");
    exit;
}

$delete = mysqli_query($conn, "DELETE FROM quotes WHERE id = $id");

if ($delete) {
    $_SESSION['alert'] = ['type' => 'success', 'title' => 'Deleted!', 'message' => 'Quote deleted successfully'];
} else {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Error!', 'message' => mysqli_error($conn)];
}

header("Location: ?page=quotes");
exit;
?>