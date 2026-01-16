-- Cleanup Event, Donation, Registration Tables
-- Backup your database before running this!
-- Run this script in phpMyAdmin

SET FOREIGN_KEY_CHECKS = 0;

-- Drop registration and donation tables first (have foreign keys)
DROP TABLE IF EXISTS `event_registrations`;
DROP TABLE IF EXISTS `donations`;

-- Drop payment methods
DROP TABLE IF EXISTS `payment_methods`;

SET FOREIGN_KEY_CHECKS = 1;

-- Cleanup permissions related to events (optional - keeps admin role working)
-- DELETE FROM `permissions` WHERE `module` = 'events';

-- Show confirmation
SELECT 'Cleanup completed successfully!' AS status;
