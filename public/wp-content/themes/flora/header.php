<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title><?php wp_title(''); ?></title>
        <?php 
        global $wyde_options, $wyde_page_id, $wyde_page_header, $wyde_title_area; 
        ?>
        <?php if( !empty($wyde_options['favicon_image']['url']) ): ?>
        <link rel="icon" href="<?php echo esc_url( $wyde_options['favicon_image']['url'] ); ?>" type="image/png" />
	    <?php endif; ?>
        <?php if( !empty($wyde_options['favicon']['url']) ): ?>
	    <link rel="shortcut icon" href="<?php echo esc_url( $wyde_options['favicon']['url'] ); ?>" type="image/x-icon" />
	    <?php endif; ?>
	    <?php if( !empty($wyde_options['favicon_iphone']['url']) ): ?>
	    <link rel="apple-touch-icon-precomposed" href="<?php echo esc_url( $wyde_options['favicon_iphone']['url'] ); ?>">
	    <?php endif; ?>
	    <?php if( !empty($wyde_options['favicon_iphone_retina']['url']) ): ?>
	    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo esc_url( $wyde_options['favicon_iphone_retina']['url'] ); ?>">
	    <?php endif; ?>
	    <?php if( !empty($wyde_options['favicon_ipad']['url']) ): ?>
	    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo esc_url( $wyde_options['favicon_ipad']['url'] ); ?>">
	    <?php endif; ?>
	    <?php if( !empty($wyde_options['favicon_ipad_retina']['url']) ): ?>
	    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo esc_url( $wyde_options['favicon_ipad_retina']['url'] ); ?>">
	    <?php endif; ?>
        <?php
            
        $wyde_page_id = get_the_ID();

        if( is_home() ){
            
            $blog_page_id = get_option('page_for_posts');
            if( $blog_page_id ){
                $wyde_page_id = $blog_page_id;
            }else{
                $wyde_page_id = '';
            }

        }elseif( class_exists('WooCommerce') && ( is_shop() || is_product_category() ) ){
            $wyde_page_id = get_option('woocommerce_shop_page_id');
        } 

        if( get_post_type(get_the_ID()) == 'wyde_portfolio' && is_archive() ){
            $wyde_page_id = '';
        }

        $classes = array();

        if($wyde_options['onepage']) $classes[] = 'onepage';

        $wyde_page_header = 1; 
        if( get_post_meta( $wyde_page_id, '_w_page_header', true ) == 'hide' ){
            $wyde_page_header = 0;
            $classes[] = 'no-header';
        }
        
        $classes[] = $wyde_options['nav_layout'] .'-nav';           

        $wyde_title_area = 0;
        if( get_post_meta( $wyde_page_id, '_w_title_area', true ) != 'show' ){
            $wyde_title_area = 0;
        }else{
            $wyde_title_area = 1;
        }

        if( is_single() ){
            if( get_post_meta( $wyde_page_id, '_w_post_custom_title', true ) != 'on' ){
                $wyde_title_area = 0;
            }
        }

        if( is_archive() ){
            $wyde_title_area = 1;
        }

        if( $wyde_title_area == 0 ){
            $classes[] = 'no-title';
        }
        ?>
        <?php wp_head(); ?>
    </head>
    <body <?php body_class( esc_attr( implode(' ', $classes) ) ); ?>>
         <div id="preloader">
            <?php wyde_page_loader($wyde_options['page_loader']); ?>
        </div>
        <?php 
        get_template_part( 'templates/navigation/side-nav' );
        get_template_part( 'templates/navigation/header-nav' );    
        get_template_part( 'templates/navigation/fullscreen-nav' );    
        get_template_part( 'templates/navigation/slidingbar' );    
        ?>
        <div id="page-overlay"></div>
        <?php
        get_template_part( 'templates/navigation/live-search' );