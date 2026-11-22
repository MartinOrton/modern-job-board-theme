<?php
/**
 * Navigation helpers: breadcrumbs for content pages and the Features mega menu.
 *
 * @package ModernJobBoardTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plain-text title for a post (titles come back entity-encoded).
 *
 * @param int|WP_Post $post Post.
 * @return string
 */
function mjb_theme_plain_title( $post ) {
	return trim( html_entity_decode( wp_strip_all_tags( get_the_title( $post ) ), ENT_QUOTES, 'UTF-8' ) );
}

/**
 * Breadcrumb trail for the current request.
 *
 * @return array[] Items with 'label' and 'url' (empty url = current page).
 */
function mjb_theme_get_breadcrumbs() {
	if ( is_front_page() ) {
		return array();
	}

	$crumbs = array(
		array(
			'label' => __( 'Home', 'modern-job-board-theme' ),
			'url'   => home_url( '/' ),
		),
	);

	if ( is_singular() ) {
		$post = get_queried_object();

		if ( ! $post instanceof WP_Post ) {
			return array();
		}

		if ( is_post_type_hierarchical( $post->post_type ) ) {
			foreach ( array_reverse( get_post_ancestors( $post ) ) as $ancestor_id ) {
				$crumbs[] = array(
					'label' => mjb_theme_plain_title( $ancestor_id ),
					'url'   => get_permalink( $ancestor_id ),
				);
			}
		} elseif ( 'post' === $post->post_type ) {
			$blog_id = (int) get_option( 'page_for_posts' );

			if ( $blog_id ) {
				$crumbs[] = array(
					'label' => mjb_theme_plain_title( $blog_id ),
					'url'   => get_permalink( $blog_id ),
				);
			}
		} else {
			$type    = get_post_type_object( $post->post_type );
			$archive = get_post_type_archive_link( $post->post_type );

			if ( $type && $archive ) {
				$crumbs[] = array(
					'label' => $type->labels->name,
					'url'   => $archive,
				);
			}
		}

		$crumbs[] = array(
			'label' => mjb_theme_plain_title( $post ),
			'url'   => '',
		);
	} elseif ( is_home() ) {
		$blog_id  = (int) get_option( 'page_for_posts' );
		$crumbs[] = array(
			'label' => $blog_id ? mjb_theme_plain_title( $blog_id ) : __( 'Blog', 'modern-job-board-theme' ),
			'url'   => '',
		);
	} elseif ( is_search() ) {
		$crumbs[] = array(
			'label' => __( 'Search results', 'modern-job-board-theme' ),
			'url'   => '',
		);
	} elseif ( is_archive() ) {
		$crumbs[] = array(
			'label' => wp_strip_all_tags( get_the_archive_title() ),
			'url'   => '',
		);
	} else {
		return array();
	}

	/**
	 * Filter the breadcrumb trail (label and url per crumb; an empty url marks the current page).
	 *
	 * @param array[] $crumbs Crumbs.
	 */
	return apply_filters( 'mjb_theme_breadcrumbs', $crumbs );
}

/**
 * Print the breadcrumb trail (used in the content page hero).
 */
function mjb_theme_breadcrumbs() {
	$crumbs = mjb_theme_get_breadcrumbs();

	if ( count( $crumbs ) < 2 ) {
		return;
	}

	echo '<nav class="mjb-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'modern-job-board-theme' ) . '"><ol>';

	foreach ( $crumbs as $crumb ) {
		if ( '' !== $crumb['url'] ) {
			printf( '<li><a href="%s">%s</a></li>', esc_url( $crumb['url'] ), esc_html( $crumb['label'] ) );
		} else {
			printf( '<li aria-current="page"><span>%s</span></li>', esc_html( $crumb['label'] ) );
		}
	}

	echo '</ol></nav>';
}

/**
 * BreadcrumbList structured data for search engines.
 */
function mjb_theme_breadcrumbs_schema() {
	$crumbs = mjb_theme_get_breadcrumbs();

	if ( count( $crumbs ) < 2 || ! is_singular() ) {
		return;
	}

	$items = array();

	foreach ( $crumbs as $index => $crumb ) {
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $index + 1,
			'name'     => $crumb['label'],
			'item'     => '' !== $crumb['url'] ? $crumb['url'] : get_permalink( get_queried_object_id() ),
		);
	}

	$data = array(
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $items,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-encoded.
}
add_action( 'wp_head', 'mjb_theme_breadcrumbs_schema', 20 );

/**
 * Items for the Features mega menu.
 *
 * Uses the feature cards from the front page (title, description and icon, in
 * card order; see mjb_theme_get_feature_cards()). If the front page has no
 * cards, falls back to the child pages of /features/.
 *
 * @return array[] Items with title, description, url, icon.
 */
