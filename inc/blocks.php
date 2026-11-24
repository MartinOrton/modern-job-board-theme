<?php
/**
 * Block editor support: the Feature Grid and Feature Illustration blocks, block
 * patterns built from core blocks, the feature page hero, and the markup helpers
 * used to seed pages with editable blocks.
 *
 * Self-contained on purpose: this file, inc/feature-art.php and blocks/editor.js
 * can move into a plugin unchanged (swap get_template_directory_uri() for plugins_url()).
 *
 * @package ModernJobBoardTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// The illustration library for the Feature Illustration block.
require_once __DIR__ . '/feature-art.php';

// Seed copy and SEO helpers for the feature pages' body content.
require_once __DIR__ . '/feature-content.php';

/**
 * Register the block, its editor script, the pattern category and patterns.
 */
function mjb_theme_register_blocks() {
	// Page excerpts drive the intro line under the title in the page hero.
	add_post_type_support( 'page', 'excerpt' );

	wp_register_script(
		'mjb-blocks-editor',
		get_template_directory_uri() . '/blocks/editor.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-i18n', 'wp-hooks', 'wp-compose', 'wp-data' ),
		MJB_THEME_VERSION,
		true
	);

	register_block_type(
		'mjb/feature-grid',
		array(
			'api_version'           => 3,
			'title'                 => __( 'Feature Grid', 'modern-job-board-theme' ),
			'description'           => __( 'Tiles for the features on the home page: icon, title, description and a Learn More link. Edit the cards on the home page to change them.', 'modern-job-board-theme' ),
			'category'              => 'mjb',
			'icon'                  => 'screenoptions',
			'keywords'              => array( 'features', 'cards', 'tiles' ),
			'attributes'            => array(
				'show'         => array(
					'type'    => 'string',
					'enum'    => array( 'all', 'related' ),
					'default' => 'all',
				),
				'count'        => array(
					'type'    => 'integer',
					'default' => 3,
				),
				'headingLevel' => array(
					'type'    => 'integer',
					'default' => 2,
				),
			),
			'supports'              => array(
				'html'  => false,
				'align' => false,
			),
			'editor_script_handles' => array( 'mjb-blocks-editor' ),
			'render_callback'       => 'mjb_theme_render_feature_grid',
		)
	);

	register_block_type(
		'mjb/feature-art',
		array(
			'api_version'           => 3,
			'title'                 => __( 'Feature Illustration', 'modern-job-board-theme' ),
			'description'           => __( 'An animated illustration in the site colours. "Automatic" picks one to suit the page.', 'modern-job-board-theme' ),
			'category'              => 'mjb',
			'icon'                  => 'format-image',
			'keywords'              => array( 'illustration', 'animation', 'hero' ),
			'attributes'            => array(
				'art' => array(
					'type'    => 'string',
					'default' => 'auto',
				),
			),
			'supports'              => array(
				'html'  => false,
				'align' => false,
			),
			'editor_script_handles' => array( 'mjb-blocks-editor' ),
			'render_callback'       => 'mjb_theme_render_feature_art',
		)
	);

	// Illustration choices for the block's sidebar control.
	$art_options = array(
		array(
			'value' => 'auto',
			'label' => __( 'Automatic (match the page)', 'modern-job-board-theme' ),
		),
	);

	foreach ( mjb_theme_feature_arts() as $key => $art ) {
		$art_options[] = array(
			'value' => $key,
			'label' => $art['label'],
		);
	}

	wp_add_inline_script( 'mjb-blocks-editor', 'window.mjbThemeBlocks = ' . wp_json_encode(
		array(
			'arts'  => $art_options,
			'icons' => mjb_theme_list_icons(),
		)
	) . ';', 'before' );

	register_block_pattern_category(
		'mjb',
		array( 'label' => __( 'Modern Job Board', 'modern-job-board-theme' ) )
	);

	register_block_pattern(
		'mjb/call-to-action',
		array(
			'title'       => __( 'Call to action band', 'modern-job-board-theme' ),
			'description' => __( 'Teal band with a heading, a line of text and two buttons.', 'modern-job-board-theme' ),
			'categories'  => array( 'mjb', 'call-to-action' ),
			'content'     => mjb_theme_cta_blocks(),
		)
	);

	register_block_pattern(
		'mjb/more-features',
		array(
			'title'       => __( 'More features', 'modern-job-board-theme' ),
			'description' => __( 'Heading, an "All features" link and three related feature tiles.', 'modern-job-board-theme' ),
			'categories'  => array( 'mjb' ),
			'content'     => mjb_theme_more_features_blocks(),
		)
	);

	register_block_pattern(
		'mjb/device-screenshots',
		array(
			'title'       => __( 'Screenshot on desktop and phone', 'modern-job-board-theme' ),
			'description' => __( 'A desktop screenshot in a browser window with the phone screenshot on top of it. Use a 16:10 desktop image (1280 x 800) and a 390 x 763 phone image.', 'modern-job-board-theme' ),
			'categories'  => array( 'mjb' ),
			'content'     => "<!-- wp:group {\"className\":\"mjb-devices\"} -->\n<div class=\"wp-block-group mjb-devices\"><!-- wp:image {\"sizeSlug\":\"full\",\"linkDestination\":\"none\",\"className\":\"mjb-devices__desktop\"} -->\n<figure class=\"wp-block-image size-full mjb-devices__desktop\"><img alt=\"\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:image {\"sizeSlug\":\"full\",\"linkDestination\":\"none\",\"className\":\"mjb-devices__mobile\"} -->\n<figure class=\"wp-block-image size-full mjb-devices__mobile\"><img alt=\"\"/></figure>\n<!-- /wp:image -->\n\n" . mjb_theme_b_paragraph( __( 'Caption', 'modern-job-board-theme' ), 'mjb-devices__caption' ) . "</div>\n<!-- /wp:group -->",
		)
	);

	register_block_pattern(
		'mjb/icon-cards',
		array(
			'title'       => __( 'Icon cards', 'modern-job-board-theme' ),
			'description' => __( 'A list shown as cards, each with its own icon. Select a list item to choose its icon in the sidebar.', 'modern-job-board-theme' ),
			'categories'  => array( 'mjb' ),
			'content'     => mjb_theme_b_group(
				'mjb-feature-body',
				mjb_theme_b_cards(
					array(
						array( 'star', __( 'First point', 'modern-job-board-theme' ), __( 'Say what it is in a sentence.', 'modern-job-board-theme' ) ),
						array( 'zap', __( 'Second point', 'modern-job-board-theme' ), __( 'Say what it is in a sentence.', 'modern-job-board-theme' ) ),
					)
				)
			),
		)
	);

	register_block_pattern(
		'mjb/feature-card',
		array(
			'title'       => __( 'Feature card', 'modern-job-board-theme' ),
			'description' => __( 'Home page feature card: icon, title, description and a Learn More button.', 'modern-job-board-theme' ),
			'categories'  => array( 'mjb' ),
			'content'     => mjb_theme_feature_card_blocks(),
		)
	);

	register_block_pattern(
		'mjb/buttons',
		array(
			'title'       => __( 'Theme buttons', 'modern-job-board-theme' ),
			'description' => __( 'A primary and an outline button in the site style.', 'modern-job-board-theme' ),
			'categories'  => array( 'mjb', 'buttons' ),
			'content'     => mjb_theme_buttons_block(
				'mjb-buttons',
				array(
					array(
						'text'  => __( 'Get Started Free', 'modern-job-board-theme' ),
						'url'   => home_url( '/#pricing' ),
						'class' => 'mjb-btn mjb-btn-primary',
					),
					array(
						'text'  => __( 'Learn More', 'modern-job-board-theme' ),
						'url'   => home_url( '/features/' ),
						'class' => 'mjb-btn mjb-btn-outline mjb-btn-arrow',
					),
				)
			),
		)
	);
}
add_action( 'init', 'mjb_theme_register_blocks' );

