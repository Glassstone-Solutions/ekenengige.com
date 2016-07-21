<?php
/* Pricing Box
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Pricing_Box extends WPBakeryShortCode {
}

$icon_picker_options = Wyde_Shortcode::get_iconpicker_options();

vc_map( array(
    'name' => __('Pricing Box', 'flora'),
    'description' => __('Create pricing box.', 'flora'),
    'base' => 'wyde_pricing_box',
    'class' => '',
    'controls' => 'full',
    'icon' =>  'wyde-icon pricing-box-icon', 
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
                'param_name' => 'heading',
                'admin_label' => true,
                'value' => '',
                'description' => __('Enter the heading or package name.', 'flora')
            ),
            array(
                'type' => 'textfield',
                'class' => '',
                'heading' => __('Sub Heading', 'flora'),
                'param_name' => 'sub_heading',
                'value' => '',
                'description' => __('Enter a short description.', 'flora')
            ),
            array(
                'type' => 'textfield',
                'class' => '',
                'heading' => __('Price', 'flora'),
                'param_name' => 'price',
                'admin_label' => true,
                'value' => '',
                'description' => __('Enter a price. Example: $100', 'flora')
            ),
            array(
                'type' => 'textfield',
                'class' => '',
                'heading' => __('Price Unit', 'flora'),
                'param_name' => 'price_unit',
                'value' => '',
                'description' => __('Enter a price unit. Example: per month', 'flora')
            ),
            array(
			    'type' => 'colorpicker',
			    'heading' => __( 'Box Color', 'flora' ),
			    'param_name' => 'color',
			    'description' => __( 'Select a box color.', 'flora' ),
            ),
            array(
                'type' => 'textarea_html',
                'class' => '',
                'heading' => __('Features', 'flora'),
                'param_name' => 'content',
                'value' => '',
                'description' => __('Enter the features list or table description.', 'flora')
            ),
            array(
                'type' => 'textfield',
                'class' => '',
                'heading' => __('Button Text', 'flora'),
                'param_name' => 'button_text',
                'value' => '',
                'description' => __('Enter a button text.', 'flora')
            ),
            array(
			    'type' => 'colorpicker',
			    'heading' => __( 'Button Color', 'flora' ),
			    'param_name' => 'button_color',
			    'description' => __( 'Select a button color.', 'flora' ),
            ),
            array(
                'type' => 'vc_link',
                'class' => '',
                'heading' => __('Button Link', 'flora'),
                'param_name' => 'link',
                'value' => '',
                'description' => __('Select or enter the link for button.', 'flora')
            ),
            array(
                'type' => 'checkbox',
                'class' => '',
                'heading' => __('Featured Box', 'flora'),
                'param_name' => 'featured',
                'value' => array(
                        __('Make this box as featured', 'flora') => 'true'
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