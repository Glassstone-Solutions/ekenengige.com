<?php
    
    global $wyde_grid_columns, $wyde_masonry_classes;

    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $classes = array();

    $classes[] = 'w-portfolio-grid';

    $masonry_layout = array();
    $layout_count = 0;
    $col_name = '';
    switch($view){
        case 'grid':
        case 'grid-space':
            $wyde_grid_columns = intval( $columns );
            $classes[] = 'w-'. $view;
            $classes[] = 'grid-'. $wyde_grid_columns .'-cols';
            $col_name = 'col-'.  absint( floor(12/ $wyde_grid_columns ) );
        break;
        case 'masonry':
        case 'w-masonry':
            $classes[] = 'w-masonry';
            if($view == 'w-masonry'){
                $classes[] = 'w-layout-flora';
            }else{
                $wyde_grid_columns = intval( $columns );
                $classes[] = 'w-layout-basic';
                $classes[] = 'grid-'. $wyde_grid_columns .'-cols';
                $col_name = 'col-'.  absint( floor(12/ $wyde_grid_columns ) );
            }
            $masonry_layout = $this->get_masonry_layout($view);
            $layout_count = count($masonry_layout);
        break;
    }

    $classes[] = 'w-scrollmore';

    if($hide_filter != 'true'){
        $classes[] = 'w-filterable';
    } 
        
    if( !empty($el_class) ){
        $classes[] = $el_class;
    }

	$attrs['class'] = implode(' ', $classes);

    if($pagination == '2'){
        $attrs['data-trigger'] = "0";
    }

    if($animation) $attrs['data-animation'] = $animation;
    if($animation_delay) $attrs['data-animation-delay'] = floatval( $animation_delay );
        
    global $paged, $post;
        
    if( is_front_page() || is_home() ) {
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
	} else {
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	}
           
    if ( $count != '' && ! is_numeric( $count ) ) $count = 12;

    list( $query_args, $loop ) = vc_build_loop_query( $posts_query );

    $query_args['post_type'] = 'wyde_portfolio';
    $query_args['paged'] = intval( $paged );
    $query_args['has_password'] = false;
    $query_args['posts_per_page'] = intval( $count );

    $post_query = new WP_Query( $query_args );

    $item_index = (intval($paged)-1 ) * intval( $count );
                      
    $hover_effect_class = '';
    if( $view != 'masonry' && !empty($hover_effect) ) $hover_effect_class = ' w-effect-'. $hover_effect;

?>
<div<?php echo wyde_get_attributes( $attrs );?>>
    <?php if( $hide_filter != 'true' ): ?>
    <ul class="w-filter clear">
        <li class="active"><a href="#all" title=""><?php echo __('All', 'flora'); ?></a></li>
        <?php   
        $terms = get_terms('portfolio_category');

        if ( is_array($terms) )
        {   
            foreach ( $terms as $term ) {
                $term_link = urldecode($term->slug);
                echo sprintf('<li><a href="#c-%s" title="">%s</a></li>', esc_attr( $term_link ), esc_html( $term->name ));
            }
        }
        ?>
    </ul>
    <?php endif; ?>
    <ul class="w-view<?php echo esc_attr($hover_effect_class); ?> clear">
    <?php
    while ( $post_query->have_posts() ) : 
            
        $post_query->the_post();

        $item_classes = array();   
        if( $view == 'masonry' || $view == 'w-masonry' ){
            $key = ($item_index % $layout_count);
            if( !empty($masonry_layout[$key]) ){
                $item_masonry_class = $masonry_layout[$key];
                $item_classes[] = $item_masonry_class;
                $wyde_masonry_classes = explode(' ', $item_masonry_class);
            } 
            if( $view == 'masonry'){
                $item_classes[] = $col_name;
            }
        }else{
            $item_classes[] = 'w-item';
            $item_classes[] = $col_name;
        }
   
        $cate_names = array();   
      
        $categories = get_the_terms( $post->ID, 'portfolio_category' );
            
        if (is_array( $categories )) { 
            foreach ( $categories as $item ) 
            {
                $item_classes[] = urldecode('c-'.$item->slug);
                $cate_names[] = $item->name;
            }
        }
        $item_index++;                    
        ?>
        <li class="<?php echo esc_attr( implode(' ', $item_classes) ); ?>">
            <?php wyde_portfolio_content($view); ?>
        </li>
    <?php
    endwhile;
    wp_reset_postdata();
    ?>            
    </ul>
    <?php 
    if($pagination != 'hide'){ 
        wyde_infinitescroll($post_query->max_num_pages);
    } 
    ?>
</div>