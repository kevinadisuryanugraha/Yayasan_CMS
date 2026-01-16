<?php
// Delete Faith Pillar
// Table: faith_pillars

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Error!', 'message' => 'Pillar ID is required'];
    header("Location: ?page=faith_pillars");
    exit;
}

$id = intval($_GET['id']);

$check = mysqli_query($conn, "SELECT * FROM faith_pillars WHERE id = $id");
if (mysqli_num_rows($check) == 0) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Not Found!', 'message' => 'Pillar does not exist'];
    header("Location: ?page=faith_pillars");
    exit;
}

$pillar = mysqli_fetch_assoc($check);

// Delete main image if exists
if (!empty($pillar['main_image']) && file_exists('../' . $pillar['main_image'])) {
    unlink('../' . $pillar['main_image']);
}

// Delete tab icon if exists
if (!empty($pillar['tab_icon']) && file_exists('../' . $pillar['tab_icon'])) {
    unlink('../' . $pillar['tab_icon']);
}

// Delete from database
$delete = mysqli_query($conn, "DELETE FROM faith_pillars WHERE id = $id");

if ($delete) {
    $_SESSION['alert'] = ['type' => 'success', 'title' => 'Deleted!', 'message' => 'Pillar deleted successfully'];
} else {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Error!', 'message' => mysqli_error($conn)];
}

header("Location: ?page=faith_pillars");
exit;
?>