<?php get_header(); ?>
<div id="content">
    <?php   

    global $wyde_page_id, $wyde_options, $wyde_page_layout, $wyde_sidebar_position, $wyde_blog_layout, $wyde_blog_excerpt;
    
    wyde_page_title();
  
    $wyde_page_layout = '';
    $wyde_sidebar_position = '';

    // There is an assigned posts page
    if( !empty($wyde_page_id) ){
        $wyde_page_layout = get_post_meta( $wyde_page_id, '_w_layout', true );
    }
    
    if( empty($wyde_page_layout) ){
        $wyde_page_layout = $wyde_options['page_layout'];
    }

    if( $wyde_page_layout == 'boxed' ){
        // There is an assigned posts page
        if( !empty($wyde_page_id) ){
            $wyde_sidebar_position = get_post_meta( $wyde_page_id, '_w_sidebar_position', true );
        }

        if( empty($wyde_sidebar_position) ){
            $wyde_sidebar_position = $wyde_options['blog_sidebar'];  
        } 
        
    }else{
        $wyde_sidebar_position = '3';
    }

    $wyde_blog_layout = $wyde_options['blog_layout'];

    $wyde_blog_excerpt = $wyde_options['blog_excerpt'];

    $grid_columns = intval( $wyde_options['blog_grid_columns'] );

    $pagination = $wyde_options['blog_pagination'];

    ?>
    <div class="<?php echo wyde_get_layout_class(); ?>">    
        <?php wyde_page_background(); ?>     
        <div class="post-content container">  
            <?php 
            if($wyde_sidebar_position == '2' ){
                wyde_sidebar();
            }
            ?>
            <div class="<?php echo wyde_get_main_class(); ?>">      
                <?php

                $attrs = array();

                $classes = array();

                $classes[] = 'w-blog-posts';

                $masonry_layout = array();
                $layout_count = 0;
                $col_name = '';
                $view = $wyde_blog_layout;
                switch( $view ){
                    case 'masonry':
                    case 'w-masonry':                        
                        $classes[] = 'w-masonry';
                        if($view == 'w-masonry'){
                            $classes[] = 'w-layout-flora';
                        }else{
                            $classes[] = 'w-layout-basic';
                        }
                        $masonry_layout = wyde_get_blog_masonry_layout( $masonry_layout, $view );
                        $layout_count = count($masonry_layout);
                        $wyde_blog_layout = 'masonry';
                        break;
                    case 'grid':
                        $wyde_blog_layout = 'grid';
                        $classes[] = 'w-'.$view;
                        $classes[] = 'grid-'. $grid_columns .'-cols';
                        $col_name = 'col-'.  absint( floor(12/ $grid_columns ) );
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

                if( is_front_page() || is_home() ) {
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
                } else {
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                }

                $count = get_option('posts_per_page');                

                $item_index = (intval($paged) -1 ) * intval( $count );

            ?>      
            <div<?php echo wyde_get_attributes($attrs);?>>
                <ul class="w-view clear">
                <?php 
                while ( have_posts() ) : 

                    the_post();
                    
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
                    wyde_pagination($pagination, $wp_query->max_num_pages); 
                }
                ?>
            </div>

            </div>
            <?php 
            if($wyde_sidebar_position == '3'){
                wyde_sidebar(); 
            }
            ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>