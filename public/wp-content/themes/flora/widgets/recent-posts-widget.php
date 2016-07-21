<?php


class Wyde_Widget_Recent_Posts extends WP_Widget {

	function __construct() {
		parent::__construct(
            'w-recent-posts', 
            __('Wyde: Recent Posts', 'flora'), 
            array(
                'classname' => 'wyde_widget_recent_posts', 
                'description' => __('Most recent Posts with Thumbnail.', 'flora')
            )
        );

	}

	function widget($args, $instance) {

		extract($args);

		$title = apply_filters('widget_title', $instance['title']);

		$number = ( ! empty( $instance['number'] ) ) ? absint( esc_attr( $instance['number'] ) ) : 5;
		if ( ! $number ) $number = 5;
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : true;

		$posts = new WP_Query( apply_filters( 'widget_posts_args', array(
			    'posts_per_page'      => $number,
                'has_password' => false,
			    'ignore_sticky_posts' => true,
                'order' => 'DESC'
		        ) ) 
         );

		if ($posts->have_posts()) :

       	echo wp_kses_post($before_widget);
		
		if($title) {
			echo wp_kses_post( $before_title ). esc_html( $title ) .wp_kses_post( $after_title );
		}
        
        ?>
        <div class="w-recent-posts">
		    <ul class="w-posts">
		    <?php while ( $posts->have_posts() ) : $posts->the_post(); ?>
			    <li>
                    <span class="thumb">
                    <?php echo wyde_get_post_thumbnail(get_the_ID(), 'thumbnail', get_permalink());?>
                    </span>
                    <p>
                        <a href="<?php the_permalink(); ?>"><?php  the_title(); ?></a>
			        <?php if ( $show_date ) : ?>
				        <span><?php echo get_the_date(); ?></span>
			        <?php endif; ?>
                    </p>
			    </li>
		    <?php endwhile; ?>
		    </ul>
        </div>
		<?php echo wp_kses_post($after_widget); ?>
<?php

		wp_reset_postdata();

		endif;

	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = intval( $new_instance['number'] );
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : true;

		return $instance;
	}


	function form( $instance ) {


        $defaults = array(
            'title' => __('Recent Posts', 'flora'), 
            'number' => 5, 
            'show_date' => true
        );

		$instance = wp_parse_args((array) $instance, $defaults); 


?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo __('Title', 'flora'); ?>:</label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo sanitize_text_field( $instance['title'] ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php echo __('Number of posts to show', 'flora'); ?>:</label>
		<input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['number'] ); ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $instance['show_date'] ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" />
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php echo __('Post date.', 'flora'); ?></label></p>
<?php
	}
}

add_action('widgets_init', 'wyde_widget_recent_posts_load');

function wyde_widget_recent_posts_load()
{
	register_widget('Wyde_Widget_Recent_Posts');
}