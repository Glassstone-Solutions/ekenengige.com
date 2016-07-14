<?php

    global $wyde_blog_layout, $wyde_blog_excerpt, $wyde_grid_columns, $wyde_masonry_classes, $paged;

    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );

    extract( $atts );
            
    $attrs = array();

    $classes = array();

    $classes[] = 'w-blog-posts';

    $wyde_blog_excerpt = 1;

    $masonry_layout = array();
    $layout_count = 0;
    $col_name = '';
    switch( $view ){
        case 'masonry':
        case 'w-masonry':
            $wyde_blog_layout = 'masonry';
            $classes[] = 'w-masonry';
            if($view == 'w-masonry'){
                $classes[] = 'w-layout-flora';
            }else{
                $classes[] = 'w-layout-basic';
            }
            $masonry_layout = $this->get_masonry_layout($view);
            $layout_count = count($masonry_layout);
            break;
        case 'grid':
            $wyde_blog_layout = 'grid';
            $wyde_grid_columns = intval( $columns );
            $classes[] = 'w-'.$view;
            $classes[] = 'grid-'. $wyde_grid_columns .'-cols';
            $col_name = 'col-'.  absint( floor(12/ $wyde_grid_columns ) );
            break;            
        default:
            $wyde_blog_layout = '';
            $classes[] = 'w-large';
            break;
    }

    if($pagination == '2'){
        $classes[] = 'w-scrollmore';
    }

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }
        
    $attrs['class'] = implode(' ', $classes);

    if($animation) $attrs['data-animation'] = $animation;
    if($animation_delay) $attrs['data-animation-delay'] = floatval( $animation_delay );

    if( is_front_page() || is_home() ) {
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
	} else {
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	}

    $query_args = array();

    list( $query_args, $loop ) = vc_build_loop_query( $posts_query );

    $query_args['paged'] = intval( $paged );
    $query_args['has_password'] = false;
    $query_args['posts_per_page'] = intval( $count );

    $post_query = new WP_Query( $query_args );
      
    $item_index = (intval($paged) -1 ) * intval( $count );

?>      
<div<?php echo wyde_get_attributes($attrs);?>>
    <ul class="w-view clear">
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
        }else{
            $item_classes[] = 'w-item';
            if( !empty($col_name) ) $item_classes[] = $col_name;
        } 
        $item_index++;                    
    ?>
        <li class="<?php echo esc_attr( implode(' ', $item_classes) ); ?>">
        <?php wyde_post_content( $view ); ?>    
        </li>
    <?php 
    endwhile; 
    wp_reset_postdata(); 
    ?>
    </ul>
    <?php 
    if( $pagination != 'hide' ){
        wyde_pagination($pagination, $post_query->max_num_pages); 
    }
    ?>
</div>