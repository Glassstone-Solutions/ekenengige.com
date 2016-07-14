<?php
    
    global $wyde_options, $post, $wyde_sidebar_position;
    
    $tags = get_the_terms( get_the_ID(), 'portfolio_tag' );
    if ( is_array($tags) ) {
        
        $tag_ids = array();

        foreach($tags as $tag){
            $tag_ids[] = $tag->term_id;
        } 

    
        $args = array(
            'post_type' => 'wyde_portfolio',
	        'tax_query' => array(
		        array(
			        'taxonomy' => 'portfolio_tag',
			        'field'    => 'id',
			        'terms'    => $tag_ids,
		        ),
	        ),
            'post__not_in' => array(get_the_ID()),
            'posts_per_page'    => intval( $wyde_options['portfolio_related_posts'] ),
            'ignore_sticky_posts'   => 1
        );

        $post_query = new WP_Query( $args );
         
        if( $post_query->have_posts() ) {
?>
<div class="related-posts clear">
    <h3><?php echo esc_html( $wyde_options['portfolio_related_title'] );?></h3>
    <ul class="row">
    <?php

    while( $post_query->have_posts() ) {
	    $post_query->the_post();

        $image_size = 'medium';
        $cover_image_url = '';
        $cover_image =  wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $image_size);
        if( $cover_image[0] ){
            $cover_image_url = $cover_image[0];
        }else{
            if( isset($wyde_options['portfolio_placeholder_image']['id']) && !empty($wyde_options['portfolio_placeholder_image']['id']) ){
                $cover_image = wp_get_attachment_image_src($wyde_options['portfolio_placeholder_image']['id'], $image_size );
                $cover_image_url = $cover_image[0];
            }else{
                $cover_image_url = $wyde_options['portfolio_placeholder_image']['url'];
            }
        }
	?>
	    <li class="col col-2">
            <span class="thumb">
            <?php echo sprintf('<a href="%s" title=""><img src="%s" alt="%s" class="post-thumb" /></a>', esc_url(get_permalink()), esc_url($cover_image_url), get_the_title() );?>
            </span>
		</li>
	<?php
	}
    ?>
    </ul>
</div>
<?php
	    }
    wp_reset_postdata();
    }