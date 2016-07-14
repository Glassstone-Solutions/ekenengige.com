<?php

    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $attrs['class'] = 'w-tab';
?>
<div<?php echo wyde_get_attributes( $attrs );?>>
    <div class="w-tab-content">
    <?php echo wpb_js_remove_wpautop($content);?> 
    </div>
</div>