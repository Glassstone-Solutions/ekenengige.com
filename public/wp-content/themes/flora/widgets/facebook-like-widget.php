<?php
class Wyde_Widget_Facebook_Like extends WP_Widget {

	function __construct() {
		parent::__construct(
            'w-facebook-like', 
            __('Wyde: Facebook Like', 'flora'), 
            array(
                'classname' => 'wyde_widget_facebook_like', 
                'description' => __('Facebook Like box.', 'flora')
            )
        );

	}

	function widget($args, $instance)
	{
		extract($args);

		$title = apply_filters('widget_title', $instance['title']);
		$page_url = $instance['page_url'];
		$width = $instance['width'];
		$color_scheme = $instance['color_scheme'];
		$show_faces = isset($instance['show_faces']) ? 'true' : 'false';
		$show_stream = isset($instance['show_stream']) ? 'true' : 'false';
		$show_header = isset($instance['show_header']) ? 'true' : 'false';
		$height = '35';

		if($show_faces == 'true') {
			$height = '210';
		}

		if($show_stream == 'true') {
			$height = '485';
		}

		if($show_stream == 'true' && $show_faces == 'true' && $show_header == 'true') {
			$height = '510';
		}

		if($show_stream == 'true' && $show_faces == 'true' && $show_header == 'false') {
			$height = '510';
		}

		if($show_header == 'true') {
			$height = $height + 30;
		}

		echo wp_kses_post($before_widget);
		
		if($title) {
			echo wp_kses_post( $before_title ). esc_html( $title ) .wp_kses_post( $after_title );
		}

		if( !empty( $page_url )): ?>
        <div class="w-facebook-box<?php $color_scheme == 'dark' ? 'w-dark' : '';?>">
		<?php echo sprintf('<iframe src="http%s://www.facebook.com/plugins/likebox.php?href=%s&amp;width=%s&amp;colorscheme=%s&amp;show_faces=%s&amp;stream=%s&amp;header=%s&amp;height=%s&amp;force_wall=true%s" style="width:%spx; height: %spx;"></iframe>',
		(is_ssl())? 's' : '',
		urlencode( esc_url( $page_url ) ),
		absint( $width ),
		esc_attr( $color_scheme ),
		esc_attr( $show_faces ),
		esc_attr( $show_stream ),
		esc_attr( $show_header ),
		absint( $height ),
		$show_faces == 'true' ? '&amp;connections=8' : '',
		absint( $width ),
		absint( $height )
		); ?>    
		</div>
        <?php endif;

		echo wp_kses_post($after_widget);
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['page_url'] = esc_url( $new_instance['page_url'] );
		$instance['width'] = absint( $new_instance['width'] );
		$instance['color_scheme'] = $new_instance['color_scheme'];
		$instance['show_faces'] = $new_instance['show_faces'];
		$instance['show_stream'] = $new_instance['show_stream'];
		$instance['show_header'] = $new_instance['show_header'];

		return $instance;
	}

	function form($instance)
	{
		$defaults = array(
            'title' => __('Find us on Facebook', 'flora'), 
            'page_url' => '', 
            'width' => '240', 
            'color_scheme' => 'light', 
            'show_faces' => 'on', 
            'show_stream' => false, 
            'show_header' => false
        );

		$instance = wp_parse_args((array) $instance, $defaults); ?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php echo __('Title', 'flora'); ?>:</label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('page_url') ); ?>"><?php echo __('Facebook Page URL', 'flora'); ?>:</label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id('page_url') ); ?>" name="<?php echo esc_attr( $this->get_field_name('page_url') ); ?>" value="<?php echo esc_url( $instance['page_url'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('width') ); ?>"><?php echo __('Width', 'flora'); ?>:</label>
			<input class="widefat" type="text" style="width: 50px;" id="<?php echo esc_attr( $this->get_field_id('width') ); ?>" name="<?php echo esc_attr( $this->get_field_name('width') ); ?>" value="<?php echo esc_attr( $instance['width'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('color_scheme') ); ?>"><?php echo __('Color Scheme', 'flora'); ?>:</label>
			<select id="<?php echo esc_attr( $this->get_field_id('color_scheme') ); ?>" name="<?php echo esc_attr( $this->get_field_name('color_scheme') ); ?>" class="widefat" style="width:100%;">
				<option value="light" <?php if ('light' == $instance['color_scheme']) echo 'selected="selected"'; ?>><?php echo __('Light', 'flora'); ?></option>
				<option value="dark" <?php if ('dark' == $instance['color_scheme']) echo 'selected="selected"'; ?>><?php echo __('Dark', 'flora'); ?></option>
			</select>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_faces'], 'on'); ?> id="<?php echo esc_attr( $this->get_field_id('show_faces') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_faces') ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id('show_faces') ); ?>"><?php echo __('Show faces', 'flora'); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_stream'], 'on'); ?> id="<?php echo esc_attr( $this->get_field_id('show_stream') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_stream') ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id('show_stream') ); ?>"><?php echo __('Show stream', 'flora'); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_header'], 'on'); ?> id="<?php echo esc_attr( $this->get_field_id('show_header') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_header') ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id('show_header') ); ?>"><?php echo __('Show facebook header', 'flora'); ?></label>
		</p>
	<?php
	}
}
add_action('widgets_init', 'wyde_widget_facebook_like_load');

function wyde_widget_facebook_like_load()
{
	register_widget('Wyde_Widget_Facebook_Like');
}