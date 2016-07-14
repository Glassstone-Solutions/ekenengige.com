<?php
/* Image Gallery
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Image_Gallery extends WPBakeryShortCode {
    public function get_masonry_layout( $layout = '1' ){
        $masonry_layout = array();
        switch( $layout ){
            case '1':
            $masonry_layout = array('w-item', 'w-item w-h2', 'w-item', 'w-item w-h2', 'w-item w-h2', 'w-item w-h2', 'w-item', 'w-item');
            break;
            case '2':
            $masonry_layout = array('w-item w-h2', 'w-item', 'w-item w-h2', 'w-item', 'w-item', 'w-item w-h2', 'w-item w-h2', 'w-item w-h2', 'w-item w-h2', 'w-item', 'w-item');
            break;
            default:
            $masonry_layout = array('w-item w-h2', 'w-item', 'w-item w-w2 w-h2', 'w-item', 'w-item w-w2 w-h2', 'w-item', 'w-item w-h2', 'w-item', 'w-item w-h2', 'w-item w-w2 w-h2', 'w-item w-h2', 'w-item w-w2', 'w-item w-w2');
            break;
        }

        return apply_filters( 'gallery_masonry_layout_'.$layout, $masonry_layout );
    }
}

vc_map( array(
            'name' => __('Image Gallery', 'flora'),
            'description' => __('Create beautiful responsive image gallery.', 'flora'),
            'base' => 'wyde_image_gallery',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon image-gallery-icon',
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
				        __('Thumbnail (150x150)', 'flora' ) => 'thumbnail',
				        __('Medium (300x300)', 'flora' ) => 'medium',
				        __('Large (640x640)', 'flora' ) => 'large',
				        __('Extra Large (960x960)', 'flora' ) => 'x-large',
                        __('Full Width (1280x720)', 'flora' ) => 'full-width',
                        __('Original', 'flora' ) => 'full',
			        ),
			        'description' => __( 'Select image size.', 'flora' )
		        ),
		        array(
			        'type' => 'dropdown',
			        'heading' => __( 'Gallery Type', 'flora' ),
			        'param_name' => 'gallery_type',
			        'value' => array(
                        __('Grid (Without Space)', 'flora' ) => 'grid', 
                        __('Grid (With Space)', 'flora' ) => 'grid-space',
				        __('Masonry', 'flora' ) => 'masonry',
				        __('Slider', 'flora' ) => 'slider',
			        ),
			        'description' => __( 'Select image size.', 'flora' )
		        ),
                array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Columns', 'flora'),
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of grid columns.', 'flora'),
                    'dependency' => array(
		                'element' => 'gallery_type',
		                'value' => array('grid', 'grid-space')
		            )
                ),
		        array(
			        'type' => 'dropdown',
			        'heading' => __( 'Masonry Layout', 'flora' ),
			        'param_name' => 'layout',
			        'value' => array(
                        __('Flora', 'flora' ) => '', 
                        __('Basic 1', 'flora' ) => '1',
				        __('Basic 2', 'flora' ) => '2',
			        ),
			        'description' => __( 'Select masonry layout.', 'flora' ),
                    'dependency' => array(
		                'element' => 'gallery_type',
		                'value' => array('masonry')
		            )
		        ),
                array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Hover Effect', 'flora'),
                    'param_name' => 'hover_effect',
                    'admin_label' => true,
                    'value' => array(
                        __('None', 'flora' ) => '', 
                        __('Zoom In', 'flora' ) => 'zoomIn', 
                        __('Zoom Out', 'flora' ) => 'zoomOut',
                        __('Rotate Zoom In', 'flora' ) => 'rotateZoomIn',
                    ),
                    'description' => __('Select the hover effect for image.', 'flora'),
                    'dependency' => array(
		                'element' => 'gallery_type',
		                'value' => array('grid', 'grid-space', 'masonry')
		            )
                ),
                array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Transition', 'flora'),
                    'param_name' => 'transition',
                    'value' => array(
                        __('Slide', 'flora') => '', 
                        __('Fade', 'flora') => 'fade', 
                    ),
                    'description' => __('The maximum amount of items displayed at a time.', 'flora'),
                    'dependency' => array(
	                    'element' => 'gallery_type',
	                    'value' => array('slider')
                    )
                ),
                array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Visible Items', 'flora'),
                    'param_name' => 'visible_items',
                    'value' => array('auto', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'),
                    'std' => '3',
                    'description' => __('The maximum amount of items displayed at a time.', 'flora'),
                    'dependency' => array(
	                    'element' => 'gallery_type',
	                    'value' => array('slider')
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'class' => '',
                    'param_name' => 'show_navigation',
                    'value' => array(
                            __('Show Navigation', 'flora') => 'true'
                    ),
                    'description' => __('Display "next" and "prev" buttons.', 'flora'),
                    'dependency' => array(
	                    'element' => 'gallery_type',
	                    'value' => array('slider')
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'class' => '',
                    'param_name' => 'show_pagination',
                    'value' => array(
                            __('Show Pagination', 'flora') => 'true'
                    ),
                    'description' => __('Show pagination.', 'flora'),
                    'dependency' => array(
	                    'element' => 'gallery_type',
	                    'value' => array('slider')
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'class' => '',
                    'heading' => __('Auto Play', 'flora'),
                    'param_name' => 'auto_play',
                    'value' => array(
                            __('Auto Play', 'flora') => 'true'
                    ),
                    'description' => __('Auto play slide.', 'flora'),
                    'dependency' => array(
	                    'element' => 'gallery_type',
	                    'value' => array('slider')
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'class' => '',
                    'param_name' => 'loop',
                    'value' => array(
                            __('Loop', 'flora') => 'true'
                    ),
                    'description' => __('Inifnity loop. Duplicate last and first items to get loop illusion.', 'flora'),
                    'dependency' => array(
	                    'element' => 'gallery_type',
	                    'value' => array('slider')
                    )
                ),
		        array(
			        'type' => 'textfield',
			        'heading' => __( 'Extra CSS Class', 'flora' ),
			        'param_name' => 'el_class',
			        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		        ),
            )
) );