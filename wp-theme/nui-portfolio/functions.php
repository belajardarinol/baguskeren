<?php
/**
 * Theme setup and assets enqueue
 */

if (!defined('NUI_VERSION')) {
    define('NUI_VERSION', '1.0.0');
}

add_action('after_setup_theme', function () {
    // Core supports
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    add_theme_support('custom-logo', [
        'height' => 60,
        'flex-height' => true,
        'flex-width' => true,
    ]);

    register_nav_menus([
        'primary' => __('Primary Menu', 'nui-portfolio'),
    ]);
});

add_action('wp_enqueue_scripts', function () {
    $theme_uri = get_template_directory_uri();

    // Google Fonts (keep as remote)
    wp_enqueue_style('nui-google-fonts-poppins', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap', [], null);
    wp_enqueue_style('nui-google-fonts-syne', 'https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&display=swap', [], null);

    // Vendor CSS
    wp_enqueue_style('normalize', $theme_uri . '/assets/vendor/normalize/normalize.min.css', [], '8.0.1');
    wp_enqueue_style('fontawesome', $theme_uri . '/assets/vendor/fontawesome/css/fontawesome-all.min.css', [], '6');
    wp_enqueue_style('swiper', $theme_uri . '/assets/vendor/swiper/css/swiper-bundle.min.css', [], '8');
    wp_enqueue_style('lightgallery', $theme_uri . '/assets/vendor/lightgallery/css/lightgallery.min.css', [], '2');

    // Theme CSS
    wp_enqueue_style('nui-helper', $theme_uri . '/assets/css/helper.css', [], NUI_VERSION);
    wp_enqueue_style('nui-theme', $theme_uri . '/assets/css/theme.css', ['nui-helper'], NUI_VERSION);

    // Core JS (WordPress already provides jquery)
    wp_enqueue_script('jquery');

    // Vendor JS
    wp_enqueue_script('gsap', $theme_uri . '/assets/vendor/gsap/gsap.min.js', [], '3', true);
    wp_enqueue_script('gsap-scrollto', $theme_uri . '/assets/vendor/gsap/ScrollToPlugin.min.js', ['gsap'], '3', true);
    wp_enqueue_script('gsap-scrolltrigger', $theme_uri . '/assets/vendor/gsap/ScrollTrigger.min.js', ['gsap'], '3', true);
    wp_enqueue_script('smooth-scrollbar', $theme_uri . '/assets/vendor/smooth-scrollbar.js', ['jquery'], '8', true);
    wp_enqueue_script('swiper', $theme_uri . '/assets/vendor/swiper/js/swiper-bundle.min.js', [], '8', true);
    wp_enqueue_script('imagesloaded', $theme_uri . '/assets/vendor/isotope/imagesloaded.pkgd.min.js', ['jquery'], '4', true);
    wp_enqueue_script('isotope', $theme_uri . '/assets/vendor/isotope/isotope.pkgd.min.js', ['imagesloaded'], '3', true);
    wp_enqueue_script('isotope-packery', $theme_uri . '/assets/vendor/isotope/packery-mode.pkgd.min.js', ['isotope'], '2', true);
    wp_enqueue_script('lightgallery', $theme_uri . '/assets/vendor/lightgallery/js/lightgallery-all.min.js', ['jquery'], '2', true);
    wp_enqueue_script('jquery-mousewheel', $theme_uri . '/assets/vendor/jquery.mousewheel.min.js', ['jquery'], '3.1.13', true);

    // Theme JS
    wp_enqueue_script('nui-theme', $theme_uri . '/assets/js/theme.js', ['jquery','gsap','swiper','isotope','lightgallery'], NUI_VERSION, true);
});

// Register Portfolio Custom Post Type and Taxonomy
add_action('init', function () {
    // CPT: portfolio
    $labels = [
        'name'               => _x('Portfolio', 'post type general name', 'nui-portfolio'),
        'singular_name'      => _x('Portfolio Item', 'post type singular name', 'nui-portfolio'),
        'menu_name'          => _x('Portfolio', 'admin menu', 'nui-portfolio'),
        'name_admin_bar'     => _x('Portfolio Item', 'add new on admin bar', 'nui-portfolio'),
        'add_new'            => _x('Add New', 'portfolio', 'nui-portfolio'),
        'add_new_item'       => __('Add New Portfolio Item', 'nui-portfolio'),
        'new_item'           => __('New Portfolio Item', 'nui-portfolio'),
        'edit_item'          => __('Edit Portfolio Item', 'nui-portfolio'),
        'view_item'          => __('View Portfolio Item', 'nui-portfolio'),
        'all_items'          => __('All Portfolio Items', 'nui-portfolio'),
        'search_items'       => __('Search Portfolio', 'nui-portfolio'),
        'not_found'          => __('No portfolio items found.', 'nui-portfolio'),
        'not_found_in_trash' => __('No portfolio items found in Trash.', 'nui-portfolio')
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'portfolio'],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => ['title','editor','thumbnail','excerpt'],
        'show_in_rest'       => true,
    ];
    register_post_type('portfolio', $args);

    // Taxonomy: portfolio_category
    $tx_labels = [
        'name'          => _x('Portfolio Categories', 'taxonomy general name', 'nui-portfolio'),
        'singular_name' => _x('Portfolio Category', 'taxonomy singular name', 'nui-portfolio'),
        'search_items'  => __('Search Categories', 'nui-portfolio'),
        'all_items'     => __('All Categories', 'nui-portfolio'),
        'edit_item'     => __('Edit Category', 'nui-portfolio'),
        'update_item'   => __('Update Category', 'nui-portfolio'),
        'add_new_item'  => __('Add New Category', 'nui-portfolio'),
        'new_item_name' => __('New Category Name', 'nui-portfolio'),
        'menu_name'     => __('Categories', 'nui-portfolio'),
    ];
    register_taxonomy('portfolio_category', ['portfolio'], [
        'hierarchical'      => true,
        'labels'            => $tx_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'portfolio-category'],
        'show_in_rest'      => true,
    ]);
});

