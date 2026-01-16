<?php
// Add Feature About
if (!defined('INDEX_AUTH')) {
    header("Location: index.php");
    exit;
}

// Proses Simpan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Validasi Text
    $text = isset($_POST['text']) ? trim($_POST['text']) : '';
    if (empty($text)) {
        $errors[] = 'Teks fitur wajib diisi';
    } elseif (strlen($text) > 255) {
        $errors[] = 'Teks maksimal 255 karakter';
    }
    $text = mysqli_real_escape_string($conn, $text);

    // Validasi Icon
    $icon = isset($_POST['icon']) ? trim($_POST['icon']) : '';
    if (empty($icon)) {
        $errors[] = 'Ikon wajib dipilih';
    }
    $icon = mysqli_real_escape_string($conn, $icon);

    // Validasi Sort Order
    $sort_order = isset($_POST['sort_order']) ? intval($_POST['sort_order']) : 0;

    if (empty($errors)) {
        $query = "INSERT INTO about_features (icon, text, sort_order) VALUES ('$icon', '$text', $sort_order)";
        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Fitur baru berhasil ditambahkan'];
            header("Location: ?page=features_about");
            exit;
        } else {
            $errors[] = 'Database Error: ' . mysqli_error($conn);
        }
    }

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
    }
}

// Error handling initialization
$form_errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);
$error_script = '';
if (!empty($form_errors)) {
    $error_list = '<ul style="text-align:left;margin:0;padding-left:20px;">';
    foreach ($form_errors as $error)
        $error_list .= '<li>' . htmlspecialchars($error) . '</li>';
    $error_list .= '</ul>';
    $error_script = "<script>Swal.fire({icon: 'error', title: 'Kesalahan Validasi', html: '$error_list'});</script>";
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=intro_section_about">Intro About</a></li>
                        <li class="breadcrumb-item"><a href="?page=features_about">Fitur</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div>
                <h4 class="page-title">Tambah Fitur Baru</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Form Panel -->
        <div class="col-lg-8">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title pb-3">Formulir Fitur</h4>

                    <form method="POST" id="featureForm">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Teks Keunggulan <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="text" id="text" required
                                    placeholder="Contoh: Pendidikan Berkualitas" maxlength="255">
                                <small class="text-muted">Teks singkat yang menjelaskan keunggulan.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Pilih Ikon <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control select2-icon" name="icon" id="icon" style="width: 100%;">
                                    <option value="">-- Cari Ikon --</option>
                                    <?php
                                    $icons = [
                                        'icofont-check' => 'Centang Normal',
                                        'icofont-check-circled' => 'Centang Bulat',
                                        'icofont-star' => 'Bintang',
                                        'icofont-like' => 'Jempol / Suka',
                                        'icofont-chart-growth' => 'Grafik Pertumbuhan',
                                        'icofont-users-alt-2' => 'Pengguna / User',
                                        'icofont-shield' => 'Perisai / Keamanan',
                                        'icofont-book-alt' => 'Buku',
                                        'icofont-graduate-alt' => 'Topi Wisuda',
                                        'icofont-teacher' => 'Pengajar / Guru',
                                        'icofont-group' => 'Kelompok / Group',
                                        'icofont-trophy' => 'Piala / Penghargaan',
                                        'icofont-calendar' => 'Kalender',
                                        'icofont-building-alt' => 'Gedung',
                                        'icofont-globe' => 'Bola Dunia'
                                    ];
                                    foreach ($icons as $value => $label) {
                                        echo "<option value='$value'>$label</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Urutan</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="sort_order" value="0" min="0">
                                <small class="text-muted">Semakin kecil angkanya, semakin awal munculnya.</small>
                            </div>
                        </div>

                        <div class="form-group row m-t-20">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="mdi mdi-check-circle"></i> Simpan Fitur
                                </button>
                                <a href="?page=features_about" class="btn btn-secondary btn-lg btn-cancel">
                                    <i class="mdi mdi-arrow-left"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview Panel -->
        <div class="col-lg-4">
            <div class="card m-b-30 bg-light border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0"><i class="mdi mdi-eye"></i> Live Preview</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center p-3 bg-white rounded shadow-sm">
                        <div class="mr-3">
                            <i id="preview-icon" class="icofont-check text-success font-24"></i>
                        </div>
                        <div>
                            <span id="preview-text" class="font-weight-bold text-dark font-16">Pendidikan
                                Berkualitas</span>
                        </div>
                    </div>
                    <p class="text-center mt-3 text-muted small">
                        Ini adalah simulasi tampilan item di daftar fitur.
                    </p>
                </div>
            </div>

            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="m-0"><i class="mdi mdi-lightbulb-on"></i> Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0 pl-3">
                        <li class="mb-2">Gunakan kaliamat singkat & padat (2-4 kata).</li>
                        <li class="mb-2">Pilih ikon yang relevan dengan teks.</li>
                        <li class="mb-0">Atur urutan agar poin terpenting muncul duluan.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $error_script; ?>

<script>
    $(document).ready(function () {
        // Init Select2 with Icon
        function formatIcon(state) {
            if (!state.id) return state.text;
            return $('<span><i class="' + state.element.value + ' mr-2"></i> ' + state.text + '</span>');
        }
        $('.select2-icon').select2({
            templateResult: formatIcon,
            templateSelection: formatIcon
        });

        // Live Preview
        $('#text').on('input', function () {
            var val = $(this).val();
            $('#preview-text').text(val || 'Pendidikan Berkualitas');
        });

        $('#icon').on('change select2:select', function () {
            var val = $(this).val();
            $('#preview-icon').attr('class', (val || 'icofont-check') + ' text-success font-24');
        });

        // Cancel Confirmation
        $('.btn-cancel').on('click', function (e) {
            e.preventDefault();
            const href = $(this).attr('href');
            Swal.fire({
                title: 'Batalkan?',
                text: "Data yang diisi akan hilang.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Batalkan'
            }).then((result) => {
                if (result.isConfirmed) window.location.href = href;
            });
        });

        // Submit Loader
        $('#featureForm').on('submit', function () {
            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
        });
    });
</script>