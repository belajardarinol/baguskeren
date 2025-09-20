<?php
/* Archive: Portfolio */
get_header();
?>
<div id="page-header" class="ph-cap-sm ph-ghost-scroll ph-image-cropped ph-content-parallax">
    <div class="page-header-inner tt-wrap">
        <div class="ph-caption">
            <h1 class="ph-caption-title"><div class="ph-appear"><?php post_type_archive_title(); ?></div></h1>
            <div class="ph-caption-title-ghost"><div class="ph-appear">Works</div></div>
        </div>
    </div>
    <div class="tt-scroll-down"><a href="#page-content" class="tt-sd-inner ph-appear" data-offset="0"><div class="tt-sd-arrow"><div class="tt-sd-arrow-inner"></div></div><div class="tt-sd-text">Scroll</div></a></div>
</div>

<div id="page-content">
    <div class="tt-section">
        <div class="tt-section-inner">
            <div id="portfolio-grid" class="pgi-hover">
                <div class="tt-grid ttgr-layout-3 ttgr-gap-4">
                    <div class="tt-grid-items-wrap isotope-items-wrap">
                        <?php if (have_posts()): while (have_posts()): the_post(); ?>
                            <div class="tt-grid-item isotope-item">
                                <div class="ttgr-item-inner">
                                    <div class="portfolio-grid-item">
                                        <a href="<?php the_permalink(); ?>" class="pgi-image-wrap" data-cursor="View<br>Project">
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
                        <?php endwhile; else: ?>
                            <p class="text-center"><?php _e('No portfolio items found.', 'nui-portfolio'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="pagination tt-wrap max-width-900 text-center margin-top-60">
                <?php the_posts_pagination(); ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
