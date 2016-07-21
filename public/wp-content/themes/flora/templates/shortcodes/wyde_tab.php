<?php
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $attrs['class'] = 'w-tab';
?>
<div<?php echo wyde_get_attributes( $attrs );?>>
    <?php if( !empty($title) ):?>
    <h4><?php echo esc_html($title); ?></h4>
    <?php endif; ?>
    <div class="w-tab-content">
    <?php echo wpb_js_remove_wpautop($content);?> 
    </div>
</div>