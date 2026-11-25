<?php
/**
 * Feature pages.
 *
 * 1. Adds a "Learn More" link to every feature card on the front page, pointing
 *    at /features/{slug}/ where {slug} is the card title run through
 *    sanitize_title() ("SEO & Google for Jobs" => "seo-google-for-jobs").
 * 2. Creates the pages those links point to: a "Features" parent page and one
 *    child page per card, seeded with editable blocks (inc/blocks.php). A page
 *    is created once per card; existing pages are never modified, and a page
 *    you delete is not recreated. New cards get their page the next time the
 *    front page is viewed.
 *
 * Both work from the rendered front page HTML (any element with the
 * "feature-card" class), so it does not matter whether the cards are Group
 * blocks, a Custom HTML block, a pattern or a shortcode.
 *
 * @package ModernJobBoardTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * URL slug for a feature card title.
 *
 * @param string $title Card title (may contain markup or entities).
 * @return string
 */
function mjb_theme_feature_slug( $title ) {
	return sanitize_title( mjb_theme_feature_plain_text( $title ) );
}

/**
 * Plain text from a fragment of block markup.
 *
 * @param string $html Markup.
 * @return string
 */
function mjb_theme_feature_plain_text( $html ) {
	$text = html_entity_decode( wp_strip_all_tags( (string) $html ), ENT_QUOTES, 'UTF-8' );

	return trim( preg_replace( '/\s+/', ' ', $text ) );
}

/**
 * Find feature cards in rendered HTML.
 *
 * @param string $html Rendered markup.
 * @return array[] Cards in document order: title, description, slug, icon (inline SVG), close (offset of the card's closing tag).
 */
function mjb_theme_scan_feature_cards( $html ) {
	$cards = array();

	if ( false === strpos( $html, 'feature-card' ) ) {
		return $cards;
	}

	if ( ! preg_match_all( '/<div\b[^>]*\bclass\s*=\s*(["\'])([^"\']*)\1[^>]*>/i', $html, $opens, PREG_OFFSET_CAPTURE | PREG_SET_ORDER ) ) {
		return $cards;
	}

	$skip_until = 0;

	foreach ( $opens as $open ) {
		$start = $open[0][1];

		if ( $start < $skip_until || ! in_array( 'feature-card', preg_split( '/\s+/', trim( $open[2][0] ) ), true ) ) {
			continue;
		}

		// Walk forward to the matching closing tag.
		$offset = $start + strlen( $open[0][0] );
		$depth  = 1;
		$close  = false;

		while ( $depth > 0 && preg_match( '/<div\b|<\/div\s*>/i', $html, $tag, PREG_OFFSET_CAPTURE, $offset ) ) {
			if ( '/' === $tag[0][0][1] ) {
				--$depth;
				$close = $tag[0][1];
			} else {
				++$depth;
			}

			$offset = $tag[0][1] + strlen( $tag[0][0] );
		}

		if ( $depth > 0 || false === $close ) {
			continue;
		}

		$skip_until = $close;
		$inner      = substr( $html, $start, $close - $start );

		if ( ! preg_match( '/<h([2-4])\b[^>]*>(.*?)<\/h\1\s*>/is', $inner, $heading ) ) {
			continue;
		}

		$title = mjb_theme_feature_plain_text( $heading[2] );
		$slug  = mjb_theme_feature_slug( $title );

		if ( '' === $slug ) {
			continue;
		}

		$description = preg_match( '/<p\b[^>]*>(.*?)<\/p\s*>/is', $inner, $paragraph ) ? mjb_theme_feature_plain_text( $paragraph[1] ) : '';

		$cards[] = array(
			'title'       => $title,
			'description' => $description,
			'slug'        => $slug,
			'icon'        => preg_match( '/<svg\b.*?<\/svg>/is', $inner, $svg ) ? $svg[0] : '',
			'close'       => $close,
			'has_link'    => false !== strpos( $inner, 'feature-link' ),
		);
	}

	return $cards;
}

/**
 * Feature cards (slug, title, description, icon) in front page order.
 *
 * Recorded whenever the front page is rendered. If nothing is recorded yet,
 * the front page content is rendered here once so menus never start empty.
 *
 * @return array[]
 */
function mjb_theme_get_feature_cards() {
	$cards = get_option( 'mjb_theme_feature_cards', null );

	if ( is_array( $cards ) && ! empty( $cards ) ) {
		return mjb_theme_feature_cards_text( $cards );
	}

	static $built = null;

	if ( null !== $built ) {
		return $built;
	}

	$built    = array();
	$front_id = (int) get_option( 'page_on_front' );
	$front    = $front_id ? get_post( $front_id ) : null;

	if ( $front instanceof WP_Post ) {
		$seen = array();

		foreach ( mjb_theme_scan_feature_cards( do_shortcode( do_blocks( $front->post_content ) ) ) as $card ) {
			if ( isset( $seen[ $card['slug'] ] ) ) {
				continue;
			}

			$seen[ $card['slug'] ] = true;
			$built[]               = array(
				'slug'        => $card['slug'],
				'title'       => $card['title'],
				'description' => $card['description'],
				'icon'        => $card['icon'],
			);
		}

		if ( ! empty( $built ) ) {
			update_option( 'mjb_theme_feature_cards', $built );
		}
	}

	return mjb_theme_feature_cards_text( $built );
}