/**
 * "Modern Job Board" category in the block inserter.
 *
 * @param array[] $categories Block categories.
 * @return array[]
 */
function mjb_theme_block_category( $categories ) {
	array_unshift(
		$categories,
		array(
			'slug'  => 'mjb',
			'title' => __( 'Modern Job Board', 'modern-job-board-theme' ),
		)
	);

	return $categories;
}
add_filter( 'block_categories_all', 'mjb_theme_block_category' );

/**
 * Whether a parsed block's className attribute contains a class.
 *
 * @param array  $block Parsed block.
 * @param string $class Class name.
 * @return bool
 */
function mjb_theme_block_has_class( $block, $class ) {
	$class_attr = isset( $block['attrs']['className'] ) ? (string) $block['attrs']['className'] : '';

	return in_array( $class, preg_split( '/\s+/', trim( $class_attr ) ), true );
}

/**
 * Render the Feature Grid block.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function mjb_theme_render_feature_grid( $attributes ) {
	$cards = mjb_theme_get_feature_cards();
	$level = isset( $attributes['headingLevel'] ) && 3 === (int) $attributes['headingLevel'] ? 'h3' : 'h2';

	if ( isset( $attributes['show'] ) && 'related' === $attributes['show'] ) {
		$post   = get_post();
		$name   = $post instanceof WP_Post ? $post->post_name : '';
		$index  = array_search( $name, array_column( $cards, 'slug' ), true );
		$count  = max( 1, min( 6, isset( $attributes['count'] ) ? (int) $attributes['count'] : 3 ) );
		$total  = count( $cards );
		$picked = array();

		// The next features after the current page, wrapping round.
		for ( $i = 1; $i <= $total && count( $picked ) < $count; $i++ ) {
			$card = $cards[ ( ( false === $index ? -1 : $index ) + $i ) % $total ];

			if ( $card['slug'] !== $name ) {
				$picked[] = $card;
			}
		}

		$cards = $picked;
	}

	if ( empty( $cards ) ) {
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return '<p>' . esc_html__( 'No feature cards were found on the home page.', 'modern-job-board-theme' ) . '</p>';
		}

		return '';
	}

	return sprintf(
		'<div %1$s>%2$s</div>',
		get_block_wrapper_attributes( array( 'class' => 'mjb-feature-index' ) ),
		mjb_theme_feature_tiles( $cards, $level )
	);
}

/**
 * Serialized core Buttons block in the site's button style.
 *
 * @param string  $wrapper_class Class(es) for the Buttons block.
 * @param array[] $buttons       Buttons: text, url, class, and optionally new_tab.
 * @return string
 */
