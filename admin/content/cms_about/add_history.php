<?php
// Add History Item CMS
if (!defined('INDEX_AUTH')) {
    header("Location: index.php");
    exit;
}

$errors = [];
$success = false;

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
        $query = "INSERT INTO about_history_items (year, title, description, sort_order) VALUES ('$year', '$title', '$description', '$sort_order')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'History item baru berhasil ditambahkan.'];
            echo "<script>window.location='?page=history_about';</script>";
            exit;
        } else {
            $errors[] = "Database Error: " . mysqli_error($conn);
        }
    }
}

// Error Handling to retain input
if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
}

$form_data = $_SESSION['form_data'] ?? [];
$form_errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_data']);
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
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div>
                <h4 class="page-title">Tambah Event Sejarah</h4>
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
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#dc3545'
                });
            });
        </script>
    <?php endif; ?>

    <form method="post" action="" id="addHistoryForm">
        <div class="row">
            <!-- Left Column: Form Input -->
            <div class="col-md-8">
                <div class="card m-b-30">
                    <div class="card-header bg-white">
                        <h5 class="m-0 text-primary"><i class="mdi mdi-playlist-plus mr-2"></i>Form Input Event</h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tahun</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="year" id="year"
                                    placeholder="Contoh: 2018"
                                    value="<?php echo htmlspecialchars($form_data['year'] ?? ''); ?>" required>
                                <small class="text-muted">Masukkan tahun kejadian (4 digit).</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Judul Event</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="title" id="title"
                                    placeholder="Judul singkat event..."
                                    value="<?php echo htmlspecialchars($form_data['title'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="description" id="description" rows="4"
                                    placeholder="Jelaskan apa yang terjadi di tahun tersebut..."
                                    required><?php echo htmlspecialchars($form_data['description'] ?? ''); ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Urutan</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="sort_order"
                                    value="<?php echo htmlspecialchars($form_data['sort_order'] ?? '0'); ?>">
                                <small class="text-muted">Semakin kecil angkanya, semakin awal munculnya.</small>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group row mb-0">
                            <div class="col-sm-12 text-right">
                                <a href="?page=history_about" class="btn btn-secondary btn-cancel">Batal</a>
                                <button type="submit" name="submit" class="btn btn-primary"><i
                                        class="mdi mdi-content-save mr-1"></i> Simpan Event</button>
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
                        <h5 class="m-0"><i class="mdi mdi-eye mr-2"></i>Preview Hasil</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="text-muted text-center mb-3">Tampilan di Website (Timeline Item)</h6>

                        <!-- Timeline Simulation -->
                        <div class="p-3 bg-white rounded shadow-sm border-left border-primary"
                            style="border-left-width: 4px !important;">
                            <h5 class="text-primary font-weight-bold mb-1" id="preview_year">20XX</h5>
                            <h5 class="mt-0 mb-2 font-16" id="preview_title">Judul Event</h5>
                            <p class="text-muted mb-0 small" id="preview_description">
                                Deskripsi event akan muncul di sini. Pastikan kalimatnya menarik dan mudah dibaca oleh
                                pengunjung.
                            </p>
                        </div>

                        <div class="text-center mt-3">
                            <small class="text-info"><i class="mdi mdi-information"></i> Ini adalah simulasi visual
                                sederhana.</small>
                        </div>
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="card m-b-30 border-warning">
                    <div class="card-header bg-warning text-white">
                        <h5 class="m-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Tips Pengisian</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-2"><strong><i class="mdi mdi-check text-success"></i>
                                    Relevansi:</strong><br>Pastikan event yang dimasukkan benar-benar momen penting bagi
                                yayasan.</li>
                            <li class="mb-2"><strong><i class="mdi mdi-check text-success"></i> Singkat &
                                    Padat:</strong><br>Pengunjung lebih suka membaca poin-poin ringkas daripada paragraf
                                panjang.</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Live Preview Logic
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
                    previewEl.textContent = this.value || (inputId === 'year' ? '20XX' : (inputId === 'title' ? 'Judul Event' : 'Deskripsi event...'));
                });
            }
        });

        // Cancel Confirmation
        const btnCancel = document.querySelector('.btn-cancel');
        if (btnCancel) {
            btnCancel.addEventListener('click', function (e) {
                e.preventDefault();
                const href = this.getAttribute('href');

                // Cek apakah ada inputan
                const hasInput = document.getElementById('title').value.length > 0 ||
                    document.getElementById('description').value.length > 0;

                if (hasInput) {
                    Swal.fire({
                        title: 'Batalkan Pengisian?',
                        text: "Data yang sudah diisi akan hilang.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Batalkan',
                        cancelButtonText: 'Lanjut Isi',
                        confirmButtonColor: '#dc3545',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = href;
                        }
                    });
                } else {
                    window.location.href = href;
                }
            });
        }

        // Submit Loader
        const form = document.getElementById('addHistoryForm');
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