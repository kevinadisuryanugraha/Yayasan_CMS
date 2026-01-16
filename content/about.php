<?php
include 'admin/koneksi.php';

// Fetch About Section Data
$about_query = mysqli_query($conn, "SELECT * FROM about_section WHERE is_active = 1 LIMIT 1");
$about = mysqli_fetch_assoc($about_query);

if (!$about) {
    $about = [
        'subtitle' => 'Tentang Kami',
        'title' => 'Yayasan Indonesia Bijak Bestari',
        'sub_heading' => 'Membangun Generasi yang Berilmu dan Berakhlak Mulia',
        'description' => 'Yayasan kami berkomitmen untuk memberikan pendidikan berkualitas dan pembinaan karakter untuk seluruh lapisan masyarakat.',
        'image' => 'assets/images/about/02.png',
        'button_text' => 'Hubungi Kami',
        'button_link' => '?page=contact'
    ];
}

// Fetch Appearance Settings
$appearance_query = mysqli_query($conn, "SELECT * FROM appearance_settings LIMIT 1");
$appearance = mysqli_fetch_assoc($appearance_query);

$cms_primary = $appearance['primary_color'] ?? '#00997d';
$cms_secondary = $appearance['secondary_color'] ?? '#0a294a';
$cms_accent = $appearance['accent_color'] ?? '#fab702';

// Fetch Features
$features_query = mysqli_query($conn, "SELECT * FROM about_features ORDER BY sort_order ASC, id ASC");
$features = [];
while ($row = mysqli_fetch_assoc($features_query)) {
    $features[] = $row;
}

// Fetch Vision Mission Section Header
$vm_section_query = mysqli_query($conn, "SELECT * FROM about_vision_mission_section WHERE id = 1");
$vm_section = mysqli_fetch_assoc($vm_section_query);

// Fetch Vision Mission Items
$vm_items_query = mysqli_query($conn, "SELECT * FROM about_vision_mission_items ORDER BY sort_order ASC, id ASC");
$vm_items = [];
while ($row = mysqli_fetch_assoc($vm_items_query)) {
    $vm_items[] = $row;
}
?>

<!-- About Page External Stylesheet -->
<link rel="stylesheet" href="assets/css/about.css">

<!-- Dynamic CSS Variables (from CMS settings) -->
<style>
    :root {
        --about-primary:
            <?php echo $cms_primary; ?>
        ;
        --about-secondary:
            <?php echo $cms_secondary; ?>
        ;
        --about-accent:
            <?php echo $cms_accent; ?>
        ;
    }
</style>

<!-- ========================================
     HERO BANNER
======================================== -->
<section class="about-hero-section" aria-label="Banner Halaman Tentang Kami">
    <div class="about-hero-float float-1" aria-hidden="true"></div>
    <div class="about-hero-float float-2" aria-hidden="true"></div>

    <div class="container">
        <div class="about-hero-content">
            <div class="about-hero-badge" aria-label="Identitas Yayasan">
                <i class="icofont-heart-alt" aria-hidden="true"></i>
                <span>Yayasan Indonesia Bijak Bestari</span>
            </div>
            <h1 class="about-hero-title">Tentang Kami</h1>
            <nav class="about-breadcrumb" aria-label="Navigasi Breadcrumb">
                <a href="index.php" aria-label="Kembali ke Beranda"><i class="icofont-home" aria-hidden="true"></i>
                    Beranda</a>
                <i class="icofont-rounded-right" aria-hidden="true"></i>
                <span aria-current="page">Tentang Kami</span>
            </nav>
        </div>
    </div>
</section>

<!-- ========================================
     ABOUT INTRODUCTION