function mjb_theme_buttons_block( $wrapper_class, $buttons ) {
	$inner = array();

	foreach ( $buttons as $button ) {
		$inner[] = sprintf(
			"<!-- wp:button %1\$s -->\n<div class=\"wp-block-button %2\$s\"><a class=\"wp-block-button__link wp-element-button\" href=\"%3\$s\"%4\$s>%5\$s</a></div>\n<!-- /wp:button -->",
			serialize_block_attributes( array( 'className' => $button['class'] ) ),
			esc_attr( $button['class'] ),
			esc_url( $button['url'] ),
			! empty( $button['new_tab'] ) ? ' target="_blank" rel="noopener noreferrer"' : '',
			esc_html( $button['text'] )
		);
	}

	return sprintf(
		"<!-- wp:buttons %1\$s -->\n<div class=\"wp-block-buttons %2\$s\">%3\$s</div>\n<!-- /wp:buttons -->",
		serialize_block_attributes( array( 'className' => $wrapper_class ) ),
		esc_attr( $wrapper_class ),
		implode( "\n\n", $inner )
	);
}

/**
 * Serialized "Learn More" button for a feature card.
 *
 * @param string $slug Feature slug.
 * @return string
 */
function mjb_theme_learn_more_block( $slug ) {
	return mjb_theme_buttons_block(
		'mjb-buttons feature-link',
		array(
			array(
				'text'  => __( 'Learn More', 'modern-job-board-theme' ),
				'url'   => home_url( '/features/' . $slug . '/' ),
				'class' => 'mjb-btn mjb-btn-outline mjb-btn-sm mjb-btn-arrow',
			),
		)
	);
}

/**
 * Serialized call-to-action band (Group > Heading, Paragraph, Buttons).
 *
 * @return string
 */
function mjb_theme_cta_blocks() {
	$buttons = mjb_theme_buttons_block(
		'mjb-buttons mjb-feature-cta__actions',
		array(
			array(
				'text'  => __( 'Get Started Free', 'modern-job-board-theme' ),
				'url'   => home_url( '/#pricing' ),
				'class' => 'mjb-btn mjb-btn-primary',
			),
			array(
				'text'    => __( 'Live Demo', 'modern-job-board-theme' ),
				'url'     => home_url( '/jobs/' ),
				'class'   => 'mjb-btn mjb-btn-outline',
				'new_tab' => true,
			),
		)
	);

	return sprintf(
		"<!-- wp:group {\"className\":\"mjb-feature-cta\",\"layout\":{\"type\":\"constrained\"}} -->\n<div class=\"wp-block-group mjb-feature-cta\"><!-- wp:heading {\"className\":\"mjb-feature-cta__title\"} -->\n<h2 class=\"wp-block-heading mjb-feature-cta__title\">%1\$s</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>%2\$s</p>\n<!-- /wp:paragraph -->\n\n%3\$s</div>\n<!-- /wp:group -->",
		esc_html__( 'Ready to run your own job board?', 'modern-job-board-theme' ),
		esc_html__( 'Start free on your own WordPress site, or try every feature in the live demo first.', 'modern-job-board-theme' ),
		$buttons
	);
}

