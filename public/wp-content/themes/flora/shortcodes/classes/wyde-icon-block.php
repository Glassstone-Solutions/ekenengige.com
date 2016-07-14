<?php 
/* Icon Block
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Icon_Block extends WPBakeryShortCode {
}

$icon_picker_options = Wyde_Shortcode::get_iconpicker_options();

vc_map( array(
            'name' => __('Icon Block', 'flora'),
            'description' => __('Add icon block.', 'flora'),
            'base' => 'wyde_icon_block',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon icon-block-icon', 
            'weight'    => 900,
            'category' => 'Flora',
            'params' => array(
                    $icon_picker_options[0],
                    $icon_picker_options[1],
                    $icon_picker_options[2],
                    $icon_picker_options[3],
                    $icon_picker_options[4],
                    $icon_picker_options[5],
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Icon Size', 'flora'),
                        'param_name' => 'size',
                        'value' => array(
                            __('Small', 'flora') => 'small', 
                            __('Medium', 'flora') => 'medium', 
                            __('Large', 'flora') => 'large',
                        ),
                        'description' => __('Select icon size.', 'flora')
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Icon Style', 'flora'),
                        'param_name' => 'style',
                        'value' => array(
                            __('Circle', 'flora') => 'circle', 
                            __('Square', 'flora') => 'square',
                            __('None', 'flora') => 'none',
                        ),
                        'description' => __('Select icon style.', 'flora')
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Hover Effect', 'flora'),
                        'param_name' => 'hover',
                        'value' => array(
                            __('None', 'flora') => 'none',
                            __('Zoom In', 'flora') => '1',
                            __('Zoom Out', 'flora')  => '2',
                            __('Pulse', 'flora')  => '3',
                            __('Left to Right', 'flora')  => '4',
                            __('Right to Left', 'flora') => '5',
                            __('Bottom to Top', 'flora') => '6',
                            __('Top to Bottom', 'flora') => '7'
                        ),
                        'description' => __('Select icon hover effect.', 'flora'),
                        'dependency' => array(
		                    'element' => 'style',
		                    'value' => array('circle', 'square')
	                    )
                    ),
                    array(
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => __('Color', 'flora'),
                        'param_name' => 'color',
                        'description' => __('Select icon text color.', 'flora'),
                        'dependency' => array(
		                    'element' => 'style',
		                    'value' => array('none')
	                    )
                    ),
                    array(
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => __('Background Color', 'flora'),
                        'param_name' => 'bg_color',
                        'description' => __('Select icon background color.', 'flora'),
                        'dependency' => array(
		                    'element' => 'style',
		                    'value' => array('circle', 'square')
	                    )
                    ),
                    array(
                        'type' => 'vc_link',
                        'class' => '',
                        'heading' => __('URL', 'flora'),
                        'param_name' => 'link',
                        'description' => __('Icon link.', 'flora'),
                        'dependency' => array(
		                    'element' => 'style',
		                    'value' => array('circle', 'square')
	                    )
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
		            )
            )
) );