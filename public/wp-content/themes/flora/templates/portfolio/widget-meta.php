<?php

    global $wyde_options;
    
    $project_url = get_post_meta(get_the_ID(), '_w_project_url', true );
    
    $tags = get_the_terms( get_the_ID(), 'portfolio_tag' );

?>
<div class="portfolio-meta-widget widget">
    <?php if( is_array($tags) && count($tags) > 0 ): ?>
    <p class="portfolio-tags">
        <?php $tag_links = array(); ?>
        <?php 
        foreach ( $tags as $item ){
            $tag_links[] = sprintf('<a href="%s">%s</a>', esc_url( get_term_link($item) ), esc_html( $item->name ));
        } 
        echo '<i class="flora-icon-bookmark"></i><span>'.implode(', ', $tag_links).'</span>';
        ?>  
    </p>
    <?php endif; ?>  
    <?php if( !empty( $project_url )): ?>
    <p><i class="flora-icon-link"></i> <a href="<?php echo esc_url( $project_url );?>" title="Visit Site" class="launch-project"><?php echo __('Visit Site', 'flora');?></a></p>
    <?php endif; ?>
    <?php if( !empty( $wyde_options['portfolio_date'] )): ?>
    <p><i class="flora-icon-calendar"></i><?php echo __('Published', 'flora').': '. get_the_date();?></p>
    <?php endif; ?>
</div>