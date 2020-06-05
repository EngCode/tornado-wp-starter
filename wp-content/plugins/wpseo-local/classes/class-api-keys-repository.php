<?php
/**
 * Yoast SEO: Local plugin file.
 *
 * @package WPSEO_Local/api-keys
 * @since   11.9
 */

if ( ! class_exists( 'WPSEO_Local_Api_Keys_Repository' ) ) {

	/**
	 * WPSEO_Local_Api_Keys class. Handles all basic needs for the api keys needed for the Google Maps.
	 */
	class WPSEO_Local_Api_Keys_Repository {

		/**
		 * Stores the options for this plugin.
		 *
		 * @var mixed
		 */
		private $options;

		/**
		 * WPSEO_Local_Api_Keys constructor.
		 */
		public function __construct() {
			$this->get_options();
		}

		/**
		 * Get wpseo_local options.
		 */
		private function get_options() {
			$this->options = get_option( 'wpseo_local' );
		}

		/**
		 * Returns the right API key when needed.
		 *
		 * @param null $type Optional. Either 'server' or 'browser'.
		 *
		 * @return mixed|string|void
		 */
		public function get_api_key( $type = null ) {
			$api_key = '';

			if ( isset( $this->options['googlemaps_api_key'] ) ) {
				$api_key = $this->options['googlemaps_api_key'];
			}

			if ( defined( 'WPSEO_LOCAL_GOOGLEMAPS_API_KEY' ) ) {
				$api_key = WPSEO_LOCAL_GOOGLEMAPS_API_KEY;
			}

			if ( empty( $api_key ) && ! empty( $type ) ) {
				if ( $type === 'server' ) {
					$api_key = $this->get_api_key_server();
				}

				if ( $type === 'browser' ) {
					$api_key = $this->get_api_key_browser();
				}
			}

			return $api_key;
		}

		/**
		 * Gets the api server key if it is set or if its set in a constant
		 *
		 * @return string|void
		 */
		public function get_api_key_server() {
			$api_key_server = '';

			if ( isset( $this->options['api_key'] ) ) {
				$api_key_server = $this->options['api_key'];
			}

			if ( defined( 'WPSEO_LOCAL_API_KEY_SERVER' ) ) {
				$api_key_server = WPSEO_LOCAL_API_KEY_SERVER;
			}

			return esc_attr( $api_key_server );
		}

		/**
		 * Gets the api browser key if it is set or if its set in a constant
		 *
		 * @return string|void
		 */
		public function get_api_key_browser() {
			$api_key_browser = '';

			if ( isset( $this->options['local_api_key_browser'] ) ) {
				$api_key_browser = $this->options['local_api_key_browser'];
			}

			if ( defined( 'WPSEO_LOCAL_API_KEY_BROWSER' ) ) {
				$api_key_browser = WPSEO_LOCAL_API_KEY_BROWSER;
			}

			return esc_attr( $api_key_browser );
		}
	}
}
