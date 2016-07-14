<?php get_header(); ?>
<div id="content">
    <?php   
    global $wyde_options;

    $view = $wyde_options['portfolio_archive_layout'];
    $columns = $wyde_options['portfolio_archive_grid_columns'];
    $pagination = $wyde_options['portfolio_archive_pagination'];

    wyde_page_title();

    $term = get_queried_object();
    $term_id = '';
    if($term) $term_id = $term->term_id;

    ?>
    <div class="<?php echo wyde_get_layout_class(); ?>"> 
        <?php wyde_page_background(); ?>
        <div class="post-content container"> 
        <?php 
        $cate_desc = term_description();
        ?>
        <?php if( $cate_desc ): ?>
        <div class="post-detail">
		<?php echo wp_kses_post( $cate_desc ); ?>    
        </div>
        <?php endif; ?>
        <?php echo do_shortcode( sprintf('[wyde_portfolio_grid view="%s" columns="%s" pagination="%s" hide_filter="true" posts_query="tax_query:%s"]', esc_attr($view), esc_attr($columns), esc_attr($pagination), esc_attr($term_id) ) ); ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>