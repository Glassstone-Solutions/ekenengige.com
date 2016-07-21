<?php

/* Toggle (FAQ)
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Toggle extends WPBakeryShortCode {
}

vc_map( array(
	    'name' => __( 'FAQ', 'flora' ),
	    'base' => 'wyde_toggle',
	    'icon' => 'wyde-icon toggle-icon',
        'weight'    => 900,
	    'category' => 'Flora',
	    'description' => __( 'Toggle element for Q&A block', 'flora' ),
	    'params' => array(
		    array(
			    'type' => 'textfield',
			    'holder' => 'h4',
			    'class' => 'vc_toggle_title',
			    'heading' => __( 'Toggle title', 'flora' ),
			    'param_name' => 'title',
			    'value' => __( 'Toggle title', 'flora' ),
			    'description' => __( 'Toggle block title.', 'flora' )
		    ),
		    array(
			    'type' => 'textarea_html',
			    'holder' => 'div',
			    'class' => 'vc_toggle_content',
			    'heading' => __( 'Toggle content', 'flora' ),
			    'param_name' => 'content',
			    'value' => __( '<p>Toggle content goes here, click edit button to change this text.</p>', 'flora' ),
			    'description' => __( 'Toggle block content.', 'flora' )
		    ),
            array(
                'type' => 'colorpicker',
                'class' => '',
                'param_name' => 'color',
                'heading' => __('Color', 'flora'),
                'description' => __('Select an element color.', 'flora')
            ),
		    array(
			    'type' => 'dropdown',
			    'heading' => __( 'Default State', 'flora' ),
			    'param_name' => 'open',
			    'value' => array(
				    __( 'Closed', 'flora' ) => 'false',
				    __( 'Open', 'flora' ) => 'true'
			    ),
			    'description' => __( 'Select "Open" if you want toggle to be open by default.', 'flora' )
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
	    ),
	    'js_view' => 'VcToggleView'
) );