/**
 * Card titles and descriptions with [mjb_doc] rendered (CV or Resume).
 *
 * @param array[] $cards Cards.
 * @return array[]
 */
function mjb_theme_feature_cards_text( $cards ) {
	foreach ( $cards as $index => $card ) {
		foreach ( array( 'title', 'description' ) as $key ) {
			if ( isset( $card[ $key ] ) ) {
				$cards[ $index ][ $key ] = mjb_theme_doc_text( $card[ $key ] );
			}
		}
	}

	return $cards;
}

/**
 * Inline SVG for a feature icon, or a generic icon when none is usable.
 *
 * Icons are the site's own front page markup; anything that is not a plain
 * inline SVG falls back to the default.
 *
 * @param string $icon Recorded icon markup.
 * @return string
 */
function mjb_theme_feature_icon( $icon ) {
	$icon = (string) $icon;

	if ( '' === $icon || ! preg_match( '/^<svg\b[^>]*>.*<\/svg>$/is', $icon ) || preg_match( '/<script|<foreignObject|\son\w+\s*=|javascript:/i', $icon ) ) {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 17 2 2 4-4"/><path d="m3 7 2 2 4-4"/><path d="M13 6h8"/><path d="M13 12h8"/><path d="M13 18h8"/></svg>';
	}

	return $icon;
}

/**
 * The /features/ parent page, if it exists.
 *
 * @return WP_Post|null
 */
function mjb_theme_get_features_page() {
	static $page = false;

	if ( false === $page ) {
		$found = get_page_by_path( 'features' );
		$page  = $found instanceof WP_Post ? $found : null;
	}

	return $page;
}

/**
 * Feature tiles (icon, title, description, Learn More) for the Features page
 * and the "More features" row on individual feature pages.
 *
 * @param array[] $cards   Feature cards.
 * @param string  $heading Heading tag for tile titles.
 * @return string
 */
