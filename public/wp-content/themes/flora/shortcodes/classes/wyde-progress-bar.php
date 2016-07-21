<?php
/* Progress Bar
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Progress_Bar extends WPBakeryShortCode {

}

vc_map( array(
	    'name' => __( 'Progress Bar', 'flora' ),
	    'base' => 'wyde_progress_bar',
        'icon' =>  'wyde-icon progress-bar-icon', 
        'weight'    => 900,
	    'category' => 'Flora',
	    'description' => __( 'Animated progress bar', 'flora' ),
	    'params' => array(
		        array(
			        'type' => 'textfield',
			        'heading' => __( 'Title', 'flora' ),
			        'param_name' => 'title',
                    'admin_label' => true,
			        'description' => __( 'Enter text which will be used as graph title.', 'flora' )
		        ),
		        array(
			        'type' => 'textfield',
			        'heading' => __( 'Value', 'flora' ),
			        'param_name' => 'value',
			        'description' => __( 'Input graph value', 'flora')
		        ),
		        array(
			        'type' => 'textfield',
			        'heading' => __( 'Unit', 'flora' ),
			        'param_name' => 'unit',
			        'description' => __( 'Enter measurement unit (if needed) Eg. %, px, points, etc. Graph value and unit will be appended to the graph tooltip.', 'flora' )
		        ),
		        array(
			        'type' => 'colorpicker',
			        'heading' => __( 'Color', 'flora' ),
			        'param_name' => 'color',
			        'description' => __( 'Select bar color.', 'flora' ),
		        ),
		        array(
			        'type' => 'checkbox',
			        'heading' => __( 'Options', 'flora' ),
			        'param_name' => 'options',
			        'value' => array(
                        __( 'Hide Counter', 'flora' ) => 'hidecounter',
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
