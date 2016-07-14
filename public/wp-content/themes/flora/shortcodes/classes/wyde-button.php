<?php
/* Button
---------------------------------------------------------- */  
class WPBakeryShortCode_Wyde_Button extends WPBakeryShortCode {
}

vc_map( array(
            'name' => __('Button', 'flora'),
            'description' => __('Add button.', 'flora'),
            'base' => 'wyde_button',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon button-icon', 
            'weight'    => 900,
            'category' => 'Flora',
            'params' => array(
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Title', 'flora'),
                        'param_name' => 'title',
                        'admin_label' => true,
                        'value' => '',
                        'description' => __('Text on the button.', 'flora')
                    ),
                    array(
			            'type' => 'vc_link',
			            'heading' => __( 'URL (Link)', 'flora' ),
			            'param_name' => 'link',
			            'description' => __( 'Set a button link.', 'flora' )
		            ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Style', 'flora'),
                        'param_name' => 'style',
                        'value' => array(
                            __('Square', 'flora') => '', 
                            __('Round', 'flora') => 'round', 
                            __('Square Outline', 'flora') => 'outline', 
                            __('Round Outline', 'flora') => 'round-outline', 
                        ),
                        'description' => __('Select button style.', 'flora')
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Size', 'flora'),
                        'param_name' => 'size',
                        'value' => array(
                            __('Small', 'flora') => '', 
                            __('Large', 'flora') =>'large', 
                        ),
                        'description' => __('Select button size.', 'flora')
                    ),
                    array(
			            'type' => 'colorpicker',
			            'heading' => __( 'Text Color', 'flora' ),
			            'param_name' => 'color',
			            'description' => __( 'Select button text color.', 'flora' ),
                        'dependency' => array(
				            'element' => 'style',
				            'value' => array('', 'round')
			            )
                    ),
                    array(
			            'type' => 'colorpicker',
			            'heading' => __( 'Background Color', 'flora' ),
			            'param_name' => 'bg_color',
			            'description' => __( 'Select button background color.', 'flora' ),
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
		            array(
			            'type' => 'css_editor',
			            'heading' => __( 'CSS', 'flora' ),
			            'param_name' => 'css',
			            'group' => __( 'Design Options', 'flora' )
		            ),
            )
));