<?php get_header(); ?>
<div id="page-header" class="ph-cap-sm ph-bg-image ph-ghost-scroll ph-image-cover-6 ph-content-parallax">
    <div class="page-header-inner tt-wrap">
        <div class="ph-caption max-width-1000">
            <div class="ph-categories ph-appear">
                <?php the_category(' '); ?>
            </div>
            <h1 class="ph-caption-title"><div class="ph-appear"><?php the_title(); ?></div></h1>
            <div class="ph-meta"><div class="ph-appear"><span class="ph-meta-published"><?php the_time(get_option('date_format')); ?></span> <span class="ph-meta-posted-by"><?php _e('by:', 'nui-portfolio'); ?> <?php the_author_posts_link(); ?></span></div></div>
        </div>
    </div>
    <div class="tt-scroll-down"><a href="#page-content" class="tt-sd-inner ph-appear" data-offset="0"><div class="tt-sd-arrow"><div class="tt-sd-arrow-inner"></div></div><div class="tt-sd-text">Scroll</div></a></div>
</div>
<div id="page-content">
    <div class="tt-section">
        <div class="tt-section-inner tt-wrap max-width-900">
            <article <?php post_class('tt-blog-post lightgallery'); ?>>
                <div class="tt-blog-post-content">
                    <?php if (has_post_thumbnail()): ?>
                        <figure class="tt-blog-post-image"><?php the_post_thumbnail('large'); ?></figure>
                    <?php endif; ?>
                    <?php while (have_posts()): the_post(); the_content(); endwhile; ?>
                </div>
                <div class="tt-blog-post-tags"><ul><li><span>Tags:</span></li><?php the_tags('<li>','</li><li>','</li>'); ?></ul></div>
                <div class="tt-blog-post-share"><div class="tt-bps-text">Share:</div></div>
            </article>
            <div class="tt-blog-post-nav">
                <div class="tt-bp-nav-col tt-bp-nav-left"><?php previous_post_link('%link','<span class="tt-bp-nav-text"><span><i class="fas fa-angle-left"></i></span>Prev Post</span><h4 class="tt-bp-nav-title">%title</h4>'); ?></div>
                <div class="tt-bp-nav-col tt-bp-nav-right"><?php next_post_link('%link','<span class="tt-bp-nav-text">Next Post<span><i class="fas fa-angle-right"></i></span></span><h4 class="tt-bp-nav-title">%title</h4>'); ?></div>
            </div>
            <?php comments_template(); ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
