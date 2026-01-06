<?php
// Load site settings if not already loaded
if (!isset($site_settings)) {
    include_once __DIR__ . '/site_settings.php';
}
?>
<style>
/* Footer Responsive Styles */
.footer-section .footer-top .lab-inner {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
}
.footer-section .footer-top .lab-content span {
    word-break: break-word;
    font-size: 14px;
}
.footer-section .footer-middle .fm-item-content p {
    word-break: break-word;
}
.footer-section .social-icons {
    flex-wrap: wrap;
    gap: 8px;
}
.footer-section .social-icons li {
    margin: 0 !important;
}
.footer-section .social-icons li a {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    transition: all 0.3s ease;
}
.footer-section .social-icons li a:hover {
    background: #8bc34a;
    transform: translateY(-3px);
}
.footer-links {
    padding: 0;
    margin: 0;
    list-style: none;
}
.footer-links li {
    margin-bottom: 10px;
}
.footer-links li a {
    color: rgba(255,255,255,0.8);
    transition: all 0.3s ease;
}
.footer-links li a:hover {
    color: #8bc34a;
    padding-left: 5px;
}
.footer-links li a i {
    margin-right: 8px;
    font-size: 12px;
}

/* Mobile Responsive */
@media (max-width: 991px) {
    .footer-section .footer-top .row {
        gap: 15px;
    }
    .footer-section .footer-top .lab-inner {
        justify-content: center;
        text-align: center;
    }
    .footer-section .footer-middle .footer-middle-item,
    .footer-section .footer-middle .footer-middle-item-3 {
        text-align: center;
    }
    .footer-section .footer-middle .fm-item-content {
        display: flex;
        flex-direction: column;
        align-items: center;
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
    .footer-section .footer-top .lab-content span {
        font-size: 13px;
    }
    .footer-section .footer-middle {
        padding: 40px 0;
    }
    .footer-section .fm-item-title h5 {
        font-size: 18px;
    }
    .footer-section .fm-item-content p {
        font-size: 14px;
    }
    .footer-section .footer-abt-img {
        max-width: 100%;
        height: auto;
    }
    .footer-section .lab-btn {
        width: 100%;
        text-align: center;
    }
    .footer-section .form-group input {
        width: 100%;
    }
}

@media (max-width: 575px) {
    .footer-section .footer-top {
        padding: 20px 0;
    }
    .footer-section .footer-top .lab-inner {
        flex-direction: column;
        text-align: center;
    }
    .footer-section .footer-top .lab-thumb {
        margin-bottom: 10px;
    }
    .footer-section .footer-top .lab-content span {
        font-size: 12px;
    }
    .footer-section .social-icons li a {
        width: 32px;
        height: 32px;
        font-size: 14px;
    }
}
</style>

<footer class="footer-section" style="background-image: url(assets/images/bg-images/footer-bg.png);">
    <div class="footer-top">
        <div class="container">
            <div class="row g-3 justify-content-center g-lg-0">
                <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="footer-top-item lab-item">
                        <div class="lab-inner">
                            <div class="lab-thumb">
                                <img src="assets/images/footer/footer-top/01.png" alt="Phone-icon">
                            </div>
                            <div class="lab-content">
                                <span>Phone Number : <?php echo htmlspecialchars($site_settings['phone_primary'] ?? '+88019 339 702 520'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="footer-top-item lab-item">
                        <div class="lab-inner">
                            <div class="lab-thumb">
                                <img src="assets/images/footer/footer-top/02.png" alt="email-icon">
                            </div>
                            <div class="lab-content">
                                <span>Email : <?php echo htmlspecialchars($site_settings['email_primary'] ?? 'admin@hafsa.com'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <div class="footer-top-item lab-item">
                        <div class="lab-inner">
                            <div class="lab-thumb">
                                <img src="assets/images/footer/footer-top/03.png" alt="location-icon">
                            </div>
                            <div class="lab-content">
                                <span>Address : <?php echo htmlspecialchars($site_settings['address'] ?? '30 North West New York 240'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-middle padding-tb tri-shape-3">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="footer-middle-item-wrapper">
                        <div class="footer-middle-item mb-4 mb-lg-0">
                            <div class="fm-item-title">
                                <h5>About <?php echo htmlspecialchars($site_settings['site_name'] ?? 'Hafsa'); ?></h5>
                            </div>
                            <div class="fm-item-content">
                                <p class="mb-4"><?php echo htmlspecialchars($site_settings['footer_text'] ?? 'Energistically coordinate highly efficient procesr partnerships befor revolutionar growth strategie improvement'); ?></p>
                                <img src="assets/images/footer/footer-middle/01.jpg" alt="about-image" class="footer-abt-img img-fluid rounded">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="footer-middle-item-wrapper">
                        <div class="footer-middle-item mb-4 mb-lg-0">
                            <div class="fm-item-title">
                                <h5>Quick Links</h5>
                            </div>
                            <div class="fm-item-content">
                                <ul class="lab-ul footer-links">
                                    <li><a href="index.php"><i class="icofont-double-right"></i> Home</a></li>
                                    <li><a href="index.php?page=about"><i class="icofont-double-right"></i> About Us</a></li>
                                    <li><a href="index.php?page=events"><i class="icofont-double-right"></i> Events</a></li>
                                    <li><a href="index.php?page=programs"><i class="icofont-double-right"></i> Programs</a></li>
                                    <li><a href="index.php?page=contact"><i class="icofont-double-right"></i> Contact</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-12">
                    <div class="footer-middle-item-wrapper">
                        <div class="footer-middle-item-3 mb-4 mb-lg-0">
                            <div class="fm-item-title">
                                <h5>OUR NEWSLETTER</h5>
                            </div>
                            <div class="fm-item-content">
                                <p><?php echo htmlspecialchars($site_settings['site_name'] ?? 'Hafsa'); ?> is a nonprofit organization supported by community leaders</p>
                                <form class="newsletter-form">
                                    <div class="form-group mb-3">
                                        <input type="email" class="form-control" placeholder="Enter email" required>
                                    </div>
                                    <button type="submit" class="lab-btn">Send Message <i class="icofont-paper-plane"></i></button>
                                </form>

                                <!-- Social Media Links -->
                                <?php if (!empty($site_settings['facebook_url']) || !empty($site_settings['twitter_url']) || !empty($site_settings['instagram_url']) || !empty($site_settings['youtube_url']) || !empty($site_settings['whatsapp_number'])): ?>
                                <ul class="social-icons lab-ul d-flex mt-4">
                                    <?php if (!empty($site_settings['facebook_url'])): ?>
                                    <li><a href="<?php echo htmlspecialchars($site_settings['facebook_url']); ?>" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                    <?php endif; ?>
                                    <?php if (!empty($site_settings['twitter_url'])): ?>
                                    <li><a href="<?php echo htmlspecialchars($site_settings['twitter_url']); ?>" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                    <?php endif; ?>
                                    <?php if (!empty($site_settings['instagram_url'])): ?>
                                    <li><a href="<?php echo htmlspecialchars($site_settings['instagram_url']); ?>" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                                    <?php endif; ?>
                                    <?php if (!empty($site_settings['youtube_url'])): ?>
                                    <li><a href="<?php echo htmlspecialchars($site_settings['youtube_url']); ?>" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a></li>
                                    <?php endif; ?>
                                    <?php if (!empty($site_settings['whatsapp_number'])): ?>
                                    <li><a href="https://wa.me/<?php echo htmlspecialchars($site_settings['whatsapp_number']); ?>" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a></li>
                                    <?php endif; ?>
                                </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="footer-bottom-content text-center py-3">
                        <p class="mb-0"><?php echo htmlspecialchars($site_settings['copyright_text'] ?? '©2024 Hafsa - Islamic Center'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>