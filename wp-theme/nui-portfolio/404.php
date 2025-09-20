<?php get_header(); ?>
<div id="page-header" class="ph-full ph-cap-xxlg ph-bg-image ph-image-cover-4">
    <div class="page-header-inner tt-wrap">
        <div class="ph-caption max-width-1000">
            <h1 class="ph-caption-title"><div class="ph-appear">Oops!</div></h1>
            <div class="ph-caption-subtitle"><div class="ph-appear"><?php _e("Sorry, we couldn't find that page.", 'nui-portfolio'); ?></div></div>
        </div>
    </div>
</div>
<footer id="tt-footer" class="footer-absolute">
    <div class="tt-footer-inner">
        <div class="footer-col tt-align-center-left">
            <div class="footer-col-inner">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="tt-btn tt-btn-link">
                    <div data-hover="Back to Home"><?php _e('Back to Home', 'nui-portfolio'); ?></div>
                    <span class="tt-btn-icon"><i class="tt-btn-line"></i></span>
                </a>
            </div>
        </div>
    </div>
</footer>
<?php get_footer(); ?>
