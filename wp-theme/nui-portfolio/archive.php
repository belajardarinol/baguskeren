<?php get_header(); ?>
<div id="page-content">
    <div class="tt-section">
        <div class="tt-section-inner tt-wrap max-width-900">
            <header class="archive-header"><h1 class="archive-title"><?php the_archive_title(); ?></h1><div class="archive-description"><?php the_archive_description(); ?></div></header>
            <?php if (have_posts()): while (have_posts()): the_post(); ?>
                <article <?php post_class('tt-blog-post'); ?>>
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="entry-meta text-gray"><?php the_time(get_option('date_format')); ?> — <?php the_author_posts_link(); ?></div>
                    <div class="entry-excerpt"><?php the_excerpt(); ?></div>
                </article>
                <hr/>
            <?php endwhile; the_posts_pagination(); else: ?>
                <p><?php _e('No posts found.', 'nui-portfolio'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
