<?php
/* Template: Front Page */
get_header();
?>

<!-- Page Header (simplified from index.html) -->
<div id="page-header" class="ph-full ph-cap-lg ph-ghost-scroll ph-image-cropped ph-content-parallax">
    <div class="page-header-inner tt-wrap">
        <div class="ph-caption">
            <div class="ph-caption-subtitle" style="transform: translate(0px, 0px);">
                <div class="ph-appear" style="">Ruang Literasi dan Ekspresi untuk Publik</div>
            </div>
            <h1 class="ph-caption-title">
                <div class="ph-appear"><?php echo esc_html(get_bloginfo('description') ?: 'Ruang Literasi Kaliurang'); ?></div>
            </h1>
            <div class="ph-caption-title-ghost"><div class="ph-appear">Literasi</div></div>
        </div>
    </div>
    <div class="tt-scroll-down"><a href="#page-content" class="tt-sd-inner ph-appear" data-offset="0"><div class="tt-sd-arrow"><div class="tt-sd-arrow-inner"></div></div><div class="tt-sd-text">Scroll</div></a></div>
</div>

<!-- Page Content -->
<div id="page-content">
    <center>
        <div class="tt-heading tt-heading-xlg anim-fadeinup inline-before-grid">
            <h2 class="tt-heading-title">Fasilitas</h2>
        </div>
    </center>
    <div class="tt-section">
        <div class="tt-section-inner">
            <div id="portfolio-grid" class="pgi-hover">
                <div class="tt-grid ttgr-layout-3 ttgr-gap-4">
                    <div class="tt-grid-items-wrap isotope-items-wrap">
                        <?php
                        // Show latest portfolio items
                        $q = new WP_Query([
                            'post_type'      => 'portfolio',
                            'posts_per_page' => 8,
                        ]);
                        if ($q->have_posts()):
                            while ($q->have_posts()): $q->the_post(); ?>
                                <div class="tt-grid-item isotope-item">
                                    <div class="ttgr-item-inner">
                                        <div class="portfolio-grid-item">
                                            <a href="<?php the_permalink(); ?>" class="pgi-image-wrap" data-cursor="View<br>Post">
                                                <div class="pgi-image-holder">
                                                    <div class="pgi-image-inner anim-zoomin">
                                                        <figure class="pgi-image ttgr-height">
                                                            <?php if (has_post_thumbnail()) { the_post_thumbnail('nui-portfolio-grid'); } else { ?>
                                                                <img src="<?php echo esc_url(get_template_directory_uri().'/assets/img/portfolio/1200/portfolio-1.jpg'); ?>" alt="<?php the_title_attribute(); ?>" />
                                                            <?php } ?>
                                                        </figure>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="pgi-caption"><div class="pgi-caption-inner">
                                                <h2 class="pgi-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                                <div class="pgi-categories-wrap">
                                                    <div class="pgi-category"><?php echo get_the_term_list(get_the_ID(), 'portfolio_category', '', ', '); ?></div>
                                                </div>
                                            </div></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; wp_reset_postdata();
                        else: ?>
                            <p class="text-center"><?php esc_html_e('No posts found.', 'nui-portfolio'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="text-center anim-fadeinup">
                <a href="<?php echo esc_url(get_post_type_archive_link('portfolio')); ?>" class="tt-scrolling-btn all-works-btn" data-cursor="All<br>Works">
                    <div class="scr-btn-inner ph-appear">
                        <div class="scr-btn-icon"><i class="fas fa-arrow-right"></i></div>
                        <div class="scr-btn-spinner">
                            <svg viewBox="0 0 500 500"><defs><path d="M50,250c0-110.5,89.5-200,200-200s200,89.5,200,200s-89.5,200-200,200S50,360.5,50,250" id="textcircle"></path></defs><text dy="30" class="scr-btn-text"><textPath xlink:href="#textcircle">See All Works - See All Works -</textPath></text></svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Services / Accordion Section -->
    <div class="tt-section padding-top-xlg-100 padding-bottom-xlg-150">
        <div class="tt-section-inner tt-wrap max-width-1700">
            <div class="tt-row margin-left-lg-3-p margin-right-lg-3-p">
                <div class="tt-col-xl-4">
                    <div class="tt-heading tt-heading-xlg anim-fadeinup">
                        <h3 class="tt-heading-subtitle">Informasi & Layanan</h3>
                        <h2 class="tt-heading-title">Ruang Literasi Kaliurang</h2>
                    </div>
                    <div class="max-width-600 margin-bottom-60 anim-fadeinup">
                        <h5>Ruang Literasi Kaliurang adalah ruang bersama untuk membaca, berdialog, dan berkarya—menumbuhkan nalar, meneguhkan akhlak, dan memberi dampak bagi sekitar.
                        </h5>
                    </div>
                </div>
                <div class="tt-col-xl-1"></div>
                <div class="tt-col-xl-7">
                    <div class="tt-accordion tt-ac-borders">
                        <div class="tt-accordion-item anim-fadeinup">
                            <div class="tt-accordion-heading">
                                <div class="tt-ac-head cursor-alter">
                                    <h3 class="tt-ac-head-title">Informasi Umum</h3>
                                </div>
                                <div class="tt-accordion-caret-wrap"><div class="tt-accordion-caret-inner magnetic-item"><div class="tt-accordion-caret"></div></div></div>
                            </div>
                            <div class="tt-accordion-content max-width-800">
                            <ul class="tt-list">
                                <li><strong>Nama Gedung:</strong> Ruang Literasi Kaliurang</li>
                                <li><strong>Alamat:</strong> RT 1/ RW 23 Pedukuhan Penen,
                                    Harjobinangun, Pakem, Sleman, DIY</li>
                                <li><strong>Pemilik:</strong> Yayasan Ruang Literasi Kaliurang</li>
                                <li><strong>Tipe:</strong> Kegunaan campuran (ruang publik,
                                    komersial, residen)</li>
                                <li><strong>Tujuan:</strong> Ruang bersama, inkubator kader
                                    literasi, menopang layanan komersial</li>
                                <li><strong>Cakupan Manajemen:</strong> Seluruh bangunan dan tanah
                                    kavling RLK</li>
                            </ul>
                            </div>
                        </div>
                        <div class="tt-accordion-item anim-fadeinup">
                            <div class="tt-accordion-heading">
                                <div class="tt-ac-head cursor-alter">
                                    <h3 class="tt-ac-head-title">Ruangan</h3>
                                </div>
                                <div class="tt-accordion-caret-wrap"><div class="tt-accordion-caret-inner magnetic-item"><div class="tt-accordion-caret"></div></div></div>
                            </div>
                            <div class="tt-accordion-content max-width-800">
                                <h5>Ruangan yang Dapat Dipinjam</h5>
                                <ul class="tt-list">
                                    <li>Studio Surya Paloh</li>
                                    <li>Plaza Fisipol</li>
                                    <li>Joglo</li>
                                </ul>
                                <h5 class="margin-top-20">Ruangan yang Tersedia</h5>
                                <ul class="tt-list">
                                    <li>Perpustakaan Angku Navis</li>
                                    <li>Pustaka Anak Rakyat</li>
                                    <li>Ruang Teater Surya Paloh</li>
                                    <li>Studio Podcast Rohana Kudus</li>
                                    <li>Kafe</li>
                                    <li>Plaza Fisipol</li>
                                    <li>Joglo</li>
                                </ul>
                            </div>
                        </div>
                        <div class="tt-accordion-item anim-fadeinup">
                            <div class="tt-accordion-heading">
                                <div class="tt-ac-head cursor-alter">
                                    <h3 class="tt-ac-head-title">Inventaris</h3>
                                </div>
                                <div class="tt-accordion-caret-wrap"><div class="tt-accordion-caret-inner magnetic-item"><div class="tt-accordion-caret"></div></div></div>
                            </div>
                            <div class="tt-accordion-content max-width-800">
                            <ul class="tt-list tt-list-col tt-list-col-2">
                                <li>Sound system</li>
                                <li>Mini Stage</li>
                                <li>Stand Mic</li>
                                <li>Podium</li>
                                <li>Coffee Table</li>
                                <li>±60 Kursi Chitos</li>
                                <li>±40 Kursi Kayu</li>
                                <li>2 Pcs Mic</li>
                                <li>Projector</li>
                                <li>Screen Projector</li>
                            </ul>
                            </div>
                        </div>
                        <div class="tt-accordion-item anim-fadeinup">
                            <div class="tt-accordion-heading">
                                <div class="tt-ac-head cursor-alter">
                                    <h3 class="tt-ac-head-title">Fasilitas</h3>
                                </div>
                                <div class="tt-accordion-caret-wrap"><div class="tt-accordion-caret-inner magnetic-item"><div class="tt-accordion-caret"></div></div></div>
                            </div>
                            <div class="tt-accordion-content max-width-800">
                                <h5>Area Publik</h5>
                                <ul class="tt-list">
                                    <li>Perpustakaan Umum A.A. Navis — koleksi 10.000 buku.</li>
                                    <li>Perpustakaan Anak Rakyat (Ara) — buku &amp; permainan anak.</li>
                                    <li>Ruang Teater Surya Paloh — kapasitas ±50 orang.</li>
                                    <li>Studio Podcast Rohana Kudus — kedap suara.</li>
                                    <li>Joglo Tjokroaminoto, Musala Gelanggang, Kafe, Plaza Fisipol,
                                        Balkon Balairung.</li>
                                </ul>
                                <h5 class="margin-top-20">Area Tamu</h5>
                                <ul class="tt-list">
                                    <li>Kamar Pandega Wreksa (3–4 org), Jetis Harjo (2 org), Sidomulyo &amp;
                                        Soropadan (kamar dalam).</li>
                                    <li>Tiga kamar mandi/toilet di lantai satu.</li>
                                </ul>
                                <h5 class="margin-top-20">Area Pribadi &amp; Pelayanan</h5>
                                <ul class="tt-list">
                                    <li>Kamar Peneleh, Kramat 106, Menteng 31, Gondangdia; Ruang
                                        keluarga/tamu/makan; Dapur bersih.</li>
                                    <li>Dapur pelayanan, kamar tidur service (3), toilet (3), ruang
                                        laundry/gudang/jemur.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scrolling Text Section -->
    <div class="tt-section padding-top-xlg-120 padding-bottom-xlg-120 bg-white-accent-3">
        <div class="tt-section-inner">
            <div class="tt-scrolling-text" data-scroll-speed="10">
                <div class="tt-scrolling-text-inner" data-text="Belajar Sepanjang Hayat • Berbagi untuk Sesama →">
                Belajar Sepanjang Hayat • Berbagi untuk Sesama →
                </div>
            </div>
            <div class="tt-scrolling-text scr-text-stroke scr-text-reverse" data-scroll-speed="10">
                <div class="tt-scrolling-text-inner" data-text="Membaca • Berdialog • Berkarya — Tumbuhkan Nalar, Teguhkan Akhlak →">
                Membaca • Berdialog • Berkarya — Tumbuhkan Nalar, Teguhkan Akhlak →
                </div>
            </div>
        </div>
    </div>

    <!-- Clients Logo Wall -->
    <div class="tt-section padding-top-xlg-150 padding-bottom-xlg-150">
        <div class="tt-section-inner tt-wrap">
            <ul class="tt-logo-wall anim-fadeinup">
                <?php $theme_uri = get_template_directory_uri(); ?>
                <li><a href="https://themetorium.net/" class="cursor-alter" target="_blank" rel="noopener">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-1-light.png'); ?>" class="lv-client-light" alt="Client">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-1-dark.png'); ?>" class="lv-client-dark" alt="Client"></a></li>
                <li><a href="https://themetorium.net/" class="cursor-alter" target="_blank" rel="noopener">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-2-light.png'); ?>" class="lv-client-light" alt="Client">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-2-dark.png'); ?>" class="lv-client-dark" alt="Client"></a></li>
                <li><a href="https://themetorium.net/" class="cursor-alter" target="_blank" rel="noopener">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-3-light.png'); ?>" class="lv-client-light" alt="Client">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-3-dark.png'); ?>" class="lv-client-dark" alt="Client"></a></li>
                <li><a href="https://themetorium.net/" class="cursor-alter" target="_blank" rel="noopener">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-4-light.png'); ?>" class="lv-client-light" alt="Client">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-4-dark.png'); ?>" class="lv-client-dark" alt="Client"></a></li>
                <li><a href="https://themetorium.net/" class="cursor-alter" target="_blank" rel="noopener">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-5-light.png'); ?>" class="lv-client-light" alt="Client">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-5-dark.png'); ?>" class="lv-client-dark" alt="Client"></a></li>
                <li><a href="https://themetorium.net/" class="cursor-alter" target="_blank" rel="noopener">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-6-light.png'); ?>" class="lv-client-light" alt="Client">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-6-dark.png'); ?>" class="lv-client-dark" alt="Client"></a></li>
                <li><a href="https://themetorium.net/" class="cursor-alter" target="_blank" rel="noopener">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-7-light.png'); ?>" class="lv-client-light" alt="Client">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-7-dark.png'); ?>" class="lv-client-dark" alt="Client"></a></li>
                <li><a href="https://themetorium.net/" class="cursor-alter" target="_blank" rel="noopener">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-8-light.png'); ?>" class="lv-client-light" alt="Client">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-8-dark.png'); ?>" class="lv-client-dark" alt="Client"></a></li>
                <li><a href="https://themetorium.net/" class="cursor-alter" target="_blank" rel="noopener">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-9-light.png'); ?>" class="lv-client-light" alt="Client">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-9-dark.png'); ?>" class="lv-client-dark" alt="Client"></a></li>
                <li><a href="https://themetorium.net/" class="cursor-alter" target="_blank" rel="noopener">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-10-light.png'); ?>" class="lv-client-light" alt="Client">
                    <img src="<?php echo esc_url($theme_uri.'/assets/img/clients/client-10-dark.png'); ?>" class="lv-client-dark" alt="Client"></a></li>
            </ul>
        </div>
    </div>

    <!-- Blog Carousel -->
    <div class="tt-section padding-top-xlg-150 padding-bottom-xlg-150 bg-white-accent-3">
        <div class="tt-section-inner tt-wrap max-width-1600">
            <div class="tt-heading tt-heading-xlg margin-bottom-7-p anim-fadeinup max-width-1250 margin-auto">
                <h3 class="tt-heading-subtitle">Latest News</h3>
                <h2 class="tt-heading-title">From the Blog</h2>
                <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>" class="tt-btn tt-btn-link">
                    <div data-hover="Browse All News">Browse All News</div>
                    <span class="tt-btn-icon"><i class="fas fa-arrow-right"></i></span>
                </a>
            </div>

            <div class="tt-blog-carousel anim-fadeinup" data-speed="800" data-simulate-touch="true" data-pagination-type="bullets">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <?php
                        $bc = new WP_Query(['posts_per_page' => 6]);
                        if ($bc->have_posts()):
                            while ($bc->have_posts()): $bc->the_post(); ?>
                                <div class="swiper-slide">
                                    <div class="tt-blog-carousel-item">
                                        <a href="<?php the_permalink(); ?>" class="tt-bci-image-wrap" data-cursor="Read<br>More">
                                            <figure class="tt-bci-image">
                                                <?php if (has_post_thumbnail()) {
                                                    $img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
                                                    $img_url = $img ? $img[0] : '';
                                                ?>
                                                    <img class="swiper-lazy" src="<?php echo esc_url(get_template_directory_uri().'/assets/img/low-qlt-thumb.jpg'); ?>" data-src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>">
                                                <?php } else { ?>
                                                    <img class="swiper-lazy" src="<?php echo esc_url(get_template_directory_uri().'/assets/img/low-qlt-thumb.jpg'); ?>" data-src="<?php echo esc_url(get_template_directory_uri().'/assets/img/blog/carousel/blog-carousel-1.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                                                <?php } ?>
                                            </figure>
                                        </a>
                                        <div class="tt-bci-info">
                                            <div class="tt-bci-categories"><?php the_category(' '); ?></div>
                                            <h2 class="tt-bci-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                            <div class="tt-bci-meta">
                                                <span class="published"><?php echo esc_html(get_the_date()); ?></span>
                                                <span class="posted-by">- <?php _e('by', 'nui-portfolio'); ?> <?php the_author_posts_link(); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; wp_reset_postdata();
                        endif; ?>
                    </div>
                </div>
                <div class="tt-blc-nav-prev"><div class="tt-blc-nav-arrow magnetic-item"><i class="tt-arrow-left"></i></div></div>
                <div class="tt-blc-nav-next"><div class="tt-blc-nav-arrow magnetic-item"><i class="tt-arrow-right"></i></div></div>
                <div class="tt-blc-pagination hide-cursor"></div>
            </div>
        </div>
    </div>

    <?php
    // Booking Form Section
    echo do_shortcode('[booking_form title="Booking Ruangan RLK"]');
    ?>

    <!-- Contact Section -->
    <div class="tt-section padding-top-xlg-150">
        <div class="tt-section-inner tt-wrap max-width-700">
            <div class="tt-heading tt-heading-xxlg margin-bottom-8-p anim-fadeinup">
                <h3 class="tt-heading-subtitle">Get in Touch</h3>
                <h2 class="tt-heading-title">Let's Work<br> Together!</h2>
            </div>
            <form id="tt-contact-form" class="tt-form-filled anim-fadeinup" method="post" action="#">
                <div class="tt-row">
                    <div class="tt-col-md-6">
                        <div class="tt-form-group">
                            <label>Your Name <span class="required">*</span></label>
                            <input class="tt-form-control" type="text" name="Name" required>
                        </div>
                    </div>
                    <div class="tt-col-md-6">
                        <div class="tt-form-group">
                            <label>Email address <span class="required">*</span></label>
                            <input class="tt-form-control" type="email" name="Email" required>
                        </div>
                    </div>
                </div>
                <div class="tt-form-group">
                    <label>Subject <span class="required">*</span></label>
                    <input class="tt-form-control" type="text" name="Subject" required>
                </div>
                <div class="tt-form-group">
                    <label>Your Message <span class="required">*</span></label>
                    <textarea class="tt-form-control" rows="5" name="Message" required></textarea>
                </div>
                <small class="tt-form-text"><em>Fields marked with an asterisk (*) are required!</em></small>
                <button type="submit" class="tt-btn tt-btn-primary margin-top-30">
                    <div data-hover="Send Message">Send Message</div>
                    <span class="tt-btn-icon"><i class="fas fa-paper-plane"></i></span>
                </button>
            </form>
        </div>
    </div>

<?php get_footer();
