<?php
    global $wyde_options;

    // Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
 
    $home_button_url = '';
    if( $wyde_options['portfolio_home'] && !empty($wyde_options['portfolio_home_url']) ){
        $home_button_url = $wyde_options['portfolio_home_url'];
    } 

?>
<nav class="post-nav clear">
    <div class="prev-post">
    <?php
		if($previous){
            $prev_thumbnail = wyde_get_portfolio_thumbnail($previous->ID);
            previous_post_link('%link', '');
            echo '<div class="post-link clear">';
            previous_post_link('<span>%link</span>', $prev_thumbnail);
            previous_post_link('<h4>%link</h4>');
            echo '</div>';
		} 
    ?>
    </div>
    <div class="next-post">
    <?php
		if($next){
            $next_thumbnail = wyde_get_portfolio_thumbnail($next->ID);
            next_post_link('%link', '');
            echo '<div class="post-link clear">';
            next_post_link('<span>%link</span>', $next_thumbnail);
            next_post_link('<h4>%link</h4>');
            echo '</div>';
		} 
    ?>
    </div>
    <?php if( !empty( $home_button_url ) ):?>
    <div class="nav-home">
        <a href="<?php echo esc_url( $home_button_url );?>"><i class="flora-icon-th"></i></a>
    </div>
    <?php endif; ?>
</nav>