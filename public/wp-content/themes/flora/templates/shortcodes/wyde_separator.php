<?php

    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $classes = array();

    $classes[] = 'w-separator';

    if( empty($label_style) ){
        $classes[] = 'no-text';
    }

    $styles = array();

    if( !empty($style) ){
        if( $style == 'double' ) $classes[] = 'w-style-double';
        $styles[] = 'border-style:'. $style;
    }

    if( !empty($color) ){
        $styles[] = 'color:'. $color;
        $styles[] = 'border-color:'. $color;
    }

    if( !empty($el_width) ){
        $styles[] = 'width:'. $el_width;
    }

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }
    
    if( !empty($css) ){
        $classes[] = vc_shortcode_custom_css_class( $css, '' );    
    }

    $attrs['class'] = implode(' ', $classes);

    $attrs['style'] = implode(';', $styles);

    if( $label_style == 'icon' && !empty($icon_set) ){
        $icon = isset( ${"icon_" . $icon_set} )? ${"icon_" . $icon_set} : '';
    } 

?>
<div<?php echo wyde_get_attributes( $attrs );?>>
    <span class="w-text">
        <?php if( $label_style == 'icon' && !empty($icon)){?>
        <i class="<?php echo esc_attr($icon); ?>"></i>
        <?php }else{ ?>
        <?php echo esc_html($title); ?>
        <?php } ?>
    </span>
</div>