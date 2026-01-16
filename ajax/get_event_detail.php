<?php
/**
 * AJAX Handler: Get Event Detail
 * Returns HTML content for event modal
 */

include '../admin/koneksi.php';

// Validate ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo '<div class="modal-error"><i class="icofont-warning"></i><p>ID acara tidak valid</p></div>';
    exit;
}

// Fetch event
$query = mysqli_query($conn, "SELECT * FROM events WHERE id = $id AND is_active = 1");
$event = mysqli_fetch_assoc($query);

if (!$event) {
    echo '<div class="modal-error"><i class="icofont-warning"></i><p>Acara tidak ditemukan</p></div>';
    exit;
}

// Image path logic
$img_src = 'assets/images/placeholder_landscape.jpg';
if (!empty($event['image'])) {
    if (file_exists('../' . $event['image']))
        $img_src = $event['image'];
    elseif (file_exists('../admin/' . $event['image']))
        $img_src = 'admin/' . $event['image'];
}

// Speaker image
$speaker_img = '';
if (!empty($event['speaker_image'])) {
    if (file_exists('../' . $event['speaker_image']))
        $speaker_img = $event['speaker_image'];
    elseif (file_exists('../admin/' . $event['speaker_image']))
        $speaker_img = 'admin/' . $event['speaker_image'];
}
?>

<div class="modal-event-header">
    <img src="<?php echo htmlspecialchars($img_src); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
    <span class="event-category-badge">
        <?php echo htmlspecialchars($event['category'] ?? 'General'); ?>
    </span>
</div>

