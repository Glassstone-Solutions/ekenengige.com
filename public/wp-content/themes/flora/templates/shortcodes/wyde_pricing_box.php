<?php

    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $classes = array();

    $classes[] = 'w-pricing-box';

    if($featured == 'true') $classes[] = 'w-featured';

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }

    $attrs['class'] = implode(' ', $classes);

    $styles = array();

    if( !empty($color) ){
        $attrs['style'] = 'color:'.$color.';border-color:'.$color;
    } 


    if($animation) $attrs['data-animation'] = $animation;
    if($animation_delay) $attrs['data-animation-delay'] = floatval( $animation_delay );

    if( !empty($icon_set) ){
        $icon = isset( ${"icon_" . $icon_set} )? ${"icon_" . $icon_set} : '';
    } 

?>
<div<?php echo wyde_get_attributes( $attrs );?>>
    <?php if( !empty($heading) ): ?>      
    <div class="box-header<?php echo empty( $icon )?' no-icon':'';?> clear">
        <?php if( !empty( $icon ) ):?>
        <span><i class="<?php echo esc_attr( $icon );?>"></i></span>
        <?php endif; ?>
        <div class="w-header">
            <h3><?php echo esc_html( $heading );?></h3>
            <h4><?php echo esc_html( $sub_heading );?></h4>
        </div>
    </div>
    <?php endif; ?>
    <?php if( !empty($price) ): ?> 
    <div class="box-price">
        <h4><?php echo esc_html( $price ); ?></h4>
        <span><?php echo esc_html( $price_unit ); ?></span>
    </div>
    <?php endif; ?>
    <div class="box-content">
    <?php if( !empty($content) ): ?>
    <?php echo wpb_js_remove_wpautop($content, true); ?>
    <?php endif;?>
    </div>
    <div class="box-button">
    <?php if( !empty($button_text) ): ?> 
    <?php
        //$link = ( $link == '||' ) ? '' : $link;
        /*$link = vc_build_link( $link );

        $link_attrs = array();
        $link_attrs['href'] = empty($link['url'])? '#':$link['url']; 

        if( !empty($link['title']) ){
           $link_attrs['title'] = $link['title']; 
        } 
        if( !empty($link['target']) ){
            $link_attrs['target'] = trim($link['target']);
        } 

        $button_bg = '';
        if( !empty($button_color) ){
            $link_attrs['style'] = 'color:'.$button_color.';';
            $button_bg = '<span style="background-color:'.$button_color.';"></span>';
        } */
        echo do_shortcode( sprintf('[wyde_link_button link="%s" title="%s" color="%s" size="large"]', $link, $button_text, $button_color));
    ?>
    <?php endif;?>
    </div>
</div>