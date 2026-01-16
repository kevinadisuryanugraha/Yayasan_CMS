<?php
// Edit Kutipan

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'ID Tidak Valid!', 'message' => 'ID kutipan diperlukan'];
    header("Location: ?page=quotes");
    exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM quotes WHERE id = $id");
if (mysqli_num_rows($query) == 0) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Tidak Ditemukan!', 'message' => 'Kutipan tidak ditemukan'];
    header("Location: ?page=quotes");
    exit;
}

$quote = mysqli_fetch_assoc($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Validasi Teks Kutipan (wajib)
    $quote_text = isset($_POST['quote_text']) ? trim($_POST['quote_text']) : '';
    if (empty($quote_text)) {
        $errors[] = 'Teks kutipan wajib diisi';
    } elseif (strlen($quote_text) > 1000) {
        $errors[] = 'Teks kutipan maksimal 1000 karakter';
    } elseif (strlen($quote_text) < 10) {
        $errors[] = 'Teks kutipan minimal 10 karakter';
    }
    $quote_text = mysqli_real_escape_string($conn, $quote_text);

    // Validasi Penulis
    $author = isset($_POST['author']) ? trim($_POST['author']) : '';
    if (strlen($author) > 150) {
        $errors[] = 'Nama penulis maksimal 150 karakter';
    }
    $author = mysqli_real_escape_string($conn, $author);

    // Validasi Sumber
    $source = isset($_POST['source']) ? trim($_POST['source']) : '';
    if (strlen($source) > 255) {
        $errors[] = 'Sumber kutipan maksimal 255 karakter';
    }
    $source = mysqli_real_escape_string($conn, $source);

    // Validasi Urutan
    $order_position = isset($_POST['order_position']) ? intval($_POST['order_position']) : 1;
    if ($order_position < 1 || $order_position > 100) {
        $errors[] = 'Urutan harus antara 1 - 100';
    }

    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Simpan ke database
    if (empty($errors)) {
        $update = "UPDATE quotes SET 
                   quote_text = '$quote_text', author = '$author', source = '$source',
                   order_position = $order_position, is_active = $is_active
                   WHERE id = $id";

        if (mysqli_query($conn, $update)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Kutipan berhasil diperbarui'];
            header("Location: ?page=quotes");
            exit;
        } else {
            $errors[] = 'Gagal menyimpan: ' . mysqli_error($conn);
        }
    }

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
    }
}

$form_errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);

