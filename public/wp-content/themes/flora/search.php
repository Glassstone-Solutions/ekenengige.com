<?php get_header(); ?>
<div id="content">
    <?php   

    global $wyde_options, $wp_query;
    
    $wyde_sidebar_position = $wyde_options['search_sidebar'];
    if( empty($wyde_sidebar_position) ){
        $wyde_sidebar_position = '1';
    }

    wyde_page_title();

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
                <?php get_search_form(); ?>    
                <?php
                if ( have_posts() ) {
                ?>
                <p class="search-query">
                    <?php echo sprintf( __('About %s results', 'flora'), number_format_i18n( $wp_query->found_posts ) ); ?>      
                </p>               
                <div class="search-results clear">
                    <?php while( have_posts() ): the_post(); ?>
                    <div id="post-<?php the_ID(); ?>" class="search-item clear">
                        <div class="item-header clear">
                            <?php if( $wyde_options['search_show_image'] !== '0' ){ ?>
                            <?php if( has_post_thumbnail() || get_post_type() =='post' ) {?>
                            <span class="thumb">
                                <a href="<?php echo esc_url( get_permalink() );?>" target="_blank">
                                <?php echo wyde_get_post_thumbnail(get_the_ID(), 'thumbnail');?>
                                </a>
                            </span>
                            <?php }else{ ?>
                            <span class="type-icon">
                                <a href="<?php echo esc_url( get_permalink() );?>" target="_blank">
                                <?php echo wyde_get_type_icon();?>
                                    </a>
                            </span>
                            <?php } ?>
                            <?php } ?>
                            <h4>
                                <a href="<?php echo esc_url( get_permalink() );?>"><?php the_title(); ?></a>
                            </h4>
                            <?php wyde_search_meta();?>
                        </div>
                        <?php if( get_post_type() != 'page' ){ ?>
                        <div class="post-summary">
                            <?php echo wyde_get_excerpt(); ?>
                        </div>
                        <?php } ?>
                    </div>       
                    <?php endwhile; ?>
                </div>
                <?php 
                wyde_pagination();
                }else{ 
                ?>
                <p>
                <?php echo __('No result found.', 'flora');?>
                </p>
                <?php } ?>
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