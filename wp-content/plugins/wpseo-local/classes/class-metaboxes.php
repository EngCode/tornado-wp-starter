<?php
/**
 * Yoast SEO: Local plugin file.
 *
 * @package WPSEO_Local\Admin
 * @since   1.0
 */

if ( ! class_exists( 'WPSEO_Local_Metaboxes' ) ) {

	/**
	 * WPSEO_Local_Metaboxes class.
	 *
	 * @since   1.0
	 */
	class WPSEO_Local_Metaboxes {

		/**
		 * Stores the options for this plugin.
		 *
		 * @var array
		 */
		public $options;

		/**
		 * Days used for opening hours.
		 *
		 * @var array
		 */
		public $days = array();

		/**
		 * Holds WPSEO Local Timezone Repository instance.
		 *
		 * @var WPSEO_Local_Locations_Repository
		 */
		private $wpseo_local_locations_repository;

		/**
		 * Holds WPSEO Local Timezone Repository instance.
		 *
		 * @var mixed
		 */
		private $wpseo_local_timezone_repository;

		/**
		 * Admin Asset Manager object.
		 *
		 * @var WPSEO_Local_Admin_Assets
		 */
		private $asset_manager;

		/**
		 * WPSEO Admin Asset Manager object.
		 *
		 * @var WPSEO_Admin_Asset_Manager
		 */
		private $wpseo_asset_manager;

		/**
		 * Contains the current location id.
		 *
		 * @var $location_id int
		 */
		private $location_id;

		/**
		 * Contains the current location meta data
		 *
		 * @var $location_meta array
		 */
		private $location_meta;

		/**
		 * Contains array of all WPSEO Local single locations.
		 *
		 * @var $locations array
		 */
		private $locations;

		/**
		 * Contains the select options for the various <select> dropdowns.
		 *
		 * @var array
		 */
		private $locations_select_options;

		/**
		 * Holds the API keys repository.
		 *
		 * @var WPSEO_Local_Api_Keys_Repository
		 */
		private $api_repository;

		/**
		 * @var array
		 */
		private $tabs;

		/**
		 * Constructor for the WPSEO_Local_Metaboxes class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			$this->wpseo_asset_manager = new WPSEO_Admin_Asset_Manager();
			$this->asset_manager       = new WPSEO_Local_Admin_Assets();
			$this->asset_manager->register_assets();

			add_action( 'current_screen', array( $this, 'register_media_buttons' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );

			// Add scripts for buttons for adding shortcodes in RTE in front-end when using Beaver Builder.
			if ( isset( $_GET['fl_builder'] ) ) {
				add_action( 'wp_footer', array( &$this, 'add_mce_popup' ) );
			}

			/**
			 * The rest is only applicable when using multiple locations.
			 */
			if ( wpseo_has_multiple_locations() ) {
				$this->options = get_option( 'wpseo_local' );
				$this->get_timezone_repository();
				$this->get_locations_repositry();

				$this->api_repository = new WPSEO_Local_Api_Keys_Repository();

				add_action( 'add_meta_boxes', array( $this, 'set_locations' ) );
				add_action( 'add_meta_boxes', array( $this, 'set_current_location' ) );
				add_action( 'add_meta_boxes', array( $this, 'set_location_select_options' ) );
				add_action( 'add_meta_boxes', array( $this, 'set_tabs' ) );

				// Create custom post type functionality + meta boxes for Custom Post Type.
				add_action( 'add_meta_boxes', array( $this, 'add_location_metaboxes' ) );

				// Add panels for each tab.
				add_action( 'wpseo-local-panel-content-business_info', array( $this, 'business_info_panel_content' ) );
				add_action( 'wpseo-local-panel-content-opening_hours', array( $this, 'opening_hours_panel_content' ) );
				add_action( 'wpseo-local-panel-content-maps_settings', array( $this, 'maps_settings_panel_content' ) );

				add_action( 'save_post', array( $this, 'wpseo_locations_save_meta' ), 10, 2 );

				// Only add the filter on Yoast SEO before 3.0, because 3.0 removed this filter. 2.3.5 was the last 2.x version.
				if ( version_compare( WPSEO_VERSION, '2.3.5', '<=' ) ) {
					add_filter( 'wpseo_linkdex_results', array( &$this, 'filter_linkdex_results' ), 10, 3 );
				}
			}
		}

		/**
		 * Set the WPSEO_Local_Locations_Repository in the $wpseo_local_locations_repository property.
		 */
		private function get_locations_repositry() {
			$this->wpseo_local_locations_repository = new WPSEO_Local_Locations_Repository();
		}

		/**
		 * Set WPSEO Local Core Timezone Repository in local property.
		 *
		 * @since 4.2
		 */
		private function get_timezone_repository() {
			$wpseo_local_timezone_repository       = new WPSEO_Local_Timezone_Repository();
			$this->wpseo_local_timezone_repository = $wpseo_local_timezone_repository;
		}

		/**
		 * Set all location ID's to the $locations property.
		 */
		public function set_locations() {
			$this->locations = $this->wpseo_local_locations_repository->get( array(), false );
		}

		/**
		 * Set current location ID and meta data local properties.
		 */
		public function set_current_location() {
			$this->location_id = get_the_ID();
			$meta_data         = $this->wpseo_local_locations_repository->get( array( 'id' => $this->location_id ) );

			$this->location_meta['business_type']                = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_type'] : '';
			$this->location_meta['business_address']             = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_address'] : '';
			$this->location_meta['business_address_2']           = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_address_2'] : '';
			$this->location_meta['business_city']                = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_city'] : '';
			$this->location_meta['business_state']               = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_state'] : '';
			$this->location_meta['business_zipcode']             = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_zipcode'] : '';
			$this->location_meta['business_country']             = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_country'] : '';
			$this->location_meta['business_phone']               = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_phone'] : '';
			$this->location_meta['business_phone_2nd']           = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_phone_2nd'] : '';
			$this->location_meta['business_fax']                 = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_fax'] : '';
			$this->location_meta['business_email']               = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_email'] : '';
			$this->location_meta['business_url']                 = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_url'] : '';
			$this->location_meta['business_vat']                 = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_vat'] : '';
			$this->location_meta['business_tax']                 = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_tax'] : '';
			$this->location_meta['business_coc']                 = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_coc'] : '';
			$this->location_meta['business_price_range']         = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_price_range'] : '';
			$this->location_meta['business_currencies_accepted'] = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_currencies_accepted'] : '';
			$this->location_meta['business_payment_accepted']    = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_payment_accepted'] : '';
			$this->location_meta['business_area_served']         = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_area_served'] : '';
			$this->location_meta['coords']['lat']                = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['coords']['lat'] : '';
			$this->location_meta['coords']['long']               = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['coords']['long'] : '';
			$this->location_meta['business_timezone']            = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_timezone'] : '';
			$this->location_meta['business_logo']                = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['business_logo'] : '';
			$this->location_meta['custom_marker']                = ! empty( $meta_data ) ? $meta_data[ $this->location_id ]['custom_marker'] : '';
		}

		/**
		 * Set all <options> for the location <select> dropdowns in an array.
		 */
		public function set_location_select_options() {
			$this->locations_select_options[] = '<option value="">' . esc_html__( 'Select a location', 'yoast-local-seo' ) . '--</option>';
			if ( ! empty( $this->locations ) ) {
				$this->locations_select_options['all'] = '<option value="all">' . esc_html__( 'All locations', 'yoast-local-seo' ) . '</option>';

				foreach ( $this->locations as $location_id ) {
					$this->locations_select_options[ $location_id ] = '<option value="' . esc_attr( $location_id ) . '">' . esc_html( get_the_title( $location_id ) ) . '</option>';
				}
			}
		}

		/**
		 * Set tabs to $tabs property. These tabs can be filtered using wpseo-local-location-meta-tabs.
		 */
		public function set_tabs() {
			$tabs = array(
				array(
					'id'    => 'business_info',
					'title' => __( 'Business info', 'yoast-local-seo' ),
					'icon'  => 'admin-home',
				),
				array(
					'id'    => 'opening_hours',
					'title' => __( 'Opening hours', 'yoast-local-seo' ),
					'icon'  => 'clock',
				),
				array(
					'id'    => 'maps_settings',
					'title' => __( 'Map settings', 'yoast-local-seo' ),
					'icon'  => 'location-alt',
				),
			);

			$tabs = apply_filters( 'wpseo-local-location-meta-tabs', $tabs );

			$this->tabs = $tabs;
		}

		/**
		 * Get all <options> for the location <select> dropdowns in an array.
		 *
		 * @param bool $include_all_locations_option Optional. Whether or not to include
		 *                                           the 'all' option. Defaults to true.
		 *
		 * @return string
		 */
		public function get_location_select_options( $include_all_locations_option = true ) {
			$select_options = $this->locations_select_options;
			if ( ! $include_all_locations_option ) {
				unset( $select_options['all'] );
			}

			return join( "\n", $select_options );
		}

		/**
		 * Adds metabox for editing screen of the wpseo_locations Custom Post Type.
		 */
		public function add_location_metaboxes() {
			add_meta_box(
				'wpseo_locations',
				__( 'Yoast Local SEO', 'yoast-local-seo' ),
				array( &$this, 'metabox_locations' ),
				'wpseo_locations',
				'normal',
				'high'
			);
		}

		/**
		 * Add tabs for navigation Location Meta.
		 */
		private function tab_navigation() {
			echo '<div class="wpseo-local-metabox-menu">';
			echo '<ul role="tablist" class="yoast-seo-local-aria-tabs" aria-label="Yoast SEO: Local">';
			foreach ( $this->tabs as $key => $tab ) {
				$active = ( 0 === $key ) ? true : false;

				$link_class = array( 'wpseo-local-meta-section-link' );
				if ( $active ) {
					$link_class[] = 'yoast-active-tab';
				}

				echo '<li role="presentation" ' . ( ( $active ) ? 'class="active"' : '' ) . '>';
				echo '<a role="tab" href="#wpseo-local-tab-' . $tab['id'] . '"  class="' . join( ' ', $link_class ) . '" id="wpseo-local-tab-' . $tab['id'] . '-content">';
				echo '<span class="dashicons dashicons-' . $tab['icon'] . '"></span>';
				echo $tab['title'];
				echo '</a>';
				echo '</li>';
			}
			echo '</ul>';
			echo '</div> <!-- .wpseo-metabox-menu -->';
		}

		/**
		 * Add a panel for each tab.
		 */
		private function tabs_panels() {
			foreach ( $this->tabs as $key => $tab ) {
				$active = ( 0 === $key ) ? true : false;

				$panel_class = array( 'wpseo-local-meta-section' );
				if ( $active ) {
					$panel_class[] = 'active';
				}

				echo '<div role="tabpanel" id="wpseo-local-tab-' . $tab['id'] . '" class="' . join( ' ', $panel_class ) . '">';
				echo '<div class="wpseo-local-metabox-content">';
				do_action( 'wpseo-local-panel-content-' . $tab['id'] );
				echo '</div>';
				echo '</div>';
			}
		}

		/**
		 * The content for the business info tab.
		 */
		public function business_info_panel_content() {
			$business_types_repo      = new WPSEO_Local_Business_Types_Repository();
			$flattened_business_types = $business_types_repo->get_business_types();
			$business_types_help      = new WPSEO_Admin_Help_Panel(
				'business_types_help',
				__( 'Help with: Business types', 'yoast-local-seo' ),
				sprintf(
				/* translators: 1: HTML <a> open tag; 2: <a> close tag. */
					__( 'If your business type is not listed, please read %1$sthe FAQ entry%2$s.', 'yoast-local-seo' ),
					'<a href="https://yoa.st/business-listing" target="_blank">',
					'</a>'
				),
				'has-wrapper'
			);

			$price_indication_help = new WPSEO_Admin_Help_Panel(
				'price_indication_help',
				__( 'Help with: Price indication', 'yoast-local-seo' ),
				esc_html__( 'Select the price indication of your business, where $ is cheap and $$$$$ is expensive.', 'yoast-local-seo' ),
				'has-wrapper'
			);

			$area_served_help = new WPSEO_Admin_Help_Panel(
				'area_served_help',
				__( 'Help with: Area served', 'yoast-local-seo' ),
				esc_html__( 'The geographic area where a service or offered item is provided.', 'yoast-local-seo' ),
				'has-wrapper'
			);

			$api_key = $this->api_repository->get_api_key( 'browser' );

			if ( count( $this->locations ) > 0 ) {

				echo '<label for="wpseo_copy_from_location" class="textinput">' . esc_html__( 'Copy data from another location:', 'yoast-local-seo' ) . '</label>';
				echo '<select class="select2-select" name="_wpseo_copy_from_location" id="wpseo_copy_from_location" style="width: 400px;" data-placeholder="' . esc_attr__( 'Choose your location', 'yoast-local-seo' ) . '">';
				echo $this->get_location_select_options( false );
				echo '</select>';

				echo '<p class="yoast-local-seo-field-desc"><em><strong>' . esc_html__( 'Note:', 'yoast-local-seo' ) . '</strong> ' . esc_html__( 'selecting a location will overwrite all data below. If you accidently selected a location, just refresh the page and make sure you don\'t save it.', 'yoast-local-seo' ) . '</em></p>';
				echo '<br class="clear">';
			}

			echo '<p><label class="textinput" for="wpseo_business_type">' . esc_html__( 'Business type', 'yoast-local-seo' ) . $business_types_help->get_button_html() . ' </label>';
			echo '<select class="select2-select" name="_wpseo_business_type" id="wpseo_business_type" style="width: 400px;" data-placeholder="' . esc_attr__( 'Choose your business type', 'yoast-local-seo' ) . '">';
			foreach ( $flattened_business_types as $bt_option => $bt_label ) {
				echo '<option ' . selected( $this->location_meta['business_type'], $bt_option, false ) . ' value="' . $bt_option . '">' . $bt_label . '</option>';
			}
			echo '</select></p>';
			/* translators: First %s extends to link opening tag with a link to Yoast knowledge base; Second %s closes the link tag. */
			echo $business_types_help->get_panel_html();

			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_business_address">' . esc_html__( 'Business address', 'yoast-local-seo' ) . '</label>';
			echo '<input type="text" name="_wpseo_business_address" id="wpseo_business_address" value="' . esc_attr( $this->location_meta['business_address'] ) . '" class="wpseo_local_address_input" /></p>';
			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_business_address_2">' . esc_html__( 'Business address line 2', 'yoast-local-seo' ) . '</label>';
			echo '<input type="text" name="_wpseo_business_address_2" id="wpseo_business_address_2" value="' . esc_attr( $this->location_meta['business_address_2'] ) . '" /></p>';
			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_business_city">' . esc_html__( 'Business city', 'yoast-local-seo' ) . '</label>';
			echo '<input type="text" name="_wpseo_business_city" id="wpseo_business_city" value="' . esc_attr( $this->location_meta['business_city'] ) . '" class="wpseo_local_city_input" /></p>';
			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_business_state">' . esc_html__( 'Business state', 'yoast-local-seo' ) . '</label>';
			echo '<input type="text" name="_wpseo_business_state" id="wpseo_business_state" value="' . esc_attr( $this->location_meta['business_state'] ) . '" class="wpseo_local_state_input" /></p>';
			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_business_zipcode">' . esc_html__( 'Business zipcode', 'yoast-local-seo' ) . '</label>';
			echo '<input type="text" name="_wpseo_business_zipcode" id="wpseo_business_zipcode" value="' . esc_attr( $this->location_meta['business_zipcode'] ) . '" class="wpseo_local_zipcode_input" /></p>';
			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_business_country">' . esc_html__( 'Business country', 'yoast-local-seo' ) . '</label>';
			echo '<select class="select select2-select" name="_wpseo_business_country" id="wpseo_business_country" style="width: 400px;" data-placeholder="' . esc_attr__( 'Choose your country', 'yoast-local-seo' ) . '">';
			echo '<option></option>';
			$countries = WPSEO_Local_Frontend::get_country_array();
			foreach ( $countries as $key => $val ) {
				echo '<option value="' . $key . '"' . ( ( $this->location_meta['business_country'] == $key ) ? ' selected="selected"' : '' ) . '>' . $countries[ $key ] . '</option>';
			}
			echo '</select></p>';
			echo '<div id="location-coordinates-settings">';
			if ( $api_key !== '' && empty( $this->location_meta['coords']['lat'] ) && empty( $this->location_meta['coords']['long'] )  ) {
				WPSEO_Local_Admin::display_notification( esc_html__( 'You\'ve set a Google Maps API Key. By using the button below, you can automatically calculate the coordinates that match the entered business address', 'yoast-local-seo' ), 'info' );
			}
			if ( $api_key === '' ) {
				echo '<p>';
				printf(
				/* translators: 1: HTML <a> open tag; 2: <a> close tag; 3: HTML <a> open tag; 4: <a> close tag. */
					esc_html__( 'To determine the exact location of your business, search engined need to know its latitude and longitude coordinates. You can %1$smanually enter%2$s these coordinates below. If you\'ve entered a %3$sGoogle Maps API Key%4$s the coordinates will automatically be calculated.', 'yoast-local-seo' ),
					'<a href="https://support.google.com/maps/answer/18539?co=GENIE.Platform%3DDesktop&hl=en" target="_blank">',
					'</a>',
					'<a href="' . esc_url( admin_url( 'admin.php?page=wpseo_local#top#api_keys' ) ) . '" data-action="link-to-tab" data-tab-id="api_keys">',
					'</a>'
				);
				echo '</p>';
			}
			echo '<div id="location-coordinates-settings-lat-lng-wrapper">';
			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_coordinates_lat">' . esc_html__( 'Latitude', 'yoast-local-seo' ) . '</label>';
			echo '<input type="text" name="_wpseo_coordinates_lat" id="wpseo_coordinates_lat" value="' . esc_attr( $this->location_meta['coords']['lat'] ) . '" class="wpseo_local_lat_input" /></p>';
			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_coordinates_long">' . esc_html__( 'Longitude', 'yoast-local-seo' ) . '</label>';
			echo '<input type="text" name="_wpseo_coordinates_long" id="wpseo_coordinates_long" value="' . esc_attr( $this->location_meta['coords']['long'] ) . '" class="wpseo_local_lng_input" /></p>';
			if ( ! empty( $api_key ) ) {
				echo '<button class="button calculate_lat_lng_button" id="calculate_lat_lng_button" type="button">' . esc_html__( 'Calculate coordinates', 'yoast-local-seo' ) . '</button>';
			}
			echo '</div>';
			echo '</div>';
			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_business_phone">' . esc_html__( 'Business phone', 'yoast-local-seo' ) . '</label>';
			echo '<input type="text" name="_wpseo_business_phone" id="wpseo_business_phone" value="' . esc_attr( $this->location_meta['business_phone'] ) . '" /></p>';
			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_business_phone_2nd">' . esc_html__( '2nd Business phone', 'yoast-local-seo' ) . '</label>';
			echo '<input type="text" name="_wpseo_business_phone_2nd" id="wpseo_business_phone_2nd" value="' . esc_attr( $this->location_meta['business_phone_2nd'] ) . '" /></p>';
			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_business_fax">' . esc_html__( 'Business fax', 'yoast-local-seo' ) . '</label>';
			echo '<input type="text" name="_wpseo_business_fax" id="wpseo_business_fax" value="' . esc_attr( $this->location_meta['business_fax'] ) . '" /></p>';
			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_business_email">' . esc_html__( 'Business email', 'yoast-local-seo' ) . '</label>';
			echo '<input type="text" name="_wpseo_business_email" id="wpseo_business_email" value="' . esc_attr( $this->location_meta['business_email'] ) . '" /></p>';
			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_business_url">' . esc_html__( 'URL', 'yoast-local-seo' ) . '</label>';
			echo '<input type="text" name="_wpseo_business_url" id="wpseo_business_url" value="' . esc_url( $this->location_meta['business_url'] ) . '" /></p>';
			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_business_vat_id">' . esc_html__( 'VAT ID', 'yoast-local-seo' ) . '</label>';
			echo '<input type="text" name="_wpseo_business_vat_id" id="wpseo_business_vat_id" value="' . esc_attr( $this->location_meta['business_vat'] ) . '" /></p>';
			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_business_tax_id">' . esc_html__( 'Tax ID', 'yoast-local-seo' ) . '</label>';
			echo '<input type="text" name="_wpseo_business_tax_id" id="wpseo_business_tax_id" value="' . esc_attr( $this->location_meta['business_tax'] ) . '" /></p>';
			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_business_coc_id">' . esc_html__( 'Chamber of Commerce ID', 'yoast-local-seo' ) . '</label>';
			echo '<input type="text" name="_wpseo_business_coc_id" id="wpseo_business_coc_id" value="' . esc_attr( $this->location_meta['business_coc'] ) . '" /></p>';

			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_business_price_range">' . esc_html__( 'Price indication', 'yoast-local-seo' ) . $price_indication_help->get_button_html() . ' </label>';
			echo '<select class="select2-select" name="_wpseo_business_price_range" id="wpseo_business_price_range" style="width: 400px;" data-placeholder="' . esc_attr__( 'Select your price indication', 'yoast-local-seo' ) . '">';
			echo '<option></option>';
			$pricerange = $this->get_pricerange_array();
			foreach ( $pricerange as $key => $val ) {
				echo '<option value="' . $key . '"' . ( ( $this->location_meta['business_price_range'] == $key ) ? ' selected="selected"' : '' ) . '>' . $pricerange[ $key ] . '</option>';
			}
			echo '</select></p>';
			echo $price_indication_help->get_panel_html();

			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_business_currencies_accepted">' . esc_html__( 'Currencies accepted', 'yoast-local-seo' ) . '</label>';
			echo '<input type="text" name="_wpseo_business_currencies_accepted" id="wpseo_business_currencies_accepted" value="' . esc_attr( $this->location_meta['business_currencies_accepted'] ) . '" /></p>';

			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_business_payment_accepted">' . esc_html__( 'Payment methods accepted', 'yoast-local-seo' ) . '</label>';
			echo '<input type="text" name="_wpseo_business_payment_accepted" id="wpseo_business_payment_accepted" value="' . esc_attr( $this->location_meta['business_payment_accepted'] ) . '" /></p>';

			echo '<p class="wpseo-local-input-wrap"><label class="textinput" for="wpseo_business_area_served">' . esc_html__( 'Area served', 'yoast-local-seo' ) . $area_served_help->get_button_html() . ' </label>';
			echo '<input type="text" name="_wpseo_business_area_served" id="wpseo_business_area_served" value="' . esc_attr( $this->location_meta['business_area_served'] ) . '" /></p>';
			echo $area_served_help->get_panel_html();

			$is_postal_address = get_post_meta( $this->location_id, '_wpseo_is_postal_address', true );
			$is_postal_address = wpseo_check_falses( $is_postal_address );
			echo '<p>' . $this->wpseo_local_checkbox( __( 'This address is a postal address (not a physical location)', 'yoast-local-seo' ), $is_postal_address, '_wpseo_is_postal_address', 'wpseo_is_postal_address' ) . '</p>';

			if ( ! empty( $api_key ) && ( '' !== $this->location_meta['coords']['lat'] && '' !== $this->location_meta['coords']['lat'] ) ) {

				echo '<p class="yoast-local-seo-field-desc">' . esc_html__( 'If the marker is not in the right location for your store, you can drag the pin to the location where you want it.', 'yoast-local-seo' ) . '</p>';

				$atts = array(
					'id'         => $this->location_id,
					'echo'       => true,
					'show_route' => false,
					'map_style'  => 'roadmap',
					'draggable'  => true,
				);
				wpseo_local_show_map( $atts );
			}

			echo '<br class="clear">';
			echo '<p class="wpseo-local-input-wrap">';
			echo '<label class="textinput" for="wpseo_business_location_logo">' . esc_html__( 'Location logo', 'yoast-local-seo' ) . '</label>';
			echo '<input class="textinput" id="wpseo_business_location_logo" type="text" size="36" name="_wpseo_business_location_logo" value="' . wp_get_attachment_image_url( $this->location_meta['business_logo'], 'full' ) . '">';
			echo '<input id="wpseo_business_location_logo_button" class="wpseo_image_upload_button button" type="button" value="' . esc_attr__( 'Upload image', 'yoast-local-seo' ) . '">';
			echo '<br class="clear">';
			echo esc_html__( 'This logo will override the logo set in the Yoast SEO Company Info tab', 'yoast-local-seo' );
			echo '</p>';
		}

		/**
		 * The content for the opening hours tab.
		 */
		public function opening_hours_panel_content() {
			/** @var WPSEO_Local_Core */
			global $wpseo_local_core;

			$multiple_opening_hours = get_post_meta( $this->location_id, '_wpseo_multiple_opening_hours', true );
			$open_247               = get_post_meta( $this->location_id, '_wpseo_open_247', true );
			$format_12h             = get_post_meta( $this->location_id, '_wpseo_format_12h', true );
			$format_24h             = get_post_meta( $this->location_id, '_wpseo_format_24h', true );

			$timezone_help = new WPSEO_Admin_Help_Panel(
				'timezone_help',
				__( 'Help with: Timezone', 'yoast-local-seo' ),
				esc_html__( 'The timezone is used to calculate the “Open now” functionality which can be shown together with your opening hours.', 'yoast-local-seo' ),
				'has-wrapper'
			);

			$hide_opening_hours = isset( $this->options['hide_opening_hours'] ) && $this->options['hide_opening_hours'] === 'on';

			echo '<div id="hide-opening-hours" class="wpseo_hide_opening_hours" style="display: ' . ( ( $hide_opening_hours ) ? 'none' : 'block' ) . ';">';

			echo '<div id="opening-hours-multiple">';

			// Change 'on' or 'off' to true or false.
			$format_12h        = wpseo_check_falses( $format_12h );
			$format_24h        = wpseo_check_falses( $format_24h );
			$opening_hours_24h = ( isset( $this->options['opening_hours_24h'] ) && $this->options['opening_hours_24h'] === 'on' ) ? false : true;

			if ( $opening_hours_24h ) {
				echo $this->wpseo_local_checkbox( __( 'Use 24h format', 'yoast-local-seo' ), $format_24h, '_wpseo_format_24h', 'wpseo_format_24h' );
			}
			else {
				echo $this->wpseo_local_checkbox( __( 'Use 12h format', 'yoast-local-seo' ), $format_12h, '_wpseo_format_12h', 'wpseo_format_12h' );
			}

			echo $this->wpseo_local_checkbox( __( 'Open 24/7', 'yoast-local-seo' ), $open_247, '_wpseo_open_247', 'wpseo_open_247' );

			echo '</div>';

			echo '<div class="opening-hours-wrap" style="display:' . ( ( 'on' === $open_247 ) ? 'none' : 'block' ) . '">';

			echo $this->wpseo_local_checkbox( __( 'I have two sets of opening hours per day', 'yoast-local-seo' ), $multiple_opening_hours, '_wpseo_multiple_opening_hours', 'wpseo_multiple_opening_hours' );


			echo '<p class="opening-hours-second-description" style="display:' . ( ( $multiple_opening_hours !== 'on' ) ? 'none' : 'block' ) . ';">';
			printf(
			/* translators: 1: <strong> open tag; 2: <strong> close tag. */
				esc_html__( 'If a specific day only has one set of opening hours, please set the second set for that day to %1$sclosed%2$s', 'yoast-local-seo' ),
				'<strong>',
				'</strong>'
			);
			echo '</p>';
			echo '<br class="clear">';

			foreach ( $wpseo_local_core->days as $key => $day ) {

				$field_name = '_wpseo_opening_hours_' . $key;
				$value_from = get_post_meta( $this->location_id, $field_name . '_from', true );
				if ( ! $value_from ) {
					$value_from = '09:00';
				}
				$value_to = get_post_meta( $this->location_id, $field_name . '_to', true );
				if ( ! $value_to ) {
					$value_to = '17:00';
				}
				$value_second_from = get_post_meta( $this->location_id, $field_name . '_second_from', true );
				if ( ! $value_second_from ) {
					$value_second_from = '09:00';
				}
				$value_second_to = get_post_meta( $this->location_id, $field_name . '_second_to', true );
				if ( ! $value_second_to ) {
					$value_second_to = '17:00';
				}
				$value_24h = get_post_meta( $this->location_id, $field_name . '_24h', true );
				if ( ! $value_24h ) {
					$value_24h = false;
				}

				$format_24h_option = wpseo_check_falses( empty( $this->options['opening_hours_24h'] ) ? false : $this->options['opening_hours_24h'] );
				$format_12h        = wpseo_check_falses( get_post_meta( $this->location_id, '_wpseo_format_12h', true ) );
				$format_24h        = wpseo_check_falses( get_post_meta( $this->location_id, '_wpseo_format_24h', true ) );

				// If options is set to 24 hours and the location is not set to 12 hours, return true.
				if ( ( $format_24h_option && ! $format_12h ) || ( ! $format_24h_option && $format_24h ) ) {
					$use_24_hours = true;
				}
				else {
					$use_24_hours = false;
				}

				echo '<div class="opening-hours">';
				echo '<legend class="textinput">' . esc_html( $day ) . '</legend>';
				echo '<div class="openinghours-wrapper">';
				echo '<select class="openinghours_from" style="width: 100px;" id="' . $field_name . '_from" name="' . $field_name . '_from" ' . ( ( 'on' === $value_24h ) ? 'disabled="disabled"' : '' ) . '>';
				echo wpseo_show_hour_options( $use_24_hours, $value_from );
				echo '</select>';
				echo '<span> - </span>';
				echo '<select class="openinghours_to" style="width: 100px;" id="' . $field_name . '_to" name="' . $field_name . '_to" ' . ( ( 'on' === $value_24h ) ? 'disabled="disabled"' : '' ) . '>';
				echo wpseo_show_hour_options( $use_24_hours, $value_to );
				echo '</select>';
				echo '<div class="opening-hours-second ' . ( ( '' === $multiple_opening_hours ) ? 'hidden' : '' ) . '" ' . ( ( 'on' === $value_24h ) ? 'disabled="disabled"' : '' ) . '>';
				echo '<div id="' . $field_name . '_second">';
				echo '<select class="openinghours_from_second" style="width: 100px;" id="' . $field_name . '_second_from" name="' . $field_name . '_second_from" ' . ( ( 'on' === $value_24h ) ? 'disabled="disabled"' : '' ) . '>';
				echo wpseo_show_hour_options( $use_24_hours, $value_second_from );
				echo '</select>';
				echo '<span> - </span>';
				echo '<select class="openinghours_to_second" style="width: 100px;" id="' . $field_name . '_second_to" name="' . $field_name . '_second_to" ' . ( ( 'on' === $value_24h ) ? 'disabled="disabled"' : '' ) . '>';
				echo wpseo_show_hour_options( $use_24_hours, $value_second_to );
				echo '</select>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
				echo $this->wpseo_local_checkbox( __( 'Open 24 hours', 'yoast-local-seo' ), $value_24h, $field_name . '_24h', 'wpseo_open_24h' );

				//echo '<label class="wpseo_open_24h" for="' . $field_name . '_24h"><input type="checkbox" name="' . $field_name . '_24h" id="' . $field_name . '_24h" ' . checked( $value_24h, 'on', false ) . ' /> ' . __( 'Open 24h', 'yoast-local-seo' ) . '</label>';
				echo '</div>';
			}
			echo '<p class="wpseo-local-input-wrap"><label for="wpseo_map_location_id" class="textinput">' . esc_html__( 'Timezone', 'yoast-local-seo' ) . $timezone_help->get_button_html() . ' </label>';
			echo '<select name="_wpseo_business_timezone" id="wpseo_business_timezone">';
			echo '<option></option>';
			foreach ( WPSEO_Local_Timezone_Repository::get_timezones() as $value => $name ) {
				echo '<option value="' . $value . '" ' . selected( $value, $this->location_meta['business_timezone'], false ) . '>' . $name . '</option>';
			}
			echo '</select></p>';
			echo $timezone_help->get_panel_html();
			echo '</div><!-- .opening-hours-wrap -->';

			echo '</div><!-- #hide-opening-hours -->';
		}

		/**
		 * The content for the map settings tab.
		 */
		public function maps_settings_panel_content() {
			/**
			 * @var $wpseo_local_core \WPSEO_Local_Core;
			 */
			global $wpseo_local_core;
			$api_key = $this->api_repository->get_api_key( 'browser' );

			if ( ( empty( $api_key ) ) ) {
				$base_url = get_site_url();

				echo '<p>';
				printf(
				/* translators: 1: HTML <a> open tag; 2: <a> close tag. */
					esc_html__( 'In order to use the Maps settings, you should set an API key. You can add an API key in the %1$sAPI settings tab%2$s, which allows you to change the Maps settings here.', 'yoast-local-seo' ),
					'<a href="' . esc_url( $base_url ) . '/wp-admin/admin.php?page=wpseo_local#top#api_keys" target="_blank">',
					'</a>'
				);
				echo '</p>';
			}

			if ( ! empty( $api_key ) ) {
				$custom_marker = get_post_meta( $this->location_id, '_wpseo_business_location_custom_marker', true );

				echo '<div class="wpseo-local-custom_marker-wrapper">';
				echo '<label class="textinput" for="wpseo_business_location_custom_marker">' . esc_html__( 'Custom marker', 'yoast-local-seo' ) . '</label>';
				echo '<div class="wpseo-local-custom_marker-wrapper__content">';
				echo '<img src="' . esc_url( $custom_marker ) . '" id="custom_marker_image_container">';
				echo '<br class="wpseo-local-hide-button' . ( ( empty( $custom_marker ) ) ? ' hidden' : '' ) . '">';
				echo '<button type="button" class="set_custom_images button" data-id="custom_marker">' . esc_html__( 'Set custom marker image', 'yoast-local-seo' ) . '</button>';
				echo '<br>';
				echo '<a href="javascript:;" id="remove_marker" class="remove_custom_image wpseo-local-hide-button' . ( ( empty( $custom_marker ) ) ? ' hidden' : '' ) . '" data-id="custom_marker">' . esc_html__( 'Remove marker', 'yoast-local-seo' ) . '</a>';
				echo '<input type="hidden" id="hidden_custom_marker" name="_wpseo_business_location_custom_marker" value="' . ( ( ! empty( $custom_marker ) ) ? esc_url( $custom_marker ) : '' ) . '">';
				echo '</div> <!-- .wpseo-local-custom_marker-wrapper -->';
				if ( empty( $custom_marker ) ) {
					echo '<p class="yoast-local-seo-field-desc">' . esc_html__( 'The custom marker should be 100x100 px. If the image exceeds those dimensions it could (partially) cover the info popup.', 'yoast-local-seo' ) . '</p>';
				}
				/* translators: %s extends to Yoast Local SEO brand name */
				echo '<p class="yoast-local-seo-field-desc">' . sprintf( esc_html__( 'This custom marker will override the custom markers for the location category and general %s settings', 'yoast-local-seo' ), 'Yoast Local SEO' ) . '</p>';
				echo '</div>';
				if ( ! empty( $custom_marker ) ) {
					$wpseo_local_core->check_custom_marker_size( $custom_marker );
				}
			}
		}

		/**
		 * Builds the metabox for editing screen of the wpseo_locations Custom Post Type
		 */
		public function metabox_locations() {
			if ( empty( $this->tabs ) ) {
				return;
			}

			echo '<div id="wpseo-local-metabox">';
			echo '<div class="wpseo-local-metabox-content">';

			// Adding a tabbed UI to match the options pages even more.
			$this->tab_navigation();
			$this->tabs_panels();

			// Noncename needed to verify where the data originated.
			echo '<input type="hidden" name="locationsmeta_noncename" id="locationsmeta_noncename" value="' . esc_attr( wp_create_nonce( plugin_basename( __FILE__ ) ) ) . '" />';

			echo '</div>';
			echo '</div><!-- .wpseo-metabox-content -->';
		}

		/**
		 * Handles and saves the data entered in the wpseo_locations metabox.
		 *
		 * @param int    $post_id The post ID of which the data must be saved.
		 * @param object $post    The post object of which the data must be saved.
		 *
		 * @return bool|void
		 */
		public function wpseo_locations_save_meta( $post_id, $post ) {
			// Bail if this is a multisite installation and the site has been switched.
			if ( is_multisite() && ms_is_switched() ) {
				return;
			}

			// First check if post type is wpseo_locations.
			if ( $post->post_type === 'wpseo_locations' ) {

				global $wpseo_local_core;

				// Verify this came from the our screen and with proper authorization,
				// because save_post can be triggered at other times.
				if ( false === isset( $_POST['locationsmeta_noncename'] ) || ( isset( $_POST['locationsmeta_noncename'] ) && ! wp_verify_nonce( $_POST['locationsmeta_noncename'], plugin_basename( __FILE__ ) ) ) ) {
					return $post_id;
				}

				// Is the user allowed to edit the post or page?
				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return $post_id;
				}

				$custom_marker = '';
				if ( ! empty( $_POST['_wpseo_business_location_custom_marker'] ) ) {
					if ( is_numeric( $_POST['_wpseo_business_location_custom_marker'] ) ) {
						$custom_marker = wp_get_attachment_image_url( $_POST['_wpseo_business_location_custom_marker'], 'full' );
					}

					if ( ! is_numeric( $_POST['_wpseo_business_location_custom_marker'] ) ) {
						$custom_marker = esc_url_raw( $_POST['_wpseo_business_location_custom_marker'] );
					}
				}

				// OK, we're authenticated: we need to find and save the data
				// We'll put it into an array to make it easier to loop though.
				$locations_meta['_wpseo_business_type']                   = isset( $_POST['_wpseo_business_type'] ) ? sanitize_text_field( $_POST['_wpseo_business_type'] ) : 'LocalBusiness';
				$locations_meta['_wpseo_business_address']                = isset( $_POST['_wpseo_business_address'] ) ? sanitize_text_field( $_POST['_wpseo_business_address'] ) : '';
				$locations_meta['_wpseo_business_address_2']              = isset( $_POST['_wpseo_business_address_2'] ) ? sanitize_text_field( $_POST['_wpseo_business_address_2'] ) : '';
				$locations_meta['_wpseo_business_city']                   = isset( $_POST['_wpseo_business_city'] ) ? sanitize_text_field( $_POST['_wpseo_business_city'] ) : '';
				$locations_meta['_wpseo_business_state']                  = isset( $_POST['_wpseo_business_state'] ) ? sanitize_text_field( $_POST['_wpseo_business_state'] ) : '';
				$locations_meta['_wpseo_business_zipcode']                = isset( $_POST['_wpseo_business_zipcode'] ) ? sanitize_text_field( $_POST['_wpseo_business_zipcode'] ) : '';
				$locations_meta['_wpseo_business_country']                = isset( $_POST['_wpseo_business_country'] ) ? sanitize_text_field( $_POST['_wpseo_business_country'] ) : '';
				$locations_meta['_wpseo_business_phone']                  = isset( $_POST['_wpseo_business_phone'] ) ? sanitize_text_field( $_POST['_wpseo_business_phone'] ) : '';
				$locations_meta['_wpseo_business_phone_2nd']              = isset( $_POST['_wpseo_business_phone_2nd'] ) ? sanitize_text_field( $_POST['_wpseo_business_phone_2nd'] ) : '';
				$locations_meta['_wpseo_business_fax']                    = isset( $_POST['_wpseo_business_fax'] ) ? sanitize_text_field( $_POST['_wpseo_business_fax'] ) : '';
				$locations_meta['_wpseo_business_email']                  = isset( $_POST['_wpseo_business_email'] ) ? sanitize_email( $_POST['_wpseo_business_email'] ) : '';
				$locations_meta['_wpseo_business_location_logo']          = isset( $_POST['_wpseo_business_location_logo'] ) ? sanitize_text_field( $_POST['_wpseo_business_location_logo'] ) : '';
				$locations_meta['_wpseo_business_location_custom_marker'] = $custom_marker;
				$locations_meta['_wpseo_business_vat_id']                 = isset( $_POST['_wpseo_business_vat_id'] ) ? sanitize_text_field( $_POST['_wpseo_business_vat_id'] ) : '';
				$locations_meta['_wpseo_business_tax_id']                 = isset( $_POST['_wpseo_business_tax_id'] ) ? sanitize_text_field( $_POST['_wpseo_business_tax_id'] ) : '';
				$locations_meta['_wpseo_business_coc_id']                 = isset( $_POST['_wpseo_business_coc_id'] ) ? sanitize_text_field( $_POST['_wpseo_business_coc_id'] ) : '';
				$locations_meta['_wpseo_business_price_range']            = isset( $_POST['_wpseo_business_price_range'] ) ? sanitize_text_field( $_POST['_wpseo_business_price_range'] ) : '';
				$locations_meta['_wpseo_business_currencies_accepted']    = isset( $_POST['_wpseo_business_currencies_accepted'] ) ? sanitize_text_field( $_POST['_wpseo_business_currencies_accepted'] ) : '';
				$locations_meta['_wpseo_business_payment_accepted']       = isset( $_POST['_wpseo_business_payment_accepted'] ) ? sanitize_text_field( $_POST['_wpseo_business_payment_accepted'] ) : '';
				$locations_meta['_wpseo_business_area_served']            = isset( $_POST['_wpseo_business_area_served'] ) ? sanitize_text_field( $_POST['_wpseo_business_area_served'] ) : '';
				$locations_meta['_wpseo_is_postal_address']               = isset( $_POST['_wpseo_is_postal_address'] ) ? sanitize_text_field( $_POST['_wpseo_is_postal_address'] ) : '';
				$locations_meta['_wpseo_multiple_opening_hours']          = isset( $_POST['_wpseo_multiple_opening_hours'] ) ? $_POST['_wpseo_multiple_opening_hours'] : '';
				$locations_meta['_wpseo_open_247']                        = isset( $_POST['_wpseo_open_247'] ) ? $_POST['_wpseo_open_247'] : '';
				$locations_meta['_wpseo_format_12h']                      = isset( $_POST['_wpseo_format_12h'] ) ? $_POST['_wpseo_format_12h'] : 'off';
				$locations_meta['_wpseo_format_24h']                      = isset( $_POST['_wpseo_format_24h'] ) ? $_POST['_wpseo_format_24h'] : 'off';
				$locations_meta['_wpseo_coordinates_lat']                 = isset( $_POST['_wpseo_coordinates_lat'] ) ? $_POST['_wpseo_coordinates_lat'] : '';
				$locations_meta['_wpseo_coordinates_long']                = isset( $_POST['_wpseo_coordinates_long'] ) ? $_POST['_wpseo_coordinates_long'] : '';
				$locations_meta['_wpseo_business_timezone']               = isset( $_POST['_wpseo_business_timezone'] ) ? $_POST['_wpseo_business_timezone'] : '';
				foreach ( $wpseo_local_core->days as $key => $day ) {
					$field_name                                     = '_wpseo_opening_hours_' . $key;
					$locations_meta[ $field_name . '_from' ]        = ( isset( $_POST[ $field_name . '_from' ] ) ) ? sanitize_text_field( $_POST[ $field_name . '_from' ] ) : '';
					$locations_meta[ $field_name . '_to' ]          = ( isset( $_POST[ $field_name . '_to' ] ) ) ? sanitize_text_field( $_POST[ $field_name . '_to' ] ) : '';
					$locations_meta[ $field_name . '_second_from' ] = ( isset( $_POST[ $field_name . '_second_from' ] ) ) ? sanitize_text_field( $_POST[ $field_name . '_second_from' ] ) : '';
					$locations_meta[ $field_name . '_second_to' ]   = ( isset( $_POST[ $field_name . '_second_to' ] ) ) ? sanitize_text_field( $_POST[ $field_name . '_second_to' ] ) : '';
					$locations_meta[ $field_name . '_24h' ]         = ( isset( $_POST[ $field_name . '_24h' ] ) ) ? sanitize_text_field( $_POST[ $field_name . '_24h' ] ) : '';

					if ( $locations_meta[ $field_name . '_from' ] === 'closed' ) {
						$locations_meta[ $field_name . '_to' ] = $locations_meta[ $field_name . '_from' ];
					}
					if ( $locations_meta[ $field_name . '_second_from' ] == 'closed' ) {
						$locations_meta[ $field_name . '_second_to' ] = $locations_meta[ $field_name . '_second_from' ];
					}
				}

				$locations_meta['_wpseo_business_url'] = ( isset( $_POST['_wpseo_business_url'] ) && '' !== $_POST['_wpseo_business_url'] ) ? sanitize_text_field( $_POST['_wpseo_business_url'] ) : get_permalink( $post_id );

				// Put http:// in front of the URL, if it's not there yet.
				if ( ! preg_match( '~^(?:f|ht)tps?://~i', $locations_meta['_wpseo_business_url'] ) ) {
					$locations_meta['_wpseo_business_url'] = 'http://' . $locations_meta['_wpseo_business_url'];
				}

				// Add values of $locations_meta as custom fields.
				foreach ( $locations_meta as $key => $value ) {
					// Cycle through the $locations_meta array.
					if ( $post->post_type === 'revision' ) {
						// Don't store custom data twice.
						return $post_id;
					}
					if ( ! empty( $value ) ) {
						update_post_meta( $post_id, $key, $value );
					}
					else {
						// Delete if blank.
						delete_post_meta( $post_id, $key );
					}
				}

				// Re-ping the new sitemap.
				WPSEO_Local_Core::update_sitemap();
			}

			return true;
		}

		/**
		 * Register actions for adding media buttons in the editor.
		 */
		public function register_media_buttons() {
			$current_screen = get_current_screen();
			if ( $current_screen->base === 'post' ) {
				add_action( 'media_buttons', array( &$this, 'add_media_buttons' ), 20 );
				add_action( 'admin_footer', array( &$this, 'add_mce_popup' ) );
			}
		}

		/**
		 * Retrieves array of the 5 pricerange steps.
		 *
		 * @return array Array of pricerange.
		 */
		private function get_pricerange_array() {
			$pricerange = array(
				''      => __( 'Select your price indication', 'yoast-local-seo' ),
				'$'     => '$',
				'$$'    => '$$',
				'$$$'   => '$$$',
				'$$$$'  => '$$$$',
				'$$$$$' => '$$$$$',
			);

			return $pricerange;
		}

		/**
		 * Makes the green toggle switch inside op the CPT UI.
		 *
		 * @param string $label   Is the translatable string in front of the check.
		 * @param bool   $checked Checking if the checkbox is checked and returns true or false.
		 * @param string $name    This is the ID which is saved by the database.
		 * @param string $class   The classname which can be used to change css or js.
		 *
		 * @return string
		 */
		public function wpseo_local_checkbox( $label, $checked, $name, $class ) {

			if ( ! empty( $label ) ) {
				$wpseo_local_checkbox = '<p class="' . $class . '">';
				$wpseo_local_checkbox .= '<input type="checkbox" class="' . $class . '" id="' . $name . '" name="' . $name . '" value="on" ' . checked( true, wpseo_check_falses( $checked ), false ) . '>';
				$wpseo_local_checkbox .= '<label for="' . $name . '">' . $label . '</label>';
				$wpseo_local_checkbox .= '</p>';
			}

			return $wpseo_local_checkbox;
		}

		/**
		 * Add buttons to editor to add the shortcodes via a ppup UI.
		 */
		public function add_media_buttons() {
			// Make sure to don't output white space between these buttons.
			echo '<a href="#TB_inline?width=480&height=600&inlineId=wpseo_add_map" class="thickbox button" id="wpseo_add_map_button"><span class="wpseo_media_icon wpseo_icon_map"></span> ' . esc_html__( 'Map', 'yoast-local-seo' ) . '</a>';

			echo '<a href="#TB_inline?width=480&inlineId=wpseo_add_address" class="thickbox button" id="wpseo_add_address_button"><span class="wpseo_media_icon wpseo_icon_address"></span> ' . esc_html__( 'Address', 'yoast-local-seo' ) . '</a>';

			echo '<a href="#TB_inline?width=480&inlineId=wpseo_add_opening_hours" class="thickbox button" id="wpseo_add_opening_hours_button"><span class="wpseo_media_icon wpseo_icon_opening_hours"></span> ' . esc_html__( 'Opening hours', 'yoast-local-seo' ) . '</a>';

			if ( wpseo_has_multiple_locations() ) {
				echo '<a href="#TB_inline?width=480&height=510&inlineId=wpseo_add_storelocator" class="thickbox button" id="wpseo_add_storelocator_button"><span class="wpseo_media_icon wpseo_icon_storelocator"></span> ' . esc_html__( 'Store locator', 'yoast-local-seo' ) . '</a>';
			}
		}

		/**
		 * Creates the popup HTML for adding the shortcodes.
		 */
		public function add_mce_popup() {
			// An exception for Beaver Builder.
			if ( ! isset( $_GET['fl_builder'] ) ) {
				$screen = get_current_screen();

				if ( 'edit' !== $screen->parent_base || 'edit-tags' === $screen->base ) {
					return;
				}
			}

			?>
			<?php // @codingStandardsIgnoreStart ?>
			<script>
				function WPSEO_InsertMap() {
					var wrapper = jQuery( "#wpseo_add_map" );
					var location_id = jQuery( "#wpseo_map_location_id" ).val();
					var term_id = jQuery( "#wpseo_map_term_id" ).val();
					var center_id = jQuery( "#wpseo_map_center_location_id" ).val();
					var max_number = (
						! jQuery( "#wpseo_map_max_number" ).prop( "disabled" )
					) ? " max_number=\"" + jQuery( "#wpseo_map_max_number" ).val() + "\"" : "";

					<?php if ( wpseo_has_multiple_locations() ) { ?>
					if ( location_id == "" ) {
						alert( "<?php _e( 'Please select a location', 'yoast-local-seo' ); ?>" );
						return;
					}
					<?php } ?>

					var map_style = jQuery( "input[name=wpseo_map_style]:checked", ".wpseo_map_style" ).val();
					var width = jQuery( "#wpseo_map_width" ).val();
					var height = jQuery( "#wpseo_map_height" ).val();
					var zoom = jQuery( "#wpseo_map_zoom" ).val();
					var scrollable = jQuery( "#wpseo_map_scrollable" ).is( ":checked" )
						? " scrollable=\"1\""
						: " scrollable=\"0\"";
					var draggable = jQuery( "#wpseo_map_draggable" ).is( ":checked" )
						? " draggable=\"1\""
						: " draggable=\"0\"";
					var marker_clustering = jQuery( "#wpseo_map_marker_clustering" ).is( ":checked" )
						? " marker_clustering=\"1\""
						: " marker_clustering=\"0\"";
					var show_route = jQuery( "#wpseo_map_show_route" ).is( ":checked" )
						? " show_route=\"1\""
						: " show_route=\"0\"";
					var show_state = jQuery( "#wpseo_map_show_state" ).is( ":checked" )
						? " show_state=\"1\""
						: " show_state=\"0\"";
					var show_country = jQuery( "#wpseo_map_show_country" ).is( ":checked" )
						? " show_country=\"1\""
						: "";
					var show_url = jQuery( "#wpseo_map_show_url" ).is( ":checked" ) ? " show_url=\"1\"" : "";
					var show_email = jQuery( "#wpseo_map_show_email" ).is( ":checked" ) ? " show_email=\"1\"" : "";
					var show_category_filter = jQuery( "#wpseo_map_show_category_filter" ).is( ":checked" )
						? " show_category_filter=\"1\""
						: "";

					var show_phone = jQuery( "#wpseo_map_show_phone" ).is( ":checked" )
						? " show_phone=\"1\""
						: " show_phone=\"0\"";
					var show_phone_2 = jQuery( "#wpseo_map_show_phone_2nd" ).is( ":checked" )
						? " show_phone_2=\"1\""
						: " show_phone_2=\"0\"";
					var show_fax = jQuery( "#wpseo_map_show_fax" ).is( ":checked" )
						? " show_fax=\"1\""
						: " show_fax=\"0\"";

					var id = "";
					if ( location_id != "undefined" && typeof location_id != "undefined" ) {
						id = "id=\"" + location_id + "\" ";
					}

					var term = "";
					if ( term_id != "undefined" && typeof term_id != "undefined" && term_id != "" ) {
						term = "term_id=\"" + term_id + "\" ";
					}

					var center = "";
					if ( center_id != "undefined" && typeof center_id != "undefined" && center_id != "" ) {
						center = "center=\"" + center_id + "\" ";
					}

					var default_show_infowindow = "";
					if ( location_id != "all" && jQuery( "#wpseo_show_popup_default" ).is( ":checked" ) ) {
						default_show_infowindow = " default_show_infowindow=\"1\"";
					}

					if ( location_id != "all" ) {
						marker_clustering = "";
					}

					window.send_to_editor( "[wpseo_map " + id + term + center + max_number + " width=\"" + width + "\" height=\"" + height + "\" zoom=\"" + zoom + "\" map_style=\"" + map_style + "\"" + scrollable + draggable + marker_clustering + show_route + show_state + show_country + show_url + show_email + show_category_filter + default_show_infowindow + show_phone + show_phone_2 + show_fax + "]" );
				}

				function WPSEO_InsertAddress() {
					var location_id = jQuery( "#wpseo_address_location_id" ).val();
					var term_id = jQuery( "#wpseo_address_term_id" ).val();

					<?php if ( wpseo_has_multiple_locations() ) { ?>
					if ( location_id == "" ) {
						alert( "<?php _e( 'Please select a location', 'yoast-local-seo' ); ?>" );
						return;
					}
					<?php } ?>
					var hide_name = jQuery( "#wpseo_hide_name" ).is( ":checked" ) ? " hide_name=\"1\"" : "";
					var hide_address = jQuery( "#wpseo_hide_address" ).is( ":checked" ) ? " hide_address=\"1\"" : "";
					var oneline = jQuery( "#wpseo_oneline" ).is( ":checked" ) ? " oneline=\"1\"" : "";
					var show_state = jQuery( "#wpseo_show_state" ).is( ":checked" )
						? " show_state=\"1\""
						: " show_state=\"0\"";
					var show_country = jQuery( "#wpseo_show_country" ).is( ":checked" )
						? " show_country=\"1\""
						: " show_country=\"0\"";
					var show_phone = jQuery( "#wpseo_show_phone" ).is( ":checked" )
						? " show_phone=\"1\""
						: " show_phone=\"0\"";
					var show_phone_2 = jQuery( "#wpseo_show_phone_2nd" ).is( ":checked" )
						? " show_phone_2=\"1\""
						: " show_phone_2=\"0\"";
					var show_fax = jQuery( "#wpseo_show_fax" ).is( ":checked" ) ? " show_fax=\"1\"" : " show_fax=\"0\"";
					var show_email = jQuery( "#wpseo_show_email" ).is( ":checked" )
						? " show_email=\"1\""
						: " show_email=\"0\"";
					var show_url = jQuery( "#wpseo_show_url" ).is( ":checked" ) ? " show_url=\"1\"" : "";
					var show_logo = jQuery( "#wpseo_show_logo" ).is( ":checked" )
						? " show_logo=\"1\""
						: " show_logo=\"0\"";
					var show_vat = jQuery( "#wpseo_show_vat_id" ).is( ":checked" ) ? " show_vat=\"1\"" : "";
					var show_tax = jQuery( "#wpseo_show_tax_id" ).is( ":checked" ) ? " show_tax=\"1\"" : "";
					var show_coc = jQuery( "#wpseo_show_coc_id" ).is( ":checked" ) ? " show_coc=\"1\"" : "";
					var show_price_range = jQuery( "#wpseo_show_price_range" ).is( ":checked" )
						? " show_price_range=\"1\""
						: "";
					var show_opening_hours = jQuery( "#wpseo_show_opening_hours" ).is( ":checked" )
						? " show_opening_hours=\"1\""
						: "";
					var hide_closed = jQuery( "#wpseo_hide_closed" ).is( ":checked" ) ? " hide_closed=\"1\"" : "";
					var comment_string = jQuery( "#wpseo_comment" ).val();
					var orderby = "";
					var order = "";
					var max_number = "";

					var id = "";
					if ( location_id != "undefined" && typeof location_id != "undefined" ) {
						id = "id=\"" + location_id + "\" ";
					}

					var term = "";
					if ( term_id != "undefined" && typeof term_id != "undefined" && term_id != "" ) {
						term = "term_id=\"" + term_id + "\" ";
					}

					var shortcode_name = "wpseo_address";
					if ( location_id == "all" ) {
						shortcode_name = "wpseo_all_locations";

						max_number = " max_number=\"" + jQuery( "#wpseo_address_max_number" ).val() + "\"";
						orderby = " orderby=" + jQuery( "#wpseo_address_all_locations_orderby" ).val();
						order = " order=" + jQuery( "#wpseo_address_all_locations_order" ).val();
					}

					var comment = "";
					if ( comment_string != "" ) {
						comment = " comment=\"" + comment_string + "\"";
					}

					window.send_to_editor( "[" + shortcode_name + " " + id + term + max_number + hide_name + hide_address + oneline + show_state + show_country + show_phone + show_phone_2 + show_fax + show_email + show_url + show_vat + show_tax + show_coc + show_price_range + show_logo + show_opening_hours + hide_closed + comment + orderby + order + "]" );
				}

				function WPSEO_InsertOpeningHours() {
					var wrapper = jQuery( "#wpseo_add_opening_hours" );

					var location_id = jQuery( "#wpseo_oh_location_id" ).val();
					if ( location_id == "" ) {
						alert( "<?php _e( 'Please select a location', 'yoast-local-seo' ); ?>" );
						return;
					}

					var id = "";
					if ( location_id != "undefined" && typeof location_id != "undefined" ) {
						id = "id=\"" + location_id + "\" ";
					}
					var show_days = new Array();

					if ( jQuery( "#wpseo_oh_show_sunday" ).is( ":checked" ) ) {
						show_days.push( jQuery( "#wpseo_oh_show_sunday" ).val() );
					}
					if ( jQuery( "#wpseo_oh_show_monday" ).is( ":checked" ) ) {
						show_days.push( jQuery( "#wpseo_oh_show_monday" ).val() );
					}
					if ( jQuery( "#wpseo_oh_show_tuesday" ).is( ":checked" ) ) {
						show_days.push( jQuery( "#wpseo_oh_show_tuesday" ).val() );
					}
					if ( jQuery( "#wpseo_oh_show_wednesday" ).is( ":checked" ) ) {
						show_days.push( jQuery( "#wpseo_oh_show_wednesday" ).val() );
					}
					if ( jQuery( "#wpseo_oh_show_thursday" ).is( ":checked" ) ) {
						show_days.push( jQuery( "#wpseo_oh_show_thursday" ).val() );
					}
					if ( jQuery( "#wpseo_oh_show_friday" ).is( ":checked" ) ) {
						show_days.push( jQuery( "#wpseo_oh_show_friday" ).val() );
					}
					if ( jQuery( "#wpseo_oh_show_saturday" ).is( ":checked" ) ) {
						show_days.push( jQuery( "#wpseo_oh_show_saturday" ).val() );
					}

					var comment_string = jQuery( "#wpseo_oh_comment" ).val();

					var hide_closed = jQuery( "#wpseo_oh_hide_closed" ).is( ":checked" ) ? " hide_closed=\"1\"" : "";
					var show_open_label = jQuery( "#wpseo_oh_show_open_label" ).is( ":checked" )
						? " show_open_label=\"1\""
						: "";

					var comment = "";
					if ( comment_string != "" ) {
						comment = " comment=\"" + comment_string + "\"";
					}

					window.send_to_editor( "[wpseo_opening_hours " + id + hide_closed + show_open_label + " show_days=\"" + show_days + "\"" + comment + "]" );
				}
				<?php if ( wpseo_has_multiple_locations() ) { ?>
				function WPSEO_InsertStorelocator() {

					var width = jQuery( "#wpseo_sl_map_width" ).val();
					var height = jQuery( "#wpseo_sl_map_height" ).val();
					var zoom = jQuery( "#wpseo_sl_map_zoom" ).val();

					var show_map = jQuery( "#wpseo_sl_show_map" ).is( ":checked" )
						? " show_map=\"1\""
						: " show_map=\"0\"";
					var scrollable = jQuery( "#wpseo_sl_scrollable" ).is( ":checked" )
						? " scrollable=\"1\""
						: " scrollable=\"0\"";
					var draggable = jQuery( "#wpseo_sl_draggable" ).is( ":checked" )
						? " draggable=\"1\""
						: " draggable=\"0\"";
					var marker_clustering = jQuery( "#wpseo_sl_marker_clustering" ).is( ":checked" )
						? " marker_clustering=\"1\""
						: " marker_clustering=\"0\"";
					var show_radius = jQuery( "#wpseo_sl_show_radius" ).is( ":checked" ) ? " show_radius=\"1\"" : "";
					var show_nearest_suggestion = jQuery( "#wpseo_sl_show_nearest_suggestion" ).is( ":checked" )
						? " show_nearest_suggestion=\"1\""
						: " show_nearest_suggestion=\"0\"";
					var show_filter = jQuery( "#wpseo_sl_show_filter" ).is( ":checked" ) ? " show_filter=\"1\"" : "";
					var max_number = " max_number=\"" + jQuery( "#wpseo_sl_max_number" ).val() + "\"";
					var radius = " radius=\"" + jQuery( "#wpseo_sl_radius" ).val() + "\"";

					var map_style = jQuery( "input[name=wpseo_sl_map_style]:checked", ".wpseo_map_style" ).val();
					var oneline = jQuery( "#wpseo_sl_oneline" ).is( ":checked" ) ? " oneline=\"1\"" : "";
					var show_state = jQuery( "#wpseo_sl_show_state" ).is( ":checked" ) ? " show_state=\"1\"" : "";
					var show_country = jQuery( "#wpseo_sl_show_country" ).is( ":checked" ) ? " show_country=\"1\"" : "";
					var show_phone = jQuery( "#wpseo_sl_show_phone" ).is( ":checked" ) ? " show_phone=\"1\"" : "";
					var show_phone_2 = jQuery( "#wpseo_sl_show_phone_2nd" ).is( ":checked" )
						? " show_phone_2=\"1\""
						: "";
					var show_fax = jQuery( "#wpseo_sl_show_fax" ).is( ":checked" ) ? " show_fax=\"1\"" : "";
					var show_email = jQuery( "#wpseo_sl_show_email" ).is( ":checked" ) ? " show_email=\"1\"" : "";
					var show_url = jQuery( "#wpseo_sl_show_url" ).is( ":checked" ) ? " show_url=\"1\"" : "";
					var show_opening_hours = jQuery( "#wpseo_sl_show_opening_hours" ).is( ":checked" )
						? " show_opening_hours=\"1\""
						: "";
					var hide_closed = jQuery( "#wpseo_sl_hide_closed" ).is( ":checked" ) ? " hide_closed=\"1\"" : "";
					var show_category_filter = jQuery( "#wpseo_sl_show_category_filter" ).is( ":checked" )
						? " show_category_filter=\"1\""
						: "";

					window.send_to_editor( "[wpseo_storelocator " + show_map + scrollable + draggable + marker_clustering + show_radius + show_nearest_suggestion + radius + max_number + show_filter + " width=\"" + width + "\" height=\"" + height + "\" zoom=\"" + zoom + "\" map_style=\"" + map_style + "\"" + oneline + show_state + show_country + show_phone + show_phone_2 + show_fax + show_email + show_url + show_opening_hours + hide_closed + show_category_filter + "]" );
				}

				function WPSEO_Address_Change_Order( obj ) {
					if ( jQuery( obj ).val() == "all" ) {
						jQuery( "#wpseo_address_all_locations_order_wrapper" ).slideDown();
						jQuery( "#wpseo_address_term_id" ).removeAttr( "disabled" );
					} else {
						jQuery( "#wpseo_address_all_locations_order_wrapper" ).slideUp();
						jQuery( "#wpseo_address_term_id" ).val( "" );
						jQuery( "#wpseo_address_term_id" ).attr( "disabled", true );
					}
				}

				function WPSEO_Address_Change_Term_Order( obj ) {
					if ( jQuery( obj ).val() != "all" && jQuery( obj ).val() != "" ) {
						jQuery( "#wpseo_address_location_id" ).val( "all" );
					}
				}

				function WPSEO_Map_Change_Location( obj ) {
					if ( jQuery( obj ).val() != "all" ) {
						jQuery( "#wpseo_map_max_number" ).attr( "disabled", true );
						jQuery( "#wpseo_map_term_id" ).val( "" );
						jQuery( "#wpseo_map_center_location_id" ).val( "" );
						jQuery( "#wpseo_map_term_id" ).attr( "disabled", true );
						jQuery( "#wpseo_map_center_location_id" ).attr( "disabled", true );
						jQuery( "#wpseo_show_popup_default" ).removeAttr( "disabled" );
						jQuery( "#wpseo_map_marker_clustering" ).attr( "disabled", true );
						jQuery( "#wpseo_map_show_route" ).removeAttr( "disabled" );
					} else {
						jQuery( "#wpseo_map_term_id" ).removeAttr( "disabled" );
						jQuery( "#wpseo_map_center_location_id" ).removeAttr( "disabled" );
						jQuery( "#wpseo_show_popup_default" ).attr( "disabled", true );
						jQuery( "#wpseo_map_show_route" ).attr( "disabled", true );
						jQuery( "#wpseo_map_marker_clustering" ).removeAttr( "disabled" );
						jQuery( "#wpseo_map_max_number" ).removeAttr( "disabled" );
					}
				}

				function WPSEO_Map_Change_Term( obj ) {
					if ( jQuery( obj ).val() != "all" && jQuery( obj ).val() != "" ) {
						jQuery( "#wpseo_map_location_id" ).val( "all" );
					}
				}
				<?php } ?>
			</script>
			<div id="wpseo_add_map" style="display:none;">
				<div class="wrap">
					<div>
						<style>
							.wpseo-textfield {
								width: 60px;
								border: 1px solid #dfdfdf;
								-webkit-border-radius: 3px;
								border-radius: 3px;
							}

							.wpseo-select {
								width: 100px;
								margin: 0;
							}

							.wpseo-for-textfield {
								display: inline-block;
								width: 100px;
							}
						</style>
						<div style="padding:15px 15px 0 15px;">
							<h2><?php esc_html_e( 'Insert Google Map', 'yoast-local-seo' ); ?></h2>
						</div>

						<?php if ( wpseo_has_multiple_locations() && ! empty( $this->locations ) ) { ?>
							<div style="padding:15px 15px 0 15px;">
								<label for="wpseo_map_location_id"
									   class="screen-reader-text"><?php esc_html_e( 'Location:', 'yoast-local-seo' ); ?></label>
								<select id="wpseo_map_location_id" onchange="WPSEO_Map_Change_Location( this )">
									<?php echo $this->get_location_select_options(); ?>
								</select>
								<label for="wpseo_map_term_id"
									   class="screen-reader-text"><?php esc_html_e( 'Category:', 'yoast-local-seo' ); ?></label>
								<select id="wpseo_map_term_id"
										onchange="WPSEO_Map_Change_Term( this )" <?php echo( in_array( get_the_ID(), $this->locations ) ? 'disabled' : '' ); ?>>
									<option value=""> -- <?php esc_html_e( 'Select a category', 'yoast-local-seo' ); ?>
										--
									</option>
									<?php
									$categories = get_terms(
										'wpseo_locations_category',
										array( 'hide_empty' => false )
									);

									foreach ( $categories as $category ) {
										?>
										<option
											value="<?php echo esc_attr( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></option>
										<?php
									}
									?>
								</select> <br><br>
								<label
									for="wpseo_map_max_number"><?php esc_html_e( 'Maximum number of results to show', 'yoast-local-seo' ); ?>
									<input type="text" id="wpseo_map_max_number" value="200" /></label><br>
								<label
									for="wpseo_map_center_location_id"><?php esc_html_e( 'Center map on this location:', 'yoast-local-seo' ); ?></label><br>
								<select
									id="wpseo_map_center_location_id" <?php echo( in_array( get_the_ID(), $this->locations ) ? 'disabled' : '' ); ?>>
									<?php echo $this->get_location_select_options(); ?>
								</select>
							</div>
						<?php } ?>
						<?php if ( ( wpseo_has_multiple_locations() && ! empty( $this->locations ) ) || ! wpseo_has_multiple_locations() ) { ?>
							<div style="padding:15px 15px 0 15px;">
								<h2><?php esc_html_e( 'Map style', 'yoast-local-seo' ); ?></h2>
								<ul>
									<?php
									$map_styles = array(
										'ROADMAP'   => __( 'Roadmap', 'yoast-local-seo' ),
										'HYBRID'    => __( 'Hybrid', 'yoast-local-seo' ),
										'SATELLITE' => __( 'Satellite', 'yoast-local-seo' ),
										'TERRAIN'   => __( 'Terrain', 'yoast-local-seo' ),
									);

									foreach ( $map_styles as $key => $label ) {
										?>
										<li class="wpseo_map_style"
											style="display: inline-block; width: 120px; height: 150px; margin-right: 10px;text-align: center;">
											<label for="wpseo_map_style-<?php echo strtolower( $key ); ?>">
												<img
													src="<?php echo esc_url( plugins_url( '/images/map-' . strtolower( $key ) . '.png', dirname( __FILE__ ) ) ); ?>"
													alt=""><br>
												<?php echo esc_html( $label ); ?><br>
												<input type="radio" name="wpseo_map_style"
													   id="wpseo_map_style-<?php echo strtolower( $key ); ?>"
													   value="<?php echo esc_attr( strtolower( $key ) ); ?>" <?php checked( 'ROADMAP', $key ); ?>>
											</label>
										</li>
										<?php
									}
									?>
								</ul>
							</div>

							<div style="padding:15px 15px 0 15px;">
								<label class="wpseo-for-textfield"
									   for="wpseo_map_width"><?php esc_html_e( 'Width:', 'yoast-local-seo' ); ?></label>
								<input type="text" id="wpseo_map_width" class="wpseo-textfield" value="400"><br>
								<label class="wpseo-for-textfield"
									   for="wpseo_map_height"><?php esc_html_e( 'Height:', 'yoast-local-seo' ); ?></label>
								<input type="text" id="wpseo_map_height" class="wpseo-textfield" value="300"><br>
								<label class="wpseo-for-textfield"
									   for="wpseo_map_zoom"><?php esc_html_e( 'Zoom level:', 'yoast-local-seo' ); ?></label>
								<select id="wpseo_map_zoom" class="wpseo-select" value="300">
									<option value="-1"><?php esc_html_e( 'Auto', 'yoast-local-seo' ); ?></option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
									<option value="8">8</option>
									<option value="9">9</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="14">14</option>
									<option value="15">15</option>
									<option value="16">16</option>
									<option value="17">17</option>
									<option value="18">18</option>
									<option value="19">19</option>
								</select><br>
								<br>
								<input type="checkbox" id="wpseo_map_scrollable" checked="checked" />
								<label
									for="wpseo_map_scrollable"><?php esc_html_e( 'Allow zoom by scroll', 'yoast-local-seo' ); ?></label><br>
								<input type="checkbox" id="wpseo_map_draggable" checked="checked" />
								<label
									for="wpseo_map_draggable"><?php esc_html_e( 'Allow dragging of the map', 'yoast-local-seo' ); ?></label><br>
								<input type="checkbox" id="wpseo_map_show_phone" checked="checked" />
								<label
									for="wpseo_map_show_phone"><?php esc_html_e( 'Show phone number in info-popup', 'yoast-local-seo' ); ?></label><br>
								<input type="checkbox" id="wpseo_map_show_phone_2nd" checked="checked" />
								<label
									for="wpseo_map_show_phone_2nd"><?php esc_html_e( 'Show 2nd phone number in info-popup', 'yoast-local-seo' ); ?></label><br>
								<input type="checkbox" id="wpseo_map_show_fax" checked="checked" />
								<label
									for="wpseo_map_show_fax"><?php esc_html_e( 'Show fax number in info-popup', 'yoast-local-seo' ); ?></label><br>
								<?php if ( wpseo_has_multiple_locations() ) { ?>
									<input type="checkbox" id="wpseo_map_marker_clustering" />
									<label
										for="wpseo_map_marker_clustering"><?php esc_html_e( 'Marker clustering', 'yoast-local-seo' ); ?></label>
									<br>
								<?php } ?>
								<input type="checkbox" id="wpseo_map_show_route" />
								<label
									for="wpseo_map_show_route"><?php esc_html_e( 'Show route planner', 'yoast-local-seo' ); ?></label><br>
								<input type="checkbox" id="wpseo_show_popup_default" />
								<label
									for="wpseo_show_popup_default"><?php esc_html_e( 'Show info-popup by default', 'yoast-local-seo' ); ?></label><br>
								<input type="checkbox" id="wpseo_map_show_state" />
								<label
									for="wpseo_map_show_state"><?php esc_html_e( 'Show state in info-popup', 'yoast-local-seo' ); ?></label><br>
								<input type="checkbox" id="wpseo_map_show_country" />
								<label
									for="wpseo_map_show_country"><?php esc_html_e( 'Show country in info-popup', 'yoast-local-seo' ); ?></label><br>
								<input type="checkbox" id="wpseo_map_show_url" />
								<label
									for="wpseo_map_show_url"><?php esc_html_e( 'Show URL in info-popup', 'yoast-local-seo' ); ?></label><br>
								<input type="checkbox" id="wpseo_map_show_email" />
								<label
									for="wpseo_map_show_email"><?php esc_html_e( 'Show email in info popup', 'yoast-local-seo' ); ?></label><br>
								<?php if ( wpseo_has_multiple_locations() ) { ?>
									<input type="checkbox" id="wpseo_map_show_category_filter" />
									<label
										for="wpseo_map_show_category_filter"><?php esc_html_e( 'Show a filter for location categories under the map', 'yoast-local-seo' ); ?></label>
									<br>
								<?php } ?>
							</div>
							<div style="padding:15px;">
								<input type="button" class="button button-primary"
									   value="<?php esc_html_e( 'Insert map', 'yoast-local-seo' ); ?>"
									   onclick="WPSEO_InsertMap();" />&nbsp;&nbsp;&nbsp;
								<a class="button" href="#"
								   onclick="tb_remove(); return false;"><?php esc_html_e( 'Cancel', 'yoast-local-seo' ); ?></a>
							</div>
						<?php } ?>

						<?php
						if ( wpseo_has_multiple_locations() && empty( $this->locations ) ) {
							echo '<p>';
							printf(
							/* translators: 1: link open tag; 2: link close tag. */
								esc_html__( 'In order to use this shortcode function, please %1$sadd one or more locations%2$s first.', 'yoast-local-seo' ),
								'<a href="' . esc_url( admin_url( 'edit.php?post_type=wpseo_locations' ) ) . '">',
								'</a>'
							);
							echo '</p>';
						}
						?>
					</div>
				</div>
			</div>
			<div id="wpseo_add_address" style="display:none;">
				<div class="wrap">
					<div>
						<div style="padding:15px 15px 0 15px;">
							<h2><?php esc_html_e( 'Insert Address', 'yoast-local-seo' ); ?></h2>
						</div>

						<?php if ( wpseo_has_multiple_locations() && ! empty( $this->locations ) ) { ?>
							<div style="padding:15px 15px 0 15px;">
								<label for="wpseo_address_location_id"
									   class="screen-reader-text"><?php esc_html_e( 'Location:', 'yoast-local-seo' ); ?></label>
								<select id="wpseo_address_location_id" onchange="WPSEO_Address_Change_Order( this );">
									<?php echo $this->get_location_select_options(); ?>
								</select>
								<?php
								$categories = get_terms(
									'wpseo_locations_category',
									array( 'hide_empty' => false )
								);
								if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
									?>
									<label for="wpseo_address_term_id"
										   class="screen-reader-text"><?php esc_html_e( 'Category:', 'yoast-local-seo' ); ?></label>
									<select id="wpseo_address_term_id"
											onchange="WPSEO_Address_Change_Term_Order( this );">
										<option value="">
											-- <?php esc_html_e( 'Select a category', 'yoast-local-seo' ); ?> --
										</option>
										<?php
										foreach ( $categories as $category ) {
											?>
											<option
												value="<?php echo esc_attr( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></option>
											<?php
										}
										?>
									</select>
								<?php } ?>

								<br />

								<div id="wpseo_address_all_locations_order_wrapper" style="display: none;">
									<label
										for="wpseo_address_max_number"><?php esc_html_e( 'Maximum number of results to show', 'yoast-local-seo' ); ?>
										<input type="text" id="wpseo_address_max_number" value="200" /></label><br>
									<label
										for="wpseo_address_all_locations_orderby"><?php esc_html_e( 'Order by:', 'yoast-local-seo' ); ?></label>
									<select name="wpseo_address_all_locations_orderby"
											id="wpseo_address_all_locations_orderby">
										<option
											value="title"><?php esc_html_e( 'Alphabetical', 'yoast-local-seo' ); ?></option>
										<option
											value="date"><?php esc_html_e( 'By publish date', 'yoast-local-seo' ); ?></option>
									</select><br>

									<label
										for="wpseo_address_all_locations_order"><?php esc_html_e( 'Order:', 'yoast-local-seo' ); ?></label>
									<select name="wpseo_address_all_locations_order"
											id="wpseo_address_all_locations_order">
										<option
											value="ASC"><?php esc_html_e( 'Ascending', 'yoast-local-seo' ); ?></option>
										<option
											value="DESC"><?php esc_html_e( 'Descending', 'yoast-local-seo' ); ?></option>
									</select>
								</div>
							</div>
						<?php } ?>
						<?php if ( ( wpseo_has_multiple_locations() && ! empty( $this->locations ) ) || ! wpseo_has_multiple_locations() ) { ?>
							<div style="padding:15px 15px 0 15px;">
								<label for="wpseo_hide_name"><input type="checkbox"
																	id="wpseo_hide_name" /> <?php esc_html_e( 'Hide business name', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_hide_address"><input type="checkbox"
																	   id="wpseo_hide_address" /> <?php esc_html_e( 'Hide business address', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_oneline"><input type="checkbox"
																  id="wpseo_oneline" /> <?php esc_html_e( 'Show address on one line', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_show_state"><input type="checkbox" id="wpseo_show_state"
																	 checked /> <?php esc_html_e( 'Show state', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_show_country"><input type="checkbox" id="wpseo_show_country"
																	   checked /> <?php esc_html_e( 'Show country', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_show_phone"><input type="checkbox" id="wpseo_show_phone"
																	 checked /> <?php esc_html_e( 'Show phone number', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_show_phone_2nd"><input type="checkbox" id="wpseo_show_phone_2nd"
																		 checked /> <?php esc_html_e( 'Show 2nd phone number', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_show_fax"><input type="checkbox" id="wpseo_show_fax"
																   checked /> <?php esc_html_e( 'Show fax number', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_show_email"><input type="checkbox" id="wpseo_show_email"
																	 checked /> <?php esc_html_e( 'Show email', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_show_url"><input type="checkbox"
																   id="wpseo_show_url" /> <?php esc_html_e( 'Show URL', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_show_logo"><input type="checkbox"
																	id="wpseo_show_logo" /> <?php esc_html_e( 'Show logo', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_show_vat_id"><input type="checkbox"
																	  id="wpseo_show_vat_id" /> <?php esc_html_e( 'Show VAT ID', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_show_tax_id"><input type="checkbox"
																	  id="wpseo_show_tax_id" /> <?php esc_html_e( 'Show Tax ID', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_show_coc_id"><input type="checkbox"
																	  id="wpseo_show_coc_id" /> <?php esc_html_e( 'Show Chamber of Commerce ID', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_show_price_range"><input type="checkbox"
																		   id="wpseo_show_price_range" /> <?php esc_html_e( 'Show price indication', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_show_opening_hours"><input type="checkbox"
																			 id="wpseo_show_opening_hours" /> <?php esc_html_e( 'Show opening hours', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_hide_closed"><input type="checkbox"
																	  id="wpseo_hide_closed" /> <?php esc_html_e( 'Hide closed days', 'yoast-local-seo' ); ?>
								</label><br>
							</div>
							<div style="padding:15px 15px 0 15px;">
								<label
									for="wpseo_comment"><?php esc_html_e( 'Extra comment', 'yoast-local-seo' ); ?></label><br>
								<textarea id="wpseo_comment" rows="5" cols="50"></textarea>
							</div>
							<div style="padding:15px;">
								<input type="button" class="button button-primary"
									   value="<?php esc_attr_e( 'Insert address', 'yoast-local-seo' ); ?>"
									   onclick="WPSEO_InsertAddress();" />&nbsp;&nbsp;&nbsp;
								<a class="button" href="javascript:"
								   onclick="tb_remove(); return false;"><?php esc_html_e( 'Cancel', 'yoast-local-seo' ); ?></a>
							</div>
						<?php } ?>

						<?php
						if ( wpseo_has_multiple_locations() && empty( $this->locations ) ) {
							echo '<p>';
							printf(
							/* translators: 1: link open tag; 2: link close tag. */
								esc_html__( 'In order to use this shortcode function, please %1$sadd one or more locations%2$s first.', 'yoast-local-seo' ),
								'<a href="' . esc_url( admin_url( 'edit.php?post_type=wpseo_locations' ) ) . '">',
								'</a>'
							);
							echo '</p>';
						}
						?>
					</div>
				</div>
			</div>
			<div id="wpseo_add_opening_hours" style="display:none;">
				<div class="wrap">
					<div>
						<div style="padding:15px 15px 0 15px;">
							<h2><?php esc_html_e( 'Insert Opening Hours', 'yoast-local-seo' ); ?></h2>
						</div>

						<?php if ( wpseo_has_multiple_locations() && ! empty( $this->locations ) ) { ?>
							<div style="padding:15px 15px 0 15px;">
								<label for="wpseo_oh_location_id"
									   class="screen-reader-text"><?php esc_html_e( 'Location:', 'yoast-local-seo' ); ?></label>
								<select id="wpseo_oh_location_id">
									<?php echo $this->get_location_select_options(); ?>
								</select> <br />

							</div>
						<?php } ?>
						<?php if ( ( wpseo_has_multiple_locations() && ! empty( $this->locations ) ) || ! wpseo_has_multiple_locations() ) { ?>
							<div style="padding:15px 15px 0 15px;">
								<h2><?php esc_html_e( 'Show Days', 'yoast-local-seo' ); ?></h2>
								<?php
								$days = array(
									'sunday'    => __( 'Sunday', 'yoast-local-seo' ),
									'monday'    => __( 'Monday', 'yoast-local-seo' ),
									'tuesday'   => __( 'Tuesday', 'yoast-local-seo' ),
									'wednesday' => __( 'Wednesday', 'yoast-local-seo' ),
									'thursday'  => __( 'Thursday', 'yoast-local-seo' ),
									'friday'    => __( 'Friday', 'yoast-local-seo' ),
									'saturday'  => __( 'Saturday', 'yoast-local-seo' ),
								);
								foreach ( $days as $key => $day ) {
									/* translators: %s extends to weekdays */
									echo '<label for="wpseo_oh_show_' . $key . '"><input type="checkbox" id="wpseo_oh_show_' . $key . '" value="' . $key . '" checked />' . sprintf( esc_html__( 'Show %s', 'yoast-local-seo' ), $day ) . '</label><br>';
								}
								?>
							</div>
							<div style="padding:15px 15px 0 15px;">
								<label for="wpseo_oh_show_open_label"><input type="checkbox"
																			 id="wpseo_oh_show_open_label" /> <?php esc_html_e( 'Show open now label after opening hour for current day', 'yoast-local-seo' ); ?>
								</label>
							</div>
							<div style="padding:15px 15px 0 15px;">
								<label for="wpseo_oh_hide_closed"><input type="checkbox"
																		 id="wpseo_oh_hide_closed" /> <?php esc_html_e( 'Hide closed days', 'yoast-local-seo' ); ?>
								</label>
							</div>
							<div style="padding:15px 15px 0 15px;">
								<label
									for="wpseo_oh_comment"><?php esc_html_e( 'Extra comment', 'yoast-local-seo' ); ?></label><br>
								<textarea id="wpseo_oh_comment" rows="5" cols="50"></textarea>
							</div>
							<div style="padding:15px;">
								<input type="button" class="button button-primary"
									   value="<?php esc_attr_e( 'Insert opening hours', 'yoast-local-seo' ); ?>"
									   onclick="WPSEO_InsertOpeningHours();" />&nbsp;&nbsp;&nbsp;
								<a class="button" href="javascript:"
								   onclick="tb_remove(); return false;"><?php esc_html_e( 'Cancel', 'yoast-local-seo' ); ?></a>
							</div>
						<?php } ?>

						<?php
						if ( wpseo_has_multiple_locations() && empty( $this->locations ) ) {
							echo '<p>';
							printf(
							/* translators: 1: link open tag; 2: link close tag. */
								esc_html__( 'In order to use this shortcode function, please %1$sadd one or more locations%2$s first.', 'yoast-local-seo' ),
								'<a href="' . esc_url( admin_url( 'edit.php?post_type=wpseo_locations' ) ) . '">',
								'</a>'
							);
							echo '</p>';
						}
						?>
					</div>
				</div>
			</div>

			<?php if ( wpseo_has_multiple_locations() ) { ?>
				<div id="wpseo_add_storelocator" style="display:none;">
					<div class="wrap">
						<div>
							<div style="padding:15px 15px 0 15px;">
								<h2><?php esc_html_e( 'Insert Store locator', 'yoast-local-seo' ); ?></h2>
							</div>

							<div style="padding:15px 15px 0 15px;">
								<label for="wpseo_sl_show_map"><input type="checkbox" id="wpseo_sl_show_map"
																	  checked="checked" /> <?php esc_html_e( 'Show Map with the search results', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_sl_scrollable"><input type="checkbox" id="wpseo_sl_scrollable"
																		checked="checked" /> <?php esc_html_e( 'Allow zoom by scroll', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_sl_draggable"><input type="checkbox" id="wpseo_sl_draggable"
																	   checked="checked" /> <?php esc_html_e( 'Allow dragging of the map', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_sl_marker_clustering"><input type="checkbox"
																			   id="wpseo_sl_marker_clustering" /> <?php esc_html_e( 'Marker clustering', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_sl_show_filter"><input type="checkbox"
																		 id="wpseo_sl_show_filter" /> <?php esc_html_e( 'Show filter to narrow down search results by category', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_sl_show_radius"><input type="checkbox"
																		 id="wpseo_sl_show_radius" /> <?php esc_html_e( 'Show radius to limit your search', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_sl_show_nearest_suggestion"><input type="checkbox"
																					 id="wpseo_sl_show_nearest_suggestion"
																					 checked="checked" /> <?php esc_html_e( 'Show the nearest location, if none are found within radius', 'yoast-local-seo' ); ?>
								</label><br>
								<br>
								<label
									for="wpseo_sl_max_number"><?php esc_html_e( 'Maximum number of results to show', 'yoast-local-seo' ); ?>
									<input type="text" id="wpseo_sl_max_number" value="200" /></label><br>
								<?php /* translators: %s extends to the chosen unit system: km or mi */ ?>
								<label for="wpseo_sl_radius"><?php printf( esc_html__( 'Search radius (in %s)', 'yoast-local-seo' ), ( empty( $this->options['unit_system'] ) || $this->options['unit_system'] === 'METRIC' ) ? 'km' : 'mi' ); ?>
									<input type="text" id="wpseo_sl_radius" value="10" /></label><br>
							</div>
							<div style="padding:0 15px 0 15px;">
								<h2><?php esc_html_e( 'Map style', 'yoast-local-seo' ); ?></h2>
								<p><?php esc_html_e( 'Please specify below how the search results should look like.', 'yoast-local-seo' ); ?></p>
								<ul>
									<?php
									$map_styles = array(
										'ROADMAP'   => __( 'Roadmap', 'yoast-local-seo' ),
										'HYBRID'    => __( 'Hybrid', 'yoast-local-seo' ),
										'SATELLITE' => __( 'Satellite', 'yoast-local-seo' ),
										'TERRAIN'   => __( 'Terrain', 'yoast-local-seo' ),
									);

									foreach ( $map_styles as $key => $label ) {
										?>
										<li class="wpseo_map_style"
											style="display: inline-block; width: 120px; height: 150px; margin-right: 10px;text-align: center;">
											<label for="wpseo_sl_map_style-<?php echo strtolower( $key ); ?>">
												<img
													src="<?php echo esc_url( plugins_url( '/images/map-' . strtolower( $key ) . '.png', dirname( __FILE__ ) ) ); ?>"
													alt=""><br>
												<?php echo esc_html( $label ); ?><br>
												<input type="radio" name="wpseo_sl_map_style"
													   id="wpseo_sl_map_style-<?php echo strtolower( $key ); ?>"
													   value="<?php echo esc_attr( strtolower( $key ) ); ?>" <?php checked( 'ROADMAP', $key ); ?>>
											</label>
										</li>
										<?php
									}
									?>
								</ul>
								<label class="wpseo-for-textfield"
									   for="wpseo_sl_map_width"><?php esc_html_e( 'Width:', 'yoast-local-seo' ); ?></label>
								<input type="text" id="wpseo_sl_map_width" class="wpseo-textfield" value="100%"><br>
								<label class="wpseo-for-textfield"
									   for="wpseo_sl_map_height"><?php esc_html_e( 'Height:', 'yoast-local-seo' ); ?></label>
								<input type="text" id="wpseo_sl_map_height" class="wpseo-textfield" value="300"><br>
								<label class="wpseo-for-textfield"
									   for="wpseo_sl_map_zoom"><?php esc_html_e( 'Zoom level:', 'yoast-local-seo' ); ?></label>
								<select id="wpseo_sl_map_zoom" class="wpseo-select" value="300">
									<option value="-1"><?php esc_html_e( 'Auto', 'yoast-local-seo' ); ?></option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
									<option value="8">8</option>
									<option value="9">9</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="14">14</option>
									<option value="15">15</option>
									<option value="16">16</option>
									<option value="17">17</option>
									<option value="18">18</option>
									<option value="19">19</option>
								</select><br>
								<br>
								<label for="wpseo_sl_show_state"><input type="checkbox"
																		id="wpseo_sl_show_state" /> <?php esc_html_e( 'Show state', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_sl_show_country"><input type="checkbox"
																		  id="wpseo_sl_show_country" /> <?php esc_html_e( 'Show country', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_sl_show_phone"><input type="checkbox"
																		id="wpseo_sl_show_phone" /> <?php esc_html_e( 'Show phone number', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_sl_show_phone_2nd"><input type="checkbox"
																			id="wpseo_sl_show_phone_2nd" /> <?php esc_html_e( 'Show 2nd phone number', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_sl_show_fax"><input type="checkbox"
																	  id="wpseo_sl_show_fax" /> <?php esc_html_e( 'Show fax number', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_sl_show_email"><input type="checkbox"
																		id="wpseo_sl_show_email" /> <?php esc_html_e( 'Show email', 'yoast-local-seo' ); ?>
								</label><br>
								<label for="wpseo_sl_show_url"><input type="checkbox"
																	  id="wpseo_sl_show_url" /> <?php esc_html_e( 'Show URL', 'yoast-local-seo' ); ?>
								</label><br>
							</div>
							<div style="padding:15px;">
								<input type="button" class="button button-primary"
									   value="<?php esc_attr_e( 'Insert Store locator', 'yoast-local-seo' ); ?>"
									   onclick="WPSEO_InsertStorelocator();" />&nbsp;&nbsp;&nbsp;
								<a class="button" href="javascript:"
								   onclick="tb_remove(); return false;"><?php esc_html_e( 'Cancel', 'yoast-local-seo' ); ?></a>
							</div>
						</div>
					</div>
				</div>
				<?php // @codingStandardsIgnoreEnd ?>
			<?php } ?>

			<?php
		}

		/**
		 * Filter the Page Analysis results to make sure we're giving the correct hints.
		 *
		 * @param array  $results The results array to filter and update.
		 * @param array  $job     The current jobs variables.
		 * @param object $post    The post object for the current page.
		 *
		 * @return array $results
		 * @since 0.2
		 */
		public function filter_linkdex_results( $results, $job, $post ) {

			// @todo dit moet nog gaan werken voor single implementaties, first pass enzo.
			if ( $post->post_type !== 'wpseo_locations' ) {
				return $results;
			}

			$custom = get_post_custom();

			if ( strpos( $job['title'], $custom['_wpseo_business_city'][0] ) === false ) {
				$results['local-title'] = array(
					'val' => 4,
					'msg' => __( 'Your title does not contain your location\'s city, you should really add that.', 'yoast-local-seo' ),
				);
			}
			else {
				$results['local-title'] = array(
					'val' => 9,
					'msg' => __( 'Your title contains your location\'s city, well done!', 'yoast-local-seo' ),
				);
			}

			if ( stripos( $job['pageUrl'], $custom['_wpseo_business_city'][0] ) === false ) {
				$results['local-url'] = array(
					'val' => 4,
					'msg' => __( 'Your URL does not contain your location\'s city, you should really add that.', 'yoast-local-seo' ),
				);
			}
			else {
				$results['local-url'] = array(
					'val' => 9,
					'msg' => __( 'Your URL contains your location\'s city, well done!', 'yoast-local-seo' ),
				);
			}

			return $results;
		}

		/**
		 * Enqueues the pluginstyles.
		 */
		public function enqueue_styles() {
			if ( 'wpseo_locations' === get_post_type() ) {
				$this->wpseo_asset_manager->enqueue_style( 'admin-css' );
				$this->wpseo_asset_manager->enqueue_style( 'select2' );
			}
		}

		/**
		 * Enqueues the pluginscripts.
		 */
		public function enqueue_scripts() {

			// Only do this on location pages.
			if ( 'wpseo_locations' === get_post_type() ) {
				$this->asset_manager->enqueue_script( 'seo-locations' );
				$this->wpseo_asset_manager->enqueue_script( 'admin-media' );
				$this->wpseo_asset_manager->enqueue_script( 'select2' );

				wp_localize_script( WPSEO_Local_Admin_Assets::PREFIX . 'seo-locations', 'wpseoLocalL10n', $this->localize_script_locations() );
				wp_localize_script( WPSEO_Admin_Asset_Manager::PREFIX . 'admin-media', 'wpseoMediaL10n', __return_empty_array() );
			}
			elseif ( in_array( get_post_type(), array( 'post', 'page' ), true ) ) {
				$this->asset_manager->enqueue_script( 'seo-pages' );

				wp_localize_script( WPSEO_Local_Admin_Assets::PREFIX . 'seo-pages', 'wpseoLocalL10n', $this->localize_script_pages() );
			}
		}

		/**
		 * Localizes scripts for the local plugin.
		 *
		 * @return array
		 */
		public function localize_script_locations() {
			$custom        = get_post_custom();
			$asset_manager = new WPSEO_Local_Admin_Assets();

			$current_screen    = get_current_screen();

			$address_schema    = __( 'Your content contains an address block  which is important for search engines to validate your business address. Well done!', 'yoast-local-seo' );
			$no_address_schema = __( 'Your content does not contain an address block which is important for search engines to validate your business address. You should really add that.', 'yoast-local-seo' );

			if ( ! $current_screen->is_block_editor ) {
				$address_schema    = __( 'Your content contains an address shortcode which is important for search engines to validate your business address. Well done!', 'yoast-local-seo' );
				$no_address_schema = __( 'Your content does not contain an address shortcode which is important for search engines to validate your business address. You should really add that.', 'yoast-local-seo' );
			}

			return array(
				'location'             => ( ! empty( $custom['_wpseo_business_city'][0] ) ) ? $custom['_wpseo_business_city'][0] : '',
				'locations_script_url' => plugins_url( 'js/dist/wp-seo-local-worker-locations-' . $asset_manager->flatten_version( WPSEO_LOCAL_VERSION ) . WPSEO_CSSJS_SUFFIX . '.js', WPSEO_LOCAL_FILE ),
				'title_no_location'    => __( 'Your title does not contain your location\'s city, you should really add that.', 'yoast-local-seo' ),
				'title_location'       => __( 'Your title contains your location\'s city, well done!', 'yoast-local-seo' ),
				'url_no_location'      => __( 'Your URL does not contain your location\'s city, you should really add that.', 'yoast-local-seo' ),
				'url_location'         => __( 'Your URL contains your location\'s city, well done!', 'yoast-local-seo' ),
				'heading_location'     => __( 'Your h1 and/or h2 headings contain your location\'s city, well done!', 'yoast-local-seo' ),
				'heading_no_location'  => __( 'Your h1 and/or h2 headings do not contain your location\'s city, you should really add that.', 'yoast-local-seo' ),
				'address_schema'       => $address_schema,
				'no_address_schema'    => $no_address_schema,
			);
		}

		/**
		 * Localizes scripts for the videoplugin.
		 *
		 * @return array
		 */
		public function localize_script_pages() {
			$asset_manager = new WPSEO_Local_Admin_Assets();

			return array(
				'pages_script_url'     => plugins_url( 'js/dist/wp-seo-local-worker-pages-' . $asset_manager->flatten_version( WPSEO_LOCAL_VERSION ) . WPSEO_CSSJS_SUFFIX . '.js', WPSEO_LOCAL_FILE ),
				'storelocator_content' => __( 'Your content contains a store locator shortcode, but not much more content. Please add content to make your page more useful for your visitors.', 'yoast-local-seo' ),
			);
		}
	}
}
