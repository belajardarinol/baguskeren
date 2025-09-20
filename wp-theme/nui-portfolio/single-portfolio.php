<?php
/* Single: Portfolio */
get_header();
?>
<?php if (have_posts()): while (have_posts()): the_post(); ?>
<div id="page-header" class="ph-full ph-bg-image ph-image-cover-3 ph-content-parallax">
    <div class="page-header-inner tt-wrap">
        <div class="ph-image">
            <div class="ph-image-inner">
                <?php if (has_post_thumbnail()) { the_post_thumbnail('full'); } else { ?>
                    <img src="<?php echo esc_url(get_template_directory_uri().'/assets/img/page-header/project-ph/project-ph-1.jpg'); ?>" alt="">
                <?php } ?>
            </div>
        </div>
        <div class="ph-caption">
            <div class="ph-categories"><div class="ph-categories-inner ph-appear">
                <div class="ph-category"><?php echo get_the_term_list(get_the_ID(), 'portfolio_category', '', ', '); ?></div>
            </div></div>
            <h1 class="ph-caption-title"><div class="ph-appear"><?php the_title(); ?></div></h1>
            <div class="ph-caption-title-ghost"><div class="ph-appear"><?php the_title(); ?></div></div>
        </div>
    </div>
    <div class="tt-scroll-down"><a href="#page-content" class="tt-sd-inner ph-appear" data-offset="0"><div class="tt-sd-arrow"><div class="tt-sd-arrow-inner"></div></div><div class="tt-sd-text">Scroll</div></a></div>
</div>

<div id="page-content">
    <div class="tt-section padding-top-xlg-180 padding-left-sm-3-p padding-right-sm-3-p">
        <div class="tt-section-inner tt-wrap">
            <div class="tt-row">
                <div class="tt-col-lg-4 padding-right-md-5-p">
                    <div class="tt-heading tt-heading-xsmm margin-bottom-30 anim-fadeinup">
                        <h2 class="tt-heading-title"><?php _e('About the Project', 'nui-portfolio'); ?></h2>
                    </div>
                </div>
                <div class="tt-col-lg-8">
                    <div class="anim-fadeinup">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tt-section padding-bottom-xlg-180">
        <div class="tt-section-inner tt-wrap max-width-900 text-center">
            <?php if (has_excerpt()) : ?>
                <div class="anim-fadeinup text-xlg"><p><?php echo get_the_excerpt(); ?></p></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="tt-section no-padding">
        <div class="tt-section-inner">
            <div class="tt-next-project pn-image-cover-3">
                <?php $next = get_next_post(false, '', 'portfolio_category'); if ($next): ?>
                    <?php $thumb = get_the_post_thumbnail_url($next->ID, 'large'); if ($thumb): ?>
                        <div class="tt-np-image"><img src="<?php echo esc_url($thumb); ?>" alt=""></div>
                    <?php endif; ?>
                    <div class="tt-np-caption">
                        <div class="tt-np-subtitle"><?php _e('Next Project', 'nui-portfolio'); ?></div>
                        <h2 class="tt-np-title"><a href="<?php echo get_permalink($next); ?>" data-cursor="View<br> Project"><?php echo esc_html(get_the_title($next)); ?></a></h2>
                    </div>
                    <div class="tt-np-ghost"><?php _e('Next', 'nui-portfolio'); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endwhile; endif; ?>
<?php get_footer(); ?>
