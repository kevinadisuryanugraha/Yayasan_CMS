-- =============================================
-- Tabel Event Registrations
-- Relasi ke tabel events dengan FK
-- =============================================

CREATE TABLE IF NOT EXISTS `event_registrations` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `event_id` INT(11) NOT NULL,
    `registration_code` VARCHAR(20) NOT NULL UNIQUE,
    
    -- Data Peserta
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `whatsapp` VARCHAR(20) DEFAULT NULL,
    `gender` ENUM('male', 'female') DEFAULT NULL,
    `age` INT(3) DEFAULT NULL,
    `address` TEXT DEFAULT NULL,
    `city` VARCHAR(100) DEFAULT NULL,
    `institution` VARCHAR(150) DEFAULT NULL COMMENT 'Instansi/Lembaga/Sekolah',
    
    -- Status Registrasi
    `status` ENUM('pending', 'confirmed', 'cancelled', 'attended') DEFAULT 'pending',
    `payment_status` ENUM('unpaid', 'paid', 'refunded') DEFAULT 'unpaid',
    `payment_amount` DECIMAL(12,2) DEFAULT 0.00,
    `payment_proof` VARCHAR(255) DEFAULT NULL COMMENT 'Path bukti pembayaran',
    `payment_date` DATETIME DEFAULT NULL,
    
    -- Catatan
    `notes` TEXT DEFAULT NULL COMMENT 'Catatan dari peserta',
    `admin_notes` TEXT DEFAULT NULL COMMENT 'Catatan dari admin',
    
    -- Timestamps
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `confirmed_at` DATETIME DEFAULT NULL,
    
    -- Indexes
    INDEX `idx_event_id` (`event_id`),
    INDEX `idx_email` (`email`),
    INDEX `idx_status` (`status`),
    INDEX `idx_registration_code` (`registration_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Trigger untuk update kolom 'registered' di tabel events
-- =============================================

DELIMITER //

-- Trigger: Setelah INSERT registrasi baru (status confirmed)
CREATE TRIGGER `after_registration_insert` 
AFTER INSERT ON `event_registrations`
FOR EACH ROW
BEGIN
    IF NEW.status = 'confirmed' THEN
        UPDATE `events` SET `registered` = `registered` + 1 WHERE `id` = NEW.event_id;
    END IF;
END//

-- Trigger: Setelah UPDATE status registrasi
CREATE TRIGGER `after_registration_update`
AFTER UPDATE ON `event_registrations`
FOR EACH ROW
BEGIN
    -- Jika status berubah dari non-confirmed ke confirmed
    IF OLD.status != 'confirmed' AND NEW.status = 'confirmed' THEN
        UPDATE `events` SET `registered` = `registered` + 1 WHERE `id` = NEW.event_id;
    -- Jika status berubah dari confirmed ke non-confirmed
    ELSEIF OLD.status = 'confirmed' AND NEW.status != 'confirmed' THEN
        UPDATE `events` SET `registered` = GREATEST(`registered` - 1, 0) WHERE `id` = NEW.event_id;
    END IF;
END//

-- Trigger: Setelah DELETE registrasi
CREATE TRIGGER `after_registration_delete`
AFTER DELETE ON `event_registrations`
FOR EACH ROW
BEGIN
    IF OLD.status = 'confirmed' THEN
        UPDATE `events` SET `registered` = GREATEST(`registered` - 1, 0) WHERE `id` = OLD.event_id;
    END IF;
END//

DELIMITER ;

-- =============================================
-- Selesai
-- =============================================
