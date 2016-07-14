<?php   
/* Blog Posts
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Blog_Posts extends WPBakeryShortCode {

    public function get_masonry_layout( $layout = '' ){
        $masonry_layout = array(); 
        return apply_filters('wyde_blog_masonry_layout', $masonry_layout, $layout);
    }

}

vc_map( array(
        'name' => __('Blog Posts', 'flora'),
        'description' => __('Displays Blog Posts list.', 'flora'),
        'base' => 'wyde_blog_posts',
        'class' => '',
        'controls' => 'full',
        'icon' =>  'wyde-icon blog-posts-icon', 
        'weight'    => 900,
        'category' => 'Flora',
        'params' => array(
                array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Layout', 'flora'),
                    'param_name' => 'view',
                    'admin_label' => true,
                    'value' => array(
                        __('Default', 'flora') => '', 
                        __('Grid', 'flora') => 'grid', 
                        __('Flora Masonry', 'flora') => 'w-masonry'
                    ),
                    'description' => __('Select blog posts view.', 'flora')
                ),
                array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Columns', 'flora'),
                    'param_name' => 'columns',
                    'value' => array(
                        '2', 
                        '3', 
                        '4'
                    ),
                    'description' => __('Select the number of columns.', 'flora'),
                    'dependency' => array(
				        'element' => 'view',
				        'value' => array( 'grid' ),
			        )
                ),
                array(
			        'type' => 'loop',
			        'heading' => __( 'Custom Posts', 'flora' ),
			        'param_name' => 'posts_query',
			        'settings' => array(
                        'post_type'  => array('hidden' => true),
				        'size' => array( 'hidden' => true),
				        'order_by' => array( 'value' => 'date' ),
				        'order' => array( 'value' => 'DESC' ),
			        ),
			        'description' => __( 'Create WordPress loop, to populate content from your site.', 'flora' )
		        ),
                array(
                    'type'      => 'textfield',
                    'class'     => '',
                    'heading'     => __('Number of Posts per Page', 'flora'),
                    'param_name'    => 'count',
                    'description'  => __('Enter the number of posts per page.', 'flora'),
                    'value'   => '10'
                        
                ),
                array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Pagination Type', 'flora'),
                    'param_name' => 'pagination',
                    'value' => array(
                        __('Numeric Pagination', 'flora') => '1', 
                        __('Infinite Scroll', 'flora') => '2',
                        __('Next and Previous', 'flora') => '3',
                        __('Hide', 'flora') => 'hide',
                        ),
                    'description' => __('Select the pagination type.', 'flora')
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