function mjb_theme_get_feature_menu_items() {
	$items = array();

	foreach ( mjb_theme_get_feature_cards() as $card ) {
		if ( empty( $card['slug'] ) || empty( $card['title'] ) ) {
			continue;
		}

		$items[] = array(
			'title'       => (string) $card['title'],
			'description' => isset( $card['description'] ) ? (string) $card['description'] : '',
			'url'         => home_url( '/features/' . $card['slug'] . '/' ),
			'icon'        => isset( $card['icon'] ) ? (string) $card['icon'] : '',
		);
	}

	if ( ! empty( $items ) ) {
		return $items;
	}

	$parent = get_page_by_path( 'features' );

	if ( ! $parent instanceof WP_Post ) {
		return array();
	}

	$pages = get_pages(
		array(
			'parent'      => $parent->ID,
			'sort_column' => 'menu_order,post_title',
		)
	);

	foreach ( (array) $pages as $page ) {
		$items[] = array(
			'title'       => mjb_theme_plain_title( $page ),
			'description' => (string) $page->post_excerpt,
			'url'         => get_permalink( $page ),
			'icon'        => '',
		);
	}

	return $items;
}

/**
 * Turn a menu item's sub-items (Appearance → Menus) into mega menu content.
 *
 * Each sub-item becomes a tile: its Navigation Label, its link, its Description
 * (or, when that is empty, the linked page's excerpt) and the icon of the home
 * feature card with the same slug as the linked page. Sub-items with the CSS
 * class "mjb-mega-footer" go in the strip along the bottom of the panel.
 *
 * @param WP_Post[] $children Menu items.
 * @return array{items:array[],footer:array[]}
 */
function mjb_theme_menu_children_to_mega( $children ) {
	$menu  = array(
		'items'  => array(),
		'footer' => array(),
	);
	$icons = array();

	foreach ( mjb_theme_get_feature_cards() as $card ) {
		$icons[ $card['slug'] ] = isset( $card['icon'] ) ? (string) $card['icon'] : '';
	}

	foreach ( (array) $children as $child ) {
		// Menu titles arrive entity-encoded; decode so they are escaped exactly once on output.
		$title = trim( html_entity_decode( wp_strip_all_tags( (string) $child->title ), ENT_QUOTES, 'UTF-8' ) );

		if ( '' === $title ) {
			continue;
		}

		$target = ! empty( $child->target ) ? (string) $child->target : '';

		if ( in_array( 'mjb-mega-footer', array_map( 'strval', (array) $child->classes ), true ) ) {
			$menu['footer'][] = array(
				'title'  => $title,
				'url'    => (string) $child->url,
				'target' => $target,
			);
			continue;
		}

		$description = trim( html_entity_decode( wp_strip_all_tags( (string) $child->description ), ENT_QUOTES, 'UTF-8' ) );
		$icon        = '';

		if ( 'post_type' === $child->type && ! empty( $child->object_id ) ) {
			$linked = get_post( (int) $child->object_id );

			if ( $linked instanceof WP_Post ) {
				if ( '' === $description ) {
					$description = trim( wp_strip_all_tags( (string) $linked->post_excerpt ) );
				}

				$icon = isset( $icons[ $linked->post_name ] ) ? $icons[ $linked->post_name ] : '';
			}
		}

		$menu['items'][] = array(
			'title'       => $title,
			'description' => $description,
			'url'         => (string) $child->url,
			'icon'        => $icon,
			'target'      => $target,
		);
	}

	return $menu;
}

/**
 * Mega menu panel markup (empty string when there is nothing to show).
 *
 * @param array|null $menu Content from mjb_theme_menu_children_to_mega(). Null
 *                         lists the home feature cards automatically.
 * @param string     $id   Panel id.
 * @return string
 */
