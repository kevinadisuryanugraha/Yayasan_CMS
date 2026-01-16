<?php
// Delete Intro Section Logic
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ?page=intro_section_about");
    exit;
}

$id = intval($_GET['id']);

// Ambil info gambar sebelum hapus
$query = mysqli_query($conn, "SELECT image FROM about_section WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if ($data) {
    $delete_query = "DELETE FROM about_section WHERE id = $id";
    if (mysqli_query($conn, $delete_query)) {
        // Hapus file gambar jika ada
        if (!empty($data['image']) && file_exists('../' . $data['image'])) {
            unlink('../' . $data['image']);
        }

        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Terhapus!',
            'message' => 'Data intro berhasil dihapus.'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Gagal!',
            'message' => 'Terjadi kesalahan database.'
        ];
    }
} else {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Gagal!',
        'message' => 'Data tidak ditemukan.'
    ];
}

header("Location: ?page=intro_section_about");
exit;
?>