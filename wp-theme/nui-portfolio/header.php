<?php
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" href="<?php echo esc_url(get_site_icon_url()); ?>" />
    <?php wp_head(); ?>
</head>
<body <?php body_class('tt-transition tt-boxed tt-smooth-scroll tt-magic-cursor'); ?> id="body">
    <main id="body-inner">
        <div id="page-transition">
            <div class="ptr-overlay"></div>
            <div class="ptr-preloader">
                <div class="ptr-prel-content">
                    <?php if (has_custom_logo()) { echo wp_get_attachment_image(get_theme_mod('custom_logo'), 'full', false, ['class' => 'ptr-prel-image tt-logo-light']); } else { ?>
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/img/logo-light.png'); ?>" class="ptr-prel-image tt-logo-light" alt="Logo">
                    <?php } ?>
                </div>
            </div>
        </div>
        <div id="magic-cursor"><div id="ball"></div></div>
        <div id="scroll-container">
            <header id="tt-header" class="tt-header-fixed">
                <div class="tt-header-inner">
                    <div class="tt-header-col">
                        <div class="tt-logo">
                            <a href="<?php echo esc_url(home_url('/')); ?>">
                                <?php if (has_custom_logo()) {
                                    the_custom_logo();
                                } else { ?>
                                    <img src="<?php echo esc_url(get_template_directory_uri().'/assets/img/logo-light.png'); ?>" class="tt-logo-light magnetic-item" alt="Logo">
                                    <img src="<?php echo esc_url(get_template_directory_uri().'/assets/img/logo-dark.png'); ?>" class="tt-logo-dark magnetic-item" alt="Logo">
                                <?php } ?>
                            </a>
                        </div>
                    </div>
                    <div class="tt-header-col">
                        <div id="tt-ol-menu-toggle-btn-wrap">
                            <div class="tt-ol-menu-toggle-btn-text-wrap hide-cursor">
                                <div class="tt-ol-menu-toggle-btn-text">
                                    <span class="text-menu" data-hover="Open">Menu</span>
                                    <span class="text-close">Close</span>
                                </div>
                            </div>
                            <div class="tt-ol-menu-toggle-btn-holder">
                                <a href="#" class="tt-ol-menu-toggle-btn magnetic-item"><span></span></a>
                            </div>
                        </div>
                        <nav class="tt-overlay-menu tt-ol-menu-count">
                            <div class="tt-ol-menu-ghost">Explore</div>
                            <div class="tt-ol-menu-holder">
                                <div class="tt-ol-menu-inner tt-wrap">
                                    <div class="tt-ol-menu-content">
                                        <?php
                                        if (has_nav_menu('primary')) {
                                            wp_nav_menu([
                                                'theme_location' => 'primary',
                                                'container' => false,
                                                'menu_class' => 'tt-ol-menu-list',
                                                'fallback_cb' => false,
                                            ]);
                                        } else { ?>
                                            <ul class="tt-ol-menu-list">
                                                <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                                                <li><a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>">Blog</a></li>
                                                <li><a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>">Articles</a></li>
                                                <li><a href="<?php echo esc_url(home_url('/contact')); ?>">Contact</a></li>
                                            </ul>
                                        <?php } ?>
                                        <ul class="tt-ol-menu-social">
                                            <li><h6 class="tt-ol-menu-social-heading">Social Links:</h6></li>
                                            <li><a href="#" target="_blank" rel="noopener">Facebook</a></li>
                                            <li><a href="#" target="_blank" rel="noopener">Twitter</a></li>
                                            <li><a href="#" target="_blank" rel="noopener">Youtube</a></li>
                                            <li><a href="#" target="_blank" rel="noopener">Dribbble</a></li>
                                            <li><a href="#" target="_blank" rel="noopener">Behance</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </header>
            <div id="content-wrap">
