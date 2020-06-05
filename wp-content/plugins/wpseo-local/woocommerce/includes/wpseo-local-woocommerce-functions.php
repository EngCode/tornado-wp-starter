<?php
/**
 * Yoast SEO: Local for WooCommerce plugin file.
 *
 * @package YoastSEO_Local_WooCommerce
 */

if ( ! function_exists( 'yoast_seo_local_woocommerce_get_address_for_method_id' ) ) {

	function yoast_seo_local_woocommerce_get_address_for_method_id( $method_id ) {

		// Only alter the shipping address when local shipping has been selected.
		if ( false === ( strstr( $method_id, 'yoast_wcseo_local_pickup' ) ) ) {
			return '';
		}

		// Get the specific post id for this location.
		$location_id = (int) str_replace( 'yoast_wcseo_local_pickup_', '', $method_id );

		// Store the specs we want as an array.
		$address_array = array(
			get_post_meta( $location_id, '_wpseo_business_address', true ),
			get_post_meta( $location_id, '_wpseo_business_zipcode', true ),
			get_post_meta( $location_id, '_wpseo_business_city', true ),
			get_post_meta( $location_id, '_wpseo_business_country', true ),
		);

		// Clear empty values.
		$address_array = array_filter( $address_array );

		// Return as a comma separated string.
		return implode( ', ', $address_array );
	}
}
