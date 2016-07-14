<?php
if( !class_exists('Wyde_Shortcode') ){

    class Wyde_Shortcode{

        function __construct() {
		    add_action( 'init', array($this, 'init'));
            add_action( 'wp_enqueue_scripts', array($this, 'load_shortcodes_scripts' ) );
            
            $this->revslider_set_as_theme();
            $this->integrate_with_vc();
	    }

        function init() {

            if ( get_user_option('rich_editing') == 'true' ) {
                add_filter( 'tiny_mce_before_init', array( $this, 'init_tinymce' ) );
                //add_filter( 'mce_buttons', array( $this, 'register_buttons' ), 500 );
                add_filter( 'mce_external_plugins', array( $this, 'add_buttons' ) );
		    }
    
	    }

        /*
        * Initialize tinymce settings
        */
        function init_tinymce( $in ) {
	        $in['toolbar1'] = 'bold,italic,underline,strikethrough,dropcap,highlight,bullist,numlist,blockquote,alignleft,aligncenter,alignright,alignjustify,link,unlink,wp_fullscreen,wp_adv';
	        $in['toolbar2'] = 'formatselect,forecolor,backcolor,hr,pastetext,removeformat,charmap,outdent,indent,wp_more,spellchecker,undo,redo,wp_help';
	        return $in;
        }

        /*
        * Register editor buttons
        */
        public function register_buttons( $buttons ) {

            //Remove buttons
            $removes = array('revslider', 'more');

            //Find the array key and then unset
            foreach( $removes as $remove){
              if ( ( $key = array_search($remove, $buttons) ) !== false )	unset($buttons[$key]);
            }

            return $buttons;

        }

        /*
        * Add Wyde button plugins
        */
        public function add_buttons( $plugin_array ) {
            $plugin_array['wydeEditor'] = get_template_directory_uri() . '/shortcodes/js/editor-plugin.js';
            return $plugin_array;
        }

        /*
        Load plugin css and javascript files which you may need for shortcodes
        */
        public function load_shortcodes_scripts() {
            global $wyde_options;

            // Google Maps API
            wp_register_script('googlemaps', 'https://maps.googleapis.com/maps/api/js', null, null, true);
            if( $wyde_options['ajax_page'] ){
                wp_enqueue_script('googlemaps');
            }

            // Flora shortcodes styles
            wp_enqueue_style('flora-shortcodes', get_template_directory_uri() . '/shortcodes/css/shortcodes.css', null, null);

            // Flora shortcodes scripts
            wp_enqueue_script('flora-shortcodes', get_template_directory_uri() . '/shortcodes/js/shortcodes.js', array('jquery'), null, true);

        }

        /*
        Load plugin css and javascript files which you may need for backend editor
        */
        public function load_editor_scripts() {
            // Flora backend styles
            wp_enqueue_style( 'wyde-backend-style', get_template_directory_uri() . '/shortcodes/css/backend.css', null, '1.0.0');
            
            // Flora backend scripts
            wp_enqueue_script('wyde-backend-script', get_template_directory_uri(). '/shortcodes/js/backend.js', null, '1.0.0', true);
            
            // Google Maps API
            wp_enqueue_script('googlemaps', 'https://maps.googleapis.com/maps/api/js', null, null, false);
        }

        /*
         * Set the RevSlider Plugin as a Theme. This hides the activation notice and the activation area in the Slider Overview
         */
        function revslider_set_as_theme(){
            global $revSliderAsTheme;
            if( function_exists('set_revslider_as_theme') ){
                $revSliderAsTheme = true;
                update_option('revslider-valid-notice', 'off');
                add_filter('revslider_set_notifications', array($this, 'revslider_set_notifications') );
            }
        }

        /*
         * Disable update notifications
         */
        function revslider_set_notifications(){
            return 'off';
        }

        /*
        * Integrate with Visual Composer
        */
        function integrate_with_vc() {
            // Check if Visual Composer is installed
            if ( ! defined( 'WPB_VC_VERSION' ) ) {
                return;
            }

            
            include get_template_directory() .'/shortcodes/css_editor.php';

            add_action( 'vc_before_init', array($this, 'vc_before_init') );

            add_action( 'vc_after_init', array($this, 'vc_after_init') );
            add_action( 'vc_after_init_base', array($this, 'vc_after_init_base') );

            add_action( 'vc_backend_editor_enqueue_js_css', array($this, 'load_editor_scripts'));
            add_action( 'vc_frontend_editor_enqueue_js_css', array($this, 'load_editor_scripts'));

            add_action( 'init', array($this, 'deregister_grid_element'), 100);
            remove_action( 'init', 'vc_page_welcome_redirect' );

            add_filter( 'vc_google_fonts_get_fonts_filter', array($this, 'get_google_fonts'), 100 );
            add_filter( 'vc_iconpicker-type-fontawesome', array($this, 'get_font_awesome_icons'), 100 );
            add_filter( 'vc_iconpicker-type-linecons', array($this, 'get_linecons_icons'), 100 );
            add_filter( 'vc_iconpicker-type-etline', array($this, 'get_etline_icons'), 100 );
            add_filter( 'vc_iconpicker-type-flora', array($this, 'get_flora_icons') );

            add_filter( 'wyde_blog_masonry_layout', 'wyde_get_blog_masonry_layout', 50, 2);
            add_filter( 'wyde_portfolio_masonry_layout', 'wyde_get_portfolio_masonry_layout', 50, 2);

            $this->init_shortcodes();
        }


        /*
        * Initialize shortcodes
        */
        function init_shortcodes(){

            WpbakeryShortcodeParams::addField('wyde_animation', array( $this, 'animation_field'), get_template_directory_uri() .'/shortcodes/js/wyde-animation.js');
            WpbakeryShortcodeParams::addField('wyde_gmaps', array( $this, 'gmaps_field'), get_template_directory_uri() .'/shortcodes/js/wyde-gmaps.js');
        
            // Remove all Visual Composer shortcodes
            WPBMap::dropAllShortcodes();

            // Add Flora theme shortcodes
            $this->load_shortcodes();

            // Update Visual Composer shortcodes
            $this->update_shortcodes();

            // Update Plugins shortcodes
            $this->update_plugins_shortcodes();
        }

        /*
        * Add action before vc init
        */
        public function vc_before_init() {    
            //Disable automatic updates notifications
	        vc_set_as_theme(true);
            //Set Shortcodes Templates Directory
            vc_set_shortcodes_templates_dir( get_template_directory() .'/templates/shortcodes' );
            //Set Default Editor Post Types
            //vc_set_default_editor_post_types( array('page', 'post', 'wyde_portfolio') );
        }

        public function vc_after_init() {
            //remove vc edit button from admin bar
            remove_action( 'admin_bar_menu', array( vc_frontend_editor(), 'adminBarEditLink' ), 1000 );
            //remove vc edit button from wp edit links
            remove_filter( 'edit_post_link', array( vc_frontend_editor(), 'renderEditButton' ) );
            //disable frontend editor
            vc_disable_frontend(); 

        }
   
        public function vc_after_init_base() {

            /*
            global $vc_row_layouts;
            $vc_row_layouts = array(
	            array( 'cells' => '11', 'mask' => '12', 'title' => '1/1', 'icon_class' => 'col-1' ),
	            array( 'cells' => '12_12', 'mask' => '26', 'title' => '1/2 + 1/2', 'icon_class' => 'col-12-12' ),
	            array( 'cells' => '23_13', 'mask' => '29', 'title' => '2/3 + 1/3', 'icon_class' => 'col-23-13' ),
	            array( 'cells' => '13_23', 'mask' => '29', 'title' => '1/3 + 2/3', 'icon_class' => 'col-13-23' ),
	            array( 'cells' => '13_13_13', 'mask' => '312', 'title' => '1/3 + 1/3 + 1/3', 'icon_class' => 'col-13-13-13' ),
	            array( 'cells' => '14_14_14_14', 'mask' => '420', 'title' => '1/4 + 1/4 + 1/4 + 1/4', 'icon_class' => 'col-14-14-14-14' ),
	            array( 'cells' => '14_34', 'mask' => '212', 'title' => '1/4 + 3/4', 'icon_class' => 'col-14-34' ),
	            array( 'cells' => '14_12_14', 'mask' => '313', 'title' => '1/4 + 1/2 + 1/4', 'icon_class' => 'col-14-12-14' ),
	            array( 'cells' => '56_16', 'mask' => '218', 'title' => '5/6 + 1/6', 'icon_class' => 'col-56-16' ),
	            array( 'cells' => '16_16_16_16_16_16', 'mask' => '642', 'title' => '1/6 + 1/6 + 1/6 + 1/6 + 1/6 + 1/6', 'icon_class' => 'col-16-16-16-16-16-16' ),
	            array( 'cells' => '16_23_16', 'mask' => '319', 'title' => '1/6 + 4/6 + 1/6', 'icon_class' => 'col-16-46-16' ),
	            array( 'cells' => '16_16_16_12', 'mask' => '424', 'title' => '1/6 + 1/6 + 1/6 + 1/2', 'icon_class' => 'col-16-16-16-12' ),
                array( 'cells' => '15_15_15_15_15', 'mask' => '530', 'title' => '1/5 + 1/5 + 1/5 + 1/5 + 1/5', 'icon_class' => 'col-15-15-15-15-15' )
            );
            */
            $this->vc_metadata();
        }

        /*
	    * Find and include all shortcode classes within classes folder
	    */
	    public function load_shortcodes() {

		    foreach( glob( get_template_directory() . '/shortcodes/classes/*.php' ) as $filename ) {
			    require_once $filename;
		    }

	    }

        /*
        * Update VC elements
        */
        public function update_shortcodes(){
        
            $icon_picker_options = Wyde_Shortcode::get_iconpicker_options();

            /***************************************** 
            /* Row
            /*****************************************/
            vc_map( array(
	            'name' => __( 'Row', 'flora' ),
	            'base' => 'vc_row',
                'weight'    => 1001,
	            'is_container' => true,
	            'icon' => 'icon-wpb-row',
	            'show_settings_on_create' => false,
	            'category' => __( 'Content', 'flora' ),
	            'description' => __( 'Place content elements inside row', 'flora' ),
	            'params' => array(
                    array(
			            'type' => 'dropdown',
			            'heading' => __( 'Content Width', 'flora' ),
			            'param_name' => 'row_style',
			            'value' => array(
				            __( 'Default', 'flora' ) => '',
				            __( 'Full Width', 'flora' ) => 'full-width',
			            ),
			            'description' => __( 'Select content width options.', 'flora' )
		            ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Text Style', 'flora'),
                        'param_name' => 'text_style',
                        'value' => array(
                            __('Dark', 'flora') => '',
                            __('Light', 'flora') => 'light',
                        ),
                        'description' => __('Apply text style.', 'flora')
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Content Vertical Alignment', 'flora'),
                        'param_name' => 'vertical_align',
                        'value' => array(
                            __('Top', 'flora') => '', 
                            __('Middle', 'flora') =>'middle', 
                            __('Bottom', 'flora') => 'bottom', 
                        ),
                        'description' => __('Select content vertical alignment.', 'flora')
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Vertical Padding Size', 'flora'),
                        'param_name' => 'padding_size',
                        'value' => array(
                             __('Default', 'flora') => '', 
                             __('No Padding', 'flora') => 'no-padding', 
                             __('Small', 'flora') => 's-padding', 
                             __('Medium', 'flora') => 'm-padding', 
                             __('Large', 'flora') => 'l-padding', 
                             __('Extra Large', 'flora') => 'xl-padding'
                        ),
                        'description' => __('Select vertical padding size.', 'flora')
                    ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Row ID', 'flora' ),
			            'param_name' => 'el_id',
			            'description' => sprintf( __( 'Enter row ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">W3C specification</a>).', 'flora' ), 'http://www.w3schools.com/tags/att_global_id.asp' )
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            ),
                    array(
			            'type' => 'colorpicker',
			            'heading' => __( 'Background Color', 'flora' ),
			            'param_name' => 'background_color',
			            'description' => __( 'Select background color.', 'flora' ),
                        'value' => '',
                        'group' => __( 'Background', 'flora' ),
		            ),
		            array(
			            'type' => 'attach_image',
			            'heading' => __( 'Background Image', 'flora' ),
			            'param_name' => 'background_image',
			            'description' => __( 'Select background image.', 'flora' ),
                        'group' => __( 'Background', 'flora' ),
		            ),
		            array(
			            'type' => 'hidden',
			            'param_name' => 'bg_image_url',
			            'value' => '',
                        'group' => __( 'Background', 'flora' ),
		            ),
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Background Style', 'flora' ),
			            'param_name' => 'background_style',
			            'value' => array(
				            __( 'Default', 'flora' ) => '',
				            __( 'Cover', 'flora' ) => 'cover',
				            __( 'Contain', 'flora' ) => 'contain',
				            __( 'No Repeat', 'flora' ) => 'no-repeat',
				            __( 'Repeat', 'flora' ) => 'repeat',
			            ),
			            'description' => __( 'Select background style.', 'flora' ),
                        'dependency' => array(
				            'element' => 'background_image',
				            'not_empty' => true,
                            'callback' => 'wyde_row_background_image_callback',
			            ),
                        'group' => __( 'Background', 'flora' ),

		            ),
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Parallax', 'flora' ),
			            'param_name' => 'parallax',
			            'value' => array(
				            __( 'None', 'flora' ) => '',
				            __( 'Simple Parallax', 'flora' ) => 'parallax',
				            __( 'Reverse Parallax', 'flora' ) => 'reverse',
				            __( 'Parallax with Fade', 'flora' ) => 'fade',
			            ),
			            'description' => __( 'Select parallax background type.', 'flora' ),
                        'dependency' => array(
				            'element' => 'background_image',
				            'not_empty' => true,
			            ),
                        'group' => __( 'Background', 'flora' ),

		            ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Background Overlay', 'flora'),
                        'param_name' => 'background_overlay',
                        'value' => array(
                            __('None', 'flora', 'flora') => '',
                            __('Color Overlay', 'flora') => 'color',
                        ),
                        'description' => __('Apply an overlay to the background.', 'flora'),
                        'dependency' => array(
				            'element' => 'background_image',
				            'not_empty' => true,
			            ),
                        'group' => __( 'Background', 'flora' ),
                    ),
                    array(
			            'type' => 'colorpicker',
			            'heading' => __( 'Background Overlay Color', 'flora' ),
			            'param_name' => 'overlay_color',
			            'description' => __( 'Select background overlay color.', 'flora' ),
                        'value' => '#211F1E',
                        'dependency' => array(
				            'element' => 'background_overlay',
				            'not_empty' => true
			            ),
                        'group' => __( 'Background', 'flora' ),
		            ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Background Overlay Opacity', 'flora'),
                        'param_name' => 'overlay_opacity',
                        'value' => array(
                            __('Default', 'flora') => '', 
                            '0.1' => '0.1', 
                            '0.2' => '0.2', 
                            '0.3' => '0.3', 
                            '0.4' => '0.4', 
                            '0.5' => '0.5', 
                            '0.6' => '0.6', 
                            '0.7' => '0.7', 
                            '0.8' => '0.8', 
                            '0.9' => '0.9', 
                        ),
                        'description' => __('Select background overlay opacity.', 'flora'),
                        'dependency' => array(
				            'element' => 'background_overlay',
				            'not_empty' => true
			            ),
                        'group' => __( 'Background', 'flora' ),
                    ),
		            array(
			            'type' => 'css_editor',
			            'heading' => __( 'CSS', 'flora' ),
			            'param_name' => 'css',
			            'group' => __( 'Design Options', 'flora' )
		            )
	            ),
	            'js_view' => 'WydeRowView'
            ) );

            /***************************************** 
            /* Row Inner
            /*****************************************/
            vc_map( array(
	            'name' => __( 'Row', 'flora' ), //Inner Row
	            'base' => 'vc_row_inner',
	            'content_element' => false,
	            'is_container' => true,
	            'icon' => 'icon-wpb-row',
	            'weight' => 1000,
	            'show_settings_on_create' => false,
	            'description' => __( 'Place content elements inside the row', 'flora' ),
	            'params' => array(
                        array(
                            'type' => 'dropdown',
                            'class' => '',
                            'heading' => __('Text Style', 'flora'),
                            'param_name' => 'text_style',
                            'value' => array(
                                __('Dark', 'flora') => '',
                                __('Light', 'flora') => 'light',
                            ),
                            'description' => __('Apply text style.', 'flora')
                        ),
                        array(
                            'type' => 'dropdown',
                            'class' => '',
                            'heading' => __('Content Vertical Alignment', 'flora'),
                            'param_name' => 'vertical_align',
                            'value' => array(
                                __('Top', 'flora') => '', 
                                __('Middle', 'flora') =>'middle', 
                                __('Bottom', 'flora') => 'bottom', 
                            ),
                            'description' => __('Select content (columns) vertical alignment.', 'flora')
                        ),
                        array(
                            'type' => 'dropdown',
                            'class' => '',
                            'heading' => __('Vertical Padding Size', 'flora'),
                            'param_name' => 'padding_size',
                            'value' => array(
                                 __('Default', 'flora') => '', 
                                 __('No Padding', 'flora') => 'no-padding', 
                                 __('Small', 'flora') => 's-padding', 
                                 __('Medium', 'flora') => 'm-padding', 
                                 __('Large', 'flora') => 'l-padding', 
                                 __('Extra Large', 'flora') => 'xl-padding'
                            ),
                            'description' => __('Select vertical padding size.', 'flora')
                        ),
		                array(
			                'type' => 'textfield',
			                'heading' => __( 'Row ID', 'flora' ),
			                'param_name' => 'el_id',
			                'description' => sprintf( __( 'Enter row ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">W3C specification</a>).', 'flora' ), 'http://www.w3schools.com/tags/att_global_id.asp' )
		                ),
		                array(
			                'type' => 'textfield',
			                'heading' => __( 'Extra CSS Class', 'flora' ),
			                'param_name' => 'el_class',
			                'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		                ),
                        array(
			                'type' => 'colorpicker',
			                'heading' => __( 'Background Color', 'flora' ),
			                'param_name' => 'background_color',
			                'description' => __( 'Select background color.', 'flora' ),
                            'value' => '',
                            'group' => __( 'Background', 'flora' ),
		                ),
		                array(
			                'type' => 'attach_image',
			                'heading' => __( 'Background Image', 'flora' ),
			                'param_name' => 'background_image',
			                'description' => __( 'Select background image.', 'flora' ),
                            'group' => __( 'Background', 'flora' ),
		                ),
		                array(
			                'type' => 'hidden',
			                'param_name' => 'bg_image_url',
			                'value' => '',
                            'group' => __( 'Background', 'flora' ),
		                ),
		                array(
			                'type' => 'dropdown',
			                'heading' => __( 'Background Style', 'flora' ),
			                'param_name' => 'background_style',
			                'value' => array(
				                __( 'Default', 'flora' ) => '',
				                __( 'Cover', 'flora' ) => 'cover',
				                __( 'Contain', 'flora' ) => 'contain',
				                __( 'No Repeat', 'flora' ) => 'no-repeat',
				                __( 'Repeat', 'flora' ) => 'repeat',
			                ),
			                'description' => __( 'Select background style.', 'flora' ),
                            'dependency' => array(
				                'element' => 'background_image',
				                'not_empty' => true,
                                'callback' => 'wyde_row_background_image_callback',
			                ),
                            'group' => __( 'Background', 'flora' ),

		                ),
                        array(
                            'type' => 'dropdown',
                            'class' => '',
                            'heading' => __('Background Overlay', 'flora'),
                            'param_name' => 'background_overlay',
                            'value' => array(
                                __('None', 'flora', 'flora') => '',
                                __('Color Overlay', 'flora') => 'color',
                            ),
                            'description' => __('Apply an overlay to the background.', 'flora'),
                            'dependency' => array(
				                'element' => 'background_image',
				                'not_empty' => true,
			                ),
                            'group' => __( 'Background', 'flora' ),
                        ),
                        array(
			                'type' => 'colorpicker',
			                'heading' => __( 'Background Overlay Color', 'flora' ),
			                'param_name' => 'overlay_color',
			                'description' => __( 'Select background overlay color.', 'flora' ),
                            'value' => '#211F1E',
                            'dependency' => array(
				                'element' => 'background_overlay',
				                'not_empty' => true
			                ),
                            'group' => __( 'Background', 'flora' ),
		                ),
                        array(
                            'type' => 'dropdown',
                            'class' => '',
                            'heading' => __('Background Overlay Opacity', 'flora'),
                            'param_name' => 'overlay_opacity',
                            'value' => array(
                                __('Default', 'flora') => '', 
                                '0.1' => '0.1', 
                                '0.2' => '0.2', 
                                '0.3' => '0.3', 
                                '0.4' => '0.4', 
                                '0.5' => '0.5', 
                                '0.6' => '0.6', 
                                '0.7' => '0.7', 
                                '0.8' => '0.8', 
                                '0.9' => '0.9', 
                            ),
                            'description' => __('Select background overlay opacity.', 'flora'),
                            'dependency' => array(
				                'element' => 'background_overlay',
				                'not_empty' => true
			                ),
                            'group' => __( 'Background', 'flora' ),
                        ),
		                array(
			                'type' => 'css_editor',
			                'heading' => __( 'CSS', 'flora' ),
			                'param_name' => 'css',
			                'group' => __( 'Design Options', 'flora' )
		                )
	            ),
	            'js_view' => 'WydeRowView'
            ) );

            /***************************************** 
            /* Column
            /*****************************************/
            vc_map( array(
	            'name' => __( 'Column', 'flora' ),
	            'base' => 'vc_column',
	            'is_container' => true,
	            'content_element' => false,
	            'params' => array(
		            array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Text Style', 'flora'),
                        'param_name' => 'text_style',
                        'value' => array(
                            __('Dark', 'flora') => '',
                            __('Light', 'flora') => 'light',
                            __('Custom', 'flora') => 'custom',
                        ),
                        'description' => __('Apply text style.', 'flora')
                    ),
                    array(
                        'type' => 'colorpicker',
                        'class' => '',
                        'param_name' => 'text_color',
                        'heading' => __('Text Color', 'flora'),
                        'description' => __('Choose column text color.', 'flora'),
                        'dependency' => array(
				            'element' => 'text_style',
				            'value' => array('custom')
			            )
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Text Alignment', 'flora'),
                        'param_name' => 'text_align',
                        'value' => array(
                            __('Left', 'flora') => '', 
                            __('Center', 'flora') =>'center', 
                            __('Right', 'flora') => 'right', 
                        ),
                        'description' => __('Select text alignment.', 'flora')
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Padding Size', 'flora'),
                        'param_name' => 'padding_size',
                        'value' => array(
                            __('Default', 'flora') => '', 
                            __('Small', 'flora') => 's-padding', 
                            __('Large', 'flora') => 'l-padding', 
                            __('No Padding', 'flora') => 'no-padding', 
                        ),
                        'description' => __('Select padding size.', 'flora')
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
			            'type' => 'colorpicker',
			            'heading' => __( 'Background Color', 'flora' ),
			            'param_name' => 'background_color',
			            'description' => __( 'Select background color.', 'flora' ),
                        'value' => '',
                        'group' => __( 'Background', 'flora' ),
		            ),
		            array(
			            'type' => 'attach_image',
			            'heading' => __( 'Background Image', 'flora' ),
			            'param_name' => 'background_image',
			            'description' => __( 'Select background image.', 'flora' ),
                        'group' => __( 'Background', 'flora' ),
		            ),
		            array(
			            'type' => 'hidden',
			            'param_name' => 'bg_image_url',
			            'value' => '',
                        'group' => __( 'Background', 'flora' ),
		            ),
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Background Style', 'flora' ),
			            'param_name' => 'background_style',
			            'value' => array(
				            __( 'Default', 'flora' ) => '',
				            __( 'Cover', 'flora' ) => 'cover',
				            __( 'Contain', 'flora' ) => 'contain',
				            __( 'No Repeat', 'flora' ) => 'no-repeat',
				            __( 'Repeat', 'flora' ) => 'repeat',
			            ),
			            'description' => __( 'Select background style.', 'flora' ),
                        'dependency' => array(
				            'element' => 'background_image',
				            'not_empty' => true,
                            'callback' => 'wyde_column_background_image_callback',
			            ),
                        'group' => __( 'Background', 'flora' ),

		            ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Background Overlay', 'flora'),
                        'param_name' => 'background_overlay',
                        'value' => array(
                            __('None', 'flora', 'flora') => '',
                            __('Color Overlay', 'flora') => 'color',
                        ),
                        'description' => __('Apply an overlay to the background.', 'flora'),
                        'dependency' => array(
				            'element' => 'background_image',
				            'not_empty' => true,
			            ),
                        'group' => __( 'Background', 'flora' ),
                    ),
                    array(
			            'type' => 'colorpicker',
			            'heading' => __( 'Background Overlay Color', 'flora' ),
			            'param_name' => 'overlay_color',
			            'description' => __( 'Select background overlay color.', 'flora' ),
                        'value' => '#211F1E',
                        'dependency' => array(
				            'element' => 'background_overlay',
				            'not_empty' => true
			            ),
                        'group' => __( 'Background', 'flora' ),
		            ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Background Overlay Opacity', 'flora'),
                        'param_name' => 'overlay_opacity',
                        'value' => array(
                            __('Default', 'flora') => '', 
                            '0.1' => '0.1', 
                            '0.2' => '0.2', 
                            '0.3' => '0.3', 
                            '0.4' => '0.4', 
                            '0.5' => '0.5', 
                            '0.6' => '0.6', 
                            '0.7' => '0.7', 
                            '0.8' => '0.8', 
                            '0.9' => '0.9', 
                        ),
                        'description' => __('Select background overlay opacity.', 'flora'),
                        'dependency' => array(
				            'element' => 'background_overlay',
				            'not_empty' => true
			            ),
                        'group' => __( 'Background', 'flora' ),
                    ),
		            array(
			            'type' => 'css_editor',
			            'heading' => __( 'CSS', 'flora' ),
			            'param_name' => 'css',
			            'group' => __( 'Design Options', 'flora' )
		            ),
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Width', 'flora' ),
			            'param_name' => 'width',
			            'value' => array(
	                        __( '1 column - 1/12', 'flora' ) => '1/12',
	                        __( '2 columns - 1/6', 'flora' ) => '1/6',
	                        __( '3 columns - 1/4', 'flora' ) => '1/4',
	                        __( '4 columns - 1/3', 'flora' ) => '1/3',
	                        __( '5 columns - 5/12', 'flora' ) => '5/12',
	                        __( '6 columns - 1/2', 'flora' ) => '1/2',
	                        __( '7 columns - 7/12', 'flora' ) => '7/12',
	                        __( '8 columns - 2/3', 'flora' ) => '2/3',
	                        __( '9 columns - 3/4', 'flora' ) => '3/4',
	                        __( '10 columns - 5/6', 'flora' ) => '5/6',
	                        __( '11 columns - 11/12', 'flora' ) => '11/12',
	                        __( '12 columns - 1/1', 'flora' ) => '1/1',
                        ),
			            'group' => __( 'Responsive Options', 'flora' ),
			            'description' => __( 'Select column width.', 'flora' ),
			            'std' => '1/1'
		            ),
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Width on small screen', 'flora' ),
			            'param_name' => 'width_sm',
			            'value' => array(
                            __( 'Default', 'flora' ) => '',
	                        __( '1 column - 1/12', 'flora' ) => '1/12',
	                        __( '2 columns - 1/6', 'flora' ) => '1/6',
	                        __( '3 columns - 1/4', 'flora' ) => '1/4',
	                        __( '4 columns - 1/3', 'flora' ) => '1/3',
	                        __( '5 columns - 5/12', 'flora' ) => '5/12',
	                        __( '6 columns - 1/2', 'flora' ) => '1/2',
	                        __( '7 columns - 7/12', 'flora' ) => '7/12',
	                        __( '8 columns - 2/3', 'flora' ) => '2/3',
	                        __( '9 columns - 3/4', 'flora' ) => '3/4',
	                        __( '10 columns - 5/6', 'flora' ) => '5/6',
	                        __( '11 columns - 11/12', 'flora' ) => '11/12',
	                        __( '12 columns - 1/1', 'flora' ) => '1/1'
                        ),
			            'group' => __( 'Responsive Options', 'flora' ),
			            'description' => __( 'Select column width on small screen (Tablets).', 'flora' ),
		            ),
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Offset', 'flora' ),
			            'param_name' => 'column_offset',
			            'value' => array(
	                        __( 'None', 'flora' ) => '',
	                        __( '1 column - 1/12', 'flora' ) => '1',
	                        __( '2 columns - 1/6', 'flora' ) => '2',
	                        __( '3 columns - 1/4', 'flora' ) => '3',
	                        __( '4 columns - 1/3', 'flora' ) => '4',
	                        __( '5 columns - 5/12', 'flora' ) => '5',
	                        __( '6 columns - 1/2', 'flora' ) => '6',
	                        __( '7 columns - 7/12', 'flora' ) => '7',
	                        __( '8 columns - 2/3', 'flora' ) => '8',
	                        __( '9 columns - 3/4', 'flora' ) => '9',
	                        __( '10 columns - 5/6', 'flora' ) => '10',
	                        __( '11 columns - 11/12', 'flora' ) => '11',
	                        __( '12 columns - 1/1', 'flora' ) => '12'
                        ),
			            'group' => __( 'Responsive Options', 'flora' ),
			            'description' => __( 'Select column offset. This value will not be used on Tablets and Mobiles.', 'flora' ),
		            )
	            ),
	            'js_view' => 'WydeColumnView'
            ) );

        
            /***************************************** 
            /* Column Inner
            /*****************************************/
            vc_map( array(
	            'name' => __( 'Column', 'flora' ),
	            'base' => 'vc_column_inner',
	            'class' => '',
	            'icon' => '',
	            'wrapper_class' => '',
	            'controls' => 'full',
	            'allowed_container_element' => false,
	            'content_element' => false,
	            'is_container' => true,
	            'params' => array(
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Text Style', 'flora'),
                        'param_name' => 'text_style',
                        'value' => array(
                            __('Dark', 'flora') => '',
                            __('Light', 'flora') => 'light',
                            __('Custom', 'flora') => 'custom',
                        ),
                        'description' => __('Apply text style.', 'flora')
                    ),
                    array(
                        'type' => 'colorpicker',
                        'class' => '',
                        'param_name' => 'text_color',
                        'heading' => __('Text Color', 'flora'),
                        'description' => __('Choose column text color.', 'flora'),
                        'dependency' => array(
				            'element' => 'text_style',
				            'value' => array('custom')
			            )
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Text Alignment', 'flora'),
                        'param_name' => 'text_align',
                        'value' => array(
                            __('Left', 'flora') => '', 
                            __('Center', 'flora') =>'center', 
                            __('Right', 'flora') => 'right', 
                        ),
                        'description' => __('Select text alignment.', 'flora')
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Padding Size', 'flora'),
                        'param_name' => 'padding_size',
                        'value' => array(
                            __('Default', 'flora') => '', 
                            __('Small', 'flora') => 's-padding', 
                            __('Large', 'flora') => 'l-padding', 
                            __('No Padding', 'flora') => 'no-padding', 
                        ),
                        'description' => __('Select padding size.', 'flora')
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
			            'type' => 'colorpicker',
			            'heading' => __( 'Background Color', 'flora' ),
			            'param_name' => 'background_color',
			            'description' => __( 'Select background color.', 'flora' ),
                        'value' => '',
                        'group' => __( 'Background', 'flora' ),
		            ),
		            array(
			            'type' => 'attach_image',
			            'heading' => __( 'Background Image', 'flora' ),
			            'param_name' => 'background_image',
			            'description' => __( 'Select background image.', 'flora' ),
                        'group' => __( 'Background', 'flora' ),
		            ),
		            array(
			            'type' => 'hidden',
			            'param_name' => 'bg_image_url',
			            'value' => '',
                        'group' => __( 'Background', 'flora' ),
		            ),
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Background Style', 'flora' ),
			            'param_name' => 'background_style',
			            'value' => array(
				            __( 'Default', 'flora' ) => '',
				            __( 'Cover', 'flora' ) => 'cover',
				            __( 'Contain', 'flora' ) => 'contain',
				            __( 'No Repeat', 'flora' ) => 'no-repeat',
				            __( 'Repeat', 'flora' ) => 'repeat',
			            ),
			            'description' => __( 'Select background style.', 'flora' ),
                        'dependency' => array(
				            'element' => 'background_image',
				            'not_empty' => true,
                            'callback' => 'wyde_column_background_image_callback',
			            ),
                        'group' => __( 'Background', 'flora' ),

		            ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Background Overlay', 'flora'),
                        'param_name' => 'background_overlay',
                        'value' => array(
                            __('None', 'flora', 'flora') => '',
                            __('Color Overlay', 'flora') => 'color',
                        ),
                        'description' => __('Apply an overlay to the background.', 'flora'),
                        'dependency' => array(
				            'element' => 'background_image',
				            'not_empty' => true,
			            ),
                        'group' => __( 'Background', 'flora' ),
                    ),
                    array(
			            'type' => 'colorpicker',
			            'heading' => __( 'Background Overlay Color', 'flora' ),
			            'param_name' => 'overlay_color',
			            'description' => __( 'Select background overlay color.', 'flora' ),
                        'value' => '#211F1E',
                        'dependency' => array(
				            'element' => 'background_overlay',
				            'not_empty' => true
			            ),
                        'group' => __( 'Background', 'flora' ),
		            ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Background Overlay Opacity', 'flora'),
                        'param_name' => 'overlay_opacity',
                        'value' => array(
                            __('Default', 'flora') => '', 
                            '0.1' => '0.1', 
                            '0.2' => '0.2', 
                            '0.3' => '0.3', 
                            '0.4' => '0.4', 
                            '0.5' => '0.5', 
                            '0.6' => '0.6', 
                            '0.7' => '0.7', 
                            '0.8' => '0.8', 
                            '0.9' => '0.9', 
                        ),
                        'description' => __('Select background overlay opacity.', 'flora'),
                        'dependency' => array(
				            'element' => 'background_overlay',
				            'not_empty' => true
			            ),
                        'group' => __( 'Background', 'flora' ),
                    ),
		            array(
			            'type' => 'css_editor',
			            'heading' => __( 'CSS', 'flora' ),
			            'param_name' => 'css',
			            'group' => __( 'Design Options', 'flora' )
		            ),
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Width', 'flora' ),
			            'param_name' => 'width',
			            'value' => array(
	                        __( '1 column - 1/12', 'flora' ) => '1/12',
	                        __( '2 columns - 1/6', 'flora' ) => '1/6',
	                        __( '3 columns - 1/4', 'flora' ) => '1/4',
	                        __( '4 columns - 1/3', 'flora' ) => '1/3',
	                        __( '5 columns - 5/12', 'flora' ) => '5/12',
	                        __( '6 columns - 1/2', 'flora' ) => '1/2',
	                        __( '7 columns - 7/12', 'flora' ) => '7/12',
	                        __( '8 columns - 2/3', 'flora' ) => '2/3',
	                        __( '9 columns - 3/4', 'flora' ) => '3/4',
	                        __( '10 columns - 5/6', 'flora' ) => '5/6',
	                        __( '11 columns - 11/12', 'flora' ) => '11/12',
	                        __( '12 columns - 1/1', 'flora' ) => '1/1'
                        ),
			            'group' => __( 'Responsive Options', 'flora' ),
			            'description' => __( 'Select column width.', 'flora' ),
			            'std' => '1/1'
		            ),
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Width on small screen', 'flora' ),
			            'param_name' => 'width_sm',
			            'value' => array(
                        	__( 'Default', 'flora' ) => '',
	                        __( '1 column - 1/12', 'flora' ) => '1/12',
	                        __( '2 columns - 1/6', 'flora' ) => '1/6',
	                        __( '3 columns - 1/4', 'flora' ) => '1/4',
	                        __( '4 columns - 1/3', 'flora' ) => '1/3',
	                        __( '5 columns - 5/12', 'flora' ) => '5/12',
	                        __( '6 columns - 1/2', 'flora' ) => '1/2',
	                        __( '7 columns - 7/12', 'flora' ) => '7/12',
	                        __( '8 columns - 2/3', 'flora' ) => '2/3',
	                        __( '9 columns - 3/4', 'flora' ) => '3/4',
	                        __( '10 columns - 5/6', 'flora' ) => '5/6',
	                        __( '11 columns - 11/12', 'flora' ) => '11/12',
	                        __( '12 columns - 1/1', 'flora' ) => '1/1'
                        ),
			            'group' => __( 'Responsive Options', 'flora' ),
			            'description' => __( 'Select column width on small screen (Tablets).', 'flora' ),
		            ),
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Offset', 'flora' ),
			            'param_name' => 'column_offset',
			            'value' => array(
	                        __( 'None', 'flora' ) => '',
	                        __( '1 column - 1/12', 'flora' ) => '1',
	                        __( '2 columns - 1/6', 'flora' ) => '2',
	                        __( '3 columns - 1/4', 'flora' ) => '3',
	                        __( '4 columns - 1/3', 'flora' ) => '4',
	                        __( '5 columns - 5/12', 'flora' ) => '5',
	                        __( '6 columns - 1/2', 'flora' ) => '6',
	                        __( '7 columns - 7/12', 'flora' ) => '7',
	                        __( '8 columns - 2/3', 'flora' ) => '8',
	                        __( '9 columns - 3/4', 'flora' ) => '9',
	                        __( '10 columns - 5/6', 'flora' ) => '10',
	                        __( '11 columns - 11/12', 'flora' ) => '11',
	                        __( '12 columns - 1/1', 'flora' ) => '12'
                        ),
			            'group' => __( 'Responsive Options', 'flora' ),
			            'description' => __( 'Select column offset. This value will not be used on Tablets and Mobiles.', 'flora' ),
		            )
	            ),
	            'js_view' => 'WydeColumnView'
            ) );


            /***************************************** 
            /* Text Block
            /*****************************************/
            vc_map( array(
	            'name' => __( 'Text Block', 'flora' ),
	            'base' => 'vc_column_text',
                'weight'    => 1000,
	            'icon' => 'wyde-icon text-block-icon',
	            'wrapper_class' => 'clearfix',
	            'category' => __( 'Content', 'flora' ),
	            'description' => __( 'A block of text with WYSIWYG editor', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'textarea_html',
			            'holder' => 'div',
			            'heading' => __( 'Text', 'flora' ),
			            'param_name' => 'content',
			            'value' => __( '<p>I am text block. Click edit button to change this text.</p>', 'flora' )
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


            /* Empty Space
            ---------------------------------------------------------- */
            vc_map( array(
	            'name' => __( 'Empty Space', 'flora' ),
	            'base' => 'vc_empty_space',
	            'icon' => 'wyde-icon empty-space-icon',
	            'show_settings_on_create' => true,
                'weight'    => 1000,
	            'category' => __( 'Content', 'flora' ),
	            'description' => __( 'Blank space with custom height', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Height', 'flora' ),
			            'param_name' => 'height',
			            'value' => '30px',
			            'admin_label' => true,
			            'description' => __( 'Enter empty space height (Note: CSS measurement units allowed).', 'flora' ),
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            ),
	            ),
            ) );
         

            /* Raw HTML
            ---------------------------------------------------------- */
            vc_map( array(
	            'name' => __( 'Raw HTML', 'flora' ),
	            'base' => 'vc_raw_html',
	            'icon' => 'icon-wpb-raw-html',
	            'category' => __( 'Structure', 'flora' ),
	            'wrapper_class' => 'clearfix',
	            'description' => __( 'Output raw HTML code on your page', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'textarea_raw_html',
			            'holder' => 'div',
			            'heading' => __( 'Raw HTML', 'flora' ),
			            'param_name' => 'content',
			            'value' => '',
			            'description' => __( 'Enter your HTML content.', 'flora' )
		            ),
	            )
            ) );


            /* Raw JS
            ---------------------------------------------------------- */
            vc_map( array(
	            'name' => __( 'Raw JS', 'flora' ),
	            'base' => 'vc_raw_js',
	            'icon' => 'icon-wpb-raw-javascript',
	            'category' => __( 'Structure', 'flora' ),
	            'wrapper_class' => 'clearfix',
	            'description' => __( 'Output raw JavaScript code on your page', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'textarea_raw_html',
			            'holder' => 'div',
			            'heading' => __( 'JavaScript Code', 'flora' ),
			            'param_name' => 'content',
			            'value' => '',
			            'description' => __( 'Enter your JavaScript code.', 'flora' )
		            ),
	            )
            ) );


            /* Single Image
            ---------------------------------------------------------- */
            vc_map( array(
	            'name' => __( 'Single Image', 'flora' ),
	            'base' => 'vc_single_image',
	            'icon' => 'wyde-icon image-icon',
                'weight'    => 998,
	            'category' => 'Flora',
	            'description' => __( 'Insert an image', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'attach_image',
			            'heading' => __( 'Image', 'flora' ),
			            'param_name' => 'image',
			            'value' => '',
			            'description' => __( 'Select image from media library.', 'flora' )
		            ),
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Image Size', 'flora' ),
			            'param_name' => 'img_size',
			            'value' => array(
				            __('Thumbnail (150x150)', 'flora' ) => 'thumbnail',
				            __('Medium (300x300)', 'flora' ) => 'medium',
				            __('Large (640x640)', 'flora' ) => 'large',
				            __('Extra Large (960x960)', 'flora' ) => 'x-large',
                            __('Full Width (1280x720)', 'flora' ) => 'full-width',
                            __('Original', 'flora' ) => 'full',
			            ),
                        'std'   => 'full',
			            'description' => __( 'Select image size.', 'flora' )
		            ),
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Image Style', 'flora' ),
			            'param_name' => 'style',
                        'admin_label' => true,
			            'value' => array(
		                    __('Default', 'flora' ) => '',
		                    __('Border', 'flora' ) => 'border',
		                    __('Outline', 'flora' ) => 'outline',
		                    __('Shadow', 'flora' ) => 'shadow',
		                    __('Round', 'flora' ) => 'round',
		                    __('Round Border', 'flora' ) => 'round-border',
		                    __('Round Outline', 'flora' ) => 'round-outline', 
		                    __('Round Shadow', 'flora' ) => 'round-shadow', 
		                    __('Circle', 'flora' ) => 'circle', 
		                    __('Circle Border', 'flora' ) => 'circle-border', 
		                    __('Circle Outline', 'flora' ) => 'circle-outline',
		                    __('Circle Shadow', 'flora' ) => 'circle-shadow',
	                    ),
			            'description' => __( 'Select image alignment.', 'flora' )
		            ),
		            array(
			            'type' => 'colorpicker',
			            'heading' => __( 'Border Color', 'flora' ),
			            'param_name' => 'border_color',
			            'description' => __( 'Select image border color.', 'flora' ),
			            'dependency' => array(
				            'element' => 'style',
				            'value' => array( 'border', 'outline', 'round-border', 'round-outline', 'circle-border', 'circle-outline' )
			            )
		            ),
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Image Alignment', 'flora' ),
			            'param_name' => 'alignment',
			            'value' => array(
				            __( 'Align Left', 'flora' ) => 'left',
				            __( 'Align Center', 'flora' ) => 'center',
                            __( 'Align Right', 'flora' ) => 'right',
			            ),
			            'description' => __( 'Select image alignment.', 'flora' )
		            ),
		            array(
			            'type' => 'checkbox',
			            'heading' => __( 'Responsive', 'flora' ),
			            'param_name' => 'responsive',
			            'value' => array( __( 'Allow image to auto resize to fit in the container.', 'flora' ) => 'true' )
		            ),
		            array(
			            'type' => 'checkbox',
			            'heading' => __( 'Image Link', 'flora' ),
			            'param_name' => 'link_large',
			            'value' => array( __( 'Link to the larger image', 'flora' ) => 'true' )
		            ),
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Link Target', 'flora' ),
			            'param_name' => 'link_target',
			            'value' => array(
                            __( 'Pretty Photo', 'flora' ) => "prettyphoto",
	                        __( 'Same window', 'flora' ) => '_self',
	                        __( 'New window', 'flora' ) => "_blank",
                        ),
                        'dependency' => array(
				            'element' => 'link_large',
				            'value' => array('true')
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
			            'heading' => __( 'Css', 'flora' ),
			            'param_name' => 'css',
			            'group' => __( 'Design Options', 'flora' )
                    ) 
                )
            ) );
        

            /* Tabs
            ---------------------------------------------------------- */
            $tab_id_1 = 'def' . time() . '-1-' . rand( 0, 100 );
            $tab_id_2 = 'def' . time() . '-2-' . rand( 0, 100 );
            vc_map( array(
	            'name' => __( 'Tabs', 'flora' ),
	            'base' => 'vc_tabs',
	            'show_settings_on_create' => false,
	            'is_container' => true,
	            'icon' => 'icon-wpb-ui-tab-content',
	            'category' => __( 'Content', 'flora' ),
	            'description' => __( 'Tabbed content', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Auto rotate tabs', 'flora' ),
			            'param_name' => 'interval',
			            'value' => array( __( 'Disable', 'flora' ) => 0, 3, 5, 10, 15 ),
			            'std' => 0,
			            'description' => __( 'Auto rotate tabs each X seconds.', 'flora' )
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            )
	            ),
	            'custom_markup' => '<div class="wpb_tabs_holder wpb_holder vc_container_for_children"><ul class="tabs_controls"></ul>%content%</div>',
	            'default_content' => '[vc_tab title="' . __( 'Tab 1', 'flora' ) . '" tab_id="' . $tab_id_1 . '"][/vc_tab][vc_tab title="' . __( 'Tab 2', 'flora' ) . '" tab_id="' . $tab_id_2 . '"][/vc_tab]',
	            'js_view' => 'VcTabsView'
            ) );


            /* Tour
            ---------------------------------------------------------- */
            $tab_id_1 = time() . '-1-' . rand( 0, 100 );
            $tab_id_2 = time() . '-2-' . rand( 0, 100 );
            vc_map( array(
	            'name' => __( 'Tour', 'flora' ),
	            'base' => 'vc_tour',
	            'show_settings_on_create' => false,
	            'is_container' => true,
	            'container_not_allowed' => true,
	            'icon' => 'icon-wpb-ui-tab-content-vertical',
	            'category' => __( 'Content', 'flora' ),
	            'wrapper_class' => 'vc_clearfix',
	            'description' => __( 'Vertical tabbed content', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Auto rotate slides', 'flora' ),
			            'param_name' => 'interval',
			            'value' => array( __( 'Disable', 'flora' ) => 0, 3, 5, 10, 15 ),
			            'std' => 0,
			            'description' => __( 'Auto rotate slides each X seconds.', 'flora' )
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            )
	            ),
	            'custom_markup' => '<div class="wpb_tabs_holder wpb_holder vc_clearfix vc_container_for_children"><ul class="tabs_controls"></ul>%content%</div>',
	            'default_content' => '[vc_tab title="' . __( 'Tab 1', 'flora' ) . '" tab_id="' . $tab_id_1 . '"][/vc_tab][vc_tab title="' . __( 'Tab 2', 'flora' ) . '" tab_id="' . $tab_id_2 . '"][/vc_tab]',
	            'js_view' => 'VcTabsView'
            ) );


            /* Tab section
            ---------------------------------------------------------- */
            vc_map( array(
	            'name' => __( 'Tab', 'flora' ),
	            'base' => 'vc_tab',
	            'allowed_container_element' => 'vc_row',
	            'is_container' => true,
	            'content_element' => false,
	            'params' => array(
                        array(
			                'type' => 'textfield',
			                'heading' => __( 'Title', 'flora' ),
			                'param_name' => 'title',
			                'description' => __( 'Tab title.', 'flora' ),
		                ),
		                array(
			                'type' => 'tab_id',
			                'heading' => __( 'Tab ID', 'flora' ),
			                'param_name' => "tab_id"
		                ),

	            ),
	            'js_view' => 'VcTabView'
            ) );


            /* Accordion
            ---------------------------------------------------------- */
            vc_map( array(
	            'name' => __( 'Accordion', 'flora' ),
	            'base' => 'vc_accordion',
	            'show_settings_on_create' => false,
	            'is_container' => true,
	            'icon' => 'wyde-icon accordion-icon',
                'weight'    => 990,
	            'category' => __( 'Content', 'flora' ),
	            'description' => __( 'Collapsible content panels', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Active section', 'flora' ),
			            'param_name' => 'active_tab',
			            'description' => __( 'Enter section number to be active on load or enter "false" to collapse all sections.', 'flora' )
		            ),
		            array(
			            'type' => 'checkbox',
			            'heading' => __( 'Collapse all', 'flora' ),
			            'param_name' => 'collapsible',
			            'value' => array( __( 'Allow collapse all sections', 'flora' ) => 'yes' )
		            ),
                    array(
                        'type' => 'colorpicker',
                        'class' => '',
                        'param_name' => 'color',
                        'heading' => __('Color', 'flora'),
                        'description' => __('Choose color.', 'flora')
                    ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            )
	            ),
	            'custom_markup' => '<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">%content%</div>
                <div class="tab_controls">
                    <a class="add_tab" title="' . __( 'Add section', 'flora' ) . '"><span class="vc_icon"></span> <span class="tab-label">' . __( 'Add section', 'flora' ) . '</span></a>
                </div>',
	            'default_content' => '[vc_accordion_tab title="' . __( 'Section 1', 'flora' ) . '"][/vc_accordion_tab]
                [vc_accordion_tab title="' . __( 'Section 2', 'flora' ) . '"][/vc_accordion_tab]',
	            'js_view' => 'VcAccordionView'
            ) );



            /* Accordion Section
            ---------------------------------------------------------- */
            vc_map( array(
	            'name' => __( 'Section', 'flora' ),
	            'base' => 'vc_accordion_tab',
	            'allowed_container_element' => 'vc_row',
	            'is_container' => true,
	            'content_element' => false,
	            'params' => array(
                        $icon_picker_options[0],
                        $icon_picker_options[1],
                        $icon_picker_options[2],
                        $icon_picker_options[3],
                        $icon_picker_options[4],
                        $icon_picker_options[5],
                        array(
			                'type' => 'textfield',
			                'heading' => __( 'Title', 'flora' ),
			                'param_name' => 'title',
			                'description' => __( 'Tab title.', 'flora' ),
		                ),
		                array(
			                'type' => 'textfield',
			                'heading' => __( 'Extra CSS Class', 'flora' ),
			                'param_name' => 'el_class',
			                'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		                ),
	            ),
	            'js_view' => 'WydeAccordionTabView'
            ) );


            /* Custom Heading
            ----------------------------------------------------------- */
            vc_map( array(
	            'name' => __( 'Custom Heading', 'flora' ),
	            'base' => 'vc_custom_heading',
	            'icon' => 'icon-wpb-ui-custom_heading',
	            'show_settings_on_create' => true,
                'weight'    => 999,
	            'category' => __( 'Content', 'flora' ),
	            'description' => __( 'Text with Google fonts', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'textarea',
			            'heading' => __( 'Text', 'flora' ),
			            'param_name' => 'text',
			            'admin_label' => true,
			            'value' => __( 'This is custom heading element with Google Fonts', 'flora' ),
			            'description' => __( 'Note: If you are using non-latin characters be sure to activate them under Settings/Visual Composer/General Settings.', 'flora' ),
		            ),
		            array(
			            'type' => 'vc_link',
			            'heading' => __( 'URL (Link)', 'flora' ),
			            'param_name' => 'link',
			            'description' => __( 'Add link to custom heading.', 'flora' ),
		            ),
		            array(
			            'type' => 'font_container',
			            'param_name' => 'font_container',
			            'value' => 'tag:h2|text_align:left',
			            'settings' => array(
				            'fields' => array(
					            'tag' => 'h2', // default value h2
					            'text_align',
					            'font_size',
					            'line_height',
					            'color',
					            //'font_style_italic'
					            //'font_style_bold'
					            //'font_family'
					            'tag_description' => __( 'Select element tag.', 'flora' ),
					            'text_align_description' => __( 'Select text alignment.', 'flora' ),
					            'font_size_description' => __( 'Enter font size.', 'flora' ),
					            'line_height_description' => __( 'Enter line height.', 'flora' ),
					            'color_description' => __( 'Select heading color.', 'flora' ),
					            //'font_style_description' => __('Put your description here','flora'),
					            //'font_family_description' => __('Put your description here','flora'),
				            ),
			            ),
			            // 'description' => __( '', 'flora' ),
		            ),
		            array(
			            'type' => 'google_fonts',
			            'param_name' => 'google_fonts',
			            'value' => 'font_family:Abril%20Fatface%3A400|font_style:400%20regular%3A400%3Anormal',
			            // default
			            //'font_family:'.rawurlencode('Abril Fatface:400').'|font_style:'.rawurlencode('400 regular:400:normal')
			            // this will override 'settings'. 'font_family:'.rawurlencode('Exo:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic').'|font_style:'.rawurlencode('900 bold italic:900:italic'),
			            'settings' => array(
				            //'no_font_style' // Method 1: To disable font style
				            //'no_font_style'=>true // Method 2: To disable font style
				            'fields' => array(
					            //'font_family' => 'Abril Fatface:regular',
					            //'Exo:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic',// Default font family and all available styles to fetch
					            //'font_style' => '400 regular:400:normal',
					            // Default font style. Name:weight:style, example: "800 bold regular:800:normal"
					            'font_family_description' => __( 'Select font family.', 'flora' ),
					            'font_style_description' => __( 'Select font styling.', 'flora' )
				            )
			            ),
		            ),
                    array(
			                'type' => 'textfield',
			                'heading' => __( 'Letter Spacing', 'flora' ),
			                'param_name' => 'letter_spacing',
			                'description' => __( 'Input a Letter Spacing (e.g. 1px, 2px, etc.).', 'flora' ),
		            ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Text Transform', 'flora'),
                        'param_name' => 'text_transform',
                        'value' => array(
                            __('Default', 'flora') => '',
                            __('None', 'flora') => 'none',
                            __('Capitalize', 'flora') => 'capitalize',
                            __('Lowercase', 'flora') => 'lowercase',
                            __('Uppercase', 'flora') => 'uppercase',
                        ),
                        'description' => __('Apply text case and capitalization.', 'flora')
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
			            'heading' => __( 'Css', 'flora' ),
			            'param_name' => 'css',
			            'group' => __( 'Design Options', 'flora' )
                    )
	            ),
            ) );


            /* Video
            ---------------------------------------------------------- */
            vc_map( array(
	            'name' => __( 'Video Player', 'flora' ),
	            'base' => 'vc_video',
	            'icon' => 'icon-wpb-film-youtube',
	            'category' => __( 'Content', 'flora' ),
	            'description' => __( 'Embed YouTube/Vimeo player', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Video URL', 'flora' ),
			            'param_name' => 'media_url',
			            'admin_label' => true,
			            'description' => sprintf( __( 'Enter URL of video (Note: read more about available formats at WordPress <a href="%s" target="_blank">codex page</a>).', 'flora' ), 'http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F' )
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            ),
		            array(
			            'type' => 'css_editor',
			            'heading' => __( 'Css', 'flora' ),
			            'param_name' => 'css',
			            'group' => __( 'Design Options', 'flora' )
                    )
	            )
            ) );


            /* Widgetised sidebar
            ---------------------------------------------------------- */
            vc_map( array(
	            'name' => __( 'Widgetised Sidebar', 'flora' ),
	            'base' => 'vc_widget_sidebar',
	            'class' => 'wpb_widget_sidebar_widget',
	            'icon' => 'icon-wpb-layout_sidebar',
	            'category' => __( 'Structure', 'flora' ),
	            'description' => __( 'WordPress widgetised sidebar', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'widgetised_sidebars',
			            'heading' => __( 'Sidebar', 'flora' ),
			            'param_name' => 'sidebar_id',
			            'description' => __( 'Select widget area to display.', 'flora' )
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            )
	            )
            ) );


            /* WordPress default Widgets (Appearance->Widgets)
            ---------------------------------------------------------- */
            vc_map( array(
	            'name' => 'WP ' . __( "Search", 'flora' ),
	            'base' => 'vc_wp_search',
	            'icon' => 'icon-wpb-wp',
	            'category' => __( 'WordPress Widgets', 'flora' ),
	            'class' => 'wpb_vc_wp_widget',
	            'weight' => - 50,
	            'description' => __( 'A search form for your site', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Widget title', 'flora' ),
			            'param_name' => 'title',
			            'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'flora' )
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            )
	            )
            ) );

            vc_map( array(
	            'name' => 'WP ' . __( 'Meta', 'flora' ),
	            'base' => 'vc_wp_meta',
	            'icon' => 'icon-wpb-wp',
	            'category' => __( 'WordPress Widgets', 'flora' ),
	            'class' => 'wpb_vc_wp_widget',
	            'weight' => - 50,
	            'description' => __( 'Log in/out, admin, feed and WordPress links', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Widget title', 'flora' ),
			            'param_name' => 'title',
			            'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'flora' )
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            )
	            )
            ) );

            vc_map( array(
	            'name' => 'WP ' . __( 'Recent Comments', 'flora' ),
	            'base' => 'vc_wp_recentcomments',
	            'icon' => 'icon-wpb-wp',
	            'category' => __( 'WordPress Widgets', 'flora' ),
	            'class' => 'wpb_vc_wp_widget',
	            'weight' => - 50,
	            'description' => __( 'The most recent comments', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Widget title', 'flora' ),
			            'param_name' => 'title',
			            'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'flora' )
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Number of comments', 'flora' ),
			            'description' => __( 'Enter number of comments to display.', 'flora' ),
			            'param_name' => 'number',
			            'admin_label' => true
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            )
	            )
            ) );

            vc_map( array(
	            'name' => 'WP ' . __( 'Calendar', 'flora' ),
	            'base' => 'vc_wp_calendar',
	            'icon' => 'icon-wpb-wp',
	            'category' => __( 'WordPress Widgets', 'flora' ),
	            'class' => 'wpb_vc_wp_widget',
	            'weight' => - 50,
	            'description' => __( 'A calendar of your sites posts', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Widget title', 'flora' ),
			            'param_name' => 'title',
			            'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'flora' )
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            )
	            )
            ) );

            vc_map( array(
	            'name' => 'WP ' . __( 'Pages', 'flora' ),
	            'base' => 'vc_wp_pages',
	            'icon' => 'icon-wpb-wp',
	            'category' => __( 'WordPress Widgets', 'flora' ),
	            'class' => 'wpb_vc_wp_widget',
	            'weight' => - 50,
	            'description' => __( 'Your sites WordPress Pages', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Widget title', 'flora' ),
			            'param_name' => 'title',
			            'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'flora' )
		            ),
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Order by', 'flora' ),
			            'param_name' => 'sortby',
			            'value' => array(
				            __( 'Page title', 'flora' ) => 'post_title',
				            __( 'Page order', 'flora' ) => 'menu_order',
				            __( 'Page ID', 'flora' ) => 'ID'
			            ),
			            'description' => __( 'Select how to sort pages.', 'flora' ),
			            'admin_label' => true
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Exclude', 'flora' ),
			            'param_name' => 'exclude',
			            'description' => __( 'Enter page IDs to be excluded (Note: separate values by commas (,)).', 'flora' ),
			            'admin_label' => true
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            )
	            )
            ) );

            $tag_taxonomies = array();
            $taxonomies = get_taxonomies();
            if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ) {
	            foreach ( $taxonomies as $taxonomy ) {
		            $tax = get_taxonomy( $taxonomy );
		            if ( ( is_object( $tax ) && ( ! $tax->show_tagcloud || empty( $tax->labels->name ) ) ) || ! is_object( $tax ) ) {
			            continue;
		            }
		            $tag_taxonomies[ $tax->labels->name ] = esc_attr( $taxonomy );
	            }
            }
            vc_map( array(
	            'name' => 'WP ' . __( 'Tag Cloud', 'flora' ),
	            'base' => 'vc_wp_tagcloud',
	            'icon' => 'icon-wpb-wp',
	            'category' => __( 'WordPress Widgets', 'flora' ),
	            'class' => 'wpb_vc_wp_widget',
	            'weight' => - 50,
	            'description' => __( 'Your most used tags in cloud format', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Widget title', 'flora' ),
			            'param_name' => 'title',
			            'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'flora' )
		            ),
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Taxonomy', 'flora' ),
			            'param_name' => 'taxonomy',
			            'value' => $tag_taxonomies,
			            'description' => __( 'Select source for tag cloud.', 'flora' ),
			            'admin_label' => true
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            )
	            )
            ) );

            $custom_menus = array();
            $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
            if ( is_array( $menus ) && ! empty( $menus ) ) {
	            foreach ( $menus as $single_menu ) {
		            if ( is_object( $single_menu ) && isset( $single_menu->name, $single_menu->term_id ) ) {
			            $custom_menus[ $single_menu->name ] = $single_menu->term_id;
		            }
	            }
            }
            vc_map( array(
	            'name' => 'WP ' . __( "Custom Menu", 'flora' ),
	            'base' => 'vc_wp_custommenu',
	            'icon' => 'icon-wpb-wp',
	            'category' => __( 'WordPress Widgets', 'flora' ),
	            'class' => 'wpb_vc_wp_widget',
	            'weight' => - 50,
	            'description' => __( 'Use this widget to add one of your custom menus as a widget', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Widget title', 'flora' ),
			            'param_name' => 'title',
			            'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'flora' )
		            ),
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Menu', 'flora' ),
			            'param_name' => 'nav_menu',
			            'value' => $custom_menus,
			            'description' => empty( $custom_menus ) ? __( 'Custom menus not found. Please visit <b>Appearance > Menus</b> page to create new menu.', 'flora' ) : __( 'Select menu to display.', 'flora' ),
			            'admin_label' => true
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            )
	            )
            ) );

            vc_map( array(
	            'name' => 'WP ' . __( 'Text', 'flora' ),
	            'base' => 'vc_wp_text',
	            'icon' => 'icon-wpb-wp',
	            'category' => __( 'WordPress Widgets', 'flora' ),
	            'class' => 'wpb_vc_wp_widget',
	            'weight' => - 50,
	            'description' => __( 'Arbitrary text or HTML', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Widget title', 'flora' ),
			            'param_name' => 'title',
			            'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'flora' )
		            ),
		            array(
			            'type' => 'textarea_html',
			            'holder' => 'div',
			            'heading' => __( 'Text', 'flora' ),
			            'param_name' => 'content',
			            // 'admin_label' => true
		            ),
		            /*array(
                        'type' => 'checkbox',
                        'heading' => __( 'Automatically add paragraphs', 'flora' ),
                        'param_name' => "filter"
                    ),*/
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            )
	            )
            ) );

            vc_map( array(
	            'name' => 'WP ' . __( 'Recent Posts', 'flora' ),
	            'base' => 'vc_wp_posts',
	            'icon' => 'icon-wpb-wp',
	            'category' => __( 'WordPress Widgets', 'flora' ),
	            'class' => 'wpb_vc_wp_widget',
	            'weight' => - 50,
	            'description' => __( 'The most recent posts on your site', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Widget title', 'flora' ),
			            'param_name' => 'title',
			            'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'flora' )
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Number of posts', 'flora' ),
			            'description' => __( 'Enter number of posts to display.', 'flora' ),
			            'param_name' => 'number',
			            'admin_label' => true
		            ),
		            array(
			            'type' => 'checkbox',
			            'heading' => __( 'Display post date?', 'flora' ),
			            'param_name' => 'show_date',
			            'value' => array( __( 'Yes', 'flora' ) => true ),
			            'description' => __( 'If checked, date will be displayed.', 'flora' )
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            )
	            )
            ) );

            $link_category = array( __( 'All Links', 'flora' ) => '' );
            $link_cats = get_terms( 'link_category' );
            if ( is_array( $link_cats ) && ! empty( $link_cats ) ) {
	            foreach ( $link_cats as $link_cat ) {
		            if ( is_object( $link_cat ) && isset( $link_cat->name, $link_cat->term_id ) ) {
			            $link_category[ $link_cat->name ] = $link_cat->term_id;
		            }
	            }
            }
            vc_map( array(
	            'name' => 'WP ' . __( 'Links', 'flora' ),
	            'base' => 'vc_wp_links',
	            'icon' => 'icon-wpb-wp',
	            'category' => __( 'WordPress Widgets', 'flora' ),
	            'class' => 'wpb_vc_wp_widget',
	            'content_element' => (bool) get_option( 'link_manager_enabled' ),
	            'weight' => - 50,
	            'description' => __( 'Your blogroll', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Link Category', 'flora' ),
			            'param_name' => 'category',
			            'value' => $link_category,
			            'admin_label' => true
		            ),
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Order by', 'flora' ),
			            'param_name' => 'orderby',
			            'value' => array(
				            __( 'Link title', 'flora' ) => 'name',
				            __( 'Link rating', 'flora' ) => 'rating',
				            __( 'Link ID', 'flora' ) => 'id',
				            __( 'Random', 'flora' ) => 'rand'
			            )
		            ),
		            array(
			            'type' => 'checkbox',
			            'heading' => __( 'Options', 'flora' ),
			            'param_name' => 'options',
			            'value' => array(
				            __( 'Show Link Image', 'flora' ) => 'images',
				            __( 'Show Link Name', 'flora' ) => 'name',
				            __( 'Show Link Description', 'flora' ) => 'description',
				            __( 'Show Link Rating', 'flora' ) => 'rating'
			            )
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Number of links to show', 'flora' ),
			            'param_name' => 'limit'
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            ),
	            )
            ) );

            vc_map( array(
	            'name' => 'WP ' . __( 'Categories', 'flora' ),
	            'base' => 'vc_wp_categories',
	            'icon' => 'icon-wpb-wp',
	            'category' => __( 'WordPress Widgets', 'flora' ),
	            'class' => 'wpb_vc_wp_widget',
	            'weight' => - 50,
	            'description' => __( 'A list or dropdown of categories', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Widget title', 'flora' ),
			            'param_name' => 'title',
			            'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'flora' )
		            ),
		            array(
			            'type' => 'checkbox',
			            'heading' => __( 'Display Options', 'flora' ),
			            'param_name' => 'options',
			            'value' => array(
				            __( 'Dropdown', 'flora' ) => 'dropdown',
				            __( 'Show post counts', 'flora' ) => 'count',
				            __( 'Show hierarchy', 'flora' ) => 'hierarchical'
			            ),
			            'description' => __( 'Select display options for categories.', 'flora' )
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            ),
	            )
            ) );

            vc_map( array(
	            'name' => 'WP ' . __( 'Archives', 'flora' ),
	            'base' => 'vc_wp_archives',
	            'icon' => 'icon-wpb-wp',
	            'category' => __( 'WordPress Widgets', 'flora' ),
	            'class' => 'wpb_vc_wp_widget',
	            'weight' => - 50,
	            'description' => __( 'A monthly archive of your sites posts', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Widget title', 'flora' ),
			            'param_name' => 'title',
			            'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'flora' )
		            ),
		            array(
			            'type' => 'checkbox',
			            'heading' => __( 'Display Options', 'flora' ),
			            'param_name' => 'options',
			            'value' => array(
				            __( 'Dropdown', 'flora' ) => 'dropdown',
				            __( 'Show post counts', 'flora' ) => 'count'
			            ),
			            'description' => __( 'Select display options for archives.', 'flora' )
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            ),
	            )
            ) );

            vc_map( array(
	            'name' => 'WP ' . __( 'RSS', 'flora' ),
	            'base' => 'vc_wp_rss',
	            'icon' => 'icon-wpb-wp',
	            'category' => __( 'WordPress Widgets', 'flora' ),
	            'class' => 'wpb_vc_wp_widget',
	            'weight' => - 50,
	            'description' => __( 'Entries from any RSS or Atom feed', 'flora' ),
	            'params' => array(
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Widget title', 'flora' ),
			            'param_name' => 'title',
			            'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'flora' )
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'RSS feed URL', 'flora' ),
			            'param_name' => 'url',
			            'description' => __( 'Enter the RSS feed URL.', 'flora' ),
			            'admin_label' => true
		            ),
		            array(
			            'type' => 'dropdown',
			            'heading' => __( 'Items', 'flora' ),
			            'param_name' => 'items',
			            'value' => array(
				            __( '10 - Default', 'flora' ) => '',
				            1,
				            2,
				            3,
				            4,
				            5,
				            6,
				            7,
				            8,
				            9,
				            10,
				            11,
				            12,
				            13,
				            14,
				            15,
				            16,
				            17,
				            18,
				            19,
				            20
			            ),
			            'description' => __( 'Select how many items to display.', 'flora' ),
			            'admin_label' => true
		            ),
		            array(
			            'type' => 'checkbox',
			            'heading' => __( 'Options', 'flora' ),
			            'param_name' => 'options',
			            'value' => array(
				            __( 'Item content', 'flora' ) => 'show_summary',
				            __( 'Display item author if available?', 'flora' ) => 'show_author',
				            __( 'Display item date?', 'flora' ) => 'show_date'
			            ),
			            'description' => __( 'Select display options for RSS feeds.', 'flora' )
		            ),
		            array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		            ),
	            )
            ) );



        


        
        }

        public function update_plugins_shortcodes(){
            add_action( 'vc_build_admin_page', array(&$this, 'update_woocommerce_shortcodes'), 11 );
            add_action( 'vc_load_shortcode', array(&$this, 'update_woocommerce_shortcodes'), 11 );

            add_action( 'vc_after_mapping', array( &$this, 'update_revslider_shortcodes' ), 11 );
        }

        public function update_woocommerce_shortcodes(){
            /* WooCommerce
            ---------------------------------------------------------- */
            if ( class_exists( 'WooCommerce' ) ) {

                /* Add default params for shortcodes */
                vc_map_update( 'woocommerce_cart', array( 'params' => array() ) );
                vc_map_update( 'woocommerce_checkout', array( 'params' => array() ) );
                vc_map_update( 'woocommerce_order_tracking', array( 'params' => array() ) );

                /* Recent products
                ---------------------------------------------------------- */
                vc_remove_param( 'recent_products', 'columns' );
                vc_add_param( 'recent_products', array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Columns', 'flora'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'flora'),
                ) );

                /* Featured Products
                ---------------------------------------------------------- */
                vc_remove_param( 'featured_products', 'columns' );
                vc_add_param( 'featured_products', array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Columns', 'flora'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'flora'),
                ) );

                /* Products
                ---------------------------------------------------------- */
                vc_remove_param( 'products', 'columns' );
                vc_add_param( 'products', array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Columns', 'flora'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'flora'),
                ) );


                /* Product Category
                ---------------------------------------------------------- */
                vc_remove_param( 'product_category', 'columns' );
                vc_add_param( 'product_category', array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Columns', 'flora'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'flora'),
                ) );


                /* Product Category
                ---------------------------------------------------------- */
                vc_remove_param( 'product_categories', 'columns' );
                vc_add_param( 'product_categories', array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Columns', 'flora'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'flora'),
                ) );


                /* Sale products
                ---------------------------------------------------------- */
                vc_remove_param( 'sale_products', 'columns' );
                vc_add_param( 'sale_products', array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Columns', 'flora'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'flora'),
                ) );

                /* Best Selling Products
                ---------------------------------------------------------- */
                vc_remove_param( 'best_selling_products', 'columns' );
                vc_add_param( 'best_selling_products', array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Columns', 'flora'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'flora'),
                ) );

                /* Top Rated Products
                ---------------------------------------------------------- */
                vc_remove_param( 'top_rated_products', 'columns' );
                vc_add_param( 'top_rated_products', array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Columns', 'flora'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'flora'),
                ) );

                /* Product Attribute
                ---------------------------------------------------------- */
                vc_remove_param( 'product_attribute', 'columns' );
                vc_add_param( 'product_attribute', array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Columns', 'flora'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'flora'),
                ) );


                /* Related Products
                ---------------------------------------------------------- */
                vc_remove_param( 'related_products', 'columns' );
                vc_add_param( 'related_products', array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Columns', 'flora'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'flora'),
                ) );

            }
        }

        public function update_revslider_shortcodes(){
            /* Revolution Slider
            ---------------------------------------------------------- */
            if ( class_exists( 'RevSlider' ) ) {
               /* vc_remove_param( 'rev_slider_vc', 'title' );
                vc_remove_param( 'rev_slider_vc', 'el_class' );

                vc_add_param( 'rev_slider_vc', array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Scroll Button', 'flora'),
                        'param_name' => 'button_style',
                        'value' => array(
                            __('Hide', 'flora' ) => '',
		                    __('Mouse Wheel', 'flora' ) => '1',
		                    __('Arrow Down', 'flora' ) => '2',
                        ),
                        'description' => __('Select a scroll button at the bottom of slider.', 'flora'),
                ) );

                vc_add_param( 'rev_slider_vc', array(
			            'type' => 'colorpicker',
			            'heading' => __( 'Button Color', 'flora' ),
			            'param_name' => 'color',
			            'description' => __( 'Select a button color.', 'flora' ),
                        'value' => '',
		        ) );

                vc_add_param( 'rev_slider_vc', array(
			            'type' => 'textfield',
			            'heading' => __( 'Extra CSS Class', 'flora' ),
			            'param_name' => 'el_class',
			            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		        ));
                */

                $slider = new RevSlider();
			    $arrSliders = $slider->getArrSliders();

			    $revsliders = array();
			    if ( $arrSliders ) {
				    foreach ( $arrSliders as $slider ) {
					    /** @var $slider RevSlider */
					    $revsliders[ $slider->getTitle() ] = $slider->getAlias();
				    }
			    } else {
				    $revsliders[ __( 'No sliders found', 'flora' ) ] = 0;
			    }


                vc_map( array(
			        'base' => 'rev_slider_vc',
			        'name' => __( 'Revolution Slider', 'flora' ),
			        'icon' => 'icon-wpb-revslider',
			        'category' => __( 'Content', 'flora' ),
			        'description' => __( 'Place Revolution slider', 'flora' ),
			        "params" => array(
				        array(
					        'type' => 'dropdown',
					        'heading' => __( 'Revolution Slider', 'flora' ),
					        'param_name' => 'alias',
					        'admin_label' => true,
					        'value' => $revsliders,
					        'description' => __( 'Select your Revolution Slider.', 'flora' )
				        ),
                        array(
                            'type' => 'dropdown',
                            'class' => '',
                            'heading' => __('Scroll Button', 'flora'),
                            'param_name' => 'button_style',
                            'value' => array(
                                __('Hide', 'flora' ) => '',
		                        __('Mouse Wheel', 'flora' ) => '1',
		                        __('Arrow Down', 'flora' ) => '2',
                            ),
                            'description' => __('Select a scroll button at the bottom of slider.', 'flora'),
                        ),
                        array(
			                'type' => 'colorpicker',
			                'heading' => __( 'Button Color', 'flora' ),
			                'param_name' => 'color',
			                'description' => __( 'Select a button color.', 'flora' ),
                            'value' => '',
		                ),
                        array(
			                'type' => 'textfield',
			                'heading' => __( 'Extra CSS Class', 'flora' ),
			                'param_name' => 'el_class',
			                'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'flora' )
		                )
			        )
		        ) );
            }
        }

        /** Deregister Grid Element post type **/
        public function deregister_grid_element(){

            $this->unregister_post_type('vc_grid_item');
            remove_action('vc_menu_page_build', 'vc_gitem_add_submenu_page');

        }

        public function unregister_post_type( $post_type ){
            global $wp_post_types;
	        if ( isset( $wp_post_types[ $post_type ] ) ) {
                unset( $wp_post_types[ $post_type ] );
	        }
        }

        public function vc_metadata(){
            remove_action('wp_head', array(visual_composer(), 'addMetaData'));
            add_action('wp_head', array($this, 'update_vc_metadata'));

            remove_action('wp_head', array(visual_composer(), 'addNoScript'), 1000);            

            remove_filter('body_class', array(visual_composer(), 'bodyClass'));
        }

        public function update_vc_metadata(){
            echo '<meta name="generator" content="Visual Composer '.WPB_VC_VERSION.'"/>' . "\n";
        }

        /** Icon Picker Options **/
        public static function get_iconpicker_options(){
            return array(
                        array(
			                'type' => 'dropdown',
			                'heading' => __( 'Icon Set', 'flora' ),
			                'value' => array(
                                'Flora' => '',
				                'Font Awesome' => 'fontawesome',
				                'Typicons' => 'typicons',
				                'Linecons' => 'linecons',
				                'ET Line' => 'etline',
			                ),
			                'param_name' => 'icon_set',
			                'description' => __('Select an icon set.', 'flora')
		                ),
                        array(
			                'type' => 'iconpicker',
			                'heading' => __( 'Icon', 'flora' ),
			                'param_name' => 'icon',
			                'value' => '', 
			                'settings' => array(
				                'emptyIcon' => true, 
                                'type' => 'flora',
				                'iconsPerPage' => 4000, 
			                ),
                            'description' => __('Select an icon.', 'flora'),
			                'dependency' => array(
				                'element' => 'icon_set',
				                'is_empty' => true,
			                ),
		                ),
                        array(
			                'type' => 'iconpicker',
			                'heading' => __( 'Icon', 'flora' ),
			                'param_name' => 'icon_fontawesome',
			                'value' => '', 
			                'settings' => array(
				                'emptyIcon' => true, 
				                'iconsPerPage' => 4000, 
			                ),
                            'description' => __('Select an icon.', 'flora'),
			                'dependency' => array(
				                'element' => 'icon_set',
				                'value' => 'fontawesome',
			                ),
		                ),
                        array(
	                        'type' => 'iconpicker',
	                        'heading' => __( 'Icon', 'flora' ),
	                        'param_name' => 'icon_typicons',
	                        'value' => '', 
	                        'settings' => array(
		                        'emptyIcon' => true, 
		                        'type' => 'typicons',
		                        'iconsPerPage' => 4000, 
	                        ),
	                        'description' => __('Select an icon.', 'flora'),
	                        'dependency' => array(
		                        'element' => 'icon_set',
		                        'value' => 'typicons',
	                        ),
                        ),
                        array(
	                        'type' => 'iconpicker',
	                        'heading' => __( 'Icon', 'flora' ),
	                        'param_name' => 'icon_linecons',
	                        'value' => '',
	                        'settings' => array(
		                        'emptyIcon' => true, 
		                        'type' => 'linecons',
		                        'iconsPerPage' => 4000,
	                        ),
	                        'description' => __('Select an icon.', 'flora'),
	                        'dependency' => array(
		                        'element' => 'icon_set',
		                        'value' => 'linecons',
	                        ),
                        ),
                        array(
	                        'type' => 'iconpicker',
	                        'heading' => __( 'Icon', 'flora' ),
	                        'param_name' => 'icon_etline',
	                        'value' => '',
	                        'settings' => array(
		                        'emptyIcon' => true, 
		                        'type' => 'etline',
		                        'iconsPerPage' => 4000,
	                        ),
	                        'description' => __('Select an icon.', 'flora'),
	                        'dependency' => array(
		                        'element' => 'icon_set',
		                        'value' => 'etline',
	                        ),
                        ),
                        

            );

        }

        /** Load Font Icons from css file **/
        function get_font_icons_from_css( $css_uri ) {

            $css = '';
            if( !empty($css_uri) ){
                ob_start();
                include_once $css_uri;
                $css = ob_get_clean();
            }
		
	        $icons = array();
	        $hex_codes = array();

	        preg_match_all( '/\.(icon-|fa-)([^,}]*)\s*:before\s*{\s*(content:)\s*"(\\\\[^"]+)"/s', $css, $matches );
	        $icons = $matches[2];
	        $hex_codes = $matches[4];

	        $icons = array_combine( $hex_codes, $icons );

	        asort( $icons );

	        return $icons;

        }

        function get_font_icons( $name, $version = 1.0, $css_uri){
            $cache_version = get_transient( $name.'_current_version' );
            $icons = get_transient( $name.'_icons' );
            if($cache_version == false || $cache_version < $version || $icons == false){
	            $icons = $this->get_font_icons_from_css( $css_uri );
	            set_transient( $name.'_icons', $icons, 4 * WEEK_IN_SECONDS );
	            set_transient( $name.'_current_version', $version, 4 * WEEK_IN_SECONDS );
            }
            return $icons;
        }

        public function get_font_awesome_icons( $icons ){
                
            $fontawesome_icons_4_3 = array(
		        "Web Application Icons" => array(
                    array( "fa fa-bed" => "Bed" ),
                    array( "fa fa-cart-arrow-down" => "Cart Arrow Down" ),
                    array( "fa fa-cart-plus" => "Cart Plus" ),
                    array( "fa fa-diamond" => "Diamond" ),
                    array( "fa fa-heartbeat" => "Heartbeat" ),
                    array( "fa fa-motorcycle" => "Motorcycle" ),
                    array( "fa fa-server" => "Server" ),
                    array( "fa fa-ship" => "Ship" ),
                    array( "fa fa-street-view" => "Street View" ),
                    array( "fa fa-user-plus" => "User Plus" ),
                    array( "fa fa-user-secret" => "User Secret" ),
                    array( "fa fa-user-times" => "User Times" ),
                ),
                "Transportation Icons" => array(
                    array( "fa fa-subway" => "Subway" ),
                    array( "fa fa-train" => "Train" ),
                ),
                "Brand Icons" => array(
                    array( "fa fa-buysellads" => "Buysellads" ),
                    array( "fa fa-connectdevelop" => "Connectdevelop" ),
                    array( "fa fa-dashcube" => "Dashcube" ),
                    array( "fa fa-facebook-official" => "Facebook Official" ),
                    array( "fa fa-forumbee" => "Forumbee" ),
                    array( "fa fa-leanpub" => "Leanpub" ),
                    array( "fa fa-medium" => "Medium" ),
                    array( "fa fa-pinterest-p" => "Pinterest P" ),
                    array( "fa fa-sellsy" => "Sellsy" ),
                    array( "fa fa-shirtsinbulk" => "Shirtsinbulk" ),
                    array( "fa fa-simplybuilt" => "Simplybuilt" ),
                    array( "fa fa-skyatlas" => "Skyatlas" ),

                ),
                "Gender Icons" => array(
                    array( "fa fa-mars" => "Mars" ),
                    array( "fa fa-mars-double" => "Mars Double" ),
                    array( "fa fa-mars-stroke" => "Mars Stroke" ),
                    array( "fa fa-mars-stroke-h" => "Mars Stroke Horizontal" ),
                    array( "fa fa-mars-stroke-v" => "Mars Stroke Vertical" ),
                    array( "fa fa-mercury" => "Mercury" ),
                    array( "fa fa-neuter" => "Neuter" ),
                    array( "fa fa-transgender" => "Transgender" ),
                    array( "fa fa-transgender-alt" => "Transgender Alt" ),
                    array( "fa fa-venus" => "Venus" ),
                    array( "fa fa-venus-double" => "Venus Double" ),
                    array( "fa fa-venus-mars" => "Venus Mars" ),
                    array( "fa fa-viacoin" => "Viacoin" ),
                )

            );

            return array_merge_recursive( $icons, $fontawesome_icons_4_3 );
        }

        public function get_linecons_icons( $icons ){
        
            $icons = array(
		        array( "linecons-heart" => "Heart" ),
		        array( "linecons-cloud" => "Cloud" ),
		        array( "linecons-star" => "Star" ),
		        array( "linecons-tv" => "Tv" ),
		        array( "linecons-sound" => "Sound" ),
		        array( "linecons-video" => "Video" ),
		        array( "linecons-trash" => "Trash" ),
		        array( "linecons-user" => "User" ),
		        array( "linecons-key" => "Key" ),
		        array( "linecons-search" => "Search" ),
		        array( "linecons-settings" => "Settings" ),
		        array( "linecons-camera" => "Camera" ),
		        array( "linecons-tag" => "Tag" ),
		        array( "linecons-lock" => "Lock" ),
		        array( "linecons-bulb" => "Bulb" ),
		        array( "linecons-pen" => "Pen" ),
		        array( "linecons-diamond" => "Diamond" ),
		        array( "linecons-display" => "Display" ),
		        array( "linecons-location" => "Location" ),
		        array( "linecons-eye" => "Eye" ),
		        array( "linecons-bubble" => "Bubble" ),
		        array( "linecons-stack" => "Stack" ),
		        array( "linecons-cup" => "Cup" ),
		        array( "linecons-phone" => "Phone" ),
		        array( "linecons-news" => "News" ),
		        array( "linecons-mail" => "Mail" ),
		        array( "linecons-like" => "Like" ),
		        array( "linecons-photo" => "Photo" ),
		        array( "linecons-note" => "Note" ),
		        array( "linecons-clock" => "Clock" ),
		        array( "linecons-paperplane" => "Paperplane" ),
		        array( "linecons-params" => "Params" ),
		        array( "linecons-banknote" => "Banknote" ),
		        array( "linecons-data" => "Data" ),
		        array( "linecons-music" => "Music" ),
		        array( "linecons-megaphone" => "Megaphone" ),
		        array( "linecons-study" => "Study" ),
		        array( "linecons-lab" => "Lab" ),
		        array( "linecons-food" => "Food" ),
		        array( "linecons-t-shirt" => "T Shirt" ),
		        array( "linecons-fire" => "Fire" ),
		        array( "linecons-clip" => "Clip" ),
		        array( "linecons-shop" => "Shop" ),
		        array( "linecons-calendar" => "Calendar" ),
		        array( "linecons-wallet" => "Wallet" ),
		        array( "linecons-vynil" => "Vynil" ),
		        array( "linecons-truck" => "Truck" ),
		        array( "linecons-world" => "World" ),
	        );

            return $icons;

        }

        public function get_etline_icons( $icons ){
            $icons = array(
		        array( "etline-mobile" => "Mobile" ),
		        array( "etline-laptop" => "Laptop" ),
		        array( "etline-desktop" => "Desktop" ),
		        array( "etline-tablet" => "Tablet" ),
		        array( "etline-phone" => "Phone" ),
		        array( "etline-document" => "Document" ),
		        array( "etline-documents" => "Documents" ),
		        array( "etline-search" => "Search" ),
		        array( "etline-clipboard" => "Clipboard" ),
		        array( "etline-newspaper" => "Newspaper" ),
		        array( "etline-notebook" => "Notebook" ),
		        array( "etline-book-open" => "Book Open" ),
		        array( "etline-browser" => "Browser" ),
		        array( "etline-calendar" => "Calendar" ),
		        array( "etline-presentation" => "Presentation" ),
		        array( "etline-picture" => "Picture" ),
		        array( "etline-pictures" => "Pictures" ),
		        array( "etline-video" => "Video" ),
		        array( "etline-camera" => "Camera" ),
		        array( "etline-printer" => "Printer" ),
		        array( "etline-toolbox" => "Toolbox" ),
		        array( "etline-briefcase" => "Briefcase" ),
		        array( "etline-wallet" => "Wallet" ),
		        array( "etline-gift" => "Gift" ),
		        array( "etline-bargraph" => "Bargraph" ),
		        array( "etline-grid" => "Grid" ),
		        array( "etline-expand" => "Expand" ),
		        array( "etline-focus" => "Focus" ),
		        array( "etline-edit" => "Edit" ),
		        array( "etline-adjustments" => "Adjustments" ),
		        array( "etline-ribbon" => "Ribbon" ),
		        array( "etline-hourglass" => "Hourglass" ),
		        array( "etline-lock" => "Icon-lock" ),
		        array( "etline-megaphone" => "Icon-megaphone" ),
		        array( "etline-shield" => "Icon-shield" ),
		        array( "etline-trophy" => "Icon-trophy" ),
		        array( "etline-flag" => "Icon-flag" ),
		        array( "etline-map" => "Icon-map" ),
		        array( "etline-puzzle" => "Icon-puzzle" ),
		        array( "etline-basket" => "Icon-basket" ),
		        array( "etline-envelope" => "Icon-envelope" ),
		        array( "etline-streetsign" => "Icon-streetsign" ),
		        array( "etline-telescope" => "Icon-telescope" ),
		        array( "etline-gears" => "Icon-gears" ),
		        array( "etline-key" => "Icon-key" ),
		        array( "etline-paperclip" => "Icon-paperclip" ),
		        array( "etline-attachment" => "Icon-attachment" ),
		        array( "etline-pricetags" => "Icon-pricetags" ),
			    array( "etline-lightbulb" => "Icon-lightbulb" ),
			    array( "etline-layers" => "Icon-layers" ),
			    array( "etline-pencil" => "Icon-pencil" ),
			    array( "etline-tools" => "Icon-tools" ),
			    array( "etline-tools-2" => "Icon-tools-2" ),
			    array( "etline-scissors" => "Icon-scissors" ),
			    array( "etline-paintbrush" => "Icon-paintbrush" ),
			    array( "etline-magnifying-glass" => "Icon-magnifying-glass" ),
			    array( "etline-circle-compass" => "Icon-circle-compass" ),
			    array( "etline-linegraph" => "Icon-linegraph" ),
			    array( "etline-mic" => "Icon-mic" ),
			    array( "etline-strategy" => "Icon-strategy" ),
			    array( "etline-beaker" => "Icon-beaker" ),
			    array( "etline-caution" => "Icon-caution" ),
			    array( "etline-recycle" => "Icon-recycle" ),
			    array( "etline-anchor" => "Icon-anchor" ),
			    array( "etline-profile-male" => "Icon-profile-male" ),
			    array( "etline-profile-female" => "Icon-profile-female" ),
			    array( "etline-bike" => "Icon-bike" ),
			    array( "etline-wine" => "Icon-wine" ),
			    array( "etline-hotairballoon" => "Icon-hotairballoon" ),
			    array( "etline-globe" => "Icon-globe" ),
			    array( "etline-genius" => "Icon-genius" ),
			    array( "etline-map-pin" => "Icon-map-pin" ),
			    array( "etline-dial" => "Icon-dial" ),
			    array( "etline-chat" => "Icon-chat" ),
			    array( "etline-heart" => "Icon-heart" ),
			    array( "etline-cloud" => "Icon-cloud" ),
			    array( "etline-upload" => "Icon-upload" ),
			    array( "etline-download" => "Icon-download" ),
			    array( "etline-target" => "Icon-target" ),
			    array( "etline-hazardous" => "Icon-hazardous" ),
			    array( "etline-piechart" => "Icon-piechart" ),
			    array( "etline-speedometer" => "Icon-speedometer" ),
			    array( "etline-global" => "Icon-global" ),
			    array( "etline-compass" => "Icon-compass" ),
			    array( "etline-lifesaver" => "Icon-lifesaver" ),
			    array( "etline-clock" => "Icon-clock" ),
			    array( "etline-aperture" => "Icon-aperture" ),
			    array( "etline-quote" => "Icon-quote" ),
			    array( "etline-scope" => "Icon-scope" ),
			    array( "etline-alarmclock" => "Icon-alarmclock" ),
			    array( "etline-refresh" => "Icon-refresh" ),
			    array( "etline-happy" => "Icon-happy" ),
			    array( "etline-sad" => "Icon-sad" ),
			    array( "etline-facebook" => "Icon-facebook" ),
			    array( "etline-twitter" => "Icon-twitter" ),
			    array( "etline-googleplus" => "Icon-googleplus" ),
			    array( "etline-rss" => "Icon-rss" ),
			    array( "etline-tumblr" => "Icon-tumblr" ),
			    array( "etline-linkedin" => "Icon-linkedin" ),
			    array( "etline-dribbble" => "Icon-dribbble" )
	        );
            return $icons;
        }

        public function get_flora_icons( $icons ){
            $icons = array(
            	array( "flora-icon-arrow-1" => "Arrow 1" ),
		        array( "flora-icon-arrow-2" => "Arrow 2" ),
		        array( "flora-icon-arrow-3" => "Arrow 3" ),
		        array( "flora-icon-arrow-4" => "Arrow 4" ),
		        array( "flora-icon-arrow-5" => "Arrow 5" ),
                array( "flora-icon-bear"    => "Bear" ),
		        array( "flora-icon-birds"   => "Birds" ),
                array( "flora-icon-deer-1" => "Deer 1" ),
                array( "flora-icon-deer-2" => "Deer 2" ),
		        array( "flora-icon-glasses" => "Glasses" ),
                array( "flora-icon-leaf-1" => "Leaf 1" ),
                array( "flora-icon-leaf-2" => "Leaf 2" ),
		        array( "flora-icon-leaf-3" => "Leaf 3" ),
                array( "flora-icon-line" => "Line" ),
		        array( "flora-icon-mountain-1"  => "Mountain 1" ),
                array( "flora-icon-mountain-2"  => "Mountain 2" ),
		        array( "flora-icon-moustache-1" => "Moustache 1" ),
		        array( "flora-icon-moustache-2" => "Moustache 2" ),
		        array( "flora-icon-rectangle"   => "Rectangle" ),
                array( "flora-icon-tree" => "Tree" ),
		        array( "flora-icon-triangle-1" => "Triangle 1" ),
		        array( "flora-icon-triangle-2" => "Triangle 2" ),
		        array( "flora-icon-triangle-3" => "Triangle 3" ),
		        array( "flora-icon-triangle-4" => "Triangle 4" ),
                array( "flora-icon-triangle-5" => "Triangle 5" ),
		        array( "flora-icon-triangle-6" => "Triangle 6" ),
		        array( "flora-icon-triangle-7" => "Triangle 7" ),
		        array( "flora-icon-triangle-8" => "Triangle 8" ),
		        array( "flora-icon-triangle-9" => "Triangle 9" ),
		        array( "flora-icon-triangle-10" => "Triangle 10" ),
		        array( "flora-icon-wave" => "Wave" ),
		        array( "flora-icon-wolf" => "Wolf" ),
		        array( "flora-icon-zigzag-1" => "Zigzag 1" ),
		        array( "flora-icon-zigzag-2" => "Zigzag 2" ),
	        );
            return apply_filters('flora_iconpicker_icons', $icons);
        }

        /** Add new fonts to Google Fonts field */
        public function get_google_fonts( $fonts ){
            $fonts[] = json_decode('{"font_family":"Alegreya","font_styles":"regular,italic,bold,bold italic,900 italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold:700:normal,700 bold italic:700:italic,900 bold italic:900:italic"}');
            $fonts[] = json_decode('{"font_family":"Dancing Script","font_styles":"regular,bold","font_types":"400 regular:400:normal,700 bold:700:normal"}');
            //$fonts[] = json_decode('{"font_family":"Libre Baskerville","font_styles":"regular,italic,bold","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold:700:normal"}');
            $fonts[] = json_decode('{"font_family":"Lobster Two","font_styles":"regular,italic,bold,bold italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold:700:normal,700 bold italic:700:italic"}');
            //$fonts[] = json_decode('{"font_family":"Lora","font_styles":"regular,italic,bold,bold italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold:700:normal,700 bold italic:700:italic"}');
            $fonts[] = json_decode('{"font_family":"Questrial","font_styles":"regular","font_types":"400 regular:400:normal"}');
            $fonts[] = json_decode('{"font_family":"Source Sans Pro","font_styles":"regular,italic,bold,bold italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold:700:normal,700 bold italic:700:italic"}');
            asort($fonts);
            return $fonts;
        }

        public function animation_field($settings, $value) {
    
            $dependency = vc_generate_dependencies_attributes($settings);

            $html ='<div class="wyde-animation">';
            $html .='<div class="animation-field">';
            $html .= sprintf('<select name="%1$s" class="wpb_vc_param_value %1$s %2$s_field" %3$s>', esc_attr( $settings['param_name'] ), esc_attr( $settings['type'] ), $dependency);

            $animations  = wyde_get_animations();

            foreach($animations as $key => $text){
                $html .= sprintf('<option value="%s" %s>%s</option>', esc_attr( $key ), ($value==$key? ' selected':''), esc_html( $text ) );
            }

            $html .= '</select></div>';
            $html .= '<div class="animation-preview"><span>Animation</span></div>';
            $html .= '</div>';

            return $html;

        }

        public function gmaps_field($settings, $value) {
    
            $dependency = vc_generate_dependencies_attributes($settings);

            $html ='<div class="wyde-gmaps">';
            $html .='<div class="gmaps-field">';
            $html .= sprintf('<input name="%1$s" class="wpb_vc_param_value %1$s %2$s_field" type="hidden" value="%3$s" %4$s/>', esc_attr( $settings['param_name'] ), esc_attr( $settings['type'] ), esc_attr( $value ), $dependency);
            $html .= sprintf('  <div class="edit_form_line"><input class="map-address" type="text" value="" /><span class="vc_description vc_clearfix">%s</span></div>', __('Enter text to display in the Info Window.', 'flora'));
        
            $html .= '  <div class="vc_column vc_clearfix">';
            $html .= '      <div class="vc_col-sm-6">';
            $html .= sprintf('<div class="wpb_element_label">%s</div>', __('Map Type', 'flora'));
            $html .= '          <div class="edit_form_line">';
            $html .= '              <select class="wpb-select dropdown map-type"><option value="1">Hybrid</option><option value="2">RoadMap</option><option value="3">Satellite</option><option value="4">Terrain</option></select>';
            $html .= '          </div>';
            $html .= '       </div>';
            $html .= '      <div class="vc_col-sm-6">';
            $html .= sprintf('<div class="wpb_element_label">%s</div>', __('Map Zoom', 'flora'));
            $html .= '          <div class="edit_form_line">';
            $html .= '              <select class="wpb-select dropdown map-zoom">';
            for($i=1; $i<=20; $i++){
            $html .= sprintf('          <option value="%1$s">%1$s</option>', $i);
            }
            $html .= '              </select>';
            $html .= '          </div>';
            $html .= '      </div>';
            $html .= '  </div>';
            $html .= '</div>';
            $html .= '<div class="vc_column vc_clearfix">';
            $html .= sprintf('<span class="vc_description vc_clearfix">%s</span>', __('Drag & Drop marker to set your location.', 'flora'));
            $html .= '  <div class="gmaps-canvas" style="height:300px;"></div>';
            $html .= '</div>';
            $html .= '</div>';

            return $html;

        }
    
    }

}

new Wyde_Shortcode();    
?>