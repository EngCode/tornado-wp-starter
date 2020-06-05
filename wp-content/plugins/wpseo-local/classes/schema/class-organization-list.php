<?php
/**
 * @package WPSEO_Local\Frontend\Schema
 */

/**
 * Class WPSEO_Local_Organization_List.
 *
 * Manages the Schema for an Organization List.
 *
 * @property WPSEO_Schema_Context $context A value object with context variables.
 * @property array                $options Local SEO options.
 */
class WPSEO_Local_Organization_List implements WPSEO_Graph_Piece {

	/**
	 * A value object with context variables.
	 *
	 * @var WPSEO_Schema_Context
	 */
	public $context;

	/**
	 * Stores the options for this plugin.
	 *
	 * @var array
	 */
	public $options = array();

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
	 * Determines whether or not this piece should be added to the graph.
	 *
	 * @return bool
	 */
	public function is_needed() {
		if ( is_post_type_archive( 'wpseo_locations' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Generates JSON+LD output for locations.
	 *
	 * @return bool|array Array with Place schema data. Returns false when no valid location is found.
	 */
	public function generate() {
		$repo      = new WPSEO_Local_Locations_Repository();
		$locations = $repo->get();

		if ( count( $locations ) === 0 ) {
			return false;
		}

		$data = array(
			'@type'            => 'ItemList',
			'@id'              => $this->context->canonical . WPSEO_Local_Schema_IDs::LIST_ID,
			'mainEntityOfPage' => array( '@id' => $this->context->canonical . WPSEO_Schema_IDs::WEBPAGE_HASH ),
		);

		$i = 0;
		foreach ( $locations as $location ) {
			$i++;
			$data['itemListElement'][] = array(
				'@type'    => 'ListItem',
				'position' => $i,
				'url'      => get_permalink( $location['post_id'] ),
				'name'     => get_the_title( $location['post_id'] ),
			);
		}

		return $data;
	}
}

