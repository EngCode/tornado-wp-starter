<?php
/**
 * Base functionality for registering Schema-related functionality and filtering Yoast SEO output.
 *
 * @package    WordPress SEO
 * @subpackage WordPress SEO Video
 */

/**
 * Initializes videoObject and attaches it to the rest of the Schema.
 *
 * @package WordPress SEO Video
 * @since   11.1
 */
class WPSEO_Video_Schema {
	/**
	 * The hash used in the video identifier.
	 */
	const VIDEO_HASH = '#video';

	/**
	 * Video schema object.
	 *
	 * @var WPSEO_Video_Schema_VideoObject
	 */
	public $object;

	/**
	 * WPSEO_Video_Schema constructor.
	 */
	public function __construct() {
		add_filter( 'wpseo_schema_graph_pieces', array( $this, 'add_graph_piece' ), 11, 2 );
		add_filter( 'wpseo_schema_article', array( $this, 'filter_article' ), 11 );
		add_filter( 'wpseo_schema_webpage', array( $this, 'filter_webpage' ), 11 );
	}

	/**
	 * Adds the videoObject graph piece.
	 *
	 * @param array                $pieces  The Schema pieces to output.
	 * @param WPSEO_Schema_Context $context A value object with context variables.
	 *
	 * @return array $pieces The Schema pieces to output.
	 */
	public function add_graph_piece( $pieces, $context ) {
		$this->object = new WPSEO_Video_Schema_VideoObject( $context );
		$pieces[]     = $this->object;

		return $pieces;
	}

	/**
	 * Changes Article Schema output.
	 *
	 * @param array $data Article Schema data.
	 *
	 * @return array $data Article Schema data.
	 */
	public function filter_article( $data ) {
		if ( $this->object->is_needed() ) {
			$data['video'] = array( $this->get_video_id() );
		}

		return $data;
	}

	/**
	 * Changes WebPage Schema output.
	 *
	 * @param array $data WebPage Schema data.
	 *
	 * @return array $data WebPage Schema data.
	 */
	public function filter_webpage( $data ) {
		if ( ! is_singular() ) {
			return $data;
		}

		/**
		 * Filter: 'wpseo_schema_article_post_types' - Allow changing for which post types we output Article schema.
		 *
		 * @api string[] $post_types The post types for which we output Article.
		 */
		$post_types = apply_filters( 'wpseo_schema_article_post_types', array( 'post' ) );

		if ( in_array( get_post_type(), $post_types, true ) ) {
			return $data;
		}

		if ( $this->object->is_needed() ) {
			$data['video'] = array( $this->get_video_id() );
		}

		return $data;
	}

	/**
	 * Returns an array with the video identifier.
	 *
	 * @return array Array with the video identifier.
	 */
	private function get_video_id() {
		return array( '@id' => WPSEO_Frontend::get_instance()->canonical( false ) . self::VIDEO_HASH );
	}
}
