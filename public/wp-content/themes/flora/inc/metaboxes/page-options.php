<?php
    global $wyde_options;

	$prefix = '_w_';
  
    $template_directory = get_template_directory_uri();

    /***************************** 
    * Page Options 
    ******************************/
    $page_options = new_cmb2_box( array(
		'id'         => 'page_options',
		'title'      => __( 'Page', 'flora' ),
        'icon'         => 'dashicons dashicons-format-aside',
		'object_types'      => array('page'),
	) );

    $page_options->add_field( array(
        'id'         => $prefix . 'layout',
		'name'       => __( 'Layout', 'flora' ),
		'desc'       => __( 'Select a page layout, choose \'Boxed\' for create a Regular WordPress page with comments and sidebar, Wide for a Full Width page suited for the Visual Composer Page Builder.', 'flora' ),
		'type'    => 'select',
		'options' => array(
            ''   => __('Default', 'flora'),
            'boxed'   => __('Boxed', 'flora'),
            'wide'   => __('Wide', 'flora'),
        ),
        'default' => '',
	) );

    /** Page Sidebar **/
    $sidebars = array();
    $sidebars[''] = __('Default', 'flora');
    foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) { 
        $sidebars[ucwords( $sidebar['id'] )] = ucwords( $sidebar['name'] );
    }

    $page_options->add_field( array(
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

    $page_options->add_field( array(
		'id'   => $prefix . 'sidebar_name',
		'name' => __( 'Sidebar Name', 'flora' ),
		'desc' => __( 'Select a sidebar to display.', 'flora' ),
		'type' => 'select',
        'options' => $sidebars,
        'default' => ''
	));

    /***************************** 
    * Header Options 
    ******************************/
    $header_options = new_cmb2_box( array(
		'id'            => 'header_options',
		'title'         => __( 'Header', 'flora' ),
		'icon'         => 'dashicons dashicons-menu',
		'object_types'  => array('page'),
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
        ),
        'default'  => ''
	) );

    $header_options->add_field( array(
		'id'      => $prefix . 'header_text_style',
		'name'    => __( 'Foreground Style', 'flora' ),
		'desc'    => __( 'Select a header navigation foreground style.', 'flora' ),
		'type'       => 'select',
        'options'    => array(
            ''      => __('Default', 'flora'),
            'light' => __('Light', 'flora'),
            'dark'  => __('Dark', 'flora')
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
		'object_types'  => array('page'),
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
		'object_types'      => array('page'),
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

    /****************************
    * Footer Options 
    ******************************/
    $footer_options = new_cmb2_box( array(
		'id'         => 'footer_options',
		'title'      => __( 'Footer', 'flora' ),
        'icon'         => 'dashicons dashicons-editor-insertmore',
		'object_types'      => array('page'),
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