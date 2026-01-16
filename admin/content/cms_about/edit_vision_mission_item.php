<?php
// Edit Vision & Mission Item
if (!defined('INDEX_AUTH')) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);
$query = mysqli_query($conn, "SELECT * FROM about_vision_mission_items WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: ?page=vision_mission_about");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];
    $title = mysqli_real_escape_string($conn, trim($_POST['title'] ?? ''));
    $description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $icon = mysqli_real_escape_string($conn, trim($_POST['icon'] ?? ''));
    $list_items = mysqli_real_escape_string($conn, trim($_POST['list_items'] ?? ''));
    $sort_order = intval($_POST['sort_order'] ?? 0);

    if (empty($title))
        $errors[] = "Judul wajib diisi";
    if (empty($icon))
        $errors[] = "Ikon wajib dipilih";

    if (empty($errors)) {
        $query = "UPDATE about_vision_mission_items SET icon='$icon', title='$title', description='$description', list_items='$list_items', sort_order=$sort_order WHERE id=$id";
        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Item berhasil diperbarui'];
            header("Location: ?page=vision_mission_about");
            exit;
        } else {
            $errors[] = "Database Error: " . mysqli_error($conn);
        }
    }

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
    }
}

// Prepare Data for View (Preserve input on error)
$form_data = $_SESSION['form_data'] ?? $data; // Use DB data by default, or POST data if error
unset($_SESSION['form_data']);
$form_errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);

