<?php

    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $classes = array();

    $styles = array();

    $classes[] = 'w-image';

    if( !empty($alignment) ){
        $classes[] = 'text-'.$alignment;
    }

    if( !empty($style) ){
        $classes[] = 'w-'.$style;
    }

    if( !empty($responsive) ){
        $classes[] = 'w-responsive';
    }

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }

    if( !empty($css) ){
        $classes[] = vc_shortcode_custom_css_class( $css, '' );    
    }


    $attrs['class'] = implode(' ', $classes);


    if($animation) $attrs['data-animation'] = $animation;
    if($animation_delay) $attrs['data-animation-delay'] = floatval( $animation_delay );


    if( !empty($border_color) ){
        $attrs['style'] = 'border-color:'.$border_color;
    }


    $img_id = preg_replace( '/[^\d]/', '', $image );
    $img = wp_get_attachment_image_src( $img_id, $img_size );

    $image_output = '';

    if( isset($img[0]) ):
   
        $link_attrs = array();
        $full_image = '';
        if ( $link_large == 'true' ) {
	
            if ($link_target == "prettyphoto") {
	            $link_attrs['data-rel'] = 'prettyPhoto';
            }else{
                $link_attrs['target'] = $link_target;
            }

            if( $img_size != 'full'){
                $full_image = wp_get_attachment_image_src( $img_id, 'full' );
            }else{
                $full_image = $img;    
            }

            $link_attrs['href'] = $full_image;
        }

        $image_output = '<img src="'. esc_attr( $img[0] ).'" alt="" />';

        if( isset($link_attrs['href']) ){
            $image_output = '<a'.wyde_get_attributes( $link_attrs ).'>' . $image_output . '</a>';
        }

        if( $responsive ){
            $image_output = '<div class="responsive-image" style="background-image:url('.$img[0].');">'.$image_output.'</div>';
        }

?>
<div<?php echo wyde_get_attributes( $attrs );?>>
    <?php echo wpb_js_remove_wpautop( $image_output ); ?>
</div>
<?php endif; ?>