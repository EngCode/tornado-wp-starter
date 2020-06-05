<?php
/**
 * Yoast SEO: Local plugin file.
 *
 * @package WPSEO_Local\Frontend
 */

/**
 * Class WPSEO_Show_OpeningHours.
 *
 * Creates widget for showing the address.
 */
class WPSEO_Show_OpeningHours extends WP_Widget {

	/**
	 * Holds the Opening Hours Repository object
	 *
	 * @var WPSEO_Local_Opening_Hours_Repository
	 */
	private $opening_hours_repository;

	/**
	 * WPSEO_Show_OpeningHours constructor.
	 */
	public function __construct() {
		$widget_options = array(
			'classname'   => 'WPSEO_Show_OpeningHours',
			'description' => __( 'Shows opening hours of locations.', 'yoast-local-seo' ),
		);

		parent::__construct( false, __( 'WP SEO - Show Opening hours', 'yoast-local-seo' ), $widget_options );

		$this->opening_hours_repository = new WPSEO_Local_Opening_Hours_Repository();
	}

	/**
	 * Displays the store locator form.
	 *
	 * @see WP_Widget::widget
	 *
	 * @param array $args     Array of options for this widget.
	 * @param array $instance Instance of the widget.
	 *
	 * @return void
	 */
	public function widget( $args, $instance ) {
		$title       = apply_filters( 'widget_title', $instance['title'] );
		$location_id = ! empty( $instance['location_id'] ) ? $instance['location_id'] : '';
		$comment     = ! empty( $instance['comment'] ) ? esc_attr( $instance['comment'] ) : '';

		// Set location ID, since get_post_status() needs an integer as parameter.
		if ( 'current' === $location_id ) {
			$location_id = get_queried_object_id();
		}

		if ( wpseo_has_multiple_locations() && ( 'publish' !== get_post_status( $location_id ) && ! current_user_can( 'edit_posts' ) ) ) {
			return;
		}

		if ( ( $location_id == '' && wpseo_has_multiple_locations() ) || ( $location_id === 'current' && ! is_singular( 'wpseo_locations' ) ) ) {
			return;
		}

		if ( wpseo_has_multiple_locations() && 'wpseo_locations' !== get_post_type( $location_id ) ) {
			return;
		}

		$shortcode_args = array(
			'id'              => $location_id,
			'comment'         => $comment,
			'from_widget'     => true,
			'widget_title'    => $title,
			'before_title'    => $args['before_title'],
			'after_title'     => $args['after_title'],
			'hide_closed'     => ( $instance['hide_closed'] ) ? 1 : 0,
			'show_days'       => $instance['show_days'],
			'show_open_label' => isset( $instance['show_open_label'] ) ? $instance['show_open_label'] : false,
		);

		if ( 'current' === $location_id ) {
			$location_id = get_the_ID();
		}

		if ( isset( $args['before_widget'] ) ) {
			echo $args['before_widget'];
		}

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		if ( ! isset( $instance['hide_closed'] ) ) {
			$instance['hide_closed'] = 0;
		}

		echo wpseo_local_show_opening_hours( $shortcode_args );

		if ( isset( $args['after_widget'] ) ) {
			echo $args['after_widget'];
		}
	}

