<?php
    
    global $wyde_options, $wyde_title_area;

    $has_cover = has_post_thumbnail();

    $images = get_post_meta(get_the_ID(), '_w_gallery_images', true);
        
    $embed_url = esc_url( get_post_meta(get_the_ID(), '_w_embed_url', true ) );
        
?>
<?php 
    if( $has_cover || !empty( $embed_url ) ):

        $cover_id = get_post_thumbnail_id(get_the_ID()); 

        $image_size = 'full';

        $cover_image = wp_get_attachment_image_src($cover_id, $image_size ); 

        $lightbox_url = '';
        $media_button = false;

        if( !empty( $embed_url ) ){
            $lightbox_url = wyde_get_media_preview( $embed_url );
            $media_button = true;
        }else{
            if( $wyde_options['portfolio_lightbox_size'] == $image_size ) {
                $lightbox_url = $cover_image[0];
            }else{
                $full_image = wp_get_attachment_image_src($cover_id, $wyde_options['portfolio_lightbox_size'] );
                if( isset($full_image[0]) ){
                    $lightbox_url = $full_image[0]; 
                }  
            }
        }

?>
<div class="post-media">
    <?php if( isset($cover_image[0]) ) :?>
    <div class="cover-image" style="background-image: url('<?php echo esc_url( $cover_image[0] );?>');"></div>
    <?php endif; ?>
    <a href="<?php echo esc_url( $lightbox_url );?>" data-rel="prettyPhoto[portfolio]">
        <?php if( $media_button ){ ?>
            <span class="w-media-player"></span>
        <?php } ?>
    </a>
</div>
<?php endif; ?>
<div class="post-content container">
    <?php if( !$wyde_title_area ) the_title('<h2 class="post-title">', '</h2>'); ?>
    <?php the_content(); ?>
    <div class="post-description row">
        <div class="col col-12">
            <?php wyde_portfolio_widget('meta'); ?>
        </div>
        <div class="col col-3">
            <?php wyde_portfolio_widget('categories'); ?>
            <?php wyde_portfolio_widget('skills'); ?>
        </div>
        <div class="col col-8 col-offset-1">
            <?php wyde_portfolio_widget('clients'); ?>
            <?php wyde_portfolio_widget('fields'); ?>
        </div>

    </div>
</div>
<?php if( is_array($images) ) : ?>
<ul class="post-gallery clear">
    <?php
    $item_index = 0;
    foreach( $images as $image_id => $image_url ):
        $image_size = ( $item_index < 2 ) ? 'preview-large':'large';
        $gallery_image = wp_get_attachment_image_src($image_id, $image_size); 
                
        if( $wyde_options['portfolio_lightbox_size'] == $image_size ) {
            $lightbox_url = $gallery_image;
        }else{
            $lightbox_url =  wp_get_attachment_image_src($image_id, $wyde_options['portfolio_lightbox_size'] );
        }
        $item_index++;
    ?>
    <?php if($gallery_image[0]):?>
	<li>
        <div class="cover-image" style="background-image: url('<?php echo esc_url( $gallery_image[0] );?>');"></div>
        <a href="<?php echo esc_url( $lightbox_url[0] );?>" data-rel="prettyPhoto[portfolio]"></a>
	</li>
    <?php endif; ?>
    <?php    
    endforeach;
    ?>
</ul>
<?php endif; ?>
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
