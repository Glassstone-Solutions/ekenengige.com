<?php

    global $wyde_options;

	$prefix = '_w_';
  
    $template_directory = get_template_directory_uri();


    /***************************** 
    * Single Post Options 
    ******************************/
    $post_options = new_cmb2_box( array(
		'id'         => 'post_options',
		'title'      => __( 'Single Post', 'flora' ),
        'icon'         => 'dashicons dashicons-media-document',
		'object_types'      => array('post'),
	) );

    /** Embeds Options **/
    $post_options->add_field( array(
        'id'       => $prefix . 'media_info',
		'name'     => __( 'Media Options', 'flora' ),
        'desc'     => __('You can insert media URL from any major video and audio hosting service (Youtube, Vimeo, DailyMotion, SoundCloud, Mixcloud, WordPress.tv, etc.). Supports services listed at <a href="http://codex.wordpress.org/Embeds" target="_blank">http://codex.wordpress.org/Embeds</a>', 'flora'),
		'type'     => 'title',
	));

    $post_options->add_field( array(
        'id'   => $prefix . 'embed_url',
		'name' => __( 'Media URL', 'flora' ),
		'desc' => __( 'Insert a media URL.', 'flora' ),
		'type' => 'oembed'
	));

    
    /** Gallery Options **/
    $post_options->add_field( array(
        'id'       => $prefix . 'gallery_info',
		'name'     => __( 'Gallery Options', 'flora' ),
		'type'     => 'title',
	));

    $post_options->add_field( array(
        'id'   => $prefix . 'gallery_images',
		'name' => __( 'Images', 'flora' ),
		'desc' => __( 'Upload or select images from media library. Recommended size: 960x540px or larger.', 'flora' ),
		'type' => 'file_list',
        'preview_size' => 'thumbnail', 
	));


    /** Post Format Link Options **/
    $post_options->add_field( array(
        'id'       => $prefix . 'link_info',
		'name'     => __( 'Link Options.', 'flora' ),
        'desc' => __( 'Extra options for Post Format Link.', 'flora' ),
		'type'     => 'title',
	));

    $post_options->add_field( array(
        'id'   => $prefix . 'post_link',
		'name' => __( 'Post URL', 'flora' ),
		'desc' => __( 'Insert a post URL.', 'flora' ),
		'type' => 'text_url'
	));
      

    /** Quote Options **/
    $post_options->add_field( array(
        'id'       => $prefix . 'quote_info',
		'name'     => __( 'Quote Options', 'flora' ),
        'desc' => __( 'Extra options for Post Format Quote.', 'flora' ),
		'type'     => 'title',
	));

    $post_options->add_field( array(
        'id'   => $prefix . 'post_quote',
		'name' => __( 'Quote', 'flora' ),
		'desc' => __( 'Insert quote here.', 'flora' ),
		'type' => 'textarea_small'
	));

    $post_options->add_field( array(
        'id'   => $prefix . 'post_quote_author',
		'name' => __( 'Author', 'flora' ),
		'desc' => __( 'Insert quote\'s author.', 'flora' ),
		'type' => 'text_medium'
	));   
    
    /* Post Options */
    $post_options->add_field( array(
        'id'         => $prefix . 'post_author',
		'name'       => __( 'Author Info', 'flora' ),
		'desc'       => __( 'Display author description box.', 'flora' ),
		'type'    => 'select',
		'options' => array(
            ''   => __('Default', 'flora'),
            'show'   => __('Show', 'flora'),
            'hide'   => __('Hide', 'flora'),
        )
	) );

    $post_options->add_field( array(
        'id'         => $prefix . 'post_related',
		'name'       => __( 'Related Posts', 'flora' ),
		'desc'       => __( 'Display related posts.', 'flora' ),
		'type'    => 'select',
		'options' => array(
            ''   => __('Default', 'flora'),
            'show'   => __('Show', 'flora'),
            'hide'   => __('Hide', 'flora'),
        )
	) ); 

    /***************************** 
    * Header Options 
    ******************************/
    $header_options = new_cmb2_box( array(
		'id'            => 'header_options',
		'title'         => __( 'Header', 'flora' ),
		'icon'         => 'dashicons dashicons-menu',
		'object_types'  => array('post'),
	) );

    $header_options->add_field( array(
        'id'         => $prefix . 'page_header',
		'name'       => __( 'Display Header', 'flora' ),
		'desc'       => __( 'Show or hide the header.', 'flora' ),
		'type'    => 'select',
		'options' => array(
            ''   => __('Show', 'flora'),
            'hide'   => __('Hide', 'flora'),
        )
	) );


    $header_options->add_field( array(
        'id'         => $prefix . 'header_transparent',
		'name'       => __( 'Transparent Header', 'flora' ),
		'desc'       => __( 'Select a transparent header.', 'flora' ),
		'type'    => 'select',
		'options' => array(
            ''   => __('Disable', 'flora'),
            'true'   => __('Enable', 'flora'),
        )
	) );

    $header_options->add_field( array(
		'id'      => $prefix . 'header_text_style',
		'name'    => __( 'Foreground Style', 'flora' ),
		'desc'    => __( 'Select a header navigation foreground style.', 'flora' ),
		'type'       => 'select',
        'options'    => array(
            ''   => __('Default', 'flora'),
            'light' => __('Light', 'flora'),
            'dark' => __('Dark', 'flora')
        ),
        'default'  => ''
	) );

    
    /***************************** 
    * Title Options 
    ******************************/
    $title_options = new_cmb2_box( array(
		'id'            => 'title_options',
		'title'         => __( 'Title Area', 'flora' ),
        'icon'         => 'dashicons dashicons-feedback',
		'object_types'  => array('post'),
	) );

    $title_options->add_field( array(
        'id'         => $prefix . 'post_custom_title',
		'name'       => __( 'Title Area', 'flora' ),
		'desc'       => __( 'Use custom title area for this post.', 'flora' ),
		'type'       => 'checkbox'
	) );

    $title_options->add_field( array(
        'id'         => $prefix . 'title_area',
		'name'       => __( 'Display Title Area', 'flora' ),
		'desc'       => __( 'Show or Hide the page title area.', 'flora' ),
		'type'       => 'select',
        'options'    => array(
            'hide' => __('Hide', 'flora'),
            'show' => __('Show', 'flora')
        ),
        'default'  => 'hide'
	) );

    $title_options->add_field( array(				
        'id'   => $prefix . 'page_title',
		'name' => __( 'Page Title', 'flora' ),
		'desc' => __( 'Custom text for the page title.', 'flora' ),
		'type' => 'textarea_code',
        'default' => isset( $_GET['post'] ) ? get_the_title( $_GET['post'] ) : ''
	) );

    $title_options->add_field( array(				
        'id'   => $prefix . 'subtitle',
		'name' => __( 'Subtitle', 'flora' ),
		'desc' => __( 'This text will display as subheading in the title area.', 'flora' ),
		'type' => 'textarea_code',
        'default' => ''
	) );

    $title_options->add_field( array(
		'id'   => $prefix . 'title_scroll_effect',
		'name' => __( 'Scrolling Effect', 'flora' ),
		'desc' => __( 'Select a scrolling animation for title text and subtitle.', 'flora' ),
		'type' => 'select',
        'options'   => array(
            '' => __('Default', 'flora'), 
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
        'default'  => ''
	));

    $title_options->add_field( array(
		'id'      => $prefix . 'title_color',
		'name'    => __( 'Text Color', 'flora' ),
		'desc'    => __( 'Select the title text color.', 'flora' ),
		'type'    => 'colorpicker',
		'default' => ''
	) );

    $title_options->add_field( array(				
        'id'   => $prefix . 'title_align',
		'name' => __( 'Alignment', 'flora' ),
		'desc' => __( 'Select the title text alignment.', 'flora' ),
		'type' => 'select',
        'options' => array(
            '' => __('Default', 'flora'), 
            'left' => __('Left', 'flora'), 
            'center' => __('Center', 'flora'), 
            'right' => __('Right', 'flora'), 
            ),
        'default' => ''
	) );

    $title_options->add_field( array(				
        'id'   => $prefix . 'title_size',
		'name' => __( 'Size', 'flora' ),
		'desc' => __( 'Select a title area size.', 'flora' ),
		'type' => 'select',
        'options' => array(
            '' => __('Default', 'flora'), 
            's' => __('Small', 'flora'), 
            'm' => __('Medium', 'flora'), 
            'l' => __('Large', 'flora'), 
            'full' => __('Full Screen', 'flora'), 
            ),
        'default' => ''
	));

    $title_options->add_field( array(				
        'id'   => $prefix . 'title_background',
		'name' => __( 'Background', 'flora' ),
		'desc' => __( 'Select a background type for the title area.', 'flora' ),
		'type' => 'select',
        'options' => array(
            '' => __('Default', 'flora'), 
            'none' => __('None', 'flora'), 
            'color' => __('Color', 'flora'), 
            'image' => __('Image', 'flora'), 
            'video' => __('Video', 'flora')
            ),
        'default' => ''
	));

    $title_options->add_field( array(
		'id'   => $prefix . 'title_background_image',
        'name' => __( 'Background Image', 'flora' ),
		'desc' => __( 'Upload an image or insert a URL.', 'flora' ),
		'type' => 'file'
	));


    $title_options->add_field( array(
		'id'   => $prefix . 'title_background_video',
        'name' => __( 'Background Video', 'flora' ),
		'desc' => __( 'Upload a video or insert a URL.', 'flora' ),
		'type' => 'file'
	));

    $title_options->add_field( array(
		'id'      => $prefix . 'title_background_color',
		'name'    => __( 'Background Color', 'flora' ),
		'desc'    => __( 'Choose a background color.', 'flora' ),
		'type'    => 'colorpicker',
		'default' => ''
	));

    $title_options->add_field( array(
		'id'   => $prefix . 'title_background_size',
		'name' => __( 'Background Size', 'flora' ),
		'desc' => __( 'For full width or high-definition background image, choose Cover. Otherwise, choose Contain.', 'flora' ),
		'type' => 'select',
        'options'   => array(
            'cover' => __('Cover', 'flora'), 
            'contain' => __('Contain', 'flora')
            ),
        'default'  => 'cover'
	));

    $title_options->add_field( array(
		'id'      => $prefix . 'title_overlay_color',
		'name'    => __( 'Background Overlay Color', 'flora' ),
		'desc'    => __( 'Select background overlay color.', 'flora' ),
		'type'    => 'colorpicker',
		'default' => ''
	));

    $title_options->add_field( array(
		'id'      => $prefix . 'title_overlay_opacity',
		'name'    => __( 'Background Overlay Opacity', 'flora' ),
		'desc'    => __( 'Select background overlay opacity.', 'flora' ),
		'type' => 'select',
        'options'   => array(
            '' => __('Default', 'flora'), 
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
		'default' => ''
	));

    $title_options->add_field( array(
		'id'   => $prefix . 'title_background_parallax',
		'name' => __( 'Parallax', 'flora' ),
		'desc' => __( 'Enable parallax background scrolling.', 'flora' ),
		'type' => 'checkbox',
        'default'  => ''
	));

    /***************************** 
    * Background Options 
    ******************************/
    $background_options = new_cmb2_box( array(
		'id'         => 'background_options',
		'title'      => __( 'Background', 'flora' ),
        'icon'         => 'dashicons dashicons-format-image',
		'object_types'      => array('post'),
	) );

    $background_options->add_field( array(
		'id'   => $prefix . 'background',
		'name' => __( 'Background', 'flora' ),
		'desc' => __( 'Select a background type for this page.', 'flora' ),
		'type' => 'select',
        'options' => array(
            '' => __('None', 'flora'), 
            'color' => __('Color', 'flora'), 
            'image' => __('Image', 'flora'), 
        ),
        'default' => ''
	));

    $background_options->add_field( array(
		'id'   => $prefix . 'background_image',
		'name' => __( 'Background Image', 'flora' ),
		'desc' => __( 'Upload an image or insert a URL.', 'flora' ),
		'type' => 'file'
	));
    
    $background_options->add_field( array(
		'id'      => $prefix . 'background_color',
		'name'    => __( 'Background Color', 'flora' ),
		'desc'    => __( 'Choose a background color.', 'flora' ),
		'type'    => 'colorpicker',
		'default' => ''
	));

    $background_options->add_field( array(
		'id'   => $prefix . 'background_size',
		'name' => __( 'Background Size', 'flora' ),
		'desc' => __( 'For full width or high-definition background image, choose Cover. Otherwise, choose Contain.', 'flora' ),
		'type' => 'select',
        'options'   => array(
            'cover' => __('Cover', 'flora'), 
            'contain' => __('Contain', 'flora')
            ),
        'default'  => 'cover'
	));

    $background_options->add_field( array(
		'id'      => $prefix . 'background_overlay_color',
		'name'    => __( 'Background Overlay Color', 'flora' ),
		'desc'    => __( 'Select background color overlay.', 'flora' ),
		'type'    => 'colorpicker',
		'default' => ''
	));

    $background_options->add_field( array(
		'id'      => $prefix . 'background_overlay_opacity',
		'name'    => __( 'Background Overlay Opacity', 'flora' ),
		'desc'    => __( 'Select background overlay opacity.', 'flora' ),
		'type' => 'select',
        'options'   => array(
                '' => __('Default', 'flora'), 
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
		'default' => ''
	));

    /***************************** 
    * Sidebar Options 
    ******************************/
    $sidebars = array();
    $sidebars[''] = __('Default', 'flora');
    foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) { 
        $sidebars[ucwords( $sidebar['id'] )] = ucwords( $sidebar['name'] );
    }

    $sidebar_options = new_cmb2_box( array(
		'id'         => 'sidebar_options',
		'title'      => __( 'Sidebar', 'flora' ),
        'icon'         => 'dashicons dashicons-format-aside',
		'object_types'      => array('post'),
	) );

    $sidebar_options->add_field( array(
        'id'         => $prefix . 'post_custom_sidebar',
		'name'       => __( 'Sidebar', 'flora' ),
		'desc'       => __( 'Use custom sidebar for this post.', 'flora' ),
		'type'       => 'checkbox'
	) );

    $sidebar_options->add_field( array(
        'id'         => $prefix . 'sidebar_position',
		'name'       => __( 'Sidebar Position', 'flora' ),
		'desc'       => __( 'Select sidebar position.', 'flora' ),
		'type'    => 'radio_inline',
		'options' => array(
			'1' => '<img src="' . $template_directory . '/images/columns/1.png" alt="No Sidebar"/>',
			'2' => '<img src="' . $template_directory . '/images/columns/2.png" alt="One Left"/>',
			'3' => '<img src="' . $template_directory . '/images/columns/3.png" alt="One Right"/>',
		),
        'default'   =>  '1'
	));

    $sidebar_options->add_field( array(
		'id'   => $prefix . 'sidebar_name',
		'name' => __( 'Sidebar Name', 'flora' ),
		'desc' => __( 'Select a sidebar to display.', 'flora' ),
		'type' => 'select',
        'options' => $sidebars,
        'default' => ''
	));
   
    /****************************
    * Footer Options 
    ******************************/
    $footer_options = new_cmb2_box( array(
		'id'         => 'footer_options',
		'title'      => __( 'Footer', 'flora' ),
        'icon'         => 'dashicons dashicons-editor-insertmore',
		'object_types'      => array('post'),
	) );

    $footer_options->add_field( array(
		'id'         => $prefix . 'page_footer',
		'name'       => __( 'Footer', 'flora' ),
		'desc'       => __( 'Show or hide the footer.', 'flora' ),
		'type'      => 'select',
		'options'   => array(
            ''   => __('Show', 'flora'),
            'hide'   => __('Hide', 'flora'),
        ),
    ));