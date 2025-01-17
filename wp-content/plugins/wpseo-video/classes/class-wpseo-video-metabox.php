<?php
/**
 * Yoast SEO Video plugin file.
 *
 * @package    Admin
 * @since      1.6.0
 * @version    1.6.0
 */

// Avoid direct calls to this file.
if ( ! class_exists( 'WPSEO_Video_Sitemap' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists( 'WPSEO_Video_Metabox' ) ) {

	/**
	 * This class adds the Video tab to the WP SEO metabox and makes sure the settings are saved.
	 */
	class WPSEO_Video_Metabox extends WPSEO_Metabox {

		/**
		 * Class constructor
		 */
		public function __construct() {
			add_action( 'wpseo_tab_translate', array( $this, 'translate_meta_boxes' ) );
			add_filter( 'wpseo_save_metaboxes', array( $this, 'save_meta_boxes' ), 10, 1 );

			add_filter( 'wpseo_do_meta_box_field_videositemap-duration', array( $this, 'do_number_field' ), 10, 4 );
			add_filter( 'wpseo_do_meta_box_field_videositemap-rating', array( $this, 'do_number_field' ), 10, 4 );

			add_filter( 'yoast_free_additional_metabox_sections', array( $this, 'add_metabox_section' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		/**
		 * Translate text strings for use in the meta box
		 *
		 * IMPORTANT: if you want to add a new string (option) somewhere, make sure you add that array key to
		 * the main meta box definition array in the class WPSEO_Meta() as well!!!!
		 */
		public static function translate_meta_boxes() {
			WPSEO_Meta::$meta_fields['video']['videositemap-disable']['title'] = __( 'Disable video', 'yoast-video-seo' );
			/* translators: %s: post type name. */
			WPSEO_Meta::$meta_fields['video']['videositemap-disable']['expl'] = __( 'Disable video for this %s', 'yoast-video-seo' );

			WPSEO_Meta::$meta_fields['video']['videositemap-thumbnail']['title'] = __( 'Video Thumbnail', 'yoast-video-seo' );
			/* translators: 1: link open tag; 2: link closing tag. */
			WPSEO_Meta::$meta_fields['video']['videositemap-thumbnail']['description'] = __( 'Now set to %1$sthis image%2$s based on the embed code.', 'yoast-video-seo' );
			WPSEO_Meta::$meta_fields['video']['videositemap-thumbnail']['placeholder'] = __( 'URL to thumbnail image (remember it\'ll be displayed as 16:9)', 'yoast-video-seo' );

			WPSEO_Meta::$meta_fields['video']['videositemap-duration']['title']       = __( 'Video Duration', 'yoast-video-seo' );
			WPSEO_Meta::$meta_fields['video']['videositemap-duration']['description'] = __( 'Overwrite the video duration, or enter one if it\'s empty.', 'yoast-video-seo' );

			WPSEO_Meta::$meta_fields['video']['videositemap-tags']['title']       = __( 'Tags', 'yoast-video-seo' );
			WPSEO_Meta::$meta_fields['video']['videositemap-tags']['description'] = __( 'Add extra tags for this video', 'yoast-video-seo' );

			WPSEO_Meta::$meta_fields['video']['videositemap-category']['title']       = __( 'Category', 'yoast-video-seo' );
			WPSEO_Meta::$meta_fields['video']['videositemap-category']['description'] = __( 'Override video category for this video', 'yoast-video-seo' );

			WPSEO_Meta::$meta_fields['video']['videositemap-rating']['title']       = __( 'Rating', 'yoast-video-seo' );
			WPSEO_Meta::$meta_fields['video']['videositemap-rating']['description'] = __( 'Set a rating between 0 and 5.', 'yoast-video-seo' );

			WPSEO_Meta::$meta_fields['video']['videositemap-not-family-friendly']['title']       = __( 'Not Family-friendly', 'yoast-video-seo' );
			WPSEO_Meta::$meta_fields['video']['videositemap-not-family-friendly']['expl']        = __( 'Mark this video as not Family-friendly', 'yoast-video-seo' );
			WPSEO_Meta::$meta_fields['video']['videositemap-not-family-friendly']['description'] = __( 'If this video should not be available for safe search users, check this box.', 'yoast-video-seo' );
		}

		/**
		 * Helper function to check if the metabox functionality should be loaded
		 *
		 * @return bool
		 */
		public function has_video() {
			if ( isset( $GLOBALS['post']->ID ) ) {
				$video = WPSEO_Meta::get_value( 'video_meta', $GLOBALS['post']->ID );
				if ( is_array( $video ) && $video !== array() ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Adds a video section to the metabox sections array.
		 *
		 * @param array $sections The sections to add.
		 *
		 * @return array
		 */
		public function add_metabox_section( $sections ) {
			if ( ! $this->should_show_metabox() ) {
				return $sections;
			}

			if ( $this->has_video() ) {
				$content = '';
				foreach ( WPSEO_Meta::get_meta_field_defs( 'video' ) as $meta_key => $meta_field ) {
					$content .= $this->do_meta_box( $meta_field, $meta_key );
				}
			}
			else {
				$content = '<p>' . __( 'It looks like your content does not yet contain a video. Please add a video and save your draft in order for Video SEO to work.', 'yoast-video-seo' ) . '</p>';
			}

			$sections[] = array(
				'name'         => 'video',
				'link_content' => '<span class="dashicons dashicons-admin-plugins"></span>' . esc_html__( 'Video', 'yoast-video-seo' ),
				'content'      => $content,
			);

			return $sections;
		}

		/**
		 * Filter over the meta boxes to save, this function adds the Video meta box fields.
		 *
		 * @param array $field_defs Array of metaboxes to save.
		 *
		 * @return array
		 */
		public function save_meta_boxes( $field_defs ) {
			return array_merge( $field_defs, WPSEO_Meta::get_meta_field_defs( 'video' ) );
		}

		/**
		 * Form field generator for number fields in WPSEO metabox
		 *
		 * @param string $content      The current content of the metabox.
		 * @param mixed  $meta_value   The meta value to use for the form field.
		 * @param string $esc_form_key The pre-escaped key for the form field.
		 * @param array  $options      Contains the min and max value of the number field, if relevant.
		 *
		 * @return string
		 */
		public function do_number_field( $content, $meta_value, $esc_form_key, $options = array() ) {
			$options  = $options['options'];
			$minvalue = '';
			$maxvalue = '';
			$step     = '';

			if ( isset( $options['min_value'] ) ) {
				$minvalue = ' min="' . $options['min_value'] . '" ';
			}

			if ( isset( $options['max_value'] ) ) {
				$maxvalue = ' max="' . $options['max_value'] . '" ';
			}

			if ( isset( $options['step'] ) ) {
				$step = ' step="' . $options['step'] . '" ';
			}

			$content .= '<input type="number" id="' . $esc_form_key . '" name="' . $esc_form_key . '" value="' . $meta_value . '"' . $minvalue . $maxvalue . $step . 'class="small-text" /><br />';

			return $content;
		}

		/**
		 * Flattens a version number for use in a filename
		 *
		 * @param string $version The original version number.
		 *
		 * @return string The flattened version number.
		 */
		public function flatten_version( $version ) {
			$parts = explode( '.', $version );
			if ( count( $parts ) === 2 && preg_match( '/^\d+$/', $parts[1] ) === 1 ) {
				$parts[] = '0';
			}
			return implode( '', $parts );
		}

		/**
		 * Enqueues the plugin scripts.
		 */
		public function enqueue_scripts() {
			global $pagenow;

			wp_enqueue_script( 'wp-seo-video-seo', plugins_url( 'js/yoast-video-seo-plugin-' . $this->flatten_version( WPSEO_VIDEO_VERSION ) . WPSEO_CSSJS_SUFFIX . '.js', WPSEO_VIDEO_FILE ), array(), WPSEO_VERSION, true );

			if ( $pagenow === 'post.php' || $pagenow === 'post-new.php' ) {
				wp_enqueue_style( 'wpseo-video-admin-metabox-css', plugins_url( 'css/admin-metabox' . WPSEO_CSSJS_SUFFIX . '.css', WPSEO_VIDEO_FILE ), array(), WPSEO_VIDEO_VERSION );
			}

			wp_localize_script( 'wp-seo-video-seo', 'wpseoVideoL10n', $this->localize_video_script() );
		}

		/**
		 * Check if the post type the user is currently editing is shown in the sitemaps. If so, the video metabox should be shown.
		 *
		 * @return bool
		 */
		private function should_show_metabox() {
			return WPSEO_Video_Utils::is_videoseo_active_for_posttype( get_post_type() );
		}

		/**
		 * Localizes scripts for the videoplugin.
		 *
		 * @return array
		 */
		private function localize_video_script() {
			return array(
				'has_video'           => $this->has_video(),
				'script_url'          => plugins_url( 'js/yoast-video-seo-worker-' . $this->flatten_version( WPSEO_VIDEO_VERSION ) . WPSEO_CSSJS_SUFFIX . '.js', WPSEO_VIDEO_FILE ),
				'video'               => __( 'video', 'yoast-video-seo' ),
				'video_title_ok'      => __( 'You should consider adding the word "video" in your title, to optimize your ability to be found by people searching for video.', 'yoast-video-seo' ),
				'video_title_good'    => __( 'You\'re using the word "video" in your title, this optimizes your ability to be found by people searching for video.', 'yoast-video-seo' ),
				'video_body_short'    => __( 'Your body copy is too short for Search Engines to understand the topic of your video, add some more content describing the contents of the video.', 'yoast-video-seo' ),
				'video_body_good'     => __( 'Your body copy is at optimal length for your video to be recognized by Search Engines.', 'yoast-video-seo' ),
				/* translators: 1: links to https://yoast.com/video-not-showing-search-results, 2: closing link tag */
				'video_body_long'     => __( 'Your body copy is quite long, make sure that the video is the most important asset on the page, read %1$sthis post%2$s for more info.', 'yoast-video-seo' ),
				'video_body_long_url' => '<a target="new" href="https://yoast.com/video-not-showing-search-results/">',
			);
		}

		/* ********************* DEPRECATED METHODS ********************* */

		/**
		 * Output the tab header for the Video tab in the WordPress SEO meta box on edit post pages.
		 *
		 * @deprecated 11.9
		 * @codeCoverageIgnore
		 */
		public function tab_header() {
			_deprecated_function( __METHOD__, '11.9' );
		}

		/**
		 * Output the tab content for the Video tab in the WordPress SEO meta box on edit post pages.
		 *
		 * @deprecated 11.9
		 * @codeCoverageIgnore
		 */
		public function tab_content() {
			_deprecated_function( __METHOD__, '11.9' );
		}
	}

}
