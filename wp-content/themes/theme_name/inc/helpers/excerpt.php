<?php
/**
 * Except functions
 *
 * @package theme_name
 */

if ( ! function_exists( 'get_excerpt' ) ) {
	/**
	 * Custom Excerpt to set word limit
	 *
	 * @param integer $limit  Number of characters to trim.
	 * @param string  $source From where to chose the source, excerpt or content.
	 * @return string         Trimmed excerpt.
	 */
	function get_excerpt( $limit = null, $source = null ) {

		$excerpt = get_the_content();

		if ( empty( $excerpt ) ) {
			return false;
		}

		if ( empty( $limit ) ) {
			$limit = 140;
		}

		$excerpt = preg_replace( ' (\[.*?\])','',$excerpt );
		$excerpt = strip_shortcodes( $excerpt );
		$excerpt = strip_tags( $excerpt );
		$excerpt = substr( $excerpt, 0, $limit );
		$excerpt = substr( $excerpt, 0, strripos( $excerpt, ' ' ) );
		$excerpt = trim( preg_replace( '/\s+/', ' ', $excerpt ) );
		$excerpt = '<p>' . $excerpt . '...</p>';

		return $excerpt;
	}
}
