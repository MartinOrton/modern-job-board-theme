<?php
/**
 * Site pages: footer, contact page, legal pages and knowledge base.
 *
 * - Loads the footer, contact form and knowledge base code.
 * - css/site.css styles all of them (front end and editor).
 * - One-off set-up, each step run once and remembered in an option:
 *   creates the Contact and legal pages (from inc/pages/*.html), sets the
 *   Privacy Policy page in Settings → Privacy, and arranges the Footer menu
 *   in columns. After that everything is edited in the block editor and
 *   Appearance → Menus; nothing is overwritten again.
 *
 * @package ModernJobBoardTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/footer.php';
require_once __DIR__ . '/contact.php';
require_once __DIR__ . '/kb.php';

/**
 * Editor script for the site blocks, with the data its controls need.
 */
function mjb_theme_site_register_editor() {
	mjb_theme_site_register_handles();

	$defaults = wp_list_pluck( mjb_theme_contact_attributes(), 'default' );
	$data     = array(
		'contact' => array(
			'topics'         => $defaults['topics'],
			'submitLabel'    => $defaults['submitLabel'],
			'successMessage' => $defaults['successMessage'],
			'adminEmail'     => current_user_can( 'manage_options' ) ? get_option( 'admin_email' ) : '',
		),
	);

	if ( function_exists( 'mjb_theme_kb_editor_data' ) ) {
		$data['kb'] = mjb_theme_kb_editor_data();
	}

	wp_add_inline_script( 'mjb-site-blocks-editor', 'window.mjbSiteBlocks = ' . wp_json_encode( $data ) . ';', 'before' );
}
add_action( 'enqueue_block_editor_assets', 'mjb_theme_site_register_editor', 5 );

/**
 * Register the editor script handle early, so register_block_type() can reference it.
 */
function mjb_theme_site_register_handles() {
	if ( ! wp_script_is( 'mjb-site-blocks-editor', 'registered' ) ) {
		wp_register_script(
			'mjb-site-blocks-editor',
			get_template_directory_uri() . '/blocks/site-editor.js',
			array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-i18n' ),
			MJB_THEME_VERSION,
			true
		);
	}
}
add_action( 'init', 'mjb_theme_site_register_handles', 5 );

/**
 * Styles for the footer, contact form, legal pages and knowledge base.
 */
