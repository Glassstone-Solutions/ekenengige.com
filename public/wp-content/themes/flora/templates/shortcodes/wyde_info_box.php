<?php
        
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $classes = array();

    $classes[] = 'w-info-box';

    $classes[] = 'w-'.$icon_size;       
    if( !empty($icon_position) ) $classes[] = 'w-'.$icon_position;        
    if( !empty($icon_style) ) $classes[] = 'w-'.$icon_style;        

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }

    if( !empty($css) ){
        $classes[] = vc_shortcode_custom_css_class( $css, '' );    
    }

	$attrs['class'] = implode(' ', $classes);

    if($animation) $attrs['data-animation'] = $animation;
    if($animation_delay) $attrs['data-animation-delay'] = floatval( $animation_delay );

    if( !empty($icon_set) ){
        $icon = isset( ${"icon_" . $icon_set} )? ${"icon_" . $icon_set} : '';
    } 

    $icon_attrs = array();
    if( !empty($icon) ){
        $icon_attrs['class'] = 'w-icon';
        if( !empty( $color ) ){
            if( $icon_style == 'circle' ){
                $icon_attrs['style'] = 'border-color:'.$color.';background-color:'. $color;
            }else{
                $icon_attrs['style'] = 'color:'. $color;
            }
        }
    }

    $border_attrs = array();
    $border_attrs['class'] = 'w-border';
    if( !empty($icon_attrs['style']) ) $border_attrs['style'] = $icon_attrs['style'];

    
    $link = ( $link == '||' ) ? '' : $link;

?>
<div<?php echo wyde_get_attributes( $attrs );?>>
    <div class="w-header">
        <?php if( !empty( $icon ) ):?>
        <span<?php echo wyde_get_attributes( $icon_attrs );?>>
            <i class="<?php echo esc_attr( $icon );?>"></i>
            <span<?php echo wyde_get_attributes( $border_attrs );?>></span>
        </span>
        <?php endif;?>
        <?php if( !empty( $title ) ):?>
        <h3><?php echo esc_html( $title );?></h3>
        <?php endif;?>
    </div>
    <?php if( !empty( $content ) ):?>
    <div class="w-content">
        <?php echo wpb_js_remove_wpautop($content, true); ?>
        <?php if( !empty( $link ) ):?>
        <?php echo do_shortcode( sprintf('[wyde_link_button title="%s" link="%s" size="small" icon="flora-icon-right-small"]', __('Read More', 'flora'), esc_attr($link)) ); ?>
        <?php endif;?>
    </div>
    <?php endif;?>
</div>


