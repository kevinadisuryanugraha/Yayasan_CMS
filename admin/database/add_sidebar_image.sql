-- Add background_image field to campaign_sidebar table
ALTER TABLE `campaign_sidebar` ADD COLUMN `background_image` varchar(255) DEFAULT NULL AFTER `headline`;
