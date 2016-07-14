<?php
    global $wyde_options;
    
    $footer_columns = intval( $wyde_options['footer_widget_columns'] );
    
    $bg_overlay_color = $wyde_options['footer_widget_overlay_color'];
    $bg_overlay_opacity = $wyde_options['footer_widget_overlay_opacity'];

    $attrs = array();
    $classes = array();

    $attrs['id'] = 'footer-widget';
    if( $wyde_options['footer_widget_align_middle'] ) $classes[] = 'w-v-align w-middle';

    $attrs['class'] = implode(' ', $classes);

?>
<aside<?php echo wyde_get_attributes( $attrs );?>>
    <?php if( !empty($bg_overlay_color) ) :
        $opacity = '';
        if( !empty($bg_overlay_opacity) ){
            $opacity = 'opacity:'.$bg_overlay_opacity.';';
        }
    ?>
    <div class="bg-overlay" style="background-color: <?php echo esc_attr( $bg_overlay_color ); ?>;<?php echo esc_attr( $opacity ); ?>"></div>
    <?php endif; ?>
    <div class="container">
        <div class="row">
            <?php for($i = 0; $i < $footer_columns; $i++){ ?>
            <div class="col col-<?php echo absint( floor( 12 / $footer_columns ) );?>">
                <?php dynamic_sidebar('footer'.($i+1)); ?>
            </div>
            <?php } ?>
        </div>
    </div>
</aside>