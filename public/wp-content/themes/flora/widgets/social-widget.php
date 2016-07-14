<?php
class Wyde_Widget_Social extends WP_Widget {

	function __construct() {
		parent::__construct(
            'w-social', 
            __('Wyde: Social Icons', 'flora'), 
            array(
                'classname' => 'wyde_widget_social', 
                'description' => __('Displays social icons from Theme Options -> Social Media.', 'flora')
            )
        );

	}

	function widget($args, $instance) {

        extract( $args );

		$title = apply_filters('widget_title', $instance['title']);
		$align = strip_tags( $instance['align'] );

	
        echo wp_kses_post($before_widget);
        
        if($title) {
            echo wp_kses_post( $before_title ). esc_html( $title ) .wp_kses_post( $after_title );
        }
        
	    echo '<div class="w-social-icons text-'. esc_attr( $align ).'">';
		wyde_social_icons();
        echo '</div>';		
		echo wp_kses_post($after_widget);
	}

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['align'] = strip_tags( $new_instance['align'] );
        
		return $instance;
	}

	function form( $instance ) {
		
        // Set up the default form values.
		$defaults = array(
			'title'			=> __('Social Icons', 'flora'),
			'align'			=> 'center',
		);


		/* Merge the user-selected arguments with the defaults. */
		$instance = wp_parse_args( (array) $instance, $defaults );
        
        ?>
		<p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo __('Title', 'flora'); ?>:</label>
		    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo __('Alignment', 'flora'); ?>:</label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'align' ) ); ?>" class="widefat">
                <?php
                $options = array( 
                    'left' => __('Left', 'flora'), 
                    'center'  => __('Center', 'flora'),
                    'right'  => __('Right', 'flora'),
                );
                foreach($options as $value => $text):
                ?>
                <option value="<?php echo esc_attr($value); ?>"<?php echo ($instance['align'] == $value) ? ' selected="selected"' : '';?>><?php echo esc_html($text); ?></option>
                <?php  endforeach; ?>
            </select>        
        </p>
        <?php
	}
}
add_action('widgets_init', 'wyde_widget_social_load');

function wyde_widget_social_load()
{
	register_widget('Wyde_Widget_Social');
}