<?php

    global $wyde_options, $post, $wyde_sidebar_position;

    $tags = get_the_tags();
    
    if ( is_array($tags) ) {
        
        $tag_ids = array();

        foreach($tags as $tag){
            $tag_ids[] = $tag->term_id;
        } 
    
        $args=array(
            'tag__in' => $tag_ids,
            'post__not_in' => array($post->ID),
            'posts_per_page'    => intval( $wyde_options['blog_single_related_posts'] ),
            'ignore_sticky_posts'   => 1
        );

        $post_query = new WP_Query( $args );
         
        if( $post_query->have_posts() ) {
?>
<div class="related-posts">
    <h3><?php echo esc_html( $wyde_options['blog_single_related_title'] );?></h3>
    <ul class="row">
    <?php
    while( $post_query->have_posts() ) :
	    $post_query->the_post();

        $image_size = 'preview-medium';
        $cover_image_url = '';
        $cover_image =  wp_get_attachment_image_src(get_post_thumbnail_id( get_the_ID() ), $image_size);
        if( $cover_image[0] ){
            $cover_image_url = $cover_image[0];
        }else{
            if( isset($wyde_options['blog_placeholder_image']['id']) && !empty($wyde_options['blog_placeholder_image']['id']) ){
                $cover_image = wp_get_attachment_image_src($wyde_options['blog_placeholder_image']['id'], $image_size );
                $cover_image_url = $cover_image[0];
            }else{
                $cover_image_url = $wyde_options['blog_placeholder_image']['url'];
            }
        }

	?>
	    <li class="col <?php echo ($wyde_sidebar_position == '1' ? 'five-cols':'col-3');?>">
            <span class="thumb">
            <?php echo sprintf('<a href="%s" title=""><img src="%s" alt="%s" class="post-thumb" /></a>', esc_url(get_permalink()), esc_url($cover_image_url), get_the_title() );?>
            </span>
            <h4>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h4>
            <span class="date"><?php echo get_the_date(); ?></span>
		</li>
	<?php
	endwhile;
    ?>
    </ul>
</div>
<?php
	    }
	wp_reset_postdata();
    }