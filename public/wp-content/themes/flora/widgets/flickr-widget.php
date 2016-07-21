<?php
class Wyde_Widget_Flickr extends WP_Widget {

	function __construct() {
		parent::__construct(
            'w-flickr', 
            __('Wyde: Flickr', 'flora'), 
            array(
                'classname' => 'wyde_widget_flickr', 
                'description' => __('Displays a Flickr photo stream.', 'flora')
            )
        );

	}

	function widget($args, $instance) {

        extract( $args );

		$title = apply_filters('widget_title', $instance['title']);
		$flickr_id = strip_tags( $instance['flickr_id'] );
		$type = strip_tags( $instance['type'] );
		$count = $instance['count'];
		$columns =  $instance['columns'];

	
        echo wp_kses_post($before_widget);
        
        if($title) {
            echo wp_kses_post( $before_title ). esc_html( $title ) .wp_kses_post( $after_title );
        }
	
		echo do_shortcode(sprintf('[wyde_flickr flickr_id="%s" type="%s" count="%s" columns="%s"]', esc_attr( $flickr_id ), esc_attr( $type ), intval( $count ), intval( $columns ) ) );
		
		echo wp_kses_post($after_widget);
	}

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		
        $instance['title']			= sanitize_text_field( $new_instance['title'] );
		$instance['flickr_id'] 		= strip_tags( $new_instance['flickr_id'] );
		$instance['type']           = strip_tags( $new_instance['type'] );
		$instance['count'] 			= intval( $new_instance['count'] );
		$instance['columns']        = intval( $new_instance['columns'] );
        
		return $instance;
	}

	function form( $instance ) {
		
        // Set up the default form values.
		$defaults = array(
			'title'			=> __('Flickr Stream', 'flora'),
			'flickr_id'		=> '',
			'type'     => 'user',
			'count'         => 9,
			'columns'       => 3,
		);


		/* Merge the user-selected arguments with the defaults. */
		$instance = wp_parse_args( (array) $instance, $defaults );
        
        ?>
		<p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo __('Title', 'flora'); ?>:</label>
		    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php echo __('Type', 'flora'); ?>:</label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>" class="widefat">
                <?php
                $options = array( 
                    'user' => __('User', 'flora'), 
                    'group'  => __('Group', 'flora'),
                );
                foreach($options as $value => $text):
                ?>
                <option value="<?php echo esc_attr($value); ?>"<?php echo ($instance['type'] == $value) ? ' selected="selected"':'';?>><?php echo esc_html($text); ?></option>
                <?php  endforeach; ?>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'flickr_id' ) ); ?>"><?php echo __('Flickr ID', 'flora'); ?>:</label>
		    <input id="<?php echo esc_attr( $this->get_field_id( 'flickr_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'flickr_id' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['flickr_id'] ); ?>" />
            <p><?php echo sprintf( __( 'To find your flickID visit %s.', 'flora' ), '<a href="http://idgettr.com/" target="_blank">idGettr</a>' ); ?></p>
        </p>

		<p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php echo __('Number of images to show', 'flora'); ?>:</label>
		    <input id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="text" value="<?php echo intval( $instance['count'] ); ?>" size="3" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"><?php echo __('Columns', 'flora'); ?>:</label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>" class="widefat">
                <?php
                $options = array( 
                    2,
                    3,
                    4,
                    5,
                    6,
                    12,
                );
                foreach($options as $value):
                ?>
                <option value="<?php echo esc_attr($value); ?>"<?php if ($value == $instance['columns']) echo ' selected="selected"'; ?>><?php echo esc_html($value); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
<?php
	}
}
add_action('widgets_init', 'wyde_widget_flickr_load');

function wyde_widget_flickr_load()
{
	register_widget('Wyde_Widget_Flickr');
}