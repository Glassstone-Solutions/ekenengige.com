<?php


require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-column.php' );

class WPBakeryShortCode_Wyde_Tab extends WPBakeryShortCode_VC_Column {
	protected $controls_css_settings = 'tc vc_control-container';
	protected $controls_list = array( 'add', 'edit', 'clone', 'delete' );
	protected $predefined_atts = array(
		'tab_id' => "Tab",
		'title' => ''
	);
	protected $controls_template_file = 'editors/partials/backend_controls_tab.tpl.php';

	public function __construct( $settings ) {
		parent::__construct( $settings );
	}

	public function customAdminBlockParams() {
		return ' id="tab-' . $this->atts['tab_id'] . '"';
	}

	public function mainHtmlBlockParams( $width, $i ) {
		return 'data-element_type="' . $this->settings["base"] . '" class="wpb_' . $this->settings['base'] . ' wpb_sortable wpb_content_holder"' . $this->customAdminBlockParams();
	}

	public function containerHtmlBlockParams( $width, $i ) {
		return 'class="wpb_column_container vc_container_for_children"';
	}

	public function getColumnControls( $controls, $extended_css = '' ) {
		return $this->getColumnControlsModular( $extended_css );
		/*
		$controls_start = '<div class="vc_controls controls controls_column' . ( ! empty( $extended_css ) ? " {$extended_css}" : '' ) . '">';
		$controls_end = '</div>';

		if ( $extended_css == 'bottom-controls' ) $control_title = sprintf( __( 'Append to this %s', 'js_composer' ), strtolower( $this->settings( 'name' ) ) );
		else $control_title = sprintf( __( 'Prepend to this %s', 'js_composer' ), strtolower( $this->settings( 'name' ) ) );

		$controls_add = ' <a class="vc_control column_add" href="#" title="' . $control_title . '"><i class="vc_icon"></i></a>';
		$controls_edit = ' <a class="vc_control column_edit" href="#" title="' . sprintf( __( 'Edit this %s', 'js_composer' ), strtolower( $this->settings( 'name' ) ) ) . '"><i class="vc_icon"></i></a>';
		$controls_clone = '<a class="vc_control column_clone" href="#" title="' . sprintf( __( 'Clone this %s', 'js_composer' ), strtolower( $this->settings( 'name' ) ) ) . '"><i class="vc_icon"></i></a>';

		$controls_delete = '<a class="vc_control column_delete" href="#" title="' . sprintf( __( 'Delete this %s', 'js_composer' ), strtolower( $this->settings( 'name' ) ) ) . '"><i class="vc_icon"></i></a>';
		return $controls_start . $controls_add . $controls_edit . $controls_clone . $controls_delete . $controls_end;
		*/
	}
}

$icon_picker_options = Wyde_Shortcode::get_iconpicker_options();

vc_map( array(
	'name' => __( 'Tab', 'flora' ),
	'base' => 'wyde_tab',
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
			    'type' => 'tab_id',
			    'heading' => __( 'Tab ID', 'flora' ),
			    'param_name' => "tab_id"
		    ),

	),
	'js_view' => 'WydeTabView'
) );