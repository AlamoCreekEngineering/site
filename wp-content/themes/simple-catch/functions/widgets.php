<?php
/** 
 * Extends class wp_widget
 * 
 * Creates a function CustomTagWidget
 * $widget_ops option array passed to wp_register_sidebar_widget().
 * $control_ops option array passed to wp_register_widget_control().
 * $name, Name for this widget which appear on widget bar.
 */
class CustomTagWidget extends WP_Widget {
		function CustomTagWidget() {
			$widget_ops = array('description' => 'Displays Custom Tag Cloud');
			$control_ops = array('width' => 400, 'height' => 500);
			parent::WP_Widget(false,$name='Custom Tag Cloud',$widget_ops,$control_ops);
		}
		
		/** Displays the Widget in the front-end.
		 * 
		 * $args Display arguments including before_title, after_title, before_widget, and after_widget.
		 * $instance The settings for the particular instance of the widget
		 */
		function widget( $args, $instance ) {
			extract( $args );
			$title = apply_filters( 'widget_title', empty( $instance[ 'title' ] ) ? 'Custom Tags :' : $instance[ 'title' ] );
			
			echo $before_widget;

			if ( $title ):
				echo $before_title . $title . $after_title;
			endif;
			
			if ( function_exists( 'simplecatch_custom_tag_cloud' ) ):
				simplecatch_custom_tag_cloud();
			endif;
			
			echo $after_widget;
		}
		
		/**
		 * update the particular instant  
		 * 
		 * This function should check that $new_instance is set correctly.
		 * The newly calculated value of $instance should be returned.
		 * If "false" is returned, the instance won't be saved/updated.
		 *
		 * $new_instance New settings for this instance as input by the user via form()
		 * $old_instance Old settings for this instance
		 * Settings to save or bool false to cancel saving
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = stripslashes($new_instance['title']);
			
			return $instance;
		}	
		
		/**
 		 * Creates the form for the widget in the back-end which includes the Title 
		 * $instance Current settings
		 */
		function form($instance) {
			$instance = wp_parse_args( ( array ) $instance, array( 'title'=>'Custom Tag' ) );
			$title = sanitize_title( $instance[ 'title' ] );
			
			/**
			 * Constructs title attributes  for use in form() field
			 * @return string Name attribute for $field_name
			 */
		echo '<p><label for="' . $this->get_field_id( 'title' ) . '">' . 'Title:' . '</label><input class="widefat" id="' . 
		$this->get_field_id( 'title' ) . '" name="' .       $this->get_field_name( 'title' ) . '" type="text" value="' . $title . '" /> </p>';
					
		}
}// end CustomTagWidget class

/** 
 * Register CustomTagWidget widget
 */
function TagWidget() {
	register_widget( 'CustomTagWidget' );
 }
add_action( 'widgets_init', 'TagWidget' );
?>