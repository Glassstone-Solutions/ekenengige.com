<?php    
    global $wyde_options, $wyde_page_id;

    if( get_post_meta( $wyde_page_id, '_w_page_footer', true ) != 'hide' && ( $wyde_options['footer_widget'] || $wyde_options['footer_bottom'] ) ):

    $classes = array();

    $classes[] = 'footer-v'. intval( $wyde_options['footer_layout'] );

    if( $wyde_options['footer_sticky'] ) $classes[] = 'w-sticky';
    if( $wyde_options['footer_fullwidth'] ) $classes[] = 'w-full';

?>
    <footer id="footer" class="<?php echo esc_attr( implode(' ', $classes) ); ?>">
        <div class="footer-wrapper">
            <?php
            get_template_part( 'templates/footers/footer', 'v'. intval( $wyde_options['footer_layout'] ) );
            ?> 
        </div>
	</footer>
    <?php endif; ?>
    <?php if($wyde_options["totop_button"]): ?>
    <a id="toplink-button" href="#">
        <span class="border">
            <i class="flora-icon-up-1"></i>
        </span>
    </a>
    <?php endif; ?>
    <?php wyde_footer_content(); ?>
<?php wp_footer(); ?>
</body>
</html>