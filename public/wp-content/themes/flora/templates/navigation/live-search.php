<?php
    global $wyde_header_style;
?>
<div id="live-search" class="w-<?php echo esc_attr($wyde_header_style); ?>">
    <div class="container">
        <form id="live-search-form" class="live-search-form clear" action="<?php echo esc_url( get_site_url() );?>" method="get">
            <input type="text" name="s" id="keyword" value="<?php the_search_query(); ?>" placeholder="<?php echo __('Start Typing...', 'flora'); ?>" />
            <a href="#" class="fullscreen-remove-button"><i class="flora-icon-cancel"></i></a>
        </form>
    </div>
</div>