function mjb_theme_get_features_mega_panel( $menu = null, $id = 'mjb-mega-features' ) {
	if ( null === $menu ) {
		$menu = array(
			'items'  => mjb_theme_get_feature_menu_items(),
			'footer' => array(
				array(
					'title'  => __( 'All features', 'modern-job-board-theme' ),
					'url'    => home_url( '/features/' ),
					'target' => '',
				),
				array(
					'title'  => __( 'See them in the live demo', 'modern-job-board-theme' ),
					'url'    => home_url( '/jobs/' ),
					'target' => '_blank',
				),
			),
		);
	}

	$items = $menu['items'];

	if ( empty( $items ) ) {
		return '';
	}

	$arrow = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>';

	$html  = '<div class="mega-menu" id="' . esc_attr( $id ) . '">';
	$html .= '<div class="mega-menu__inner"><div class="mega-menu__grid">';

	foreach ( $items as $item ) {
		$html .= sprintf(
			'<a class="mega-menu__item" href="%1$s"%5$s><span class="mega-menu__icon" aria-hidden="true">%2$s</span><span class="mega-menu__text"><span class="mega-menu__title">%3$s</span>%4$s</span></a>',
			esc_url( $item['url'] ),
			mjb_theme_feature_icon( $item['icon'] ),
			esc_html( $item['title'] ),
			'' !== $item['description'] ? '<span class="mega-menu__desc">' . esc_html( $item['description'] ) . '</span>' : '',
			! empty( $item['target'] ) ? ' target="' . esc_attr( $item['target'] ) . '" rel="noopener noreferrer"' : ''
		);
	}

	$html .= '</div>';

	if ( ! empty( $menu['footer'] ) ) {
		$html .= '<div class="mega-menu__footer">';

		foreach ( $menu['footer'] as $index => $link ) {
			$html .= sprintf(
				'<a class="%1$s" href="%2$s"%3$s>%4$s%5$s</a>',
				0 === $index ? 'mega-menu__all' : 'mega-menu__demo',
				esc_url( $link['url'] ),
				! empty( $link['target'] ) ? ' target="' . esc_attr( $link['target'] ) . '" rel="noopener noreferrer"' : '',
				esc_html( $link['title'] ),
				$arrow
			);
		}

		$html .= '</div>';
	}

	$html .= '</div></div>';

	return $html;
}

/**
 * Print the Features nav item: the link plus, when feature pages exist, the mega menu.
 *
 * @param string $url   Link target for the Features item.
 * @param bool   $echo  Print (true) or return (false).
 * @param string $label Link text; defaults to "Features".
 * @param array|null $menu Mega menu content from the menu editor; null lists the home feature cards.
 * @param string     $id   Panel id.
 * @return string|void
 */
function mjb_theme_features_nav_item( $url, $echo = true, $label = '', $menu = null, $id = 'mjb-mega-features' ) {
	$panel = mjb_theme_get_features_mega_panel( $menu, $id );
	$label = '' !== $label ? esc_html( wp_strip_all_tags( $label ) ) : esc_html__( 'Features', 'modern-job-board-theme' );

	if ( '' === $panel ) {
		$html = sprintf( '<a href="%s">%s</a>', esc_url( $url ), $label );
	} else {
		$html = sprintf(
			'<div class="nav-item nav-item--mega"><a href="%1$s" class="nav-item__trigger" aria-haspopup="true" aria-expanded="false" aria-controls="%4$s">%2$s<svg class="nav-item__chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></a>%3$s</div>',
			esc_url( $url ),
			$label,
			$panel,
			esc_attr( $id )
		);
	}

	if ( ! $echo ) {
		return $html;
	}

	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built from escaped parts.
}

/**
 * Mobile menu: Features as a collapsible group listing every feature page.
 *
 * @param string $url   Link target for the Features overview (the home section).
 * @param bool   $echo  Print (true) or return (false).
 * @param string $label Link text; defaults to "Features".
 * @return string|void
 */
function mjb_theme_features_mobile_item( $url, $echo = true, $label = '', $menu = null ) {
	if ( null === $menu ) {
		$menu = array(
			'items'  => mjb_theme_get_feature_menu_items(),
			'footer' => array(
				array(
					'title'  => __( 'All features', 'modern-job-board-theme' ),
					'url'    => home_url( '/features/' ),
					'target' => '',
				),
			),
		);
	}

	$items = $menu['items'];
	$label = '' !== $label ? esc_html( wp_strip_all_tags( $label ) ) : esc_html__( 'Features', 'modern-job-board-theme' );

	if ( empty( $items ) ) {
		$html = sprintf( '<a href="%s" class="mobile-link">%s</a>', esc_url( $url ), $label );
	} else {
		$html  = '<details class="mobile-group"><summary><span class="mobile-group__label">' . $label . '</span>';
		$html .= '<svg class="mobile-group__chevron" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></summary>';
		$html .= '<div class="mobile-submenu">';

		foreach ( $items as $item ) {
			$html .= sprintf(
				'<a class="mobile-sublink" href="%1$s"><span class="mobile-sublink__icon" aria-hidden="true">%2$s</span>%3$s</a>',
				esc_url( $item['url'] ),
				mjb_theme_feature_icon( $item['icon'] ),
				esc_html( $item['title'] )
			);
		}

		foreach ( $menu['footer'] as $link ) {
			$html .= sprintf(
				'<a class="mobile-sublink mobile-sublink--all" href="%1$s"%2$s>%3$s</a>',
				esc_url( $link['url'] ),
				! empty( $link['target'] ) ? ' target="' . esc_attr( $link['target'] ) . '" rel="noopener noreferrer"' : '',
				esc_html( $link['title'] )
			);
		}

		$html .= '</div></details>';
	}

	if ( ! $echo ) {
		return $html;
	}

	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built from escaped parts.
}
