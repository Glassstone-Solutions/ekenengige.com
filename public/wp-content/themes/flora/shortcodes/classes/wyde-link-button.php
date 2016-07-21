<?php
/* Link Button
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Link_Button extends WPBakeryShortCode {
}

$icon_picker_options = Wyde_Shortcode::get_iconpicker_options();

vc_map( array(
    'name' => __('Link Button', 'flora'),
    'description' => __('Add link button with icon.', 'flora'),
    'base' => 'wyde_link_button',
    'class' => '',
    'controls' => 'full',
    'icon' =>  'wyde-icon link-button-icon', 
    'weight'    => 990,
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
                    __('Square', 'flora') => '', 
                    __('Round', 'flora') => 'round', 
                    __('None', 'flora') => 'none', 
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
			    'heading' => __( 'Button Color', 'flora' ),
			    'param_name' => 'color',
			    'description' => __( 'Select button color.', 'flora' ),
            ),
            array(
			    'type' => 'colorpicker',
			    'heading' => __( 'Hover Color', 'flora' ),
			    'param_name' => 'hover_color',
			    'description' => __( 'Select hover text color.', 'flora' ),
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