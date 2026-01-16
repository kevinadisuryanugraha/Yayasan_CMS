<?php
/**
 * AJAX: Get Registration Form for Event
 * Professional, Responsive Design with Better Spacing
 */
include '../admin/koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo '<div style="text-align:center;padding:60px 40px;color:#e74c3c;"><i class="icofont-warning" style="font-size:48px;display:block;margin-bottom:15px;"></i>Event ID tidak valid</div>';
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM events WHERE id = $id AND is_active = 1 AND status = 'published'");
$event = mysqli_fetch_assoc($query);

if (!$event) {
    echo '<div style="text-align:center;padding:60px 40px;color:#e74c3c;"><i class="icofont-warning" style="font-size:48px;display:block;margin-bottom:15px;"></i>Event tidak ditemukan</div>';
    exit;
}

$is_closed = strtotime($event['event_date']) < strtotime('today');
$quota_full = ($event['quota'] > 0 && $event['registered'] >= $event['quota']);
$remaining = $event['quota'] > 0 ? max(0, $event['quota'] - $event['registered']) : 999;
$is_paid = ($event['price'] > 0);
?>
<style>
    /* ===== REGISTRATION FORM STYLES ===== */
    .regform-container {
        font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
        max-width: 100%;
        background: #fff;
    }

    /* Header Section - Added bottom padding */
    .regform-header {
        background: linear-gradient(135deg, #00997d 0%, #1a4d5c 100%);
        color: #fff;
        padding: 30px 35px 45px 35px;
        margin: -30px -30px 0 -30px;
        border-radius: 20px 20px 0 0;
        text-align: center;
    }

    .regform-header-icon {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        backdrop-filter: blur(5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .regform-header-icon i {
        font-size: 32px;
    }

    .regform-header h3 {
        font-size: 22px;
        margin: 0 0 10px 0;
        font-weight: 700;
    }

    .regform-header>p {
        font-size: 14px;
        margin: 0 0 25px 0;
        opacity: 0.9;
    }

    .event-info-card {
        background: rgba(255, 255, 255, 0.15);
        padding: 20px 22px;
        border-radius: 14px;
        text-align: left;
        backdrop-filter: blur(5px);
    }

    .event-info-card h4 {
        font-size: 16px;
        margin: 0 0 14px 0;
        font-weight: 600;
        line-height: 1.4;
    }

    .event-info-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        font-size: 13px;
        opacity: 0.95;
    }

    .event-info-meta span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Body Section */
    .regform-body {
        padding: 35px 40px 40px 40px;
    }

    /* Closed/Full State */
    .regform-closed {
        text-align: center;
        padding: 50px 30px;
        background: linear-gradient(135deg, #fff5f5, #ffe0e0);
        border-radius: 16px;
        margin: 10px 0;
    }

    .regform-closed i {
        font-size: 70px;
        color: #e74c3c;
        display: block;
        margin-bottom: 25px;
    }

    .regform-closed h4 {
        color: #c0392b;
        font-size: 22px;
        margin: 0 0 12px 0;
    }

    .regform-closed p {
        color: #666;
        font-size: 15px;
        margin: 0;
    }

    /* Info Cards */
    .info-cards-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        margin-bottom: 30px;
    }

    .info-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 22px;
        border-radius: 14px;
        background: #f8f9fa;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }

    .info-card.quota {
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        color: #1565c0;
    }

    .info-card.free {
        background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
        color: #2e7d32;
    }

    .info-card.paid {
        background: linear-gradient(135deg, #fff3e0, #ffe0b2);
        color: #e65100;
    }

    .info-card-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .info-card-icon i {
        font-size: 24px;
    }

    .info-card-text {
        display: flex;
        flex-direction: column;
    }

    .info-card-text strong {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.75;
        margin-bottom: 3px;
    }

    .info-card-text span {
        font-size: 16px;
        font-weight: 700;
    }

    /* Form Sections */
    .form-section {
        margin-bottom: 30px;
        padding-bottom: 26px;
        border-bottom: 1px solid #eee;
    }

    .form-section:last-of-type {
        border-bottom: none;
        margin-bottom: 15px;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        font-weight: 600;
        color: #1a4d5c;
        margin-bottom: 22px;
    }

    .section-title i {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #00997d, #1a4d5c);
        color: #fff;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    /* Form Fields */
    .field-group {
        margin-bottom: 20px;
    }

    .field-group label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }

    .field-group label .required {
        color: #e74c3c;
        font-weight: 700;
    }

    .field-group input,
    .field-group select,
    .field-group textarea {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 15px;
        box-sizing: border-box;
        transition: all 0.3s;
        background: #fafafa;
    }

    .field-group input:focus,
    .field-group select:focus,
    .field-group textarea:focus {
        outline: none;
        border-color: #00997d;
        box-shadow: 0 0 0 4px rgba(0, 153, 125, 0.1);
        background: #fff;
    }

    .field-group input::placeholder,
    .field-group textarea::placeholder {
        color: #aaa;
    }

    .field-group small {
        font-size: 12px;
        color: #888;
        display: block;
        margin-top: 6px;
    }

    /* Two Column Row */
    .field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    /* Payment Section */
    .payment-section {
        background: linear-gradient(135deg, #fffbf0, #fff8e1);
        margin: 0 -40px 30px -40px;
        padding: 28px 40px;
        border-top: 3px solid #ffc107;
        border-bottom: 3px solid #ffc107;
    }

    .payment-notice {
        display: flex;
        gap: 15px;
        background: #fff;
        padding: 18px;
        border-radius: 12px;
        margin-bottom: 18px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    }

    .payment-notice-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #ffc107, #ff9800);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .payment-notice-icon i {
        font-size: 22px;
        color: #fff;
    }

    .payment-notice-text strong {
        font-size: 14px;
        color: #333;
        display: block;
        margin-bottom: 5px;
    }

    .payment-notice-text p {
        font-size: 13px;
        color: #666;
        margin: 0;
        line-height: 1.5;
    }

    .payment-amount-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #00997d, #1a4d5c);
        color: #fff;
        padding: 18px 22px;
        border-radius: 14px;
        margin-bottom: 18px;
        box-shadow: 0 4px 15px rgba(0, 153, 125, 0.3);
    }

    .payment-amount-box span {
        font-size: 15px;
        opacity: 0.9;
    }

    .payment-amount-box strong {
        font-size: 26px;
        font-weight: 700;
    }

    /* Submit Section */
    .submit-section {
        text-align: center;
        padding-top: 15px;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        color: #fff;
        border: none;
        padding: 18px 45px;
        border-radius: 50px;
        font-size: 17px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
        max-width: 350px;
        box-shadow: 0 6px 20px rgba(238, 90, 36, 0.35);
    }

    .btn-submit:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(238, 90, 36, 0.45);
    }

    .btn-submit:disabled {
        background: linear-gradient(135deg, #bbb, #999);
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .disclaimer-text {
        font-size: 13px;
        color: #888;
        margin: 18px 0 0 0;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 700px) {
        .regform-header {
            margin: -20px -20px 0 -20px;
            padding: 25px 25px 40px 25px;
        }

        .regform-body {
            padding: 30px 28px 35px 28px;
        }

        .regform-header-icon {
            width: 60px;
            height: 60px;
        }

        .regform-header h3 {
            font-size: 20px;
        }

        .payment-section {
            margin: 0 -28px 28px -28px;
            padding: 22px 28px;
        }
    }

    @media (max-width: 550px) {
        .regform-header {
            margin: -15px -15px 0 -15px;
            padding: 22px 20px 35px 20px;
        }

        .regform-body {
            padding: 25px 22px 30px 22px;
        }

        .info-cards-row {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .field-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .payment-section {
            margin: 0 -22px 25px -22px;
            padding: 20px 22px;
        }

        .payment-amount-box {
            flex-direction: column;
            gap: 6px;
            text-align: center;
        }

        .payment-notice {
            flex-direction: column;
            text-align: center;
        }

        .payment-notice-icon {
            margin: 0 auto;
        }

        .field-group input,
        .field-group select,
        .field-group textarea {
            font-size: 16px;
            padding: 14px 16px;
        }

        .btn-submit {
            font-size: 16px;
            padding: 16px 35px;
        }
    }
</style>

<div class="regform-container">
    <div class="regform-header">
        <div class="regform-header-icon">
            <i class="icofont-clip-board"></i>
        </div>
        <h3>Form Pendaftaran Event</h3>
        <p>Daftarkan diri Anda untuk mengikuti acara ini</p>
        <div class="event-info-card">
            <h4><?php echo htmlspecialchars($event['title']); ?></h4>
            <div class="event-info-meta">
                <span><i class="icofont-calendar"></i>
                    <?php echo date('d F Y', strtotime($event['event_date'])); ?></span>
                <?php if ($event['event_time']): ?>
                    <span><i class="icofont-clock-time"></i> <?php echo date('H:i', strtotime($event['event_time'])); ?>
                        WIB</span>
                <?php endif; ?>
                <?php if ($event['location']): ?>
                    <span><i class="icofont-location-pin"></i> <?php echo htmlspecialchars($event['location']); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="regform-body">
        <?php if ($is_closed): ?>
            <div class="regform-closed">
                <i class="icofont-lock"></i>
                <h4>Pendaftaran Ditutup</h4>
                <p>Maaf, pendaftaran untuk acara ini sudah ditutup karena acara sudah berlangsung.</p>
            </div>
        <?php elseif ($quota_full): ?>
            <div class="regform-closed">
                <i class="icofont-users-alt-4"></i>
                <h4>Kuota Penuh</h4>
                <p>Maaf, kuota pendaftaran untuk acara ini sudah penuh.</p>
            </div>
        <?php else: ?>

            <!-- Info Cards -->
            <div class="info-cards-row">
                <div class="info-card quota">
                    <div class="info-card-icon">
                        <i class="icofont-users-alt-4"></i>
                    </div>
                    <div class="info-card-text">
                        <strong>Sisa Kuota</strong>
                        <span><?php echo $event['quota'] > 0 ? $remaining . ' dari ' . $event['quota'] : 'Tidak Terbatas'; ?></span>
                    </div>
                </div>
                <div class="info-card <?php echo $is_paid ? 'paid' : 'free'; ?>">
                    <div class="info-card-icon">
                        <i class="icofont-<?php echo $is_paid ? 'money' : 'gift'; ?>"></i>
                    </div>
                    <div class="info-card-text">
                        <strong><?php echo $is_paid ? 'Biaya Pendaftaran' : 'Pendaftaran'; ?></strong>
                        <span><?php echo $is_paid ? 'Rp ' . number_format($event['price'], 0, ',', '.') : 'GRATIS'; ?></span>
                    </div>
                </div>
            </div>

            <!-- Registration Form -->
            <form id="rfForm">
                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">

                <!-- Section: Data Pribadi -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="icofont-user"></i>
                        <span>Data Pribadi</span>
                    </div>

                    <div class="field-group">
                        <label>Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="full_name" required placeholder="Masukkan nama lengkap Anda">
                    </div>

                    <div class="field-row">
                        <div class="field-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" name="email" required placeholder="email@contoh.com">
                        </div>
                        <div class="field-group">
                            <label>No. HP <span class="required">*</span></label>
                            <input type="tel" name="phone" required placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-group">
                            <label>Jenis Kelamin</label>
                            <select name="gender">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="male">Laki-laki</option>
                                <option value="female">Perempuan</option>
                            </select>
                        </div>
                        <div class="field-group">
                            <label>Usia</label>
                            <input type="number" name="age" min="1" max="120" placeholder="Masukkan usia Anda">
                        </div>
                    </div>
                </div>

                <!-- Section: Alamat & Instansi -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="icofont-building-alt"></i>
                        <span>Alamat & Instansi</span>
                    </div>

                    <div class="field-group">
                        <label>Alamat Lengkap</label>
                        <textarea name="address" rows="2" placeholder="Masukkan alamat lengkap Anda"></textarea>
                    </div>

                    <div class="field-row">
                        <div class="field-group">
                            <label>Kota/Kabupaten</label>
                            <input type="text" name="city" placeholder="Contoh: Jakarta Selatan">
                        </div>
                        <div class="field-group">
                            <label>Instansi/Lembaga</label>
                            <input type="text" name="institution" placeholder="Nama kantor/sekolah/kampus">
                        </div>
                    </div>
                </div>                <?php if ($is_paid): ?>
                    <?php include 'partials/payment_section.php'; ?>
                <?php else: ?>
                    <input type="hidden" name="whatsapp" value="">
                <?php endif; ?>

                <!-- Section: Catatan -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="icofont-pencil-alt-2"></i>
                        <span>Catatan Tambahan (Opsional)</span>
                    </div>

                    <div class="field-group">
                        <textarea name="notes" rows="2"
                            placeholder="Pertanyaan atau informasi tambahan yang ingin Anda sampaikan"></textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="submit-section">
                    <button type="button" class="btn-submit" id="rfBtn" onclick="submitRegistrationForm()">
                        <i class="icofont-paper-plane"></i>
                        <span><?php echo $is_paid ? 'Daftar & Lanjut Pembayaran' : 'Daftar Sekarang'; ?></span>
                    </button>
                    <p class="disclaimer-text">Dengan mendaftar, Anda menyetujui syarat dan ketentuan yang berlaku.</p>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>