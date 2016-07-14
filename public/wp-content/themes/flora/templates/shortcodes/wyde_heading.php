<?php

    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $classes = array();

    $classes[] = 'w-heading';

    if( !empty($style) ){
        $classes[] = 'title-'.$style;
    }

    if( !empty($text_align) ){
        $classes[] = 'text-'. $text_align;
    }

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }
        
	$attrs['class'] = implode(' ', $classes);

    if($animation) $attrs['data-animation'] = $animation;
    if($animation_delay) $attrs['data-animation-delay'] = floatval( $animation_delay );

?>
<div<?php echo wyde_get_attributes( $attrs );?>>
    <?php if(!empty($title)) : ?> 
    <h2><?php echo esc_html( $title ); ?></h2>
    <?php endif; ?>
    <?php if(!empty($sub_title)) : ?> 
    <span class="sub-title"><?php echo wpb_js_remove_wpautop($sub_title, true);?></span>
    <?php endif; ?>
</div>