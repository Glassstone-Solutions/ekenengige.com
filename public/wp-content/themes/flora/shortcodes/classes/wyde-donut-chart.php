<?php
/* Donut Chart
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Donut_Chart extends WPBakeryShortCode {
}

$icon_picker_options = Wyde_Shortcode::get_iconpicker_options();
$icon_picker_options[0]['dependency'] = array(
		                    'element' => 'label_style',
		                    'value' => array('icon')
		                );

vc_map( array(
            'name' => __('Donut Chart', 'flora'),
            'description' => __('Animated donut chart.', 'flora'),
            'base' => 'wyde_donut_chart',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon donut-chart-icon', 
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
                        'description' => __('Set chart title.', 'flora')
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Chart Value', 'flora'),
                        'param_name' => 'value',
                        'admin_label' => true,
                        'value' => '',
                        'description' => __('Input chart value here. can be 1 to 100.', 'flora')
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Label Style', 'flora'),
                        'param_name' => 'label_style',
                        'value' => array(
                            __('Text', 'flora') => '', 
                            __('Icon', 'flora') => 'icon', 
                            ),
                        'description' => __('Select a label style.', 'flora')
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Label', 'flora'),
                        'param_name' => 'label',
                        'value' => '',
                        'description' => __('Set chart label.', 'flora'),
                        'dependency' => array(
		                    'element' => 'label_style',
		                    'is_empty' => true,
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
                        'class' => '',
                        'heading' => __('Style', 'flora'),
                        'param_name' => 'style',
                        'value' => array(
                            __('Default', 'flora') => '', 
                            __('Outline', 'flora') => 'outline', 
                            __('Inline', 'flora') => 'inline', 
                            ),
                        'description' => __('Select style.', 'flora')
                    ),
                    array(
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => __('Bar Color', 'flora'),
                        'param_name' => 'bar_color',
                        'value' => '',
                        'description' => __('Select bar color.', 'flora')
                    ),
                    array(
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => __('Border Color', 'flora'),
                        'param_name' => 'bar_border_color',
                        'value' => '',
                        'description' => __('Select border color.', 'flora')
                    ),
                    array(
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => __('Fill Color', 'flora'),
                        'param_name' => 'fill_color',
                        'value' => '',
                        'description' => __('Select background color of the whole circle.', 'flora')
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Start', 'flora'),
                        'param_name' => 'start',
                        'value' => array(
                            __('Default', 'flora') => '', 
                            __('90 degree', 'flora') => '90', 
                            __('180 degree', 'flora') => '180', 
                            __('270 degree', 'flora') => '270', 
                            ),
                        'description' => __('Select the degree to start animate.', 'flora')
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
));