	/**
	 * Updates a particular instance of a widget.
	 *
	 * @see WP_Widget::update
	 *
	 * @param array $new_instance New option values for this widget.
	 * @param array $old_instance Old, current option values for this widget.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                    = $old_instance;
		$instance['title']           = esc_attr( $new_instance['title'] );
		$instance['location_id']     = esc_attr( $new_instance['location_id'] );
		$instance['hide_closed']     = isset( $new_instance['hide_closed'] ) ? esc_attr( $new_instance['hide_closed'] ) : '';
		$instance['comment']         = esc_attr( $new_instance['comment'] );
		$instance['show_days']       = ! empty( $new_instance['show_days'] ) ? $new_instance['show_days'] : array();
		$instance['show_open_label'] = $new_instance['show_open_label'];

		return $instance;
	}

	/**
	 * Displays the form for the widget options.
	 *
	 * @see WP_Widget::form
	 *
	 * @param array $instance Array with all the (saved) option values.
	 *
	 * @return string
	 */
	public function form( $instance ) {
		$title           = ! empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$location_id     = ! empty( $instance['location_id'] ) ? esc_attr( $instance['location_id'] ) : '';
		$hide_closed     = ! empty( $instance['hide_closed'] ) && esc_attr( $instance['hide_closed'] ) == '1';
		$comment         = ! empty( $instance['comment'] ) ? esc_attr( $instance['comment'] ) : '';
		$show_days       = ( isset( $instance['show_days'] ) && is_array( $instance['show_days'] ) ) ? $instance['show_days'] : array_keys( $this->opening_hours_repository->get_days() );
		$show_open_label = ! empty( $instance['show_open_label'] ) ? $instance['show_open_label'] : '';
		?>
		<?php // @codingStandardsIgnoreStart ?>
		<p>
			<label
				for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'yoast-local-seo' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
				name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>"/>
		</p>
		<?php // @codingStandardsIgnoreEnd ?>
		<?php
		if ( wpseo_has_multiple_locations() ) {
			?>
			<?php // @codingStandardsIgnoreStart ?>
			<p>
				<label
						for="<?php echo $this->get_field_id( 'location_id' ); ?>"><?php _e( 'Location:', 'yoast-local-seo' ); ?></label>
				<?php
				$repo      = new WPSEO_Local_Locations_Repository();
				$locations = $repo->get( array(), false );
				?>
				<select name="<?php echo $this->get_field_name( 'location_id' ); ?>"
					id="<?php echo $this->get_field_id( 'location_id' ); ?>" class="widefat">
					<option value=""><?php _e( 'Select a location', 'yoast-local-seo' ); ?></option>
					<option value="current" <?php selected( $location_id, 'current' ); ?>><?php _e( 'Use current location', 'yoast-local-seo' ); ?></option>
					<?php foreach ( $locations as $loc_id ) { ?>
						<option
							value="<?php echo $loc_id; ?>" <?php selected( $location_id, $loc_id ); ?>><?php echo get_the_title( $loc_id ); ?></option>
					<?php } ?>
				</select>
			</p>
			<?php // @codingStandardsIgnoreEnd ?>
			<?php
		}
		?>
		<?php // @codingStandardsIgnoreStart ?>
		<p>
			<?php _e( 'Show days', 'yoast-local-seo' ); ?>:<br>
			<?php
			foreach ( $this->opening_hours_repository->get_days() as $key => $day ) {
				$checked = ( ! empty( $show_days ) && is_array( $show_days ) && in_array( $key, $show_days, true ) );
				echo '<label for="' . $this->get_field_id( 'show_days' . $key ) . '"><input type="checkbox" id="' . $this->get_field_id( 'show_days' . $key ) . '" value="' . $key . '" name="' . $this->get_field_name( 'show_days[]' ) . '" ' . ( $checked ? 'checked="checked"' : '' ) . ' />' . $day . '</label><br>';
			}
			?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'hide_closed' ); ?>">
				<input id="<?php echo $this->get_field_id( 'hide_closed' ); ?>"
					name="<?php echo $this->get_field_name( 'hide_closed' ); ?>" type="checkbox"
					value="1" <?php echo ! empty( $hide_closed ) ? ' checked="checked"' : ''; ?> />
				<?php _e( 'Hide closed days', 'yoast-local-seo' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show_open_label' ); ?>">
				<input id="<?php echo $this->get_field_id( 'show_open_label' ); ?>"
					name="<?php echo $this->get_field_name( 'show_open_label' ); ?>" type="checkbox"
					value="1" <?php echo ! empty( $show_open_label ) ? ' checked="checked"' : ''; ?> />
				<?php _e( 'Show open now label after opening hour for current day', 'yoast-local-seo' ); ?>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'comment' ); ?>"><?php _e( 'Extra comment', 'yoast-local-seo' ); ?></label>
			<textarea id="<?php echo $this->get_field_id( 'comment' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'comment' ); ?>"><?php echo esc_attr( $comment ); ?></textarea>
		</p>
		<?php // @codingStandardsIgnoreEnd ?>
		<?php

		return '';
	}
}