======================================== -->
<section class="about-intro-section">
    <div class="container">
        <div class="about-intro-row">
            <div class="about-intro-image-col">
                <div class="about-intro-image-wrapper">
                    <img src="<?php echo htmlspecialchars($about['image']); ?>"
                        alt="Foto tentang Yayasan Indonesia Bijak Bestari" class="about-intro-main-img" loading="lazy">
                    <div class="about-intro-frame"></div>
                    <div class="about-intro-stats-card" role="complementary"
                        aria-label="Statistik <?php echo htmlspecialchars($about['stat_text'] ?? 'Pengalaman'); ?>">
                        <div class="stats-icon-circle" aria-hidden="true">
                            <i class="<?php echo htmlspecialchars($about['stat_icon'] ?? 'icofont-calendar'); ?>"></i>
                        </div>
                        <div class="stats-text">
                            <h3 aria-label="<?php echo htmlspecialchars($about['stat_number'] ?? '10+'); ?>">
                                <?php echo htmlspecialchars($about['stat_number'] ?? '10+'); ?>
                            </h3>
                            <p>
                                <?php echo htmlspecialchars($about['stat_text'] ?? 'Tahun Pengalaman'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="about-intro-content-col">
                <?php if ($about['subtitle']): ?>
                    <span class="about-intro-subtitle">
                        <?php echo htmlspecialchars($about['subtitle']); ?>
                    </span>
                <?php endif; ?>

                <h2 class="about-intro-title">
                    <?php echo htmlspecialchars($about['title']); ?>
                </h2>

                <?php if ($about['sub_heading']): ?>
                    <h4 class="about-intro-heading">
                        <?php echo htmlspecialchars($about['sub_heading']); ?>
                    </h4>
                <?php endif; ?>

                <?php if ($about['description']): ?>
                    <p class="about-intro-desc">
                        <?php echo htmlspecialchars($about['description']); ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($features)): ?>
                    <ul class="about-features-list" role="list" aria-label="Keunggulan Yayasan">
                        <?php foreach ($features as $feature): ?>
                            <li class="about-feature-item">
                                <i class="<?php echo htmlspecialchars($feature['icon']); ?>" aria-hidden="true"></i>
                                <span>
                                    <?php echo htmlspecialchars($feature['text']); ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if ($about['button_text'] && $about['button_link']): ?>
                    <a href="<?php echo htmlspecialchars($about['button_link']); ?>" class="btn-hero-primary">
                        <?php echo htmlspecialchars($about['button_text']); ?> <i class="icofont-arrow-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ========================================
     VISION & MISSION
======================================== -->

<section class="vision-mission-section">
    <div class="container">
        <div class="section-header">
            <?php if (!empty($vm_section['subtitle'])): ?>
                <span class="section-header-subtitle"><?php echo htmlspecialchars($vm_section['subtitle']); ?></span>
            <?php endif; ?>

            <h2 class="section-header-title">
                <?php echo htmlspecialchars($vm_section['title'] ?? 'Visi & Misi'); ?>
            </h2>

            <?php if (!empty($vm_section['description'])): ?>
                <p class="section-header-desc">
                    <?php echo htmlspecialchars($vm_section['description']); ?>
                </p>
            <?php endif; ?>
        </div>

        <div class="vm-cards-row">
            <?php if (count($vm_items) > 0): ?>
                <?php foreach ($vm_items as $item): ?>
                    <div class="vm-card">
                        <div class="vm-card-icon">
                            <i class="<?php echo htmlspecialchars($item['icon']); ?>"></i>
                        </div>
                        <h3 class="vm-card-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                        <p class="vm-card-text"><?php echo htmlspecialchars($item['description']); ?></p>

                        <?php if (!empty($item['list_items'])):
                            $list = explode("\n", $item['list_items']);
                            ?>
                            <ul class="vm-card-list">
                                <?php foreach ($list as $li):
                                    if (trim($li)):
                                        ?>
                                        <li><i class="icofont-check-circled"></i> <?php echo htmlspecialchars(trim($li)); ?></li>
                                        <?php
                                    endif;
                                endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback if no items -->
                <div class="col-12 text-center">
                    <p>Belum ada data Visi & Misi.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ========================================
     HISTORY TIMELINE (Dynamic)
======================================== -->
<?php
// Fetch History Data
$h_sec_q = mysqli_query($conn, "SELECT * FROM about_history_section WHERE id = 1");
$h_sec = mysqli_fetch_assoc($h_sec_q);

