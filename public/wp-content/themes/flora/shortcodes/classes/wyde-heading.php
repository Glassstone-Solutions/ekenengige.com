<?php
/* Heading
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Heading extends WPBakeryShortCode {
}

vc_map( array(
            'name' => __('Heading', 'flora'),
            'description' => __('Heading text.', 'flora'),
            'base' => 'wyde_heading',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon heading-icon', 
            'weight'    => 999,
            'category' => 'Flora',
            'params' => array(
                array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Title', 'flora'),
                    'param_name' => 'title',
                    'admin_label' => true,
                    'value' => '',
                    'description' => __('Enter title text.', 'flora')
                ),
                array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Sub Title', 'flora'),
                    'param_name' => 'sub_title',
                    'admin_label' => true,
                    'value' => '',
                    'description' => __('Enter sub title text.', 'flora')
                ),
                array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Separator Style', 'flora'),
                    'param_name' => 'style',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4', 
                        '5',
                        '6',
                        '7',
                        '8',
                        '9',
                        '10',
                    ),
                    'description' => __('Select a heading separator style.', 'flora')
                ),
                array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Text Alignment', 'flora'),
                    'param_name' => 'text_align',
                    'value' => array(
                        __('Center', 'flora') =>'', 
                        __('Left', 'flora') => 'left', 
                        __('Right', 'flora') => 'right', 
                    ),
                    'description' => __('Select text alignment.', 'flora'),
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
		                'not_empty' => true
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