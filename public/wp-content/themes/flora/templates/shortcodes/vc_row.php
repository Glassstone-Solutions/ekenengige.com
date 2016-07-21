<?php

    global $wyde_page_layout, $wyde_section_fullwidth;

    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );    

	$attrs = array();
    $classes = array();

    if( !empty($el_id) ){
        $attrs['id'] = $el_id;
    }
    
    if( $wyde_page_layout != 'wide' || is_single() ){
        $classes[] = 'row';
    }else{
        $classes[] = 'w-section';
    }

    if( $row_style == 'full-width' ){
        $classes[] = 'w-full';
        $wyde_section_fullwidth = 1;
    }else{
        $wyde_section_fullwidth = 0;
    }


    if( !empty($text_style) ) $classes[] = 'w-text-'.$text_style;    
    if( !empty($full_screen) ) $classes[] = 'w-fullscreen';    
    if( !empty($padding_size) ) $classes[] = $padding_size;    
    if( !empty($vertical_align) ) $classes[] = 'w-v-align w-'.$vertical_align;


    $background = '';
    $overlay = '';
    if( !empty($background_image) ){
        $image_id = preg_replace( '/[^\d]/', '', $background_image );
        if( $image_id ){
            $image = wp_get_attachment_image_src( $image_id, 'full' );
            if( $image[0] ){
                $bg_attrs = array();
                $bg_attrs['style'] = sprintf("background-image:url('%s');", $image[0]);                
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

                if( !empty($parallax) ){
                    $classes[] =  'w-parallax';
                    if( $parallax == 'reverse' ) $classes[] = 'w-reverse';
                    if( $parallax == 'fade' ) $classes[] =  'w-fadeout';
                }

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

    if( !empty($css) ){
        $classes[] = vc_shortcode_custom_css_class( $css, '' );
    } 
    
    if( !empty($el_class) ){
        $classes[] =  $el_class;
    } 

    $attrs['class'] = implode(' ', $classes);     

    if( !empty($background_color) ){
        $attrs['style'] = 'background-color:'.$background_color;
    }

    if( $wyde_page_layout != 'wide' || is_single() ){
?>
<div<?php echo wyde_get_attributes( $attrs );?>>
<?php 
    if( !empty($background) ){
        echo sprintf('<div class="section-background">%s%s</div>', $background, $overlay); 
    }
    echo wpb_js_remove_wpautop($content);
    ?>
</div>
<?php
    }else{
?>
<section<?php echo wyde_get_attributes( $attrs ); ?>>
    <?php 
    if( !empty($background) ){
        echo sprintf('<div class="section-background">%s%s</div>', $background, $overlay); 
    }
    ?>   
    <div class="container">
        <div class="row">
            <?php echo wpb_js_remove_wpautop($content); ?>
        </div>
    </div>
</section>
<?php } ?>