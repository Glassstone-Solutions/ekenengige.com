<?php
/* Info Box
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Info_Box extends WPBakeryShortCode {

}

$icon_picker_options = Wyde_Shortcode::get_iconpicker_options();

vc_map( array(
        'name' => __('Info Box', 'flora'),
        'description' => __('Content box with icon.', 'flora'),
        'base' => 'wyde_info_box',
        'class' => '',
        'controls' => 'full',
        'icon' =>  'wyde-icon info-box-icon', 
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
                    'description' => __('Set info box title.', 'flora')
                ),
                array(
                    'type' => 'textarea_html',
                    'class' => '',
                    'heading' => __('Content', 'flora'),
                    'param_name' => 'content',
                    'value' => '',
                    'description' => __('Enter your content.', 'flora')
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
                    'heading' => __('Icon Size', 'flora'),
                    'param_name' => 'icon_size',
                    'value' => array(
                        __('Small', 'flora') => 'small', 
                        __('Medium', 'flora') => 'medium', 
                        __('Large', 'flora') => 'large'
                    ),
                    'description' => __('Select icon size.', 'flora')
                ),
                array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Icon Position', 'flora'),
                    'param_name' => 'icon_position',
                    'value' => array(
                        __('Top', 'flora') => 'top', 
                        __('Left', 'flora') => 'left', 
                        __('Right', 'flora') => 'right', 
                    ),
                    'description' => __('Select icon position.', 'flora'),
                    'dependency' => array(
		                'element' => 'icon_size',
		                'value' => array('small', 'medium')
	                )
                ),
                array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Border Style', 'flora'),
                    'param_name' => 'icon_style',
                    'value' => array(
                        __('None', 'flora') => 'none', 
                        __('Circle', 'flora') => 'circle', 
                    ),
                    'description' => __('Select icon border style.', 'flora'),
                    'dependency' => array(
		                'element' => 'icon_size',
		                'value' => array('small', 'medium')
	                )
                ),
                array(
                    'type' => 'colorpicker',
                    'class' => '',
                    'heading' => __('Color', 'flora'),
                    'param_name' => 'color',
                    'value' => '',
                    'description' => __('Select an icon color.', 'flora')
                ),
                array(
			        'type' => 'vc_link',
			        'heading' => __( 'URL (Link)', 'flora' ),
			        'param_name' => 'link',
			        'description' => __( 'Set a Read More link.', 'flora' )
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