<div class="modal-event-body">
    <h2 class="modal-event-title">
        <?php echo htmlspecialchars($event['title']); ?>
    </h2>

    <div class="modal-event-meta">
        <div class="modal-meta-item">
            <i class="icofont-calendar"></i>
            <div>
                <strong>Tanggal</strong>
                <span>
                    <?php echo date('l, d F Y', strtotime($event['event_date'])); ?>
                </span>
            </div>
        </div>

        <?php if ($event['event_time']): ?>
            <div class="modal-meta-item">
                <i class="icofont-clock-time"></i>
                <div>
                    <strong>Waktu</strong>
                    <span>
                        <?php echo date('H:i', strtotime($event['event_time'])); ?> WIB
                    </span>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($event['location']): ?>
            <div class="modal-meta-item">
                <i class="icofont-location-pin"></i>
                <div>
                    <strong>Lokasi</strong>
                    <span>
                        <?php echo htmlspecialchars($event['location']); ?>
                    </span>
                </div>
            </div>
        <?php endif; ?>

        <div class="modal-meta-item">
            <i class="icofont-ticket"></i>
            <div>
                <strong>Harga</strong>
                <span>
                    <?php echo $event['price'] > 0 ? 'Rp ' . number_format($event['price'], 0, ',', '.') : 'Gratis'; ?>
                </span>
            </div>
        </div>

        <?php if ($event['quota']): ?>
            <div class="modal-meta-item">
                <i class="icofont-users-alt-4"></i>
                <div>
                    <strong>Kuota</strong>
                    <span>
                        <?php echo $event['quota']; ?> peserta
                    </span>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($event['status'] == 'ended' || strtotime($event['event_date']) < strtotime('today')): ?>
            <div class="modal-meta-item">
                <i class="icofont-check-circled"></i>
                <div>
                    <strong>Status</strong>
                    <span style="color: #888;">Acara Sudah Selesai</span>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($event['description']): ?>
        <div class="modal-event-desc">
            <h4><i class="icofont-info-circle"></i> Tentang Acara</h4>
            <?php echo $event['description']; ?>
        </div>
    <?php endif; ?>

    <?php if ($event['location_address']): ?>
        <div class="modal-event-desc">
            <h4><i class="icofont-map"></i> Alamat Lengkap</h4>
            <p>
                <?php echo $event['location_address']; ?>
            </p>
            <?php if ($event['location_maps']): ?>
                <a href="<?php echo htmlspecialchars($event['location_maps']); ?>" target="_blank" class="btn-event-detail"
                    style="margin-top: 10px;">
                    <i class="icofont-google-map"></i> Buka di Google Maps
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($event['speaker_name']): ?>
        <div class="modal-event-speaker">
            <?php if ($speaker_img): ?>
                <img src="<?php echo htmlspecialchars($speaker_img); ?>"
                    alt="<?php echo htmlspecialchars($event['speaker_name']); ?>" class="modal-speaker-image">
            <?php else: ?>
                <div class="modal-speaker-image"
                    style="background: linear-gradient(135deg, var(--event-primary), var(--event-secondary)); display: flex; align-items: center; justify-content: center;">
                    <i class="icofont-user-alt-7" style="font-size: 30px; color: #fff;"></i>
                </div>
            <?php endif; ?>
            <div class="modal-speaker-info">
                <h5>
                    <?php echo htmlspecialchars($event['speaker_name']); ?>
                </h5>
                <?php if ($event['speaker_title']): ?>
                    <p>
                        <?php echo htmlspecialchars($event['speaker_title']); ?>
                    </p>
                <?php endif; ?>
                <?php if ($event['speaker_bio']): ?>
                    <p style="margin-top: 8px; font-size: 13px; color: #666;">
                        <?php echo mb_substr(strip_tags($event['speaker_bio']), 0, 150); ?>...
                    </p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php
    // Registration status
    $is_past = strtotime($event['event_date']) < strtotime('today');
    $quota_full = ($event['quota'] > 0 && $event['registered'] >= $event['quota']);
    $remaining = $event['quota'] > 0 ? max(0, $event['quota'] - $event['registered']) : 0;
    ?>

    <?php if (!$is_past && $event['quota'] > 0): ?>
        <div class="modal-registration-status">
            <div class="quota-progress">
                <div class="quota-bar">
                    <div class="quota-fill"
                        style="width: <?php echo min(100, ($event['registered'] / $event['quota']) * 100); ?>%"></div>
                </div>
                <div class="quota-text">
                    <span><i class="icofont-users-alt-4"></i>
                        <?php echo $event['registered']; ?>/<?php echo $event['quota']; ?> peserta terdaftar</span>
                    <?php if ($remaining > 0): ?>
                        <span class="remaining">Sisa <?php echo $remaining; ?> slot</span>
                    <?php else: ?>
                        <span class="remaining full">Kuota Penuh</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="modal-event-actions">
        <?php if (!$is_past && !$quota_full): ?>
            <a href="#" class="btn-event-register" onclick="openRegistrationModal(<?php echo $event['id']; ?>)">
                <i class="icofont-edit"></i> Daftar Sekarang
            </a>
        <?php elseif ($quota_full): ?>
            <span class="btn-event-disabled">
                <i class="icofont-ban"></i> Kuota Penuh
            </span>
        <?php elseif ($is_past): ?>
            <span class="btn-event-disabled">
                <i class="icofont-lock"></i> Pendaftaran Ditutup
            </span>
        <?php endif; ?>

        <?php if ($event['contact_whatsapp']): ?>
            <a href="https://wa.me/<?php echo $event['contact_whatsapp']; ?>?text=Halo, saya tertarik dengan acara <?php echo urlencode($event['title']); ?>"
                target="_blank" class="btn-event-whatsapp">
                <i class="icofont-whatsapp"></i> Hubungi via WhatsApp
            </a>
        <?php endif; ?>

        <?php if ($event['contact_phone']): ?>
            <a href="tel:<?php echo $event['contact_phone']; ?>" class="btn-event-primary" style="background: #3498db;">
                <i class="icofont-phone"></i>
                <?php echo $event['contact_phone']; ?>
            </a>
        <?php endif; ?>

        <?php if ($event['location_maps']): ?>
            <a href="<?php echo htmlspecialchars($event['location_maps']); ?>" target="_blank" class="btn-event-primary"
                style="background: #e74c3c;">
                <i class="icofont-google-map"></i> Lihat Lokasi
            </a>
        <?php endif; ?>
    </div>
</div>

<style>
    .modal-registration-status {
        margin-bottom: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .quota-progress .quota-bar {
        height: 10px;
        background: #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .quota-progress .quota-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--event-primary), var(--event-accent));
        border-radius: 10px;
        transition: width 0.5s ease;
    }

    .quota-progress .quota-text {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        color: #666;
    }

    .quota-text .remaining {
        font-weight: 600;
        color: var(--event-primary);
    }

    .quota-text .remaining.full {
        color: #e74c3c;
    }

    .btn-event-register {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        color: #fff;
        padding: 14px 28px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(238, 90, 36, 0.3);
    }

    .btn-event-register:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(238, 90, 36, 0.4);
        color: #fff;
    }

    .btn-event-disabled {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ccc;
        color: #666;
        padding: 14px 28px;
        border-radius: 50px;
        font-weight: 600;
        cursor: not-allowed;
    }
</style>