<?php get_header(); ?>
<div id="content">
    <?php
    if( have_posts() ) : 
    
    the_post();

    global $wyde_options, $wyde_page_layout, $wyde_sidebar_position, $wyde_portfolio_layout;

    $portfolio_layout_id = get_post_meta( get_the_ID(), '_w_portfolio_layout', true );
        
    $wyde_portfolio_layout = '';

    $wyde_sidebar_position = '3';

    switch( intval( $portfolio_layout_id ) ){
        case 2:
            $wyde_portfolio_layout = 'gallery';
            $wyde_page_layout = 'wide';
            $wyde_sidebar_position = '1';
            break;
        case 3:
            $wyde_portfolio_layout = 'slider';
            break;
        case 4:
            $wyde_portfolio_layout = 'grid';
            break;
    }

    wyde_page_title();

    $classes = array();

    $classes[] = wyde_get_layout_class();

    if( !empty($wyde_portfolio_layout) ) $classes[] = 'portfolio-'. $wyde_portfolio_layout;
    else $classes[] = 'portfolio-default';

    ?>
    <div class="<?php echo implode(' ', $classes); ?>">
        <?php wyde_page_background(); ?>
        <?php
        if(post_password_required(get_the_ID())){
            the_content();
        }else{  
            get_template_part('templates/portfolio/single', $wyde_portfolio_layout);
        } 
        ?>
    </div>
    <?php endif; ?>
</div>
<?php get_footer(); ?>