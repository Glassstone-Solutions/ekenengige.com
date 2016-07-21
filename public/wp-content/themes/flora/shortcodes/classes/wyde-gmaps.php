<?php
/* Google Maps
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_GMaps extends WPBakeryShortCode {
}

vc_map( array(
            'name' => __('Google Maps', 'flora'),
            'description' => __('Google Maps block.', 'flora'),
            'base' => 'wyde_gmaps',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon gmaps-icon', 
            'weight'    => 900,
            'category' => 'Flora',
	        'params' => array(
                array(
			        'type' => 'wyde_gmaps',
			        'heading' => 'Address',
			        'param_name' => 'gmaps',
		        ),
		        array(
			        'type' => 'textfield',
			        'heading' => __( 'Map Height', 'flora' ),
			        'param_name' => 'height',
			        'admin_label' => true,
                    'value' => '300',
			        'description' => __( 'Enter map height in pixels. Example: 300.', 'flora' )
		        ),
                array(
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => __('Map Color', 'flora'),
                        'param_name' => 'color',
                        'value' => '',
                        'description' => __('Select map background color. If empty "Theme Color Scheme" will be used.', 'flora')
                ),
		        array(
			        'type' => 'attach_image',
			        'heading' => __( 'Icon', 'flora' ),
			        'param_name' => 'icon',
			        'description' => __( 'To custom your own marker icon, upload or select images from media library.', 'flora' )
		        ),
		        array(
			        'type' => 'textfield',
			        'heading' => __( 'Extra CSS Class', 'flora' ),
			        'param_name' => 'el_class',
			        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		        ),
	        )
));