<?php
// Contact Page - Hubungi Kami
// Note: Database connection ($conn) is already available via index.php

// Fetch Appearance Settings
$appearance_query = mysqli_query($conn, "SELECT * FROM appearance_settings LIMIT 1");
$appearance = mysqli_fetch_assoc($appearance_query);

$cms_primary = $appearance['primary_color'] ?? '#00997d';
$cms_secondary = $appearance['secondary_color'] ?? '#0a294a';
$cms_accent = $appearance['accent_color'] ?? '#fab702';

// Fetch Site Settings for contact info
$site_query = mysqli_query($conn, "SELECT * FROM site_settings LIMIT 1");
$site_settings = mysqli_fetch_assoc($site_query);

// Default values
$contact_address = $site_settings['address'] ?? 'Jl. Contoh No. 123, Kota, Indonesia';
$contact_phone = $site_settings['phone_primary'] ?? '+62 812 3456 7890';
$contact_whatsapp = $site_settings['whatsapp_number'] ?? '6281234567890';
$contact_email = $site_settings['email_primary'] ?? 'info@yibbi.org';
$site_name = $site_settings['site_name'] ?? 'Yayasan Indonesia Bijak Bestari';

// Social media links
$social_instagram = $site_settings['instagram_url'] ?? '#';
$social_facebook = $site_settings['facebook_url'] ?? '#';
$social_youtube = $site_settings['youtube_url'] ?? '#';
$social_twitter = $site_settings['twitter_url'] ?? '#';

// Operating hours (could be dynamic from database)
$operating_hours = 'Senin - Jumat: 08:00 - 17:00 WIB';
?>

<!-- Contact Page External Stylesheet -->
<link rel="stylesheet" href="assets/css/contact.css">

<!-- Dynamic CSS Variables -->
<style>
    :root {
        --contact-primary:
            <?php echo $cms_primary; ?>
        ;
        --contact-secondary:
            <?php echo $cms_secondary; ?>
        ;
        --contact-accent:
            <?php echo $cms_accent; ?>
        ;
    }
</style>

<!-- ========================================
     HERO BANNER
======================================== -->
<section class="contact-hero-section" aria-label="Banner Halaman Kontak">
    <div class="contact-hero-float float-1" aria-hidden="true"></div>
    <div class="contact-hero-float float-2" aria-hidden="true"></div>

    <div class="container">
        <div class="contact-hero-content">
            <div class="contact-hero-badge" aria-label="Identitas Halaman">
                <i class="icofont-envelope" aria-hidden="true"></i>
                <span>Hubungi Kami</span>
            </div>
            <h1 class="contact-hero-title text-white">Kontak</h1>
            <p class="contact-hero-desc text-white">
                Ada pertanyaan atau ingin berkolaborasi? Silakan hubungi kami melalui form di bawah ini.
            </p>
            <nav class="contact-breadcrumb" aria-label="Navigasi Breadcrumb">
                <a href="index.php" aria-label="Kembali ke Beranda">
                    <i class="icofont-home" aria-hidden="true"></i> Beranda
                </a>
                <i class="icofont-rounded-right" aria-hidden="true"></i>
                <span aria-current="page">Kontak</span>
            </nav>
        </div>
    </div>
</section>

<!-- ========================================
     MAIN CONTACT SECTION
