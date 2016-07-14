<?php
/* Portfolio Grid
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Portfolio_Grid extends WPBakeryShortCode {
    public function get_masonry_layout( $layout = '' ){
        $masonry_layout = array(); 
        return apply_filters('wyde_portfolio_masonry_layout', $masonry_layout, $layout);
    }
}

vc_map( array(
    'name' => __('Portfolio Grid', 'flora'),
    'description' => __('Displays Portfolio list.', 'flora'),
    'base' => 'wyde_portfolio_grid',
    'class' => '',
    'controls' => 'full',
    'icon' =>  'wyde-icon portfolio-grid-icon', 
    'weight'    => 900,
    'category' => 'Flora',
    'params' => array(
            array(
                'type' => 'dropdown',
                'class' => '',
                'heading' => __('View', 'flora'),
                'param_name' => 'view',
                'admin_label' => true,
                'value' => array(
                    __('Grid (Without Space)', 'flora') => 'grid', 
                    __('Grid (With Space)', 'flora') => 'grid-space',
                    __('Standard Masonry', 'flora') => 'masonry',
                    __('Flora Masonry', 'flora') => 'w-masonry',
                ),
                'description' => __('Select a grid view style.', 'flora')
            ),
            array(
                'type' => 'dropdown',
                'class' => '',
                'heading' => __('Columns', 'flora'),
                'admin_label' => true,
                'param_name' => 'columns',
                'value' => array(
                    '2', 
                    '3', 
                    '4',
                ),
                'std' => '4',
                'description' => __('Select a number of grid columns.', 'flora'),
                'dependency' => array(
		            'element' => 'view',
		            'value' => array('grid', 'grid-space', 'masonry')
		        )
            ),
            array(
                'type' => 'dropdown',
                'class' => '',
                'heading' => __('Hover Effect', 'flora'),
                'param_name' => 'hover_effect',
                'admin_label' => true,
                'value' => array(
                    __('Flora 1', 'flora') => 'flora-1', 
                    __('Flora 2', 'flora') => 'flora-2',
                    __('Flora 3', 'flora') => 'flora-3',
                    __('Apollo', 'flora') => 'apollo', 
                    __('Duke', 'flora') => 'duke',
                    __('Jazz', 'flora') => 'jazz',
                    __('Kira', 'flora') => 'kira',
                    __('Lexi', 'flora') => 'lexi',
                    __('Sadie', 'flora') => 'sadie',
                    __('Horizontal Split', 'flora') => 'split', 
                    __('Vertical Split', 'flora') => 'split v-split', 
                ),
                'description' => __('Select a hover effect for portfolio items.', 'flora'),
                'dependency' => array(
		            'element' => 'view',
		            'value' => array('grid', 'grid-space', 'w-masonry')
		        )
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
				    'order_by' => array( 'value' => 'date' ),
				    'order' => array( 'value' => 'DESC' ),
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
                'type' => 'checkbox',
                'class' => '',
                'param_name' => 'hide_filter',
                'value' => array(
                    __('Hide Filter', 'flora') => 'true'
                ),
                'description' => __('Display animated category filter to your grid.', 'flora')
            ),
            array(
                'type' => 'dropdown',
                'class' => '',
                'heading' => __('Pagination Type', 'flora'),
                'param_name' => 'pagination',
                'value' => array(
                    __('Infinite Scroll', 'flora') => '1',
                    __('Show More Button', 'flora') => '2',
                    __('Hide', 'flora') => 'hide',
                    ),
                'description' => __('Select a pagination type.', 'flora')
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