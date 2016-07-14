<?php

    $styles = array();
    extract( $this->getAttributes( $atts ) );
    extract( $this->getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) );

    extract( shortcode_atts( array(
        'letter_spacing' =>  '',
        'text_transform' =>  '',
        'animation' =>  '',
        'animation_delay' =>  0,
    ), $atts ) );

    $settings = get_option( 'wpb_js_google_fonts_subsets' );
    $subsets = '';
    if ( is_array( $settings ) && !empty( $settings ) ) {
	    $subsets = '&subset=' . implode( ',', $settings );
    }

    if ( isset( $google_fonts_data['values']['font_family'] ) ) {
        /*Fix HTML Validation issue */
        //wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
        echo '<link href="'. esc_url('//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets) .'" rel="stylesheet" property="stylesheet" type="text/css" media="all" />'; //id="rev-google-font"
    }

    $attrs = array();

    $classes = array();
    
    $classes[] = 'w-custom-heading';
    
    if( !empty($el_class) ){
        $classes[] = $el_class;
    }

    if( !empty($css) ){
        $classes[] = vc_shortcode_custom_css_class( $css, '' );    
    }

    $attrs['class'] = implode(' ', $classes);

    if($animation) $attrs['data-animation'] = $animation;
    if($animation_delay) $attrs['data-animation-delay'] = floatval( $animation_delay );


    if( !empty($letter_spacing) ){
        $styles[] = 'letter-spacing:'.$letter_spacing;
    }

    if( !empty($text_transform) ){
        $styles[] = 'text-transform:'.$text_transform;
    }

    $font_style = '';
    if ( !empty( $styles ) ) {
	    $font_style = ' style="'. implode( ';', $styles ) .'"';
    }

    $output_html = $text;
    if ( !empty( $link ) ) {
	    $link = vc_build_link( $link );
	    $output_html = '<a href="' . esc_attr( $link['url'] ) . '"'
	                   . ( $link['target'] ? ' target="' . esc_attr( $link['target'] ) . '"' : '' )
	                   . ( $link['title'] ? ' title="' . esc_attr( $link['title'] ) . '"' : '' )
	                   . '>' . $text . '</a>';
    }

?>
<div<?php echo wyde_get_attributes( $attrs );?>>
    <?php echo sprintf('<%s%s>', esc_attr( $font_container_data['values']['tag'] ), $font_style ); ?>
    <?php echo wpb_js_remove_wpautop( $output_html );?>
    <?php echo sprintf('</%s>', esc_attr( $font_container_data['values']['tag'] ) ); ?>
</div>