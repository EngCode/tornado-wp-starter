<?php
/**
 * Yoast SEO: Local plugin file.
 *
 * @package WPSEO_Local\Frontend
 */

if ( ! class_exists( 'WPSEO_Local_Storelocator' ) ) {

	/**
	 * Class WPSEO_Local_Storelocator
	 *
	 * Adds all functionality for the store locator
	 */
	class WPSEO_Local_Storelocator {

		/**
		 * Stores the options for this plugin.
		 *
		 * @var array
		 */
		public $options = array();

		/**
		 * Admin Asset Manager object.
		 *
		 * @var WPSEO_Local_Admin_Assets
		 */
		private $asset_manager;

		/**
		 * Whether to load external stylesheet or not.
		 *
		 * @var boolean
		 */
		public $load_styles = false;

		/**
		 * Default attributes for the `wpseo_storelocator` shortcode.
		 *
		 * @var array
		 */
		protected $shortcode_defaults = array(
			'radius'                  => 10,
			'max_number'              => '',
			'show_radius'             => false,
			'show_nearest_suggestion' => true,
			'show_map'                => true,
			'show_filter'             => false,
			'map_width'               => '100%',
			'scrollable'              => true,
			'draggable'               => true,
			'marker_clustering'       => false,
			'show_country'            => false,
			'show_state'              => false,
			'show_phone'              => false,
			'show_phone_2'            => false,
			'show_fax'                => false,
			'show_email'              => false,
			'show_url'                => false,
			'map_style'               => 'ROADMAP',
			'show_route_label'        => '',
			'oneline'                 => false,
			'show_opening_hours'      => false,
			'hide_closed'             => false,
			'show_category_filter'    => false,
			'from_widget'             => false,
			'widget_title'            => '',
			'before_title'            => '',
			'after_title'             => '',
			'echo'                    => false,
			'width'                   => '100%',
			'height'                  => 300,
			'zoom'                    => -1,
		);

		/**
		 * Constructor.
		 */
		public function __construct() {
			/**
			 * The functionality from this class never needed in the admin area.
			 * So we bail from executing the rest if we are there.
			 */
			if ( is_admin() ) {
				return;
			}

			$this->options = get_option( 'wpseo_local' );

			if ( isset( $this->options['map_view_style'] ) ) {
				$this->shortcode_defaults['map_style'] = $this->options['map_view_style'];
			}

			if ( isset( $this->options['show_route_label'] ) && ! empty( $this->options['show_route_label'] ) ) {
				$this->shortcode_defaults['show_route_label'] = $this->options['show_route_label'];
			}
			else {
				$this->shortcode_defaults['show_route_label'] = __( 'Show route', 'yoast-local-seo' );
			}

			add_shortcode( 'wpseo_storelocator', array( &$this, 'show_storelocator' ) );

			add_action( 'wp_head', array( &$this, 'load_scripts' ), 99 );
		}

		/**
		 * Enqueue the scripts necessary for the Store Locator to work.
		 *
		 * The wp-polyfill asset is needed for versions of WP before 5.0.
		 */
		public function enqueue_scripts() {
			$this->asset_manager = new WPSEO_Local_Admin_Assets();

			$this->asset_manager->enqueue_script( 'store-locator' );

			$default_country = isset( $this->options['default_country'] ) ? WPSEO_Local_Frontend::get_country( $this->options['default_country'] ) : '';
			wp_localize_script( WPSEO_Local_Admin_Assets::PREFIX . 'store-locator', 'storeLocator', [ 'defaultCountry' => $default_country ] );

			add_action( 'wp_footer', 'wpseo_enqueue_geocoder' );
			add_action( 'admin_footer', 'wpseo_enqueue_geocoder' );
		}

		/**
		 * Outputs HTML for the store locator.
		 *
		 * @param array $atts Array of attributes for the store locator shortcode.
		 *
		 * @return string
		 */
		public function show_storelocator( $atts ) {
			global $wpseo_enqueue_geocoder, $wpseo_sl_load_scripts;
			$this->enqueue_scripts();

			// Don't show any output when you don't have multiple locations enabled.
			if ( false == wpseo_has_multiple_locations() ) {
				return '';
			}

			$wpseo_sl_load_scripts = true;
			$atts                  = wpseo_check_falses( shortcode_atts( $this->shortcode_defaults, $atts ) );

			if ( $atts['show_map'] ) {
				$wpseo_enqueue_geocoder = true;
			}

			ob_start();
			?>
			<!--local_seo_store_locator_start-->
			<form action="#wpseo-storelocator-form" method="post" id="wpseo-storelocator-form">
				<fieldset>
					<?php
					$search_string    = isset( $_REQUEST['wpseo-sl-search'] ) ? esc_attr( $_REQUEST['wpseo-sl-search'] ) : '';
					$sl_category_term = ! empty( $_REQUEST['wpseo-sl-category'] ) ? $_REQUEST['wpseo-sl-category'] : '';
					?>
					<p>
						<label for="wpseo-sl-search"><?php echo apply_filters( 'yoast-local-seo-search-label', __( 'Enter your postal code, city and / or state', 'yoast-local-seo' ) ); ?></label>
						<input type="text" name="wpseo-sl-search" id="wpseo-sl-search" value="<?php echo esc_attr( $search_string ); ?>">
						<input type="hidden" name="wpseo-sl-lat" id="wpseo-sl-lat" value="">
						<input type="hidden" name="wpseo-sl-lng" id="wpseo-sl-lng" value="">

						<?php
						// Show icon for retrieving current location.
						if ( true === wpseo_may_use_current_location() ) {
							echo ' <button type="button" class="wpseo_use_current_location" data-target="wpseo-sl-search"><img src="' . esc_url( plugins_url( 'images/location-icon.svg', WPSEO_LOCAL_FILE ) ) . '" class="wpseo_use_current_location_image" height="24" width="24" alt="' . esc_attr__( 'Use my current location', 'yoast-local-seo' ) . '" data-loading-text="' . esc_attr__( 'Determining current location', 'yoast-local-seo' ) . '"></button> ';
						}

						// Show the radius selectbox.
						if ( $atts['show_radius'] ) {
							esc_html_e( 'within', 'yoast-local-seo' );
							?>
							<select name="wpseo-sl-radius" id="wpseo-sl-radius">
								<?php
								$radius_array    = array( 1, 5, 10, 25, 50, 100, 250, 500, 1000 );
								$selected_radius = ! empty( $_REQUEST['wpseo-sl-radius'] ) ? esc_attr( $_REQUEST['wpseo-sl-radius'] ) : $atts['radius'];

								foreach ( $radius_array as $radius ) {
									echo '<option value="' . (int) $radius . '" ' . selected( $selected_radius, $radius, false ) . '>' . (int) $radius . ( ( $this->options['unit_system'] === 'METRIC' ) ? 'km' : 'mi' ) . '</option>';
								}
								?>
							</select>
							<?php
						}
						else {
							?>
							<input type="hidden" name="wpseo-sl-radius" id="wpseo-sl-radius-text" value="<?php echo esc_attr( $atts['radius'] ); ?>">
							<?php
						}
						?>
					</p>

					<?php if ( $atts['show_filter'] ) { ?>
						<?php
						$terms = get_terms( 'wpseo_locations_category' );
						?>
						<?php if ( count( $terms ) > 0 ) { ?>
							<p class="sl-filter">
								<label for="wpseo-sl-category"><?php esc_html_e( 'Filter by category', 'yoast-local-seo' ); ?></label>
								<select name="wpseo-sl-category" id="wpseo-sl-category">
									<option value=""></option>
									<?php
									foreach ( $terms as $term ) {
										echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( $sl_category_term, $term->term_id, false ) . '>' . esc_html( $term->name ) . '</option>';
									}
									?>
								</select>
							</p>
						<?php } ?>
					<?php } ?>

					<p class="sl-submit">
						<input type="submit" value="<?php esc_attr_e( 'Search', 'yoast-local-seo' ); ?>">
					</p>

				</fieldset>
			</form>

			<div id="wpseo-storelocator-results">
				<?php
				$results = false;

				if ( false === empty( $_POST ) ) {
					$results = $this->get_results();
				}

				if ( $atts['show_map'] ) {
					$location_ids = array();
					$ids          = 'all';
					if ( ! empty( $_POST ) && ! is_wp_error( $results ) ) {
						foreach ( $results['locations'] as $location ) {
							$location_ids[] = $location['ID'];
						}
						$ids = implode( ',', $location_ids );
					}

					$map_atts = array(
						'id'                   => $ids,
						'max_number'           => $atts['max_number'],
						'width'                => $atts['map_width'],
						'from_sl'              => true,
						'show_route'           => true,
						'scrollable'           => $atts['scrollable'],
						'draggable'            => $atts['draggable'],
						'marker_clustering'    => $atts['marker_clustering'],
						'map_style'            => $atts['map_style'],
						'show_category_filter' => $atts['show_category_filter'],
						'zoom'                 => $atts['zoom'],
						'width'                => $atts['width'],
						'height'               => $atts['height'],
						'show_phone'           => $atts['show_phone'],
						'show_phone_2'         => $atts['show_phone_2'],
						'show_fax'             => $atts['show_fax'],
						'show_country'         => $atts['show_country'],
						'show_phone'           => $atts['show_phone'],
						'show_email'           => $atts['show_email'],
						'show_url'             => $atts['show_url'],
					);
					echo wpseo_local_show_map( $map_atts );
				}

				if ( false === empty( $_POST ) ) :
					if ( ! is_wp_error( $results ) ) {
						$show_suggestion = $results['in_radius'] <= 0 && true === $atts['show_nearest_suggestion'] && ! empty( $results['locations'] );

						if ( $results['in_radius'] > 0 ) {
							$number = count( $results['locations'] );
							/* translators: %d extends to the number of found locations in the radius */
							echo '<h2>' . sprintf( esc_html( _n( '%d result has been found', '%d results have been found', $number, 'yoast-local-seo' ) ), (int) $number ) . '</h2>';

							foreach ( $results['locations'] as $key => $location ) {
								$this->get_location_details( $location['ID'], $atts );
							}
						}
						else {
							echo '<h2>' . esc_html__( 'No results found', 'yoast-local-seo' ) . '</h2>';

							if ( $show_suggestion ) {
								foreach ( $results['locations'] as $distance => $location ) {
									/* translators: %s extends to the distance in miles */
									$text_mi = sprintf( __( 'The nearest location is %s miles away', 'yoast-local-seo' ), $distance );
									/* translators: %s extends to the distance in kilometers */
									$text_km = sprintf( __( 'The nearest location is %s kilometers away', 'yoast-local-seo' ), $distance );

									$text = ( ( $this->options['unit_system'] === 'METRIC' ) ? $text_km : $text_mi );
									$text = apply_filters_deprecated( 'wpso_local_no_stores_in_radius', array( $text ), 'WPSEO Local 12.3', 'Yoast\WP\Local\no_stores_in_radius' );
									// @codingStandardsIgnoreLine
									$text = apply_filters( 'Yoast\WP\Local\no_stores_in_radius', $text );

									echo '<p class="nearest_location">' . esc_html( $text ) . '</p>';

									$this->get_location_details( $location['ID'], $atts );
								}
							}
						}
					}
					else {
						echo '<h2>' . esc_html__( 'No results found', 'yoast-local-seo' ) . '</h2>';
					}

				endif;
				?>
			</div><!--local_seo_store_locator_end-->

			<?php
			$output = ob_get_contents();
			ob_end_clean();

			return $output;
		}

		/**
		 * Retrieves the search results based on given search term (zipcode or city).
		 *
		 * @return array | WP_Error
		 */
		public function get_results() {
			global $wpdb;

			if ( empty( $_POST['wpseo-sl-search'] ) ) {
				return new WP_Error( 'wpseo-no-input', __( 'Please enter a zipcode or city', 'yoast-local-seo' ) );
			}

			$nr_results       = ( ! empty( $this->options['sl_num_results'] ) ) ? $this->options['sl_num_results'] : 10;
			$metric           = ( $this->options['unit_system'] === 'METRIC' ) ? 'km' : 'mi';
			$radius           = ( ! empty( $_REQUEST['wpseo-sl-radius'] ) ) ? $_REQUEST['wpseo-sl-radius'] : 99999;
			$sl_category_term = ( ! empty( $_REQUEST['wpseo-sl-category'] ) ) ? $_REQUEST['wpseo-sl-category'] : '';
			$distances        = array(
				'in_radius' => 0,
				'locations' => array(),
			);

			$search_string = isset( $_REQUEST['wpseo-sl-search'] ) ? esc_attr( $_REQUEST['wpseo-sl-search'] ) : '';
			if ( $search_string == '' ) {
				return $distances;
			}

			$coordinates = (object) array(
				'lat' => floatval( $_POST['wpseo-sl-lat'] ),
				'lng' => floatval( $_POST['wpseo-sl-lng'] ),
			);

			if ( ! $coordinates ) {
				return new WP_Error( 'wpseo-get-results-error', __( 'No valid coordinates. We cannot complete the search.', 'yoast-local-seo' ) );
			}

			$replacements = array();

			// Extend SQL with category filter.
			$inner_join = '';
			if ( $sl_category_term != '' ) {
				$inner_join .= "
				INNER JOIN $wpdb->term_relationships AS term_rel ON p.ID = term_rel.object_id
				INNER JOIN $wpdb->term_taxonomy AS taxo ON term_rel.term_taxonomy_id = taxo.term_taxonomy_id
				AND taxo.taxonomy = 'wpseo_locations_category'
				AND taxo.term_id = %s
				";

				$replacements[] = $sl_category_term;
			}

			/*
			 * Get all coordinates from posts.
			 */

			// @codingStandardsIgnoreStart

			$post_status = array( 'publish' );
			// If the user is logged in and can edit posts, add more post statuses.
			if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
				$post_status[] = array_push( $post_status, 'draft', 'future', 'pending', 'private' );
			}

			$replacements = array_merge( $replacements, $post_status );

			$locations = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT p.ID, m1.meta_value as lat, m2.meta_value as lng
					FROM {$wpdb->posts} p
					INNER JOIN {$wpdb->postmeta} m1 ON p.ID = m1.post_id
					INNER JOIN {$wpdb->postmeta} m2 ON p.ID = m2.post_id
					$inner_join
					WHERE
						p.post_type = 'wpseo_locations' AND
						p.post_status IN( " . implode( ',', array_fill( 0, count( $post_status ), '%s' ) ) . " ) AND
						m1.meta_key = '_wpseo_coordinates_lat' AND
						m2.meta_key = '_wpseo_coordinates_long'
					GROUP BY p.ID",
					$replacements
				)
			);
			// @codingStandardsIgnoreEnd

			// Calculate distance.
			$in_radius     = array();
			$out_of_radius = array();

			if ( 0 === $wpdb->num_rows ) {
				return $distances;
			}

			foreach ( $locations as $location ) {
				// Skip locations with empty lat/long coordinates.
				if ( empty( $location->lat ) || empty( $location->lng ) ) {
					continue;
				}

				$distance     = $this->get_distance( $coordinates->lat, $coordinates->lng, $location->lat, $location->lng );
				$distance_key = ( round( $distance[ $metric ], 4 ) * 10000 );

				// Filter on radius.
				if ( $distance[ $metric ] > $radius ) {
					$out_of_radius[ $distance[ $metric ] ] = array(
						'distance' => $distance_key,
						'ID'       => $location->ID,
					);
				}
				else {
					$in_radius[] = array(
						'distance' => $distance_key,
						'ID'       => $location->ID,
					);
				}
			}

			if ( 0 === count( $in_radius ) ) {
				// No results were found inside the given radius.
				ksort( $out_of_radius, SORT_NUMERIC );

				$distances['locations'] = array_slice( $out_of_radius, 0, 1, true );

				return $distances;
			}

			usort( $in_radius, array( $this, 'sort_distances' ) );
			$in_radius = array_slice( $in_radius, 0, $nr_results, true );

			$distances['in_radius'] = count( $in_radius );
			$distances['locations'] = $in_radius;

			return $distances;
		}

		/**
		 * Sort multidimensional array with distances
		 *
		 * @param float $a Distance A.
		 * @param float $b Distance B.
		 *
		 * @return mixed
		 */
		public function sort_distances( $a, $b ) {
			return ( $a['distance'] - $b['distance'] );
		}

		/**
		 * Calculates distance between two sets of coordinates. Used code from http://www.inkplant.com/code/calculate-the-distance-between-two-points.php
		 *
		 * @param float $latitude1  First latitude.
		 * @param float $longitude1 First longitude.
		 * @param float $latitude2  Second latitude.
		 * @param float $longitude2 Second longitude.
		 *
		 * @return array
		 */
		public function get_distance( $latitude1, $longitude1, $latitude2, $longitude2 ) {
			$theta = ( $longitude1 - $longitude2 );
			$miles = ( ( sin( deg2rad( $latitude1 ) ) * sin( deg2rad( $latitude2 ) ) ) + ( cos( deg2rad( $latitude1 ) ) * cos( deg2rad( $latitude2 ) ) * cos( deg2rad( $theta ) ) ) );
			$miles = acos( $miles );
			$miles = rad2deg( $miles );
			$miles = ( $miles * 60 * 1.1515 );
			$km    = ( $miles * 1.609344 );

			return array(
				'mi' => $miles,
				'km' => $km,
			);
		}

		/**
		 * Load jQuery script (if not already loaded before).
		 */
		public function load_scripts() {
			if ( false === wp_script_is( 'jquery', 'done' ) && false !== apply_filters( 'wpseo_local_load_jquery', true ) ) {
				wp_enqueue_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js', array(), '1.10.2', true );
			}
		}

		/**
		 * Show all location information.
		 *
		 * @param int   $location_id Post ID of the location.
		 * @param array $atts        Array of attributes, used for displaying the address.
		 *                           These are matching attributes for the wpseo_local_show_address() method.
		 */
		public function get_location_details( $location_id, $atts ) {
			$coords_lat  = get_post_meta( $location_id, '_wpseo_coordinates_lat', true );
			$coords_long = get_post_meta( $location_id, '_wpseo_coordinates_long', true );
			?>

			<div class="wpseo-result">
				<?php
				$address_atts = array(
					'id'                 => $location_id,
					'show_state'         => $atts['show_state'],
					'show_country'       => $atts['show_country'],
					'show_phone'         => $atts['show_phone'],
					'show_phone_2'       => $atts['show_phone_2'],
					'show_fax'           => $atts['show_fax'],
					'show_email'         => $atts['show_email'],
					'show_url'           => $atts['show_url'],
					'show_opening_hours' => $atts['show_opening_hours'],
					'hide_closed'        => $atts['hide_closed'],
					'oneline'            => $atts['oneline'],
					'from_sl'            => true,
					'echo'               => false,
					'hide_json_ld'       => true,
				);
				$location     = wpseo_local_show_address( $address_atts );

				echo apply_filters( 'wpseo_local_sl_result', $location, $location_id );
				?>
				<div class="wpseo-sl-route">
					<a href="javascript:;" onclick="wpseo_sl_show_route( this, '<?php echo $coords_lat; ?>', '<?php echo $coords_long; ?>' );"><?php echo $atts['show_route_label']; ?></a>
				</div>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'wpseo_local_storelocator' ) ) {
	/**
	 * Initialize the store locator.
	 *
	 * @param array $atts Array of attributes for displaying the store locator.
	 *
	 * @return string
	 */
	function wpseo_local_storelocator( $atts ) {
		global $wpseo_local_storelocator;

		if ( null == $wpseo_local_storelocator ) {
			$wpseo_local_storelocator = new WPSEO_Local_Storelocator();
		}

		return $wpseo_local_storelocator->show_storelocator( $atts );
	}
}
$wpseo_sl_load_scripts = false;
