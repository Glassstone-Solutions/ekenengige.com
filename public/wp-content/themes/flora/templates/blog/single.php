<?php
    global $wyde_options, $wyde_title_area, $wyde_sidebar_position;

    $post_format = get_post_format();

    $has_cover = has_post_thumbnail();

    $images = '';
    $embed_url = '';
    $post_link = '';
    switch( $post_format ){
        case 'gallery':
            $images = get_post_meta(get_the_ID(), '_w_gallery_images', true);
        break;
        case 'link':
            $post_link = get_post_meta(get_the_ID(), '_w_post_link', true);
        break;
        case 'audio':
        case 'video':
            $embed_url = esc_url( get_post_meta(get_the_ID(), '_w_embed_url', true ) );
        break;
    }
    
    $attrs = array();
    $attrs['class'] = 'post-media';
    if( is_array($images) && count($images) > 0 ){
        $attrs['class'] .= ' owl-carousel';
        $attrs['data-auto-height'] = 'true';
    } 
    
?>
<div class="post-detail clear">
    <?php if( !$wyde_title_area ) wyde_post_title(); ?>
    <?php if( $has_cover || $images || !empty( $embed_url ) ): ?>
    <div<?php echo wyde_get_attributes($attrs); ?>>
        <?php
        
        $image_size = $wyde_options['blog_single_image_size'];

        if($has_cover && $image_size != 'hide'){

            $cover_id = get_post_thumbnail_id(get_the_ID()); 
            $cover_image = wp_get_attachment_image_src($cover_id, $image_size ); 

            $lightbox_url = '';
            $media_button = false;

            if( !empty( $embed_url ) ){

                $lightbox_url = wyde_get_media_preview( $embed_url );
                $media_button = true;

            }else{
                if( $wyde_options['blog_single_lightbox_size'] == $image_size ) {
                    $lightbox_url = $cover_image[0];
                }else{
                    $full_image = wp_get_attachment_image_src($cover_id, $wyde_options['blog_single_lightbox_size'] );
                    if( isset($full_image[0]) ){
                        $lightbox_url = $full_image[0]; 
                    }                   
                }
            }
        ?>
	    <div class="featured-<?php echo esc_attr( $image_size ); ?>">
            <a href="<?php echo esc_url( $lightbox_url );?>" data-rel="prettyPhoto[<?php echo get_the_ID();?>]">
                <?php if($cover_image[0]):?>
                <img src="<?php echo esc_url( $cover_image[0] ); ?>" alt="<?php echo esc_attr( get_the_title() );?>" />
                <?php endif; ?>
                <?php if( $media_button ){ ?>
                    <span class="w-media-player"></span>
                <?php } ?>
            </a>
	    </div>
	    <?php 
        }else if( !empty($embed_url) ){ 
        ?>
        <div class="video-wrapper">
        <?php 
            echo wp_oembed_get($embed_url, array(
                    'width'     => '1170',
                    'height'    => '658'
            ));
        ?>
        </div>
        <?php
        } 
        if( is_array($images) ): 
            foreach( (array)$images as $image_id => $image_url ): 
                $gallery_image = wp_get_attachment_image_src($image_id, $image_size); 
                $lightbox_image = '';
                if( $wyde_options['blog_single_lightbox_size'] == $image_size ) {
                    $lightbox_image = $gallery_image;
                }else{
                    $lightbox_image =  wp_get_attachment_image_src($image_id, $wyde_options['blog_single_lightbox_size'] );
                }
        ?>
        <?php if($gallery_image[0]):?>
        <div>
            <a href="<?php echo esc_url( $lightbox_image[0] );?>" data-rel="prettyPhoto[<?php echo get_the_ID();?>]">
                <img src="<?php echo esc_url( $gallery_image[0] ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" />
            </a>
        </div>
        <?php endif; ?>
        <?php       
            endforeach; 
        endif; 
        ?>
    </div>
    <?php 
    endif; 
    ?>
    <div class="post-meta">
        <?php   if( $wyde_options['blog_meta_date'] !== '0' ): ?>
        <span class="meta-date">
            <a href="<?php echo esc_url( get_day_link( get_the_date('Y'), get_the_date('m'), get_the_date('d') ) );?>">
                <strong><?php echo get_the_date('d');?></strong>
                <span>
                    <em><?php echo get_the_date('M');?></em>
                    <em><?php echo get_the_date('Y');?></em>
                </span>
            </a>
        </span>
        <?php   endif;	?>
        <?php if( $wyde_options['blog_meta_author'] !== '0' ){?>
        <span class="meta-author">
            <strong><?php echo __('By', 'flora');?></strong><?php echo the_author_posts_link();?>
        </span>
        <?php }?>
        <?php if( $wyde_options['blog_meta_category'] !== '0' ){?>
        <span class="meta-category">
            <strong><?php echo __('In', 'flora');?></strong><?php echo wyde_get_single_category(); ?>
        </span>  
        <?php }?>
        <?php
        edit_post_link('', '<span class="meta-edit">', '</span>' );
	    ?>
        <div class="meta-right">
            <?php if ( $wyde_options['blog_meta_comment'] !== '0' && ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
	        <span class="meta-comment"><?php comments_popup_link( '<i class="w-comment-empty"></i>'.__('Add Comment', 'flora'), '<i class="w-comment"></i>1 '.__('Comment', 'flora'), '<i class="w-comment"></i>% '.__('Comments', 'flora')); ?></span>
	        <?php endif; ?>
            <?php if( $wyde_options['blog_meta_share'] !== '0' ){ ?>
            <span class="post-share">
                <span class="share-links">
                    <a href="http://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( esc_url( get_permalink() ) );?>" target="_blank"><i class="flora-icon-facebook"></i></a>
                    <a href="https://twitter.com/intent/tweet?source=webclient&amp;url=<?php echo urlencode( esc_url( get_permalink() ) );?>&amp;text=<?php echo urlencode( get_the_title() );?>" target="_blank"><i class="flora-icon-twitter"></i></a>
                    <a href="https://plus.google.com/share?url=<?php echo urlencode( esc_url( get_permalink() ) );?>" target="_blank"><i class="flora-icon-google-plus"></i></a>
                </span>
                <a href="#" class="share-icon"><i class="flora-icon-share"></i></a>
            </span>
            <?php }?>  
        </div>
    </div>
    <?php 
    if( !empty($post_link) ):
        $urls = parse_url($post_link);        
    ?>
        <p class="post-external-link"><a href="<?php echo esc_url($post_link); ?>" target="_blank"><i class="flora-icon-link-ext"></i> <?php echo esc_url( $urls['host'] ); ?></a></p>
    <?php endif; ?>
    <?php   the_content();  ?>
    <?php   wp_link_pages(array( 'before' => '<div class="page-links clear">', 'after' => '</div>', 'link_before' => '<span>', 'link_after'  => '</span>' )); ?>
    <?php   if( $wyde_options['blog_single_tags'] !== '0' ) the_tags('<div class="post-tags"><i class="flora-icon-bookmark"></i>', ', ', '</div>' );   ?>
</div>
<?php   
    $author_box = get_post_meta(get_the_ID(), '_w_post_author', true);
    if( empty($author_box) ){
        $author_box = $wyde_options['blog_single_author'];
    }

    if( $author_box && $author_box !== 'hide' ){
        wyde_post_author();   
    }
?>
<?php   
    if( $wyde_options['blog_single_nav'] !== '0' ){
        wyde_post_nav();   
    } 
?>
<?php
    $related = get_post_meta(get_the_ID(), '_w_post_related', true);
    if( empty($related) ){
        $related = $wyde_options['blog_single_related'];
    }

    if( $related && $related !== 'hide' ){
       
        wyde_related_posts();
    }

	if ( $wyde_options['blog_single_comment'] !== '0' && (comments_open() || get_comments_number()) ) {
		comments_template();
	}
?>