/**
 * The call-to-action band as a synced pattern, so there is one copy to edit and
 * every page that shows it updates together. The pattern is created on first
 * use (Appearance → Editor → Patterns, or click the band in any page and choose
 * "Edit original"). Falls back to an inline copy if it cannot be created.
 *
 * @return string Serialized block.
 */
function mjb_theme_cta_block() {
	$id      = (int) get_option( 'mjb_theme_cta_pattern' );
	$pattern = $id ? get_post( $id ) : null;

	if ( ! $pattern instanceof WP_Post || 'wp_block' !== $pattern->post_type || 'publish' !== $pattern->post_status ) {
		// Trusted, generated markup: save it exactly as built, whoever triggers this request.
		$filtered = false !== has_filter( 'content_save_pre', 'wp_filter_post_kses' );

		if ( $filtered ) {
			kses_remove_filters();
		}

		$id = wp_insert_post(
			wp_slash(
				array(
					'post_type'    => 'wp_block',
					'post_status'  => 'publish',
					'post_title'   => __( 'Call to action band', 'modern-job-board-theme' ),
					'post_content' => mjb_theme_cta_blocks(),
				)
			),
			true
		);

		if ( $filtered ) {
			kses_init_filters();
		}

		if ( is_wp_error( $id ) || ! $id ) {
			return mjb_theme_cta_blocks();
		}

		update_option( 'mjb_theme_cta_pattern', (int) $id, false );
	}

	return '<!-- wp:block {"ref":' . (int) $id . '} /-->';
}

/**
 * Render the Feature Illustration block.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function mjb_theme_render_feature_art( $attributes ) {
	$key = isset( $attributes['art'] ) ? (string) $attributes['art'] : 'auto';

	if ( 'auto' === $key ) {
		$post = get_post();
		$key  = mjb_theme_match_feature_art( $post instanceof WP_Post ? $post->post_name . ' ' . $post->post_title : '' );
	}

	return sprintf( '<div %s>%s</div>', get_block_wrapper_attributes( array( 'class' => 'mjb-feature-art' ) ), mjb_theme_feature_art( $key ) );
}

/**
 * Starting copy for a feature page's hero: heading, text, button labels, demo
 * link and illustration. This only seeds the page; from then on the words live
 * in the page content and are edited in the block editor.
 *
 * @param string $slug        Feature slug.
 * @param string $title       Feature title.
 * @param string $description Feature description (the card text).
 * @return array{heading:string,text:string,primary:string,secondary:string,demo:string,art:string}
 */