function mjb_theme_site_styles() {
	wp_enqueue_style(
		'mjb-site-style',
		get_template_directory_uri() . '/css/site.css',
		array( 'mjb-main-style' ),
		MJB_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'mjb_theme_site_styles', 20 );

/**
 * Navigation progress on every public page: a bar at the top and a spinner
 * on the link or button used, until the next page shows.
 */
function mjb_theme_nav_progress_script() {
	wp_enqueue_script(
		'mjb-nav-progress',
		get_template_directory_uri() . '/js/nav-progress.js',
		array(),
		MJB_THEME_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'mjb_theme_nav_progress_script', 20 );

/**
 * The same styles inside the block editor.
 */
function mjb_theme_site_editor_styles() {
	if ( ! is_admin() ) {
		return;
	}

	wp_enqueue_style(
		'mjb-site-style-editor',
		get_template_directory_uri() . '/css/site.css',
		array(),
		MJB_THEME_VERSION
	);
}
add_action( 'enqueue_block_assets', 'mjb_theme_site_editor_styles' );

/**
 * Pages created by the set-up: slug => title, excerpt and content file.
 *
 * @return array
 */
function mjb_theme_site_pages() {
	return array(
		'about-us'              => array(
			'title'   => __( 'About Us', 'modern-job-board-theme' ),
			'excerpt' => __( 'Modern Job Board is built and supported by 4Mation Digital®, a digital agency in Johannesburg that has been building websites since 1999.', 'modern-job-board-theme' ),
		),
		'contact'               => array(
			'title'   => __( 'Contact', 'modern-job-board-theme' ),
			'excerpt' => __( 'Questions about plans, a Complete Site build or a technical problem? Send us a message and we will reply by email.', 'modern-job-board-theme' ),
		),
		'privacy-policy'        => array(
			'title'   => __( 'Privacy Policy', 'modern-job-board-theme' ),
			'excerpt' => __( 'What personal information this website collects, why, how long we keep it, and your rights under POPIA and the GDPR.', 'modern-job-board-theme' ),
		),
		'terms-of-use'          => array(
			'title'   => __( 'Terms of Use', 'modern-job-board-theme' ),
			'excerpt' => __( 'The rules for using this website and the live demo job board.', 'modern-job-board-theme' ),
		),
		'cookie-policy'         => array(
			'title'   => __( 'Cookie Policy', 'modern-job-board-theme' ),
			'excerpt' => __( 'The cookies and browser storage this website uses, what they are for, and how to control them.', 'modern-job-board-theme' ),
		),
		'license-refund-policy' => array(
			'title'   => __( 'License & Refund Policy', 'modern-job-board-theme' ),
			'excerpt' => __( 'How Modern Job Board is licensed, what each plan includes, renewals, and when you can get a refund.', 'modern-job-board-theme' ),
		),
	);
}

/**
 * Block content for a page created by the set-up, with the site's URLs filled in.
 *
 * @param string $slug Page slug.
 * @return string
 */
function mjb_theme_site_page_content( $slug ) {
	$file = __DIR__ . '/pages/' . $slug . '.html';

	if ( ! is_readable( $file ) ) {
		return '';
	}

	$content = (string) file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local theme file.

	return strtr(
		$content,
		array(
			'{{home}}'    => untrailingslashit( home_url() ),
			'{{updated}}' => '19 November 2026',
			// The shared call-to-action band (a synced pattern), as on the feature pages.
			'{{cta}}'     => false !== strpos( $content, '{{cta}}' ) && function_exists( 'mjb_theme_cta_block' ) ? mjb_theme_cta_block() : '',
		)
	);
}

/**
 * Create one page (or publish the WordPress draft with the same slug).
 *
 * @param string $slug Page slug.
 * @return int Page id, or 0.
 */
function mjb_theme_site_create_page( $slug ) {
	$pages = mjb_theme_site_pages();

	if ( empty( $pages[ $slug ] ) ) {
		return 0;
	}

	$existing = get_posts(
		array(
			'post_type'      => 'page',
			'name'           => $slug,
			'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'future' ),
			'posts_per_page' => 1,
		)
	);
	$page     = $existing ? $existing[0] : null;

	// A published page with this slug is the owner's: leave it as it is.
	if ( $page && 'publish' === $page->post_status ) {
		return (int) $page->ID;
	}

	$content = mjb_theme_site_page_content( $slug );

	if ( '' === $content ) {
		return 0;
	}

	$data = array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_name'    => $slug,
		'post_title'   => $pages[ $slug ]['title'],
		'post_excerpt' => $pages[ $slug ]['excerpt'],
		'post_content' => $content,
		'post_author'  => mjb_theme_site_author(),
	);

	if ( $page ) {
		$data['ID'] = $page->ID;
	}

	// Save exactly what was built: no HTML filtering for this trusted theme content
	// (the set-up can run on a visitor's request, where filtering would apply).
	kses_remove_filters();
	$id = wp_insert_post( wp_slash( $data ), true );
	kses_init();

	return is_wp_error( $id ) ? 0 : (int) $id;
}

/**
 * Author for pages created by the set-up: the first administrator.
 *
 * @return int
 */
function mjb_theme_site_author() {
	$admins = get_users(
		array(
			'role'    => 'administrator',
			'number'  => 1,
			'orderby' => 'ID',
			'fields'  => 'ID',
		)
	);

	return $admins ? (int) $admins[0] : 0;
}

/**
 * Run a set-up step once. The option is added before the step runs, so two
 * requests at the same moment cannot both run it.
 *
 * @param string   $option Option that remembers the step.
 * @param callable $step   Step; its return value is stored in the option.
 */
function mjb_theme_site_once( $option, $step ) {
	$state = get_option( $option, false );

	// A step that stopped half-way (a fatal error) is tried again after 10 minutes.
	if ( is_string( $state ) && 0 === strpos( $state, 'running:' ) && (int) substr( $state, 8 ) < time() - 10 * MINUTE_IN_SECONDS ) {
		delete_option( $option );
		$state = false;
	}

	if ( false !== $state || ! add_option( $option, 'running:' . time(), '', false ) ) {
		return;
	}

	$result = call_user_func( $step );
	update_option( $option, $result ? $result : 'done', false );
}

/**
 * One-off set-up of the site pages and footer menu.
 */
function mjb_theme_site_setup() {
	if ( wp_installing() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return;
	}

	mjb_theme_site_once(
		'mjb_theme_page_contact',
		static function () {
			return mjb_theme_site_create_page( 'contact' );
		}
	);

	mjb_theme_site_once(
		'mjb_theme_pages_legal',
		static function () {
			$ids = array();

			foreach ( array( 'privacy-policy', 'terms-of-use', 'cookie-policy', 'license-refund-policy' ) as $slug ) {
				$ids[ $slug ] = mjb_theme_site_create_page( $slug );
			}

			if ( ! empty( $ids['privacy-policy'] ) ) {
				update_option( 'wp_page_for_privacy_policy', $ids['privacy-policy'] );
			}

			return $ids;
		}
	);

	mjb_theme_site_once(
		'mjb_theme_page_about',
		static function () {
			return mjb_theme_site_create_page( 'about-us' );
		}
	);

	mjb_theme_site_once( 'mjb_theme_footer_menu_v2', 'mjb_theme_site_build_footer_menu' );
	mjb_theme_site_once( 'mjb_theme_legal_fonts_v1', 'mjb_theme_site_legal_drop_google_fonts' );
	mjb_theme_site_once( 'mjb_theme_footer_about_v1', 'mjb_theme_site_footer_about' );
}
add_action( 'init', 'mjb_theme_site_setup', 30 );

/**
 * Copy of a menu item's fields, for wp_update_nav_menu_item().
 *
 * @param WP_Post $item     Menu item.
 * @param int     $parent   New parent item id.
 * @param int     $position New position.
 * @return array
 */
function mjb_theme_site_menu_item_data( $item, $parent, $position ) {
	return array(
		'menu-item-db-id'       => $item->ID,
		'menu-item-object-id'   => $item->object_id,
		'menu-item-object'      => $item->object,
		'menu-item-parent-id'   => $parent,
		'menu-item-position'    => $position,
		'menu-item-type'        => $item->type,
		'menu-item-title'       => $item->title,
		'menu-item-url'         => $item->url,
		'menu-item-description' => $item->description,
		'menu-item-attr-title'  => $item->attr_title,
		'menu-item-target'      => $item->target,
		'menu-item-classes'     => implode( ' ', array_filter( (array) $item->classes ) ),
		'menu-item-xfn'         => $item->xfn,
		'menu-item-status'      => 'publish',
	);
}

/**
 * Arrange the Footer menu in four columns: Features, Resources, Company and
 * Legal. Links already in the footer menu are moved into the columns, the
 * feature links are copied from the Primary menu, and the new pages are
 * added. Skipped if the footer menu already has columns.
 *
 * @return string
 */
function mjb_theme_site_build_footer_menu() {
	$locations = get_nav_menu_locations();
	$menu_id   = isset( $locations['footer'] ) ? (int) $locations['footer'] : 0;

	if ( ! $menu_id || ! wp_get_nav_menu_object( $menu_id ) ) {
		$menu_id = wp_create_nav_menu( __( 'Footer', 'modern-job-board-theme' ) );

		if ( is_wp_error( $menu_id ) ) {
			return 'error';
		}

		$locations['footer'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	$footer = (array) wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'any' ) );

	foreach ( $footer as $item ) {
		if ( (int) $item->menu_item_parent ) {
			return 'has-columns';
		}
	}

	$primary = ! empty( $locations['primary'] ) ? (array) wp_get_nav_menu_items( $locations['primary'] ) : array();

	// Find a menu item whose URL ends with $suffix.
	$find = static function ( $items, $suffix, $top_only = false ) {
		foreach ( $items as $item ) {
			if ( $top_only && (int) $item->menu_item_parent ) {
				continue;
			}

			$url = untrailingslashit( (string) $item->url );

			if ( untrailingslashit( $suffix ) === substr( $url, -strlen( untrailingslashit( $suffix ) ) ) ) {
				return $item;
			}
		}

		return null;
	};

	$page_link = static function ( $slug, $title = '' ) {
		$page = get_page_by_path( $slug );

		if ( ! $page instanceof WP_Post || 'publish' !== $page->post_status ) {
			return null;
		}

		return array(
			'title' => '' !== $title ? $title : $page->post_title,
			'page'  => $page,
		);
	};

	// Column 1: the feature links from the Primary menu's Features item.
	$features = array();
	$parent   = $find( $primary, '#features', true );

	if ( ! $parent ) {
		$parent = $find( $primary, '/features', true );
	}

	if ( $parent ) {
		foreach ( $primary as $item ) {
			// The live demo link goes in the Resources column instead.
			$path = untrailingslashit( (string) wp_parse_url( (string) $item->url, PHP_URL_PATH ) );

			if ( (int) $item->menu_item_parent !== (int) $parent->ID || '/jobs' === substr( $path, -5 ) ) {
				continue;
			}

			$link = array( 'title' => $item->title );

			if ( 'post_type' === $item->type && 'page' === $item->object ) {
				$link['page'] = get_post( $item->object_id );
			} else {
				$link['url'] = $item->url;
			}

			$features[] = $link;
		}
	}

	if ( ! $features ) {
		$all = $page_link( 'features', __( 'All features', 'modern-job-board-theme' ) );

		if ( $all ) {
			$features[] = $all;
		}

		foreach ( get_pages( array( 'child_of' => $all ? $all['page']->ID : -1, 'sort_column' => 'menu_order' ) ) as $page ) {
			$features[] = array(
				'title' => $page->post_title,
				'page'  => $page,
			);
		}
	}

	$custom = static function ( $title, $path ) {
		return array(
			'title' => $title,
			'url'   => home_url( $path ),
		);
	};

	$from_primary = static function ( $suffix, $title, $path ) use ( $find, $primary, $custom ) {
		$item = $find( $primary, $suffix, true );

		return $custom( $item ? $item->title : $title, $path );
	};

	$columns = array(
		__( 'Features', 'modern-job-board-theme' )  => $features,
		__( 'Resources', 'modern-job-board-theme' ) => array(
			$find( $footer, '/docs' ) ? $find( $footer, '/docs' ) : $page_link( 'docs', __( 'Documentation', 'modern-job-board-theme' ) ),
			$from_primary( '#developers', __( 'Developers', 'modern-job-board-theme' ), '/#developers' ),
			$from_primary( '#comparison', __( 'Compare', 'modern-job-board-theme' ), '/#comparison' ),
			$from_primary( '/jobs', __( 'Live Demo', 'modern-job-board-theme' ), '/jobs/' ),
		),
		__( 'Company', 'modern-job-board-theme' )   => array(
			$find( $footer, '#pricing' ) ? $find( $footer, '#pricing' ) : $custom( __( 'Pricing', 'modern-job-board-theme' ), '/#pricing' ),
			$page_link( 'contact' ),
			$page_link( 'about-us' ),
		),
		__( 'Legal', 'modern-job-board-theme' )     => array(
			$page_link( 'privacy-policy' ),
			$page_link( 'terms-of-use' ),
			$page_link( 'cookie-policy' ),
			$page_link( 'license-refund-policy' ),
		),
	);

	$position = 0;
	$used     = array();

	foreach ( $columns as $heading => $links ) {
		$links = array_filter( $links );

		if ( ! $links ) {
			continue;
		}

		$heading_id = mjb_theme_add_menu_item(
			$menu_id,
			array(
				'title' => $heading,
				'url'   => '#',
			),
			++$position,
			0
		);

		if ( is_wp_error( $heading_id ) ) {
			continue;
		}

		foreach ( $links as $link ) {
			if ( $link instanceof WP_Post ) {
				// An existing footer link: move it into the column.
				wp_update_nav_menu_item( $menu_id, $link->ID, mjb_theme_site_menu_item_data( $link, $heading_id, ++$position ) );
				$used[] = $link->ID;
			} else {
				mjb_theme_add_menu_item( $menu_id, $link, ++$position, $heading_id );
			}
		}
	}

	// Anything else that was in the footer menu stays, after the columns (shown in the bottom bar).
	foreach ( $footer as $item ) {
		if ( ! in_array( $item->ID, $used, true ) ) {
			wp_update_nav_menu_item( $menu_id, $item->ID, mjb_theme_site_menu_item_data( $item, 0, ++$position ) );
		}
	}

	return 'columns';
}

/**
 * Footer, Company column: the About Us page takes the place of the old
 * "Author" link (martinorton.com). If there is no such link, About Us is added
 * after Contact. Nothing happens if the footer already links to the page.
 *
 * @return string
 */
function mjb_theme_site_footer_about() {
	$page      = get_page_by_path( 'about-us' );
	$locations = get_nav_menu_locations();
	$menu_id   = isset( $locations['footer'] ) ? (int) $locations['footer'] : 0;

	if ( ! $page instanceof WP_Post || 'publish' !== $page->post_status ) {
		return 'no-page';
	}

	if ( ! $menu_id || ! wp_get_nav_menu_object( $menu_id ) ) {
		return 'no-menu';
	}

	$items   = (array) wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'any' ) );
	$author  = null;
	$contact = null;

	foreach ( $items as $item ) {
		if ( 'post_type' === $item->type && (int) $item->object_id === (int) $page->ID ) {
			return 'already-linked';
		}

		if ( ! $author && false !== strpos( (string) $item->url, 'martinorton.com' ) ) {
			$author = $item;
		}

		if ( ! $contact && 'post_type' === $item->type && 'contact' === get_post_field( 'post_name', (int) $item->object_id ) ) {
			$contact = $item;
		}
	}

	$data = array(
		'menu-item-object-id' => $page->ID,
		'menu-item-object'    => 'page',
		'menu-item-type'      => 'post_type',
		'menu-item-title'     => __( 'About Us', 'modern-job-board-theme' ),
		'menu-item-status'    => 'publish',
	);

	if ( $author ) {
		// Same place in the menu; the external link becomes the page.
		$data['menu-item-parent-id'] = (int) $author->menu_item_parent;
		$data['menu-item-position']  = (int) $author->menu_order;
		$saved                       = wp_update_nav_menu_item( $menu_id, $author->ID, $data );

		return is_wp_error( $saved ) ? 'failed' : 'replaced-author';
	}

	if ( ! $contact ) {
		return 'no-company-column';
	}

	$data['menu-item-parent-id'] = (int) $contact->menu_item_parent;
	$data['menu-item-position']  = (int) $contact->menu_order + 1;
	$saved                       = wp_update_nav_menu_item( $menu_id, 0, $data );

	return is_wp_error( $saved ) ? 'failed' : 'added';
}

