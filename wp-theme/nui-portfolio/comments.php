<?php
/**
 * Comments template
 */

if (post_password_required()) {
    return;
}
?>

<div id="tt-blog-post-comments">
    <?php if (have_comments()) : ?>
        <h4 class="tt-bpc-heading"><?php echo get_comments_number(); ?> comments:</h4>
        <ul class="tt-comments-list">
            <?php
            wp_list_comments([
                'style'       => 'ul',
                'avatar_size' => 60,
                'short_ping'  => true,
                'callback'    => function ($comment, $args, $depth) {
                    $tag = ($args['style'] === 'div') ? 'div' : 'li';
                    ?>
                    <<?php echo $tag; ?> <?php comment_class('tt-comment'); ?> id="comment-<?php comment_ID(); ?>">
                        <a class="tt-comment-avatar" href="#">
                            <?php echo get_avatar($comment, $args['avatar_size'], '', '', ['class' => 'tt-lazy']); ?>
                        </a>
                        <div class="tt-comment-body">
                            <div class="tt-comment-meta">
                                <h4 class="tt-comment-heading"><?php comment_author_link(); ?></h4>
                                <span class="tt-comment-time"><?php printf('%1$s at %2$s', get_comment_date(), get_comment_time()); ?></span>
                            </div>
                            <span class="tt-comment-reply"><?php comment_reply_link(array_merge($args, ['depth' => $depth, 'max_depth' => $args['max_depth']])); ?></span>
                            <div class="tt-comment-text">
                                <?php if ($comment->comment_approved == '0') : ?>
                                    <em><?php _e('Your comment is awaiting moderation.', 'nui-portfolio'); ?></em>
                                <?php endif; ?>
                                <?php comment_text(); ?>
                            </div>
                        </div>
                    </<?php echo $tag; ?>>
                    <?php
                }
            ]);
            ?>
        </ul>
    <?php endif; ?>

    <?php if (comments_open()) : ?>
        <div id="respond" class="comment-respond">
            <?php
            comment_form([
                'class_form'         => 'tt-form-filled anim-fadeinup',
                'title_reply'        => __('Leave a Comment:', 'nui-portfolio'),
                'title_reply_before' => '<h4 class="tt-post-comment-form-heading">',
                'title_reply_after'  => '</h4>',
                'comment_field'      => '<div class="tt-form-group"><label>' . __('Comment', 'nui-portfolio') . ' <span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="6" required></textarea></div>',
                'fields'             => [
                    'author' => '<div class="tt-row"><div class="tt-col-lg-6"><div class="tt-form-group"><label>' . __('Name', 'nui-portfolio') . ' <span class="required">*</span></label><input id="author" name="author" type="text" required></div></div>',
                    'email'  => '<div class="tt-col-lg-6"><div class="tt-form-group"><label>' . __('Email', 'nui-portfolio') . ' <span class="required">*</span></label><input id="email" name="email" type="email" required></div></div></div>',
                ],
                'class_submit'       => 'tt-btn tt-btn-primary',
                'label_submit'       => __('Post Comment', 'nui-portfolio'),
            ]);
            ?>
        </div>
    <?php endif; ?>
</div>