$error_script = '';
if (!empty($form_errors)) {
    $error_list = '<ul style="text-align:left;margin:0;padding-left:20px;">';
    foreach ($form_errors as $error)
        $error_list .= '<li>' . htmlspecialchars($error) . '</li>';
    $error_list .= '</ul>';
    $error_script = "<script>document.addEventListener('DOMContentLoaded', function() { Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan!', html: '" . addslashes($error_list) . "', confirmButtonText: 'Mengerti', confirmButtonColor: '#dc3545' }); });</script>";
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=quotes">Kutipan</a></li>
                        <li class="breadcrumb-item active">Ubah</li>
                    </ol>
                </div>
                <h4 class="page-title">Ubah Kutipan</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mt-0 header-title">Detail Kutipan</h4>
                            <p class="text-muted mb-0 font-14">Perbarui informasi kutipan</p>
                        </div>
                        <span class="badge badge-<?php echo $quote['is_active'] ? 'success' : 'secondary'; ?> p-2">
                            <?php echo $quote['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                        </span>
                    </div>

                    <form method="POST" action="" id="quoteForm">
                        <div class="form-group">
                            <label for="quote_text">Teks Kutipan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="quote_text" name="quote_text" rows="4" required
                                minlength="10"
                                maxlength="1000"><?php echo htmlspecialchars($quote['quote_text']); ?></textarea>
                            <small class="form-text text-muted">
                                <i class="mdi mdi-information-outline"></i> Kata-kata inspiratif (10-1000 karakter)
                                <span class="float-right" id="charCount">0/1000</span>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="author">Penulis / Sumber Kutipan</label>
                            <input type="text" class="form-control" id="author" name="author"
                                value="<?php echo htmlspecialchars($quote['author'] ?? ''); ?>"
                                placeholder="Contoh: Nabi Muhammad SAW" maxlength="150">
                            <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Nama penulis
                                kutipan (opsional)</small>
                        </div>

                        <div class="form-group">
                            <label for="source">Referensi / Kitab</label>
                            <input type="text" class="form-control" id="source" name="source"
                                value="<?php echo htmlspecialchars($quote['source'] ?? ''); ?>"
                                placeholder="Contoh: HR. Bukhari" maxlength="255">
                            <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Sumber atau
                                referensi (opsional)</small>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-cog mr-1"></i>Pengaturan Tampilan</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order_position">Urutan Tampilan</label>
                                    <input type="number" class="form-control" id="order_position" name="order_position"
                                        value="<?php echo $quote['order_position'] ?? 1; ?>" min="1" max="100"
                                        style="width: 100px;">
                                    <small class="form-text text-muted">Nilai kecil tampil lebih dulu</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <div class="custom-control custom-switch mt-2">
                                        <input type="checkbox" class="custom-control-input" id="is_active"
                                            name="is_active" <?php echo $quote['is_active'] ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="is_active">
                                            <span class="badge badge-success">Aktif</span> - Tampilkan di slider
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="mdi mdi-content-save"></i> Simpan Perubahan
                        </button>
                        <a href="?page=quotes" class="btn btn-secondary btn-lg btn-cancel">
                            <i class="mdi mdi-arrow-left"></i> Batal
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Preview -->
            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-eye mr-2"></i>Preview Kutipan</h5>
                </div>
                <div class="card-body">
                    <div class="p-4 bg-light rounded">
                        <div class="text-center">
                            <i class="mdi mdi-format-quote-open text-primary" style="font-size: 30px;"></i>
                        </div>
                        <p class="mb-3 text-center font-italic">
                            <?php echo htmlspecialchars($quote['quote_text']); ?>
                        </p>
                        <p class="mb-0 text-right">
                            <strong class="text-primary">—
                                <?php echo htmlspecialchars($quote['author'] ?? 'Anonim'); ?></strong>
                            <?php if (!empty($quote['source'])): ?>
                                <br><small class="text-muted"><?php echo htmlspecialchars($quote['source']); ?></small>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Informasi Data -->
            <div class="card m-b-30 border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-information mr-2"></i>Informasi Data</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">ID:</td>
                            <td><strong>#<?php echo $quote['id']; ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Urutan:</td>
                            <td><span class="badge badge-info"><?php echo $quote['order_position'] ?? 1; ?></span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status:</td>
                            <td>
                                <span class="badge badge-<?php echo $quote['is_active'] ? 'success' : 'secondary'; ?>">
                                    <?php echo $quote['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Tips -->
            <div class="card m-b-30 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Tips Update</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2">Pastikan kutipan bermakna dan inspiratif</li>
                        <li class="mb-2">Sertakan sumber untuk keaslian</li>
                        <li class="mb-0">Nonaktifkan jika tidak ingin ditampilkan</li>
                    </ul>
                </div>
            </div>

            <!-- Panduan -->
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-help-circle mr-2"></i>Panduan</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2"><strong>Teks Kutipan:</strong> Kata-kata inspiratif (wajib)</li>
                        <li class="mb-2"><strong>Penulis:</strong> Nama penulis kutipan</li>
                        <li class="mb-2"><strong>Referensi:</strong> Sumber atau kitab asal</li>
                        <li class="mb-0"><strong>Status:</strong> Aktif = tampil di slider</li>
                    </ul>
                </div>
            </div>

            <!-- Jenis Kutipan -->
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-tag-multiple mr-2"></i>Jenis Kutipan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <span class="badge badge-light p-2 m-1 border">Ayat Al-Quran</span>
                        <span class="badge badge-light p-2 m-1 border">Hadits Nabi</span>
                        <span class="badge badge-light p-2 m-1 border">Kata Ulama</span>
                        <span class="badge badge-light p-2 m-1 border">Doa</span>
                        <span class="badge badge-light p-2 m-1 border">Hikmah</span>
                    </div>
                </div>
            </div>

            <!-- Penjelasan Field -->
            <div class="card m-b-30 border-secondary">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="mdi mdi-form-textbox mr-2"></i>Penjelasan Field</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0 small">
                        <tr>
                            <td width="35%"><strong>Kutipan</strong> <span class="text-danger">*</span></td>
                            <td>Teks inspiratif (10-1000 karakter)</td>
                        </tr>
                        <tr>
                            <td><strong>Penulis</strong></td>
                            <td>Nama penulis/tokoh (maks 150)</td>
                        </tr>
                        <tr>
                            <td><strong>Referensi</strong></td>
                            <td>Sumber kitab/hadits (maks 255)</td>
                        </tr>
                        <tr>
                            <td><strong>Urutan</strong></td>
                            <td>Posisi di slider (1-100)</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $error_script; ?>

<script>
    // Hitung karakter
    const quoteText = document.getElementById('quote_text');
    const charCount = document.getElementById('charCount');

    quoteText.addEventListener('input', function () {
        const count = this.value.length;
        charCount.textContent = count + '/1000';
        charCount.className = count > 900 ? 'float-right text-danger' : 'float-right text-muted';
    });

    // Trigger on load
    quoteText.dispatchEvent(new Event('input'));

    // Validasi form
    document.getElementById('quoteForm').addEventListener('submit', function (e) {
        const quoteTextVal = document.getElementById('quote_text').value.trim();

        if (quoteTextVal.length < 10) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Kutipan Terlalu Pendek!',
                text: 'Teks kutipan harus minimal 10 karakter.',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#dc3545'
            });
            return false;
        }

        Swal.fire({
            title: 'Menyimpan Perubahan...',
            html: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => { Swal.showLoading(); }
        });

        return true;
    });

    // Konfirmasi batal
    document.querySelector('.btn-cancel').addEventListener('click', function (e) {
        e.preventDefault();
        const link = this.href;

        Swal.fire({
            icon: 'question',
            title: 'Batalkan Perubahan?',
            text: 'Perubahan yang belum disimpan akan hilang.',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Lanjut Mengubah',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) window.location.href = link;
        });
    });
</script>