/**
 * Body classes for the contact and legal pages (narrower reading column).
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function mjb_theme_site_body_class( $classes ) {
	if ( is_page( array( 'privacy-policy', 'terms-of-use', 'cookie-policy', 'license-refund-policy' ) ) ) {
		$classes[] = 'mjb-page-legal';
	} elseif ( is_page( 'contact' ) ) {
		$classes[] = 'mjb-page-contact';
	}

	return $classes;
}
add_filter( 'body_class', 'mjb_theme_site_body_class' );

/**
 * Tables with the class "mjb-stack-table": label each cell with its column
 * heading, so the rows can stack as cards on phones.
 *
 * @param string $html  Rendered block.
 * @param array  $block Parsed block.
 * @return string
 */
function mjb_theme_site_table_labels( $html, $block ) {
	if ( false === strpos( $html, 'mjb-stack-table' ) || ! preg_match( '/<thead\b.*?<\/thead>/is', $html, $head ) || ! preg_match_all( '/<th\b[^>]*>(.*?)<\/th>/is', $head[0], $cells ) ) {
		return $html;
	}

	$labels = array_map(
		static function ( $cell ) {
			return trim( html_entity_decode( wp_strip_all_tags( $cell ), ENT_QUOTES, 'UTF-8' ) );
		},
		$cells[1]
	);

	return preg_replace_callback(
		'/<tr\b[^>]*>.*?<\/tr>/is',
		static function ( $row ) use ( $labels ) {
			$i = 0;

			return preg_replace_callback(
				'/<td\b(?![^>]*\bdata-label=)/i',
				static function ( $cell ) use ( $labels, &$i ) {
					$label = isset( $labels[ $i ] ) ? $labels[ $i ] : '';
					++$i;

					return '' !== $label ? '<td data-label="' . esc_attr( $label ) . '"' : $cell[0];
				},
				$row[0]
			);
		},
		$html
	);
}
add_filter( 'render_block_core/table', 'mjb_theme_site_table_labels', 11, 2 );

