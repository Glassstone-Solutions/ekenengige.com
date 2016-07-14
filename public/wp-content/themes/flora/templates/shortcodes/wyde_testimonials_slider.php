<?php
    
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $classes = array();

    $classes[] = 'w-testimonials-slider';

    if($show_navigation == 'true') $classes[] = 'show-navigation';    

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }

	$attrs['class'] = implode(' ', $classes);

    if($animation) $attrs['data-animation'] = $animation;
    if($animation_delay) $attrs['data-animation-delay'] = floatval( $animation_delay );

    $slider_attrs = array();

    $slider_attrs['class'] = 'owl-carousel';
    $slider_attrs['data-auto-play'] = ($auto_play =='true' ? 'true':'false');
    $slider_attrs['data-navigation'] = ($show_navigation =='true' ? 'true':'false');
    $slider_attrs['data-pagination'] = ($show_pagination =='true' ? 'true':'false');
    $slider_attrs['data-loop'] = 'true';
    if( !empty($transition) ) $slider_attrs['data-transition'] = $transition;

    if ( $count != '' && !is_numeric( $count ) ) $count = - 1;
        
    list( $query_args, $wp_loop ) = vc_build_loop_query( $posts_query );

    $query_args['post_type'] = 'wyde_testimonial';
    $query_args['has_password'] = false;
    $query_args['posts_per_page'] = intval( $count );       

    $post_query = new WP_Query( $query_args );

?>
<div<?php echo wyde_get_attributes( $attrs );?>>
    <div<?php echo wyde_get_attributes( $slider_attrs ); ?>>
        <?php while ( $post_query->have_posts() ) : $post_query->the_post(); ?>
        <div class="w-testimonial">
            <div class="w-customer">
                <?php
                $image = get_post_meta( get_the_ID(), '_w_testimonial_image', true);
                if($image){
                    $image_id = get_post_meta( get_the_ID(), '_w_testimonial_image_id', true);
                    $thumb = wp_get_attachment_image_src($image_id, array(150, 150));
                    if($thumb[0]) $image = $thumb[0];
                } 
                if($image){
                ?>
                <div class="w-border"><img src="<?php echo esc_url( $image );?>" alt="<?php echo esc_attr( get_the_title() ); ?>" /></div>
                <?php } ?>
                <h6><?php echo esc_html( get_the_title() ); ?></h6>
                <p>
                <?php
                $position =  get_post_meta( get_the_ID(), '_w_testimonial_position', true );
                $company =  get_post_meta( get_the_ID(), '_w_testimonial_company', true );
                $website =  get_post_meta( get_the_ID(), '_w_testimonial_website', true );
                if( !empty($position) ){  
                ?> 
                    <span><?php echo esc_html( $position );?></span>&ndash; 
                <?php 
                }             
                if( !empty($company) ){
                    if( !empty($website) ){
                        echo '<a href="'.esc_url( $website ).'" target="_blank">';
                    } 
                    echo esc_html( $company );
                    if( !empty($website) ){
                        echo '</a>';
                    } 
                }
                ?>
                </p>
            </div>
            <div class="w-content">
                <?php echo  wp_kses_post( get_post_meta( get_the_ID(), '_w_testimonial_detail', true ) ); ?>
            </div>
        </div>
        <?php endwhile; ?>    
        <?php wp_reset_postdata(); ?>
    </div>
</div>