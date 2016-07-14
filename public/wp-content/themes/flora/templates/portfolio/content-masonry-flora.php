<figure>
    <?php 
    global $wyde_options, $wyde_section_fullwidth, $wyde_masonry_classes;

    $image_size = $wyde_section_fullwidth ? 'large' : 'medium';
    if( is_array($wyde_masonry_classes) && ( in_array('w-h2', $wyde_masonry_classes ) || in_array('w-w2', $wyde_masonry_classes ) ) ){
        $image_size = $wyde_section_fullwidth ? 'x-large' : 'large';
    }
    $cover_image = '';
    $cover_image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), $image_size ); 
    
    $lightbox_url = '';
    $cover_image_url = '';
    if( $cover_image[0] ){
        $cover_image_url = $cover_image[0];
        if( $wyde_options['portfolio_lightbox_size'] == $image_size ) {
            $lightbox_url = $cover_image[0];
        }else{
            $full_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), $wyde_options['portfolio_lightbox_size'] );
            if( isset($full_image[0]) ){
                $lightbox_url = $full_image[0]; 
            }            
        }        
    }else{
        if( isset($wyde_options['portfolio_placeholder_image']['id']) && !empty($wyde_options['portfolio_placeholder_image']['id']) ){
            $cover_image = wp_get_attachment_image_src($wyde_options['portfolio_placeholder_image']['id'], $image_size );
            $cover_image_url = $cover_image[0];
        }else{
            $cover_image_url = $wyde_options['portfolio_placeholder_image']['url'];
        }
    }

    echo sprintf('<div class="cover-image" style="background-image:url(%s);"></div>', esc_url( $cover_image_url ));
    ?>    
    <figcaption>
        <h3><?php echo esc_html( get_the_title() );?></h3>
        <?php
        $cate_names = array();         
        $categories = get_the_terms( get_the_ID(), 'portfolio_category' );            
        if (is_array( $categories )) { 
            foreach ( $categories as $item ) 
            {
                $cate_names[] = $item->name;
            }
        }
        ?>
        <p><?php echo esc_html( implode(', ', $cate_names) ) ?></p>        
        <a href="<?php echo esc_url( get_permalink() );?>"></a>
        <span>
        <?php if($lightbox_url){ ?>
            <a href="<?php echo esc_url( $lightbox_url );?>" data-rel="prettyPhoto[portfolio]" title="<?php echo esc_attr( get_the_title() ); ?>"></a>
        <?php } ?>
        </span>
    </figcaption>
</figure>