<?php
/* Team Members Slider
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Team_Slider extends WPBakeryShortCode {
}

vc_map( array(
    'name' => __('Team Members Slider', 'flora'),
    'description' => __('Team Members in slider view.', 'flora'),
    'base' => 'wyde_team_slider',
    'class' => '',
    'controls' => 'full',
    'icon' =>  'wyde-icon team-slider-icon', 
    'weight'    => 900,
    'category' => 'Flora',
    'params' => array(
            array(
                'type' => 'checkbox',
                'class' => '',
                'param_name' => 'hide_member_name',
                'value' => array(
                        __('Hide Member Name', 'flora' ) => 'true'
                ),
                'description' => __('Check this to hide member\'s name on cover image.', 'flora')
            ),
            array(
                'type' => 'colorpicker',
                'class' => '',
                'heading' => __('Member Name Color', 'flora'),
                'param_name' => 'color',
                'value' => '',
                'description' => __('Select color of member\'s name (on cover image).', 'flora')
            ),
            array(
			    'type' => 'loop',
			    'heading' => __( 'Custom Posts', 'flora' ),
			    'param_name' => 'posts_query',
			    'settings' => array(
                    'post_type'  => array('hidden' => true),
                    'categories'  => array('hidden' => true),
                    'tags'  => array('hidden' => true),
				    'size' => array( 'hidden' => true),
				    'order_by' => array( 'hidden' => true ),
				    'order' => array( 'hidden' => true ),
			    ),
			    'description' => __( 'Create WordPress loop, to populate content from your site.', 'flora' )
		    ),
            array(
                'type' => 'textfield',
                'class' => '',
                'heading' => __('Post Count', 'flora'),
                'param_name' => 'count',
                'value' => '10',
                'description' => __('Number of posts to show.', 'flora')
            ),
            array(
                'type' => 'dropdown',
                'class' => '',
                'heading' => __('Visible Items', 'flora'),
                'param_name' => 'visible_items',
                'value' => array('1', '2', '3', '4', '5'),
                'std' => '3',
                'description' => __('The maximum amount of items displayed at a time.', 'flora')
            ),
            array(
                'type' => 'dropdown',
                'class' => '',
                'heading' => __('Transition', 'flora'),
                'param_name' => 'transition',
                'value' => array(
                    __('Slide', 'flora') => '', 
                    __('Fade', 'flora') => 'fade', 
                ),
                'description' => __('Select animation type.', 'flora'),
                'dependency' => array(
				    'element' => 'visible_items',
				    'value' => array('1')
			    )
            ),
            array(
                'type' => 'checkbox',
                'class' => '',
                'param_name' => 'show_navigation',
                'value' => array(
                        __('Show Navigation', 'flora') => 'true'
                ),
                'description' => __('Display "next" and "prev" buttons.', 'flora')
            ),
            array(
                'type' => 'checkbox',
                'class' => '',
                'param_name' => 'show_pagination',
                'value' => array(
                        __('Show Pagination', 'flora') => 'true'
                ),
                'description' => __('Show pagination.', 'flora')
            ),
            array(
                'type' => 'checkbox',
                'class' => '',
                'param_name' => 'loop',
                'value' => array(
                        __('Loop', 'flora') => 'true'
                ),
                'description' => __('Inifnity loop. Duplicate last and first items to get loop illusion.', 'flora')
            ),
            array(
                'type' => 'checkbox',
                'class' => '',
                'heading' => __('Auto Play', 'flora'),
                'param_name' => 'auto_play',
                'value' => array(
                        __('Auto Play', 'flora') => 'true'
                ),
                'description' => __('Auto play slide.', 'flora')
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