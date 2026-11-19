<?php
/**
 * Footer: columns built from the Footer menu (Appearance → Menus).
 *
 * - Each top-level item with sub-items becomes a column: the top-level item
 *   is the column heading (give it the URL "#" for a plain heading) and its
 *   sub-items are the links. A column with more than 8 links spreads over two
 *   columns on wide screens.
 * - Top-level items without sub-items are listed in the bottom bar.
 * - A footer menu without any sub-items keeps the original single-row footer.
 * - The text under the logo is the site tagline (Settings → General).
 *
 * @package ModernJobBoardTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Footer menu items as columns and loose links.
 *
 * @return array{columns:array,loose:WP_Post[]}|null Null when no footer menu is assigned.
 */
function mjb_theme_footer_menu_tree() {
	$locations = get_nav_menu_locations();

	if ( empty( $locations['footer'] ) ) {
		return null;
	}

	$items = wp_get_nav_menu_items( $locations['footer'], array( 'update_post_term_cache' => false ) );

	if ( empty( $items ) ) {
		return null;
	}

	// Adds the current-menu-item classes, as wp_nav_menu() does.
	_wp_menu_item_classes_by_context( $items );

	$top      = array();
	$children = array();

	foreach ( $items as $item ) {
		$parent = (int) $item->menu_item_parent;

		if ( $parent ) {
			$children[ $parent ][] = $item;
		} else {
			$top[] = $item;
		}
	}

	$tree = array(
		'columns' => array(),
		'loose'   => array(),
	);

	foreach ( $top as $item ) {
		if ( ! empty( $children[ $item->ID ] ) ) {
			$tree['columns'][] = array(
				'item'  => $item,
				'links' => $children[ $item->ID ],
			);
		} else {
			$tree['loose'][] = $item;
		}
	}

	return $tree;
}

/**
 * Link for one footer menu item.
 *
 * @param WP_Post $item Menu item.
 * @return string
 */
function mjb_theme_footer_link( $item ) {
	$url     = (string) $item->url;
	$home    = home_url( '/' );
	$classes = array_filter( array_map( 'strval', (array) $item->classes ) );
	$title   = apply_filters( 'the_title', $item->title, $item->ID ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core hook.

	// Same-page section links on the front page, so smooth scrolling keeps working.
	if ( is_front_page() && 0 === strpos( $url, $home . '#' ) ) {
		$url = substr( $url, strlen( $home ) );
	}

	$attributes = '';

	if ( ! empty( $item->target ) ) {
		$attributes .= ' target="' . esc_attr( $item->target ) . '" rel="' . esc_attr( trim( 'noopener noreferrer ' . $item->xfn ) ) . '"';
	} elseif ( ! empty( $item->xfn ) ) {
		$attributes .= ' rel="' . esc_attr( $item->xfn ) . '"';
	}

	if ( ! empty( $item->attr_title ) ) {
		$attributes .= ' title="' . esc_attr( $item->attr_title ) . '"';
	}

	// Section links (/#pricing) point at the front page, which WordPress would mark as current there.
	if ( false === strpos( $url, '#' ) && in_array( 'current-menu-item', $classes, true ) ) {
		$attributes .= ' aria-current="page"';
	}

	$custom = array_diff( $classes, array( 'menu-item', 'menu-item-type-custom', 'menu-item-type-post_type', 'menu-item-object-page', 'menu-item-object-custom', 'current-menu-item', 'current_page_item', 'current-menu-parent', 'current-menu-ancestor', 'current_page_parent', 'current_page_ancestor', 'menu-item-has-children', 'menu-item-home', 'page_item' ) );
	$custom = array_filter(
		$custom,
		static function ( $class ) {
			return 0 !== strpos( $class, 'menu-item-' ) && 0 !== strpos( $class, 'page-item-' );
		}
	);

	if ( $custom ) {
		$attributes .= ' class="' . esc_attr( implode( ' ', $custom ) ) . '"';
	}

	return sprintf( '<a href="%1$s"%2$s>%3$s</a>', esc_url( $url ), $attributes, $title );
}

/**
 * Column heading: plain text when the item links to "#" or nowhere.
 *
 * @param WP_Post $item Menu item.
 * @return string
 */
function mjb_theme_footer_heading( $item ) {
	$url = trim( (string) $item->url );

	if ( '' === $url || '#' === $url ) {
		$inner = apply_filters( 'the_title', $item->title, $item->ID ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core hook.
	} else {
		$inner = mjb_theme_footer_link( $item );
	}

	return '<h2 class="site-footer__heading">' . $inner . '</h2>';
}

/**
 * Copyright line at the bottom of the footer.
 *
 * @return string
 */
function mjb_theme_footer_copyright() {
	return sprintf(
		/* translators: 1: year, 2: link to the developer's site */
		__( 'Copyright &copy; %1$s Modern Job Board, a product of 4Mation Digital (Pty) Ltd. Developed by %2$s.', 'modern-job-board-theme' ),
		esc_html( gmdate( 'Y' ) ),
		'<a href="https://martinorton.com">Martin Orton</a>'
	);
}
