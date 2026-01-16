<?php
// Edit History Item CMS
if (!defined('INDEX_AUTH')) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);
$errors = [];

// Fetch Existing Data
$query = "SELECT * FROM about_history_items WHERE id = $id";
$result = mysqli_query($conn, $query);
$item = mysqli_fetch_assoc($result);

if (!$item) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Error!', 'message' => 'Data tidak ditemukan.'];
    echo "<script>window.location='?page=history_about';</script>";
    exit;
}

// Handle Form Submission
if (isset($_POST['submit'])) {
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $sort_order = intval($_POST['sort_order']);

    // Validasi
    if (empty($year))
        $errors[] = "Tahun wajib diisi.";
    if (empty($title))
        $errors[] = "Judul Event wajib diisi.";
    if (empty($description))
        $errors[] = "Deskripsi wajib diisi.";

    if (empty($errors)) {
        $update_query = "UPDATE about_history_items SET 
            year = '$year', 
            title = '$title', 
            description = '$description', 
            sort_order = '$sort_order' 
            WHERE id = $id";

        if (mysqli_query($conn, $update_query)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil Update!', 'message' => 'Perubahan berhasil disimpan.'];
            echo "<script>window.location='?page=history_about';</script>";
            exit;
        } else {
            $errors[] = "Database Error: " . mysqli_error($conn);
        }
    }
}

// Error Handling Persistence
if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
}
$form_errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=intro_section_about">About</a></li>
                        <li class="breadcrumb-item"><a href="?page=history_about">Sejarah</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Event Sejarah</h4>
            </div>
        </div>
    </div>

    <!-- Error Alert Script -->
    <?php if (!empty($form_errors)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let errorHtml = '<ul style="text-align:left;margin:0;padding-left:20px;">';
                <?php foreach ($form_errors as $error): ?>
                        errorHtml += '<li><?php echo $error; ?></li>';
                <?php endforeach; ?>
                    errorHtml += '</ul>';

                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    html: errorHtml,
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#dc3545'
                });
            });
        </script>
    <?php endif; ?>

    <form method="post" action="" id="editHistoryForm">
        <div class="row">
            <!-- Left Column: Form Input -->
            <div class="col-md-8">
                <div class="card m-b-30">
                    <div class="card-header bg-white">
                        <h5 class="m-0 text-primary"><i class="mdi mdi-pencil-box mr-2"></i>Edit Data Event</h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tahun</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="year" id="year"
                                    value="<?php echo htmlspecialchars($_POST['year'] ?? $item['year']); ?>" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Judul Event</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="title" id="title"
                                    value="<?php echo htmlspecialchars($_POST['title'] ?? $item['title']); ?>" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="description" id="description" rows="4"
                                    required><?php echo htmlspecialchars($_POST['description'] ?? $item['description']); ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Urutan</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="sort_order"
                                    value="<?php echo htmlspecialchars($_POST['sort_order'] ?? $item['sort_order']); ?>">
                            </div>
                        </div>

                        <hr>
                        <div class="form-group row mb-0">
                            <div class="col-sm-12 text-right">
                                <a href="?page=history_about" class="btn btn-secondary btn-cancel">Batal</a>
                                <button type="submit" name="submit" class="btn btn-primary"><i
                                        class="mdi mdi-content-save mr-1"></i> Simpan Perubahan</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right Column: Live Preview & Tips -->
            <div class="col-md-4">

                <!-- Live Preview Card -->
                <div class="card m-b-30 bg-light border-info">
                    <div class="card-header bg-info text-white">
                        <h5 class="m-0"><i class="mdi mdi-eye mr-2"></i>Preview Perubahan</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="text-muted text-center mb-3">Tampilan di Website</h6>

                        <!-- Timeline Simulation -->
                        <div class="p-3 bg-white rounded shadow-sm border-left border-primary"
                            style="border-left-width: 4px !important;">
                            <h5 class="text-primary font-weight-bold mb-1" id="preview_year">
                                <?php echo htmlspecialchars($item['year']); ?>
                            </h5>
                            <h5 class="mt-0 mb-2 font-16" id="preview_title">
                                <?php echo htmlspecialchars($item['title']); ?>
                            </h5>
                            <p class="text-muted mb-0 small" id="preview_description">
                                <?php echo htmlspecialchars($item['description']); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tips -->
                <div class="card m-b-30 border-warning">
                    <div class="card-header bg-warning text-white">
                        <h5 class="m-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Info Update</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-muted small">
                            Anda sedang mengedit data history. Pastikan untuk selalu memeriksa preview di kolom ini
                            sebelum menyimpan perubahan agar tampilan sesuai dengan harapan.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Live Preview Logic (Sama dengan Add)
        const inputs = {
            'year': 'preview_year',
            'title': 'preview_title',
            'description': 'preview_description'
        };

        Object.keys(inputs).forEach(inputId => {
            const inputEl = document.getElementById(inputId);
            const previewEl = document.getElementById(inputs[inputId]);

            if (inputEl) {
                inputEl.addEventListener('input', function () {
                    previewEl.textContent = this.value || '...';
                });
            }
        });

        // Cancel Confirmation
        const btnCancel = document.querySelector('.btn-cancel');
        if (btnCancel) {
            btnCancel.addEventListener('click', function (e) {
                e.preventDefault();
                const href = this.getAttribute('href');

                Swal.fire({
                    title: 'Batalkan Perubahan?',
                    text: "Perubahan yang belum disimpan akan hilang.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Batalkan',
                    cancelButtonText: 'Lanjut Edit',
                    confirmButtonColor: '#dc3545',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });
        }

        // Submit Loader
        const form = document.getElementById('editHistoryForm');
        if (form) {
            form.addEventListener('submit', function () {
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });
        }
    });
</script>