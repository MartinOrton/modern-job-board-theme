<?php
/**
 * Menus: everything in the top bar, the mobile menu and the footer is managed
 * under Appearance → Menus.
 *
 * - MJB_Theme_Nav_Walker prints menu items as the plain links the theme's CSS
 *   expects (no list markup), in three variants: desktop, mobile and footer.
 * - Button items: give a menu item the CSS classes "btn btn-outline btn-sm" or
 *   "btn btn-primary btn-sm" (Screen Options → CSS Classes in the menu editor).
 * - Mega menu: any top-level item with sub-items opens a mega menu built from
 *   those sub-items (label, link, Description; the icon comes from the home
 *   feature card with the same slug as the linked page). Give a sub-item the
 *   CSS class "mjb-mega-footer" to place it in the strip along the bottom.
 *   An item that links to #features or /features/ (or has the class
 *   "mjb-mega") and has no sub-items lists the home feature cards itself.
 * - mjb_theme_create_menus() creates the Primary and Footer menus once, from
 *   the links that used to be hard-coded in header.php and footer.php.
 *
 * @package ModernJobBoardTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Footer menu location (Primary and Live Demo are registered in functions.php).
 */
function mjb_theme_register_footer_menu() {
	register_nav_menus(
		array(
			'footer' => esc_html__( 'Footer Menu', 'modern-job-board-theme' ),
		)
	);
}
add_action( 'after_setup_theme', 'mjb_theme_register_footer_menu', 20 );

