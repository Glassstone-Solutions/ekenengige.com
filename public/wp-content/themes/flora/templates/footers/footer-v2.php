<?php
    
global $wyde_options;

if( $wyde_options['footer_widget'] ){
    get_sidebar('footer');
}

if( $wyde_options['footer_bottom'] ):
?>
<div id="footer-bottom">
    <div class="container">
        <div class="col-6">
            <?php if( $wyde_options['footer_logo'] ): ?>
            <?php
                $footer_logo = $wyde_options['footer_logo_image']['url'];
                $footer_logo_retina =  $wyde_options['footer_logo_retina']['url'];
                if( !empty($footer_logo_retina) ) $footer_logo_retina = ' data-retina="'.esc_url( $footer_logo_retina ).'"';
            ?>
            <div id="footer-logo">
                <a href="<?php echo esc_url( site_url() ); ?>">
                    <?php echo sprintf('<img src="%s"%s alt="Footer Logo" />', esc_url( $footer_logo ), $footer_logo_retina ); ?>
                </a>
            </div>
            <?php endif; ?>
            <?php if( $wyde_options['footer_text'] ): ?>
            <div id="footer-text">
            <?php echo wp_kses_post($wyde_options['footer_text_content']); ?>
            </div>
            <?php endif; ?>
        </div>            
        <div class="col-6">
            <div id="footer-nav">
                <?php if( $wyde_options['footer_menu'] ): ?>
                <ul class="footer-menu">
                    <?php wyde_menu('footer', 1); ?>
                </ul>
                <?php endif; ?>
                <?php if( $wyde_options['footer_social'] ): ?>
                <?php wyde_social_icons(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if($wyde_options["totop_button"]): ?>
    <div id="toplink-wrapper">
        <a href="#"><i class="flora-icon-up-1"></i></a>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>