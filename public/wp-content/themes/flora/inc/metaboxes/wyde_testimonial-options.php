<?php
    global $wyde_options;

	$prefix = '_w_';
  
    
    /***************************** 
    * Testimonial Options 
    ******************************/
    /** Customer Information **/
    $testimonial_options = new_cmb2_box( array(
		'id'         => 'testimonial_options',
		'title'      => __( 'Customer Information', 'flora' ),
		'object_types'      => array('wyde_testimonial'),
	) );

    $testimonial_options->add_field( array(
        'id'       => $prefix . 'testimonial_image',
		'name'     => __( 'Image', 'flora' ),
        'desc' => __( 'Customer\'s image.', 'flora' ),
		'type'     => 'file'
	));

    $testimonial_options->add_field( array(
        'id'       => $prefix . 'testimonial_position',
		'name'     => __( 'Job Position', 'flora' ),
        'desc' => __( 'Insert a customer\'s job position.', 'flora' ),
		'type'     => 'text_medium'
	));

    $testimonial_options->add_field( array(
        'id'       => $prefix . 'testimonial_company',
		'name'     => __( 'Company', 'flora' ),
        'desc' => __( 'Insert a company name.', 'flora' ),
		'type'     => 'text_medium'
	));

    $testimonial_options->add_field( array(
        'id'   => $prefix . 'testimonial_website',
		'name' => __( 'Website', 'flora' ),
		'desc' => __( 'Insert a website URL for this customer or company.', 'flora' ),
		'type' => 'text_url'
	));

    $testimonial_options->add_field( array(
        'id'   => $prefix . 'testimonial_email',
		'name'     => __( 'Email Address', 'flora' ),
        'desc' => __( 'Insert a customer\'s email address.', 'flora' ),
		'type'     => 'text_medium'
	));

    $testimonial_options->add_field( array(
        'id'   => $prefix . 'testimonial_detail',
		'name' => '',
		'desc' => '',
		'type' => 'wysiwyg',
        'options' => array(
            'media_buttons' => false,
            'teeny' => true,
        ),
	));    