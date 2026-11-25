<?php
/**
 * CV or Resume: the word for candidate documents follows the Modern Job
 * Board plugin's Document label setting (Settings › Listing).
 *
 * Theme content writes it as the [mjb_doc] shortcode (flags: plural, lower),
 * which the plugin provides. This file covers the places shortcodes don't
 * run, and keeps the shortcode working if the plugin is switched off.
 *
 * @package Modern_Job_Board_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render [mjb_doc] in plain text (titles, descriptions, meta).
 *
 * @param string $text Text.
 * @return string
 */
function mjb_theme_doc_text( $text ) {
	$text = (string) $text;

	if ( false === strpos( $text, '[mjb_doc' ) ) {
		return $text;
	}

	if ( class_exists( 'MJB_Document_Label' ) ) {
		return MJB_Document_Label::render_in_text( $text );
	}

	return (string) preg_replace_callback(
		'/\[mjb_doc((?:\s+[a-z]+)*)\s*\]/i',
		static function ( $m ) {
			return false !== stripos( $m[1], 'plural' ) ? 'CVs' : 'CV';
		},
		$text
	);
}

/**
 * Without the plugin, [mjb_doc] still prints a word rather than the shortcode.
 *
 * @return void
 */
function mjb_theme_doc_fallback() {
	if ( shortcode_exists( 'mjb_doc' ) ) {
		return;
	}

	add_shortcode(
		'mjb_doc',
		static function ( $atts ) {
			$flags = array_map( 'strtolower', array_map( 'strval', array_values( (array) $atts ) ) );

			return in_array( 'plural', $flags, true ) ? 'CVs' : 'CV';
		}
	);

	foreach ( array( 'the_title', 'nav_menu_item_title', 'single_post_title', 'get_the_excerpt', 'term_name' ) as $hook ) {
		add_filter( $hook, 'mjb_theme_doc_text', 9 );
	}

	add_filter(
		'document_title_parts',
		static function ( $parts ) {
			return is_array( $parts ) ? array_map( 'mjb_theme_doc_text', $parts ) : $parts;
		},
		20
	);
}
add_action( 'init', 'mjb_theme_doc_fallback', 99 );

/**
 * Searching for "CV" or "resume" also finds content that writes the word as
 * [mjb_doc] (site search and the docs search, which uses the REST search).
 *
 * @param WP_Query $query Query.
 * @return void
 */
function mjb_theme_doc_search( $query ) {
	$search = $query->get( 's' );

	if ( ! is_string( $search ) || '' === $search ) {
		return;
	}

	// Site content only: job, company and candidate searches keep their words.
	if ( is_admin() && ! wp_doing_ajax() ) {
		return;
	}
	$types = (array) $query->get( 'post_type' );
	if ( array_intersect( $types, array( 'job_listing', 'company', 'mjb_resume', 'job_application' ) ) ) {
		return;
	}

	$swapped = preg_replace( '/\b(cvs?|r[eé]sum[eé]s?)\b/iu', 'mjb_doc', $search );

	if ( is_string( $swapped ) && $swapped !== $search ) {
		$GLOBALS['mjb_theme_doc_search_original'] = $search;
		$query->set( 's', $swapped );
	}
}
add_action( 'pre_get_posts', 'mjb_theme_doc_search' );

/**
 * Show the visitor's own words on the search results page.
 *
 * @param string $search Search query.
 * @return string
 */
function mjb_theme_doc_search_display( $search ) {
	return isset( $GLOBALS['mjb_theme_doc_search_original'] ) ? esc_attr( $GLOBALS['mjb_theme_doc_search_original'] ) : $search;
}
add_filter( 'get_search_query', 'mjb_theme_doc_search_display' );
