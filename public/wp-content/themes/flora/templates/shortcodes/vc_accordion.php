<?php

    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $classes = array();

    $classes[] = 'w-accordion';

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }

    $attrs['class'] = implode(' ', $classes);

    if( $collapsible == 'yes' ) $attrs['data-collapsible'] = $collapsible;
    $attrs['data-active'] = $active_tab;

    if( !empty($color) ) {
        $attrs['style'] = 'border-color:'.$color;
        $attrs['data-color'] =$color;
    }   

?>
<div<?php echo wyde_get_attributes( $attrs );?>>
    <?php echo wpb_js_remove_wpautop($content);?>
</div>