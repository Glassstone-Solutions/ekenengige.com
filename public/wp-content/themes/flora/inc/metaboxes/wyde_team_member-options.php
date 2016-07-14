<?php
    global $wyde_options;

	$prefix = '_w_';
  
    /***************************** 
    * Team Members Options 
    ******************************/
    // get social icons from custom-function.php
    $socials = wyde_get_social_icons();
    $social_icons = array();
    foreach($socials as $key => $value){
        $social_icons[$value] = $value; 
    }

    /** Member Information **/
    $member_options = new_cmb2_box( array(
		'id'         => 'member_options',
		'title'      => __( 'Member Information', 'flora' ),
		'object_types'      => array('wyde_team_member'),
	) );

    $member_options->add_field( array(
        'id'       => $prefix . 'member_image',
		'name'     => __( 'Image', 'flora' ),
        'desc' => __( 'Member\'s image.', 'flora' ),
		'type'     => 'file'
	));

    $member_options->add_field( array(
        'id'       => $prefix . 'member_position',
		'name'     => __( 'Job Position', 'flora' ),
        'desc' => __( 'Insert a member\'s job position.', 'flora' ),
		'type'     => 'text_medium'
	));

    $member_options->add_field( array(
        'id'   => $prefix . 'member_detail',
		'name' => __( 'Description', 'flora' ),
		'desc' => __( 'Input a member description.', 'flora' ),
		'type' => 'wysiwyg',
        'options' => array(
            'media_buttons' => false,
            'teeny' => true,
        ),
	));

    $member_options->add_field( array(
        'id'       => $prefix . 'member_email',
		'name'     => __( 'Email Address', 'flora' ),
        'desc' => __( 'Insert a member\'s contact email address.', 'flora' ),
		'type'     => 'text_medium'
	));

    $member_options->add_field( array(
        'id'   => $prefix . 'member_website',
		'name' => __( 'Website', 'flora' ),
		'desc' => __( 'Insert a URL that applies to this member.', 'flora' ),
		'type' => 'text_url'
	));


    $social_options = new_cmb2_box( array(
		'id'         => 'social_options',
		'title'      => __( 'Social Networks', 'flora' ),
		'object_types'      => array('wyde_team_member'),
	) );

    $social_options->add_field( array(
        'id'          => $prefix . 'member_socials',
        'type'        => 'group',
        'name' => __( 'Social Networks', 'flora' ),
        'description' => __('Add member\'s social networking websites.', 'flora'),
        'options'     => array(
            'group_title'   => __( 'Website {#}', 'flora' ),
            'add_button'    => __( 'Add Another Website', 'flora' ),
            'remove_button' => __( 'Remove Website', 'flora')
        ),
        'fields'    => array(
            array(
                'id'   => 'social',
                'name' => 'Website',
                'type' => 'select',
                'description' => __('Select a social networking websites.', 'flora'),
                'options'   => $social_icons
            ),
            array(
                'id'   => 'url',
                'name' => 'URL',
                'description' => __('Insert member\'s profile URL or personal page.', 'flora'),
                'type' => 'text_url'
            )
        )
    ));
