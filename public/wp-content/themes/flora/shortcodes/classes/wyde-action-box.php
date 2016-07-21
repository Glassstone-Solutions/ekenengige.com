<?php
/* Action Box
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Action_Box extends WPBakeryShortCode {
}

$icon_picker_options = Wyde_Shortcode::get_iconpicker_options();
$icon_picker_options[0]['group'] = __( 'Button', 'flora' );
$icon_picker_options[1]['group'] = __( 'Button', 'flora' );
$icon_picker_options[2]['group'] = __( 'Button', 'flora' );
$icon_picker_options[3]['group'] = __( 'Button', 'flora' );
$icon_picker_options[4]['group'] = __( 'Button', 'flora' );
$icon_picker_options[5]['group'] = __( 'Button', 'flora' );

vc_map( array(
	'name' => __( 'Action Box', 'flora' ),
	'base' => 'wyde_action_box',
    'icon' =>  'wyde-icon action-box-icon', 
	'category' => 'Flora',
    'weight'    => 900,
	'description' => __( 'Catch visitors attention with call to action box', 'flora' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Heading first line', 'flora' ),
			'admin_label' => true,
			'param_name' => 'title',
			'value' => '',
			'description' => __( 'Text for the first heading line.', 'flora' )
		),
		array(
			'type' => 'textarea_html',
			'param_name' => 'content',
			'value' => __( 'I am promo text. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'flora' )
		),
        array(
			'type' => 'colorpicker',
			'heading' => __( 'Background Color', 'flora' ),
			'param_name' => 'bg_color',
			'description' => __( 'Select background color for this element.', 'flora' )
		),
        array(
                'type' => 'textfield',
                'class' => '',
                'heading' => __('Title', 'flora'),
                'param_name' => 'button_text',
                'admin_label' => true,
                'value' => '',
                'description' => __('Text on the button.', 'flora'),
                'group' => __( 'Button', 'flora' )
        ),
        array(
			'type' => 'vc_link',
			'heading' => __( 'URL (Link)', 'flora' ),
			'param_name' => 'link',
			'description' => __( 'Set a button link.', 'flora' ),
            'group' => __( 'Button', 'flora' )
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
            ),
            'description' => __('Select button style.', 'flora'),
            'group' => __( 'Button', 'flora' )
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
            'description' => __('Select button size.', 'flora'),
            'group' => __( 'Button', 'flora' )
        ),
        array(
			'type' => 'colorpicker',
			'heading' => __( 'Button Color', 'flora' ),
			'param_name' => 'color',
			'description' => __( 'Select button color.', 'flora' ),
            'group' => __( 'Button', 'flora' )
        ),
        array(
			'type' => 'colorpicker',
			'heading' => __( 'Hover Color', 'flora' ),
			'param_name' => 'hover_color',
			'description' => __( 'Select hover text color.', 'flora' ),
            'group' => __( 'Button', 'flora' )
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
        