function mjb_theme_feature_hero_copy( $slug, $title = '', $description = '' ) {
	$copy = array(
		'frontend-job-submission'          => array(
			__( 'Let employers post jobs without touching wp-admin', 'modern-job-board-theme' ),
			__( 'Give every employer a clean front-end form to post, edit and republish their own listings. You set the rules for expiry, featured spots and go-live dates, and the board keeps itself current.', 'modern-job-board-theme' ),
			'form',
		),
		'predictive-search-pretty-urls'    => array(
			__( 'Help candidates find the right job in a few keystrokes', 'modern-job-board-theme' ),
			__( 'Suggestions for titles, companies and locations appear as they type, and filter chips narrow the list in a tap. Every search gets a clean /jobs/ address, so results are easy to share and to subscribe to.', 'modern-job-board-theme' ),
			'search',
		),
		'employer-candidate-accounts'      => array(
			__( 'Give both sides of the hire a proper home', 'modern-job-board-theme' ),
			__( 'Candidates get a dashboard for their CV, saved jobs, applications and email preferences. Recruiters get one to move each application from New to Hired and keep their company profile up to date.', 'modern-job-board-theme' ),
			'accounts',
		),
		'private-resumes-cvs'              => array(
			__( 'Keep every CV out of public reach', 'modern-job-board-theme' ),
			__( 'Resumes are stored in a private folder, away from the Media Library, and every download needs a signed-in account. You decide how much of a candidate recruiters can see, right down to hiding surnames.', 'modern-job-board-theme' ),
			'lock',
		),
		'woocommerce-monetization-pro'     => array(
			__( 'Turn your job board into a business', 'modern-job-board-theme' ),
			__( 'Charge per post, sell credit packages, or put CV access and featured spots behind a checkout. Payments run through any WooCommerce gateway you choose, and the revenue stays with you.', 'modern-job-board-theme' ),
			'pricing',
		),
		'gutenberg-blocks'                 => array(
			__( 'Build every job board page in the editor you already know', 'modern-job-board-theme' ),
			__( 'Drop in blocks for job listings, forms, dashboards, login and registration, then arrange them like any other page. Shortcodes cover the rest, from maps and alerts to messaging and related jobs.', 'modern-job-board-theme' ),
			'blocks',
		),
		'seo-ready'                        => array(
			__( 'Make every listing ready for Google for Jobs', 'modern-job-board-theme' ),
			__( 'Each job is published with the structured data Google looks for, mapped from your own fields without code. Related jobs on every listing keep visitors browsing once they arrive.', 'modern-job-board-theme' ),
			'seo',
		),
		'custom-fields-imports-pro'        => array(
			__( 'Shape the board around your own data', 'modern-job-board-theme' ),
			__( 'Add the job and application fields your niche needs from a simple builder. Then bring listings in by CSV, XML or a remote feed on a schedule, and export a full backup whenever you want one.', 'modern-job-board-theme' ),
			'import',
		),
		'rest-api-feeds-webhooks-business' => array(
			__( 'Connect your board to everything else you run', 'modern-job-board-theme' ),
			__( 'Search and manage jobs through the REST API, syndicate listings as XML feeds, and push events to other systems with signed webhooks that retry when a delivery fails.', 'modern-job-board-theme' ),
			'api',
		),
		'job-alerts-saved-jobs-talent'     => array(
			__( 'Bring candidates back without lifting a finger', 'modern-job-board-theme' ),
			__( 'Candidates save the jobs they like and get a daily or weekly email when new ones match their search. Recruiters can browse a talent pool of candidates who chose to make their profiles public.', 'modern-job-board-theme' ),
			'alerts',
		),
		'jobs-map-promotions'              => array(
			__( 'Put jobs on the map and sponsors in the spotlight', 'modern-job-board-theme' ),
			__( 'Show openings on one map with a marker for each, showcase featured companies, and place ad banners between listings. WhatsApp apply and social sharing help every job travel further.', 'modern-job-board-theme' ),
			'map',
		),
		'admin-dashboard-jobs-console'     => array(
			__( 'See the health of your board at a glance', 'modern-job-board-theme' ),
			__( 'The dashboard shows what needs your attention and how listings are being viewed. The jobs console lets you search, filter and act on listings in bulk, then export them to CSV.', 'modern-job-board-theme' ),
			'dashboard',
		),
		'company-pages-careers-mode'       => array(
			__( 'Run a multi-employer board or your own careers site', 'modern-job-board-theme' ),
			__( 'Give every employer a public page with their open jobs, listed in a company directory. Or switch to careers mode and run the board for one company, with applications, saved jobs and alerts still in place.', 'modern-job-board-theme' ),
			'company',
		),
		'recruiter-mcp-business'           => array(
			__( 'Let recruiters manage hiring from their AI assistant', 'modern-job-board-theme' ),
			__( 'Recruiters can ask an AI assistant such as ChatGPT, Claude or Gemini to list and edit their jobs, review applications and check remaining credits, in plain English. MCP is the open standard that connects the assistant to the board, and it only ever reaches that employer\'s own records.', 'modern-job-board-theme' ),
			'assistant',
		),
	);

	if ( isset( $copy[ $slug ] ) ) {
		list( $heading, $text, $art ) = $copy[ $slug ];
	} else {
		/* translators: %s: feature name */
		$heading = '' !== $title ? sprintf( __( 'See %s in action', 'modern-job-board-theme' ), $title ) : __( 'See it in action', 'modern-job-board-theme' );
		$text    = __( 'Try it on the live demo, then run it on your own WordPress site with your own data.', 'modern-job-board-theme' );
		$art     = mjb_theme_match_feature_art( $slug . ' ' . $title );
	}

	// The paid tiers are named in the feature title, e.g. "... (Pro)".
	if ( preg_match( '/-business$/', $slug ) ) {
		$primary = __( 'See Business Pricing', 'modern-job-board-theme' );
	} elseif ( preg_match( '/-pro$/', $slug ) ) {
		$primary = __( 'See Pro Pricing', 'modern-job-board-theme' );
	} else {
		$primary = __( 'Get Started Free', 'modern-job-board-theme' );
	}

	return array(
		'heading'   => $heading,
		'text'      => $text,
		'primary'   => $primary,
		'secondary' => __( 'Try the Live Demo', 'modern-job-board-theme' ),
		'demo'      => 'company-pages-careers-mode' === $slug ? home_url( '/jobs/companies/' ) : home_url( '/jobs/' ),
		'art'       => $art,
	);
}

