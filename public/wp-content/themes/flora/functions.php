<?php

if ( !isset($content_width) ) {
    $content_width = 1130;
}

// Setup Theme
function flora_setup_theme() {
    // Translation
    load_theme_textdomain( 'flora', get_template_directory() . '/languages' );

    // Default RSS feed links
    add_theme_support('automatic-feed-links');

    // Woocommerce Support
    add_theme_support('woocommerce');

    // Enable support for Post Formats
	add_theme_support('post-formats', array(
		'audio', 'gallery', 'link', 'quote', 'video'
	));

    // Add post thumbnail functionality
    // Square sizes
    add_theme_support('post-thumbnails');
    add_image_size('medium', 300, 300, true);
    add_image_size('large', 640, 640, true);
    add_image_size('x-large', 960, 960, true);
    // Landscape sizes
    add_image_size('preview-medium', 640, 360, true);
    add_image_size('preview-large', 960, 540, true);
    add_image_size('full-width', 1280, 720, false);

    // Allow shortcodes in widget text
    add_filter('widget_text', 'do_shortcode');

    // Register Navigation Location
	register_nav_menus( array(
		'primary'   => 'Primary Navigation',
		'footer' => 'Footer Navigation'
	));

    // Add html editor css styles
    add_editor_style( array( 'css/icons.css', 'css/editor.css' ) );
    
    // This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );

}
add_action('after_setup_theme', 'flora_setup_theme');

