<?php
function flora_child_theme_enqueue_styles() {
	$theme_info = wp_get_theme('flora');
    $version = $theme_info->get( 'Version' );
    wp_enqueue_style( 'flora', get_template_directory_uri() . '/style.css', null, $version );
    wp_enqueue_style( 'flora-theme' );
    wp_enqueue_style( 'flora-icons' );
    wp_enqueue_style( 'flora-animation' );
    wp_enqueue_style( 'flora-child', get_stylesheet_directory_uri() . '/style.css', array( 'flora-theme' ), $version );
}
add_action( 'wp_enqueue_scripts', 'flora_child_theme_enqueue_styles' );


/* 
 *Add Your Custom Icons to Icon Picker, this method will append your icons into Flora icon set.
 *Please don't forget to upload your font files to the host and add your icon CSS class to style.css.
 */

/*
function flora_get_custom_icons( $icons ){

    $custom_icons =  array(
		array( "custom-1" => "Custom 1" ),
		array( "custom-2" => "Custom 2" ),
		array( "custom-2" => "Custom 3" ), 
    );        
      
    return array_merge_recursive( $icons, $custom_icons );

}
add_filter( 'flora_iconpicker_icons', 'flora_get_custom_icons' );

function flora_admin_enqueue_icons() {
    wp_enqueue_style( 'flora-custom-icons', get_stylesheet_directory_uri() . '/style.css', array( 'flora-icons' ) );
}
add_action( 'admin_enqueue_scripts', 'flora_admin_enqueue_icons' );
*/


/*
function flora_get_social_media_icons( $icons ){

    $icons['fa fa-delicious'] =  'Delicious';
    $icons['fa fa-foursquare'] =  'Foursquare';
      
    return $icons;

}
add_filter( 'wyde_social_media_icons', 'flora_get_social_media_icons' );
*/