// Image size for portfolio grid
add_action('after_setup_theme', function () {
    add_image_size('nui-portfolio-grid', 1200, 800, true);
});

// Admin: Demo Content Importer (creates 5 posts + 5 portfolio items with Picsum images)
add_action('admin_menu', function () {
    add_theme_page(
        __('Import Demo', 'nui-portfolio'),
        __('Import Demo', 'nui-portfolio'),
        'manage_options',
        'nui-import-demo',
        function () {
            if (!current_user_can('manage_options')) { return; }
            echo '<div class="wrap"><h1>Import Demo</h1>';
            if (isset($_POST['nui_do_import']) && check_admin_referer('nui_import_demo_action', 'nui_import_demo_nonce')) {
                $created = nui_portfolio_import_demo_content();
                printf('<div class="notice notice-success"><p>Imported: %d posts, %d portfolio items.</p></div>', $created['posts'], $created['portfolio']);
            }
            echo '<form method="post">';
            wp_nonce_field('nui_import_demo_action', 'nui_import_demo_nonce');
            submit_button(__('Import 5 Posts + 5 Portfolio (Picsum images)', 'nui-portfolio'), 'primary', 'nui_do_import');
            echo '</form></div>';
        }
    );
});

function nui_portfolio_import_demo_content(): array {
    $post_count = 0; $portfolio_count = 0;

    // Helper: create image from Picsum and attach as featured image
    $attach_featured = function ($post_id, $w = 1200, $h = 800) {
        $seed = wp_generate_password(8, false, false);
        $url  = sprintf('https://picsum.photos/seed/%s/%d/%d', $seed, $w, $h);
        // media_sideload_image returns HTML on success; we need attachment ID
        $tmp = download_url($url);
        if (is_wp_error($tmp)) { return; }
        $file_array = [
            'name' => 'picsum-'.time().'.jpg',
            'tmp_name' => $tmp,
        ];
        $id = media_handle_sideload($file_array, $post_id);
        if (is_wp_error($id)) {
            @unlink($file_array['tmp_name']);
            return;
        }
        set_post_thumbnail($post_id, $id);
    };

    // Create 5 blog posts
    for ($i=1; $i<=5; $i++) {
        $pid = wp_insert_post([
            'post_type'   => 'post',
            'post_title'  => sprintf('Demo Post %d', $i),
            'post_status' => 'publish',
            'post_content'=> 'This is a demo post generated automatically for design preview. Replace this content in wp-admin.',
            'post_excerpt'=> 'Demo excerpt for design preview.',
        ]);
        if ($pid && !is_wp_error($pid)) {
            $attach_featured($pid, 1200, 700);
            $post_count++;
        }
    }

    // Ensure a few portfolio categories
    $cats = ['People','Creative','Nature'];
    foreach ($cats as $c) { if (!term_exists($c, 'portfolio_category')) { wp_insert_term($c, 'portfolio_category'); } }
    $terms = get_terms(['taxonomy'=>'portfolio_category','hide_empty'=>false]);

    // Create 5 portfolio items
    for ($i=1; $i<=5; $i++) {
        $pid = wp_insert_post([
            'post_type'   => 'portfolio',
            'post_title'  => sprintf('Demo Project %d', $i),
            'post_status' => 'publish',
            'post_content'=> 'This is a demo portfolio item generated automatically for design preview. Replace with your project details.',
            'post_excerpt'=> 'Short description of the project.',
        ]);
        if ($pid && !is_wp_error($pid)) {
            // Random category
            if (!empty($terms) && !is_wp_error($terms)) {
                $term = $terms[array_rand($terms)];
                wp_set_post_terms($pid, [$term->term_id], 'portfolio_category');
            }
            $attach_featured($pid, 1200, 800);
            $portfolio_count++;
        }
    }

    return ['posts'=>$post_count, 'portfolio'=>$portfolio_count];
}