======================================== -->
<section class="contact-main-section">
    <div class="container">
        <div class="contact-grid">
            <!-- Contact Form -->
            <div class="contact-form-card">
                <div class="contact-form-header">
                    <h2>Kirim Pesan</h2>
                    <p>Isi form di bawah ini dan kami akan segera menghubungi Anda.</p>
                </div>

                <form class="contact-form" id="contactForm">
                    <div class="form-group">
                        <label>
                            <i class="icofont-user"></i>
                            Nama Lengkap <span class="required">*</span>
                        </label>
                        <input type="text" name="name" id="contactName" placeholder="Masukkan nama lengkap Anda"
                            required>
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="icofont-email"></i>
                            Email <span class="required">*</span>
                        </label>
                        <input type="email" name="email" id="contactEmail" placeholder="contoh@email.com" required>
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="icofont-whatsapp"></i>
                            Nomor WhatsApp
                        </label>
                        <div class="phone-input-group">
                            <div class="phone-prefix">
                                <span>🇮🇩</span> +62
                            </div>
                            <input type="tel" name="phone" id="contactPhone" placeholder="8123456789">
                        </div>
                        <p class="form-hint">Masukkan nomor tanpa angka 0 di depan</p>
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="icofont-speech-comments"></i>
                            Subjek
                        </label>
                        <input type="text" name="subject" id="contactSubject" placeholder="Subjek pesan (opsional)">
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="icofont-paper"></i>
                            Pesan <span class="required">*</span>
                        </label>
                        <textarea name="message" id="contactMessage" placeholder="Tuliskan pesan Anda..."
                            required></textarea>
                    </div>

                    <div class="contact-form-actions">
                        <button type="button" class="btn-contact-submit" id="btnWhatsApp">
                            <i class="icofont-whatsapp"></i>
                            Hubungi via WhatsApp
                        </button>
                        <button type="submit" class="btn-contact-email" id="btnSubmit">
                            Kirim Pesan
                        </button>
                    </div>
                </form>

                <!-- Success Message (hidden by default) -->
                <div class="contact-success" id="contactSuccess" style="display: none;">
                    <div class="contact-success-icon">
                        <i class="icofont-check-circled"></i>
                    </div>
                    <h3>Pesan Terkirim!</h3>
                    <p>Terima kasih telah menghubungi kami. Kami akan segera merespon pesan Anda.</p>
                    <button type="button" class="btn-send-another" onclick="resetContactForm()">
                        <i class="icofont-refresh"></i> Kirim Pesan Lain
                    </button>
                </div>
            </div>

            <!-- Contact Info Sidebar -->
            <div class="contact-info-card">
                <div class="contact-info-header">
                    <h3>Informasi Kontak</h3>
                </div>

                <div class="contact-info-list">
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="icofont-location-pin"></i>
                        </div>
                        <div class="contact-info-content">
                            <label>ALAMAT</label>
                            <p>
                                <?php echo htmlspecialchars($contact_address); ?>
                            </p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="icofont-whatsapp"></i>
                        </div>
                        <div class="contact-info-content">
                            <label>WHATSAPP</label>
                            <p>
                                <a href="https://wa.me/<?php echo $contact_whatsapp; ?>" target="_blank">
                                    <?php echo htmlspecialchars($contact_phone); ?>
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="icofont-email"></i>
                        </div>
                        <div class="contact-info-content">
                            <label>EMAIL</label>
                            <p>
                                <a href="mailto:<?php echo $contact_email; ?>">
                                    <?php echo htmlspecialchars($contact_email); ?>
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="icofont-clock-time"></i>
                        </div>
                        <div class="contact-info-content">
                            <label>JAM OPERASIONAL</label>
                            <p>
                                <?php echo htmlspecialchars($operating_hours); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="contact-social">
                    <h4>Ikuti Kami</h4>
                    <div class="contact-social-links">
                        <?php if (!empty($social_instagram) && $social_instagram != '#'): ?>
                            <a href="<?php echo $social_instagram; ?>" target="_blank" title="Instagram">
                                <i class="icofont-instagram text-dark"></i>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($social_facebook) && $social_facebook != '#'): ?>
                            <a href="<?php echo $social_facebook; ?>" target="_blank" title="Facebook">
                                <i class="icofont-facebook text-dark"></i>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($social_youtube) && $social_youtube != '#'): ?>
                            <a href="<?php echo $social_youtube; ?>" target="_blank" title="YouTube">
                                <i class="icofont-youtube-play text-dark"></i>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($social_twitter) && $social_twitter != '#'): ?>
                            <a href="<?php echo $social_twitter; ?>" target="_blank" title="Twitter">
                                <i class="icofont-brand-twitter text-dark"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================
     MAP SECTION
======================================== -->
<section class="contact-map-section">
    <div class="contact-map-wrapper">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.2903276036657!2d106.82729047475024!3d-6.227289393772915!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sJakarta!5e0!3m2!1sen!2sid!4v1704963025123!5m2!1sen!2sid"
            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi Kami">
        </iframe>
    </div>
</section>

<!-- ========================================
     JAVASCRIPT
======================================== -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('contactForm');
        const btnWhatsApp = document.getElementById('btnWhatsApp');
        const btnSubmit = document.getElementById('btnSubmit');
        const successDiv = document.getElementById('contactSuccess');
        const whatsappNumber = '<?php echo $contact_whatsapp; ?>';

        // WhatsApp Button Handler
        btnWhatsApp.addEventListener('click', function () {
            const name = document.getElementById('contactName').value.trim();
            const phone = document.getElementById('contactPhone').value.trim();
            const subject = document.getElementById('contactSubject').value.trim();
            const message = document.getElementById('contactMessage').value.trim();

            if (!name) {
                alert('Silakan masukkan nama Anda terlebih dahulu.');
                document.getElementById('contactName').focus();
                return;
            }

            if (!message) {
                alert('Silakan masukkan pesan Anda terlebih dahulu.');
                document.getElementById('contactMessage').focus();
                return;
            }

            // Build WhatsApp message
            let waMessage = `Halo, saya ${name}`;
            if (phone) waMessage += ` (${phone})`;
            waMessage += `.`;
            if (subject) waMessage += `\n\nSubjek: ${subject}`;
            waMessage += `\n\nPesan:\n${message}`;

            // Open WhatsApp
            const waUrl = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(waMessage)}`;
            window.open(waUrl, '_blank');
        });

        // Form Submit Handler (Email)
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = btnSubmit;
            submitBtn.classList.add('btn-loading');
            submitBtn.disabled = true;

            const formData = new FormData(form);

            // Add phone prefix if phone provided
            const phone = document.getElementById('contactPhone').value.trim();
            if (phone) {
                formData.set('phone', '+62' + phone);
            }

            fetch('ajax/submit_contact.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    submitBtn.classList.remove('btn-loading');
                    submitBtn.disabled = false;

                    if (data.success) {
                        // Hide form, show success
                        form.style.display = 'none';
                        successDiv.style.display = 'block';
                    } else {
                        alert(data.message || 'Gagal mengirim pesan. Silakan coba lagi.');
                    }
                })
                .catch(error => {
                    submitBtn.classList.remove('btn-loading');
                    submitBtn.disabled = false;
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                });
        });
    });

    // Reset form and show it again
    function resetContactForm() {
        const form = document.getElementById('contactForm');
        const successDiv = document.getElementById('contactSuccess');

        form.reset();
        form.style.display = 'block';
        successDiv.style.display = 'none';
    }
</script>