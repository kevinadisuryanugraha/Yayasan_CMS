<?php
// Edit Vision & Mission Section Header
if (!defined('INDEX_AUTH')) {
    header("Location: index.php");
    exit;
}

// Fetch Section Data
$query = mysqli_query($conn, "SELECT * FROM about_vision_mission_section WHERE id = 1");
$data = mysqli_fetch_assoc($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];
    $subtitle = mysqli_real_escape_string($conn, trim($_POST['subtitle'] ?? ''));
    $title = mysqli_real_escape_string($conn, trim($_POST['title'] ?? ''));
    $description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));

    if (empty($title))
        $errors[] = "Judul Utama wajib diisi";
    if (empty($subtitle))
        $errors[] = "Subtitle wajib diisi";

    if (empty($errors)) {
        $update = mysqli_query($conn, "UPDATE about_vision_mission_section SET subtitle='$subtitle', title='$title', description='$description' WHERE id=1");

        if ($update) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Header Section berhasil diperbarui'];
            header("Location: ?page=vision_mission_about");
            exit;
        } else {
            $errors[] = "Database Error: " . mysqli_error($conn);
        }
    }

    // Set error alert jika ada
    if (!empty($errors)) {
        $error_list = '<ul class="text-left">';
        foreach ($errors as $err)
            $error_list .= "<li>$err</li>";
        $error_list .= '</ul>';
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan!',
                    html: '$error_list',
                    confirmButtonText: 'Tutup'
                });
            });
         </script>";
    }
}
?>

<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=vision_mission_about">Visi & Misi</a></li>
                        <li class="breadcrumb-item active">Edit Header</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Header Visi & Misi</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Form Utama -->
        <div class="col-lg-8">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Detail Header Section</h4>
                    <p class="text-muted m-b-30 font-14">
                        Perbarui informasi judul dan deskripsi yang muncul di atas kartu Visi & Misi.
                    </p>

                    <form method="POST" id="editHeaderForm">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Subtitle / Label <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="subtitle" name="subtitle"
                                    value="<?php echo htmlspecialchars($data['subtitle']); ?>" required
                                    placeholder="Contoh: Panduan Kami">
                                <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Teks
                                    kecil di atas judul utama.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Judul Utama <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" name="title"
                                    value="<?php echo htmlspecialchars($data['title']); ?>" required
                                    placeholder="Contoh: Visi & Misi">
                                <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Headline
                                    besar section ini.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Deskripsi Singkat</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="description" name="description"
                                    rows="3"><?php echo htmlspecialchars($data['description']); ?></textarea>
                                <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i>
                                    Penjelasan singkat di bawah judul (opsional).</small>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="mdi mdi-content-save"></i> Simpan Perubahan
                                </button>
                                <a href="?page=vision_mission_about" class="btn btn-secondary btn-lg btn-cancel">
                                    <i class="mdi mdi-arrow-left"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel Informasi & Preview -->
        <div class="col-lg-4">
            <!-- Info Data -->
            <div class="card m-b-30 border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-information mr-2"></i>Informasi Data</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" width="40%">ID Section:</td>
                            <td><strong>#<?php echo $data['id']; ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tipe Konten:</td>
                            <td><span class="badge badge-info">Static Content</span></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Live Preview -->
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-eye mr-2"></i>Preview Hasil</h5>
                </div>
                <div class="card-body text-center">
                    <div class="p-4 bg-light rounded">
                        <span id="preview-subtitle" class="d-block text-uppercase text-primary font-weight-bold mb-2"
                            style="font-size: 12px; letter-spacing: 1px;">
                            <?php echo htmlspecialchars($data['subtitle']); ?>
                        </span>
                        <h2 id="preview-title" class="mb-3 text-dark" style="font-weight: 700; font-size: 24px;">
                            <?php echo htmlspecialchars($data['title']); ?>
                        </h2>
                        <p id="preview-desc" class="text-muted mb-0" style="font-size: 14px; line-height: 1.6;">
                            <?php echo htmlspecialchars($data['description']); ?>
                        </p>
                    </div>
                    <small class="text-muted mt-2 d-block">* Preview tampilan dasar, hasil sebenarnya mengikuti tema
                        frontend.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Live Preview Logic
        $('#subtitle').on('input', function () {
            $('#preview-subtitle').text($(this).val() || 'Subtitle');
        });
        $('#title').on('input', function () {
            $('#preview-title').text($(this).val() || 'Judul Utama');
        });
        $('#description').on('input', function () {
            $('#preview-desc').text($(this).val());
        });

        // Cancel Button Confirmation
        $('.btn-cancel').on('click', function (e) {
            e.preventDefault();
            const href = $(this).attr('href');
            Swal.fire({
                title: 'Batalkan Perubahan?',
                text: "Perubahan yang belum disimpan akan hilang!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Lanjut Edit'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });

        // Form Submit Loader
        $('#editHeaderForm').on('submit', function () {
            Swal.fire({
                title: 'Menyimpan...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
    });
</script>