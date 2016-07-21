<?php
    global $wyde_options, $wyde_page_id, $wyde_title_area, $wyde_header_full;
    
    if( $wyde_title_area == 1 ):

        $attrs = array();
        $classes = array();
        $title_color = '';
        $title_bg_color = '';
        $title_bg_video = '';

        $title_overlay_color = '';
        $title_overlay_opacity = '';
        $title_scroll_effect = '';

        $classes[] = 'title-wrapper';

        $title_align = get_post_meta( $wyde_page_id, '_w_title_align', true );
        if( empty($title_align) ) $title_align =  $wyde_options['title_align'];
        if( $title_align != 'none' ) $classes[] = 'text-'. $title_align;
        
        $title_size = get_post_meta( $wyde_page_id, '_w_title_size', true );
        if( empty($title_size) ) $title_size =  $wyde_options['title_size'];
        $classes[] = 'w-size-'. $title_size;
    
        $title_color = get_post_meta( $wyde_page_id, '_w_title_color', true );
        if( empty($title_color) ) $title_color = $wyde_options['title_color'];

        $title_bg = get_post_meta( $wyde_page_id, '_w_title_background', true );
        $title_bg_image_size = '';
        if($title_bg != ''){ //title background
            
            if($title_bg !== 'none'){

                if($title_bg === 'image'){
                    $title_bg_image =   get_post_meta( $wyde_page_id, '_w_title_background_image', true ); 
                    $title_bg_image_size = 'w-size-'.get_post_meta( $wyde_page_id, '_w_title_background_size', true );

                    if(get_post_meta( $wyde_page_id, '_w_title_background_parallax', true ) == 'on') $classes[] = 'w-parallax';

                }elseif($title_bg === 'video'){
                    $title_bg_video =   get_post_meta( $wyde_page_id, '_w_title_background_video', true ); 
                }

                $title_bg_color = get_post_meta( $wyde_page_id, '_w_title_background_color', true );
    
                $title_overlay_color = get_post_meta( $wyde_page_id, '_w_title_overlay_color', true ); 
                $title_overlay_opacity = get_post_meta( $wyde_page_id, '_w_title_overlay_opacity', true ); 

            }

        }else{ // Use default theme options
                
            $title_bg = $wyde_options['title_background_mode'];

            if($title_bg !== 'none'){
    
                if($title_bg == 'image'){
        
                    $title_bg_image = $wyde_options['title_background_image']['background-image']; 
                    $title_bg_image_size = 'w-size-'.$wyde_options['title_background_image']['background-size'];

                    if($wyde_options['title_background_parallax'] == true) $classes[] = 'w-parallax';
                    $title_scroll_effect = $wyde_options['title_scroll_effect'];

                }else if($title_bg == 'video'){
                    $title_bg_video =   $wyde_options['title_background_video']['url']; 
                }

                $title_bg_color = $wyde_options['title_background_color'];
                
                $title_overlay_color = $wyde_options['title_overlay_color']; 
                $title_overlay_opacity = $wyde_options['title_overlay_opacity']; 
            }

        }

        $attrs['class'] = implode(' ', $classes);

        $styles = array();

        if( !empty($title_color) ){
            $styles[] = 'color:'. $title_color;
        }

        if( !empty($title_bg_color) ){
            $styles[] = 'background-color:'. $title_bg_color;
        }

        $attrs['style'] = implode(';', $styles);


        $title_scroll_effect = get_post_meta( $wyde_page_id, '_w_title_scroll_effect', true );
        if( $title_scroll_effect == '') $title_scroll_effect = $wyde_options['title_scroll_effect'];
        
        if( $title_scroll_effect !== 'none' ) $attrs['data-effect'] = $title_scroll_effect;

        $container_class = $wyde_header_full ? ' w-full' : '';
?>
<div<?php echo wyde_get_attributes( $attrs );?>>
    <?php if( !empty($title_bg_image) || !empty($title_bg_video) ): ?>
    <div class="title-background">
    <?php if( !empty($title_bg_image) ){ ?>
    <div class="bg-image <?php echo esc_attr($title_bg_image_size); ?>" style="background-image: url('<?php echo esc_url( $title_bg_image );?>');"></div>
    <?php } ?>
    <?php if( !empty($title_bg_video) ){ ?>
    <div class="bg-video">
        <video class="vdobg" autoplay loop muted>
            <source src="<?php echo esc_url( $title_bg_video ); ?>" type="video/mp4" />
        </video>
    </div>
    <?php } ?>
    <?php if( ( !empty($title_bg_image) || !empty($title_bg_video) ) && !empty($title_overlay_color) ) { ?>
    <?php 
    $opacity = '';
    if( !empty($title_overlay_opacity) ){
        $opacity = 'opacity:'.$title_overlay_opacity.';';
    }
    ?>
    <div class="bg-overlay" style="background-color: <?php echo esc_attr( $title_overlay_color ); ?>;<?php echo esc_attr( $opacity ); ?>"></div>
    <?php } ?>
    </div>
    <?php endif; ?>
    <div class="container<?php echo esc_attr( $container_class ); ?>">
        <?php 

        $title = get_post_meta( $wyde_page_id, '_w_page_title', true );
        
        $sub_title = get_post_meta( $wyde_page_id, '_w_subtitle', true );

        if( empty($title) ){

            if( is_woocommerce() ) {

			    if( !is_product() ) {
				    $title = esc_html( woocommerce_page_title( false ) );
			    }

		    }

        }
        
        if( is_search() ) {
			$title = __('Search', 'flora');
            $sub_title = __('Search Results for', 'flora') .' : <strong>'. get_search_query().'</strong>';
		}

        if( is_404() ) {
			$title = __('Error 404', 'flora');
            $sub_title = __('Page not found', 'flora');
		}

        if( is_archive() && !is_woocommerce() ) {
			    
            if ( is_day() ) {
				$title = __( 'Daily Archives', 'flora' );
                $sub_title = get_the_date();
			} else if ( is_month() ) {
				$title = __( 'Monthly Archives', 'flora' );
                $sub_title = get_the_date('F Y');
			} elseif ( is_year() ) {
				$title = __( 'Yearly Archives', 'flora' );
                $sub_title = get_the_date('Y');
			} elseif ( is_author() ) {
				$author = ( isset( $_GET['author_name'] ) ) ? get_user_by( 'slug', $_GET['author_name'] ) : get_user_by(  'id', get_the_author_meta('ID') );
				$title = $author->display_name;
			} else {
				$title = single_cat_title( '', false );
                $sub_title = __('Currently browsing:', 'flora') .' <strong>'. single_cat_title( '', false ) .'</strong>';
			}

		}

        if( empty($title) ){
            $title = get_the_title();
        } 

        ?>
        <h1 class="title">
            <?php echo wp_kses_data( $title );?>
        </h1>
        <?php
        if( !empty($sub_title) ){
        ?>
        <h6 class="subtitle"><?php echo wp_kses_data( $sub_title );?></h6>
        <?php
        }
        ?>
    </div>
</div>
<?php endif; ?>