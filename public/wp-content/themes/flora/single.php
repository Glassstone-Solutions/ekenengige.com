<?php get_header(); ?>
<div id="content">
    <?php
    if( have_posts() ) :
    
    the_post();

    global $wyde_options, $wyde_sidebar_position;

    if( get_post_meta( get_the_ID(), '_w_post_custom_sidebar', true ) == 'on'){
        $wyde_sidebar_position = get_post_meta( get_the_ID(), '_w_sidebar_position', true );
    }

    if( !$wyde_sidebar_position ) $wyde_sidebar_position = $wyde_options['blog_single_sidebar'];

    wyde_page_title();

    $classes = array();

    $classes[] = wyde_get_layout_class();

    $post_format = get_post_format();

    if( !empty($post_format) ) $classes[] = 'format-'. $post_format;

    ?>
    <div class="<?php echo implode(' ', $classes);?>">    
        <?php wyde_page_background(); ?>
        <div class="post-content container">  
            <?php 
            if($wyde_sidebar_position == '2' ){
                wyde_sidebar();
            }
            ?>
            <div class="<?php echo wyde_get_main_class(); ?>">       
            <?php
            if(post_password_required(get_the_ID())){
                the_content();
            }else{  
                get_template_part('templates/blog/single'); 
            }
            ?>
            </div>
            <?php 
            if($wyde_sidebar_position == '3'){
                wyde_sidebar(); 
            }
            ?>
        </div>
    </div>
    <?php 
    endif; 
    ?>
</div>
<?php get_footer(); ?>