/**
 * Serialized feature page hero: a Columns block with a heading, text and two
 * buttons on the left and the Feature Illustration block on the right. When it
 * is the first block on a page, the theme shows it inside the page hero.
 *
 * @param string $slug        Feature slug.
 * @param string $title       Feature title.
 * @param string $description Feature description.
 * @return string
 */
function mjb_theme_feature_hero_blocks( $slug, $title = '', $description = '' ) {
	$copy    = mjb_theme_feature_hero_copy( $slug, $title, $description );
	$buttons = mjb_theme_buttons_block(
		'mjb-buttons mjb-feature-hero__actions',
		array(
			array(
				'text'  => $copy['primary'],
				'url'   => home_url( '/#pricing' ),
				'class' => 'mjb-btn mjb-btn-primary mjb-btn-lg',
			),
			array(
				'text'    => $copy['secondary'],
				'url'     => $copy['demo'],
				'class'   => 'mjb-btn mjb-btn-outline mjb-btn-lg',
				'new_tab' => true,
			),
		)
	);

	return sprintf(
		"<!-- wp:columns {\"verticalAlignment\":\"center\",\"className\":\"mjb-feature-hero\"} -->\n<div class=\"wp-block-columns are-vertically-aligned-center mjb-feature-hero\"><!-- wp:column {\"verticalAlignment\":\"center\",\"className\":\"mjb-feature-hero__text\"} -->\n<div class=\"wp-block-column is-vertically-aligned-center mjb-feature-hero__text\"><!-- wp:heading {\"className\":\"mjb-feature-hero__title\"} -->\n<h2 class=\"wp-block-heading mjb-feature-hero__title\">%1\$s</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"className\":\"mjb-feature-hero__lead\"} -->\n<p class=\"mjb-feature-hero__lead\">%2\$s</p>\n<!-- /wp:paragraph -->\n\n%3\$s</div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"verticalAlignment\":\"center\",\"className\":\"mjb-feature-hero__art\"} -->\n<div class=\"wp-block-column is-vertically-aligned-center mjb-feature-hero__art\"><!-- wp:mjb/feature-art %4\$s /--></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->",
		esc_html( $copy['heading'] ),
		esc_html( $copy['text'] ),
		$buttons,
		serialize_block_attributes( array( 'art' => $copy['art'] ) )
	);
}

/**
 * Split a page into its hero block (when the first block has the class
 * "mjb-feature-hero") and the rest of its content.
 *
 * @param int|WP_Post|null $post Post; defaults to the current post.
 * @return array{hero:array,rest:string}|null
 */
function mjb_theme_hero_split( $post = null ) {
	static $cache = array();

	$post = get_post( $post );

	if ( ! $post instanceof WP_Post ) {
		return null;
	}

	if ( array_key_exists( $post->ID, $cache ) ) {
		return $cache[ $post->ID ];
	}

	$cache[ $post->ID ] = null;

	if ( false === strpos( $post->post_content, 'mjb-feature-hero' ) ) {
		return null;
	}

	$blocks = parse_blocks( $post->post_content );

	foreach ( $blocks as $index => $block ) {
		if ( empty( $block['blockName'] ) && '' === trim( (string) $block['innerHTML'] ) ) {
			continue;
		}

		if ( mjb_theme_block_has_class( $block, 'mjb-feature-hero' ) ) {
			unset( $blocks[ $index ] );

			$cache[ $post->ID ] = array(
				'hero' => $block,
				'rest' => serialize_blocks( array_values( $blocks ) ),
			);
		}

		break;
	}

	return $cache[ $post->ID ];
}

