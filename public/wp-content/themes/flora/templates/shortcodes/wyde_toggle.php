<?php

    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $classes = array();

    $classes[] = 'w-toggle';

    if($open == 'true') $classes[] = 'active';

    $attrs['class'] = implode(' ', $classes);

    if( !empty($color) ) {
        $attrs['style'] = 'border-color:'.$color;
        $attrs['data-color'] = $color;
    }   

    if($animation) $attrs['data-animation'] = $animation;
    if($animation_delay) $attrs['data-animation-delay'] = floatval( $animation_delay );

?>
<div<?php echo wyde_get_attributes( $attrs );?>>
<h4><?php echo esc_html( $title );?></h4>
<div><?php echo wpb_js_remove_wpautop($content, true);?></div>
</div>