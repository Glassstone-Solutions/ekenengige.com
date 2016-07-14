<?php
    
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );
    
    $attrs = array();
    
    $classes = array();
    
    $classes[] = 'w-team-slider';

    $classes[] = 'grid-'.$visible_items.'-cols';
    
    if( !empty($el_class) ){
        $classes[] = $el_class;
    }
    
    $attrs['class'] = implode(' ', $classes);

    
    if($animation) $attrs['data-animation'] = $animation;
    if($animation_delay) $attrs['data-animation-delay'] = floatval( $animation_delay );
    
    $slider_attrs = array();
    
    $slider_attrs['class'] = 'owl-carousel';
    
    $slider_attrs['data-items'] = intval( $visible_items );
    $slider_attrs['data-auto-play'] = ($auto_play =='true' ? 'true':'false');
    $slider_attrs['data-navigation'] = ($show_navigation =='true' ? 'true':'false');
    $slider_attrs['data-pagination'] = ($show_pagination =='true' ? 'true':'false');
    $slider_attrs['data-loop'] = ($loop =='true' ? 'true':'false');
    if( $visible_items == '1' && !empty($transition) ) $slider_attrs['data-transition'] = $transition;

    if ( $count != '' && ! is_numeric( $count ) ) $count = - 1;
    
    list( $query_args, $wp_loop ) = vc_build_loop_query( $posts_query );

    $query_args['post_type'] = 'wyde_team_member';
    $query_args['has_password'] = false;
    $query_args['posts_per_page'] = intval( $count );       
    
    $post_query = new WP_Query( $query_args );
    
    $image_size = 'large';
    
?>
<div <?php echo wyde_get_attributes( $attrs );?>>
    <div <?php echo wyde_get_attributes( $slider_attrs );?>>
        <?php while ( $post_query->have_posts() ) : $post_query->the_post(); ?>
        <div class="team-member">
            <?php
            $image = get_post_meta( get_the_ID(), '_w_member_image', true);
            if($image){
                $image_id = get_post_meta( get_the_ID(), '_w_member_image_id', true);
                $image_attr = wp_get_attachment_image_src($image_id, $image_size);
                if($image_attr[0]) $image = $image_attr[0];
            } 
            if($image){
                echo sprintf('<div class="cover-image" style="background-image:url(%s);"></div>', esc_url( $image ));
            }
            $name = get_the_title();
            $position = get_post_meta( get_the_ID(), '_w_member_position', true );
            ?>
            <?php if( $hide_member_name != 'true'): ?>
            <div class="member-name"<?php echo !empty($color)? ' style="color:'.$color.';"':''; ?>>
                <h4><?php echo esc_html( $name ); ?></h4>
                <p><?php echo esc_html( $position ); ?></p>
            </div>
            <?php endif; ?>
            <div class="member-content">
                <div class="member-detail">
                    <h4><?php echo esc_html( $name ); ?></h4>
                    <p class="member-meta"><?php echo esc_html( $position ); ?></p>
                    <div class="member-desc"><p><?php echo wp_kses_post( get_post_meta( get_the_ID(), '_w_member_detail', true ) ); ?></p></div>
                    <p class="social-link">
                    <?php
                        
                    $email =  get_post_meta( get_the_ID(), '_w_member_email', true );
                    $website =  get_post_meta( get_the_ID(), '_w_member_website', true );

                    if($email){
                        echo '<a href="mailto:'.sanitize_email( $email ).'" title="'.__('Email', 'flora').'" target="_blank" class="tooltip-item"><i class="flora-icon-mail"></i></a>';
                    }

                    if($website){
                        echo '<a href="'.esc_url( $website ).'" title="'.__('Website', 'flora').'" target="_blank" class="tooltip-item"><i class="flora-icon-global"></i></a>';
                    }

                    $socials_icons = wyde_get_social_icons();
                    $socials = get_post_meta( get_the_ID(), '_w_member_socials', true );

                    foreach ( (array) $socials as $key => $entry ) {
                        if ( isset( $entry['url'] ) && !empty( $entry['url'] ) ){
                            echo sprintf('<a href="%s" title="%s" target="_blank" class="tooltip-item"><i class="%s"></i></a>', esc_url( $entry['url'] ), esc_attr( $entry['social'] ), esc_attr( array_search($entry['social'], $socials_icons) ));
                        }
                    }
                    ?>
                    </p>
                </div>
            </div>
        </div>
        <?php endwhile;?>
        <?php wp_reset_postdata(); ?>
    </div>
</div>