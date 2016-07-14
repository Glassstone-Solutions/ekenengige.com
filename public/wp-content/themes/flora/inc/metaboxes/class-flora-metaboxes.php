<?php

if( !class_exists('Flora_MetaBoxes') ){
    
    class Flora_MetaBoxes{

        protected $post_type = ''; 

        function __construct(){
            global $pagenow;
		    if ( is_admin() && ( $pagenow == 'post-new.php' || $pagenow == 'post.php' ) ) {
                if( defined( 'CMB2_LOADED' ) && CMB2_LOADED == true ){
                    add_action( 'cmb2_init', array($this, 'register_metaboxes') );
                    add_action( 'cmb2_before_form', array($this, 'before_form') );
                    add_action( 'cmb2_after_form', array($this, 'after_form') );
                    add_action( 'add_meta_boxes', array( $this, 'add_metaboxes' ) );
                    add_action( 'admin_enqueue_scripts', array($this, 'load_metaboxes_scripts'), 100);
                    add_action( 'do_meta_boxes', array($this, 'remove_meta_boxes') );
                }
            }
        }

        /** Register Metaboxes **/
        public function register_metaboxes() {

            $this->post_type = $this->get_current_post_type();
            switch($this->post_type){
                case 'post':
                case 'page':
                case 'wyde_portfolio':
                case 'wyde_team_member':
                case 'wyde_testimonial':
                include $this->post_type .'-options.php';
                break;
            }

        }

        /**
        * gets the current post type in the WordPress Admin
        */
        public function get_current_post_type() {
            global $post, $typenow, $current_screen;
	
            //we have a post so we can just get the post type from that
            if ( $post && $post->post_type ){
                return $post->post_type;
            }//check the global $typenow - set in admin.php
            elseif( $typenow ){
                return $typenow;
            }//check the global $current_screen object - set in sceen.php
            elseif( $current_screen && $current_screen->post_type ){
                return $current_screen->post_type;
            }//lastly check the post_type querystring
            elseif( isset( $_REQUEST['post_type'] ) ){
                return sanitize_key( $_REQUEST['post_type'] );
            }else{
                return isset($_REQUEST['post']) ? get_post_type( $_REQUEST['post'] ) : 'post';
            }
	
        }

        public function add_metaboxes(){
            $this->post_type = $this->get_current_post_type();
            add_meta_box( 'wyde_options', __('Flora Options', 'flora'), array( $this, 'show_metaboxes' ), $this->post_type, 'normal', 'high' );
        }

        public function show_metaboxes(){
            echo '<div class="w-tour clear">';
            echo '<ul class="w-tabs-nav">';
            $metaboxes = CMB2_Boxes::get_all();
            foreach( $metaboxes as $metabox){
                $icon = '';
                if( $metabox->prop('icon') ) $icon = '<i class="'.$metabox->prop('icon').'"></i>';
                echo sprintf('<li><a href="#">%s%s</a></li>', $icon, $metabox->prop('title'));
            }
            echo '</ul>';
            echo '<div class="w-tab-wrapper">';
            do_action('wyde_add_meta_box');
            echo '</div>';
            echo '</div>';
        }

        public function before_form(){
            echo '<div class="w-tab">';
        }

        public function after_form(){
            echo '</div>';
        }

        /** Load Options Scripts **/
        public function load_metaboxes_scripts($hook) {
            if( $hook != 'post.php' && $hook != 'post-new.php' ) 
                return;
 
            $template_directory = get_template_directory_uri();

            wp_enqueue_style( 'metabox-custom', $template_directory.'/inc/metaboxes/css/custom.css',  null, null);
   
            wp_enqueue_script( 'jquery-cookie', $template_directory.'/inc/metaboxes/js/jquery.cookie.js', null, null, true );
            wp_enqueue_script( 'metabox-options', $template_directory.'/inc/metaboxes/js/wyde-options.js', null, null, true );

        }
        
        /** Remove Metaboxes **/
        public function remove_meta_boxes() {

            /** Remove Revolution Slider Metabox **/
	        remove_meta_box( 'mymetabox_revslider_0', 'page', 'normal' );
	        remove_meta_box( 'mymetabox_revslider_0', 'post', 'normal' );
	        remove_meta_box( 'mymetabox_revslider_0', 'wyde_portfolio', 'normal' );
	        remove_meta_box( 'mymetabox_revslider_0', 'wyde_testimonial', 'normal' );
	        remove_meta_box( 'mymetabox_revslider_0', 'wyde_team_member', 'normal' );


            /** Remove VC Custom Teaser Metabox **/
	        remove_meta_box( 'vc_teaser', 'page', 'side' );
	        remove_meta_box( 'vc_teaser', 'post', 'side' );
	        remove_meta_box( 'vc_teaser', 'wyde_portfolio', 'side' );
        }

    }
}

new Flora_MetaBoxes();