/**
 * Print the page's hero block inside the page hero (called from index.php).
 */
function mjb_theme_the_hero_blocks() {
	$split = mjb_theme_hero_split();

	if ( ! $split ) {
		return;
	}

	$GLOBALS['mjb_theme_hero_printed'][ get_the_ID() ] = true;

	echo '<div class="mjb-content-hero__blocks">' . render_block( $split['hero'] ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Rendered block markup.
}

/**
 * Leave the hero block out of the page body once it has been shown in the hero.
 *
 * @param string $content Raw post content.
 * @return string
 */
function mjb_theme_strip_hero_block( $content ) {
	if ( is_admin() || ! is_singular() || ! in_the_loop() || ! is_main_query() || empty( $GLOBALS['mjb_theme_hero_printed'][ get_the_ID() ] ) ) {
		return $content;
	}

	$split = mjb_theme_hero_split();

	return $split ? $split['rest'] : $content;
}
add_filter( 'the_content', 'mjb_theme_strip_hero_block', 1 );

/**
 * Serialized "More features" section (Group > Heading, link Paragraph, Feature Grid).
 *
 * @return string
 */
function mjb_theme_more_features_blocks() {
	return sprintf(
		"<!-- wp:group {\"className\":\"mjb-feature-more\",\"layout\":{\"type\":\"constrained\"}} -->\n<div class=\"wp-block-group mjb-feature-more\"><!-- wp:heading {\"className\":\"mjb-feature-more__title\"} -->\n<h2 class=\"wp-block-heading mjb-feature-more__title\">%1\$s</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"className\":\"mjb-feature-more__all\"} -->\n<p class=\"mjb-feature-more__all\"><a href=\"%2\$s\">%3\$s</a></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:mjb/feature-grid {\"show\":\"related\",\"headingLevel\":3} /--></div>\n<!-- /wp:group -->",
		esc_html__( 'More features', 'modern-job-board-theme' ),
		esc_url( home_url( '/features/' ) ),
		esc_html__( 'All features', 'modern-job-board-theme' )
	);
}

/**
 * Block content for a new individual feature page.
 *
 * @param string $slug        Feature slug; when given, the page starts with the hero.
 * @param string $title       Feature title.
 * @param string $description Feature description.
 * @return string
 */
function mjb_theme_feature_page_blocks( $slug = '', $title = '', $description = '' ) {
	$body = mjb_theme_more_features_blocks() . "\n\n" . mjb_theme_cta_block();

	if ( '' === $slug ) {
		return $body;
	}

	$content = mjb_theme_feature_body_blocks( $slug );

	return mjb_theme_feature_hero_blocks( $slug, $title, $description ) . "\n\n" . ( '' !== $content ? $content . "\n\n" : '' ) . $body;
}

/**
 * Block content for the Features overview page.
 *
 * @return string
 */
function mjb_theme_features_index_blocks() {
	return mjb_theme_features_index_intro_blocks() . "\n\n<!-- wp:mjb/feature-grid /-->\n\n" . mjb_theme_features_index_after_blocks() . "\n\n" . mjb_theme_cta_block();
}

/**
 * Serialized home page feature card, for the "Feature card" pattern.
 *
 * @return string
 */
function mjb_theme_feature_card_blocks() {
	return sprintf(
		"<!-- wp:group {\"className\":\"feature-card reveal\",\"layout\":{\"type\":\"constrained\"}} -->\n<div class=\"wp-block-group feature-card reveal\"><!-- wp:html -->\n<div class=\"feature-icon\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m3 17 2 2 4-4\"/><path d=\"m3 7 2 2 4-4\"/><path d=\"M13 6h8\"/><path d=\"M13 12h8\"/><path d=\"M13 18h8\"/></svg></div>\n<!-- /wp:html -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">%1\$s</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>%2\$s</p>\n<!-- /wp:paragraph -->\n\n%3\$s</div>\n<!-- /wp:group -->",
		esc_html__( 'Feature name', 'modern-job-board-theme' ),
		esc_html__( 'Describe the feature in a sentence or two.', 'modern-job-board-theme' ),
		mjb_theme_learn_more_block( 'feature-name' )
	);
}
