<?php
/**
 * @package WPSEO_Local\Frontend\Schema
 */

/**
 * Class WPSEO_Local_JSON_LD
 *
 * Manages the Schema for a Place.
 *
 * @property WPSEO_Schema_Context $context A value object with context variables.
 * @property array                $options Local SEO options.
 */
class WPSEO_Local_Place {

	/**
	 * Stores the options for this plugin.
	 *
	 * @var array
	 */
	public $options = array();

	/**
	 * A value object with context variables.
	 *
	 * @var WPSEO_Schema_Context
	 */
	public $context;

	/**
	 * Constructor.
	 *
	 * @param WPSEO_Schema_Context $context A value object with context variables.
	 */
	public function __construct( WPSEO_Schema_Context $context ) {
		$this->context = $context;
		$this->options = get_option( 'wpseo_local' );
	}

	/**
	 * Retrieves place data.
	 *
	 * @param int $location_id Location ID.
	 *
	 * @return array|bool Place Schema data for location.
	 */
	public function get_place_data( $location_id = null ) {
		$locations = $this->get_locations( $location_id );
		if ( is_array( $locations ) ) {
			return $this->get_place_data_from_locations( $locations );
		}

		return false;
	}

	/**
	 * Given an array of locations returns Place Schema data for the first.
	 *
	 * @param array $locations Array of locations.
	 *
	 * @return array Place Schema data.
	 */
	public function get_place_data_from_locations( $locations ) {
		$location = reset( $locations );

		$data          = array();
		$data['@type'] = 'Place';
		$data['@id']   = $this->context->canonical . WPSEO_Local_Schema_IDs::PLACE_ID;

		// Add Address field.
		if ( ! empty( $location['business_address'] ) ) {
			$business_address[] = $location['business_address'];
		}
		if ( ! empty( $location['business_address_2'] ) ) {
			$business_address[] = $location['business_address_2'];
		}

		$data['address'] = array(
			'@type'           => 'PostalAddress',
			'@id'             => $this->context->canonical . WPSEO_Local_Schema_IDs::ADDRESS_ID,
			'streetAddress'   => ( ! empty( $business_address ) ) ? join( ' ', $business_address ) : '',
			'addressLocality' => ( ! empty( $location['business_city'] ) ) ? $location['business_city'] : '',
			'postalCode'      => ( ! empty( $location['business_zipcode'] ) ) ? $location['business_zipcode'] : '',
			'addressRegion'   => ( ! empty( $location['business_state'] ) ) ? $location['business_state'] : '',
			'addressCountry'  => ( ! empty( $location['business_country'] ) ) ? $location['business_country'] : '',
		);

		// Add coordinates.
		if ( isset( $location['coords'] ) ) {
			$data['geo'] = array(
				'@type'     => 'GeoCoordinates',
				'latitude'  => ( ! empty( $location['coords']['lat'] ) ) ? $location['coords']['lat'] : '',
				'longitude' => ( ! empty( $location['coords']['long'] ) ) ? $location['coords']['long'] : '',
			);
		}

		// Add Opening Hours.
		$data['openingHoursSpecification'] = $this->add_opening_hours( $location );

		// Add additional regular fields.
		$standard_fields = array(
			'telephone' => 'business_phone',
			'faxNumber' => 'business_fax',
		);

		foreach ( $standard_fields as $data_key => $option_field ) {
			if ( ! empty( $location[ $option_field ] ) ) {
				$data[ $data_key ] = $location[ $option_field ];
			}
		}

		return $data;
	}

	/**
	 * Retrieves our locations.
	 *
	 * @param null|int $location_id The location to retrieve.
	 *
	 * @return array|bool Array of locations on success, false otherwise.
	 */
	public function get_locations( $location_id = null ) {
		$repo      = new WPSEO_Local_Locations_Repository();
		$locations = $repo->get( array( 'id' => $location_id ) );

		if ( count( $locations ) === 0 ) {
			return false;
		}

		return $locations;
	}

