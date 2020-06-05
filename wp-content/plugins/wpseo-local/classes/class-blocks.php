<?php
/**
 * Yoast SEO: Local plugin file.
 *
 * @package WPSEO_Local\Main
 * @since   6.0
 */

if ( ! class_exists( 'WPSEO_Local_Blocks' ) ) {

	/**
	 * WPSEO_Local_Core class. Handles defining of Yoast Local SEO Gutenberg blocks.
	 */
	class WPSEO_Local_Blocks {

		/**
		 * JavaScript extension.
		 *
		 * @var string
		 */
		private $js_ext;

		/**
		 * CSS Extension.
		 *
		 * @var string
		 */
		private $css_ext;

		/**
		 * Stores the options for this plugin.
		 *
		 * @var array
		 */
		private $options;

		/**
		 * WPSEO_Local_Blocks constructor.
		 */
		public function __construct() {
			$this->init();
		}

		/**
		 * The init function for the WPSEO_Local_Blocks class.
		 */
		public function init() {
			$this->set_ext();
			$this->options = get_option( 'wpseo_local' );

			add_action( 'enqueue_block_editor_assets', array( $this, 'register_block_editor_assets' ) );

			add_action( 'wp_ajax_wpseo_local_show_address_ajax_cb', 'wpseo_local_show_address_ajax_cb', 10 );
			add_action( 'wp_ajax_nopriv_wpseo_local_show_address_ajax_cb', 'wpseo_local_show_address_ajax_cb', 10 );

			add_action( 'wp_ajax_wpseo_local_show_map_ajax_cb', 'wpseo_local_show_map_ajax_cb', 10 );
			add_action( 'wp_ajax_nopriv_wpseo_local_show_map_ajax_cb', 'wpseo_local_show_map_ajax_cb', 10 );

			add_action( 'wp_ajax_wpseo_local_show_opening_hours_ajax_cb', 'wpseo_local_show_opening_hours_ajax_cb', 10 );
			add_action( 'wp_ajax_nopriv_wpseo_local_show_opening_hours_ajax_cb', 'wpseo_local_show_opening_hours_ajax_cb', 10 );
		}

		/**
		 * Set the file extension for
		 */
		private function set_ext() {
			$this->css_ext = '.min.css';
			$this->js_ext  = '.min.js';

			// If debugging is on, don't use a minified file.
			if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
				$this->css_ext = '.css';
				$this->js_ext  = '.js';
			}
		}

		/**
		 * Register Block Editor Assets.
		 */
		public function register_block_editor_assets() {
			/**
			 * Filter: 'wpseo_enable_structured_data_blocks' - Allows disabling Yoast's schema blocks entirely.
			 *
			 * @api bool If false, our structured data blocks won't show.
			 */
			$enabled = apply_filters( 'wpseo_enable_structured_data_blocks', true );
			if ( ! $enabled ) {
				return;
			}

			// Scripts.
			wp_register_script(
				'yoast-local-seo-block-editor-js',
				plugins_url( 'js/dist/wp-seo-local-blocks-' . yoast_seo_local_flatten_version( WPSEO_LOCAL_VERSION ) . $this->js_ext, WPSEO_LOCAL_FILE ),
				array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
				WPSEO_LOCAL_VERSION,
				true
			);

			$localization_data = array(
				'ajax_url'             => admin_url( 'admin-ajax.php' ),
				'plugin_url'           => trailingslashit( plugins_url( '', WPSEO_LOCAL_FILE ) ),
				'hasMultipleLocations' => wpseo_has_multiple_locations(),
				'unitSystem'           => ( ( empty( $this->options['unit_system'] ) || $this->options['unit_system'] === 'METRIC' ) ? 'km' : 'mi' ),
			);
			wp_localize_script(
				'yoast-local-seo-block-editor-js',
				'yoastSeoLocal',
				$localization_data
			);
			wp_enqueue_script( 'yoast-local-seo-block-editor-js' );

			$wpseo_asset_manager = new WPSEO_Admin_Asset_Manager();
			$wpseo_asset_manager->register_assets();

			$wpseo_asset_manager->enqueue_script( 'api' );

			$yoast_seo_local_asset_manager = new WPSEO_Local_Admin_Assets();
			$yoast_seo_local_asset_manager->register_assets();

			$yoast_seo_local_asset_manager->enqueue_script( 'frontend' );

			/*
			// Styles.
			wp_register_style( 'yoast-local-seo-block-editor-css', plugins_url( 'editor.css', dirname( __FILE__ ) ), array( 'wp-edit-blocks' ), filemtime( plugin_dir_path( WPSEO_LOCAL_FILE ) . 'assets/_src/css/editor.css' ) );
			wp_enqueue_style( 'yoast-local-seo-block-editor-css' );
			*/
		}
	}
}