$h_items_q = mysqli_query($conn, "SELECT * FROM about_history_items ORDER BY sort_order ASC, year ASC");
?>
<section class="history-section">
    <div class="container">
        <div class="section-header">
            <span
                class="section-header-subtitle"><?php echo htmlspecialchars($h_sec['subtitle'] ?? 'Perjalanan Kami'); ?></span>
            <h2 class="section-header-title"><?php echo htmlspecialchars($h_sec['title'] ?? 'Sejarah Yayasan'); ?></h2>
            <p class="section-header-desc"><?php echo htmlspecialchars($h_sec['description'] ?? ''); ?></p>
        </div>

        <div class="timeline-container">
            <?php if (mysqli_num_rows($h_items_q) > 0): ?>
                <?php while ($h_item = mysqli_fetch_assoc($h_items_q)): ?>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <span class="timeline-year"><?php echo htmlspecialchars($h_item['year']); ?></span>
                            <h4 class="timeline-title"><?php echo htmlspecialchars($h_item['title']); ?></h4>
                            <p class="timeline-text"><?php echo htmlspecialchars($h_item['description']); ?></p>
                        </div>
                        <div class="timeline-dot"></div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center text-muted col-12">
                    <p><em>Belum ada data sejarah yang ditambahkan.</em></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ========================================
     TEAM SECTION (Dynamic)
======================================== -->
<?php
// Fetch Team Section Data
$t_sec_q = mysqli_query($conn, "SELECT * FROM about_team_section WHERE id = 1");
$t_sec = mysqli_fetch_assoc($t_sec_q);

// Fetch Team Items
$t_items_q = mysqli_query($conn, "SELECT * FROM about_team_items ORDER BY sort_order ASC, id ASC");
?>
<section class="team-section">
    <div class="container">
        <div class="section-header">
            <span
                class="section-header-subtitle"><?php echo htmlspecialchars($t_sec['subtitle'] ?? 'Tim Kami'); ?></span>
            <h2 class="section-header-title"><?php echo htmlspecialchars($t_sec['title'] ?? 'Pengurus Yayasan'); ?></h2>
            <p class="section-header-desc"><?php echo htmlspecialchars($t_sec['description'] ?? ''); ?></p>
        </div>

        <div class="team-cards-row">
            <?php if (mysqli_num_rows($t_items_q) > 0): ?>
                <?php while ($t_item = mysqli_fetch_assoc($t_items_q)): ?>
                    <?php
                    // Logic Pengecekan Path Gambar (Root vs Admin)
                    $img_src = 'assets/images/user-placeholder.png'; // Default Placeholder
            
                    if (!empty($t_item['image'])) {
                        // Cek apakah file ada di path relatif root (uploads/team/...)
                        if (file_exists($t_item['image'])) {
                            $img_src = $t_item['image'];
                        }
                        // Cek apakah file ada di path admin (admin/uploads/team/...) - Fallback jika upload via admin tidak pakai ../
                        elseif (file_exists('admin/' . $t_item['image'])) {
                            $img_src = 'admin/' . $t_item['image'];
                        }
                    }
                    ?>
                    <div class="team-card">
                        <div class="team-card-image">
                            <!-- Link Gambar Dinamis -->
                            <img src="<?php echo $img_src; ?>" alt="Foto <?php echo htmlspecialchars($t_item['name']); ?>"
                                loading="lazy" style="object-fit: cover;">
                        </div>
                        <h4 class="team-card-name"><?php echo htmlspecialchars($t_item['name']); ?></h4>
                        <p class="team-card-role"><?php echo htmlspecialchars($t_item['role']); ?></p>

                        <!-- Social Media Links (Conditional) -->
                        <div class="team-card-socials" role="list"
                            aria-label="Media sosial <?php echo htmlspecialchars($t_item['name']); ?>">
                            <?php if (!empty($t_item['link_facebook'])): ?>
                                <a href="<?php echo htmlspecialchars($t_item['link_facebook']); ?>" target="_blank"
                                    aria-label="Facebook"><i class="icofont-facebook"></i></a>
                            <?php endif; ?>
                            <?php if (!empty($t_item['link_twitter'])): ?>
                                <a href="<?php echo htmlspecialchars($t_item['link_twitter']); ?>" target="_blank"
                                    aria-label="Twitter"><i class="icofont-twitter"></i></a>
                            <?php endif; ?>
                            <?php if (!empty($t_item['link_linkedin'])): ?>
                                <a href="<?php echo htmlspecialchars($t_item['link_linkedin']); ?>" target="_blank"
                                    aria-label="LinkedIn"><i class="icofont-linkedin"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted"><em>Belum ada data anggota tim yang ditampilkan.</em></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ========================================
     GALLERY SECTION (Dynamic)