// -----------------------------
// Booking System (CPT + Form)
// -----------------------------

// 1) Register "booking" custom post type (private, visible in admin)
add_action('init', function () {
    $labels = [
        'name'          => _x('Bookings', 'post type general name', 'nui-portfolio'),
        'singular_name' => _x('Booking', 'post type singular name', 'nui-portfolio'),
        'menu_name'     => _x('Bookings', 'admin menu', 'nui-portfolio'),
        'add_new_item'  => __('Add New Booking', 'nui-portfolio'),
        'edit_item'     => __('Edit Booking', 'nui-portfolio'),
        'view_item'     => __('View Booking', 'nui-portfolio'),
        'all_items'     => __('All Bookings', 'nui-portfolio'),
        'search_items'  => __('Search Bookings', 'nui-portfolio'),
        'not_found'     => __('No bookings found.', 'nui-portfolio'),
    ];

    register_post_type('booking', [
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'           => ['title','editor','custom-fields'],
        'show_in_rest'       => false,
    ]);
});

// 2) Shortcode to render booking form: [booking_form]
add_shortcode('booking_form', function ($atts = []) {
    $atts = shortcode_atts([
        'title' => __('Booking Ruangan', 'nui-portfolio'),
    ], $atts, 'booking_form');

    $action = esc_url(admin_url('admin-post.php'));
    $success = isset($_GET['booking']) && $_GET['booking'] === 'success';
    $error   = isset($_GET['booking']) && $_GET['booking'] === 'error';

    ob_start();
    ?>
    <div class="tt-section padding-top-xlg-150 padding-bottom-xlg-150 bg-white-accent-3">
        <div class="tt-section-inner tt-wrap max-width-700">
            <div class="tt-heading tt-heading-xxlg margin-bottom-8-p anim-fadeinup">
                <h3 class="tt-heading-subtitle"><?php echo esc_html__('Formulir', 'nui-portfolio'); ?></h3>
                <h2 class="tt-heading-title"><?php echo esc_html($atts['title']); ?></h2>
            </div>

            <?php if ($success): ?>
                <div class="notice notice-success" style="padding:12px; margin-bottom:16px; background:#e6ffed; border-left:4px solid #28a745;">
                    <?php echo esc_html__('Terima kasih! Permohonan booking Anda telah kami terima. Kami akan menghubungi Anda segera.', 'nui-portfolio'); ?>
                </div>
            <?php elseif ($error): ?>
                <div class="notice notice-error" style="padding:12px; margin-bottom:16px; background:#ffe6e6; border-left:4px solid #dc3545;">
                    <?php echo esc_html__('Maaf, terjadi kesalahan saat mengirim data. Silakan coba lagi.', 'nui-portfolio'); ?>
                </div>
            <?php endif; ?>

            <form class="tt-form-filled anim-fadeinup" method="post" action="<?php echo $action; ?>">
                <input type="hidden" name="action" value="nui_submit_booking" />
                <?php wp_nonce_field('nui_booking_nonce_action', 'nui_booking_nonce'); ?>

                <div class="tt-row">
                    <div class="tt-col-md-6">
                        <div class="tt-form-group">
                            <label><?php echo esc_html__('Nama Lengkap', 'nui-portfolio'); ?> <span class="required">*</span></label>
                            <input class="tt-form-control" type="text" name="name" required />
                        </div>
                    </div>
                    <div class="tt-col-md-6">
                        <div class="tt-form-group">
                            <label><?php echo esc_html__('Email', 'nui-portfolio'); ?> <span class="required">*</span></label>
                            <input class="tt-form-control" type="email" name="email" required />
                        </div>
                    </div>
                </div>

                <div class="tt-row">
                    <div class="tt-col-md-6">
                        <div class="tt-form-group">
                            <label><?php echo esc_html__('No. WhatsApp/HP', 'nui-portfolio'); ?> <span class="required">*</span></label>
                            <input class="tt-form-control" type="text" name="phone" required />
                        </div>
                    </div>
                    <div class="tt-col-md-6">
                        <div class="tt-form-group">
                            <label><?php echo esc_html__('Tanggal Acara', 'nui-portfolio'); ?> <span class="required">*</span></label>
                            <input class="tt-form-control" type="date" name="date" required />
                        </div>
                    </div>
                </div>

                <div class="tt-form-group">
                    <label><?php echo esc_html__('Ruangan', 'nui-portfolio'); ?> <span class="required">*</span></label>
                    <select class="tt-form-control" name="room" required>
                        <option value=""><?php echo esc_html__('Pilih Ruangan', 'nui-portfolio'); ?></option>
                        <option value="Studio Surya Paloh">Studio Surya Paloh</option>
                        <option value="Plaza Fisipol">Plaza Fisipol</option>
                        <option value="Joglo">Joglo</option>
                        <option value="Ruang Teater Surya Paloh">Ruang Teater Surya Paloh</option>
                        <option value="Studio Podcast Rohana Kudus">Studio Podcast Rohana Kudus</option>
                        <option value="Perpustakaan Angku Navis">Perpustakaan Angku Navis</option>
                    </select>
                </div>

                <div class="tt-form-group">
                    <label><?php echo esc_html__('Keterangan/Deskripsi Kegiatan', 'nui-portfolio'); ?></label>
                    <textarea class="tt-form-control" rows="5" name="message" placeholder="Ceritakan singkat acara Anda..."></textarea>
                </div>

                <small class="tt-form-text"><em><?php echo esc_html__('Tanda (*) wajib diisi', 'nui-portfolio'); ?></em></small>
                <button type="submit" class="tt-btn tt-btn-primary margin-top-30">
                    <div data-hover="Kirim Permohonan">Kirim Permohonan</div>
                    <span class="tt-btn-icon"><i class="fas fa-paper-plane"></i></span>
                </button>
            </form>
        </div>
    </div>
    <?php
    return ob_get_clean();
});

