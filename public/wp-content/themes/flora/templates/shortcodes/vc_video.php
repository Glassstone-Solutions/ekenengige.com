<?php

    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );


    if ( $media_url == '' ) {
	    return;
    }

    $video_width = ( isset( $content_width ) ) ? $content_width : 1170;
    $video_height = $video_width / 1.61;

    $embed_code = wp_oembed_get($media_url, array(
        'width'     => $video_width,
        'height'    => $video_height
    ));

?>
<div class="video-wrapper">
    <?php echo wpb_js_remove_wpautop( $embed_code ); ?>
</div>