// Initialize Widgets
function flora_widgets_init(){
    // Register Sidebar Location
    register_sidebar(array(
		'name' => 'Page Sidebar 1',
		'id' => 'page1',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

    register_sidebar(array(
		'name' => 'Page Sidebar 2',
		'id' => 'page2',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

    register_sidebar(array(
		'name' => 'Blog Sidebar',
		'id' => 'blog',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

    register_sidebar(array(
		'name' => 'Shop Sidebar',
		'id' => 'shop',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

    register_sidebar(array(
		'name' => 'Sliding Bar',
		'id' => 'slidingbar',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

	register_sidebar(array(
		'name' => 'Footer Column 1',
		'id' => 'footer1',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

    register_sidebar(array(
		'name' => 'Footer Column 2',
		'id' => 'footer2',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

    register_sidebar(array(
		'name' => 'Footer Column 3',
		'id' => 'footer3',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

    register_sidebar(array(
		'name' => 'Footer Column 4',
		'id' => 'footer4',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
}
add_action( 'widgets_init', 'flora_widgets_init' );

// Register and enqueue styles
function flora_load_styles(){
    
    global $wp_styles, $wyde_options;

    $theme_info = wp_get_theme('flora');

    $version = $theme_info->get( 'Version' );

    $asset_base_uri = get_template_directory_uri();

    /* Plugins stylesheet */    
    // Deregister CSS from Visual Composer and other plugins
    wp_deregister_style('flexslider');
    wp_deregister_style('prettyphoto');
    wp_deregister_style('js_composer_front');
    wp_deregister_style('contact-form-7');

    //Deregister font icons from Visual Composer
    wp_deregister_style('font-awesome');
    wp_deregister_style('vc_openiconic');
    wp_deregister_style('vc_typicons');
    wp_deregister_style('vc_entypo');
    wp_deregister_style('vc_linecons');

    // Add Flora core CSS file
    wp_enqueue_style('flora', get_stylesheet_uri(), null, $version);

    // Flora stylesheet
    wp_register_style('flora-theme', $asset_base_uri . '/css/flora.css', null, $version);
    wp_enqueue_style('flora-theme');
    
    // Register font icons
    wp_register_style('flora-icons', $asset_base_uri . '/css/icons.css', 'flora-theme', $version);
    wp_enqueue_style('flora-icons');
    do_action( 'wyde_enqueue_icon_font' );

    // Flora Animations
    wp_enqueue_style('flora-animation', $asset_base_uri . '/css/animation.css', 'flora-theme', $version);
    
    if( class_exists( 'WooCommerce' ) ){
        // Deregister WooCommerce CSS
        wp_deregister_style('woocommerce-layout');
        wp_deregister_style('woocommerce-smallscreen');
        wp_deregister_style('woocommerce-general');
        wp_deregister_style('woocommerce_prettyPhoto_css');
    }

}
add_action('wp_enqueue_scripts', 'flora_load_styles');

// Register and enqueue scripts
function flora_load_scripts(){
    
    global $wyde_options;

    $theme_info = wp_get_theme();

    $version = $theme_info->get( 'Version' );

    // Deregister scripts from Visual Composer and other plugins
    wp_deregister_script( 'bootstrapjs' );
    wp_deregister_script( 'wpb_composer_front_js' );
    wp_deregister_script( 'jcarousellite' );
    wp_deregister_script( 'waypoints' );
    wp_deregister_script( 'isotope' );
    wp_deregister_script( 'flexslider' );
    wp_deregister_script( 'prettyphoto' );

    if( class_exists('WooCommerce') ){
        wp_dequeue_script( 'prettyPhoto' );
        wp_dequeue_script( 'prettyPhoto-init' );
    }
    
    $asset_base_uri = get_template_directory_uri();

    // jQuery scripts.
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-effects-core');

    // Modernizr
    wp_register_script('modernizr', $asset_base_uri . '/js/modernizr.js', array('jquery'), null, false);
    wp_enqueue_script('modernizr');

    // Flora jQuery plugins
    wp_register_script('flora-plugins', $asset_base_uri . '/js/plugins.js', array('jquery'), $version, true);
    wp_enqueue_script('flora-plugins');

    // Flora main scripts
    wp_register_script('flora-main', $asset_base_uri . '/js/main.js', array('jquery'), $version, true);
    wp_enqueue_script('flora-main');

    $page_settings = array();
    $page_settings['siteURL'] = get_home_url();
    if( $wyde_options['preload_images'] ){
        $page_settings['isPreload'] = true;
    }

    if( $wyde_options['mobile_animation'] ){
        $page_settings['mobile_animation'] = true;
    }

    $page_settings['ajaxURL'] = admin_url( 'admin-ajax.php' );

    // Ajax Search
    if( $wyde_options['ajax_search'] ){
        $page_settings['ajax_search'] = true;
    }

    // Ajax Page Transition enabled
    if($wyde_options['ajax_page']){

        $ajax_page_settings = array();
        $ajax_page_settings['transition'] =  $wyde_options['ajax_page_transition'];
        if( isset($wyde_options['ajax_page_exclude_urls']) && is_array($wyde_options['ajax_page_exclude_urls']) ){
            $ajax_page_settings['excludeURLs'] = $wyde_options['ajax_page_exclude_urls'];
        }

        $page_settings['ajax_page'] = true;
        $page_settings['ajax_page_settings'] = $ajax_page_settings;

        wp_enqueue_script( 'comment-reply' );
        wp_enqueue_script( 'wp-mediaelement' );

    }

    // Smooth Scroll
    if( $wyde_options['smooth_scroll'] ){
        $page_settings['smooth_scroll'] = true;
    }

    wp_localize_script('flora-main', 'page_settings', $page_settings);

    wp_enqueue_script('smoothscroll', $asset_base_uri . '/js/smoothscroll.js', null, $version, true);   

}
add_action('wp_enqueue_scripts', 'flora_load_scripts');

// Register and enqueue admin scripts
function flora_load_admin_styles(){
    
    $asset_base_uri = get_template_directory_uri();
    
    //Deregister font icons from Visual Composer
    wp_deregister_style('font-awesome');
    wp_deregister_style('vc_openiconic');
    wp_deregister_style('vc_typicons');
    wp_deregister_style('vc_entypo');
    wp_deregister_style('vc_linecons');

    // Register font icons
    wp_register_style('flora-icons', $asset_base_uri . '/css/icons.css', null, null);
    wp_enqueue_style('flora-icons');
    do_action( 'wyde_enqueue_icon_font' );

    // Flora Animations
    wp_enqueue_style('flora-animation', $asset_base_uri . '/css/animation.css', null, null);

}
add_action( 'admin_enqueue_scripts', 'flora_load_admin_styles');

// Theme activation - update WooCommerce image sizes
function flora_theme_activation()
{
	global $pagenow;
	if(is_admin() && 'themes.php' == $pagenow && isset($_GET['activated']))
	{	
        //update WooCommerce thumbnail size after theme activation
        update_option('shop_thumbnail_image_size', array('width' => 150, 'height' => 150, 'crop'    =>  true));
		update_option('shop_catalog_image_size', array('width' => 300, 'height' => 300, 'crop'  =>    true));
		update_option('shop_single_image_size', array('width' => 640, 'height' => 640, 'crop'   => true));
	}
}
add_action('admin_init','flora_theme_activation');

//Add Open Graph
function flora_add_opengraph_doctype( $output ) {
    return $output . ' prefix="og: http://ogp.me/ns#"';
}
add_filter('language_attributes', 'flora_add_opengraph_doctype');

//Add og meta inside <head>
function flora_add_og_meta() {
    global $wyde_options, $post;
    
    if (!is_singular()) {
        return;
    }
        
    echo sprintf('<meta property="og:title" content="%s"/>', get_the_title());
    echo sprintf('<meta property="og:url" content="%s"/>', get_permalink());
    echo sprintf('<meta property="og:site_name" content="%s"/>', get_bloginfo('name'));
    echo '<meta property="og:type" content="article"/>';
    if(!has_post_thumbnail( $post->ID )) { 
        if( !empty( $wyde_options['logo_image']['url'] )){
           echo sprintf('<meta property="og:image" content="%s"/>', esc_url( $wyde_options['logo_image']['url'] ));         
        }
    } else {
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
        echo sprintf('<meta property="og:image" content="%s"/>', esc_url( $thumbnail_src[0] ) );
    }
}
add_action('wp_head', 'flora_add_og_meta', 5);

// Site Title
function flora_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

    $sep = ' | ';

	// Add the site description for the home/front page.
	if ( is_home() || is_front_page() ){
		$title = get_bloginfo( 'name' );
        $desc = get_bloginfo( 'description' );
        if( !empty($desc) ) $title .= ' - ' .$desc;
	}else{
        // Add a page number if necessary.
	    if ( $paged >= 2 || $page >= 2 ){
		    $title = "$title - " . sprintf( __( 'Page %s', 'flora' ), max( $paged, $page ) ) . $sep. get_bloginfo( 'name' );
	    }else{
	        $title .= $sep. get_bloginfo( 'name' );
	    }
	}
	
	return trim($title);
}
add_filter( 'wp_title', 'flora_wp_title', 10, 2 );

//Update post views
function flora_track_post_views ($post_id) {
    if ( !is_single() ){
        return;
    }
    if ( empty ( $post_id) ) {
        global $post;
        $post_id = $post->ID;   
    }
    wyde_set_post_views($post_id);
}
add_action( 'wp_head', 'flora_track_post_views');

// Register default function when plugin not activated
function flora_register_functions() {
	if(!function_exists('is_woocommerce')) {
		function is_woocommerce() { return false; }
	}
}
add_action('wp_head', 'flora_register_functions');

// Advanced scripts from Theme Options
function flora_custom_head_script(){
    global $wyde_options, $wyde_color_scheme;
    $wyde_color_scheme = wyde_get_color_scheme(); 
    echo '<style type="text/css" data-name="flora-color-scheme">';
    ob_start();
    include_once get_template_directory() . '/css/custom-css.php';
    echo ob_get_clean();
    echo '</style>';
    if( !empty($wyde_options['head_script']) ){
        /**
        *Echo extra HTML/JavaScript/Stylesheet from theme options > advanced - head content
        */
        echo balanceTags( $wyde_options['head_script'], true );
    } 
}
add_action('wp_head', 'flora_custom_head_script', 160);

//Set excerpt length for blog post
function flora_excerpt_length( $length ) {
    global $wyde_options, $wyde_blog_layout;

    switch($wyde_blog_layout){
        case 'grid':
            $length = 20;
            break;
        case 'masonry':
            $length = 15;
            break;
        default:
            $length =  intval( isset( $wyde_options['blog_excerpt_length'] ) ? $wyde_options['blog_excerpt_length'] : 55 );
        break;
    }     
	return $length;
}
add_filter( 'excerpt_length', 'flora_excerpt_length', 999 );

//Set image quality
function flora_image_full_quality( $quality ) {
    $quality = 80;
    return $quality;
}
add_filter('jpeg_quality', 'flora_image_full_quality');
add_filter('wp_editor_set_quality', 'flora_image_full_quality');

//Set custom post type title placeholder
function flora_get_post_title ( $title ) {

    switch(get_post_type()){
        case 'wyde_testimonial':
        $title = __( 'Enter the customer\'s name here', 'flora' );
        break;
        case 'wyde_team_member':
        $title = __( 'Enter the member\'s name here', 'flora' );
        break;
    }

	return $title;
}
add_filter( 'enter_title_here', 'flora_get_post_title' );

function flora_after_switch_theme() {
    //flush rewrite rules after switch theme
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'flora_after_switch_theme' );

/** Wyde Walker Navigation && Mega Menu class **/
require_once get_template_directory() . '/inc/class-wyde-walker-nav.php';

/* Custom Functions */
include_once get_template_directory() . '/inc/custom-functions.php';

/* Theme Options */
require_once( get_template_directory() . '/admin/class-flora-theme-options.php' );

/* Metaboxes */
include_once get_template_directory() . '/inc/metaboxes/class-flora-metaboxes.php';

/* Ajax Search */
include_once get_template_directory() . '/inc/class-wyde-ajax-search.php';

if( class_exists('WooCommerce') ){
    /* WooCommerce Template class */
    include_once get_template_directory() . '/inc/class-woocommerce-template.php';
}

/* Shortcodes */
include_once get_template_directory() . '/shortcodes/class-shortcode.php';

/* Widgets */
include_once get_template_directory() . '/widgets/widgets.php';

/* TGM Plugin Activation */
require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';

/* Register required plugins */
function flora_register_required_plugins() {
	
	$plugins = array(
		array(
			'name'     				=> 'Wyde Core', 
			'slug'     				=> 'wyde-core', 
			'source'   				=> get_template_directory() . '/inc/plugins/wyde-core.zip',
			'required' 				=> true, 
			'version' 				=> '2.0.0', 
			'force_activation' 		=> true,
			'force_deactivation' 	=> false, 
			'external_url' 			=> '', 
		),
        array(
			'name'     				=> 'WPBakery Visual Composer', 
			'slug'     				=> 'js_composer', 
			'source'   				=> get_template_directory() . '/inc/plugins/js_composer.zip',
			'required' 				=> false, 
			'version' 				=> '4.7.4', 
			'force_activation' 		=> false,
			'force_deactivation' 	=> false, 
			'external_url' 			=> '', 
		),
        array(
			'name'     				=> 'Slider Revolution', 
			'slug'     				=> 'revslider', 
			'source'   				=> get_template_directory() . '/inc/plugins/revslider.zip',
			'required' 				=> false, 
			'version' 				=> '5.0.9',
			'force_activation' 		=> false, 
			'force_deactivation' 	=> false, 
			'external_url' 			=> '',
		),
        array(
			'name'     				=> 'Contact Form 7',
			'slug'     				=> 'contact-form-7',
			'required' 				=> false,
			'version' 				=> '4.0.3',
			'force_activation' 		=> false,
			'force_deactivation' 	=> false
		)
	);

	$config = array(
		'domain'       		=> 'flora',                     // Text domain - likely want to be the same as your theme.
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins		
        'menu'         		=> 'install-required-plugins',  // Menu slug.
        'parent_slug'       => 'themes.php',            // Parent menu slug.
        'capability'        => 'edit_theme_options',
		'has_notices'      	=> true,                       	// Show admin notices or not
        'dismissable'       => true,                        // If false, a user cannot dismiss the nag message.
		'is_automatic'    	=> false,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                      => __( 'Install Recommended Plugins', 'flora' ),
            'menu_title'                      => __( 'Install Plugins', 'flora' ),
            'installing'                      => __( 'Installing Plugin: %s', 'flora' ), // %s = plugin name.
            'oops'                            => __( 'Something went wrong with the plugin API.', 'flora' ),
            'notice_can_install_required'     => _n_noop(
                'This theme requires the following plugin: %1$s.',
                'This theme requires the following plugins: %1$s.',
                'flora'
            ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop(
                'This theme recommends the following plugin: %1$s.',
                'This theme recommends the following plugins: %1$s.',
                'flora'
            ), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop(
                'Sorry, but you do not have the correct permissions to install the %1$s plugin.',
                'Sorry, but you do not have the correct permissions to install the %1$s plugins.',
                'flora'
            ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop(
                'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                'flora'
            ), // %1$s = plugin name(s).
            'notice_ask_to_update_maybe'      => _n_noop(
                'There is an update available for: %1$s.',
                'There are updates available for the following plugins: %1$s.',
                'flora'
            ), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop(
                'Sorry, but you do not have the correct permissions to update the %1$s plugin.',
                'Sorry, but you do not have the correct permissions to update the %1$s plugins.',
                'flora'
            ), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop(
                'The following required plugin is currently inactive: %1$s.',
                'The following required plugins are currently inactive: %1$s.',
                'flora'
            ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop(
                'The following recommended plugin is currently inactive: %1$s.',
                'The following recommended plugins are currently inactive: %1$s.',
                'flora'
            ), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop(
                'Sorry, but you do not have the correct permissions to activate the %1$s plugin.',
                'Sorry, but you do not have the correct permissions to activate the %1$s plugins.',
                'flora'
            ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop(
                'Begin installing plugin',
                'Begin installing plugins',
                'flora'
            ),
            'update_link'                     => _n_noop(
                'Begin updating plugin',
                'Begin updating plugins',
                'flora'
            ),
            'activate_link'                   => _n_noop(
                'Begin activating plugin',
                'Begin activating plugins',
                'flora'
            ),
            'return'                          => __( 'Return to Required Plugins Installer', 'flora' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'flora' ),
            'activated_successfully'          => __( 'The following plugin was activated successfully:', 'flora' ),
            'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'flora' ),  // %1$s = plugin name(s).
            'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'flora' ),  // %1$s = plugin name(s).
            'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'flora' ), // %s = dashboard link.
            'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'flora' ),
            'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
		)
	);

	tgmpa($plugins, $config);
}
add_action('tgmpa_register', 'flora_register_required_plugins' );