======================================== -->
<?php
// Fetch Gallery Section
$g_sec_q = mysqli_query($conn, "SELECT * FROM about_gallery_section WHERE id = 1");
$g_sec = mysqli_fetch_assoc($g_sec_q);

// Fetch Gallery Items
$g_items_q = mysqli_query($conn, "SELECT * FROM about_gallery_items ORDER BY sort_order ASC, id ASC");
$g_items = [];
while ($row = mysqli_fetch_assoc($g_items_q)) {
    $g_items[] = $row;
}
?>
<section class="gallery-section">
    <div class="container">
        <div class="section-header">
            <span
                class="section-header-subtitle"><?php echo htmlspecialchars($g_sec['subtitle'] ?? 'Dokumentasi'); ?></span>
            <h2 class="section-header-title"><?php echo htmlspecialchars($g_sec['title'] ?? 'Galeri Kegiatan'); ?></h2>
            <p class="section-header-desc"><?php echo htmlspecialchars($g_sec['description'] ?? ''); ?></p>
        </div>

        <div class="gallery-grid" role="list" aria-label="Galeri foto kegiatan yayasan">
            <?php if (count($g_items) > 0): ?>
                <?php foreach ($g_items as $index => $item): ?>
                    <?php
                    // Logic Featured Image (First Item in Loop)
                    $is_large = ($index === 0) ? 'large' : '';

                    // Image Path Logic
                    $img_src = 'assets/images/placeholder_landscape.jpg';
                    if (!empty($item['image'])) {
                        if (file_exists($item['image'])) {
                            $img_src = $item['image'];
                        } elseif (file_exists('admin/' . $item['image'])) {
                            $img_src = 'admin/' . $item['image'];
                        }
                    }
                    ?>
                    <div class="gallery-item <?php echo $is_large; ?>" onclick="openLightbox(<?php echo $index; ?>)"
                        role="listitem" tabindex="0" aria-label="Lihat foto <?php echo htmlspecialchars($item['title']); ?>"
                        onkeypress="if(event.key==='Enter')openLightbox(<?php echo $index; ?>)">
                        <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" loading="lazy">
                        <div class="gallery-overlay" aria-hidden="true">
                            <div class="gallery-overlay-icon"><i class="icofont-search-1"></i></div>
                            <h5 class="gallery-overlay-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                            <span class="gallery-overlay-category"><?php echo htmlspecialchars($item['category']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5" style="grid-column: 1 / -1;">
                    <p class="text-muted"><em>Belum ada foto kegiatan yang ditampilkan.</em></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- Script Update for Dynamic Gallery Lightbox -->
<script>
    // Update gallery data for lightbox if needed based on DOM
    // This assumes openLightbox uses DOM selection, which is standard.
    // If it uses a hardcoded array, we might need to inject PHP array to JS variable here.
    var galleryImages = [
        <?php
        foreach ($g_items as $item) {
            $src = 'assets/images/placeholder_landscape.jpg';
            if (!empty($item['image'])) {
                if (file_exists($item['image']))
                    $src = $item['image'];
                elseif (file_exists('admin/' . $item['image']))
                    $src = 'admin/' . $item['image'];
            }
            echo "{ src: '$src', title: '" . addslashes($item['title']) . "', category: '" . addslashes($item['category']) . "' },";
        }
        ?>
    ];

    // Override default openLightbox if it depends on this global variable
    // If the original script reads from DOM, this won't hurt.
