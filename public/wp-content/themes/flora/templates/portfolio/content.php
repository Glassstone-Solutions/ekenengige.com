<figure>
    <?php 

    global $wyde_options, $wyde_section_fullwidth, $wyde_grid_columns;

    $image_size = 'large';
    if( !$wyde_section_fullwidth && $wyde_grid_columns > 3){
        $image_size = 'medium';
    }

    $cover_image = '';
    $cover_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), $image_size ); 
    
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

    echo sprintf('<img class="cover-image" src="%s" alt="%s" />', esc_url( $cover_image_url ), esc_attr( get_the_title() ));
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