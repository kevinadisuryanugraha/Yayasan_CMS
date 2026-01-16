<?php
// Load site settings if not already loaded
if (!isset($site_settings)) {
    include_once __DIR__ . '/site_settings.php';
}
?>

<style>
    /* ============================================
   FOOTER REDESIGN - Clean & Professional
   ============================================ */

    /* Footer Top - Contact Info Bar */
    .footer-section .footer-top {
        background: rgba(0, 0, 0, 0.3);
        padding: 25px 0;
    }

    .footer-section .footer-top-item {
        height: 100%;
    }

    .footer-section .footer-top .lab-inner {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 10px 15px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        height: 100%;
        transition: all 0.3s ease;
    }

    .footer-section .footer-top .lab-inner:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .footer-section .footer-top .lab-thumb {
        flex-shrink: 0;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .footer-section .footer-top .lab-thumb img {
        width: 40px;
        height: auto;
    }

    .footer-section .footer-top .lab-content {
        flex: 1;
        min-width: 0;
    }

    .footer-section .footer-top .lab-content span {
        display: block;
        font-size: 14px;
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.5;
        word-break: break-word;
    }

    /* Footer Middle - Main Content */
    .footer-section .footer-middle {
        padding: 60px 0 40px;
    }

    .footer-section .fm-item-title h5 {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        text-transform: uppercase;
        margin-bottom: 25px;
        position: relative;
        padding-bottom: 12px;
    }

    .footer-section .fm-item-title h5::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 3px;
        background: var(--accent-color, #8bc34a);
        border-radius: 2px;
    }

    .footer-section .fm-item-content p {
        color: rgba(255, 255, 255, 0.75);
        font-size: 14px;
        line-height: 1.7;
        margin-bottom: 20px;
    }

    /* About Section Image */
    .footer-section .footer-abt-img {
        max-width: 100%;
        height: 130px;
        object-fit: cover;
        border-radius: 8px;
        border: 3px solid rgba(255, 255, 255, 0.1);
    }

    /* Quick Links */
    .footer-links {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .footer-links li {
        margin-bottom: 12px;
    }

    .footer-links li:last-child {
        margin-bottom: 0;
    }

    .footer-links li a {
        color: rgba(255, 255, 255, 0.75);
        font-size: 14px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }

    .footer-links li a:hover {
        color: var(--accent-color, #8bc34a);
        padding-left: 8px;
    }

    .footer-links li a i {
        margin-right: 10px;
        font-size: 10px;
        color: var(--accent-color, #8bc34a);
    }

    /* Newsletter Form */
    .footer-section .newsletter-form .form-control {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        padding: 12px 15px;
        color: #fff;
        font-size: 14px;
    }

    .footer-section .newsletter-form .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .footer-section .newsletter-form .form-control:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: var(--accent-color, #8bc34a);
        box-shadow: 0 0 0 3px rgba(139, 195, 74, 0.2);
        outline: none;
    }

    .footer-section .newsletter-form .lab-btn {
        background: var(--accent-color, #8bc34a);
        color: #fff;
        border: none;
        padding: 12px 25px;
        border-radius: var(--btn-radius, 8px);
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .footer-section .newsletter-form .lab-btn:hover {
        background: var(--primary-color, #2E7D32);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    /* Social Icons */
    .footer-section .social-icons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .footer-section .social-icons li a {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .footer-section .social-icons li a:hover {
        background: var(--accent-color, #8bc34a);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(139, 195, 74, 0.4);
    }

    /* Footer Bottom - Copyright */
    .footer-section .footer-bottom {
        background: rgba(0, 0, 0, 0.3);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .footer-section .footer-bottom-content p {
        color: rgba(255, 255, 255, 0.7);
        font-size: 14px;
        margin: 0;
    }

    /* ============================================
   RESPONSIVE DESIGN
   ============================================ */

    @media (max-width: 991px) {
        .footer-section .footer-top .row {
            gap: 15px;
        }

        .footer-section .footer-top .lab-inner {
            justify-content: center;
            text-align: center;
            flex-direction: column;
            padding: 20px;
        }

        .footer-section .footer-middle {
            padding: 50px 0 30px;
        }

        .footer-section .footer-middle-item,
        .footer-section .footer-middle-item-3 {
            margin-bottom: 30px;
        }

        .footer-section .fm-item-title h5::after {
            left: 50%;
            transform: translateX(-50%);
        }

        .footer-section .fm-item-content {
            text-align: center;
        }

        .footer-section .footer-links {
            display: inline-block;
            text-align: left;
        }

        .footer-section .social-icons {
            justify-content: center;
        }
    }

    @media (max-width: 767px) {
        .footer-section .footer-top {
            padding: 20px 0;
        }

        .footer-section .footer-top .lab-content span {
            font-size: 13px;
        }

        .footer-section .footer-middle {
            padding: 40px 0 20px;
        }

        .footer-section .fm-item-title h5 {
            font-size: 16px;
        }

        .footer-section .footer-abt-img {
            height: 100px;
        }

        .footer-section .newsletter-form .lab-btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 575px) {
        .footer-section .footer-top .lab-thumb {
            width: 35px;
            height: 35px;
        }

        .footer-section .footer-top .lab-thumb img {
            width: 30px;
        }

        .footer-section .social-icons li a {
            width: 36px;
            height: 36px;
            font-size: 14px;
        }
    }
</style>

<footer class="footer-section" style="background-image: url(assets/images/bg-images/footer-bg.png);">
    <!-- Footer Top - Contact Info -->
    <div class="footer-top">
        <div class="container">
            <div class="row g-3 justify-content-center">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="footer-top-item lab-item">
                        <div class="lab-inner">
                            <div class="lab-thumb">
                                <img src="assets/images/footer/footer-top/01.png" alt="Phone">
                            </div>
                            <div class="lab-content">
                                <span><strong>Phone:</strong><br><?php echo htmlspecialchars($site_settings['phone_primary'] ?? '+62 812 3456 7890'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="footer-top-item lab-item">
                        <div class="lab-inner">
                            <div class="lab-thumb">
                                <img src="assets/images/footer/footer-top/02.png" alt="Email">
                            </div>
                            <div class="lab-content">
                                <span><strong>Email:</strong><br><?php echo htmlspecialchars($site_settings['email_primary'] ?? 'info@hafsa.com'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-12">
                    <div class="footer-top-item lab-item">
                        <div class="lab-inner">
                            <div class="lab-thumb">
                                <img src="assets/images/footer/footer-top/03.png" alt="Location">
                            </div>
                            <div class="lab-content">
                                <span><strong>Address:</strong><br><?php echo htmlspecialchars($site_settings['address'] ?? 'Jakarta, Indonesia'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Middle - Main Content -->
    <div class="footer-middle padding-tb">
        <div class="container">
            <div class="row g-4">
                <!-- About Column -->
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="footer-middle-item">
                        <div class="fm-item-title">
                            <h5>About <?php echo htmlspecialchars($site_settings['site_name'] ?? 'Hafsa'); ?></h5>
                        </div>
                        <div class="fm-item-content">
                            <p><?php echo htmlspecialchars($site_settings['footer_text'] ?? 'We are a nonprofit organization dedicated to serving the community with faith, compassion, and integrity.'); ?>
                            </p>

                            <!-- Social Icons in About Section -->
                            <?php if (!empty($site_settings['facebook_url']) || !empty($site_settings['instagram_url']) || !empty($site_settings['youtube_url']) || !empty($site_settings['whatsapp_number'])): ?>
                                <ul class="social-icons">
                                    <?php if (!empty($site_settings['facebook_url'])): ?>
                                        <li><a href="<?php echo htmlspecialchars($site_settings['facebook_url']); ?>"
                                                target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                    <?php endif; ?>
                                    <?php if (!empty($site_settings['twitter_url'])): ?>
                                        <li><a href="<?php echo htmlspecialchars($site_settings['twitter_url']); ?>"
                                                target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                    <?php endif; ?>
                                    <?php if (!empty($site_settings['instagram_url'])): ?>
                                        <li><a href="<?php echo htmlspecialchars($site_settings['instagram_url']); ?>"
                                                target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                                    <?php endif; ?>
                                    <?php if (!empty($site_settings['youtube_url'])): ?>
                                        <li><a href="<?php echo htmlspecialchars($site_settings['youtube_url']); ?>"
                                                target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a></li>
                                    <?php endif; ?>
                                    <?php if (!empty($site_settings['whatsapp_number'])): ?>
                                        <li><a href="https://wa.me/<?php echo htmlspecialchars($site_settings['whatsapp_number']); ?>"
                                                target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a></li>
                                    <?php endif; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Quick Links Column -->
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="footer-middle-item">
                        <div class="fm-item-title">
                            <h5>Quick Links</h5>
                        </div>
                        <div class="fm-item-content">
                            <ul class="footer-links">
                                <li><a href="index.php"><i class="icofont-double-right"></i> Home</a></li>
                                <li><a href="index.php?page=about"><i class="icofont-double-right"></i> About Us</a>
                                </li>
                                <li><a href="index.php?page=programs"><i class="icofont-double-right"></i> Programs</a>
                                </li>
                                <li><a href="index.php?page=gallery"><i class="icofont-double-right"></i> Gallery</a>
                                </li>
                                <li><a href="index.php?page=contact"><i class="icofont-double-right"></i> Contact</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Newsletter Column -->
                <div class="col-lg-4 col-md-12 col-12">
                    <div class="footer-middle-item-3">
                        <div class="fm-item-title">
                            <h5>Newsletter</h5>
                        </div>
                        <div class="fm-item-content">
                            <p>Subscribe to our newsletter for updates on events, programs, and community news.</p>
                            <form class="newsletter-form">
                                <div class="form-group mb-3">
                                    <input type="email" class="form-control" placeholder="Enter your email" required>
                                </div>
                                <button type="submit" class="lab-btn">
                                    Subscribe <i class="icofont-paper-plane"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom - Copyright -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="footer-bottom-content text-center py-3">
                        <p><?php echo htmlspecialchars($site_settings['copyright_text'] ?? '© ' . date('Y') . ' ' . ($site_settings['site_name'] ?? 'Hafsa') . ' - All Rights Reserved'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>