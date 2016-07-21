<?php
    
/** Wyde AJAX Importer **/
include_once get_template_directory() . '/admin/class-wyde-ajax-importer.php';

/** Theme Options **/
if (!class_exists('Flora_Theme_Options')) {

    class Flora_Theme_Options {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if ( !class_exists('ReduxFramework') ) {
                return;
            }

            if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                $this->initSettings();
                add_action( "redux/options/{$this->args['opt_name']}/saved", array($this, 'settings_saved'), 10, 2 );
                add_action('init', array($this, 'update_slug'), 10);
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

		    if ( is_admin() && isset($_GET['page']) && $_GET['page'] == 'theme-options' ) {
                add_action( 'admin_enqueue_scripts', array($this, 'load_scripts') );
            }

        }

        function load_scripts(){
            
            wp_enqueue_style('theme-options-style', get_template_directory_uri(). '/admin/css/theme-options.css', null, '1.3.1');
	
            wp_register_script('ajax-importer-script', get_template_directory_uri(). '/admin/js/ajax-importer.js', null, null, true);
            wp_enqueue_script( 'ajax-importer-script');
            
            $import_taks = array(__('Pages', 'flora'), __('Posts', 'flora'), __('Portfolios', 'flora'), __('Team Members', 'flora'), __('Testimonials', 'flora'), __('Widgets', 'flora'), __('Sliders', 'flora'), __('Settings', 'flora'));
            wp_localize_script('ajax-importer-script', 'ajax_importer_settings', array('import_url' => admin_url( 'admin-ajax.php' ), 'data_dir' => get_template_directory_uri() . '/admin/data/', 'import_tasks' => $import_taks) );

        }

        public function initSettings() {

            $this->theme = wp_get_theme();

            $this->setArguments();

            $this->setSections();

            if (!isset($this->args['opt_name'])) {
                return;
            }

            add_action( 'redux/loaded', array( $this, 'remove_demo' ) );

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        public function settings_saved($options, $changes){
            if( array_key_exists('portfolio_slug', $changes) ){
                echo '<script type="text/javascript">window.location.href=window.location.pathname+"?page=theme-options&slug-updated=true";</script>';
            }
        }

        public function update_slug(){
            global $pagenow;
            $slug_updated = isset( $_GET['slug-updated'] )? $_GET['slug-updated']:'';
            if( $slug_updated == 'true' ){
                flush_rewrite_rules();
                wp_redirect( admin_url( $pagenow.'?page=theme-options&settings-updated=true' ) );
            }
        }

        function remove_demo() {
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }
        
        public function setSections() {

            $template_directory = esc_url( get_template_directory_uri() );

            /***************************** 
            * Home
            ******************************/
            $import_fields = array(
                    array(
                        'id'        => 'section_import',
                        'type'      => 'section',
                        'title'     => __('Import Demo', 'flora'),
                        'subtitle'  => __('Please make sure you have required plugins installed and activated to receive that portion of the content. This is recommended to do on fresh installs.', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'notice-warning',
                        'type'      => 'info',
                        'notice'    => true,
                        'style'     => 'warning',
                        'icon'      => 'el el-warning-sign',
                        'title'     => __('WARNING:', 'flora'),
                        'desc'      => __('Importing demo content will replace your current settings and append your pages and posts. It can also take a minute to complete. <br />You can use <a href="https://wordpress.org/plugins/wordpress-reset/" target="_blank">WordPress Reset</a> plugin to remove all existing data before importing.', 'flora')
                    ),
                    array(
                        'id'        => 'raw_import',
                        'type'      => 'raw',
                        'content'   => 
                        '<div class="import-wrapper">'
                        .'<h4>Choose a Demo</h4>'
                        .'<div class="demo-content-list">'
                        .'<a id="demo-content-1" href="#" class="demo-item"><img src="'. $template_directory .'/admin/images/flora1.jpg" alt="Main Demo"/><strong>Main Demo</strong></a>'
                        .'<a id="demo-content-6" href="#" class="demo-item"><img src="'. $template_directory .'/admin/images/flora6.jpg" alt="Creative Agency"/><strong>Creative Agency</strong></a>'
                        .'<a id="demo-content-2" href="#" class="demo-item"><img src="'. $template_directory .'/admin/images/flora2.jpg" alt="Left Menu"/><strong>Left Menu</strong></a>'
                        .'<a id="demo-content-3" href="#" class="demo-item"><img src="'. $template_directory .'/admin/images/flora3.jpg" alt="One Page Site"/><strong>One Page Site</strong></a>'
                        .'<a id="demo-content-4" href="#" class="demo-item"><img src="'. $template_directory .'/admin/images/flora4.jpg" alt="Minimal Portfolio"/><strong>Minimal Portfolio</strong></a>'
                        .'<a id="demo-content-5" href="#" class="demo-item"><img src="'. $template_directory .'/admin/images/flora5.jpg" alt="Creative Portfolio"/><strong>Creative Portfolio</strong></a>'
                        .'</div>'
                        .'</div>',
                    )
            );

            $imported = isset( $_GET['imported'] )? $_GET['imported']:'';
            
            if($imported == 'success' ){
                
                 array_unshift($import_fields, array(
                        'id'        => 'notice-success',
                        'type'      => 'info',
                        'notice'    => true,
                        'style'     => 'success',
                        'icon'      => 'el el-info-circle',
                        'title'     => __('Success!', 'flora'),
                        'desc'      => __('The demo content has been successfully imported.', 'flora')
                ));

            }else if($imported == 'error' ){
                
                array_unshift($import_fields, array(
                        'id'        => 'notice-fail',
                        'type'      => 'info',
                        'notice'    => true,
                        'style'     => 'critical',
                        'icon'      => 'el el-info-circle',
                        'title'     => __('ERROR!', 'flora'),
                        'desc'      => __('An error occurred while importing demo data, please try again later.', 'flora')
                ));

            }
            
            $this->sections['home'] = array(
                'title'     => __('Home', 'flora'),
                'heading'   => false,
                'icon'      => 'el-icon-home',
                'fields'    => $import_fields
            );


            /***************************** 
            * General
            ******************************/
            $predefined_colors = array();
            for($i = 1; $i <= 9; $i ++){
                $predefined_colors[strval($i)] = array('alt' => '',  'img' => $template_directory . '/images/colors/'.$i.'.png');
            }

            $this->sections['general'] = array(
                'icon'      => 'el el-adjust-alt',
                'title'     => __('General', 'flora'),
                'heading'   => false,
                'fields'    => array(
                   array(
                        'id'        => 'predefined_color',
                        'type'      => 'image_select',
                        'title'     => __('Predefined Colors', 'flora'),
                        'subtitle'  => __('Select a predefined color schemes.', 'flora'),
                        'options'   => $predefined_colors,
                        'default'   => '1'
                   ),
                   array(
                        'id'        => 'custom_color',
                        'type'      => 'switch',
                        'title'     => __('Custom Color Scheme', 'flora'),
                        'subtitle'  => __('Use custom color from color picker.', 'flora'),
                        'default'   => false
                   ),
                   array(
                        'id'        => 'color_scheme',
                        'type'      => 'color',
                        'title'     => __('Color Scheme', 'flora'),
                        'subtitle'  => __('Choose your own color scheme.', 'flora'),
                        'required'  => array('custom_color', '=', true),
                        'transparent'   => false,
                        'default'   => '#10a5a0'
                   ),
                    array(
                        'id'        => 'mobile_animation',
                        'type'      => 'switch',
                        'title'     => __('Animation on Mobile', 'flora'),
                        'subtitle'  => __('Enable animated elements on mobile devices.', 'flora'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'smooth_scroll',
                        'type'      => 'switch',
                        'title'     => __('Smooth Scrolling', 'flora'),
                        'subtitle'  => __('Enable a smooth scrolling.', 'flora'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'totop_button',
                        'type'      => 'switch',
                        'title'     => __('Back To Top Button', 'flora'),
                        'subtitle'  => __('Enable a back to top button.', 'flora'),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'preload_images',
                        'type'      => 'switch',
                        'title'     => __('Preload Images', 'flora'),
                        'subtitle'  => __('Preloading images definitely helps users enjoy a better experience when viewing your content.', 'flora'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'page_loader',
                        'type'      => 'image_select',
                        'title'     => __('Loader', 'flora'),
                        'subtitle'  => __('Select a loader animation.', 'flora'),
                        'options'   => array(
                            'none' => array('alt' => '', 'img' => $template_directory . '/images/loaders/0.jpg'),
                            '1' => array('alt' => '', 'img' => $template_directory . '/images/loaders/1.jpg'),
                            '2' => array('alt' => '',  'img' => $template_directory . '/images/loaders/2.jpg'),
                            '3' => array('alt' => '', 'img' => $template_directory . '/images/loaders/3.jpg'),
                            '4' => array('alt' => '', 'img' => $template_directory . '/images/loaders/4.jpg'),
                            '5' => array('alt' => '', 'img' => $template_directory . '/images/loaders/5.jpg'),
                        ),
                        'default'   => '1',
                    ),
                 )
            );

            /***************************** 
            * Favicon
            ******************************/
            $this->sections['favicon'] = array(
                'icon'      => 'el-icon-star',
                'title'     => __('Favicon', 'flora'),
                'heading'   => false,
                'fields'    => array(
                    array(
                        'id'        => 'section_favicon',
                        'type'      => 'section',
                        'title'     => __('Favicon', 'flora'),
                        'subtitle'  => __('Customize a favicon for your site.', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'favicon_image',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Favicon Image (.PNG)', 'flora'),
                        'readonly'  => false,
                        'subtitle'  => __('Upload a favicon image for your site, or you can specify an image URL directly.', 'flora'),
                        'desc'      => __('Icon dimension:', 'flora').' 16px * 16px or 32px * 32px',
                        'default'   => array(        
                                            'url'=> $template_directory .'/images/favicon.png'
                        ),
                    ),
                    array(
                        'id'        => 'favicon',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Favicon (.ICO)', 'flora'),
                        'readonly'  => false,
                        'subtitle'  => __('Upload a favicon for your site, or you can specify an icon URL directly.', 'flora'),
                        'desc'      => __('Icon dimension:', 'flora').' 16px * 16px',

                    ),
                    array(
                        'id'        => 'favicon_iphone',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Apple iPhone Icon', 'flora'),
                        'height'    => '57px',
                        'readonly'  => false,
                        'subtitle'  => __('Favicon for Apple iPhone.', 'flora'),
                        'desc'      => __('Icon dimension:', 'flora').' 57px * 57px',
                    ),
                    array(
                        'id'        => 'favicon_iphone_retina',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Apple iPhone Icon (Retina Version)', 'flora'),
                        'height'    => '57px',
                        'readonly'  => false,
                        'subtitle'  => __('Favicon for Apple iPhone Retina Version.', 'flora'),
                        'desc'      => __('Icon dimension:', 'flora').' 114px  * 114px',
                    ),
                    array(
                        'id'        => 'favicon_ipad',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Apple iPad Icon', 'flora'),
                        'height'    => '72px',
                        'readonly'  => false,
                        'subtitle'  => __('Favicon for Apple iPad.', 'flora'),
                        'desc'      => __('Icon dimension:', 'flora').' 72px * 72px',
                    ),
                    array(
                        'id'        => 'favicon_ipad_retina',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Apple iPad Icon (Retina Version)', 'flora'),
                        'height'    => '57px',
                        'readonly'  => false,
                        'subtitle'  => __('Favicon for Apple iPad Retina Version.', 'flora'),
                        'desc'      => __('Icon dimension:', 'flora').' 144px  * 144px',
                    )
            ));


            /***************************** 
            * Navigation
            ******************************/
            $this->sections['nav'] = array(
                'icon'      => 'el-icon-lines',
                'title'     => __('Navigation', 'flora'),
                'heading'   => false,
                'fields'    => array(
                    array(
                        'id'        => 'section_nav',
                        'type'      => 'section',
                        'title'     => __('Navigation', 'flora'),
                        'subtitle'  => __('Customize the primary navigation.', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'nav_layout',
                        'type'      => 'select',
                        'title'     => __('Layout', 'flora'),
                        'subtitle'  => __('Select a navigation layout.', 'flora'),
                        'options'   => array(
                            'classic'   => __('Classic', 'flora'),
                            'expand'    => __('Expand', 'flora'),
                            'fullscreen'      => __('Full Screen', 'flora'),
                            'left'      => __('Left Menu', 'flora'),
                        ),
                        'default'   => 'classic'
                    ),
                    array(
                        'id'        => 'slidingbar',
                        'type'      => 'switch',
                        'required'  => array('nav_layout', '!=', '3'),
                        'title'     => __('Sliding Bar', 'flora'),
                        'subtitle'  => __('Turn on to display a sliding widget area.', 'flora'),
                        'default'   => false
                    ),
                    array(
                        'id'        => 'menu_shop_cart',
                        'type'      => 'switch',
                        'title'     => __('Shopping Cart Icon', 'flora'),
                        'subtitle'  => __('Turn on to display the shopping cart icon.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'menu_search_icon',
                        'type'      => 'switch',
                        'title'     => __('Search Icon', 'flora'),
                        'subtitle'  => __('Turn on to display the search icon.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'menu_social_icon',
                        'type'      => 'switch',
                        'title'     => __('Social Icons', 'flora'),
                        'subtitle'  => __('Turn on to display the social media icons.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'menu_contact',
                        'type'      => 'switch',
                        'title'     => __('Contact Info', 'flora'),
                        'subtitle'  => __('Turn on to display the contact info.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'menu_contact_items',
                        'type'      => 'multi_text',
                        'required'  => array('menu_contact', '=', 1),
                        'title'     => __('Contact Info Items', 'flora'),
                        'subtitle'  => __('The contact items to display in the left menu and sliding bar.', 'flora'),
                        'add_text'  => __('Add New', 'flora'),
                        'default'   => array(
                            '<i class="flora-icon-phone"></i> +1 111-888-000',
                            '<i class="flora-icon-mail"></i> email@domain.com',
                            '<i class="flora-icon-map-marker"></i> 1234, Your Address, 12345',
                        ),
                    ),
                    array(
                        'id'        => 'section_header',
                        'type'      => 'section',
                        'required'  => array('nav_layout', '!=', '3'),
                        'title'     => __('Top Navigation', 'flora'),
                        'subtitle'  => __('Customize the page header and top menu.', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'header_sticky',
                        'type'      => 'switch',
                        'title'     => __('Sticky Header', 'flora'),
                        'subtitle'  => __('Enable sticky header.', 'flora'),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'header_fullwidth',
                        'type'      => 'switch',
                        'title'     => __('Full Width', 'flora'),
                        'subtitle'  => __('Turn on to use full width header or off to use fixed header width.', 'flora'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'header_style',
                        'type'      => 'select',
                        'title'     => __('Header Background', 'flora'),
                        'subtitle'  => __('Select a header navigation background style.', 'flora'),
                        'options'   => array(
                            'light' => __('Light', 'flora'),
                            'dark'  => __('Dark', 'flora'),
                        ),
                        'default'   => 'light'
                    ),
                    array(
                        'id'        => 'header_logo',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Logo', 'flora'),
                        'height'    => '45px',
                        'readonly'  => false,
                        'subtitle'  => __('Header logo image.', 'flora'),
                        'desc'      => __('Recommended height: 70px or larger.', 'flora'),
                        'default'   => array(        
                                    'url'=> $template_directory .'/images/logo/dark-logo.png'
                        )
                    ),
                    array(
                        'id'        => 'header_logo_sticky',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Sticky Logo', 'flora'),
                        'height'    => '45px',
                        'readonly'  => false,
                        'subtitle'  => __('Sticky header logo.', 'flora'),
                        'desc'      => __('Recommended height: 50px or larger.', 'flora'),
                        'default'   => array(        
                                    'url'=> $template_directory .'/images/logo/dark-sticky.png'
                        ),
                    ),
                    array(
                        'id'        => 'header_logo_dark',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Logo for Dark Header', 'flora'),
                        'height'    => '45px',
                        'readonly'  => false,
                        'subtitle'  => __('Light version of logo image for dark header.', 'flora'),
                        'desc'      => __('Recommended height: 70px or larger.', 'flora'),
                        'default'   => array(        
                                    'url'=> $template_directory .'/images/logo/light-logo.png'
                        ),
                    ),
                    array(
                        'id'        => 'header_logo_dark_sticky',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Sticky Logo for Dark Header', 'flora'),
                        'height'    => '45px',
                        'readonly'  => false,
                        'subtitle'  => __('Light version of sticky logo image for dark header.', 'flora'),
                        'desc'      => __('Recommended height: 50px or larger.', 'flora'),
                        'default'   => array(        
                                    'url'=> $template_directory .'/images/logo/light-sticky.png'
                        ),
                    ),
                    array(
                        'id'        => 'section_sidenav',
                        'type'      => 'section',
                        'title'     => __('Side Navigation / Mobile Navigation', 'flora'),
                        'subtitle'  => __('Customize the side navigation. These options can also be used in mobile navigation.', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'side_logo',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Side Logo', 'flora'),
                        'height'     => '45px',
                        'readonly'  => false,
                        'subtitle'  => __('Upload a custom logo image, or you can specify an image URL directly.', 'flora'),
                        'desc'      => __('Recommended width: 200px.', 'flora'),
                        'default'  => array(        
                                    'url'=> $template_directory .'/images/logo/side-logo.png'
                        ),
                    ),
                    array(
                        'id'        => 'side_logo_retina',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Side Logo (Retina Version)', 'flora'),
                        'height'    => '90px',
                        'readonly'  => false,
                        'subtitle'  => __('Upload a retina logo image, or you can specify an image URL directly.', 'flora'),
                        'desc'      => __('It should be exactly 2x the size of normal logo.', 'flora'),
                        'default'   => array(        
                                    'url'=> $template_directory .'/images/logo/side-logo@2x.png'
                        ),
                    ),
                    array(
                        'id'        => 'side_nav_text_style',
                        'type'      => 'select',
                        'title'     => __('Text Style', 'flora'),
                        'subtitle'  => __('Select navigation text style.', 'flora'),
                        'options'   => array(
                            'dark'  => __('Dark', 'flora'),
                            'light' =>  __('Light', 'flora'),
                            'custom'=>  __('Custom', 'flora'),
                        ),
                        'default'   => 'light'
                    ),
                    array(
                        'id'        => 'side_nav_color',
                        'type'      => 'color',
                        'required'  => array('side_nav_text_style', '=', 'custom'),
                        'title'     => __('Text Color', 'flora'),
                        'subtitle'  => __('Set navigation text color.', 'flora'),
                        'transparent' => false,
                        'output'    => array('#side-nav'),
                        'default'   => '#fff',
                    ),
                    array(
                        'id'        => 'side_nav_background',
                        'type'      => 'background',
                        'title'     => __('Background', 'flora'),
                        'subtitle'  => __('Set a side nav background.', 'flora'),
                        'output'    => array('#side-nav'),
                        'background-repeat' => false,
                        'background-attachment' =>false,
                        'default'   => array(
                            'background-color'   => '#211F1E',
                            'background-size'   => 'cover',
                            'background-position'   => 'center bottom'
                        ),
                    ),
                    array(
                        'id'        => 'side_nav_overlay_color',
                        'type'      => 'color',
                        'title'     => __('Background Overlay Color', 'flora'),
                        'subtitle'  => __('Select background overlay color.', 'flora'),
                        'default'  => '',
                        'validate' => 'color',
                    ),
                    array(
                        'id'        => 'side_nav_overlay_opacity',
                        'type'      => 'select',
                        'title'     => __('Background Overlay Opacity', 'flora'),
                        'subtitle'  => __('Select background overlay opacity.', 'flora'),
                        'options'   => array(
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
                        'default'  => '0.8',
                    )
                 )
            );


            /***************************** 
            * Footer
            ******************************/
            $this->sections['footer'] = array(
                'icon'      => 'el-icon-th-large',
                'title'     => __('Footer', 'flora'),
                'heading'   => false,
                'fields'    => array(
                    array(
                        'id'        => 'section_footer_options',
                        'type'      => 'section',
                        'title'     => __('Footer Options', 'flora'),
                        'subtitle'  => __('Turn on or off footer options.', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'footer_sticky',
                        'type'      => 'switch',
                        'title'     => __('Sticky Footer', 'flora'),
                        'subtitle'  => __('Enable sticky footer.', 'flora'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'footer_fullwidth',
                        'type'      => 'switch',
                        'title'     => __('Full Width', 'flora'),
                        'subtitle'  => __('Turn on to use full width footer or off to use fixed footer width.', 'flora'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'section_footer_widget',
                        'type'      => 'section',
                        'title'     => __('Footer Widget Area', 'flora'),
                        'subtitle'  => __('Customize the footer widget area.', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'footer_widget',
                        'type'      => 'switch',
                        'title'     => __('Footer Widget Area', 'flora'),
                        'subtitle'  => __('Display footer widgets.', 'flora'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'footer_widget_align_middle',
                        'type'      => 'switch',
                        'title'     => __('Vertical Center', 'flora'),
                        'required'  => array('footer_widget', '=', 1),
                        'subtitle'  => __('Sets the vertical alignment of elements to middle.', 'flora'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'footer_widget_color',
                        'type'      => 'color',
                        'required'  => array('footer_widget', '=', 1),
                        'title'     => __('Text Color', 'flora'),
                        'subtitle'  => __('Set a footer widget area text color.', 'flora'),
                        'transparent' => false,
                        'output'    => array('#footer-widget'),
                        'default'   => '#8e8072',
                    ),
                    array(
                        'id'        => 'footer_widget_background',
                        'type'      => 'background',
                        'required'  => array('footer_widget', '=', 1),
                        'title'     => __('Background', 'flora'),
                        'subtitle'  => __('Set a footer widget area background.', 'flora'),
                        'output'    => array('#footer-widget'),
                        'background-repeat' => false,
                        'background-attachment' =>false,
                        'default'   => array(
                            'background-color'  => '#31291f',
                            'background-size'   => 'cover',
                            'background-position'   => 'center bottom'
                        ),
                    ),
                    array(
                        'id'       => 'footer_widget_overlay_color',
                        'type'     => 'color',
                        'required'  => array('footer_widget', '=', 1),
                        'title'    => __('Background Overlay Color', 'flora'), 
                        'subtitle' => __( 'Select background overlay color.', 'flora' ),
                        'default'  => '',
                        'validate' => 'color',
                    ),
                    array(
                        'id'        => 'footer_widget_overlay_opacity',
                        'type'      => 'select',
                        'required'  => array('footer_widget', '=', 1),
                        'title'     => __('Background Overlay Opacity', 'flora'),
                        'subtitle'  => __('Select background overlay opacity.', 'flora'),
                        'options'   => array(
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
                        'default'  => '0.8',
                    ),
                    array(
                        'id'        => 'footer_widget_columns',
                        'type'      => 'select',
                        'required'  => array('footer_widget', '=', 1),
                        'title'     => __('Columns', 'flora'),
                        'subtitle'  => __('Select the number of columns to display in the footer widget area.', 'flora'),
                        'options'   => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4'
                        ),
                        'default'   => '4'
                    ),
                    array(
                        'id'        => 'section_footer_bottom',
                        'type'      => 'section',
                        'title'     => __('Footer Bottom Bar', 'flora'),
                        'subtitle'  => __('Customize the footer bottom bar.', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'footer_bottom',
                        'type'      => 'switch',
                        'title'     => __('Footer Bottom Bar', 'flora'),
                        'subtitle'  => __('Display a footer bar at the bottom of the page.', 'flora'),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'footer_bottom_color',
                        'type'      => 'color',
                        'required'  => array('footer_bottom', '=', 1),
                        'title'     => __('Text Color', 'flora'),
                        'subtitle'  => __('Set a footer bottom bar text color.', 'flora'),
                        'transparent' => false,
                        'output'    => array('#footer-bottom'),
                        'default'   => '',
                    ),
                    array(
                        'id'        => 'footer_bottom_background',
                        'type'      => 'background',
                        'required'  => array('footer_bottom', '=', 1),
                        'title'     => __('Background', 'flora'),
                        'subtitle'  => __('Set a footer bottom bar background.', 'flora'),
                        'output'    => array('#footer-bottom'),
                        'background-repeat' => false,
                        'background-attachment' =>false,
                        'default'   => array(
                            'background-color'  => '#261f17',
                            'background-size'   => 'cover',
                            'background-position'   => 'center bottom'
                        ),
                    ),
                    array(
                        'id'        => 'footer_layout',
                        'type'      => 'image_select',
                        'required'  => array('footer_bottom', '=', 1),
                        'title'     => __('Layout', 'flora'),
                        'subtitle'  => __('Select footer layout.', 'flora'),
                        'options'   => array(
                            '1' => array('alt' => 'Version 1', 'img' => $template_directory . '/images/footers/1.jpg'),
                            '2' => array('alt' => 'Version 2', 'img' => $template_directory . '/images/footers/2.jpg'),
                            '3' => array('alt' => 'Version 3', 'img' => $template_directory . '/images/footers/3.jpg'),
                        ),
                        'default'   => '1'
                    ),
                    array(
                        'id'        => 'footer_logo',
                        'type'      => 'switch',
                        'required'  => array('footer_bottom', '=', 1),
                        'title'     => __('Footer Logo', 'flora'),
                        'subtitle'  => __('Display footer logo.', 'flora'),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'footer_logo_image',
                        'type'      => 'media',
                        'required'  => array('footer_logo', '=', 1),
                        'url'       => true,
                        'title'     => __('Footer Logo Image', 'flora'),
                        'height'    => '45px',
                        'readonly'  => false,
                        'subtitle'  => __('Upload a footer logo image, or you can specify an image URL directly.', 'flora'),
                        'default'   => array(        
                                            'url'=> $template_directory .'/images/logo/footer-logo.png'
                        ),
                    ),
                    array(
                        'id'        => 'footer_logo_retina',
                        'type'      => 'media',
                        'required'  => array('footer_logo', '=', 1),
                        'url'       => true,
                        'title'     => __('Footer Logo (Retina Version)', 'flora'),
                        'height'    => '90px',
                        'readonly'  => false,
                        'subtitle'  => __('Upload a retina logo image, or you can specify an image URL directly.', 'flora'),
                        'desc'      => __('It should be exactly 2x the size of normal logo.', 'flora'),
                        'default'   => array(        
                            'url'=> $template_directory .'/images/logo/footer-logo@2x.png'
                        ),
                    ),
                    array(
                        'id'        => 'footer_menu',
                        'type'      => 'switch',
                        'required'  => array('footer_bottom', '=', 1),
                        'title'     => __('Footer Menu', 'flora'),
                        'subtitle'  => __('Display footer menu.', 'flora'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'footer_social',
                        'type'      => 'switch',
                        'required'  => array('footer_bottom', '=', 1),
                        'title'     => __('Social Icons', 'flora'),
                        'subtitle'  => __('Display social icons.', 'flora'),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'footer_text',
                        'type'      => 'switch',
                        'required'  => array('footer_bottom', '=', 1),
                        'title'     => __('Footer Text', 'flora'),
                        'subtitle'  => __('Display footer text.', 'flora'),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'footer_text_content',
                        'type'      => 'editor',
                        'required'  => array('footer_text', '=', 1),
                        'args'   => array(
                            'teeny'            => false,
                            'textarea_rows'    => 3
                        ),
                        'default'   => '&copy;'. date('Y') .' Overlap - Premium WordPress Theme. Powered by <a href="https://wordpress.org/" target="_blank">WordPress</a>.',
                    )
                )
            );

            
            /***************************** 
            * Title Area
            ******************************/
            $this->sections['title_area'] = array(
                'icon'      => 'el-icon-photo',
                'title'     => __('Title Area', 'flora'),
                'heading'     => false,
                'fields'    => array(
                    array(
                        'id'        => 'section_page_title',
                        'type'      => 'section',
                        'title'     => __('Title Area', 'flora'),
                        'subtitle'  => __('Default settings for title area.', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'title_scroll_effect',
                        'type'      => 'select',
                        'title'     => __('Scrolling Effect', 'flora'),
                        'subtitle'  => __('Select a scrolling animation for title text and subtitle.', 'flora'),
                        'options'   => array(
                            'none' => __('None', 'flora'), 
                            'split' => __('Split', 'flora'),
                            'fadeOut' => __('Fade Out', 'flora'), 
                            'fadeOutUp' => __('Fade Out Up', 'flora'), 
                            'fadeOutDown' => __('Fade Out Down', 'flora'), 
                            'zoomIn' => __('Zoom In', 'flora'), 
                            'zoomInUp' => __('Zoom In Up', 'flora'), 
                            'zoomInDown' => __('Zoom In Down', 'flora'), 
                            'zoomOut' => __('Zoom Out', 'flora'), 
                            'zoomOutUp' => __('Zoom Out Up', 'flora'), 
                            'zoomOutDown' => __('Zoom Out Down', 'flora'), 
                        ),
                        'default' => 'fadeOut',
                    ),
                    array(
                        'id'       => 'title_color',
                        'type'     => 'color',
                        'title'    => __('Text Color', 'flora'), 
                        'subtitle' => __( 'Select the title text color.', 'flora' ),
                        'transparent'   => false,
                        'default'  => '',
                        'validate' => 'color',
                    ),
                    array(
                        'id'        => 'title_align',
                        'type'      => 'select',
                        'title'     => __('Alignment', 'flora'),
                        'subtitle'  => __('Select the title alignment.', 'flora'),
                        'options'   => array(
                            'none'  => __('Not Set', 'flora'),
                            'left'  => __('Left', 'flora'),
                            'center' => __('Center', 'flora'),
                            'right' => __('Right', 'flora')
                        ),
                        'default'   => 'none',
                    ),
                    array(
                        'id'        => 'title_size',
                        'type'      => 'select',
                        'title'     => __('Size', 'flora'),
                        'subtitle'  => __('Select the title size.', 'flora'),
                        'options'   => array(
                            's' => __('Small', 'flora'),
                            'm' => __('Medium', 'flora'),
                            'l'=> __('Large', 'flora'),
                            'full'=> __('Full Screen', 'flora')
                        ),
                        'default'   => 's',
                    ),
                    array(
                        'id'        => 'title_background_mode',
                        'type'      => 'select',
                        'title'     => __('Background', 'flora'),
                        'subtitle'  => __('Select background type.', 'flora'),
                        'options'   => array(
                            'none' => __('None', 'flora'),
                            'color' => __('Color', 'flora'),
                            'image' => __('Image', 'flora'),
                            'video'=> __('Video', 'flora')
                        ),
                        'default'   => 'color',
                    ),
                    array(
                        'id'        => 'title_background_image',
                        'type'      => 'background',
                        'required'  => array('title_background_mode', '=', 'image'),
                        'title'     => __('Background Image', 'flora'),
                        'background-color' => false,
                        'background-attachment' => false,
                        'background-repeat' => false,
                        'background-position' => false,
                        'subtitle'  => __('Customize background image.', 'flora'),
                        'default'   => array(
                            'background-size' => 'cover',
                        )
                    ),
                    array(
                        'id'        => 'title_background_video',
                        'type'      => 'media',
                        'required'  => array('title_background_mode', '=', 'video'),
                        'title'     => __('Background Video', 'flora'),
                        'subtitle'  => __('Customize background video.', 'flora'),
                        'url'       => true,
                        'mode'      => false,
                        'readonly'  => false
                    ),
                    array(
                        'id'        => 'title_background_color',
                        'type'      => 'color',
                        'required'  => array('title_background_mode', '!=', 'none'),
                        'title'     => __('Background Color', 'flora'),
                        'subtitle'  => __('Select a background color.', 'flora'),
                        'default'   => '',
                    ),
                    array(
                        'id'       => 'title_overlay_color',
                        'type'     => 'color',
                        'required'  => array('title_background_mode', 'contains', 'i'),
                        'title'    => __('Background Overlay Color', 'flora'), 
                        'subtitle' => __( 'Select background overlay color.', 'flora' ),
                        'default'  => '#211F1E',
                        'validate' => 'color',
                    ),
                    array(
                        'id'        => 'title_overlay_opacity',
                        'type'      => 'select',
                        'required'  => array('title_background_mode', 'contains', 'i'),
                        'title'     => __('Background Overlay Opacity', 'flora'),
                        'subtitle'  => __('Select background overlay opacity.', 'flora'),
                        'options'   => array(
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
                        'default' => '0.8',
                    ),
                    array(
                        'id'        => 'title_background_parallax',
                        'type'      => 'switch',
                        'required'  => array('title_background_mode', '=', 'image'),
                        'title'     => __('Parallax Background', 'flora'),
                        'subtitle'  => __('Enable parallax background when scrolling.', 'flora'),
                        'default'   => true,
                    ),

            ) );

            /***************************** 
            * Page
            ******************************/
            $this->sections['page'] = array(
                'icon'      => 'el-icon-website',
                'title'     => __('Page', 'flora'),
                'heading'     => false,
                'fields'    => array(
                    array(
                        'id'        => 'section_page_options',
                        'type'      => 'section',
                        'title'     => __('Page Options', 'flora'),
                        'subtitle'  => __('Choose default options for page.', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'onepage',
                        'type'      => 'switch',
                        'title'     => __('One Page Website', 'flora'),
                        'subtitle'  => __('Create One Page website, your frontpage will retrieve page content from primary menu automatically.', 'flora'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'page_layout',
                        'type'      => 'select',
                        'title'     => __('Layout', 'flora'),
                        'subtitle'  => __('Select a page layout, choose \'Boxed\' for create a Regular WordPress page with comments and sidebar, Wide for a Full Width page suited for the Visual Composer Page Builder.', 'flora'),
                        'options'   => array(
                            'boxed' => __('Boxed', 'flora'),
                            'wide' => __('Wide', 'flora'),
                        ),
                        'default'   => 'boxed',
                    ),
                    array(
                        'id'        => 'page_comments',
                        'type'      => 'switch',
                        'title'     => __('Comments', 'flora'),
                        'subtitle'  => __('Allow comments on Regular WordPress pages (Boxed Layout).', 'flora'),
                        'default'   => true,
                    )

                )
            );


            /***************************** 
            * Blog
            ******************************/
            $this->sections['blog'] = array(
                'icon'      => 'el-icon-edit',
                'title'     => __('Blog', 'flora'),
                'heading'   => false,
                'fields'    => array(
                    array(
                        'id'        => 'section_blog',
                        'type'      => 'section',
                        'title'     => __('Blog', 'flora'),
                        'subtitle'  => __('Customize blog page that shows your latest posts.', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'blog_sidebar',
                        'type'      => 'image_select',
                        'title'     => __('Sidebar', 'flora'),
                        'subtitle'  => __('Select sidebar position.', 'flora'),
                        'options'   => array(
                            '1' => array('alt' => 'No Sidebar', 'img' => $template_directory . '/images/columns/1.png'),
                            '2' => array('alt' => 'One Left', 'img' => $template_directory . '/images/columns/2.png'),
                            '3' => array('alt' => 'One Right', 'img' => $template_directory . '/images/columns/3.png'),
                        ),
                        'default'   => '3'
                    ),
                    array(
                        'id'        => 'blog_layout',
                        'type'      => 'image_select',
                        'title'     => __('Layout', 'flora'),
                        'subtitle'  => __('Select blog posts view.', 'flora'),
                        'options'   => array(
                            '' => array('alt' => 'Default', 'img' => $template_directory . '/images/blog/standard.jpg'),
                            'grid' => array('alt' => 'Grid',  'img' => $template_directory . '/images/blog/grid.jpg'),
                            'w-masonry' => array('alt' => 'Masonry', 'img' => $template_directory . '/images/blog/masonry.jpg'),
                        ),
                        'default'   => ''
                    ),
                    array(
                        'id'        => 'blog_excerpt',
                        'type'      => 'select',
                        'required'  => array('blog_layout', '=', ''),
                        'title'     => __('Excerpt', 'flora'),
                        'subtitle'  => __('Choose to display an excerpt or full content on blog posts view.', 'flora'),
                        'options'   => array(                            
                            0 => __('Full Content', 'flora'),
                            1 => __('Excerpt', 'flora'),
                        ),
                        'default'   => 0

                    ),    
                    array(
                        'id'        => 'blog_excerpt_length',
                        'type'      => 'text',
                        'required'  => array('blog_excerpt', '=', '1'),
                        'title'     => __('Excerpt Length', 'flora'),
                        'subtitle'  => __('Input the number of words for the post excerpts.', 'flora'),
                        'default'   => '55'
                    ),
                    array(
                        'id'        => 'blog_grid_columns',
                        'type'      => 'select',
                        'required'  => array('blog_layout', '=', 'grid'),
                        'title'     => __('Columns', 'flora'),
                        'subtitle'  => __('Select the number of grid columns.', 'flora'),
                        'options'   => array(
                            '2' => __('2 Columns', 'flora'),
                            '3' => __('3 Columns', 'flora'),
                            '4' => __('4 Columns', 'flora')
                        ),
                        'default'   => '3'

                    ),
                    array(
                        'id'        => 'blog_pagination',
                        'type'      => 'select',
                        'title'     => __('Pagination Type', 'flora'),
                        'subtitle'  => __('Select the pagination type for blog page.', 'flora'),
                        'options'   => array(
                            '1' => __('Numeric Pagination', 'flora'),
                            '2' => __('Infinite Scroll', 'flora'),
                            '3' => __('Next and Previous', 'flora'),
                        ),
                        'default'   => '3'
                    ),
                    array(
                        'id'        => 'blog_placeholder_image',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Placeholder Image', 'flora'),
                        'height'     => '540px',
                        'readonly'  => false,
                        'subtitle'  => __('Select a cover image placeholder.', 'flora'),
                        'desc'      => __('Recommended size: 960x540 px or larger.', 'flora'),
                        'default'  => array(        
                                'url'=> $template_directory .'/images/blog/placeholder.jpg',
                                'width' => '960',
                                'height' => '540px',
                        )
                    ),
                    array(
                        'id'        => 'section_blog_single',
                        'type'      => 'section',
                        'title'     => __('Blog Single Post', 'flora'),
                        'subtitle'  => __('Customize blog single post.', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'blog_single_sidebar',
                        'type'      => 'image_select',
                        'title'     => __('Sidebar', 'flora'),
                        'subtitle'  => __('Select sidebar position.', 'flora'),
                        'options'   => array(
                            '1' => array('alt' => 'No Sidebar', 'img' => $template_directory . '/images/columns/1.png'),
                            '2' => array('alt' => 'One Left', 'img' => $template_directory . '/images/columns/2.png'),
                            '3' => array('alt' => 'One Right', 'img' => $template_directory . '/images/columns/3.png'),
                        ),
                        'default'   => '1'
                    ),    
                    array(
                        'id'        => 'blog_single_image_size',
                        'type'      => 'select',
                        'title'     => __('Featured Image Size', 'flora'),
                        'subtitle'  => __('Select blog single post featured image size.', 'flora'),
                        'options'   => array(
                            'hide' => __('Hide Featured Image', 'flora'),
                            'preview-large' => __('Large (960 x 540)', 'flora'),
                            'full-width' => __('Full Width (1280 x 720)', 'flora'),
                            'full' => __('Original', 'flora'),
                        ),
                        'default'   => 'full'
                    ),    
                    array(
                        'id'        => 'blog_single_lightbox_size',
                        'type'      => 'select',
                        'title'     => __('Lightbox Image Size', 'flora'),
                        'subtitle'  => __('Select lightbox image size.', 'flora'),
                        'options'   => array(
                            'preview-large' => __('Large (960 x 540)', 'flora'),
                            'full-width' => __('Full Width (1280 x 720)', 'flora'),
                            'full' => __('Original', 'flora'),
                        ),
                        'default'   => 'full'
                    ),
                    array(
                        'id'        => 'blog_single_tags',
                        'type'      => 'switch',
                        'title'     => __('Post Tags', 'flora'),
                        'subtitle'  => __('Display post tags.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'blog_single_author',
                        'type'      => 'switch',
                        'title'     => __('Author Box', 'flora'),
                        'subtitle'  => __('Display author description box.', 'flora'),
                        'default'   => false
                    ),
                    array(
                        'id'        => 'blog_single_nav',
                        'type'      => 'switch',
                        'title'     => __('Post Navigation', 'flora'),
                        'subtitle'  => __('Display next and previous posts.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'blog_home',
                        'type'      => 'switch',
                        'required'  => array('blog_single_nav', '=', 1),
                        'title'     => __('Home Button', 'flora'),
                        'subtitle'  => __('Display a "Home" button on blog single post.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'blog_home_page',
                        'type'      => 'select',
                        'required'  => array('blog_home', '=', 1),
                        'title'     => __('Blog Home Page', 'flora'),
                        'subtitle'  => __('Select a blog home page.', 'flora'),
                        'options'   => array(
                            'default' => __('Default - Assigned Posts Page', 'flora'),
                            'custom' => __('Custom', 'flora'),                           
                        ),
                        'default'   => 'default'
                    ),
                    array(
                        'id'        => 'blog_home_url',
                        'type'      => 'text',
                        'required'  => array('blog_home_page', '=', 'custom'),
                        'title'     => __('Blog Home Page URL', 'flora'),
                        'subtitle'  => __('Home page URL for the "Home" button on blog single post.', 'flora'),
                        'default'   =>  esc_url( home_url() ). '/blog',
                    ),
                    array(
                        'id'        => 'blog_single_comment',
                        'type'      => 'switch',
                        'title'     => __('Comments', 'flora'),
                        'subtitle'  => __('Display comments box.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'blog_single_related',
                        'type'      => 'switch',
                        'title'     => __('Related Posts', 'flora'),
                        'subtitle'  => __('Display related posts.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'blog_single_related_title',
                        'type'      => 'text',
                        'required'  => array('blog_single_related', '=', 1),
                        'title'     => __('Related Posts Title', 'flora'),
                        'subtitle'  => __('The title of related posts box.', 'flora'),
                        'default'   => 'Related Posts'
                    ),
                    array(
                        'id'        => 'blog_single_related_posts',
                        'type'      => 'select',
                        'required'  => array('blog_single_related', '=', 1),
                        'title'     => __('Number of related posts', 'flora'),
                        'subtitle'  => __('Select the number of posts to show in related posts.', 'flora'),
                        'options'   => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                            '7' => '7',
                            '8' => '8',
                            '9' => '9',
                            '10' => '10',
                        ),
                        'default'   => '5'

                    ),
                    array(
                        'id'        => 'section_blog_meta',
                        'type'      => 'section',
                        'title'     => __('Blog Meta', 'flora'),
                        'subtitle'  => __('Customize blog meta data options.', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'blog_meta_date',
                        'type'      => 'switch',
                        'title'     => __('Post Date', 'flora'),
                        'subtitle'  => __('Display blog post date.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'blog_meta_author',
                        'type'      => 'switch',
                        'title'     => __('Author Name', 'flora'),
                        'subtitle'  => __('Display blog author meta data.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'blog_meta_category',
                        'type'      => 'switch',
                        'title'     => __('Category', 'flora'),
                        'subtitle'  => __('Display blog meta category.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'blog_meta_comment',
                        'type'      => 'switch',
                        'title'     => __('Comment', 'flora'),
                        'subtitle'  => __('Display blog meta comment.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'blog_meta_share',
                        'type'      => 'switch',
                        'title'     => __('Social Sharing Icons', 'flora'),
                        'subtitle'  => __('Display blog social sharing icons.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'section_blog_archive',
                        'type'      => 'section',
                        'title'     => __('Blog Archive', 'flora'),
                        'subtitle'  => __('Customize blog archive page, category page and author page.', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'blog_archive_sidebar',
                        'type'      => 'image_select',
                        'title'     => __('Sidebar', 'flora'),
                        'subtitle'  => __('Select sidebar position.', 'flora'),
                        'options'   => array(
                            '1' => array('alt' => 'No Sidebar', 'img' => $template_directory . '/images/columns/1.png'),
                            '2' => array('alt' => 'One Left', 'img' => $template_directory . '/images/columns/2.png'),
                            '3' => array('alt' => 'One Right', 'img' => $template_directory . '/images/columns/3.png'),
                        ),
                        'default'   => '1'
                    ),
                    array(
                        'id'        => 'blog_archive_layout',
                        'type'      => 'image_select',
                        'title'     => __('Layout', 'flora'),
                        'subtitle'  => __('Select blog posts view.', 'flora'),
                        'options'   => array(
                            '' => array('alt' => 'Default', 'img' => $template_directory . '/images/blog/standard.jpg'),
                            'grid' => array('alt' => 'Grid',  'img' => $template_directory . '/images/blog/grid.jpg'),
                            'w-masonry' => array('alt' => 'Masonry', 'img' => $template_directory . '/images/blog/masonry.jpg'),
                        ),
                        'default'   => ''
                    ),
                    array(
                        'id'        => 'blog_archive_grid_columns',
                        'type'      => 'select',
                        'required'  => array('blog_archive_layout', '=', 'grid'),
                        'title'     => __('Columns', 'flora'),
                        'subtitle'  => __('Select the number of grid columns.', 'flora'),
                        'options'   => array(
                            '2' => __('2 Columns', 'flora'),
                            '3' => __('3 Columns', 'flora'),
                            '4' => __('4 Columns', 'flora')
                        ),
                        'default'   => '3'

                    ),
                    array(
                        'id'        => 'blog_archive_pagination',
                        'type'      => 'select',
                        'title'     => __('Pagination Type', 'flora'),
                        'subtitle'  => __('Select the pagination type for blog page.', 'flora'),
                        'options'   => array(
                            '1' => __('Numeric Pagination', 'flora'),
                            '2' => __('Infinite Scroll', 'flora'),
                            '3' => __('Next and Previous', 'flora'),
                        ),
                        'default'   => '1'
                    )


                )
            );

            /***************************** 
            * Portfolio
            ******************************/
            $this->sections['portfolio'] = array(
                'icon'      => 'el el-folder-open',
                'title'     => __('Portfolio', 'flora'),
                'heading'   => false,
                'fields'    => array(
                    array(
                        'id'        => 'section_portfolio',
                        'type'      => 'section',
                        'title'     => __('Portfolio Options', 'flora'),
                        'subtitle'  => __('Customize the portfolio options.', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'portfolio_placeholder_image',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Placeholder Image', 'flora'),
                        'readonly'  => false,
                        'subtitle'  => __('Select a cover image placeholder.', 'flora'),
                        'desc'      => __('Recommended size: 640x640 px or larger.', 'flora'),
                        'default'  => array(        
                                'url' => $template_directory .'/images/portfolio/placeholder.jpg',
                                'width' => '640px',
                                'height' => '640px',
                        )
                    ),
                    array(
                        'id'        => 'section_portfolio_single',
                        'type'      => 'section',
                        'title'     => __('Portfolio Single Post', 'flora'),
                        'subtitle'  => __('Customize the portfolio single post.', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'portfolio_slug',
                        'type'      => 'text',
                        'title'     => __('Portfolio Slug', 'flora'),
                        'subtitle'  => __('Change/Rewrite the permalink when you use the permalink type as %postname%.', 'flora'),
                        'default'   => 'portfolio-item'
                    ),
                    array(
                        'id'        => 'portfolio_lightbox_size',
                        'type'      => 'select',
                        'title'     => __('Lightbox Image Size', 'flora'),
                        'subtitle'  => __('Select portfolio lightbox image size.', 'flora'),
                        'options'   => array(
                            'preview-large' => __('Large (960 x 540)', 'flora'),
                            'full-width' => __('Full Width (1280 x 720)', 'flora'),
                            'full' => __('Original', 'flora'),
                        ),
                        'default'   => 'full'
                    ),
                    array(
                        'id'        => 'portfolio_date',
                        'type'      => 'switch',
                        'title'     => __('Publish Date', 'flora'),
                        'subtitle'  => __('Display portfolio publish date.', 'flora'),                        
                        'default'   => true
                    ),
                    array(
                        'id'        => 'portfolio_nav',
                        'type'      => 'switch',
                        'title'     => __('Post Navigation', 'flora'),
                        'subtitle'  => __('Display next and previous posts.', 'flora'),                        
                        'default'   => true
                    ),
                    array(
                        'id'        => 'portfolio_home',
                        'type'      => 'switch',
                        'required'  => array('portfolio_nav', '=', 1),
                        'title'     => __('Home Button', 'flora'),
                        'subtitle'  => __('Display a "Home" button on portfolio single post.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'portfolio_home_url',
                        'type'      => 'text',
                        'required'  => array('portfolio_home', '=', 1),
                        'title'     => __('Portfolio Home Page', 'flora'),
                        'subtitle'  => __('Home page URL for the "Home" button on portfolio single post.', 'flora'),
                        'default'   =>  esc_url( home_url() ). '/portfolio',
                    ),
                    array(
                        'id'        => 'portfolio_related',
                        'type'      => 'switch',
                        'title'     => __('Related Posts', 'flora'),
                        'subtitle'  => __('Display related posts.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'portfolio_related_title',
                        'type'      => 'text',
                        'required'  => array('portfolio_related', '=', 1),
                        'title'     => __('Related Posts Title', 'flora'),
                        'subtitle'  => __('The title of related posts box.', 'flora'),
                        'default'   => 'Related Projects'
                    ),
                    array(
                        'id'        => 'portfolio_related_posts',
                        'type'      => 'text',
                        'required'  => array('portfolio_related', '=', 1),
                        'title'     => __('Number of related posts', 'flora'),
                        'subtitle'  => __('Select the number of posts to show in related posts.', 'flora'),
                        'default'   => '6'

                    ),
                    array(
                        'id'        => 'section_portfolio_archive',
                        'type'      => 'section',
                        'title'     => __('Portfolio Archive', 'flora'),
                        'subtitle'  => __('Customize the portfolio archive pages (Category, Skill and Tag).', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'portfolio_archive_background',
                        'type'      => 'background',
                        'title'     => __('Page Background', 'flora'),
                        'subtitle'  => __('Set portfolio archive page background.', 'flora'),
                        'output'    => array('.archive.tax-portfolio_category #content, .archive.tax-portfolio_skill #content, .archive.tax-portfolio_tag #content'),
                        'background-repeat' => false,
                        'background-attachment' =>false,
                        'default'   => array(
                            'background-color'   => '#f5f5f5',
                            'background-size'   => 'cover',
                            'background-position'   => 'center center'
                        ),
                    ),
                    array(
                        'id'        => 'portfolio_archive_layout',
                        'type'      => 'select',
                        'title'     => __('Layout', 'flora'),
                        'subtitle'  => __('Select portfolio layout for archive pages.', 'flora'),
                        'options'   => array(
                            'grid'  =>  __('Grid (Without Space)', 'flora'), 
                            'grid-space' => __('Grid (With Space)', 'flora'),
                            'masonry'   => __('Standard Masonry', 'flora'),
                            'w-masonry' => __('Flora Masonry', 'flora'),
                        ),
                        'default'   => 'masonry'
                    ),
                    array(
                        'id'        => 'portfolio_archive_grid_columns',
                        'type'      => 'select',
                        'required'  => array('portfolio_archive_layout', '!=', 'w-masonry'),
                        'title'     => __('Columns', 'flora'),
                        'subtitle'  => __('Select the number of grid columns.', 'flora'),
                        'options'   => array(
                            '2' => __('2 Columns', 'flora'),
                            '3' => __('3 Columns', 'flora'),
                            '4' => __('4 Columns', 'flora')
                        ),
                        'default'   => '4'

                    ),
                    array(
                        'id'        => 'portfolio_archive_pagination',
                        'type'      => 'select',
                        'title'     => __('Pagination Type', 'flora'),
                        'subtitle'  => __('Select the pagination type for portfolio archive pages.', 'flora'),
                        'options'   => array(
                            '1' => __('Infinite Scroll', 'flora'),
                            '2' => __('Show More Button', 'flora'),
                            'hide' => __('Hide', 'flora'),
                        ),
                        'default'   => '1'
                    )
                )
            );

            /***************************** 
            * WooCommerce
            ******************************/
            $this->sections['woocommerce'] = array(
                'icon'      => 'el-icon-shopping-cart',
                'title'     => __('WooCommerce', 'flora'),
                'heading'   => false,
                'fields'    => array(
                    array(
                        'id'        => 'section_shop',
                        'type'      => 'section',
                        'title'     => __('Shop Page', 'flora'),
                        'subtitle'  => __('Customize shop page.', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'shop_product_items',
                        'type'      => 'text',
                        'title'     => __('Number of Products per Page', 'flora'),
                        'subtitle'  => __('Enter the number of products per page.', 'flora'),
                        'validate'  => 'numeric',
                        'default'   => 8
                        
                    ),
                    array(
                        'id'        => 'shop_product_columns',
                        'type'      => 'select',
                        'title'     => __('Number of Columns', 'flora'),
                        'subtitle'  => __('Select the number of columns.', 'flora'),
                        'options'   => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                        ),
                        'default'   => '4'
                    ),
                    array(
                        'id'        => 'section_shop_single',
                        'type'      => 'section',
                        'title'     => __('Single Product', 'flora'),
                        'subtitle'  => __('Customize shop single product.', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'shop_single_sidebar',
                        'type'      => 'image_select',
                        'title'     => __('Shop Single Sidebar', 'flora'),
                        'subtitle'  => __('Select shop single product sidebar position.', 'flora'),
                        'options'   => array(
                            '1' => array('alt' => 'No Sidebar', 'img' => $template_directory . '/images/columns/1.png'),
                            '2' => array('alt' => 'One Left', 'img' => $template_directory . '/images/columns/2.png'),
                            '3' => array('alt' => 'One Right', 'img' => $template_directory . '/images/columns/3.png'),
                        ),
                        'default'   => '1'
                    ),
                    array(
                        'id'        => 'related_product_items',
                        'type'      => 'select',
                        'title'     => __('Number of Related Products', 'flora'),
                        'subtitle'  => __('Select the number of related products.', 'flora'),
                        'options'   => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                            '7' => '7',
                            '8' => '8',
                            '9' => '9',
                            '10' => '10',
                        ),
                        'default'   => '4'
                    ),
                    array(
                        'id'        => 'related_product_columns',
                        'type'      => 'select',
                        'title'     => __('Number of Columns', 'flora'),
                        'subtitle'  => __('Select the number of columns.', 'flora'),
                        'options'   => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ),
                        'default'   => '4'
                    )
                  )
            );

            /***************************** 
            * AJAX Page Options
            ******************************/
            $this->sections['ajax_page'] = array(
                'icon'      => 'el el-hourglass',
                'title'     => __('AJAX Page', 'flora'),
                'heading'     => false,
                'fields'    => array(
                    array(
                        'id'        => 'section_ajax_options',
                        'type'      => 'section',
                        'title'     => __('AJAX Options', 'flora'),
                        'subtitle'  => __('Turn on or off the AJAX page features.', 'flora'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'ajax_page',
                        'type'      => 'switch',
                        'title'     => __('Ajax Page', 'flora'),
                        'subtitle'  => __('Enable ajax page transitions.', 'flora'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'ajax_page_transition',
                        'type'      => 'select',
                        'required'  => array('ajax_page', '=', 1),
                        'title'     => __('Transitions', 'flora'),
                        'subtitle'  => __('Select a page transition effect.', 'flora'),
                        'options'   => array(
                            'fade' => __('Fade', 'flora'),
                            'slideToggle' => __('Slide Toggle', 'flora'),
                            'slideLeft' => __('Slide to Left', 'flora'),
                            'slideRight'=> __('Slide to Right', 'flora'),
                            'slideUp'=> __('Slide Up', 'flora'),
                            'slideDown'=> __('Slide Down', 'flora'),
                        ),
                        'default'   => 'fade',
                    ),
                    array(
                        'id'        => 'ajax_page_exclude_urls',
                        'type'      => 'multi_text',
                        'required'  => array('ajax_page', '=', 1),
                        'title'     => __('Exclude URLs', 'flora'),
                        'subtitle'  => __('Excludes the specific links from AJAX Page Loader. E.g. /shop/, /cart/, /checkout/, etc.', 'flora'),
                        'add_text'  => __('Add New', 'flora'),
                        'default'   => array(
                            '/shop/',
                            '/product/',
                            '/product-category/',
                            '/cart/',
                            '/checkout/',
                            '/my-account/',
                        ),
                    )
                )
            );

            /***************************** 
            * Search
            ******************************/
            $this->sections['search'] = array(
                'icon'      => 'el-icon-search',
                'title'     => __('Search', 'flora'),
                'heading'   => false,
                'fields'    => array(
                    array(
                        'id'        => 'search_sidebar',
                        'type'      => 'image_select',
                        'title'     => __('Sidebar', 'flora'),
                        'subtitle'  => __('Select search page sidebar position.', 'flora'),
                        'options'   => array(
                            '1' => array('alt' => 'No Sidebar',       'img' => $template_directory . '/images/columns/1.png'),
                            '2' => array('alt' => 'One Left',  'img' => $template_directory . '/images/columns/2.png'),
                            '3' => array('alt' => 'One Right', 'img' => $template_directory . '/images/columns/3.png'),
                        ),
                        'default'   => '1'
                    ),
                    array(
                        'id'        => 'search_items',
                        'type'      => 'text',
                        'title'     => __('Number of Search Results Per Page', 'flora'),
                        'subtitle'  => __('Select number of search results per page.', 'flora'),
                        'validate'  => 'numeric',
                        'default'   => '8'
                        
                    ),
                    array(
                        'id'        => 'search_show_image',
                        'type'      => 'switch',
                        'title'     => __('Show Featured Image.', 'flora'),
                        'subtitle'  => __('Display featured images in search results.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'search_show_date',
                        'type'      => 'switch',
                        'title'     => __('Show Post Date.', 'flora'),
                        'subtitle'  => __('Display post date in search results.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'search_show_author',
                        'type'      => 'switch',
                        'title'     => __('Show Author.', 'flora'),
                        'subtitle'  => __('Display post author in search results.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'ajax_search',
                        'type'      => 'switch',
                        'title'     => __('Ajax Search', 'flora'),
                        'subtitle'  => __('Enable ajax auto suggest search.', 'flora'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'section_ajax_search',
                        'type'      => 'section',
                        'required'  => array('ajax_search', '=', 1),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'search_post_type',
                        'type'      => 'checkbox',
                        'title'     => __('Post Types', 'flora'),
                        'subtitle'  => __('Select post types for ajax auto suggest search.', 'flora'),
                        'data'  => 'post_types',
                        'default'   => array(
                            'page' => true,
                            'post' => true,
                            'wyde_portfolio' => true,
                            'product'   => true
                        )
                    ),
                    array(
                        'id'        => 'search_suggestion_items',
                        'type'      => 'select',
                        'title'     => __('Number of Suggestion Items.', 'flora'),
                        'subtitle'  => __('Select number of search suggestion items per post type.', 'flora'),
                        'options'  => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                            '7' => '7',
                            '8' => '8',
                            '9' => '9',
                            '10' => '10',
                        ),
                        'default'   => '5'
                        
                    )
                  )
            );

            /***************************** 
            * Social Media
            ******************************/
            /*$social_fields = array(
                        array(
                            'id'        => 'section_social',
                            'type'      => 'section',
                            'title'     => __('Social Media', 'flora'),
                            'subtitle'  => __('Enter your social media URLs, then you can choose to display these in header and footer.', 'flora'),
                            'indent'    => 1,

                        )
            );*/

            $social_fields = array();

            $social_icons = wyde_get_social_icons(); // get social icons from inc/custom-functions.php

            foreach($social_icons as $key => $value){
               $social_fields[] = array(
                        'id'        => 'social_'. wyde_string_to_underscore_name($value),
                        'type'      => 'text',
                        'title'     => $value,
               ); 
            }

            $this->sections['social'] = array(
                'icon'      => 'el-icon-group',
                'title'     => __('Social Media', 'flora'),
                'fields'    => $social_fields
            );


            /***************************** 
            * Typography
            ******************************/
            $this->sections['typography'] = array(
                'icon'      => 'el-icon-font',
                'title'     => __('Typography', 'flora'),
                'desc'     => __('Customize font options for your site.', 'flora'),
                'fields'    => array(
                    array(
                        'id'            => 'font_body',
                        'type'          => 'typography',
                        'title'         => __('Body', 'flora'),
                        'subtitle'      => __('Font options for main body text.', 'flora'),
                        'google'        => true,    
                        'font-style'    => false, 
                        'line-height'   => false,
                        'all_styles'    => true,  
                        'letter-spacing'=> true,
                        'font-backup'   => true,
                        'units'         => 'px',
                        'output'        => array('body'),
                        'default'       => array(
                            'google'        => true,
                            'font-family'   => 'Lato',
                            'font-size'     => '15px',
                            'font-backup'   => "Arial, Helvetica, sans-serif"
                        ),
                        'preview' => array('text' => 'Body Text <br /> 1234567890 <br /> ABCDEFGHIJKLMNOPQRSTUVWXYZ <br /> abcdefghijklmnopqrstuvwxyz'),
                    ),                    
                    array(
                        'id'            => 'font_menu',
                        'type'          => 'typography',
                        'title'         => __('Menu', 'flora'),
                        'subtitle'      => __('Font options for main navigation menu.', 'flora'),
                        'google'        => true,    
                        'font-style'    => false, 
                        'color'    => false, 
                        'font-size'    => false, 
                        'text-align'    => false,
                        'all_styles'    => true,
                        'letter-spacing'=> true,
                        'font-backup'   => true,
                        'line-height'   => false,   
                        'units'         => 'px', 
                        'output'        => array('.top-menu, .vertical-menu, .live-search-form input'),
                        'default'       => array(
                            'google'        => true,
                            'font-family'   => 'Montserrat',
                            'letter-spacing'    => '0.5px',
                            'font-backup'   => "Arial, Helvetica, sans-serif"
                        ),
                        'preview' => array('text' => 'Main Menu <br /> 1234567890 <br /> ABCDEFGHIJKLMNOPQRSTUVWXYZ <br /> abcdefghijklmnopqrstuvwxyz'),
                    ),
                    array(
                        'id'            => 'font_buttons',
                        'type'          => 'typography',
                        'title'         => __('Buttons and Link Buttons', 'flora'),
                        'subtitle'      => __('Font options for buttons and link buttons.', 'flora'),
                        'google'        => true,    
                        'font-style'    => false, 
                        'color'    => false, 
                        'font-size'    => false, 
                        'text-align'    => false,
                        'all_styles'    => true,
                        'letter-spacing'=> true,
                        'font-backup'   => true,
                        'line-height'   => false,   
                        'units'         => 'px', 
                        'output'        => array('.w-button, .w-link-button, .w-ghost-button, a.button, button, input[type="submit"], input[type="button"], input[type="reset"]'),
                        'default'       => array(
                            'google'        => true,
                            'font-family'   => 'Montserrat',
                            'letter-spacing'    => '0.5px',
                            'font-backup'   => "Arial, Helvetica, sans-serif"
                        ),
                        'preview' => array('text' => 'Buttons <br /> 1234567890 <br /> ABCDEFGHIJKLMNOPQRSTUVWXYZ <br /> abcdefghijklmnopqrstuvwxyz'),
                    ),                     
                    array(
                        'id'            => 'font_h1',
                        'type'          => 'typography',
                        'title'         => __('H1', 'flora'),
                        'subtitle'      => __('Font options for H1.', 'flora'),
                        'google'        => true,    
                        'font-style'    => false, 
                        'line-height'   => false,   
                        'all_styles'    => true,
                        'letter-spacing'=> true,
                        'font-backup'   => true,
                        'units'         => 'px', 
                        'output'        => array('h1'),
                        'default'       => array(
                            'google'        => true,
                            'font-family'   => 'Montserrat',
                            'font-size'     => '48px',
                            'font-weight'     => '700',
                            'font-backup'   => "Arial, Helvetica, sans-serif"
                        ),
                        'preview' => array('text' => 'Heading 1 <br /> 1234567890 <br /> ABCDEFGHIJKLMNOPQRSTUVWXYZ <br /> abcdefghijklmnopqrstuvwxyz'),
                    ),                     
                    array(
                        'id'            => 'font_h2',
                        'type'          => 'typography',
                        'title'         => __('H2', 'flora'),
                        'subtitle'      => __('Font options for H2.', 'flora'),
                        'google'        => true,    
                        'font-style'    => false, 
                        'line-height'   => false,   
                        'all_styles'    => true,
                        'letter-spacing'=> true,
                        'font-backup'   => true,
                        'units'         => 'px', 
                        'output'        => array('h2'),
                        'default'       => array(
                            'google'        => true,
                            'font-family'   => 'Montserrat',
                            'font-size'     => '28px',
                            'font-weight'     => '700',
                            'font-backup'   => "Arial, Helvetica, sans-serif"
                         ),
                        'preview' => array('text' => 'Heading 2 <br /> 1234567890 <br /> ABCDEFGHIJKLMNOPQRSTUVWXYZ <br /> abcdefghijklmnopqrstuvwxyz'),
                    ),                     
                    array(
                        'id'            => 'font_h3',
                        'type'          => 'typography',
                        'title'         => __('H3', 'flora'),
                        'subtitle'      => __('Font options for H3.', 'flora'),
                        'google'        => true,    
                        'font-style'    => false, 
                        'line-height'   => false,   
                        'all_styles'    => true,
                        'letter-spacing'=> true,
                        'font-backup'   => true,
                        'units'         => 'px', 
                        'output'        => array('h3'),
                        'default'       => array(
                            'google'        => true,
                            'font-family'   => 'Montserrat',
                            'font-size'     => '22px',
                            'font-weight'     => '700',
                            'font-backup'   => "Arial, Helvetica, sans-serif"
                         ),
                        'preview' => array('text' => 'Heading 3 <br /> 1234567890 <br /> ABCDEFGHIJKLMNOPQRSTUVWXYZ <br /> abcdefghijklmnopqrstuvwxyz'),
                    ),                     
                    array(
                        'id'            => 'font_h4',
                        'type'          => 'typography',
                        'title'         => __('H4', 'flora'),
                        'subtitle'      => __('Font options for H4.', 'flora'),
                        'google'        => true,    
                        'font-style'    => true, 
                        'font-size'     => false, 
                        'line-height'   => false, 
                        'color'         => false, 
                        'text-align'    => false, 
                        'subsets'       => false, 
                        'all_styles'    => false,
                        'letter-spacing'=> false,
                        'font-backup'   => true,
                        'units'         => 'px', 
                        'output'        => array('h4'),
                        'default'       => array(
                            'google'        => true,
                            'font-family'   => 'Montserrat',
                            'font-weight'     => '700',
                            'font-backup'   => "Arial, Helvetica, sans-serif"
                         ),
                        'preview' => array('text' => 'Heading 4 <br /> 1234567890 <br /> ABCDEFGHIJKLMNOPQRSTUVWXYZ <br /> abcdefghijklmnopqrstuvwxyz'),
                    ),                     
                    array(
                        'id'            => 'font_h5',
                        'type'          => 'typography',
                        'title'         => __('H5', 'flora'),
                        'subtitle'      => __('Font options for H5.', 'flora'),
                        'google'        => true,    
                        'font-style'    => true, 
                        'font-size'     => false, 
                        'line-height'   => false, 
                        'color'         => false, 
                        'text-align'    => false, 
                        'subsets'       => false, 
                        'all_styles'    => false,
                        'letter-spacing'=> false,
                        'font-backup'   => true,
                        'units'         => 'px', 
                        'output'        => array('h5'),
                        'default'       => array(
                            'google'        => true,
                            'font-family'   => 'Lobster Two',
                            'font-weight'   => '700',
                            'font-style'    => 'italic', 
                            'font-backup'   => "Arial, Helvetica, sans-serif"
                         ),
                        'preview' => array('text' => 'Heading 5 <br /> 1234567890 <br /> ABCDEFGHIJKLMNOPQRSTUVWXYZ <br /> abcdefghijklmnopqrstuvwxyz'),
                    ),                     
                    array(
                        'id'            => 'font_h6',
                        'type'          => 'typography',
                        'title'         => __('H6', 'flora'),
                        'subtitle'      => __('Font options for H6.', 'flora'),
                        'google'        => true,    
                        'font-style'    => true, 
                        'font-size'     => false, 
                        'line-height'   => false, 
                        'color'         => false, 
                        'text-align'    => false, 
                        'subsets'       => false, 
                        'all_styles'    => false,
                        'letter-spacing'=> false,
                        'font-backup'   => true,
                        'units'         => 'px', 
                        'output'        => array('h6'),
                        'default'       => array(
                            'google'        => true,
                            'font-family'   => 'Lora',
                            'font-weight'    => '400', 
                            'font-style'    => 'italic', 
                            'font-backup'   => "Arial, Helvetica, sans-serif"
                         ),
                        'preview' => array('text' => 'Heading 6 <br /> 1234567890 <br /> ABCDEFGHIJKLMNOPQRSTUVWXYZ <br /> abcdefghijklmnopqrstuvwxyz'),
                    ),                
                )
            );

            /***************************** 
            * Advanced
            ******************************/
            $this->sections['advanced'] = array(
                'icon'      => 'el-icon-wrench',
                'title'     => __('Advanced', 'flora'),
                'heading'   => false,
                'fields'    => array(
                    array(
                        'id'        => 'head_script',
                        'type'      => 'ace_editor',
                        'title'     => __('Head Content', 'flora'),
                        'subtitle'  => __('Enter HTML/JavaScript/StyleSheet. This will be added into the head tag. You can also add a Google Analytics code, Google Verification code or custom Meta HTTP Content here.', 'flora'),
                        'mode'  => 'html'
                        
                    ),
                    array(
                        'id'        => 'footer_script',
                        'type'      => 'ace_editor',
                        'title'     => __('Body Content', 'flora'),
                        'subtitle'  => __('Enter HTML/JavaScript/StyleSheet. This will be added into the footer of all pages.', 'flora'),
                        'mode'  => 'html'
                        
                    ),
                  )
            );
                       
            /***************************** 
            * Import / Export
            ******************************/
            $this->sections['import_export'] = array(
                'title'     => __('Import / Export', 'flora'),
                'desc'      => __('Import and Export your Redux Framework settings from file, text or URL.', 'flora'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => __('Import Export', 'flora'),
                        'subtitle'      => __('Save and restore your Theme options', 'flora'),
                        'full_width'    => false,
                    ),
                ),
            );                     

            /***************************** 
            * Theme Information
            ******************************/
            $this->theme    = wp_get_theme();
            $item_name      = $this->theme->get('Name');
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';

            $customize_title = __('Customize', 'flora') . ' '. $this->theme->display('Name');

            ob_start();
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')): ?>
                        <a href="<?php echo esc_url( wp_customize_url() ); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php echo esc_attr($item_name);?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php echo esc_attr($item_name);?>" />
                <?php endif; ?>
                <h4><?php echo esc_html( $this->theme->display('Name') ); ?></h4>
                <div>
                    <p><?php echo __('By', 'flora'). ' '. $this->theme->display('Author') ; ?></p>
                    <p><?php echo __('Version', 'flora'). ' '. esc_html( $this->theme->display('Version') ); ?></p>
                    <p><?php echo '<strong>' . __('Tags', 'flora') . ':</strong> '; ?><?php echo esc_html( $this->theme->display('Tags') ); ?></p>
                    <p class="theme-description"><?php echo wp_kses_post( $this->theme->display('Description') ); ?></p>
            <?php
            if ($this->theme->parent()) {
               echo '<p class="howto">' . __('This <a href="http://codex.wordpress.org/Child_Themes">child theme</a> requires its parent theme', 'flora') . ', '. esc_html( $this->theme->parent()->display('Name') ). '</p>';
            }
            ?>
                </div>
            </div>
            <?php
            $item_info = ob_get_clean();

            $this->sections[] = array(
                'type' => 'divide',
            );

            $this->sections['theme_info'] = array(
                'icon'      => 'el el-info-circle',
                'title'     => __('Theme Information', 'flora'),
                'desc'      => '<p class="description">' . __('For more information and features about this theme, please visit', 'flora') . ' <a href="'. esc_url( $this->theme->display('AuthorURI') ) .'" target="_blank">'. esc_url( $this->theme->display('AuthorURI') ) . '</a>.</p>',
                'fields'    => array(
                    array(
                        'id'        => 'raw-theme-info',
                        'type'      => 'raw',
                        'content'   => $item_info,
                    )
                ),
            );

        }

        /** Set framework arguments **/
        public function setArguments() {

           $this->args = array(
                'display_name' => $this->theme->get('Name'),
                'display_version' =>  $this->theme->get('Version'),
                'opt_name' => 'wyde_options',
                'page_slug' => 'theme-options',
                'page_title' =>  __('Theme Options', 'flora'),
                'menu_type' => 'menu',
                'menu_title' => __('Theme Options', 'flora'),
                'page_parent'  => 'themes.php',
                'allow_sub_menu' => false,
                'customizer' => true,
                'update_notice' => false,
                'dev_mode'  => false,
                'admin_bar' => true,
                'admin_bar_icon'    => 'dashicons-admin-generic',
                'hints' => 
                        array(
                          'icon' => 'el-icon-question-sign',
                          'icon_position' => 'right',
                          'icon_size' => 'normal',
                          'tip_style' => 
                          array(
                            'color' => 'light',
                          ),
                          'tip_position' => 
                          array(
                            'my' => 'top left',
                            'at' => 'bottom right',
                          ),
                          'tip_effect' => 
                          array(
                            'show' => 
                            array(
                              'duration' => '500',
                              'event' => 'mouseover',
                            ),
                            'hide' => 
                            array(
                              'duration' => '500',
                              'event' => 'mouseleave unfocus',
                            ),
                          ),
                ),
                'output' => true,
                'compiler'  => true,
                'output_tag' => true,
                'menu_icon' => '',
                'page_icon' => 'icon-themes',
                'page_permissions' => 'manage_options',
                'save_defaults' => true,
                'show_import_export' => true,
                'transient_time' => '3600',
                'network_sites' => true,
                'disable_tracking'  => true,
                'google_api_key'   => 'AIzaSyBss9ufj66aGyREW-VQdhuDSJ4opKsD-4U',//Replace with your Google API KEY
                'async_typography' => false,
                'intro_text' => '',
                'footer_text' => '',
                'footer_credit' => '<span id="footer-thankyou">Thank you for creating with <a href="https://wordpress.org/">WordPress</a>.</span>'
              );

            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'http://themeforest.net/user/Wyde',
                'title' => 'Help',
                'icon'  => 'el-icon-question-sign'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://themeforest.net/user/Wyde/follow',
                'title' => 'Follow us on ThemeForest',
                'icon'  => 'el-icon-heart'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://themeforest.net/downloads',
                'title' => 'Give me a rating on ThemeForest',
                'icon'  => 'el-icon-star'
            );
            

        }

    }
    
    global $wyde_theme_options;
    
    $wyde_theme_options = new Flora_Theme_Options();

}