<?php
    global $wyde_options, $wyde_header_style;
?>
<?php if( $wyde_options['nav_layout'] == 'fullscreen' ): ?>
<div id="fullscreen-nav" class="w-<?php echo esc_attr($wyde_header_style); ?>">
    <div class="container">
        <div class="full-nav-wrapper">
            <nav id="full-nav">
                <ul class="vertical-menu">
                    <?php wyde_fullscreen_menu(); ?>
                </ul>
            </nav>
        </div>
        <?php 
        if( $wyde_options['menu_social_icon'] ){
            wyde_social_icons();
        }
        ?>        
    </div>
</div>
<?php endif; ?>