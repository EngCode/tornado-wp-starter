<?php
/**
 * Yoast SEO: Local plugin file.
 *
 * @package WPSEO\Admin\OnPage
 */

/**
 * Represents an implementation of the WPSEO_Endpoint interface to register one or multiple endpoints.
 */
class WPSEO_Local_Endpoint_Locations implements WPSEO_Endpoint {

	/**
	 * REST API namespace.
	 *
	 * @var string
	 */
	const REST_NAMESPACE = 'yoast/v1';

	/**
	 * REST API endpoint name.
	 *
	 * @var string
	 */
	const ENDPOINT_RETRIEVE = 'wpseo_locations';

	/**
	 * End-user capability required for the endpoint to give a response.
	 *
	 * @var string
	 */
	const CAPABILITY_RETRIEVE = 'read';

	/**
	 * Constructs the WPSEO_Local_Endpoint_Locations class and sets the service to use.
	 */
	public function __construct() {
	}

	/**
	 * Registers the REST routes that are available on the endpoint.
	 */
	public function register() {
		$args = array(
			'methods'             => 'GET',
			'callback'            => array(
				$this,
				'get_data',
			),
			'permission_callback' => array(
				$this,
				'can_retrieve_data',
			),
		);

		// Register fetch config.
		register_rest_route( self::REST_NAMESPACE, self::ENDPOINT_RETRIEVE, $args );
	}

	/**
	 * Get location data.
	 *
	 * @return WP_REST_Response
	 */
	public function get_data() {
		$location_repository = new WPSEO_Local_Locations_Repository();
		$locations           = $location_repository->get( array(), false );

		$data = array();

		foreach ( $locations as $location ) {
			$data[] = array(
				'ID'    => $location,
				'label' => ( '' !== get_the_title( $location ) ? get_the_title( $location ) : __( 'No title', 'yoast-local-seo' ) ),
			);
		}

		return new WP_REST_Response( $data );
	}

	/**
	 * Determines whether or not data can be retrieved for the registered endpoints.
	 *
	 * @return bool Whether or not data can be retrieved.
	 */
	public function can_retrieve_data() {
		return current_user_can( self::CAPABILITY_RETRIEVE );
	}
}
