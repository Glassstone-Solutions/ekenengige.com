<?php 
/* Counter Box
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Counter_Box extends WPBakeryShortCode {
}

$icon_picker_options = Wyde_Shortcode::get_iconpicker_options();

vc_map( array(
            'name' => __('Counter Box', 'flora'),
            'description' => __('Animated numbers.', 'flora'),
            'base' => 'wyde_counter_box',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon counter-box-icon', 
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
                            'type' => 'textfield',
                            'class' => '',
                            'heading' => __('Title', 'flora'),
                            'param_name' => 'title',
                            'admin_label' => true,
                            'value' => '',
                            'description' => __('Set counter title.', 'flora')
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Start From', 'flora'),
                        'param_name' => 'start',
                        'value' => '0',
                        'description' => __('Set start value.', 'flora')
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Value', 'flora'),
                        'param_name' => 'value',
                        'value' => '100',
                        'description' => __('Set counter value.', 'flora')
                    ),
                    array(
			            'type' => 'colorpicker',
			            'heading' => __( 'Color', 'flora' ),
			            'param_name' => 'color',
			            'description' => __( 'Select a color.', 'flora' ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Style', 'flora'),
                        'param_name' => 'style',
                        'value' => array(
                            __('Default', 'flora') => '', 
                            __('Classic', 'flora') => '1', 
                        ),
                        'description' => __('Select counter box style.', 'flora')
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