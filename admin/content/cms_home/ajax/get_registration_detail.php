<?php
/**
 * AJAX: Get Registration Detail
 */
include '../../../koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo '<div class="alert alert-danger">ID tidak valid</div>';
    exit;
}

$query = mysqli_query($conn, "
    SELECT r.*, e.title as event_title, e.event_date, e.event_time, e.location, e.price 
    FROM event_registrations r 
    JOIN events e ON r.event_id = e.id 
    WHERE r.id = $id
");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo '<div class="alert alert-danger">Data tidak ditemukan</div>';
    exit;
}

$status_badges = [
    'pending' => 'warning',
    'confirmed' => 'success',
    'attended' => 'info',
    'cancelled' => 'secondary'
];
$status_texts = [
    'pending' => 'Menunggu Konfirmasi',
    'confirmed' => 'Terkonfirmasi',
    'attended' => 'Sudah Hadir',
    'cancelled' => 'Dibatalkan'
];
?>

<div class="row">
    <div class="col-md-6">
        <h6 class="text-muted mb-3">Informasi Peserta</h6>
        <table class="table table-sm">
            <tr>
                <td width="130"><strong>Kode Registrasi</strong></td>
                <td><code class="text-primary font-weight-bold"><?php echo $data['registration_code']; ?></code></td>
            </tr>
            <tr>
                <td><strong>Nama Lengkap</strong></td>
                <td>
                    <?php echo htmlspecialchars($data['full_name']); ?>
                </td>
            </tr>
            <tr>
                <td><strong>Email</strong></td>
                <td><a href="mailto:<?php echo $data['email']; ?>">
                        <?php echo $data['email']; ?>
                    </a></td>
            </tr>
            <tr>
                <td><strong>Telepon</strong></td>
                <td><a href="tel:<?php echo $data['phone']; ?>">
                        <?php echo $data['phone']; ?>
                    </a></td>
            </tr>
            <?php if ($data['whatsapp']): ?>
                <tr>
                    <td><strong>WhatsApp</strong></td>
                    <td><a href="https://wa.me/<?php echo $data['whatsapp']; ?>" target="_blank">
                            <?php echo $data['whatsapp']; ?>
                        </a></td>
                </tr>
            <?php endif; ?>
            <?php if ($data['gender']): ?>
                <tr>
                    <td><strong>Jenis Kelamin</strong></td>
                    <td>
                        <?php echo $data['gender'] == 'male' ? 'Laki-laki' : 'Perempuan'; ?>
                    </td>
                </tr>
            <?php endif; ?>
            <?php if ($data['city']): ?>
                <tr>
                    <td><strong>Kota</strong></td>
                    <td>
                        <?php echo htmlspecialchars($data['city']); ?>
                    </td>
                </tr>
            <?php endif; ?>
            <?php if ($data['institution']): ?>
                <tr>
                    <td><strong>Instansi</strong></td>
                    <td>
                        <?php echo htmlspecialchars($data['institution']); ?>
                    </td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
    <div class="col-md-6">
        <h6 class="text-muted mb-3">Informasi Event</h6>
        <table class="table table-sm">
            <tr>
                <td width="100"><strong>Event</strong></td>
                <td class="text-primary">
                    <?php echo htmlspecialchars($data['event_title']); ?>
                </td>
            </tr>
            <tr>
                <td><strong>Tanggal</strong></td>
                <td>
                    <?php echo date('d F Y', strtotime($data['event_date'])); ?>
                </td>
            </tr>
            <?php if ($data['event_time']): ?>
                <tr>
                    <td><strong>Waktu</strong></td>
                    <td>
                        <?php echo date('H:i', strtotime($data['event_time'])); ?> WIB
                    </td>
                </tr>
            <?php endif; ?>
            <?php if ($data['location']): ?>
                <tr>
                    <td><strong>Lokasi</strong></td>
                    <td>
                        <?php echo htmlspecialchars($data['location']); ?>
                    </td>
                </tr>
            <?php endif; ?>
            <tr>
                <td><strong>Biaya</strong></td>
                <td>
                    <?php echo $data['price'] > 0 ? 'Rp ' . number_format($data['price'], 0, ',', '.') : '<span class="text-success">Gratis</span>'; ?>
                </td>
            </tr>
        </table>

        <h6 class="text-muted mb-3 mt-4">Status Pendaftaran</h6>
        <table class="table table-sm">
            <tr>
                <td width="100"><strong>Status</strong></td>
                <td><span class="badge badge-<?php echo $status_badges[$data['status']]; ?> p-2">
                        <?php echo $status_texts[$data['status']]; ?>
                    </span></td>
            </tr>
            <tr>
                <td><strong>Pembayaran</strong></td>
                <td>
                    <?php if ($data['payment_amount'] > 0): ?>
                        <?php if ($data['payment_status'] == 'paid'): ?>
                            <span class="badge badge-success">Lunas</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Belum Bayar</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="badge badge-info">Gratis</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong>Terdaftar</strong></td>
                <td>
                    <?php echo date('d M Y H:i', strtotime($data['created_at'])); ?>
                </td>
            </tr>
            <?php if ($data['confirmed_at']): ?>
                <tr>
                    <td><strong>Dikonfirmasi</strong></td>
                    <td>
                        <?php echo date('d M Y H:i', strtotime($data['confirmed_at'])); ?>
                    </td>
                </tr>
            <?php endif; ?>        </table>

        <?php if (!empty($data['payment_proof'])): ?>
        <div class="mt-3">
            <h6 class="text-muted mb-2"><i class="mdi mdi-receipt"></i> Bukti Pembayaran</h6>
            <div class="border rounded p-2 bg-light text-center">
                <a href="../<?php echo htmlspecialchars($data['payment_proof']); ?>" target="_blank" title="Lihat ukuran penuh">
                    <img src="../<?php echo htmlspecialchars($data['payment_proof']); ?>" alt="Bukti Pembayaran" style="max-width: 100%; max-height: 200px; border-radius: 8px; cursor: pointer;">
                </a>
                <div class="mt-2">
                    <small class="text-muted">
                        <?php if (!empty($data['payment_date'])): ?>
                            Dikirim: <?php echo date('d M Y H:i', strtotime($data['payment_date'])); ?>
                        <?php endif; ?>
                    </small>
                    <br>
                    <a href="../<?php echo htmlspecialchars($data['payment_proof']); ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                        <i class="mdi mdi-eye"></i> Lihat Full
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($data['notes']): ?>
    <div class="mt-3">
        <h6 class="text-muted">Catatan dari Peserta</h6>
        <div class="bg-light p-3 rounded">
            <?php echo nl2br(htmlspecialchars($data['notes'])); ?>
        </div>
    </div>
<?php endif; ?>

<?php if ($data['admin_notes']): ?>
    <div class="mt-3">
        <h6 class="text-muted">Catatan Admin</h6>
        <div class="bg-warning-light p-3 rounded">
            <?php echo nl2br(htmlspecialchars($data['admin_notes'])); ?>
        </div>
    </div>
<?php endif; ?>