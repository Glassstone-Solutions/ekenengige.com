<?php
    global $wyde_options;

	$prefix = '_w_';
  
    $template_directory = get_template_directory_uri();

    /***************************** 
    * Portfolio Post Options 
    ******************************/
    /** Portfolio Options **/
    $portfolio_options = new_cmb2_box( array(
		'id'         => 'portfolio_options',
		'title'      => __( 'Portfolio', 'flora' ),
        'icon'         => 'dashicons dashicons-portfolio',
		'object_types'      => array('wyde_portfolio'),
	) );
    
    $portfolio_options->add_field( array(
        'id'   => $prefix . 'portfolio_layout',
		'name' => __( 'Layout', 'flora' ),
		'desc' => __( 'Select portfolio layout.', 'flora' ),
		'type' => 'radio_inline',
        'options'   => array(
            '1'     => '<img src="' . $template_directory . '/images/portfolio/1.jpg" alt="Standard" title="Standard"/>',
			'2'      => '<img src="' . $template_directory . '/images/portfolio/2.jpg" alt="Gallery" title="Gallery"/>',
			'3'      => '<img src="' . $template_directory . '/images/portfolio/3.jpg" alt="Slider" title="Slider"/>',
			'4'      => '<img src="' . $template_directory . '/images/portfolio/4.jpg" alt="Grid" title="Grid"/>',
		),
        'default'   => isset( $wyde_options['portfolio_layout'] ) ? $wyde_options['portfolio_layout'] : '1'
	));

    $portfolio_options->add_field( array(
        'id'   => $prefix . 'project_url',
		'name' => __( 'Project URL', 'flora' ),
		'desc' => __( 'Insert a project URL.', 'flora' ),
		'type' => 'text_url'
	));

    $portfolio_options->add_field( array(
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
    
    /** Embeds Options **/
    $portfolio_options->add_field( array(
        'id'       => $prefix . 'media_info',
        'name'     => __( 'Media Options', 'flora' ),
        'desc'     => __('You can insert media URL from any major video and audio hosting service (Youtube, Vimeo, DailyMotion, SoundCloud, Mixcloud, WordPress.tv, etc.). Supports services listed at <a href="http://codex.wordpress.org/Embeds" target="_blank">http://codex.wordpress.org/Embeds</a>', 'flora'),
		'type'     => 'title',
	));

    $portfolio_options->add_field( array(
        'id'   => $prefix . 'embed_url',
		'name' => __( 'Media URL', 'flora' ),
		'desc' => __( 'Insert a media URL.', 'flora' ),
		'type' => 'oembed'
	));
   
    /** Gallery Options **/
    $portfolio_options->add_field( array(
        'id'       => $prefix . 'gallery_info',
		'name'     => __( 'Gallery Options', 'flora' ),
		'type'     => 'title',
	));

    $portfolio_options->add_field( array(
        'id'   => $prefix . 'gallery_images',
		'name' => __( 'Images', 'flora' ),
		'desc' => __( 'Upload or select images from media library. Recommended size: 640x640px or larger.', 'flora' ),
		'type' => 'file_list',
        'preview_size' => 'thumbnail', 
	));


    /** Client Options **/
    $portfolio_options->add_field( array(
        'id'       => $prefix . 'client_info',
		'name'     => __('Client Information', 'flora'),
		'type'     => 'title',
	));

    $portfolio_options->add_field( array(						
        'id'   => $prefix . 'client_name',
		'name' => __('Name', 'flora'),
        'description' => __('Insert a client name.', 'flora'),
		'type' => 'text_medium'
	));

    $portfolio_options->add_field( array(
        'id'   => $prefix . 'client_detail',
		'name' => __('Description', 'flora' ),
		'description' => __('Insert a short description about the client.', 'flora'),
		'type' => 'wysiwyg',
        'options' => array(
            'wpautop' => false, 
            'media_buttons' => false,
            'textarea_rows' => 5, 
            'teeny' => true, 
        ),
	));

    $portfolio_options->add_field( array(
        'id'   => $prefix . 'client_website',
		'name' => 'Website',
        'description' =>  __('Insert a client website.', 'flora'),
		'type' => 'text_url',
	));
    
     /** Custom Description Options **/
    $portfolio_options->add_field( array(
        'id'       => $prefix . 'custom_field_info',
		'name'     => __('Custom Description', 'flora'),
		'type'     => 'title',
	));

    $custom_fields = $portfolio_options->add_field( array(
        'id'          => $prefix . 'custom_fields',
        'type'        => 'group',
        'description' => __( 'Add new custom description fields.', 'flora'),
        'options'     => array(
            'group_title'   => __( 'Entry {#}', 'flora' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'    => __( 'Add Another Entry', 'flora' ),
            'remove_button' => __( 'Remove Entry', 'flora' ),
            'sortable'      => true, // beta
        ),
        'fields'    => array(
            array(						
                'id'   => 'custom_field_title',
		        'name' => 'Title',
                'description' => __('Insert a custom field title.', 'flora'),
		        'type' => 'text_medium',
	        ),
            array(						
                'id'   => 'custom_field_value',
		        'name' => 'Description',
                'description' => __('Insert a custom field description.', 'flora'),
		        'type' => 'textarea_small',
	        )

        ),
    ) );


    /***************************** 
    * Header Options 
    ******************************/
    $header_options = new_cmb2_box( array(
		'id'            => 'header_options',
		'title'         => __( 'Header', 'flora' ),
		'icon'         => 'dashicons dashicons-menu',
		'object_types'  => array('wyde_portfolio'),
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
		'object_types'  => array('wyde_portfolio'),
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
		'object_types'      => array('wyde_portfolio'),
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
		'object_types'      => array('wyde_portfolio'),
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