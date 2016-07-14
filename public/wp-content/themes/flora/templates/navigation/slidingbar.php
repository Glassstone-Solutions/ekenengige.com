<?php
    global $wyde_options, $wyde_header_style;
?>
<?php if( $wyde_options['slidingbar'] ):?>
<div id="slidingbar" class="w-<?php echo esc_attr($wyde_header_style); ?>">
    <a href="#" class="sliding-remove-button"><i class="flora-icon-cancel"></i></a>
    <div class="slidingbar-wrapper">
        <div class="sliding-widgets">
            <?php dynamic_sidebar('slidingbar'); ?>
        </div>
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
</div>
<?php endif; ?>