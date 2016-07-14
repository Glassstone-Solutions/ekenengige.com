<?php
/* Clients Carousel
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Clients_Carousel extends WPBakeryShortCode {
}

vc_map( array(
            'name' => __('Clients Carousel', 'flora'),
            'description' => __('Create beautiful responsive carousel slider.', 'flora'),
            'base' => 'wyde_clients_carousel',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon clients-carousel-icon', 
            'weight'    => 900,
            'category' => 'Flora',
            'params' => array(
                array(
                    'type' => 'attach_images',
                    'class' => '',
                    'heading' => __('Images', 'flora'),
                    'param_name' => 'images',
                    'value' => '',
                    'description' => __('Upload or select images from media library.', 'flora')
                ),
		        array(
			        'type' => 'dropdown',
			        'heading' => __( 'Image Size', 'flora' ),
			        'param_name' => 'image_size',
			        'value' => array(
				        __('Thumbnail (150x150)', 'flora') => 'thumbnail',
				        __('Medium (300x300)', 'flora') => 'medium',
				        __('Large (640x640)', 'flora') => 'large',
				        __('Extra Large (960x960)', 'flora') => 'x-large',
                        __('Full Width (1280x720)', 'flora') => 'full-width',
                        __('Original', 'flora') => 'full',
			        ),
			        'description' => __( 'Select image size.', 'flora' )
		        ),
                array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Visible Items', 'flora'),
                    'param_name' => 'visible_items',
                    'value' => array('auto', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'),
                    'std' => '3',
                    'description' => __('The maximum amount of items displayed at a time.', 'flora')
                ),
                array(
                    'type' => 'checkbox',
                    'class' => '',
                    'param_name' => 'show_navigation',
                    'value' => array(
                            __('Show Navigation', 'flora') => 'true'
                    ),
                    'description' => __('Display "next" and "prev" buttons.', 'flora')
                ),
                array(
                    'type' => 'checkbox',
                    'class' => '',
                    'param_name' => 'show_pagination',
                    'value' => array(
                            __('Show Pagination', 'flora') => 'true'
                    ),
                    'description' => __('Show pagination.', 'flora')
                ),
                array(
                    'type' => 'checkbox',
                    'class' => '',
                    'heading' => __('Auto Play', 'flora'),
                    'param_name' => 'auto_play',
                    'value' => array(
                            __('Auto Play', 'flora') => 'true'
                    ),
                    'description' => __('Auto play slide.', 'flora')
                ),
                array(
                    'type' => 'checkbox',
                    'class' => '',
                    'param_name' => 'loop',
                    'value' => array(
                            __('Loop', 'flora') => 'true'
                    ),
                    'description' => __('Inifnity loop. Duplicate last and first items to get loop illusion.', 'flora')
                ),
                array(
                    'type' => 'wyde_animation',
                    'class' => '',
                    'heading' => __('Animation', 'flora'),
                    'param_name' => 'animation',
                    'description' => __('Select a CSS3 Animation that applies to this element.', 'flora')
                ),
                array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Animation Delay', 'flora'),
                    'param_name' => 'animation_delay',
                    'value' => '',
                    'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'flora'),
                    'dependency' => array(
	                    'element' => 'animation',
	                    'not_empty' => true,
                    ),
                ),
		        array(
			        'type' => 'textfield',
			        'heading' => __( 'Extra CSS Class', 'flora' ),
			        'param_name' => 'el_class',
			        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		        ),
            ),
) );