</script>

<!-- Lightbox Modal -->
<div class="gallery-lightbox" id="galleryLightbox" role="dialog" aria-modal="true" aria-label="Penampil Gambar Galeri">
    <button class="lightbox-close" onclick="closeLightbox()" aria-label="Tutup galeri"><i class="icofont-close-line"
            aria-hidden="true"></i></button>
    <button class="lightbox-nav lightbox-prev" onclick="navigateLightbox(-1)" aria-label="Gambar sebelumnya"><i
            class="icofont-arrow-left" aria-hidden="true"></i></button>
    <button class="lightbox-nav lightbox-next" onclick="navigateLightbox(1)" aria-label="Gambar selanjutnya"><i
            class="icofont-arrow-right" aria-hidden="true"></i></button>
    <div class="lightbox-content">
        <img src="" alt="Gambar galeri" id="lightboxImage">
        <div class="lightbox-caption">
            <h4 id="lightboxTitle"></h4>
            <p id="lightboxCategory"></p>
        </div>
    </div>
</div>

<script>
    // Gallery Lightbox Functionality
    // Use the dynamic data from PHP (galleryImages)
    // Fallback to empty array if undefined
    var galleryData = (typeof galleryImages !== 'undefined') ? galleryImages : [];

    let currentIndex = 0;

    function openLightbox(index) {
        if (galleryData.length === 0) return;
        currentIndex = index;
        updateLightboxContent();
        document.getElementById('galleryLightbox').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('galleryLightbox').classList.remove('active');
        document.body.style.overflow = '';
    }

    function navigateLightbox(direction) {
        if (galleryData.length === 0) return;
        currentIndex += direction;
        if (currentIndex < 0) currentIndex = galleryData.length - 1;
        if (currentIndex >= galleryData.length) currentIndex = 0;
        updateLightboxContent();
    }

    function updateLightboxContent() {
        const item = galleryData[currentIndex];
        document.getElementById('lightboxImage').src = item.src;
        document.getElementById('lightboxTitle').textContent = item.title;
        document.getElementById('lightboxCategory').textContent = item.category;
    }

    // Close lightbox with Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') navigateLightbox(-1);
        if (e.key === 'ArrowRight') navigateLightbox(1);
    });

    // Close lightbox when clicking outside image
    document.getElementById('galleryLightbox').addEventListener('click', function (e) {
        if (e.target === this) closeLightbox();
    });
</script>

<!-- ========================================
     CTA SECTION (Dynamic)
======================================== -->
<?php
// Fetch CTA Section
$cta_q = mysqli_query($conn, "SELECT * FROM about_cta_section WHERE id = 1");
$cta = mysqli_fetch_assoc($cta_q);
?>
<section class="about-cta-section">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">
                <?php echo htmlspecialchars($cta['title'] ?? 'Mari Bergabung Bersama Kami'); ?>
            </h2>
            <p class="cta-desc">
                <?php echo htmlspecialchars($cta['description'] ?? 'Jadilah bagian dari perubahan positif.'); ?>
            </p>
            <div class="cta-buttons" role="group" aria-label="Tombol aksi">
                <?php if (!empty($cta['btn_primary_text'])): ?>
                    <a href="<?php echo htmlspecialchars($cta['btn_primary_link'] ?? '#'); ?>" class="cta-btn-primary"
                        aria-label="<?php echo htmlspecialchars($cta['btn_primary_text']); ?>">
                        <?php echo htmlspecialchars($cta['btn_primary_text']); ?>
                    </a>
                <?php endif; ?>

                <?php if (!empty($cta['btn_outline_text'])): ?>
                    <a href="<?php echo htmlspecialchars($cta['btn_outline_link'] ?? '#'); ?>" class="cta-btn-outline"
                        aria-label="<?php echo htmlspecialchars($cta['btn_outline_text']); ?>">
                        <?php echo htmlspecialchars($cta['btn_outline_text']); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>