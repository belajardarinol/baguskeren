<?php get_header(); ?>
<div id="page-content">
    <div class="tt-section">
        <div class="tt-section-inner tt-wrap max-width-900">
            <?php if (have_posts()): while (have_posts()): the_post(); ?>
                <article <?php post_class('tt-blog-post'); ?>>
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="entry-meta text-gray"><?php the_time(get_option('date_format')); ?> — <?php the_author_posts_link(); ?></div>
                    <div class="entry-excerpt"><?php the_excerpt(); ?></div>
                    <a class="tt-btn tt-btn-primary" href="<?php the_permalink(); ?>"><div data-hover="Read More"><?php _e('Read More', 'nui-portfolio'); ?></div><span class="tt-btn-icon"><i class="fas fa-arrow-right"></i></span></a>
                </article>
                <hr/>
            <?php endwhile; else: ?>
                <p><?php _e('No posts found.', 'nui-portfolio'); ?></p>
            <?php endif; ?>
            <div class="pagination"><?php the_posts_pagination(); ?></div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
