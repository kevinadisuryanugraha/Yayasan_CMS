<?php
/**
 * Site Settings Helper
 * Load site_settings from database for frontend use
 */

// Make sure we have database connection
if (!isset($conn)) {
    include_once __DIR__ . '/../admin/koneksi.php';
}

// Fetch site settings
$site_settings_query = @mysqli_query($conn, "SELECT * FROM site_settings WHERE id = 1 LIMIT 1");
$site_settings = $site_settings_query ? mysqli_fetch_assoc($site_settings_query) : null;

// Set defaults if no settings found
if (!$site_settings) {
    $site_settings = [
        'site_name' => 'Hafsa Islamic Center',
        'site_tagline' => 'Path to Harmony and Faith',
        'site_description' => '',
        'phone_primary' => '+88019 339 702 520',
        'phone_secondary' => '',
        'email_primary' => 'admin@hafsa.com',
        'email_secondary' => '',
        'address' => '30 North West New York 240',
        'facebook_url' => '#',
        'instagram_url' => '#',
        'twitter_url' => '#',
        'youtube_url' => '#',
        'whatsapp_number' => '',
        'logo_light' => 'assets/images/logo/01.png',
        'logo_dark' => 'assets/images/logo/01.png',
        'favicon' => '',
        'working_hours' => '',
        'map_embed_url' => '',
        'latitude' => '',
        'longitude' => '',
        'footer_text' => 'Hafsa is a nonprofit organization supported by community leaders',
        'copyright_text' => '©2024 Hafsa - Islamic Center'
    ];
}