// Error Alert Script
$error_script = '';
if (!empty($form_errors)) {
    $error_list = '<ul style="text-align:left;margin:0;padding-left:20px;">';
    foreach ($form_errors as $error) {
        $error_list .= '<li>' . htmlspecialchars($error) . '</li>';
    }
    $error_list .= '</ul>';
    $error_script = "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                html: '" . addslashes($error_list) . "',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#dc3545'
            });
        });
    </script>
    ";
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=vision_mission_about">Visi & Misi</a></li>
                        <li class="breadcrumb-item active">Edit Item</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Item Visi & Misi</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Form Utama -->
        <div class="col-lg-8">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Formulir Edit Item</h4>
                    <p class="text-muted m-b-30 font-14">
                        Perbarui detail item ini.
                    </p>

                    <form method="POST" id="editItemForm">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Judul Kartu <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" name="title" required
                                    value="<?php echo htmlspecialchars($form_data['title']); ?>"
                                    placeholder="Contoh: Visi Kami">
                                <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Judul
                                    utama yang muncul di kartu.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Pilih Ikon <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control select2-icon" id="icon" name="icon" style="width: 100%;">
                                    <option value="">-- Pilih Ikon --</option>
                                    <?php
                                    $icons = [
                                        'Umum' => [
                                            'icofont-eye-open' => 'Mata (Visi)',
                                            'icofont-flag-alt-2' => 'Bendera (Misi)',
                                            'icofont-star' => 'Bintang',
                                            'icofont-heart' => 'Hati'
                                        ],
                                        'Lainnya' => [
                                            'icofont-check-circled' => 'Centang Bulat',
                                            'icofont-book' => 'Buku',
                                            'icofont-group' => 'Kelompok',
                                            'icofont-chart-growth' => 'Grafik Naik',
                                            'icofont-globe' => 'Bola Dunia'
                                        ]
                                    ];
                                    $current_icon = $form_data['icon'] ?? '';
                                    foreach ($icons as $group => $items) {
                                        echo "<optgroup label='$group'>";
                                        foreach ($items as $k => $v) {
                                            $sel = ($current_icon == $k) ? 'selected' : '';
                                            echo "<option value='$k' $sel>$v</option>";
                                        }
                                        echo "</optgroup>";
                                    }
                                    ?>
                                </select>
                                <small class="form-text text-muted mt-2"><i class="mdi mdi-information-outline"></i>
                                    Visualisasi untuk kartu ini.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="description" name="description" rows="3"
                                    placeholder="Deskripsi singkat..."><?php echo htmlspecialchars($form_data['description']); ?></textarea>
                                <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i>
                                    Penjelasan teks paragraf (opsional jika menggunakan list).</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Daftar Poin</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="list_items" name="list_items" rows="5"
                                    placeholder="Satu poin per baris..."><?php echo htmlspecialchars($form_data['list_items']); ?></textarea>
                                <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Tekan
                                    Enter untuk membuat poin baru. Muncul sebagai checklist.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Urutan</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="sort_order"
                                    value="<?php echo intval($form_data['sort_order']); ?>">
                                <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Angka
                                    lebih kecil muncul lebih dulu.</small>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary btn-lg"><i
                                        class="mdi mdi-content-save"></i> Simpan Perubahan</button>
                                <a href="?page=vision_mission_about" class="btn btn-secondary btn-lg btn-cancel"><i
                                        class="mdi mdi-arrow-left"></i> Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview & Info -->
        <div class="col-lg-4">
            <!-- Tips Card -->
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-help-circle mr-2"></i>Panduan Pengisian</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-1-circle text-primary mr-1"></i>Judul & Ikon</strong>
                        <p class="text-muted small mb-0">Wajib diisi. Pilih ikon yang relevan agar mudah dikenali.</p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-2-circle text-primary mr-1"></i>Deskripsi</strong>
                        <p class="text-muted small mb-0">Gunakan untuk penjabaran kalimat pendek.</p>
                    </div>
                    <div class="mb-0">
                        <strong><i class="mdi mdi-numeric-3-circle text-primary mr-1"></i>Daftar Poin</strong>
                        <p class="text-muted small mb-0">Gunakan untuk rincian (misal: butir-butir Misi). Setiap baris
                            baru otomatis jadi poin.</p>
                    </div>
                </div>
            </div>

            <!-- Live Preview Card -->
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-eye mr-2"></i>Preview Hasil</h5>
                </div>
                <div class="card-body text-center">
                    <div class="p-4 bg-light rounded vm-card-preview">
                        <div class="vm-card-icon mb-3">
                            <i id="preview-icon"
                                class="<?php echo htmlspecialchars($form_data['icon']); ?> text-primary"
                                style="font-size: 40px;"></i>
                        </div>
                        <h4 id="preview-title" class="mb-3" style="font-weight: 700;">
                            <?php echo htmlspecialchars($form_data['title']); ?></h4>
                        <p id="preview-desc" class="text-muted small">
                            <?php echo htmlspecialchars($form_data['description'] ?: 'Deskripsi item akan muncul di sini.'); ?>
                        </p>
                        <ul id="preview-list" class="text-left pl-3 small text-muted">
                            <?php
                            if (isset($form_data['list_items']) && !empty($form_data['list_items'])) {
                                foreach (explode("\n", $form_data['list_items']) as $li) {
                                    if (trim($li))
                                        echo '<li><i class="icofont-check-circled text-success"></i> ' . htmlspecialchars(trim($li)) . '</li>';
                                }
                            } else {
                                echo '<li><i class="icofont-check-circled text-success"></i> Contoh Poin 1</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $error_script; ?>

<script>
    $(document).ready(function () {
        // Init Select2
        function formatIcon(state) {
            if (!state.id) return state.text;
            return $('<span><i class="' + state.element.value + ' mr-2"></i> ' + state.text + '</span>');
        }

        $('.select2-icon').select2({
            templateResult: formatIcon,
            templateSelection: formatIcon,
            width: '100%'
        });

        // Live Preview Logic
        $('#title').on('input', function () {
            $('#preview-title').text($(this).val() || 'Judul Header');
        });

        $('#description').on('input', function () {
            $('#preview-desc').text($(this).val() || 'Deskripsi item akan muncul di sini.');
        });

        $('#icon').on('change', function () {
            var iconClass = $(this).val();
            if (iconClass) {
                $('#preview-icon').attr('class', iconClass + ' text-primary');
            } else {
                $('#preview-icon').attr('class', 'icofont-eye-open text-primary');
            }
        });

        $('#list_items').on('input', function () {
            var text = $(this).val();
            var html = '';
            if (text) {
                var lines = text.split('\n');
                lines.forEach(function (line) {
                    if (line.trim()) {
                        html += '<li><i class="icofont-check-circled text-success"></i> ' + $('<div>').text(line).html() + '</li>';
                    }
                });
            } else {
                html += '<li><i class="icofont-check-circled text-success"></i> Contoh Poin 1</li>';
            }
            $('#preview-list').html(html);
        });

        // Cancel Confirmation
        $('.btn-cancel').on('click', function (e) {
            e.preventDefault();
            const href = $(this).attr('href');
            Swal.fire({
                title: 'Batalkan Perubahan?',
                text: "Perubahan yang belum disimpan akan hilang.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Lanjut Edit',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });

        // Form Submit Loader
        $('#editItemForm').on('submit', function () {
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