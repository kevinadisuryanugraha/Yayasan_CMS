<?php
// Load site settings from database
include_once __DIR__ . '/site_settings.php';
?>
<header class="header-3 pattern-1">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-xl-3 col-12">
                <div class="mobile-menu-wrapper d-flex flex-wrap align-items-center justify-content-between">
                    <div class="header-bar d-lg-none">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <div class="logo">
                        <a href="index.php">
                            <img src="<?php echo htmlspecialchars($site_settings['logo_light'] ?? 'assets/images/logo/01.png'); ?>"
                                alt="<?php echo htmlspecialchars($site_settings['site_name']); ?>">
                        </a>
                    </div>
                    <div class="ellepsis-bar d-lg-none">
                        <i class="fas fa-ellipsis-h"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-12">
                <div class="header-top">
                    <div class="header-top-area">
                        <ul class="left lab-ul">
                            <li>
                                <i class="icofont-ui-call"></i> <span>
                                    <?php echo htmlspecialchars($site_settings['phone_primary'] ?? '+800-123-4567'); ?>
                                </span>
                            </li>
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo htmlspecialchars($site_settings['address'] ?? 'Address not set'); ?>
                            </li>
                        </ul>
                        <ul class="social-icons lab-ul d-flex">
                            <?php if (!empty($site_settings['facebook_url'])): ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($site_settings['facebook_url']); ?>"
                                        target="_blank"><i class="fab fa-facebook-f"></i></a>
                                </li>
                            <?php endif; ?>
                            <?php if (!empty($site_settings['twitter_url'])): ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($site_settings['twitter_url']); ?>"
                                        target="_blank"><i class="fab fa-twitter"></i></a>
                                </li>
                            <?php endif; ?>
                            <?php if (!empty($site_settings['instagram_url'])): ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($site_settings['instagram_url']); ?>"
                                        target="_blank"><i class="fab fa-instagram"></i></a>
                                </li>
                            <?php endif; ?>
                            <?php if (!empty($site_settings['youtube_url'])): ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($site_settings['youtube_url']); ?>"
                                        target="_blank"><i class="fab fa-youtube"></i></a>
                                </li>
                            <?php endif; ?>
                            <?php if (!empty($site_settings['whatsapp_number'])): ?>
                                <li>
                                    <a href="https://wa.me/<?php echo htmlspecialchars($site_settings['whatsapp_number']); ?>"
                                        target="_blank"><i class="fab fa-whatsapp"></i></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="header-bottom">
                    <div class="header-wrapper">
                        <div class="menu-area justify-content-between w-100">
                            <ul class="menu lab-ul">
                                <li>
                                    <a href="index.php">Home</a>
                                </li>
                                <li>
                                    <a href="about.php">About</a>
                                </li>
                                <li>
                                    <a href="#0">Events</a>
                                    <ul class="submenu">
                                        <li><a href="events.php">Events</a></li>
                                        <li><a href="events-single.php">Events Single</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#0">Programs</a>
                                    <ul class="submenu">
                                        <li> <a href="programs.php">Programs</a></li>
                                        <li><a href="program-single.php">Program Single</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#0">Pages</a>
                                    <ul class="submenu">
                                        <li>
                                            <a href="gallery.php">Gallery</a>
                                        </li>
                                        <li>
                                            <a href="#0">Scholars</a>
                                            <ul class="submenu">
                                                <li><a href="scholar.php">Our Scholars</a></li>
                                                <li><a href="scholar-single.php">Scholar Single</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="#0">Blog</a>
                                            <ul class="submenu">
                                                <li><a href="blog.php">blog</a></li>
                                                <li><a href="blog-single.php">Blog Single</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="sermons.php">Sermons</a></li>
                                        <li><a href="services.php">Service</a></li>
                                    </ul>
                                </li>
                                <li><a href="contact.php">Contact</a></li>
                            </ul>
                            <div class="prayer-time d-none d-lg-block">
                                <a href="#" class="prayer-time-btn"><i class="icofont-clock-time"></i> Today Prayer
                                    Time</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>