	/**
	 * Calculates the opening hours schema for a location.
	 *
	 * @param array $location Array with location data.
	 *
	 * @return array Array with openingHoursSpecification data.
	 * @link https://developers.google.com/search/docs/data-types/local-business
	 *
	 * @link https://schema.org/OpeningHoursSpecification
	 */
	private function add_opening_hours( $location ) {
		if ( ! isset( $this->options['hide_opening_hours'] ) || ( isset( $this->options['hide_opening_hours'] ) && $this->options['hide_opening_hours'] !== 'on' ) ) {
			// Force all days to show 24h opening times.
			if ( $this->is_open_247( $location ) ) {
				return $this->opening_hours_247();
			}

			return $this->specific_opening_hours( $location );
		}

		return array();
	}

	/**
	 * Function to determine whether a location is open 24/7 or not.
	 *
	 * @param array $location Array with location data.
	 *
	 * @return bool False when location is not open 24/7, true when it is.
	 */
	private function is_open_247( $location ) {
		if ( wpseo_has_multiple_locations() ) {
			$open_247 = get_post_meta( $location['post_id'], '_wpseo_open_247', true );

			return ( $open_247 === 'on' );
		}

		$open_247 = isset( $this->options['open_247'] ) ? $this->options['open_247'] : '';

		return ( $open_247 === 'on' );
	}

	/**
	 * Returns 24/7 opening hours Schema.
	 *
	 * @return array Array with openingHoursSpecification data.
	 */
	private function opening_hours_247() {
		$output = array(
			'@type'     => 'OpeningHoursSpecification',
			'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ),
			'opens'     => '00:00',
			'closes'    => '23:59',
		);

		return $output;
	}

	/**
	 * Returns 24/7 opening hours Schema.
	 *
	 * @param array $location Location information.
	 *
	 * @return array Array with openingHoursSpecification data.
	 */
	private function specific_opening_hours( $location ) {
		$output                 = array();
		$opening_hours_repo     = new WPSEO_Local_Opening_Hours_Repository();
		$days                   = $opening_hours_repo->get_days();
		$location_opening_hours = array();

		foreach ( $days as $key => $day ) {
			$opening_hours = $opening_hours_repo->get_opening_hours( $key, ( ! empty( $location['post_id'] ) ? $location['post_id'] : 'options' ), $this->options, true );

			$opens  = $opening_hours['value_from'];
			$closes = 'closed';

			if ( $opens !== 'closed' ) {
				$closes = ( ( $opening_hours['value_second_to'] !== 'closed' && $opening_hours['use_multiple_times'] === true ) ? $opening_hours['value_second_to'] : $opening_hours['value_to'] );
			}

			if ( $opening_hours['open_24h'] === 'on' ) {
				$location_opening_hours['open_24h']['days'][] = $this->get_day_of_week( $opening_hours['value_abbr'] );
			}

			if ( isset( $location_opening_hours[ $opens . $closes ] ) ) {
				$location_opening_hours[ $opens . $closes ]['days'][] = $this->get_day_of_week( $opening_hours['value_abbr'] );
			}

			if ( ! isset( $location_opening_hours[ $opens . $closes ] ) && $opening_hours['open_24h'] !== 'on' ) {
				$location_opening_hours[ $opens . $closes ] = array(
					'opens'  => $opens,
					'closes' => $closes,
					'days'   => array(
						$this->get_day_of_week( $opening_hours['value_abbr'] ),
					),
				);
			}
		}

		foreach ( $location_opening_hours as $key => $value ) {
			$day = array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => $value['days'],
			);
			if ( isset( $value['opens'] ) && $value['opens'] == 'closed' ) {
				$day['opens']  = '00:00';
				$day['closes'] = '00:00';
			}
			elseif ( $key === 'open_24h' ) {
				$day['opens']  = '00:00';
				$day['closes'] = '23:59';
			}
			else {
				$day['opens']  = $value['opens'];
				$day['closes'] = $value['closes'];
			}

			$output[] = $day;

		}

		return $output;
	}

	/**
	 * Returns long day name based on our shortened days of week.
	 *
	 * @param string $day Day of week in short notation.
	 *
	 * @return string Day of week.
	 */
	private function get_day_of_week( $day ) {
		switch ( $day ) {
			case 'Mo':
				return 'Monday';
			case 'Tu':
				return 'Tuesday';
			case 'We':
				return 'Wednesday';
			case 'Th':
				return 'Thursday';
			case 'Fr':
				return 'Friday';
			case 'Sa':
				return 'Saturday';
			case 'Su':
			default:
				return 'Sunday';
		}
	}
}
