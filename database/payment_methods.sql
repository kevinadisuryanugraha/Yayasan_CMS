-- =====================================================
-- Payment Methods Table
-- Untuk menyimpan metode pembayaran (Bank, E-Wallet, QRIS)
-- =====================================================

CREATE TABLE IF NOT EXISTS payment_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('bank', 'ewallet', 'qris') NOT NULL COMMENT 'Tipe: bank/ewallet/qris',
    name VARCHAR(100) NOT NULL COMMENT 'Nama bank/e-wallet',
    account_number VARCHAR(50) NULL COMMENT 'Nomor rekening/nomor HP',
    account_name VARCHAR(100) NULL COMMENT 'Nama pemilik rekening',
    icon VARCHAR(255) NULL COMMENT 'Path icon/logo',
    qr_image VARCHAR(255) NULL COMMENT 'Path gambar QR Code',
    instructions TEXT NULL COMMENT 'Instruksi pembayaran',
    is_active TINYINT(1) DEFAULT 1 COMMENT '1=aktif, 0=nonaktif',
    sort_order INT DEFAULT 0 COMMENT 'Urutan tampilan',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_type (type),
    INDEX idx_active (is_active),
    INDEX idx_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample data (uncomment jika ingin menambahkan data contoh)
-- INSERT INTO payment_methods (type, name, account_number, account_name, sort_order) VALUES
-- ('bank', 'Bank BCA', '1234567890', 'Yayasan ABC', 1),
-- ('bank', 'Bank Mandiri', '0987654321', 'Yayasan ABC', 2),
-- ('bank', 'Bank BNI', '1122334455', 'Yayasan ABC', 3),
-- ('bank', 'Bank BRI', '5566778899', 'Yayasan ABC', 4),
-- ('ewallet', 'GoPay', '081234567890', 'Yayasan ABC', 5),
-- ('ewallet', 'Dana', '081234567890', 'Yayasan ABC', 6),
-- ('ewallet', 'ShopeePay', '081234567890', 'Yayasan ABC', 7),
-- ('ewallet', 'OVO', '081234567890', 'Yayasan ABC', 8),
-- ('qris', 'QRIS Universal', NULL, NULL, 9);
