<?php
/**
 * Appearance Settings Loader
 * Loads appearance settings and generates CSS variables
 */

// Load appearance settings from database
$appearance_settings = [
    'primary_color' => '#2E7D32',
    'secondary_color' => '#1565C0',
    'accent_color' => '#FF9800',
    'font_family' => 'Poppins',
    'button_style' => 'rounded'
];

if (isset($conn)) {
    $app_query = mysqli_query($conn, "SELECT * FROM appearance_settings WHERE id = 1");
    if ($app_query && mysqli_num_rows($app_query) > 0) {
        $appearance_settings = mysqli_fetch_assoc($app_query);
    }
}

// Button radius mapping
$button_radius_map = [
    'square' => '0px',
    'rounded' => '8px',
    'pill' => '50px'
];
$btn_radius = $button_radius_map[$appearance_settings['button_style']] ?? '8px';

// Google Fonts URL
$font_family = $appearance_settings['font_family'];
$google_fonts_url = "https://fonts.googleapis.com/css2?family=" . urlencode($font_family) . ":wght@300;400;500;600;700&display=swap";

/**
 * Generate CSS variables style block
 */
function getAppearanceCss($settings, $btn_radius, $font_family)
{
    $primary = htmlspecialchars($settings['primary_color']);
    $secondary = htmlspecialchars($settings['secondary_color']);
    $accent = htmlspecialchars($settings['accent_color']);

    return "
    <style id='appearance-styles'>
        :root {
            --primary-color: {$primary};
            --secondary-color: {$secondary};
            --accent-color: {$accent};
            --btn-radius: {$btn_radius};
            --font-family: '{$font_family}', sans-serif;
        }
        
        /* Apply primary color */
        .bg-primary, .btn-primary, 
        .header-section, .header-bottom,
        .scrollToTop, .scrollToTop:hover,
        .feature-item:hover .feature-thumb,
        .post-item .post-inner .post-footer-left .author-info .posted-on a:hover {
            background-color: var(--primary-color) !important;
        }
        
        a:hover, .text-primary,
        .header-section .menu > li > a:hover,
        .header-section .menu > li.active > a,
        .feature-item .feature-inner .feature-content h5 a:hover,
        .post-item .post-inner .post-content h4 a:hover {
            color: var(--primary-color) !important;
        }
        
        .border-primary {
            border-color: var(--primary-color) !important;
        }
        
        /* Apply secondary color */
        .bg-secondary, .btn-secondary {
            background-color: var(--secondary-color) !important;
        }
        
        .text-secondary {
            color: var(--secondary-color) !important;
        }
        
        /* Apply accent color */
        .bg-accent, .badge-warning,
        .service-item:hover .service-thumb::after {
            background-color: var(--accent-color) !important;
        }
        
        .text-accent {
            color: var(--accent-color) !important;
        }
        
        /* Button styles */
        .lab-btn, .btn, button[type='submit'],
        .default-btn, .custom-btn {
            border-radius: var(--btn-radius) !important;
        }
        
        /* Font family */
        body, h1, h2, h3, h4, h5, h6, p, a, span, li {
            font-family: var(--font-family);
        }
    </style>
    ";
}
?>