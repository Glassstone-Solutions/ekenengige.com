<?php
    global $wyde_options, $wyde_page_id;

    $attrs = array();
    $attrs['id'] = 'side-nav';

    $menu_text_style = $wyde_options['side_nav_text_style'];
    if( !empty($menu_text_style) ){
        $attrs['class'] = 'w-text-'.$menu_text_style;
    }
?>
<aside<?php echo wyde_get_attributes( $attrs );?>>
    <?php if( !empty($wyde_options['side_nav_overlay_color']) ): ?>
    <?php 
    $opacity = '';
    if( !empty($wyde_options['side_nav_overlay_opacity']) ){
        $opacity = 'opacity:'.$wyde_options['side_nav_overlay_opacity'].';';
    }
    ?>
    <div class="bg-overlay" style="background-color: <?php echo esc_attr( $wyde_options['side_nav_overlay_color'] ); ?>;<?php echo esc_attr( $opacity ); ?>"></div>
    <?php endif; ?>
    <div class="side-nav-wrapper">
        <span id="side-nav-logo">
            <?php
            if( !empty( $wyde_options['side_logo']['url'] )):
                
                $logo = $wyde_options['side_logo']['url'];
                $logo_retina =  $wyde_options['side_logo_retina']['url'];
                if( !empty($logo_retina) ) $logo_retina = ' data-retina="'. esc_url( $logo_retina ) .'"';

            ?>
            <a href="<?php echo esc_url( site_url() ); ?>">
                <?php echo sprintf('<img src="%s"%s alt="%s" />', esc_url( $logo ), $logo_retina, __('Logo', 'flora') ); ?>                
            </a>
            <?php
            endif;
            ?>
        </span>    
        <nav id="vertical-nav">
            <ul class="vertical-menu">
            <?php wyde_vertical_menu(); ?>
            </ul>
        </nav>
        <ul id="side-menu">
            <?php if( $wyde_options['menu_shop_cart'] && function_exists('wyde_woocommerce_menu')){ ?>
            <?php echo wyde_woocommerce_menu();   ?>
            <?php } ?>
            <?php if($wyde_options['menu_search_icon']){ ?>
            <li class="menu-item-search">
                <a class="live-search-button" href="#"><i class="flora-icon-search"></i><?php echo __('Search', 'flora');?></a>
            </li>
            <?php } ?>
        </ul>
        <?php 
        if( $wyde_options['menu_contact'] ){
            wyde_contact_info();
        }
        ?>
        <?php 
        if( $wyde_options['menu_social_icon'] ){
            wyde_social_icons();
        }
        ?>
    </div>
</aside>