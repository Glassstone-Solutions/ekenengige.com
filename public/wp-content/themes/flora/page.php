<?php get_header(); ?>
<div id="content">
<?php 

global $wyde_page_id, $wyde_options, $wyde_sidebar_position, $wyde_page_layout, $wyde_page_sidebar;

if( $wyde_options['onepage'] && is_front_page() ){
    $wyde_page_layout = 'wide';
    get_template_part('page', 'onepage');
}else{
      
    if( have_posts() ): 

    the_post();

    wyde_page_title();   

    $wyde_page_layout = get_post_meta( $wyde_page_id, '_w_layout', true );
    if( empty($wyde_page_layout) ){
        $wyde_page_layout = $wyde_options['page_layout'];
        if( $wyde_page_layout == 'wide' ){
            if( is_home() || is_woocommerce() ){
                $wyde_page_layout = 'boxed';
            }
        }
    }

    if( $wyde_page_layout == 'boxed' ){
        $wyde_sidebar_position = get_post_meta( $wyde_page_id, '_w_sidebar_position', true );    
    }else{
        $wyde_sidebar_position = '1';
    }

    if( !$wyde_sidebar_position ) {
        $wyde_sidebar_position = '1';
    }

    ?>
    <div class="<?php echo wyde_get_layout_class(); ?>">    
        <?php wyde_page_background(); ?>
        <?php
        if ( $wyde_page_layout == 'wide' ) {
           the_content();
        }else{
        ?>
        <div class="post-content container">
            <?php 
            if($wyde_sidebar_position == '2' ){
                wyde_sidebar();
            }
            ?>
            <div class="<?php echo wyde_get_main_class(); ?>">  
                <div class="page-detail clear"> 
                <?php the_content(); ?>
                <?php wp_link_pages(array( 'before' => '<div class="page-links">', 'after' => '</div>', 'link_before' => '<span>', 'link_after'  => '</span>' )); ?>
                </div> 
                <?php 
                if ( $wyde_options['page_comments'] && !is_front_page() && ( comments_open() || get_comments_number() ) && !is_woocommerce() ) {
				    comments_template();
			    }
                ?>
            </div>
            <?php 
            if($wyde_sidebar_position == '3'){
                wyde_sidebar(); 
            }
            ?>
        </div>
        <?php } ?>
    </div>
    <?php 
    endif;
    }
    ?>
</div>
<?php get_footer(); ?>