<?php
    
    $author_id    = get_the_author_meta('ID');
    $name         = get_the_author_meta('display_name', $author_id);
    $avatar       = get_avatar( get_the_author_meta('email', $author_id), '82' );
    $description  = get_the_author_meta('description', $author_id);

    if( empty($description) ) {
		$description .= '<p>'.sprintf( __( '%s has created <a href="%s">%s entries</a>.', 'flora' ), $name, get_author_posts_url($author_id), count_user_posts( $author_id ) ).'</p>';
	}
?>
<div class="about-author clear">
	<?php echo sprintf('<a href="%s">%s</a>', get_author_posts_url($author_id), $avatar); ?>
    <div class="author-detail">
        <h4><?php echo __('About', 'flora'); ?> <?php echo esc_html($name); ?></h4>
        <?php if( current_user_can('edit_users') || get_current_user_id() == $author_id ): ?>
        <span class="edit-profile"><a href="<?php echo admin_url( 'profile.php?user_id=' . $author_id ); ?>" target="_blank"><?php echo __( 'Edit Profile', 'flora' ); ?></a></span>
        <?php endif; ?>
        <div class="author-description">
        <?php echo wp_kses_post( $description ); ?>
        </div>
    </div>
</div>