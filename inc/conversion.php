<?php
/**
 * One-off conversion of theme-generated content into editable blocks.
 *
 * Earlier versions generated three things in PHP at render time: the "Learn
 * More" buttons on the home feature cards, the tile grid and call-to-action on
 * the Features page, and the "More features" row and call-to-action on each
 * feature page. This writes them into the page content as blocks so they can
 * be edited, moved or removed in the block editor.
 *
 * Safety:
 * - Runs once, on the next page load of any kind (no login needed). HTML
 *   save filters are switched off for these writes and the stored result is
 *   compared with what was built, so inline SVG in the page is never stripped.
 * - Each page's previous content is kept in the `_mjb_content_before_blocks`
 *   post meta (and in the page's revisions).
 * - The home page is only touched if WordPress can parse and re-serialize it
 *   byte for byte, and the saved result is checked; otherwise it is left as it
 *   was and the buttons stay generated.
 *
 * @package ModernJobBoardTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Save new content for a page, keeping a backup and verifying the result.
 *
 * @param WP_Post $post    Page.
 * @param string  $content New content.
 * @return bool Whether the stored content now equals $content.
 */
function mjb_theme_convert_save( $post, $content ) {
	global $wpdb;

	$original = $post->post_content;

	if ( $content === $original ) {
		return true;
	}

	add_post_meta( $post->ID, '_mjb_content_before_blocks', wp_slash( $original ), true );

	// Save exactly what was built: no HTML filtering for this trusted, generated change.
	kses_remove_filters();
	$result = wp_update_post(
		wp_slash(
			array(
				'ID'           => $post->ID,
				'post_content' => $content,
			)
		),
		true
	);
	kses_init();

	clean_post_cache( $post->ID );
	$stored = get_post( $post->ID );

	if ( ! is_wp_error( $result ) && $stored instanceof WP_Post && $stored->post_content === $content ) {
		return true;
	}

	// Something altered the content on save: put the original back untouched.
	$wpdb->update( $wpdb->posts, array( 'post_content' => $original ), array( 'ID' => $post->ID ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	clean_post_cache( $post->ID );

	return false;
}

/**
 * Add a Learn More button block to every feature card in a parsed block tree.
 *
 * @param array[] $blocks Parsed blocks (by reference).
 * @return int Number of cards changed.
 */
function mjb_theme_convert_cards( &$blocks ) {
	$changed = 0;

	foreach ( $blocks as &$block ) {
		$name = isset( $block['blockName'] ) ? $block['blockName'] : null;

		// Cards built as Group blocks: append a real Buttons block inside the card.
		if ( 'core/group' === $name && mjb_theme_block_has_class( $block, 'feature-card' ) ) {
			if ( false !== strpos( serialize_block( $block ), 'feature-link' ) ) {
				continue;
			}

			$title = '';

			foreach ( (array) $block['innerBlocks'] as $inner ) {
				if ( 'core/heading' === $inner['blockName'] ) {
					$title = mjb_theme_feature_plain_text( $inner['innerHTML'] );
					break;
				}
			}

			$slug   = mjb_theme_feature_slug( $title );
			$button = '' !== $slug ? parse_blocks( mjb_theme_learn_more_block( $slug ) ) : array();
			$last   = count( $block['innerContent'] ) - 1;

			if ( empty( $button[0]['blockName'] ) || $last < 1 || ! is_string( $block['innerContent'][ $last ] ) ) {
				continue;
			}

			$block['innerBlocks'][] = $button[0];
			array_splice( $block['innerContent'], $last, 0, array( "\n\n", null ) );
			++$changed;
			continue;
		}

		// Cards written as raw HTML (Custom HTML or Classic block): add a plain link.
		if ( ( 'core/html' === $name || null === $name || 'core/freeform' === $name ) && false !== strpos( $block['innerHTML'], 'feature-card' ) ) {
			$html = $block['innerHTML'];

			foreach ( array_reverse( mjb_theme_scan_feature_cards( $html ) ) as $card ) {
				if ( $card['has_link'] ) {
					continue;
				}

				$html = substr_replace( $html, mjb_theme_learn_more_link( $card['slug'], $card['title'] ), $card['close'], 0 );
				++$changed;
			}

			if ( $html !== $block['innerHTML'] && 1 === count( $block['innerContent'] ) ) {
				$block['innerHTML']    = $html;
				$block['innerContent'] = array( $html );
			}

			continue;
		}

		if ( ! empty( $block['innerBlocks'] ) ) {
			$changed += mjb_theme_convert_cards( $block['innerBlocks'] );
		}
	}

	return $changed;
}

/**
 * Run the conversion once.
 *
 * Marked as done before it starts so a parallel request cannot run it twice;
 * any error is caught, logged and recorded in the report rather than retried
 * on every page load.
 */
function mjb_theme_convert_to_blocks() {
	if ( get_option( 'mjb_theme_blocks_converted' ) || wp_installing() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	$report = array(
		'home'     => 'pending',
		'features' => 0,
		'pages'    => 0,
	);

	update_option( 'mjb_theme_blocks_converted', $report, false );

	try {
		$report = mjb_theme_run_block_conversion( $report );
	} catch ( Throwable $error ) {
		$report['error'] = $error->getMessage();
		error_log( 'MJB theme: block conversion stopped: ' . $error->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}

	update_option( 'mjb_theme_blocks_converted', $report, false );
}
add_action( 'init', 'mjb_theme_convert_to_blocks', 100 );

/**
 * The conversion steps.
 *
 * @param array $report Report so far.
 * @return array Report: home (converted-N, already-blocks, no-cards or skipped-*), features (0/1), pages (count).
 */
function mjb_theme_run_block_conversion( $report ) {
	$report['home'] = 'no-cards';

	// 1. Home page: Learn More buttons.
	$front_id = (int) get_option( 'page_on_front' );
	$front    = $front_id ? get_post( $front_id ) : null;

	if ( $front instanceof WP_Post ) {
		$blocks = parse_blocks( $front->post_content );

		if ( serialize_blocks( $blocks ) !== $front->post_content ) {
			$report['home'] = 'skipped-not-round-trip-safe';
		} else {
			$changed = mjb_theme_convert_cards( $blocks );

			if ( $changed > 0 ) {
				$report['home'] = mjb_theme_convert_save( $front, serialize_blocks( $blocks ) ) ? 'converted-' . $changed : 'skipped-save-altered-content';
			} elseif ( false !== strpos( $front->post_content, 'feature-link' ) ) {
				$report['home'] = 'already-blocks';
			}
		}
	}

	// Once the buttons are real blocks the theme stops adding them at render time.
	if ( 0 === strpos( $report['home'], 'converted' ) || 'already-blocks' === $report['home'] ) {
		update_option( 'mjb_theme_home_links', 'blocks' );
	}

	// 2. Features page: tile grid + call to action.
	$features = mjb_theme_get_features_page();

	if ( ! $features ) {
		return $report;
	}

	if ( ! has_block( 'mjb/feature-grid', $features ) ) {
		$content = trim( $features->post_content );
		$content = ( '' !== $content ? $content . "\n\n" : '' ) . mjb_theme_features_index_blocks();

		if ( mjb_theme_convert_save( $features, $content ) ) {
			$report['features'] = 1;
		}
	}

	// 3. Feature pages: More features + call to action.
	$children = get_posts(
		array(
			'post_type'   => 'page',
			'post_parent' => $features->ID,
			'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future' ),
			'numberposts' => -1,
		)
	);

	foreach ( $children as $child ) {
		if ( has_block( 'mjb/feature-grid', $child ) ) {
			continue;
		}

		$content = trim( $child->post_content );
		$content = ( '' !== $content ? $content . "\n\n" : '' ) . mjb_theme_feature_page_blocks();

		if ( mjb_theme_convert_save( $child, $content ) ) {
			++$report['pages'];
		}
	}

	return $report;
}

/**
 * One-off: add the hero (heading, text, two buttons and an illustration) to the
 * top of every feature page that does not have one yet. Runs after the block
 * conversion above, under the same conditions, and keeps the previous content
 * in each page's revisions.
 */
function mjb_theme_seed_feature_heroes() {
	if ( false !== get_option( 'mjb_theme_feature_heroes' ) || wp_installing() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	$features_page = mjb_theme_get_features_page();

	if ( ! $features_page ) {
		return;
	}

	update_option( 'mjb_theme_feature_heroes', 0, false );

	$added = 0;
	$pages = get_pages(
		array(
			'parent'      => $features_page->ID,
			'post_status' => 'publish,draft,private,pending',
		)
	);

	try {
		foreach ( (array) $pages as $page ) {
			if ( false !== strpos( $page->post_content, 'mjb-feature-hero' ) ) {
				continue;
			}

			$title   = trim( html_entity_decode( wp_strip_all_tags( $page->post_title ), ENT_QUOTES, 'UTF-8' ) );
			$hero    = mjb_theme_feature_hero_blocks( $page->post_name, $title, (string) $page->post_excerpt );
			$content = '' !== trim( $page->post_content ) ? $hero . "\n\n" . $page->post_content : $hero;

			if ( mjb_theme_convert_save( $page, $content ) ) {
				++$added;
			}
		}
	} catch ( Throwable $error ) {
		error_log( 'MJB theme: adding feature heroes stopped: ' . $error->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}

	update_option( 'mjb_theme_feature_heroes', $added, false );
	update_option( 'mjb_theme_feature_heroes_notice', $added, false );
}
add_action( 'init', 'mjb_theme_seed_feature_heroes', 110 );

/**
 * Tell administrators once that the heroes were added.
 */
function mjb_theme_feature_heroes_notice() {
	$added = get_option( 'mjb_theme_feature_heroes_notice' );

	if ( false === $added || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	delete_option( 'mjb_theme_feature_heroes_notice' );

	echo '<div class="notice notice-info is-dismissible"><p>' . esc_html(
		sprintf(
			/* translators: %d: number of pages */
			__( 'Modern Job Board theme: a hero with a heading, text, two buttons and an illustration was added to the top of %d feature pages. Edit it as the first block of each page; previous content is in each page\'s revisions.', 'modern-job-board-theme' ),
			(int) $added
		)
	) . '</p></div>';
}
add_action( 'admin_notices', 'mjb_theme_feature_heroes_notice' );

/**
 * Insert body content into a page, after the hero (or before the given
 * fallback marker, or at the start).
 *
 * @param string $content Page content.
 * @param string $body    Blocks to insert.
 * @return string
 */
function mjb_theme_insert_after_hero( $content, $body ) {
	$hero = strpos( $content, 'mjb-feature-hero' );

	if ( false !== $hero ) {
		$end = strpos( $content, '<!-- /wp:columns -->', $hero );

		if ( false !== $end ) {
			$end += strlen( '<!-- /wp:columns -->' );

			return substr( $content, 0, $end ) . "\n\n" . $body . substr( $content, $end );
		}
	}

	$more = strpos( $content, '<!-- wp:group {"className":"mjb-feature-more"' );

	if ( false !== $more ) {
		return substr( $content, 0, $more ) . $body . "\n\n" . substr( $content, $more );
	}

	return $body . "\n\n" . $content;
}

/**
 * One-off: add the body content (overview, how it works, what is included,
 * plans and FAQ) below the hero of every feature page, and an overview, a
 * plans table and an FAQ around the grid on the Features page. Pages that
 * already have it, and feature pages without written copy, are left alone.
 * Previous content stays in each page's revisions.
 */
function mjb_theme_seed_feature_bodies() {
	if ( false !== get_option( 'mjb_theme_feature_bodies' ) || wp_installing() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	$features_page = mjb_theme_get_features_page();

	if ( ! $features_page ) {
		return;
	}

	update_option( 'mjb_theme_feature_bodies', 0, false );

	$added = 0;

	try {
		// The Features page: overview before the grid, plans and FAQ after it.
		if ( false === strpos( $features_page->post_content, 'mjb-feature-body' ) ) {
			$content = $features_page->post_content;
			$intro   = mjb_theme_features_index_intro_blocks();
			$after   = mjb_theme_features_index_after_blocks();

			if ( preg_match( '/<!-- wp:mjb\/feature-grid(?:\s+\{[^>]*?\})?\s*\/-->/', $content, $m, PREG_OFFSET_CAPTURE ) ) {
				$start   = $m[0][1];
				$end     = $start + strlen( $m[0][0] );
				$content = substr( $content, 0, $start ) . $intro . "\n\n" . $m[0][0] . "\n\n" . $after . substr( $content, $end );
			} else {
				$ref     = strpos( $content, '<!-- wp:block {"ref":' );
				$content = false !== $ref
					? $intro . "\n\n" . substr( $content, 0, $ref ) . $after . "\n\n" . substr( $content, $ref )
					: $intro . "\n\n" . $content . "\n\n" . $after;
			}

			if ( mjb_theme_convert_save( $features_page, $content ) ) {
				++$added;
			}
		}

		$pages = get_pages(
			array(
				'parent'      => $features_page->ID,
				'post_status' => 'publish,draft,private,pending',
			)
		);

		foreach ( (array) $pages as $page ) {
			if ( false !== strpos( $page->post_content, 'mjb-feature-body' ) ) {
				continue;
			}

			$body = mjb_theme_feature_body_blocks( $page->post_name );

			if ( '' === $body ) {
				continue;
			}

			if ( mjb_theme_convert_save( $page, mjb_theme_insert_after_hero( $page->post_content, $body ) ) ) {
				++$added;
			}
		}
	} catch ( Throwable $error ) {
		error_log( 'MJB theme: adding feature page content stopped: ' . $error->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}

	update_option( 'mjb_theme_feature_bodies', $added, false );
	update_option( 'mjb_theme_feature_bodies_notice', $added, false );
}
add_action( 'init', 'mjb_theme_seed_feature_bodies', 120 );

/**
 * Tell administrators once that the body content was added.
 */
function mjb_theme_feature_bodies_notice() {
	$added = get_option( 'mjb_theme_feature_bodies_notice' );

	if ( false === $added || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	delete_option( 'mjb_theme_feature_bodies_notice' );

	echo '<div class="notice notice-info is-dismissible"><p>' . esc_html(
		sprintf(
			/* translators: %d: number of pages */
			__( 'Modern Job Board theme: body content (overview, how it works, what is included, plans and FAQ) was added to %d pages under Features. Edit it in the block editor; previous content is in each page\'s revisions.', 'modern-job-board-theme' ),
			(int) $added
		)
	) . '</p></div>';
}
add_action( 'admin_notices', 'mjb_theme_feature_bodies_notice' );

/**
 * One-off (0.9.0-beta.58): replace the first version of the body content on
 * the feature pages and the Features page with the rewritten one (unique
 * copy per page, real screenshots, icon cards), and remove the "Back to all
 * features" link from every feature page.
 *
 * A page's body is only replaced while it is still exactly what the theme
 * wrote; anything edited by hand is left alone and named in the notice.
 * Previous content stays in each page's revisions.
 */
function mjb_theme_upgrade_feature_bodies() {
	if ( false !== get_option( 'mjb_theme_feature_bodies_v2' ) || wp_installing() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	// Wait for the first seeding: it now writes the new content itself.
	if ( false === get_option( 'mjb_theme_feature_bodies' ) ) {
		return;
	}

	$features_page = mjb_theme_get_features_page();

	if ( ! $features_page ) {
		return;
	}

	update_option( 'mjb_theme_feature_bodies_v2', 0, false );

	$report = array(
		'updated' => 0,
		'links'   => 0,
		'skipped' => array(),
	);

	try {
		require_once __DIR__ . '/feature-content-v1.php';

		// The Features page: the answer before the grid, plans and FAQ after it.
		$content = $features_page->post_content;
		$swaps   = array(
			array( mjb_theme_v1_features_index_intro_blocks(), mjb_theme_features_index_intro_blocks() ),
			array( mjb_theme_v1_features_index_after_blocks(), mjb_theme_features_index_after_blocks() ),
		);

		foreach ( $swaps as $swap ) {
			if ( '' !== $swap[0] && '' !== $swap[1] && false !== strpos( $content, $swap[0] ) ) {
				$content = str_replace( $swap[0], $swap[1], $content );
			} elseif ( false !== strpos( $content, 'mjb-feature-body' ) && false === strpos( $content, $swap[1] ) ) {
				$report['skipped'][ $features_page->ID ] = get_the_title( $features_page );
			}
		}

		if ( $content !== $features_page->post_content && mjb_theme_convert_save( $features_page, $content ) ) {
			++$report['updated'];
		}

		$pages = get_pages(
			array(
				'parent'      => $features_page->ID,
				'post_status' => 'publish,draft,private,pending',
			)
		);

		foreach ( (array) $pages as $page ) {
			$content = $page->post_content;
			$old     = mjb_theme_v1_feature_body_blocks( $page->post_name );
			$new     = mjb_theme_feature_body_blocks( $page->post_name );
			$changed = false;

			if ( '' !== $old && '' !== $new && false !== strpos( $content, $old ) ) {
				$content = str_replace( $old, $new, $content );
				$changed = true;
			} elseif ( '' !== $new && false !== strpos( $content, 'mjb-feature-body' ) && false === strpos( $content, $new ) ) {
				$report['skipped'][ $page->ID ] = get_the_title( $page );
			}

			// The "Back to all features" paragraph, and the row it sat in.
			$without = preg_replace( '/\s*<!-- wp:paragraph(?: \{[^}]*\})? -->\s*<p[^>]*>\s*<a [^>]*>\s*Back to all features\s*<\/a>\s*<\/p>\s*<!-- \/wp:paragraph -->/i', '', $content, -1, $removed );

			if ( null !== $without && $removed > 0 ) {
				$content          = ltrim( $without );
				$report['links'] += $removed;
			}

			if ( $content !== $page->post_content && mjb_theme_convert_save( $page, $content ) && $changed ) {
				++$report['updated'];
			}
		}
	} catch ( Throwable $error ) {
		error_log( 'MJB theme: updating feature page content stopped: ' . $error->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}

	update_option( 'mjb_theme_feature_bodies_v2', $report['updated'], false );
	update_option( 'mjb_theme_feature_bodies_v2_notice', $report, false );
}
add_action( 'init', 'mjb_theme_upgrade_feature_bodies', 125 );

/**
 * Tell administrators once what the content update changed and what it left.
 */
function mjb_theme_feature_bodies_v2_notice() {
	$report = get_option( 'mjb_theme_feature_bodies_v2_notice' );

	if ( ! is_array( $report ) || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	delete_option( 'mjb_theme_feature_bodies_v2_notice' );

	$message = sprintf(
		/* translators: 1: number of pages, 2: number of links */
		__( 'Modern Job Board theme: the body content of %1$d pages under Features was rewritten, with screenshots and icon cards, and the "Back to all features" link was removed from %2$d pages. Previous content is in each page\'s revisions.', 'modern-job-board-theme' ),
		isset( $report['updated'] ) ? (int) $report['updated'] : 0,
		isset( $report['links'] ) ? (int) $report['links'] : 0
	);

	if ( ! empty( $report['skipped'] ) ) {
		$message .= ' ' . sprintf(
			/* translators: %s: page titles */
			__( 'Left as they are, because their content had been edited: %s.', 'modern-job-board-theme' ),
			implode( ', ', array_map( 'wp_strip_all_tags', (array) $report['skipped'] ) )
		);
	}

	echo '<div class="notice notice-info is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
}
add_action( 'admin_notices', 'mjb_theme_feature_bodies_v2_notice' );

/**
 * One-off (0.9.0-beta.59): 0.9.0-beta.58 wrote shortcodes quoted in the
 * feature page copy as [[mjb_example]]. has_shortcode() counts those as real
 * shortcodes, so the theme and the plugin took those pages for job board
 * pages and gave them the wrong header and layout. Rewrite each one the way
 * mjb_theme_quote_shortcodes() does. Only those tokens are touched, so pages
 * edited by hand are safe.
 */
function mjb_theme_fix_quoted_shortcodes() {
	if ( false !== get_option( 'mjb_theme_quoted_shortcodes' ) || wp_installing() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	$features_page = mjb_theme_get_features_page();

	if ( ! $features_page ) {
		return;
	}

	update_option( 'mjb_theme_quoted_shortcodes', 0, false );

	$fixed = 0;

	try {
		$pages = (array) get_pages(
			array(
				'parent'      => $features_page->ID,
				'post_status' => 'publish,draft,private,pending',
			)
		);

		array_unshift( $pages, $features_page );

		foreach ( $pages as $page ) {
			$content = mjb_theme_quote_shortcodes( $page->post_content );

			if ( is_string( $content ) && $content !== $page->post_content && mjb_theme_convert_save( $page, $content ) ) {
				++$fixed;
			}
		}
	} catch ( Throwable $error ) {
		error_log( 'MJB theme: fixing quoted shortcodes stopped: ' . $error->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}

	update_option( 'mjb_theme_quoted_shortcodes', $fixed, false );
}
add_action( 'init', 'mjb_theme_fix_quoted_shortcodes', 126 );

/**
 * One-off (0.9.0-beta.63): put the version back in the badge at the top of the
 * home page. 0.9.0-beta.62 removed it (option mjb_theme_home_badge_version = 1);
 * this re-inserts exactly what was removed, " • v0.9.0-beta.104", after
 * "WordPress Plugin" in the badge paragraph. Runs only where beta.62 changed the
 * page, and only if the badge still has no version.
 */
function mjb_theme_restore_home_badge_version() {
	if ( false !== get_option( 'mjb_theme_home_badge_restored' ) || wp_installing() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	update_option( 'mjb_theme_home_badge_restored', 0, false );

	if ( '1' !== (string) get_option( 'mjb_theme_home_badge_version' ) ) {
		return;
	}

	$restored = 0;

	try {
		$front_id = (int) get_option( 'page_on_front' );
		$front    = $front_id > 0 ? get_post( $front_id ) : null;

		if ( $front instanceof WP_Post ) {
			$content = preg_replace(
				'/(<p class="badge"[^>]*>(?:(?!<\/p>).)*?WordPress Plugin)(?!\s*(?:\x{2022}|&bull;|&#8226;))/su',
				'$1 ' . "\u{2022}" . ' v0.9.0-beta.104',
				$front->post_content,
				1
			);

			if ( is_string( $content ) && $content !== $front->post_content && mjb_theme_convert_save( $front, $content ) ) {
				$restored = 1;
			}
		}
	} catch ( Throwable $error ) {
		error_log( 'MJB theme: restoring the home badge version stopped: ' . $error->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}

	update_option( 'mjb_theme_home_badge_restored', $restored, false );
}
add_action( 'init', 'mjb_theme_restore_home_badge_version', 127 );

/**
 * One-off (0.9.0-beta.67): the same restore for a badge that is any element
 * with the class "badge" (on mjb.local it is a <div> in a Custom HTML block,
 * which beta.63 did not look for). Re-inserts " • v0.9.0-beta.104" after
 * "WordPress Plugin", exactly what beta.62 removed; the number shown is then
 * kept current by mjb_theme_badge_plugin_version(). Only where beta.62 changed
 * the page and the badge still has no version.
 */
function mjb_theme_restore_home_badge_version_any() {
	if ( false !== get_option( 'mjb_theme_home_badge_restored_any' ) || wp_installing() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	update_option( 'mjb_theme_home_badge_restored_any', 0, false );

	if ( '1' !== (string) get_option( 'mjb_theme_home_badge_version' ) || '1' === (string) get_option( 'mjb_theme_home_badge_restored' ) ) {
		return;
	}

	$restored = 0;

	try {
		$front_id = (int) get_option( 'page_on_front' );
		$front    = $front_id > 0 ? get_post( $front_id ) : null;

		if ( $front instanceof WP_Post ) {
			$content = preg_replace(
				'/(<(?:p|div|span)\b[^>]*\bclass="[^"]*\bbadge\b[^"]*"[^>]*>(?:(?!<\/(?:p|div)>).)*?WordPress Plugin)(?!\s*(?:\x{2022}|&bull;|&#8226;))/su',
				'$1 ' . "\u{2022}" . ' v0.9.0-beta.104',
				$front->post_content,
				1
			);

			if ( is_string( $content ) && $content !== $front->post_content && mjb_theme_convert_save( $front, $content ) ) {
				$restored = 1;
			}
		}
	} catch ( Throwable $error ) {
		error_log( 'MJB theme: restoring the home badge version stopped: ' . $error->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}

	update_option( 'mjb_theme_home_badge_restored_any', $restored, false );
}
add_action( 'init', 'mjb_theme_restore_home_badge_version_any', 129 );

/**
 * One-off (0.9.0-beta.65): show each feature page screenshot as a desktop
 * screenshot in a browser window with the phone screenshot on top of it
 * (mjb_theme_b_devices()). Each screenshot Image block is replaced only while
 * it is still exactly what the theme wrote; edited ones are left alone and
 * named in a one-time notice. Previous content stays in the revisions.
 */
function mjb_theme_upgrade_feature_devices() {
	if ( false !== get_option( 'mjb_theme_feature_devices' ) || wp_installing() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	$features_page = mjb_theme_get_features_page();

	if ( ! $features_page || ! function_exists( 'mjb_theme_b_devices' ) ) {
		return;
	}

	update_option( 'mjb_theme_feature_devices', 0, false );

	$data    = mjb_theme_feature_content_data();
	$updated = 0;
	$skipped = array();

	try {
		$pages = get_pages(
			array(
				'parent'      => $features_page->ID,
				'post_status' => 'publish,draft,private,pending',
			)
		);

		foreach ( (array) $pages as $page ) {
			if ( empty( $data['pages'][ $page->post_name ] ) ) {
				continue;
			}

			$content = $page->post_content;

			foreach ( (array) $data['pages'][ $page->post_name ] as $section ) {
				if ( empty( $section['t'] ) || 'shot' !== $section['t'] || empty( $section['devices'] ) ) {
					continue;
				}

				$old = mjb_theme_quote_shortcodes( mjb_theme_body_links( mjb_theme_b_shot( $section['img'], $section['alt'], $section['cap'], isset( $section['w'] ) ? (int) $section['w'] : 0 ) ) );
				$new = mjb_theme_quote_shortcodes( mjb_theme_body_links( mjb_theme_b_devices( $section['img'], $section['dalt'], $section['malt'], $section['cap'] ) ) );

				if ( false !== strpos( $content, $old ) ) {
					$content = str_replace( $old, $new, $content );
				} elseif ( false === strpos( $content, 'mjb-devices__desktop' ) ) {
					$skipped[ $page->ID ] = get_the_title( $page );
				}
			}

			if ( $content !== $page->post_content && mjb_theme_convert_save( $page, $content ) ) {
				++$updated;
			}
		}
	} catch ( Throwable $error ) {
		error_log( 'MJB theme: device screenshots update stopped: ' . $error->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}

	update_option( 'mjb_theme_feature_devices', $updated, false );

	if ( ! empty( $skipped ) ) {
		update_option( 'mjb_theme_feature_devices_notice', $skipped, false );
	}
}
add_action( 'init', 'mjb_theme_upgrade_feature_devices', 128 );

/**
 * Name, once, the feature pages whose screenshot was edited by hand and so
 * was not changed to the device layout.
 */
function mjb_theme_feature_devices_notice() {
	$skipped = get_option( 'mjb_theme_feature_devices_notice' );

	if ( ! is_array( $skipped ) || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	delete_option( 'mjb_theme_feature_devices_notice' );

	echo '<div class="notice notice-info is-dismissible"><p>' . esc_html(
		sprintf(
			/* translators: %s: page titles */
			__( 'Modern Job Board theme: these feature pages kept their screenshot as it was, because it had been edited: %s. Insert the "Screenshot on desktop and phone" pattern to use the new layout there.', 'modern-job-board-theme' ),
			implode( ', ', array_map( 'wp_strip_all_tags', $skipped ) )
		)
	) . '</p></div>';
}
add_action( 'admin_notices', 'mjb_theme_feature_devices_notice' );

/**
 * Show the result of the conversion once, to administrators, so it is clear
 * what was changed (and what was left alone) without checking each page.
 */
function mjb_theme_conversion_notice() {
	$report = get_option( 'mjb_theme_blocks_converted' );

	if ( ! is_array( $report ) || get_option( 'mjb_theme_blocks_notice_seen' ) || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	update_option( 'mjb_theme_blocks_notice_seen', 1, false );

	$home = isset( $report['home'] ) ? (string) $report['home'] : '';

	if ( 0 === strpos( $home, 'converted-' ) ) {
		/* translators: %d: number of cards */
		$home_text = sprintf( __( 'Home page: %d "Learn More" buttons are now blocks inside the feature cards.', 'modern-job-board-theme' ), (int) substr( $home, 10 ) );
	} elseif ( 'already-blocks' === $home ) {
		$home_text = __( 'Home page: the "Learn More" buttons were already part of the page content.', 'modern-job-board-theme' );
	} elseif ( 'no-cards' === $home ) {
		$home_text = __( 'Home page: no feature cards were found, so nothing was changed.', 'modern-job-board-theme' );
	} else {
		$home_text = __( 'Home page: left unchanged because its content could not be rewritten safely; the "Learn More" buttons are still added by the theme.', 'modern-job-board-theme' );
	}

	$lines = array(
		$home_text,
		! empty( $report['features'] )
			? __( 'Features page: the tile grid and call-to-action band are now blocks.', 'modern-job-board-theme' )
			: __( 'Features page: not changed.', 'modern-job-board-theme' ),
		/* translators: %d: number of pages */
		sprintf( __( 'Feature pages converted: %d.', 'modern-job-board-theme' ), isset( $report['pages'] ) ? (int) $report['pages'] : 0 ),
	);

	if ( ! empty( $report['error'] ) ) {
		$lines[] = __( 'The conversion stopped early with an error:', 'modern-job-board-theme' ) . ' ' . $report['error'];
	}

	echo '<div class="notice notice-info is-dismissible"><p><strong>' . esc_html__( 'Modern Job Board theme: generated content converted to blocks', 'modern-job-board-theme' ) . '</strong></p><ul style="list-style:disc;margin-left:1.5em">';

	foreach ( $lines as $line ) {
		echo '<li>' . esc_html( $line ) . '</li>';
	}

	echo '</ul><p>' . esc_html__( 'Each page keeps its previous content in its revisions. Menus are under Appearance → Menus.', 'modern-job-board-theme' ) . '</p></div>';
}
add_action( 'admin_notices', 'mjb_theme_conversion_notice' );
