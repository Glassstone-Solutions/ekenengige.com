<?php
    
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $classes = array();

    $classes[] = 'w-revslider';

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }

    $attrs['class'] = implode(' ', $classes);

    $button_attrs = array();
    if( !empty($button_style) ){
        $button_attrs['class'] = 'w-scroll-button w-button-'. $button_style;
    }
    if( !empty($color) ){
        $button_attrs['style'] = 'border-color:'.$color.';color:'.$color;
    }

?>
<div<?php echo wyde_get_attributes( $attrs );?>>
    <?php echo apply_filters( 'vc_revslider_shortcode', do_shortcode( '[rev_slider ' . esc_attr( $alias ). ']' ) );?>
    <?php if( !empty($button_style) ): ?>
    <div<?php echo wyde_get_attributes( $button_attrs );?>>
        <a href="#scroll">
		    <i></i>
        </a>
    </div>
    <?php endif; ?>
</div>