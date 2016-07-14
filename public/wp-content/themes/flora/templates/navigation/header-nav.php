<?php
    
    global $wyde_options, $wyde_page_id, $wyde_page_header, $wyde_header_style, $wyde_header_overlay, $wyde_header_full;
    
    $attrs = array();
    
    $attrs['id'] = 'header';
    
    $classes = array();    
    
    $wyde_header_style = isset( $wyde_options['header_style'] ) ? $wyde_options['header_style'] : 'light';

    $classes[] = 'w-'.$wyde_header_style;
    
    $header_sticky = isset( $wyde_options["header_sticky"] ) ? $wyde_options["header_sticky"] : true;
    if( $header_sticky ){
        $classes[] = 'w-sticky';
    }

    $wyde_header_overlay = 0;    
    $wyde_header_full = 0;    

    $header_fullwidth =  $wyde_options["header_fullwidth"];    
    if( $header_fullwidth ){
        $classes[] = 'w-full';
        $wyde_header_full = 1;
    }

    $header_transparent = get_post_meta( $wyde_page_id, '_w_header_transparent', true );    
    if( $header_transparent === 'true' ){

        $classes[] = 'w-transparent';
        $wyde_header_overlay = 1;

    }

    $text_style = get_post_meta( $wyde_page_id, '_w_header_text_style', true );
    if( empty( $text_style ) ){
        if( $wyde_header_style == 'dark' ){
            $text_style = 'light';
        }else{
            $text_style = 'dark';
        }
    }
    $classes[] = 'w-text-'.$text_style;

    $nav_layout = isset( $wyde_options['nav_layout'] ) ? $wyde_options['nav_layout'] : 'classic';
    
    $attrs['class'] = implode(' ', $classes);
    
?>
<header <?php echo wyde_get_attributes( $attrs );?>>
    <div class="container">
        <div class="header-wrapper">
            <span class="mobile-nav-icon">
                <i class="menu-icon"></i>
            </span>
             <?php
            
                $logo = $wyde_options['header_logo']['url'];
                $sticky =  $wyde_options['header_logo_sticky']['url'] ? $wyde_options['header_logo_sticky']['url'] : $wyde_options['header_logo']['url'];
            
                $dark_logo = $wyde_options['header_logo_dark']['url'];
                $dark_sticky =  $wyde_options['header_logo_dark_sticky']['url'] ? $wyde_options['header_logo_dark_sticky']['url'] : $wyde_options['header_logo_dark']['url'];
                if( !empty($logo) ):
            ?>
            <span id="header-logo">  
                <a href="<?php echo esc_url( home_url() ); ?>">
                    <img class="dark-logo" src="<?php echo esc_url( $logo ); ?>" alt="<?php echo __('Logo', 'flora'); ?>" />
                    <img class="dark-sticky" src="<?php echo esc_url( $sticky ); ?>" alt="<?php echo __('Sticky Logo', 'flora'); ?>" />
                    <img class="light-logo" src="<?php echo esc_url( $dark_logo ); ?>" alt="<?php echo __('Logo', 'flora'); ?>" />
                    <img class="light-sticky" src="<?php echo esc_url( $dark_sticky ); ?>" alt="<?php echo __('Sticky Logo', 'flora'); ?>" />
                </a>
            </span>
            <?php endif; ?>
            <nav id="top-nav" class="dropdown-nav">
                <ul class="top-menu">
                    <?php if( $nav_layout == 'classic' || $nav_layout == 'expand' ):?>
                    <?php wyde_primary_menu(); ?>
                    <?php endif;?>
                    <?php if( $wyde_options['menu_shop_cart'] && function_exists('wyde_woocommerce_dropdown_menu')): ?>
                    <?php echo wyde_woocommerce_dropdown_menu(); ?>
                    <?php endif;?>
                    <?php if( $wyde_options['menu_search_icon']): ?>
                    <li class="menu-item-search">
                        <a class="live-search-button" href="#"><i class="flora-icon-search"></i></a>
                    </li>
                    <?php endif;?>
                    <?php if( $nav_layout != 'left' && $wyde_options['slidingbar']): ?>
                    <li class="menu-item-slidingbar">
                        <a href="#"><i class="flora-icon-plus"></i></a>
                    </li>
                    <?php endif;?>
                </ul>
            </nav>
        </div>
        <?php if( $nav_layout == 'expand' || $nav_layout == 'fullscreen' ):?>
        <div class="full-nav-button">
            <span class="full-nav-icon">
                <i class="menu-icon"></i>
            </span>
        </div>
        <?php endif; ?>
    </div>
</header>