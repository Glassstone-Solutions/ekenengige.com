<?php
/*
Plugin Name: Wyde Core
Plugin URI: http://www.wydethemes.com
Description: Core Plugin for Wyde Themes
Version: 2.0.0
Author: Wyde
Author URI: http://www.wydethemes.com
*/

if( !class_exists( 'Wyde_Core' ) ) {
	
	class Wyde_Core {

	     /**
	     * Plugin version, used for cache-busting of style and script file references.
	     *
	     * @since   1.0.0
	     *
	     * @var     string
	     */
	    protected $version = '2.0.0';

	    /**
	     * Unique identifier for your plugin.
	     *
	     * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	     * match the Text Domain file header in the main plugin file.
	     *
	     * @since    1.0.0
	     *
	     * @var      string
	     */
	    protected $plugin_slug = 'wyde-core';

	    /**
	     * Instance of this class.
	     *
	     * @since    1.0.0
	     *
	     * @var      object
	     */
	    protected static $instance = null;
		
		/**
		 * This method adds other methods to specific hooks within WordPress.
		 *
		 * @since     1.0.0
		 */
		public function __construct() {
			$this->init();
		}

		/**
		 * Initialize the plugin by setting localization and loading public scripts
		 *
		 * @since     1.0.0
		 */
		function init() {

        	add_action( 'init', array($this, 'register_post_types') );

        	$plugin_dir = plugin_dir_path(__FILE__);
        	/** Redux Framework **/
			if (file_exists($plugin_dir.'/inc/redux-framework/framework.php')) {
			    require_once( $plugin_dir.'/inc/redux-framework/framework.php' );
			}

			/** Wyde Importer **/
			include_once $plugin_dir. '/inc/importer/class-wyde-importer.php';

			/** Metaboxes **/
			include_once $plugin_dir. '/inc/metaboxes/init.php';

			/** Mega Menu **/
			include_once $plugin_dir. '/inc/class-wyde-mega-menu.php';
		}

	    /**
	     * Return an instance of this class.
	     *
	     * @since     1.0.0
	     *
	     * @return    object    A single instance of this class.
	     */
	    public static function get_instance() {

		    // If the single instance hasn't been set, set it now.
		    if ( null == self::$instance ) {
			    self::$instance = new self;
		    }

		    return self::$instance;
	    }

	    /**
		 * Register custom post types
		 *
		 * @since     1.0.0
		 */
		function register_post_types(){
		    
		    global $wyde_options, $wp_post_types;

		    $portfolio_slug = isset( $wyde_options['portfolio_slug'] ) ? $wyde_options['portfolio_slug']:'portfolio-item';

		    $wp_post_types['attachment']->exclude_from_search = true;

		    //Portfolio post type
		    register_post_type('wyde_portfolio',
				array(
					'labels' => array(
						    'name' 			=> __( 'Portfolios', 'Wyde' ),
						    'singular_name' => __( 'Portfolio', 'Wyde' ),
		                    'add_new' => __('Add New', 'Wyde' ),
		                    'add_new_item' => __('Add New Portfolio', 'Wyde'),
		                    'edit_item' => __('Edit Portfolio', 'Wyde'),
		                    'new_item' => __('New Portfolio', 'Wyde'),
		                    'view_item' => __('View Portfolios', 'Wyde'),
		                    'menu_name' => __('Portfolios', 'Wyde')
					),
					'public' => true,
					'has_archive' => false,
					'rewrite' => array(
						    'slug' => sanitize_title( $portfolio_slug )
					),
					'supports' => array( 'title', 'editor', 'thumbnail'),
					'can_export' => true,
		            'menu_icon' => 'dashicons-portfolio'
				)
			);

		    //Portfolio Category    
		    register_taxonomy('portfolio_category', 'wyde_portfolio', 
		        array(
		            'hierarchical' => true, 
		            'labels' => array(
		                    'name' => __('Categories', 'Wyde'),
		                    'singular_name' => __('Category', 'Wyde'),
		                    'all_items' => __('All Categories', 'Wyde' ),
		                    'edit_item' => __('Edit Category', 'Wyde' ),
		                    'update_item' => __('Update Category', 'Wyde' ),
		                    'add_new_item' => __('Add New Category', 'Wyde' ),
		                    'new_item_name' => __('New Category', 'Wyde' ),
		            ), 
		            'query_var' => true, 
		            'rewrite' => array(
						    'slug' => 'portfolio-category'
				    )
		        )
		    );

		    //Portfolio Skill   
			register_taxonomy('portfolio_skill', 'wyde_portfolio', 
		        array(
		            'hierarchical' => true, 
		            'labels' => array(
		                    'name' =>  __('Skills', 'Wyde'),
		                    'singular_name' => __('Skill', 'Wyde'),
		                    'all_items' => __('All Skills', 'Wyde'),
		                    'edit_item' => __('Edit Skill', 'Wyde'),
		                    'update_item' =>  __('Update Skill', 'Wyde'),
		                    'add_new_item' => __('Add New Skill', 'Wyde'),
		                    'new_item_name' => __('New Skill', 'Wyde'),
		            ), 
		            'query_var' => true, 
		            'rewrite' => array(
						    'slug' => 'portfolio-skill'
				    )
		        )
		    );

		            
		    //Portfolio Tags   
			register_taxonomy('portfolio_tag', 'wyde_portfolio', 
		        array(
		            'hierarchical' => false, 
		            'labels' => array(
		                'name' => __('Tags', 'Wyde'),
		                'singular_name' => __('Tag', 'Wyde'),
		                'all_items' => __('All Tags', 'Wyde'),
		                'edit_item' => __('Edit Tag', 'Wyde'),
		                'update_item' => __('Update Tag', 'Wyde'),
		                'add_new_item' => __('Add New Tag', 'Wyde'),
		                'new_item_name' =>  __('New Tag', 'Wyde'),
		            ), 
		            'query_var' => true, 
		            'rewrite' => array(
						'slug' => 'portfolio-tag'
				    )
		        )
		    );

			
		    //Testimonial 	
		    register_post_type('wyde_testimonial',
				array(
					'labels' => array(
						    'name' 			=> __( 'Testimonials', 'Wyde' ),
						    'singular_name' => __( 'Testimonial', 'Wyde' ),
		                    'add_new' => __('Add New', 'Wyde' ),
		                    'add_new_item' => __('Add New Testimonial', 'Wyde'),
		                    'edit_item' => __('Edit Testimonial', 'Wyde'),
		                    'new_item' => __('New Testimonial', 'Wyde'),
		                    'view_item' => __('View Testimonial', 'Wyde'),
		                    'menu_name' => __('Testimonials', 'Wyde')
					),
					'public' => true,
					'has_archive' => false,
					'rewrite' => array(
						    'slug' => 'testimonial-item'
					),
					'supports' => array( 'title'),
					'can_export' => true,
		            'exclude_from_search' => true,
		            'menu_icon' => 'dashicons-testimonial'
				)
			);
		            
		    //Testimonial Category
		    register_taxonomy('testimonial_category', 'wyde_testimonial', 
		        array(
		            'hierarchical' => true, 
		            'labels' => array(
		                    'name' => __('Categories', 'Wyde'),
		                    'singular_name' => __('Category', 'Wyde'),
		                    'all_items' => __('All Categories', 'Wyde' ),
		                    'edit_item' => __('Edit Category', 'Wyde' ),
		                    'update_item' => __('Update Category', 'Wyde' ),
		                    'add_new_item' => __('Add New Category', 'Wyde' ),
		                    'new_item_name' => __('New Category', 'Wyde' ),
		                ),
		            'query_var' => true, 
		            'rewrite' => true
		        )
		    );

		    //Team Member
		    register_post_type('wyde_team_member', 
		        array(
					'labels' => array(
					        'name' 					=> __('Team Members', 'Wyde' ),
					        'singular_name' 		=> __('Team Member', 'Wyde' ),
					        'add_new' 				=> __('Add New', 'Wyde' ),
					        'add_new_item' 			=> __('Add New Team Member', 'Wyde' ),
					        'edit_item' 			=> __('Edit Team Member', 'Wyde' ),
					        'new_item' 				=> __('New Team Member', 'Wyde' ),
					        'all_items' 			=> __('All Team Members', 'Wyde' ),
					        'view_item' 			=> __('View Team Members', 'Wyde' ),
					        'search_items' 			=> __('Search Team Members', 'Wyde' ),
					        'parent_item_colon' 	=> '',
					        'menu_name' 			=> __( 'Team Members', 'Wyde' )
		            ),
					'public'    => true,
					'has_archive'   => false,
					'supports'  => array('title'),
		            'exclude_from_search'   => true,
					'menu_icon' => 'dashicons-groups',
		            'rewrite'   => array(
						    'slug' => 'team-member'
					),
				)
		    );


		    //Team Member Category
		    register_taxonomy('team_member_category', 'wyde_team_member', 
		        array(
		            'hierarchical' => true, 
		            'labels' => array(
		                    'name' => __('Categories', 'Wyde'),
		                    'singular_name' => __('Category', 'Wyde'),
		                    'all_items' => __('All Categories', 'Wyde' ),
		                    'edit_item' => __('Edit Category', 'Wyde' ),
		                    'update_item' => __('Update Category', 'Wyde' ),
		                    'add_new_item' => __('Add New Category', 'Wyde' ),
		                    'new_item_name' => __('New Category', 'Wyde' ),
		                ),
		            'query_var' => true, 
		            'rewrite' => true
		        )
		    );
		        
		}

	}

// Instantiate the class
$wyde_core = new Wyde_Core();

}