<?php
/**
 * Payment Section Partial for Event Registration
 * This file renders the payment methods and payment proof upload
 * Required variables: $conn, $event (must have 'price')
 * 
 * Note: JavaScript functions are in registration-form.js
 */

// Fetch active payment methods
$pm_query = mysqli_query($conn, "SELECT * FROM payment_methods WHERE is_active = 1 ORDER BY sort_order ASC");
$payment_methods = mysqli_fetch_all($pm_query, MYSQLI_ASSOC);

// Group by type
$banks = array_filter($payment_methods, fn($p) => $p['type'] == 'bank');
$ewallets = array_filter($payment_methods, fn($p) => $p['type'] == 'ewallet');
$qris_items = array_filter($payment_methods, fn($p) => $p['type'] == 'qris');
?>

<style>
    /* Payment Methods Container */
    .payment-methods-container {
        margin-bottom: 24px;
    }

    .payment-group {
        background: #fff;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .payment-group-title {
        font-weight: 600;
        font-size: 14px;
        color: #1a4d5c;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .payment-items {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .payment-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        background: #f8f9fa;
        border-radius: 10px;
        gap: 12px;
    }

    .payment-item-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }

    .payment-icon {
        width: 40px;
        height: 40px;
        object-fit: contain;
        border-radius: 8px;
    }

    .payment-icon-placeholder {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #e0e0e0, #bdbdbd);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
    }

    .payment-icon-placeholder.large {
        width: 100px;
        height: 100px;
        font-size: 40px;
    }

    .payment-item-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .payment-item-info strong {
        font-size: 14px;
        color: #333;
    }

    .payment-item-info code {
        font-size: 15px;
        font-weight: 700;
        color: #00997d;
        background: #e8f5f3;
        padding: 2px 8px;
        border-radius: 4px;
    }

    .payment-item-info small {
        font-size: 12px;
        color: #666;
    }

    .btn-copy,
    .btn-qr {
        padding: 8px 12px;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
    }

    .btn-copy {
        background: #00997d;
        color: #fff;
    }

    .btn-copy:hover {
        background: #00856c;
    }

    .btn-qr {
        background: #6c5ce7;
        color: #fff;
    }

    .btn-qr:hover {
        background: #5b4dd4;
    }

    .qris-item {
        flex-direction: column;
        text-align: center;
        padding: 20px;
    }

    .qris-image {
        max-width: 180px;
        max-height: 180px;
        border-radius: 12px;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .qris-image:hover {
        transform: scale(1.05);
    }

    /* Payment Proof Upload */
    .payment-proof-section {
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .upload-container {
        position: relative;
        border: 2px dashed #ccc;
        border-radius: 12px;
        min-height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #fff;
        transition: all 0.3s;
    }

    .upload-container:hover {
        border-color: #00997d;
        background: #f8fffd;
    }

    .upload-container input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 10;
    }

    .upload-placeholder {
        text-align: center;
        color: #888;
        padding: 30px;
    }

    .upload-placeholder i {
        font-size: 48px;
        display: block;
        margin-bottom: 12px;
        color: #00997d;
    }

    .upload-placeholder span {
        display: block;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .upload-placeholder small {
        font-size: 12px;
        color: #aaa;
    }

    .upload-preview {
        position: relative;
        padding: 10px;
    }

    .upload-preview img {
        max-width: 100%;
        max-height: 200px;
        border-radius: 8px;
    }

    .btn-remove-preview {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 28px;
        height: 28px;
        background: #e74c3c;
        color: #fff;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 20;
    }

    .upload-note {
        display: block;
        margin-top: 8px;
        font-size: 12px;
        color: #e74c3c;
    }

    .field-label {
        display: block;
        font-size: 14px;
        margin-bottom: 10px;
        color: #333;
    }

    /* QR Modal */
    #qrModal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    #qrModal.active {
        display: flex;
    }

    .qr-modal-content {
        background: #fff;
        padding: 24px;
        border-radius: 16px;
        text-align: center;
        max-width: 320px;
    }

    .qr-modal-content img {
        max-width: 260px;
        border-radius: 12px;
        margin-bottom: 12px;
    }

    .qr-modal-content h4 {
        margin: 0 0 8px 0;
        font-size: 16px;
    }

    .qr-modal-close {
        margin-top: 16px;
        padding: 10px 24px;
        background: #333;
        color: #fff;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }
</style>

<!-- Section: Pembayaran -->
<div class="form-section payment-section">
    <div class="section-title">
        <i class="icofont-money"></i>
        <span>Informasi Pembayaran</span>
    </div>

    <div class="payment-notice">
        <div class="payment-notice-icon">
            <i class="icofont-warning-alt"></i>
        </div>
        <div class="payment-notice-text">
            <strong>Event Berbayar</strong>
            <p>Silakan lakukan pembayaran, kemudian upload bukti pembayaran. Pendaftaran akan diverifikasi oleh admin.
            </p>
        </div>
    </div>

    <div class="payment-amount-box">
        <span>Total Pembayaran:</span>
        <strong>Rp <?php echo number_format($event['price'], 0, ',', '.'); ?></strong>
    </div>

    <!-- Payment Methods -->
    <div class="payment-methods-container">
        <label class="field-label"><strong>📝 Pilih Metode Pembayaran:</strong></label>

        <?php if (!empty($banks)): ?>
            <div class="payment-group">
                <div class="payment-group-title"><i class="icofont-bank"></i> Transfer Bank</div>
                <div class="payment-items">
                    <?php foreach ($banks as $pm): ?>
                        <div class="payment-item">
                            <div class="payment-item-left">
                                <?php if ($pm['icon']): ?>
                                    <img src="<?php echo $pm['icon']; ?>" alt="<?php echo htmlspecialchars($pm['name']); ?>"
                                        class="payment-icon">
                                <?php else: ?>
                                    <div class="payment-icon-placeholder"><i class="icofont-bank"></i></div>
                                <?php endif; ?>
                                <div class="payment-item-info">
                                    <strong><?php echo htmlspecialchars($pm['name']); ?></strong>
                                    <code><?php echo htmlspecialchars($pm['account_number'] ?? ''); ?></code>
                                    <small>a.n. <?php echo htmlspecialchars($pm['account_name'] ?? ''); ?></small>
                                </div>
                            </div>
                            <button type="button" class="btn-copy"
                                onclick="copyToClipboard('<?php echo htmlspecialchars($pm['account_number'] ?? ''); ?>')">
                                <i class="icofont-ui-copy"></i> Salin
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($ewallets)): ?>
            <div class="payment-group">
                <div class="payment-group-title"><i class="icofont-wallet"></i> E-Wallet</div>
                <div class="payment-items">
                    <?php foreach ($ewallets as $pm): ?>
                        <div class="payment-item">
                            <div class="payment-item-left">
                                <?php if ($pm['icon']): ?>
                                    <img src="<?php echo $pm['icon']; ?>" alt="<?php echo htmlspecialchars($pm['name']); ?>"
                                        class="payment-icon">
                                <?php else: ?>
                                    <div class="payment-icon-placeholder"><i class="icofont-wallet"></i></div>
                                <?php endif; ?>
                                <div class="payment-item-info">
                                    <strong><?php echo htmlspecialchars($pm['name']); ?></strong>
                                    <code><?php echo htmlspecialchars($pm['account_number'] ?? ''); ?></code>
                                    <small>a.n. <?php echo htmlspecialchars($pm['account_name'] ?? ''); ?></small>
                                </div>
                            </div>
                            <?php if ($pm['qr_image']): ?>
                                <button type="button" class="btn-qr"
                                    onclick="showQRCode('<?php echo $pm['qr_image']; ?>', '<?php echo htmlspecialchars($pm['name']); ?>')">
                                    <i class="icofont-qr-code"></i>
                                </button>
                            <?php endif; ?>
                            <button type="button" class="btn-copy"
                                onclick="copyToClipboard('<?php echo htmlspecialchars($pm['account_number'] ?? ''); ?>')">
                                <i class="icofont-ui-copy"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($qris_items)): ?>
            <div class="payment-group">
                <div class="payment-group-title"><i class="icofont-qr-code"></i> QRIS</div>
                <div class="payment-items">
                    <?php foreach ($qris_items as $pm): ?>
                        <div class="payment-item qris-item">
                            <?php if ($pm['qr_image']): ?>
                                <img src="<?php echo $pm['qr_image']; ?>" alt="QRIS" class="qris-image"
                                    onclick="showQRCode('<?php echo $pm['qr_image']; ?>', 'QRIS')">
                                <small>Klik gambar untuk memperbesar</small>
                            <?php else: ?>
                                <div class="payment-icon-placeholder large"><i class="icofont-qr-code"></i></div>
                                <small><?php echo htmlspecialchars($pm['name']); ?></small>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (empty($payment_methods)): ?>
            <div class="payment-group">
                <p style="text-align:center; color:#999; padding:20px;">
                    <i class="icofont-warning-alt" style="font-size:32px;display:block;margin-bottom:10px;"></i>
                    Metode pembayaran belum dikonfigurasi.<br>
                    Silakan hubungi admin untuk info pembayaran.
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Payment Proof Upload -->
    <div class="payment-proof-section">
        <label class="field-label"><strong>📤 Upload Bukti Pembayaran</strong> <span class="required">*</span></label>
        <div class="upload-container" id="uploadContainer">
            <input type="file" name="payment_proof" id="paymentProofInput" accept="image/*" required
                onchange="previewPaymentProof(this)">
            <div class="upload-placeholder" id="uploadPlaceholder">
                <i class="icofont-cloud-upload"></i>
                <span>Klik atau drag gambar bukti transfer disini</span>
                <small>Format: JPG, PNG, maksimal 5MB</small>
            </div>
            <div class="upload-preview" id="uploadPreview" style="display:none;">
                <img id="previewImage" src="" alt="Preview">
                <button type="button" class="btn-remove-preview" onclick="removePaymentProof()">
                    <i class="icofont-close"></i>
                </button>
            </div>
        </div>
        <small class="upload-note">* Wajib upload bukti pembayaran untuk verifikasi admin</small>
    </div>

    <input type="hidden" name="payment_date" id="paymentDateInput" value="">

    <div class="field-group">
        <label>No. WhatsApp <span class="required">*</span></label>
        <input type="tel" name="whatsapp" required placeholder="628xxxxxxxxxx">
        <small>Admin akan menghubungi via WhatsApp untuk konfirmasi</small>
    </div>
</div>

<!-- QR Modal -->
<div id="qrModal" onclick="closeQRModal()">
    <div class="qr-modal-content" onclick="event.stopPropagation()">
        <img id="qrModalImage" src="" alt="QR Code">
        <h4 id="qrModalTitle">QRIS</h4>
        <button class="qr-modal-close" onclick="closeQRModal()">Tutup</button>
    </div>
</div>