function mjb_theme_feature_tiles( $cards, $heading = 'h2' ) {
	$arrow = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>';
	$html  = '<div class="mjb-feature-grid">';

	foreach ( $cards as $card ) {
		$url   = home_url( '/features/' . $card['slug'] . '/' );
		$html .= sprintf(
			'<article class="mjb-feature-tile"><span class="mjb-feature-tile__icon" aria-hidden="true">%1$s</span><%2$s class="mjb-feature-tile__title"><a href="%3$s">%4$s</a></%2$s>%5$s<a class="btn btn-outline btn-sm mjb-feature-tile__link" href="%3$s" aria-label="%6$s">%7$s%8$s</a></article>',
			mjb_theme_feature_icon( isset( $card['icon'] ) ? $card['icon'] : '' ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Checked inline SVG.
			'h3' === $heading ? 'h3' : 'h2',
			esc_url( $url ),
			esc_html( $card['title'] ),
			! empty( $card['description'] ) ? '<p class="mjb-feature-tile__text">' . esc_html( $card['description'] ) . '</p>' : '',
			/* translators: %s: feature name */
			esc_attr( sprintf( __( 'Learn more about %s', 'modern-job-board-theme' ), $card['title'] ) ),
			esc_html__( 'Learn More', 'modern-job-board-theme' ),
			$arrow
		);
	}

	return $html . '</div>';
}

/**
 * Call-to-action band shown under the feature tiles.
 *
 * @return string
 */
function mjb_theme_feature_cta() {
	return sprintf(
		'<div class="mjb-feature-cta"><div class="mjb-feature-cta__text"><h2 class="mjb-feature-cta__title">%1$s</h2><p>%2$s</p></div><div class="mjb-feature-cta__actions"><a class="btn btn-primary" href="%3$s">%4$s</a><a class="btn btn-outline" href="%5$s" target="_blank" rel="noopener noreferrer">%6$s</a></div></div>',
		esc_html__( 'Ready to run your own job board?', 'modern-job-board-theme' ),
		esc_html__( 'Start free on your own WordPress site, or try every feature in the live demo first.', 'modern-job-board-theme' ),
		esc_url( home_url( '/#pricing' ) ),
		esc_html__( 'Get Started Free', 'modern-job-board-theme' ),
		esc_url( home_url( '/jobs/' ) ),
		esc_html__( 'Live Demo', 'modern-job-board-theme' )
	);
}

/**
 * Fallback until the pages hold these as blocks (see inc/conversion.php).
 * Features page: the full grid of feature tiles. Individual feature pages: a
 * "More features" row. Both end with the call-to-action band.
 *
 * @param string $content Rendered post content.
 * @return string
 */
function mjb_theme_feature_pages_content( $content ) {
	// Superseded by blocks in the page content once inc/conversion.php has run.
	if ( get_option( 'mjb_theme_blocks_converted' ) || is_admin() || ! is_page() || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$features_page = mjb_theme_get_features_page();
	$post          = get_post();

	if ( ! $features_page || ! $post instanceof WP_Post || false !== strpos( $content, 'mjb-feature-grid' ) ) {
		return $content;
	}

	$cards = mjb_theme_get_feature_cards();

	if ( empty( $cards ) ) {
		return $content;
	}

	if ( (int) $post->ID === (int) $features_page->ID ) {
		return $content . '<div class="mjb-feature-index">' . mjb_theme_feature_tiles( $cards, 'h2' ) . mjb_theme_feature_cta() . '</div>';
	}

	if ( (int) $post->post_parent !== (int) $features_page->ID ) {
		return $content;
	}

	// The next three features after this one, wrapping round.
	$slugs  = array_column( $cards, 'slug' );
	$index  = array_search( $post->post_name, $slugs, true );
	$others = array();
	$count  = count( $cards );

	for ( $i = 1; $i < $count && count( $others ) < 3; $i++ ) {
		$others[] = $cards[ ( ( false === $index ? -1 : $index ) + $i ) % $count ];
	}

	if ( empty( $others ) ) {
		return $content;
	}

	$more = sprintf(
		'<section class="mjb-feature-more"><div class="mjb-feature-more__head"><h2 class="mjb-feature-more__title">%1$s</h2><a class="mjb-feature-more__all" href="%2$s">%3$s</a></div>%4$s%5$s</section>',
		esc_html__( 'More features', 'modern-job-board-theme' ),
		esc_url( get_permalink( $features_page ) ),
		esc_html__( 'All features', 'modern-job-board-theme' ),
		mjb_theme_feature_tiles( $others, 'h3' ),
		mjb_theme_feature_cta()
	);

	return $content . $more;
}
add_filter( 'the_content', 'mjb_theme_feature_pages_content', 20 );

/**
 * One-off: the Features page was first created with a plain list of links as
 * its content. The page now renders the tile grid itself, so clear that list
 * (only if it is still exactly the generated list) and give the page an intro.
 */
function mjb_theme_upgrade_features_page() {
	if ( get_option( 'mjb_theme_features_page_upgraded' ) || wp_installing() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	$page = mjb_theme_get_features_page();

	if ( ! $page ) {
		return;
	}

	update_option( 'mjb_theme_features_page_upgraded', 1, false );

	$generated = '/^\s*<!-- wp:list -->\s*<ul class="wp-block-list">(\s*<!-- wp:list-item -->\s*<li><a href="[^"]*\/features\/[^"]*">[^<]*<\/a><\/li>\s*<!-- \/wp:list-item -->)+\s*<\/ul>\s*<!-- \/wp:list -->\s*$/';

	if ( ! preg_match( $generated, $page->post_content ) ) {
		return;
	}

	$update = array(
		'ID'           => $page->ID,
		'post_content' => '',
	);

	if ( '' === trim( (string) $page->post_excerpt ) ) {
		$update['post_excerpt'] = __( 'Everything Modern Job Board does, feature by feature.', 'modern-job-board-theme' );
	}

	wp_update_post( wp_slash( $update ) );
}
add_action( 'init', 'mjb_theme_upgrade_features_page', 99 );

/**
 * Plain "Learn More" link for a feature card (used when a card is raw HTML, or
 * before the buttons have been converted to blocks).
 *
 * @param string $slug  Feature slug.
 * @param string $title Feature title.
 * @return string
 */
function mjb_theme_learn_more_link( $slug, $title ) {
	return sprintf(
		'<a class="btn btn-outline btn-sm feature-link" href="%1$s" aria-label="%2$s">%3$s<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></a>',
		esc_url( home_url( '/features/' . $slug . '/' ) ),
		/* translators: %s: feature name */
		esc_attr( sprintf( __( 'Learn more about %s', 'modern-job-board-theme' ), $title ) ),
		esc_html__( 'Learn More', 'modern-job-board-theme' )
	);
}

/**
 * Record the front page feature cards and queue page creation for any card
 * that does not have a page yet. Until the Learn More buttons have been
 * converted to blocks, also add them to the cards here.
 *
 * @param string $content Rendered post content.
 * @return string
 */
function mjb_theme_feature_card_links( $content ) {
	if ( is_admin() || ! is_front_page() || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$cards = mjb_theme_scan_feature_cards( $content );

	if ( empty( $cards ) ) {
		return $content;
	}

	$features = array();
	$menu     = array();

	foreach ( $cards as $card ) {
		if ( ! isset( $features[ $card['slug'] ] ) ) {
			$features[ $card['slug'] ] = array(
				'title'       => $card['title'],
				'description' => $card['description'],
			);

			$menu[] = array(
				'slug'        => $card['slug'],
				'title'       => $card['title'],
				'description' => $card['description'],
				'icon'        => $card['icon'],
			);
		}
	}

	// Card list for the Features mega menu (inc/navigation.php), refreshed when the cards change.
	if ( get_option( 'mjb_theme_feature_cards' ) !== $menu ) {
		update_option( 'mjb_theme_feature_cards', $menu );
	}

	// Once the buttons are real blocks in the page (inc/conversion.php), nothing is added here:
	// a card without a button is then a choice made in the editor.
	$generate_links = 'blocks' !== get_option( 'mjb_theme_home_links' );

	// Insert from the last card backwards so earlier offsets stay valid.
	foreach ( array_reverse( $cards ) as $card ) {
		if ( ! $generate_links || $card['has_link'] ) {
			continue;
		}

		$content = substr_replace( $content, mjb_theme_learn_more_link( $card['slug'], $card['title'] ), $card['close'], 0 );
	}

	mjb_theme_queue_feature_pages( $features, get_post() );

	return $content;
}
add_filter( 'the_content', 'mjb_theme_feature_card_links', 20 );

/**
 * Create missing feature pages once the response has been sent.
 *
 * @param array        $features Features keyed by slug.
 * @param WP_Post|null $front    Front page post (for the author).
 */
function mjb_theme_queue_feature_pages( $features, $front ) {
	static $queued = false;

	if ( $queued || empty( $features ) ) {
		return;
	}

	$state     = mjb_theme_feature_pages_state();
	$signature = md5( wp_json_encode( array_keys( $features ) ) );

	if ( $signature === $state['signature'] ) {
		return;
	}

	$queued = true;
	$author = $front instanceof WP_Post ? (int) $front->post_author : 0;

	add_action(
		'shutdown',
		static function () use ( $features, $signature, $author ) {
			mjb_theme_sync_feature_pages( $features, $signature, $author );
		}
	);
}

/**
 * Stored sync state.
 *
 * @return array{signature:string,slugs:string[]}
 */
function mjb_theme_feature_pages_state() {
	$state = get_option( 'mjb_theme_feature_pages', array() );

	return wp_parse_args(
		is_array( $state ) ? $state : array(),
		array(
			'signature' => '',
			'slugs'     => array(),
		)
	);
}

/**
 * Create the Features parent page and one child page per feature card.
 *
 * @param array  $features  Features keyed by slug.
 * @param string $signature Hash of the current card list.
 * @param int    $author    Author for new pages.
 */
function mjb_theme_sync_feature_pages( $features, $signature, $author ) {
	$state = mjb_theme_feature_pages_state();

	if ( $signature === $state['signature'] ) {
		return;
	}

	$pending = array_diff_key( $features, array_flip( (array) $state['slugs'] ) );

	// Record the run before inserting so a parallel request cannot duplicate pages.
	$state['signature'] = $signature;
	$state['slugs']     = array_values( array_unique( array_merge( (array) $state['slugs'], array_keys( $features ) ) ) );
	update_option( 'mjb_theme_feature_pages', $state, false );

	if ( empty( $pending ) ) {
		return;
	}

	$parent = get_page_by_path( 'features' );

	if ( $parent instanceof WP_Post ) {
		$parent_id = (int) $parent->ID;
	} else {
		$parent_id = wp_insert_post(
			wp_slash(
				array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_title'   => __( 'Features', 'modern-job-board-theme' ),
					'post_name'    => 'features',
					'post_author'  => $author,
					'post_excerpt' => __( 'Everything Modern Job Board does, feature by feature.', 'modern-job-board-theme' ),
					'post_content' => mjb_theme_features_index_blocks(),
				)
			),
			true
		);

		if ( is_wp_error( $parent_id ) || ! $parent_id ) {
			return;
		}
	}

	$order = 0;

	foreach ( $features as $slug => $feature ) {
		++$order;

		if ( ! isset( $pending[ $slug ] ) || get_page_by_path( 'features/' . $slug ) ) {
			continue;
		}

		wp_insert_post(
			wp_slash(
				array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_parent'  => $parent_id,
					'post_title'   => $feature['title'],
					'post_name'    => $slug,
					'post_excerpt' => $feature['description'],
					'post_content' => mjb_theme_feature_page_blocks( $slug, $feature['title'], $feature['description'] ),
					'post_author'  => $author,
					'menu_order'   => $order,
				)
			)
		);
	}
}
