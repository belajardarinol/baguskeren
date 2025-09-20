<?php get_header(); ?>
<div id="page-content">
    <div class="tt-section">
        <div class="tt-section-inner tt-wrap max-width-900">
            <?php while (have_posts()): the_post(); ?>
                <article <?php post_class(); ?>>
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <div class="entry-content"><?php the_content(); ?></div>
                </article>
            <?php endwhile; ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