if ( ! class_exists( 'MJB_Theme_Nav_Walker' ) ) {
	/**
	 * Prints top-level menu items as plain links.
	 */
	class MJB_Theme_Nav_Walker extends Walker_Nav_Menu {

		/**
		 * Sub-items of the top-level item being printed.
		 *
		 * @var WP_Post[]
		 */
		protected $mjb_children = array();

		/**
		 * Number of mega menus printed, for unique panel ids.
		 *
		 * @var int
		 */
		protected $mjb_panels = 0;

		/**
		 * Collect a top-level item's sub-items before it is printed; they are
		 * rendered as that item's mega menu rather than as nested lists.
		 *
		 * @param object $element           Menu item.
		 * @param array  $children_elements Sub-items by parent id (by reference).
		 * @param int    $max_depth         Maximum depth.
		 * @param int    $depth             Depth.
		 * @param array  $args              Arguments.
		 * @param string $output            Output (by reference).
		 */
		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
			$this->mjb_children = array();

			if ( $element && 0 === (int) $depth ) {
				$id = $element->{$this->db_fields['id']};

				if ( ! empty( $children_elements[ $id ] ) ) {
					$this->mjb_children = $children_elements[ $id ];
				}
			}

			parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}

		/**
		 * No sub-menu wrappers: sub-items are printed as a mega menu instead.
		 *
		 * @param string   $output Output.
		 * @param int      $depth  Depth.
		 * @param stdClass $args   Arguments.
		 */
		public function start_lvl( &$output, $depth = 0, $args = null ) {}

		/**
		 * @param string   $output Output.
		 * @param int      $depth  Depth.
		 * @param stdClass $args   Arguments.
		 */
		public function end_lvl( &$output, $depth = 0, $args = null ) {}

		/**
		 * @param string   $output      Output.
		 * @param WP_Post  $data_object Menu item.
		 * @param int      $depth       Depth.
		 * @param stdClass $args        Arguments.
		 */
		public function end_el( &$output, $data_object, $depth = 0, $args = null ) {}

		/**
		 * Print one menu item.
		 *
		 * @param string   $output            Output.
		 * @param WP_Post  $data_object       Menu item.
		 * @param int      $depth             Depth.
		 * @param stdClass $args              Arguments.
		 * @param int      $current_object_id Current item ID.
		 */
		public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
			if ( $depth > 0 ) {
				return;
			}

			$item    = $data_object;
			$variant = isset( $args->mjb_variant ) ? $args->mjb_variant : 'desktop';
			$classes = array_filter( array_map( 'strval', (array) $item->classes ) );
			$buttons = array_values(
				array_filter(
					$classes,
					static function ( $class ) {
						return 'btn' === $class || 0 === strpos( $class, 'btn-' );
					}
				)
			);
			// Section links (/#pricing) point at the front page, which WordPress would mark as current there.
			$current = false === strpos( (string) $item->url, '#' ) && (bool) array_intersect( array( 'current-menu-item', 'current-page-ancestor', 'current-menu-ancestor' ), $classes );
			$title   = apply_filters( 'the_title', $item->title, $item->ID ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core hook.
			$url     = (string) $item->url;
			$home    = home_url( '/' );

			// Same-page section links on the front page, so smooth scrolling keeps working.
			if ( is_front_page() && 0 === strpos( $url, $home . '#' ) ) {
				$url = substr( $url, strlen( $home ) );
			}

			$path        = trim( (string) wp_parse_url( $item->url, PHP_URL_PATH ), '/' );
			$home_path   = trim( (string) wp_parse_url( $home, PHP_URL_PATH ), '/' );
			$is_features = in_array( 'mjb-mega', $classes, true ) || '#features' === substr( (string) $item->url, -9 ) || ltrim( $home_path . '/features', '/' ) === $path;

			// Sub-items from the menu editor take precedence over the automatic feature list.
			$mega = null;

			if ( ! empty( $this->mjb_children ) && ! $buttons && 'footer' !== $variant ) {
				$mega = mjb_theme_menu_children_to_mega( $this->mjb_children );

				if ( empty( $mega['items'] ) ) {
					$mega = null;
				}
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

			if ( $current && ! $buttons ) {
				$attributes .= ' aria-current="page"';
			}

			if ( 'footer' === $variant ) {
				$output .= sprintf( '<a href="%1$s"%2$s>%3$s</a>', esc_url( $url ), $attributes, $title );
				return;
			}

			if ( 'mobile' === $variant ) {
				if ( $mega || ( $is_features && ! $buttons ) ) {
					$output .= mjb_theme_features_mobile_item( $url, false, $title, $mega );
				} elseif ( in_array( 'btn-primary', $buttons, true ) ) {
					$output .= sprintf( '<a href="%1$s" class="btn btn-primary mobile-btn"%2$s>%3$s</a>', esc_url( $url ), $attributes, $title );
				} else {
					$output .= sprintf( '<a href="%1$s" class="mobile-link%2$s"%3$s>%4$s</a>', esc_url( $url ), $current ? ' active' : '', $attributes, $title );
				}

				return;
			}

			if ( $mega || ( $is_features && ! $buttons ) ) {
				++$this->mjb_panels;
				$output .= mjb_theme_features_nav_item( $url, false, $title, $mega, 1 === $this->mjb_panels ? 'mjb-mega-features' : 'mjb-mega-' . $this->mjb_panels );
				return;
			}

			$class = $buttons ? implode( ' ', $buttons ) : ( $current ? 'active' : '' );

			$output .= sprintf(
				'<a href="%1$s"%2$s%3$s>%4$s</a>',
				esc_url( $url ),
				'' !== $class ? ' class="' . esc_attr( $class ) . '"' : '',
				$attributes,
				$title
			);
		}
	}
}

/**
 * Add one item to a menu.
 *
 * @param int   $menu_id  Menu id.
 * @param array $item     Item: title, url, optional page (WP_Post), classes, target.
 * @param int   $position Position in the menu.
 * @param int   $parent   Parent menu item id (0 for top level).
 * @return int|WP_Error
 */
function mjb_theme_add_menu_item( $menu_id, $item, $position, $parent ) {
	$data = array(
		'menu-item-title'     => $item['title'],
		'menu-item-status'    => 'publish',
		'menu-item-position'  => $position,
		'menu-item-parent-id' => $parent,
		'menu-item-classes'   => isset( $item['classes'] ) ? $item['classes'] : '',
		'menu-item-target'    => isset( $item['target'] ) ? $item['target'] : '',
	);

	if ( ! empty( $item['page'] ) && $item['page'] instanceof WP_Post ) {
		$data['menu-item-type']      = 'post_type';
		$data['menu-item-object']    = 'page';
		$data['menu-item-object-id'] = $item['page']->ID;
	} else {
		$data['menu-item-type'] = 'custom';
		$data['menu-item-url']  = $item['url'];
	}

	return wp_update_nav_menu_item( $menu_id, 0, $data );
}

/**
 * One-off: create the Primary and Footer menus from the links that used to be
 * hard-coded in the templates, and assign them to their locations. Skipped for
 * any location that already has a menu.
 */
