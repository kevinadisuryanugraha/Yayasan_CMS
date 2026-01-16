<?php
// Edit Metode Pembayaran

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: ?page=payment_methods');
    exit;
}

// Fetch existing data
$query = mysqli_query($conn, "SELECT * FROM payment_methods WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Gagal!',
        'message' => 'Data tidak ditemukan'
    ];
    header('Location: ?page=payment_methods');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $account_number = mysqli_real_escape_string($conn, $_POST['account_number']);
    $account_name = mysqli_real_escape_string($conn, $_POST['account_name']);
    $instructions = mysqli_real_escape_string($conn, $_POST['instructions']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = intval($_POST['sort_order']);

    $icon_path = $data['icon']; // Keep existing
    $qr_path = $data['qr_image']; // Keep existing

    // Upload new icon
    if (isset($_FILES['icon']) && $_FILES['icon']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/payment_methods/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $ext = strtolower(pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

        if (in_array($ext, $allowed)) {
            // Delete old icon
            if ($data['icon'] && file_exists('../' . $data['icon'])) {
                unlink('../' . $data['icon']);
            }

            $filename = 'icon_' . time() . '_' . uniqid() . '.' . $ext;
            $target = $upload_dir . $filename;

            if (move_uploaded_file($_FILES['icon']['tmp_name'], $target)) {
                $icon_path = 'uploads/payment_methods/' . $filename;
            }
        }
    }

    // Upload new QR image
    if (isset($_FILES['qr_image']) && $_FILES['qr_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/payment_methods/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $ext = strtolower(pathinfo($_FILES['qr_image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $allowed)) {
            // Delete old QR
            if ($data['qr_image'] && file_exists('../' . $data['qr_image'])) {
                unlink('../' . $data['qr_image']);
            }

            $filename = 'qr_' . time() . '_' . uniqid() . '.' . $ext;
            $target = $upload_dir . $filename;

            if (move_uploaded_file($_FILES['qr_image']['tmp_name'], $target)) {
                $qr_path = 'uploads/payment_methods/' . $filename;
            }
        }
    }

    // Handle delete icon checkbox
    if (isset($_POST['delete_icon']) && $_POST['delete_icon'] == '1') {
        if ($data['icon'] && file_exists('../' . $data['icon'])) {
            unlink('../' . $data['icon']);
        }
        $icon_path = '';
    }

    // Handle delete QR checkbox
    if (isset($_POST['delete_qr']) && $_POST['delete_qr'] == '1') {
        if ($data['qr_image'] && file_exists('../' . $data['qr_image'])) {
            unlink('../' . $data['qr_image']);
        }
        $qr_path = '';
    }

    // Build UPDATE query
    $sql = "UPDATE payment_methods SET 
            type = '$type',
            name = '$name',
            account_number = '$account_number',
            account_name = '$account_name',
            icon = '$icon_path',
            qr_image = '$qr_path',
            instructions = '$instructions',
            is_active = $is_active,
            sort_order = $sort_order
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Metode pembayaran berhasil diupdate'
        ];
        header('Location: ?page=payment_methods');
        exit;
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Gagal!',
            'message' => 'Gagal mengupdate: ' . mysqli_error($conn)
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
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Metode Pembayaran</h4>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card m-b-30">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="mdi mdi-pencil mr-2"></i>Edit:
                        <?php echo htmlspecialchars($data['name']); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Tipe Pembayaran</strong> <span class="text-danger">*</span></label>
                                    <select name="type" class="form-control" required id="typeSelect">
                                        <option value="bank" <?php echo $data['type'] == 'bank' ? 'selected' : ''; ?>>🏦
                                            Bank Transfer</option>
                                        <option value="ewallet" <?php echo $data['type'] == 'ewallet' ? 'selected' : ''; ?>>📱 E-Wallet</option>
                                        <option value="qris" <?php echo $data['type'] == 'qris' ? 'selected' : ''; ?>>📲
                                            QRIS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Nama Bank/E-Wallet</strong> <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required
                                        value="<?php echo htmlspecialchars($data['name']); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong id="accountNumberLabel">No. Rekening</strong></label>
                                    <input type="text" name="account_number" class="form-control"
                                        id="accountNumberInput"
                                        value="<?php echo htmlspecialchars($data['account_number'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Nama Pemilik</strong></label>
                                    <input type="text" name="account_name" class="form-control"
                                        value="<?php echo htmlspecialchars($data['account_name'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Logo/Icon</strong></label>
                                    <?php if ($data['icon']): ?>
                                        <div class="mb-2">
                                            <img src="../<?php echo $data['icon']; ?>" alt="Current Icon"
                                                style="max-height: 50px;">
                                            <label class="ml-3">
                                                <input type="checkbox" name="delete_icon" value="1"> Hapus icon
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" name="icon" class="form-control-file" accept="image/*">
                                    <small class="text-muted">Upload baru untuk mengganti</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Gambar QR Code</strong></label>
                                    <?php if ($data['qr_image']): ?>
                                        <div class="mb-2">
                                            <img src="../<?php echo $data['qr_image']; ?>" alt="Current QR"
                                                style="max-height: 80px;">
                                            <label class="ml-3">
                                                <input type="checkbox" name="delete_qr" value="1"> Hapus QR
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" name="qr_image" class="form-control-file" accept="image/*">
                                    <small class="text-muted">Upload baru untuk mengganti</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><strong>Instruksi Pembayaran</strong></label>
                            <textarea name="instructions" class="form-control"
                                rows="3"><?php echo htmlspecialchars($data['instructions'] ?? ''); ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Urutan Tampilan</strong></label>
                                    <input type="number" name="sort_order" class="form-control"
                                        value="<?php echo $data['sort_order']; ?>" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Status</strong></label>
                                    <div class="custom-control custom-switch mt-2">
                                        <input type="checkbox" class="custom-control-input" id="isActive"
                                            name="is_active" <?php echo $data['is_active'] ? 'checked' : ''; ?>>
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
                            <button type="submit" class="btn btn-warning">
                                <i class="mdi mdi-content-save"></i> Update
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
                    <h5 class="mb-0"><i class="mdi mdi-information mr-2"></i>Info</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td>ID:</td>
                            <td><code><?php echo $data['id']; ?></code></td>
                        </tr>
                        <tr>
                            <td>Dibuat:</td>
                            <td>
                                <?php echo date('d M Y H:i', strtotime($data['created_at'])); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Diupdate:</td>
                            <td>
                                <?php echo date('d M Y H:i', strtotime($data['updated_at'])); ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Update label based on type
    function updateLabel() {
        const type = document.getElementById('typeSelect').value;
        const accountLabel = document.getElementById('accountNumberLabel');

        if (type === 'bank') {
            accountLabel.textContent = 'No. Rekening';
        } else if (type === 'ewallet') {
            accountLabel.textContent = 'No. HP/E-Wallet';
        } else if (type === 'qris') {
            accountLabel.textContent = 'Kode (Opsional)';
        }
    }

    document.getElementById('typeSelect').addEventListener('change', updateLabel);
    updateLabel(); // Run on load
</script>