/**
 * DM Sans is now served from the theme, so the Privacy Policy and Cookie
 * Policy no longer list Google Fonts. Removes just that list item from each
 * published page (if it is still there) and moves the "Last updated" date on.
 *
 * @return array
 */
function mjb_theme_site_legal_drop_google_fonts() {
	$done = array();

	foreach ( array( 'privacy-policy', 'cookie-policy' ) as $slug ) {
		$page = get_page_by_path( $slug );

		if ( ! $page instanceof WP_Post || 'publish' !== $page->post_status ) {
			$done[ $slug ] = 'missing';
			continue;
		}

		$content = preg_replace(
			'#<!-- wp:list-item -->\s*<li><strong>Google Fonts\.?</strong>(?:(?!</li>).)*</li>\s*<!-- /wp:list-item -->\s*#s',
			'',
			$page->post_content,
			1,
			$count
		);

		if ( ! $count ) {
			$done[ $slug ] = 'not-found';
			continue;
		}

		$content = str_replace( 'Last updated: 19 November 2026', 'Last updated: 20 November 2026', $content );

		kses_remove_filters();
		$saved = wp_update_post(
			wp_slash(
				array(
					'ID'           => $page->ID,
					'post_content' => $content,
				)
			),
			true
		);
		kses_init();

		$done[ $slug ] = is_wp_error( $saved ) ? 'failed' : 'updated';
	}

	return $done;
}

/**
 * On job board pages the Modern Job Board plugin brings its own copy of
 * DM Sans (style handle mjb-dm-sans). Empty the theme's font stylesheet
 * there so the same font faces are not declared twice. The handle stays
 * registered, so styles that depend on it still load.
 */
function mjb_theme_share_plugin_font() {
	$styles = wp_styles();

	if ( wp_style_is( 'mjb-dm-sans', 'enqueued' ) && isset( $styles->registered['mjb-theme-fonts'] ) ) {
		$styles->registered['mjb-theme-fonts']->src = false;
	}
}
add_action( 'wp_enqueue_scripts', 'mjb_theme_share_plugin_font', 100 );
