<?php
/* Flickr
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Flickr extends WPBakeryShortCode {
}

vc_map( array(
	'name' => __( 'Flickr Stream', 'flora' ),
    'base' => 'wyde_flickr',
	'icon' => 'wyde-icon flickr-icon',
    'weight'    => 900,
	'category' => 'Flora',
	'description' => __( 'Image feed from Flickr account', 'flora' ),
	"params" => array(
        array(
			'type' => 'textfield',
			'heading' => __( 'Title', 'flora' ),
			'param_name' => 'title',
			'admin_label' => true,
            'description' => __('Enter title text.', 'flora')
		),
        array(
			'type' => 'dropdown',
			'heading' => __( 'Type', 'flora' ),
			'param_name' => 'type',
            'admin_label' => true,
			'value' => array(
				__( 'User', 'flora' ) => 'user',
				__( 'Group', 'flora' ) => 'group'
			),
			'description' => __( 'Select photo stream type.', 'flora' )
		),
        array(
			'type' => 'textfield',
			'heading' => __( 'Flickr ID', 'flora' ),
			'param_name' => 'flickr_id',
			'admin_label' => true,
			'description' => sprintf( __( 'To find your flickID visit %s.', 'flora' ), '<a href="http://idgettr.com/" target="_blank">idGettr</a>' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Number of photos', 'flora' ),
			'param_name' => 'count',
			'value' => array( 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20 ),
			'description' => __( 'Select number of photos to display.', 'flora' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Columns', 'flora' ),
			'param_name' => 'columns',
			'value' => array( 2, 3, 4, 5, 6, 12 ),
            'admin_label' => true,
			'description' => __( 'Select number of grid columns to display.', 'flora' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra CSS Class', 'flora' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		),
	)
) );