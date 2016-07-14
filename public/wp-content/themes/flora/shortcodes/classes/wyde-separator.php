<?php

/* Separator (Divider)
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Separator extends WPBakeryShortCode {
}

$icon_picker_options = Wyde_Shortcode::get_iconpicker_options();
$icon_picker_options[0]['dependency'] = array(
		                    'element' => 'label_style',
		                    'value' => array('icon')
		                );

vc_map( array(
	    'name' => __( 'Separator', 'flora' ),
	    'base' => 'wyde_separator',
	    'icon' => 'wyde-icon separator-icon',
	    'show_settings_on_create' => true,
        'weight'    => 900,
	    'category' => 'Flora',
	    'description' => __( 'Horizontal separator line', 'flora' ),
	    'params' => array(
            array(
                'type' => 'dropdown',
                'class' => '',
                'heading' => __('Label Style', 'flora'),
                'param_name' => 'label_style',
                'value' => array(
                    __('None', 'flora') => '', 
                    __('Text', 'flora') => 'text', 
                    __('Icon', 'flora') => 'icon', 
                ),
                'description' => __('Select a label style.', 'flora')
            ),
            array(
                'type' => 'textfield',
                'class' => '',
                'heading' => __('Title', 'flora'),
                'param_name' => 'title',
                'value' => '',
                'description' => __('Input a title text separator.', 'flora'),
                'dependency' => array(
		            'element' => 'label_style',
		            'value' => array('text'),
		        )
            ),
            $icon_picker_options[0],
            $icon_picker_options[1],
            $icon_picker_options[2],
            $icon_picker_options[3],
            $icon_picker_options[4],
            $icon_picker_options[5],
            array(
			    'type' => 'dropdown',
			    'heading' => __( 'Style', 'flora' ),
			    'param_name' => 'style',
			    'value' => array(
            	    __('Solid', 'flora' ) => '',
		            __('Dashed', 'flora' ) => 'dashed',
		            __('Dotted', 'flora' ) => 'dotted',
		            __('Double', 'flora' ) => 'double',
	            ),
			    'description' => __( 'Separator style', 'flora' )
		    ),
		    array(
			    'type' => 'dropdown',
			    'heading' => __( 'Width', 'flora' ),
			    'param_name' => 'el_width',
			    'value' => array(
                    '10%',
                    '20%',
                    '30%',
                    '40%',
                    '50%',
                    '60%',
                    '70%',
                    '80%',
                    '90%',
                    '100%',
                ),
			    'description' => __( 'Separator element width in percents.', 'flora' ),
		    ),
            array(
			    'type' => 'colorpicker',
			    'heading' => __( 'Color', 'flora' ),
			    'param_name' => 'color',
			    'description' => __( 'Select separator border and text color.', 'flora' ),
		    ),
		    array(
			    'type' => 'textfield',
			    'heading' => __( 'Extra CSS Class', 'flora' ),
			    'param_name' => 'el_class',
			    'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		    ),
		    array(
			    'type' => 'css_editor',
			    'heading' => __( 'CSS', 'flora' ),
			    'param_name' => 'css',
			    'group' => __( 'Design Options', 'flora' )
		    )
	    )
));