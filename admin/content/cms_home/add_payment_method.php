<?php
// Tambah Metode Pembayaran

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $account_number = mysqli_real_escape_string($conn, $_POST['account_number']);
    $account_name = mysqli_real_escape_string($conn, $_POST['account_name']);
    $instructions = mysqli_real_escape_string($conn, $_POST['instructions']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = intval($_POST['sort_order']);

    $icon_path = '';
    $qr_path = '';

    // Upload icon
    if (isset($_FILES['icon']) && $_FILES['icon']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/payment_methods/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $ext = strtolower(pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

        if (in_array($ext, $allowed)) {
            $filename = 'icon_' . time() . '_' . uniqid() . '.' . $ext;
            $target = $upload_dir . $filename;

            if (move_uploaded_file($_FILES['icon']['tmp_name'], $target)) {
                $icon_path = 'uploads/payment_methods/' . $filename;
            }
        }
    }

    // Upload QR image
    if (isset($_FILES['qr_image']) && $_FILES['qr_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/payment_methods/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $ext = strtolower(pathinfo($_FILES['qr_image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $allowed)) {
            $filename = 'qr_' . time() . '_' . uniqid() . '.' . $ext;
            $target = $upload_dir . $filename;

            if (move_uploaded_file($_FILES['qr_image']['tmp_name'], $target)) {
                $qr_path = 'uploads/payment_methods/' . $filename;
            }
        }
    }

    // Build INSERT query
    $sql = "INSERT INTO payment_methods (type, name, account_number, account_name, icon, qr_image, instructions, is_active, sort_order) 
            VALUES ('$type', '$name', '$account_number', '$account_name', '$icon_path', '$qr_path', '$instructions', $is_active, $sort_order)";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Metode pembayaran berhasil ditambahkan'
        ];
        header('Location: ?page=payment_methods');
        exit;
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Gagal!',
            'message' => 'Gagal menambahkan metode pembayaran: ' . mysqli_error($conn)
        ];
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
                        <li class="breadcrumb-item"><a href="?page=payment_methods">Metode Pembayaran</a></li>
                        <li class="breadcrumb-item active">Tambah Baru</li>
                    </ol>
                </div>
                <h4 class="page-title">Tambah Metode Pembayaran</h4>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card m-b-30">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-plus-circle mr-2"></i>Form Tambah Metode Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Tipe Pembayaran</strong> <span class="text-danger">*</span></label>
                                    <select name="type" class="form-control" required id="typeSelect">
                                        <option value="">-- Pilih Tipe --</option>
                                        <option value="bank">🏦 Bank Transfer</option>
                                        <option value="ewallet">📱 E-Wallet</option>
                                        <option value="qris">📲 QRIS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Nama Bank/E-Wallet</strong> <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required
                                        placeholder="Contoh: Bank BCA, GoPay, QRIS Universal">
                                </div>
                            </div>
                        </div>

                        <div class="row" id="accountFields">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong id="accountNumberLabel">No. Rekening</strong></label>
                                    <input type="text" name="account_number" class="form-control"
                                        placeholder="1234567890" id="accountNumberInput">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Nama Pemilik</strong></label>
                                    <input type="text" name="account_name" class="form-control"
                                        placeholder="Nama pemilik rekening/akun">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Logo/Icon</strong></label>
                                    <input type="file" name="icon" class="form-control-file" accept="image/*">
                                    <small class="text-muted">Format: JPG, PNG, GIF, WebP, SVG (Max 2MB)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Gambar QR Code</strong></label>
                                    <input type="file" name="qr_image" class="form-control-file" accept="image/*">
                                    <small class="text-muted">Upload QR Code untuk QRIS/E-Wallet</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><strong>Instruksi Pembayaran</strong></label>
                            <textarea name="instructions" class="form-control" rows="3"
                                placeholder="Instruksi tambahan untuk user (opsional)"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Urutan Tampilan</strong></label>
                                    <input type="number" name="sort_order" class="form-control" value="0" min="0">
                                    <small class="text-muted">Angka kecil tampil lebih dulu</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Status</strong></label>
                                    <div class="custom-control custom-switch mt-2">
                                        <input type="checkbox" class="custom-control-input" id="isActive"
                                            name="is_active" checked>
                                        <label class="custom-control-label" for="isActive">Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="?page=payment_methods" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="mdi mdi-content-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="col-lg-4">
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0 pl-3">
                        <li class="mb-2">
                            <strong>Bank Transfer:</strong> Masukkan nomor rekening lengkap
                        </li>
                        <li class="mb-2">
                            <strong>E-Wallet:</strong> Masukkan nomor HP yang terdaftar
                        </li>
                        <li class="mb-2">
                            <strong>QRIS:</strong> Upload gambar QR Code
                        </li>
                        <li class="mb-2">
                            <strong>Logo:</strong> Gunakan logo resmi bank/e-wallet
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('typeSelect').addEventListener('change', function () {
        const type = this.value;
        const accountLabel = document.getElementById('accountNumberLabel');
        const accountInput = document.getElementById('accountNumberInput');

        if (type === 'bank') {
            accountLabel.textContent = 'No. Rekening';
            accountInput.placeholder = '1234567890';
        } else if (type === 'ewallet') {
            accountLabel.textContent = 'No. HP/E-Wallet';
            accountInput.placeholder = '081234567890';
        } else if (type === 'qris') {
            accountLabel.textContent = 'Kode (Opsional)';
            accountInput.placeholder = 'Kode QRIS jika ada';
        }
    });
</script>