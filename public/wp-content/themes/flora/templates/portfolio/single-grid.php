<?php
    
    global $wyde_options, $wyde_title_area;

    $has_cover = has_post_thumbnail();

    $images = get_post_meta(get_the_ID(), '_w_gallery_images', true);
    
    $embed_url = esc_url( get_post_meta(get_the_ID(), '_w_embed_url', true ) );
        
?>
<?php
if( $has_cover || $images || !empty( $embed_url ) ): 
?>
<div class="post-media container">
    <ul class="w-grid clear">
        <?php         
        $image_size = 'full-width';
        $cover_id = get_post_thumbnail_id(get_the_ID()); 
        $cover_image = wp_get_attachment_image_src($cover_id, $image_size ); 
        
        $lightbox_url = '';

        if( !empty( $embed_url ) ){
            $lightbox_url = wyde_get_media_preview( $embed_url );
        ?>
        <li class="w-featured">
            <?php if($cover_image[0]):?>
            <div class="cover-image" style="background-image: url('<?php echo esc_url( $cover_image[0] );?>');"></div>
            <?php endif; ?>
            <a href="<?php echo esc_url($lightbox_url);?>" data-rel="prettyPhoto[portfolio]"><span class="w-media-player"></span></a>
        </li>
        <?php }elseif($has_cover){ 
        $image_size = 'large';
        $cover_image = wp_get_attachment_image_src($cover_id, $image_size ); 
        $lightbox_image = '';
        if( $wyde_options['portfolio_lightbox_size'] == $image_size ) {
            $lightbox_image = $cover_image;
        }else{
            $lightbox_image =  wp_get_attachment_image_src($cover_id, $wyde_options['portfolio_lightbox_size'] );
        }
        ?>
        <li class="col-4">
            <a href="<?php echo esc_url( $lightbox_image[0] );?>" data-rel="prettyPhoto[portfolio]">
                <img src="<?php echo esc_url( $cover_image[0] ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" />
            </a>
        </li>
        <?php } ?>
        <?php if( is_array($images) ):
        foreach( $images as $image_id => $image_url ):
                
                $image_size = 'large';
                $gallery_image = wp_get_attachment_image_src($image_id, $image_size); 
                $lightbox_image = '';
                if( $wyde_options['portfolio_lightbox_size'] == $image_size ) {
                    $lightbox_image = $gallery_image;
                }else{
                    $lightbox_image =  wp_get_attachment_image_src($image_id, $wyde_options['portfolio_lightbox_size'] );
                }
        ?>
        <?php if( isset($gallery_image[0]) ):?>
        <li class="col-4">
            <a href="<?php echo esc_url( $lightbox_image[0] );?>" data-rel="prettyPhoto[portfolio]">
                <img src="<?php echo esc_url( $gallery_image[0] ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" />
            </a>
        </li>
        <?php endif; ?>
        <?php   
            endforeach; 
        endif;
        ?>
    </ul>
</div>
<?php 
endif; 
?>
<div class="post-content container">
    <div class="row">
        <div class="main col col-9">
            <?php if( !$wyde_title_area ) the_title('<h2 class="post-title">', '</h2>'); ?>
            <?php the_content(); ?>            
        </div>
        <div class="sidebar col col-3 post-description">
            <?php wyde_portfolio_sidebar(); ?>
        </div>
    </div>
    <?php if($wyde_options['portfolio_nav']) wyde_portfolio_nav(); ?>
    <?php
    $related = get_post_meta(get_the_ID(), '_w_post_related', true);
    if( empty($related) ){
        $related = $wyde_options['portfolio_related'];
    }
    if( $related && $related !== 'hide' ){
        wyde_portfolio_related();
    }
    ?>
</div>