function mjb_theme_create_menus() {
	if ( get_option( 'mjb_theme_menus_created' ) || wp_installing() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	update_option( 'mjb_theme_menus_created', 1, false );

	$docs      = get_page_by_path( 'docs' );
	$features  = get_page_by_path( 'features' );
	$children  = array();

	// Features sub-items: every feature page, then the two links in the mega menu's bottom strip.
	if ( $features instanceof WP_Post ) {
		$pages = get_pages(
			array(
				'parent'      => $features->ID,
				'sort_column' => 'menu_order,post_title',
			)
		);

		foreach ( (array) $pages as $page ) {
			$children[] = array(
				'title' => trim( wp_strip_all_tags( $page->post_title ) ),
				'page'  => $page,
				'url'   => get_permalink( $page ),
			);
		}

		if ( ! empty( $children ) ) {
			$children[] = array(
				'title'   => __( 'All features', 'modern-job-board-theme' ),
				'page'    => $features,
				'url'     => get_permalink( $features ),
				'classes' => 'mjb-mega-footer',
			);
			$children[] = array(
				'title'   => __( 'See them in the live demo', 'modern-job-board-theme' ),
				'url'     => home_url( '/jobs/' ),
				'classes' => 'mjb-mega-footer',
				'target'  => '_blank',
			);
		}
	}

	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations = is_array( $locations ) ? $locations : array();

	$menus = array(
		'primary' => array(
			'name'  => __( 'Primary', 'modern-job-board-theme' ),
			'items' => array(
				array(
					'title'    => __( 'Features', 'modern-job-board-theme' ),
					'url'      => home_url( '/#features' ),
					'children' => $children,
				),
				array(
					'title' => __( 'Docs', 'modern-job-board-theme' ),
					'page'  => $docs,
					'url'   => home_url( '/docs/' ),
				),
				array(
					'title' => __( 'Compare', 'modern-job-board-theme' ),
					'url'   => home_url( '/#comparison' ),
				),
				array(
					'title' => __( 'Developers', 'modern-job-board-theme' ),
					'url'   => home_url( '/#developers' ),
				),
				array(
					'title' => __( 'Pricing', 'modern-job-board-theme' ),
					'url'   => home_url( '/#pricing' ),
				),
				array(
					'title'   => __( 'Live Demo', 'modern-job-board-theme' ),
					'url'     => home_url( '/jobs/' ),
					'classes' => 'btn btn-outline btn-sm',
					'target'  => '_blank',
				),
				array(
					'title'   => __( 'Get Started Free', 'modern-job-board-theme' ),
					'url'     => home_url( '/#pricing' ),
					'classes' => 'btn btn-primary btn-sm',
				),
			),
		),
		'footer'  => array(
			'name'  => __( 'Footer', 'modern-job-board-theme' ),
			'items' => array(
				array(
					'title' => __( 'Pricing', 'modern-job-board-theme' ),
					'url'   => home_url( '/#pricing' ),
				),
				array(
					'title' => __( 'Documentation', 'modern-job-board-theme' ),
					'page'  => $docs,
					'url'   => home_url( '/docs/' ),
				),
				array(
					'title' => __( 'Author', 'modern-job-board-theme' ),
					'url'   => 'https://martinorton.com',
				),
			),
		),
	);

	foreach ( $menus as $location => $menu ) {
		if ( ! empty( $locations[ $location ] ) && wp_get_nav_menu_object( $locations[ $location ] ) ) {
			continue;
		}

		// Do not reuse or alter a menu that already has this name.
		if ( wp_get_nav_menu_object( $menu['name'] ) ) {
			continue;
		}

		$menu_id = wp_create_nav_menu( $menu['name'] );

		if ( is_wp_error( $menu_id ) ) {
			continue;
		}

		$position = 0;

		foreach ( $menu['items'] as $item ) {
			$item_id = mjb_theme_add_menu_item( $menu_id, $item, ++$position, 0 );

			if ( is_wp_error( $item_id ) || ! $item_id || empty( $item['children'] ) ) {
				continue;
			}

			foreach ( $item['children'] as $child ) {
				mjb_theme_add_menu_item( $menu_id, $child, ++$position, (int) $item_id );
			}
		}

		$locations[ $location ] = (int) $menu_id;
	}

	set_theme_mod( 'nav_menu_locations', $locations );
}
add_action( 'init', 'mjb_theme_create_menus', 98 );
