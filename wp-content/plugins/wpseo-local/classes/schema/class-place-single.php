<?php
/**
 * @package WPSEO_Local\Frontend\Schema
 */

/**
 * Class WPSEO_Local_JSON_LD
 *
 * Manages the Schema for a single Place.
 *
 * @property WPSEO_Schema_Context $context A value object with context variables.
 * @property array                $options Local SEO options.
 */
class WPSEO_Local_Place_Single extends WPSEO_Local_Place implements WPSEO_Graph_Piece {

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
		// Don't show JSON+LD in the header on any other page than the front-page.
		if ( wpseo_has_multiple_locations() ) {
			return false;
		}

		return true;
	}

	/**
	 * Generates JSON+LD output for locations.
	 *
	 * @return false|array Array with Place schema data. Returns false no valid location is found.
	 */
	public function generate() {
		return $this->get_place_data();
	}
}

