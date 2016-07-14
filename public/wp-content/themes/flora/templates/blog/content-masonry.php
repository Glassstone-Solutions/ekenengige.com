<?php
    
    global $wyde_options, $wyde_section_fullwidth, $wyde_masonry_classes;

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
    
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php if( $post_format == 'quote' ){ ?>
    <div class="post-content">        
        <div class="w-wrapper">
            <?php wyde_post_title(); ?>
            <?php if( $wyde_options['blog_meta_date'] ): ?>
            <div class="post-meta">
                <span class="post-date"><?php echo get_the_date(); ?></span>
                <?php edit_post_link('', '<span class="meta-edit">', '</span>' ); ?>
                <?php endif; ?>
                <?php if( $wyde_options['blog_meta_category'] ): ?>
                <span class="meta-category">
                    <strong><?php echo __('In', 'flora'); ?></strong><?php echo wyde_get_single_category(); ?>
                </span>  
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php }else{ ?>
    <div class="post-media">
        <?php if( $images ){ ?>
        <div class="w-gallery owl-carousel" data-loop="true">
        <?php } ?>
            <?php
            $image_size = $wyde_section_fullwidth ? 'preview-medium' : 'medium';
            if( is_array($wyde_masonry_classes) && ( in_array('w-w2', $wyde_masonry_classes ) || in_array('w-h2', $wyde_masonry_classes ) ) ){
                $image_size = $wyde_section_fullwidth ? 'preview-large' : 'large';
            }
            $cover_id = get_post_thumbnail_id(get_the_ID()); 
            $cover_image = wp_get_attachment_image_src($cover_id, $image_size ); 

            $link_attrs = array();
            $media_button = false;

            if( !empty( $embed_url ) ){

                $link_attrs['href'] = wyde_get_media_preview( $embed_url );
                $link_attrs['data-rel'] = 'prettyPhoto['. get_the_ID(). ']';
                $link_attrs['class'] = 'w-preview';
                $media_button = true;

            }else{
                $link_attrs['href'] = esc_url( get_permalink() );
            }

            $cover_image_url = $cover_image[0];
            if( !$cover_image_url ){
            if( isset($wyde_options['blog_placeholder_image']['id']) && !empty($wyde_options['blog_placeholder_image']['id']) ){
                    $cover_image = wp_get_attachment_image_src($wyde_options['blog_placeholder_image']['id'], $image_size );
                    $cover_image_url = $cover_image[0];
                }else{
                    $cover_image_url = $wyde_options['blog_placeholder_image']['url'];
                }
            }
            ?>
	        <div class="cover-image" style="background-image: url('<?php echo esc_url( $cover_image_url ); ?>');">
                <a<?php echo wyde_get_attributes( $link_attrs );?>>
                <?php if( $media_button ){ ?>
                    <span class="w-media-player"></span>
                <?php } ?>
                </a>
	        </div>
	        <?php   
            if( is_array($images) ): 
                foreach( $images as $image_id => $image_url ):
                    $gallery_image = wp_get_attachment_image_src($image_id, $image_size); 
            ?>
            <?php if($gallery_image[0]):?>
	        <div class="cover-image" style="background-image: url('<?php echo esc_url( $gallery_image[0] ); ?>');">
                <a href="<?php echo esc_url( get_permalink() ); ?>"></a>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="post-content">
        <div class="w-wrapper">
            <?php   wyde_post_title();  ?>
            <div class="post-meta">
                <?php if($wyde_options['blog_meta_date']){?>
                <span class="post-date"><?php echo get_the_date(); ?></span>
                <?php }?>
                <?php edit_post_link('', '<span class="meta-edit">', '</span>' ); ?>
                <?php if( $post_format == 'link' && $wyde_options['blog_meta_category']):?>
                <span class="meta-category">
                    <strong><?php echo __('In', 'flora'); ?></strong><?php echo wyde_get_single_category(); ?>
                </span>  
                <?php endif; ?>
            </div>
            <p class="post-summary">
            <?php 
            if( !empty($post_link) ){
                $urls = parse_url($post_link);        
            ?>
                <a href="<?php echo esc_url($post_link); ?>" target="_blank"><i class="flora-icon-link-ext"></i> <?php echo esc_url( $urls['host'] ); ?></a>
            <?php
            }else{
                echo wyde_get_excerpt();
            }
            ?>
            </p>
        </div>
        <?php if( $post_format != 'link'){?>
        <div class="post-footer">
            <?php if($wyde_options['blog_meta_category']){?>
            <span class="meta-category">
                <strong><?php echo __('In', 'flora');?></strong><?php echo wyde_get_single_category(); ?>
            </span>  
            <?php }?>
            <div class="meta-right">
                <?php
	            if ($wyde_options['blog_meta_comment']  && ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
	            ?>
	            <span class="meta-comment"><?php comments_popup_link( '<i class="w-comment-empty"></i>0', '<i class="w-comment"></i>1', '<i class="w-comment"></i>%'); ?></span>
	            <?php
	            endif;
                ?>
                <?php if($wyde_options['blog_meta_share']){ ?>
                <?php wyde_blog_meta_share_icons(); ?>
            <?php }?>         
            </div>
        </div>
        <?php } ?>
    </div>
    <?php } ?>
</article>