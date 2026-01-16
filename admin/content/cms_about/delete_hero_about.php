<?php
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ?page=hero_section_about");
    exit;
}

$id = intval($_GET['id']);

if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
    $query = "DELETE FROM about_hero WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Terhapus!',
            'message' => 'Data berhasil dihapus.'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Gagal!',
            'message' => 'Gagal menghapus data.'
        ];
    }
}

header("Location: ?page=hero_section_about");
exit;
?>