// 3) Handle booking form submission
add_action('admin_post_nopriv_nui_submit_booking', 'nui_handle_submit_booking');
add_action('admin_post_nui_submit_booking', 'nui_handle_submit_booking');

function nui_handle_submit_booking() {
    // Verify nonce
    if (!isset($_POST['nui_booking_nonce']) || !wp_verify_nonce($_POST['nui_booking_nonce'], 'nui_booking_nonce_action')) {
        return nui_booking_redirect(false);
    }

    // Sanitize inputs
    $name    = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $email   = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $phone   = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
    $date    = isset($_POST['date']) ? sanitize_text_field(wp_unslash($_POST['date'])) : '';
    $room    = isset($_POST['room']) ? sanitize_text_field(wp_unslash($_POST['room'])) : '';
    $message = isset($_POST['message']) ? wp_kses_post(wp_unslash($_POST['message'])) : '';

    if (empty($name) || empty($email) || empty($phone) || empty($date) || empty($room)) {
        return nui_booking_redirect(false);
    }

    // Create booking post (pending)
    $post_id = wp_insert_post([
        'post_type'   => 'booking',
        'post_title'  => sprintf('%s - %s (%s)', $room, $name, $date),
        'post_status' => 'pending',
        'post_content'=> $message,
    ]);

    if (!$post_id || is_wp_error($post_id)) {
        return nui_booking_redirect(false);
    }

    // Save meta
    update_post_meta($post_id, 'booking_name', $name);
    update_post_meta($post_id, 'booking_email', $email);
    update_post_meta($post_id, 'booking_phone', $phone);
    update_post_meta($post_id, 'booking_date', $date);
    update_post_meta($post_id, 'booking_room', $room);

    // Notify admin via email
    $admin_email = get_option('admin_email');
    $subject = sprintf('[Booking] %s - %s', $room, $date);
    $body    = sprintf(
        "Nama: %s\nEmail: %s\nTelepon: %s\nTanggal: %s\nRuangan: %s\n\nPesan:\n%s\n\nLihat di WP Admin: %s",
        $name, $email, $phone, $date, $room, wp_strip_all_tags($message), admin_url('post.php?post=' . $post_id . '&action=edit')
    );
    wp_mail($admin_email, $subject, $body);

    return nui_booking_redirect(true);
}

function nui_booking_redirect($success) {
    $redirect = wp_get_referer();
    if (!$redirect) { $redirect = home_url('/'); }
    $redirect = add_query_arg('booking', $success ? 'success' : 'error', $redirect);
    wp_safe_redirect($redirect);
    exit;
}
