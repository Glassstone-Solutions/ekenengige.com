<?php
    
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $classes = array();

    $styles = array();

    $classes[] =  'col';

    $classes[] =  wyde_get_column_class($width, $width_sm, $column_offset);

    if( !empty($text_style) ){
        if( $text_style == 'custom' ){
            if( !empty($text_color) ){
                $classes[] = 'w-custom-color';
                $styles[] = 'color:'.$text_color;
            }
        }else{
            $classes[] = 'w-text-'.$text_style;    
        }
    } 

    if( !empty($padding_size) ){
        $classes[] = $padding_size;
    }

    if( !empty($text_align) ){
        $classes[] = 'text-'. $text_align;
    }

    $background = '';
    $overlay = '';
    if( !empty($background_image) ){
        $image_id = preg_replace( '/[^\d]/', '', $background_image );
        if( $image_id ){
            $image = wp_get_attachment_image_src( $image_id, 'full' );
            if( $image[0] ){
                $bg_attrs = array();
                $bg_attrs['style'] = sprintf('background-image:url(%s);', $image[0]);                
                $bg_attrs['class'] = 'bg-image';
                if( !empty($background_style) ){
                    switch($background_style){
                        case 'cover':
                        case 'contain':
                        $bg_attrs['class'] .= ' w-size-'.$background_style;
                        break;
                        default:
                        $bg_attrs['class'] .= ' w-style-'.$background_style;
                        break;
                    }
                }
                
                $background = sprintf('<div%s></div>', wyde_get_attributes($bg_attrs) );        

                if($background_overlay == 'color'){

                    $overlay_styles = array();
                    if( !empty($overlay_color) ){

                        $overlay_styles[] = 'background-color:'.$overlay_color;
                        if( !empty($overlay_opacity) ){
                            $overlay_styles[] = 'opacity:'.$overlay_opacity;
                        }

                        $overlay_style = implode(';', $overlay_styles);

                        if( !empty($overlay_style ) ) $overlay_style = ' style="'.$overlay_style.'"';

                        $overlay = sprintf('<div class="bg-overlay"%s></div>', $overlay_style);
                    }
                }
            } 
        }        
    }    

    if( !empty($el_class) ){
        $classes[] = $el_class;
    } 

    if( !empty($css) ) $classes[] = vc_shortcode_custom_css_class( $css, '' );


    if( !empty($background_color) ){
        $styles[] = 'background-color:'.$background_color;
    }

    $attrs['class'] = implode(' ', $classes);
    
    $attrs['style'] = implode(';', $styles);


    if($animation) $attrs['data-animation'] = $animation;
    if($animation_delay) $attrs['data-animation-delay'] = floatval( $animation_delay );


?>
<div<?php echo wyde_get_attributes( $attrs );?>>
    <?php 
    if( !empty($background) ){
        echo sprintf('<div class="section-background">%s%s</div>', $background, $overlay); 
    }
    echo wpb_js_remove_wpautop($content);
    ?>
</div>