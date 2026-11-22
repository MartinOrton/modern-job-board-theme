<?php
/**
 * Knowledge base: articles (mjb_kb) grouped in topics (mjb_kb_topic).
 *
 * URLs:
 * - /docs/                 The Documentation page: edit it in the block editor.
 *                          The Knowledge Base Search, Topics and Articles blocks
 *                          list the articles.
 * - /docs/{article}/       An article (single-mjb_kb.php): topic navigation,
 *                          the article, "On this page" and previous / next.
 * - /docs/topic/{topic}/   A topic and its articles (taxonomy-mjb_kb_topic.php).
 * - /?s=…&post_type=mjb_kb Search results (search-mjb_kb.php).
 *
 * Articles are edited under Knowledge Base in wp-admin. The article order
 * inside a topic is the Order field (Page Attributes); topics have their own
 * order and icon on Knowledge Base → Topics.
 *
 * mjb_theme_kb_setup() runs once: it creates the topics and articles from
 * inc/kb/ (manifest.json and one .html file per article), keeps the old
 * single-page documentation as a draft, and turns the Documentation page into
 * the knowledge base home. Links to the old page's sections (#webhooks and so
 * on) are sent to the matching article.
 *
 * @package ModernJobBoardTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const MJB_THEME_KB       = 'mjb_kb';
const MJB_THEME_KB_TOPIC = 'mjb_kb_topic';

/**
 * Register the topic taxonomy, the article post type and the topic fields.
 * The taxonomy is registered first so /docs/topic/… is matched before the
 * article URLs.
 */
function mjb_theme_kb_register() {
	register_taxonomy(
		MJB_THEME_KB_TOPIC,
		array( MJB_THEME_KB ),
		array(
			'labels'            => array(
				'name'          => __( 'Topics', 'modern-job-board-theme' ),
				'singular_name' => __( 'Topic', 'modern-job-board-theme' ),
				'all_items'     => __( 'All topics', 'modern-job-board-theme' ),
				'edit_item'     => __( 'Edit topic', 'modern-job-board-theme' ),
				'add_new_item'  => __( 'Add topic', 'modern-job-board-theme' ),
				'search_items'  => __( 'Search topics', 'modern-job-board-theme' ),
				'not_found'     => __( 'No topics found.', 'modern-job-board-theme' ),
				'back_to_items' => __( '&larr; Back to topics', 'modern-job-board-theme' ),
				'parent_item'   => __( 'Parent topic', 'modern-job-board-theme' ),
				'name_field_description'   => __( 'The topic name, as shown on the Documentation page.', 'modern-job-board-theme' ),
				'desc_field_description'   => __( 'One sentence shown under the topic name on its card and topic page.', 'modern-job-board-theme' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'       => 'docs/topic',
				'with_front' => false,
			),
		)
	);

	register_post_type(
		MJB_THEME_KB,
		array(
			'labels'        => array(
				'name'               => __( 'Knowledge Base', 'modern-job-board-theme' ),
				'singular_name'      => __( 'Article', 'modern-job-board-theme' ),
				'menu_name'          => __( 'Knowledge Base', 'modern-job-board-theme' ),
				'all_items'          => __( 'Articles', 'modern-job-board-theme' ),
				'add_new'            => __( 'Add article', 'modern-job-board-theme' ),
				'add_new_item'       => __( 'Add article', 'modern-job-board-theme' ),
				'edit_item'          => __( 'Edit article', 'modern-job-board-theme' ),
				'new_item'           => __( 'New article', 'modern-job-board-theme' ),
				'view_item'          => __( 'View article', 'modern-job-board-theme' ),
				'search_items'       => __( 'Search articles', 'modern-job-board-theme' ),
				'not_found'          => __( 'No articles found.', 'modern-job-board-theme' ),
				'not_found_in_trash' => __( 'No articles in the trash.', 'modern-job-board-theme' ),
				'item_published'     => __( 'Article published.', 'modern-job-board-theme' ),
				'item_updated'       => __( 'Article updated.', 'modern-job-board-theme' ),
			),
			'public'        => true,
			'show_in_rest'  => true,
			'has_archive'   => false,
			'menu_icon'     => 'dashicons-book-alt',
			'menu_position' => 21,
			'supports'      => array( 'title', 'editor', 'excerpt', 'revisions', 'page-attributes', 'author' ),
			'taxonomies'    => array( MJB_THEME_KB_TOPIC ),
			'rewrite'       => array(
				'slug'       => 'docs',
				'with_front' => false,
				'feeds'      => false,
				'pages'      => false,
			),
		)
	);

	foreach ( array( 'mjb_kb_order' => 'integer', 'mjb_kb_icon' => 'string' ) as $key => $type ) {
		register_term_meta(
			MJB_THEME_KB_TOPIC,
			$key,
			array(
				'type'              => $type,
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'integer' === $type
					? static function ( $value ) {
						return (int) $value;
					}
					: static function ( $value ) {
						return sanitize_key( (string) $value );
					},
			)
		);
	}

	register_block_type(
		'mjb/kb-search',
		array(
			'api_version'           => 3,
			'title'                 => __( 'Knowledge Base Search', 'modern-job-board-theme' ),
			'category'              => 'mjb',
			'icon'                  => 'search',
			'attributes'            => array(
				'placeholder' => array(
					'type'    => 'string',
					'default' => __( 'Search the documentation, for example "webhooks"', 'modern-job-board-theme' ),
				),
			),
			'supports'              => array(
				'html'  => false,
				'align' => false,
			),
			'editor_script_handles' => array( 'mjb-site-blocks-editor' ),
			'render_callback'       => 'mjb_theme_kb_render_search',
		)
	);

	register_block_type(
		'mjb/kb-categories',
		array(
			'api_version'           => 3,
			'title'                 => __( 'Knowledge Base Topics', 'modern-job-board-theme' ),
			'category'              => 'mjb',
			'icon'                  => 'category',
			'attributes'            => array(
				'perTopic' => array(
					'type'    => 'integer',
					'default' => 5,
				),
			),
			'supports'              => array(
				'html'  => false,
				'align' => false,
			),
			'editor_script_handles' => array( 'mjb-site-blocks-editor' ),
			'render_callback'       => 'mjb_theme_kb_render_categories',
		)
	);

	register_block_type(
		'mjb/kb-articles',
		array(
			'api_version'           => 3,
			'title'                 => __( 'Knowledge Base Articles', 'modern-job-board-theme' ),
			'category'              => 'mjb',
			'icon'                  => 'media-document',
			'attributes'            => array(
				'topic'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'count'   => array(
					'type'    => 'integer',
					'default' => 6,
				),
				'orderBy' => array(
					'type'    => 'string',
					'enum'    => array( 'menu_order', 'modified', 'title' ),
					'default' => 'menu_order',
				),
			),
			'supports'              => array(
				'html'  => false,
				'align' => false,
			),
			'editor_script_handles' => array( 'mjb-site-blocks-editor' ),
			'render_callback'       => 'mjb_theme_kb_render_articles',
		)
	);

	wp_register_script(
		'mjb-kb',
		get_template_directory_uri() . '/js/kb.js',
		array(),
		MJB_THEME_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'init', 'mjb_theme_kb_register', 6 );

/**
 * Data for the knowledge base blocks' sidebar controls.
 *
 * @return array
 */
function mjb_theme_kb_editor_data() {
	$topics = array();

	foreach ( mjb_theme_kb_topics() as $topic ) {
		$topics[] = array(
			'value' => $topic->slug,
			'label' => $topic->name,
		);
	}

	return array(
		'placeholder' => __( 'Search the documentation, for example "webhooks"', 'modern-job-board-theme' ),
		'topics'      => $topics,
	);
}

/**
 * Is this request part of the knowledge base (an article, a topic, a KB search or the Documentation page)?
 *
 * @return bool
 */
function mjb_theme_kb_is_view() {
	return is_singular( MJB_THEME_KB ) || is_tax( MJB_THEME_KB_TOPIC ) || mjb_theme_kb_is_search() || is_page( 'docs' );
}

/**
 * Is this a knowledge base search?
 *
 * @return bool
 */
function mjb_theme_kb_is_search() {
	return is_search() && MJB_THEME_KB === get_query_var( 'post_type' );
}

/**
 * Topics in their set order.
 *
 * @return WP_Term[]
 */
function mjb_theme_kb_topics() {
	$terms = get_terms(
		array(
			'taxonomy'   => MJB_THEME_KB_TOPIC,
			'hide_empty' => false,
		)
	);

	if ( is_wp_error( $terms ) ) {
		return array();
	}

	usort(
		$terms,
		static function ( $a, $b ) {
			$order = (int) get_term_meta( $a->term_id, 'mjb_kb_order', true ) <=> (int) get_term_meta( $b->term_id, 'mjb_kb_order', true );

			return 0 !== $order ? $order : strcasecmp( $a->name, $b->name );
		}
	);

	return $terms;
}

/**
 * Published articles in a topic, in reading order.
 *
 * @param WP_Term|int $topic Topic.
 * @param int         $limit Most to return (-1 for all).
 * @return WP_Post[]
 */
function mjb_theme_kb_articles( $topic, $limit = -1 ) {
	$term_id = $topic instanceof WP_Term ? $topic->term_id : (int) $topic;

	return get_posts(
		array(
			'post_type'        => MJB_THEME_KB,
			'post_status'      => 'publish',
			'posts_per_page'   => $limit,
			'orderby'          => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			'tax_query'        => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- Small taxonomy.
				array(
					'taxonomy'         => MJB_THEME_KB_TOPIC,
					'terms'            => $term_id,
					'include_children' => false,
				),
			),
			'suppress_filters' => false,
		)
	);
}

/**
 * The topic an article belongs to (the first, in topic order).
 *
 * @param int|WP_Post $post Article.
 * @return WP_Term|null
 */
function mjb_theme_kb_topic_of( $post ) {
	$terms = get_the_terms( $post, MJB_THEME_KB_TOPIC );

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return null;
	}

	$order = wp_list_pluck( mjb_theme_kb_topics(), 'term_id' );

	usort(
		$terms,
		static function ( $a, $b ) use ( $order ) {
			return (int) array_search( $a->term_id, $order, true ) <=> (int) array_search( $b->term_id, $order, true );
		}
	);

	return $terms[0];
}

/**
 * Every published article in reading order: topic by topic, then by Order.
 *
 * @return WP_Post[]
 */
function mjb_theme_kb_reading_order() {
	static $list = null;

	if ( null !== $list ) {
		return $list;
	}

	$list = array();
	$seen = array();

	foreach ( mjb_theme_kb_topics() as $topic ) {
		foreach ( mjb_theme_kb_articles( $topic ) as $article ) {
			if ( empty( $seen[ $article->ID ] ) ) {
				$seen[ $article->ID ] = true;
				$list[]               = $article;
			}
		}
	}

	return $list;
}

/**
 * Icon for a topic (one of the card icons in css/list-icons.css).
 *
 * @param WP_Term $topic Topic.
 * @return string
 */
function mjb_theme_kb_icon( $topic ) {
	$icon = (string) get_term_meta( $topic->term_id, 'mjb_kb_icon', true );

	if ( '' === $icon || ! in_array( $icon, mjb_theme_list_icons(), true ) ) {
		$icon = 'file-text';
	}

	return '<span class="mjb-kb-icon mjb-i-' . esc_attr( $icon ) . '" aria-hidden="true"></span>';
}

/**
 * Reading time in minutes.
 *
 * @param WP_Post $post Article.
 * @return int
 */
function mjb_theme_kb_minutes( $post ) {
	$words = str_word_count( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ) );

	return max( 1, (int) round( $words / 220 ) );
}

/**
 * Styles and script for knowledge base views.
 */
function mjb_theme_kb_assets() {
	if ( ! mjb_theme_kb_is_view() ) {
		return;
	}

	// Topic icons use the card icon masks (already loaded on singular pages).
	wp_enqueue_style(
		'mjb-list-icons',
		get_template_directory_uri() . '/css/list-icons.css',
		array( 'mjb-main-style' ),
		MJB_THEME_VERSION
	);

	wp_enqueue_script( 'mjb-kb' );
	wp_localize_script(
		'mjb-kb',
		'mjbKb',
		array(
			'search'  => esc_url_raw( rest_url( 'wp/v2/search' ) ),
			'copy'     => __( 'Copy', 'modern-job-board-theme' ),
			'copyCode' => __( 'Copy code', 'modern-job-board-theme' ),
			'copied'  => __( 'Copied', 'modern-job-board-theme' ),
			'none'    => __( 'No matching articles. Press Enter to search all text.', 'modern-job-board-theme' ),
			'legacy'  => is_page( 'docs' ) ? mjb_theme_kb_legacy_map() : (object) array(),
			'showAll' => __( 'See all results', 'modern-job-board-theme' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'mjb_theme_kb_assets', 25 );

/**
 * Old single-page documentation anchors (#webhooks, …) and the article each one now lives in.
 *
 * @return array<string, string> Anchor => URL.
 */
function mjb_theme_kb_legacy_map() {
	$map      = array();
	$articles = get_posts(
		array(
			'post_type'      => MJB_THEME_KB,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_key'       => '_mjb_kb_legacy', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Runs on one page.
		)
	);

	foreach ( $articles as $article ) {
		foreach ( (array) get_post_meta( $article->ID, '_mjb_kb_legacy', true ) as $anchor => $target ) {
			$map[ (string) $anchor ] = get_permalink( $article ) . ( '' !== (string) $target ? '#' . $target : '' );
		}
	}

	return $map;
}

/**
 * Breadcrumbs: Home › Documentation › Topic › Article.
 *
 * @param array[] $crumbs Crumbs.
 * @return array[]
 */
function mjb_theme_kb_breadcrumbs( $crumbs ) {
	if ( ! is_singular( MJB_THEME_KB ) && ! is_tax( MJB_THEME_KB_TOPIC ) && ! mjb_theme_kb_is_search() ) {
		return $crumbs;
	}

	$docs  = get_page_by_path( 'docs' );
	$trail = array( $crumbs[0] );

	if ( $docs instanceof WP_Post ) {
		$trail[] = array(
			'label' => mjb_theme_plain_title( $docs ),
			'url'   => get_permalink( $docs ),
		);
	}

	if ( is_singular( MJB_THEME_KB ) ) {
		$topic = mjb_theme_kb_topic_of( get_queried_object() );

		if ( $topic ) {
			$trail[] = array(
				'label' => $topic->name,
				'url'   => get_term_link( $topic ),
			);
		}

		$trail[] = array(
			'label' => mjb_theme_plain_title( get_queried_object() ),
			'url'   => '',
		);
	} elseif ( is_tax( MJB_THEME_KB_TOPIC ) ) {
		$trail[] = array(
			'label' => single_term_title( '', false ),
			'url'   => '',
		);
	} else {
		$trail[] = array(
			'label' => __( 'Search results', 'modern-job-board-theme' ),
			'url'   => '',
		);
	}

	return $trail;
}
add_filter( 'mjb_theme_breadcrumbs', 'mjb_theme_kb_breadcrumbs' );

/**
 * Knowledge base search: articles only, 20 per page.
 *
 * @param WP_Query $query Query.
 */
function mjb_theme_kb_search_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() || MJB_THEME_KB !== $query->get( 'post_type' ) ) {
		return;
	}

	$query->set( 'posts_per_page', 20 );
	$query->set( 'post_status', 'publish' );
}
add_action( 'pre_get_posts', 'mjb_theme_kb_search_query' );

/**
 * Knowledge base search results template.
 *
 * @param string $template Template.
 * @return string
 */
function mjb_theme_kb_search_template( $template ) {
	if ( mjb_theme_kb_is_search() ) {
		$kb = locate_template( 'search-mjb_kb.php' );

		if ( $kb ) {
			return $kb;
		}
	}

	return $template;
}
add_filter( 'template_include', 'mjb_theme_kb_search_template' );

/**
 * Shortcodes written as examples inside `code` in an article stay text: [mjb_jobs] is shown, not run.
 *
 * @param string $content Content.
 * @return string
 */
function mjb_theme_kb_quote_code_shortcodes( $content ) {
	if ( MJB_THEME_KB !== get_post_type() || false === strpos( $content, '[' ) ) {
		return $content;
	}

	return preg_replace_callback(
		'#<(code|pre)\b[^>]*>.*?</\1>#is',
		static function ( $m ) {
			return str_replace( '[', '&#91;', $m[0] );
		},
		$content
	);
}
add_filter( 'the_content', 'mjb_theme_kb_quote_code_shortcodes', 8 );

/**
 * Give h2 and h3 headings an id (for links and "On this page") and collect them.
 *
 * @param string $html Rendered article.
 * @return array{html:string,toc:array}
 */
function mjb_theme_kb_headings( $html ) {
	$toc  = array();
	$used = array();

	$html = preg_replace_callback(
		'#<h([23])\b([^>]*)>(.*?)</h\1>#is',
		static function ( $m ) use ( &$toc, &$used ) {
			$attrs = $m[2];
			$text  = trim( html_entity_decode( wp_strip_all_tags( $m[3] ), ENT_QUOTES, 'UTF-8' ) );

			if ( preg_match( '/\bid="([^"]+)"/', $attrs, $id ) ) {
				$anchor = $id[1];
			} else {
				$base   = sanitize_title( $text );
				$anchor = '' !== $base ? $base : 'section';
				$n      = 2;

				while ( isset( $used[ $anchor ] ) ) {
					$anchor = $base . '-' . $n++;
				}

				$attrs = ' id="' . esc_attr( $anchor ) . '"' . $attrs;
			}

			$used[ $anchor ] = true;
			$toc[]           = array(
				'level' => (int) $m[1],
				'id'    => $anchor,
				'text'  => $text,
			);

			return '<h' . $m[1] . $attrs . '>' . $m[3] . '</h' . $m[1] . '>';
		},
		$html
	);

	return array(
		'html' => $html,
		'toc'  => $toc,
	);
}

/**
 * Search box block.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function mjb_theme_kb_render_search( $attributes ) {
	static $instance = 0;
	++$instance;

	$placeholder = isset( $attributes['placeholder'] ) && '' !== $attributes['placeholder'] ? $attributes['placeholder'] : __( 'Search the documentation', 'modern-job-board-theme' );
	$id          = 'mjb-kb-q-' . $instance;
	$value       = mjb_theme_kb_is_search() ? get_search_query() : '';

	return sprintf(
		'<form class="mjb-kb-search" role="search" method="get" action="%1$s" data-kb-search>
			<label class="screen-reader-text" for="%2$s">%3$s</label>
			<div class="mjb-kb-search__field">
				<span class="mjb-kb-search__icon mjb-i-search" aria-hidden="true"></span>
				<input type="search" id="%2$s" name="s" value="%4$s" placeholder="%5$s" autocomplete="off" spellcheck="false" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="%2$s-list" />
				<input type="hidden" name="post_type" value="%6$s" />
				<button type="submit" class="btn btn-primary mjb-kb-search__submit">%7$s</button>
			</div>
			<ul class="mjb-kb-search__list" id="%2$s-list" role="listbox" hidden></ul>
		</form>',
		esc_url( home_url( '/' ) ),
		esc_attr( $id ),
		esc_html__( 'Search the documentation', 'modern-job-board-theme' ),
		esc_attr( $value ),
		esc_attr( $placeholder ),
		esc_attr( MJB_THEME_KB ),
		esc_html__( 'Search', 'modern-job-board-theme' )
	);
}

/**
 * Topic cards block.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function mjb_theme_kb_render_categories( $attributes ) {
	$per   = isset( $attributes['perTopic'] ) ? max( 1, (int) $attributes['perTopic'] ) : 5;
	$cards = '';

	foreach ( mjb_theme_kb_topics() as $topic ) {
		$articles = mjb_theme_kb_articles( $topic );

		if ( ! $articles ) {
			continue;
		}

		$items = '';

		foreach ( array_slice( $articles, 0, $per ) as $article ) {
			$items .= '<li><a href="' . esc_url( get_permalink( $article ) ) . '">' . esc_html( mjb_theme_plain_title( $article ) ) . '</a></li>';
		}

		$more = count( $articles ) > $per
			/* translators: %d: number of articles */
			? sprintf( _n( 'All %d article', 'All %d articles', count( $articles ), 'modern-job-board-theme' ), count( $articles ) )
			: __( 'Open topic', 'modern-job-board-theme' );

		$cards .= sprintf(
			'<section class="mjb-kb-topic">
				<div class="mjb-kb-topic__head">%1$s<h2 class="mjb-kb-topic__title"><a href="%2$s">%3$s</a></h2></div>
				%4$s
				<ul class="mjb-kb-topic__list">%5$s</ul>
				<a class="mjb-kb-topic__more" href="%2$s">%6$s</a>
			</section>',
			mjb_theme_kb_icon( $topic ),
			esc_url( get_term_link( $topic ) ),
			esc_html( $topic->name ),
			'' !== $topic->description ? '<p class="mjb-kb-topic__desc">' . esc_html( $topic->description ) . '</p>' : '',
			$items,
			esc_html( $more )
		);
	}

	if ( '' === $cards ) {
		return '<p class="mjb-kb-empty">' . esc_html__( 'No articles have been published yet.', 'modern-job-board-theme' ) . '</p>';
	}

	return '<div class="mjb-kb-topics">' . $cards . '</div>';
}

/**
 * Article list block.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function mjb_theme_kb_render_articles( $attributes ) {
	$count = isset( $attributes['count'] ) ? max( 1, (int) $attributes['count'] ) : 6;
	$order = isset( $attributes['orderBy'] ) ? (string) $attributes['orderBy'] : 'menu_order';
	$args  = array(
		'post_type'      => MJB_THEME_KB,
		'post_status'    => 'publish',
		'posts_per_page' => $count,
	);

	if ( 'modified' === $order ) {
		$args['orderby'] = 'modified';
		$args['order']   = 'DESC';
	} elseif ( 'title' === $order ) {
		$args['orderby'] = 'title';
		$args['order']   = 'ASC';
	}

	if ( ! empty( $attributes['topic'] ) ) {
		$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- Small taxonomy.
			array(
				'taxonomy' => MJB_THEME_KB_TOPIC,
				'field'    => 'slug',
				'terms'    => sanitize_title( $attributes['topic'] ),
			),
		);
	}

	if ( 'menu_order' === $order ) {
		// Reading order across topics, or the topic's own order.
		$posts = empty( $attributes['topic'] ) ? array_slice( mjb_theme_kb_reading_order(), 0, $count ) : null;

		if ( null === $posts ) {
			$args['orderby'] = array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			);
			$posts           = get_posts( $args );
		}
	} else {
		$posts = get_posts( $args );
	}

	if ( ! $posts ) {
		return '';
	}

	$items = '';

	foreach ( $posts as $post ) {
		$topic  = mjb_theme_kb_topic_of( $post );
		$items .= sprintf(
			'<li class="mjb-kb-card"><a class="mjb-kb-card__link" href="%1$s"><span class="mjb-kb-card__topic">%2$s</span><span class="mjb-kb-card__title">%3$s</span>%4$s</a></li>',
			esc_url( get_permalink( $post ) ),
			$topic ? esc_html( $topic->name ) : '',
			esc_html( mjb_theme_plain_title( $post ) ),
			has_excerpt( $post ) ? '<span class="mjb-kb-card__text">' . esc_html( get_the_excerpt( $post ) ) . '</span>' : ''
		);
	}

	return '<ul class="mjb-kb-cards">' . $items . '</ul>';
}

/**
 * Topic fields: order and icon (add and edit screens).
 *
 * @param WP_Term|string $term Term on the edit screen, taxonomy slug on the add screen.
 */
function mjb_theme_kb_topic_fields( $term ) {
	$editing = $term instanceof WP_Term;
	$order   = $editing ? (int) get_term_meta( $term->term_id, 'mjb_kb_order', true ) : 0;
	$icon    = $editing ? (string) get_term_meta( $term->term_id, 'mjb_kb_icon', true ) : '';
	$options = '<option value="">' . esc_html__( 'Default (document)', 'modern-job-board-theme' ) . '</option>';

	foreach ( mjb_theme_list_icons() as $name ) {
		$options .= '<option value="' . esc_attr( $name ) . '"' . selected( $icon, $name, false ) . '>' . esc_html( $name ) . '</option>';
	}

	wp_nonce_field( 'mjb_kb_topic', 'mjb_kb_topic_nonce' );

	$order_field = '<input type="number" name="mjb_kb_order" id="mjb_kb_order" value="' . esc_attr( (string) $order ) . '" step="1" />';
	$icon_field  = '<select name="mjb_kb_icon" id="mjb_kb_icon">' . $options . '</select>';
	$order_help  = esc_html__( 'Topics are listed from the lowest number up.', 'modern-job-board-theme' );
	$icon_help   = esc_html__( 'Shown on the topic card on the Documentation page.', 'modern-job-board-theme' );

	// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- Built from escaped parts.
	if ( $editing ) {
		echo '<tr class="form-field"><th scope="row"><label for="mjb_kb_order">' . esc_html__( 'Order', 'modern-job-board-theme' ) . '</label></th><td>' . $order_field . '<p class="description">' . $order_help . '</p></td></tr>';
		echo '<tr class="form-field"><th scope="row"><label for="mjb_kb_icon">' . esc_html__( 'Icon', 'modern-job-board-theme' ) . '</label></th><td>' . $icon_field . '<p class="description">' . $icon_help . '</p></td></tr>';
	} else {
		echo '<div class="form-field"><label for="mjb_kb_order">' . esc_html__( 'Order', 'modern-job-board-theme' ) . '</label>' . $order_field . '<p>' . $order_help . '</p></div>';
		echo '<div class="form-field"><label for="mjb_kb_icon">' . esc_html__( 'Icon', 'modern-job-board-theme' ) . '</label>' . $icon_field . '<p>' . $icon_help . '</p></div>';
	}
	// phpcs:enable
}
add_action( MJB_THEME_KB_TOPIC . '_add_form_fields', 'mjb_theme_kb_topic_fields' );
add_action( MJB_THEME_KB_TOPIC . '_edit_form_fields', 'mjb_theme_kb_topic_fields' );

/**
 * Save the topic fields.
 *
 * @param int $term_id Term id.
 */
function mjb_theme_kb_save_topic_fields( $term_id ) {
	if ( ! isset( $_POST['mjb_kb_topic_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['mjb_kb_topic_nonce'] ) ), 'mjb_kb_topic' ) || ! current_user_can( 'manage_categories' ) ) {
		return;
	}

	if ( isset( $_POST['mjb_kb_order'] ) ) {
		update_term_meta( $term_id, 'mjb_kb_order', (int) $_POST['mjb_kb_order'] );
	}

	if ( isset( $_POST['mjb_kb_icon'] ) ) {
		$icon = sanitize_key( wp_unslash( $_POST['mjb_kb_icon'] ) );
		update_term_meta( $term_id, 'mjb_kb_icon', in_array( $icon, mjb_theme_list_icons(), true ) ? $icon : '' );
	}
}
add_action( 'created_' . MJB_THEME_KB_TOPIC, 'mjb_theme_kb_save_topic_fields' );
add_action( 'edited_' . MJB_THEME_KB_TOPIC, 'mjb_theme_kb_save_topic_fields' );

/**
 * Order column on the topics list.
 *
 * @param array $columns Columns.
 * @return array
 */
function mjb_theme_kb_topic_columns( $columns ) {
	$columns['mjb_kb_order'] = __( 'Order', 'modern-job-board-theme' );

	return $columns;
}
add_filter( 'manage_edit-' . MJB_THEME_KB_TOPIC . '_columns', 'mjb_theme_kb_topic_columns' );

/**
 * Order column value.
 *
 * @param string $value   Value.
 * @param string $column  Column.
 * @param int    $term_id Term id.
 * @return string
 */
function mjb_theme_kb_topic_column( $value, $column, $term_id ) {
	return 'mjb_kb_order' === $column ? esc_html( (string) (int) get_term_meta( $term_id, 'mjb_kb_order', true ) ) : $value;
}
add_filter( 'manage_' . MJB_THEME_KB_TOPIC . '_custom_column', 'mjb_theme_kb_topic_column', 10, 3 );

/**
 * Articles list: Order column, sortable, and sorted by topic order by default.
 *
 * @param array $columns Columns.
 * @return array
 */
function mjb_theme_kb_article_columns( $columns ) {
	$columns['menu_order'] = __( 'Order', 'modern-job-board-theme' );

	return $columns;
}
add_filter( 'manage_' . MJB_THEME_KB . '_posts_columns', 'mjb_theme_kb_article_columns' );

/**
 * Order column value.
 *
 * @param string $column  Column.
 * @param int    $post_id Article.
 */
function mjb_theme_kb_article_column( $column, $post_id ) {
	if ( 'menu_order' === $column ) {
		echo esc_html( (string) get_post_field( 'menu_order', $post_id ) );
	}
}
add_action( 'manage_' . MJB_THEME_KB . '_posts_custom_column', 'mjb_theme_kb_article_column', 10, 2 );

/**
 * Sortable Order column.
 *
 * @param array $columns Sortable columns.
 * @return array
 */
function mjb_theme_kb_sortable( $columns ) {
	$columns['menu_order'] = 'menu_order';

	return $columns;
}
add_filter( 'manage_edit-' . MJB_THEME_KB . '_sortable_columns', 'mjb_theme_kb_sortable' );

/**
 * One-off set-up: topics and articles from inc/kb/, the old documentation
 * page kept as a draft, and the Documentation page turned into the
 * knowledge base home. Existing articles (same slug) are never overwritten.
 *
 * @return array|string
 */
function mjb_theme_kb_setup() {
	$file = __DIR__ . '/kb/manifest.json';

	if ( ! is_readable( $file ) ) {
		return 'no-manifest';
	}

	$manifest = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local theme file.

	if ( ! is_array( $manifest ) || empty( $manifest['topics'] ) || empty( $manifest['articles'] ) ) {
		return 'bad-manifest';
	}

	$result = array(
		'topics'   => 0,
		'articles' => 0,
	);
	$topics = array();

	foreach ( $manifest['topics'] as $index => $topic ) {
		$term = get_term_by( 'slug', $topic['slug'], MJB_THEME_KB_TOPIC );

		if ( ! $term ) {
			$made = wp_insert_term(
				$topic['name'],
				MJB_THEME_KB_TOPIC,
				array(
					'slug'        => $topic['slug'],
					'description' => $topic['description'],
				)
			);

			if ( is_wp_error( $made ) ) {
				continue;
			}

			$term = get_term( $made['term_id'], MJB_THEME_KB_TOPIC );
			update_term_meta( $term->term_id, 'mjb_kb_order', ( $index + 1 ) * 10 );
			update_term_meta( $term->term_id, 'mjb_kb_icon', $topic['icon'] );
			++$result['topics'];
		}

		$topics[ $topic['slug'] ] = (int) $term->term_id;
	}

	$replace = array(
		'{{home}}' => untrailingslashit( home_url() ),
	);
	$author  = function_exists( 'mjb_theme_site_author' ) ? mjb_theme_site_author() : 0;

	foreach ( $manifest['articles'] as $article ) {
		$existing = get_posts(
			array(
				'post_type'      => MJB_THEME_KB,
				'name'           => $article['slug'],
				'post_status'    => 'any',
				'posts_per_page' => 1,
			)
		);

		if ( $existing ) {
			continue;
		}

		$html = __DIR__ . '/kb/' . $article['slug'] . '.html';

		if ( ! is_readable( $html ) ) {
			continue;
		}

		kses_remove_filters();
		$id = wp_insert_post(
			wp_slash(
				array(
					'post_type'    => MJB_THEME_KB,
					'post_status'  => 'publish',
					'post_name'    => $article['slug'],
					'post_title'   => $article['title'],
					'post_excerpt' => $article['excerpt'],
					'post_content' => strtr( (string) file_get_contents( $html ), $replace ), // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local theme file.
					'menu_order'   => (int) $article['order'],
					'post_author'  => $author,
				)
			),
			true
		);
		kses_init();

		if ( is_wp_error( $id ) ) {
			continue;
		}

		if ( isset( $topics[ $article['topic'] ] ) ) {
			wp_set_object_terms( $id, array( $topics[ $article['topic'] ] ), MJB_THEME_KB_TOPIC );
		}

		if ( ! empty( $article['legacy'] ) ) {
			update_post_meta( $id, '_mjb_kb_legacy', (array) $article['legacy'] );
		}

		++$result['articles'];
	}

	$result['docs'] = mjb_theme_kb_convert_docs_page( $manifest );

	flush_rewrite_rules( false );

	return $result;
}

/**
 * Keep the old single-page documentation as a draft and give the
 * Documentation page the knowledge base home content.
 *
 * @param array $manifest Manifest (home content and excerpt).
 * @return string
 */
function mjb_theme_kb_convert_docs_page( $manifest ) {
	$docs = get_page_by_path( 'docs' );

	if ( ! $docs instanceof WP_Post ) {
		return 'no-docs-page';
	}

	// Only the original single-page reference is converted; a page already edited into something else is left alone.
	if ( false === strpos( $docs->post_content, 'docs-shell' ) && false === strpos( $docs->post_content, 'docs-page' ) ) {
		return 'left-as-is';
	}

	$home = __DIR__ . '/kb/home.html';

	if ( ! is_readable( $home ) ) {
		return 'no-home';
	}

	kses_remove_filters();

	$copy = wp_insert_post(
		wp_slash(
			array(
				'post_type'    => 'page',
				'post_status'  => 'draft',
				'post_name'    => 'docs-single-page',
				'post_title'   => __( 'Documentation (old single page)', 'modern-job-board-theme' ),
				'post_content' => $docs->post_content,
				'post_excerpt' => $docs->post_excerpt,
				'post_author'  => $docs->post_author,
			)
		),
		true
	);

	if ( is_wp_error( $copy ) ) {
		kses_init();
		return 'copy-failed';
	}

	$updated = wp_update_post(
		wp_slash(
			array(
				'ID'           => $docs->ID,
				'post_content' => strtr( (string) file_get_contents( $home ), array( '{{home}}' => untrailingslashit( home_url() ) ) ), // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local theme file.
				'post_excerpt' => isset( $manifest['home_excerpt'] ) ? $manifest['home_excerpt'] : '',
			)
		),
		true
	);

	kses_init();

	return is_wp_error( $updated ) ? 'update-failed' : 'converted (old page kept as draft ' . $copy . ')';
}

/**
 * Run the one-off set-up after the post type is registered.
 */
function mjb_theme_kb_maybe_setup() {
	if ( wp_installing() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return;
	}

	if ( function_exists( 'mjb_theme_site_once' ) ) {
		mjb_theme_site_once( 'mjb_theme_kb_v1', 'mjb_theme_kb_setup' );
	}
}
add_action( 'init', 'mjb_theme_kb_maybe_setup', 40 );

/**
 * Keep the Docs menu item marked as the current section on articles, topics
 * and KB search (the theme's menu walker reads the item classes directly).
 *
 * @param WP_Post[] $items Menu items.
 * @return WP_Post[]
 */
function mjb_theme_kb_menu_current( $items ) {
	if ( ! ( is_singular( MJB_THEME_KB ) || is_tax( MJB_THEME_KB_TOPIC ) || mjb_theme_kb_is_search() ) ) {
		return $items;
	}

	$docs = trim( (string) wp_parse_url( home_url( '/docs/' ), PHP_URL_PATH ), '/' );

	foreach ( $items as $item ) {
		$path = trim( (string) wp_parse_url( (string) $item->url, PHP_URL_PATH ), '/' );

		if ( $path === $docs && false === strpos( (string) $item->url, '#' ) ) {
			$item->classes   = (array) $item->classes;
			$item->classes[] = 'current-page-ancestor';
		}
	}

	return $items;
}
add_filter( 'wp_nav_menu_objects', 'mjb_theme_kb_menu_current' );

/**
 * Topic navigation (sidebar on articles and topics).
 *
 * @param int $current_id Current article id (0 on a topic page).
 * @param int $topic_id   Current topic id.
 */
function mjb_theme_kb_nav( $current_id = 0, $topic_id = 0 ) {
	echo '<nav class="mjb-kb-nav" aria-label="' . esc_attr__( 'Documentation', 'modern-job-board-theme' ) . '">';
	echo '<button type="button" class="mjb-kb-nav__toggle" aria-expanded="false" aria-controls="mjb-kb-nav-list">' . esc_html__( 'Browse all topics', 'modern-job-board-theme' ) . '</button>';
	echo '<div class="mjb-kb-nav__list" id="mjb-kb-nav-list">';

	foreach ( mjb_theme_kb_topics() as $topic ) {
		$articles = mjb_theme_kb_articles( $topic );

		if ( ! $articles ) {
			continue;
		}

		$is_current = (int) $topic->term_id === (int) $topic_id;

		printf(
			'<div class="mjb-kb-nav__group%1$s"><a class="mjb-kb-nav__topic" href="%2$s"%3$s>%4$s%5$s</a><ul>',
			$is_current ? ' is-current' : '',
			esc_url( get_term_link( $topic ) ),
			$is_current && ! $current_id ? ' aria-current="page"' : '',
			mjb_theme_kb_icon( $topic ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the helper.
			esc_html( $topic->name )
		);

		foreach ( $articles as $article ) {
			printf(
				'<li><a href="%1$s"%2$s>%3$s</a></li>',
				esc_url( get_permalink( $article ) ),
				(int) $article->ID === (int) $current_id ? ' aria-current="page"' : '',
				esc_html( mjb_theme_plain_title( $article ) )
			);
		}

		echo '</ul></div>';
	}

	echo '</div></nav>';
}

/**
 * "Still need help?" box under articles and search results.
 */
function mjb_theme_kb_help() {
	$contact = get_page_by_path( 'contact' );
	$url     = $contact instanceof WP_Post && 'publish' === $contact->post_status ? get_permalink( $contact ) : '';

	echo '<aside class="mjb-kb-help">';
	echo '<div><p class="mjb-kb-help__title">' . esc_html__( 'Still need help?', 'modern-job-board-theme' ) . '</p>';
	echo '<p class="mjb-kb-help__text">' . esc_html__( 'Tell us what you are trying to do and what happened. Include your plan and WordPress version.', 'modern-job-board-theme' ) . '</p></div>';

	if ( '' !== $url ) {
		echo '<a class="btn btn-primary" href="' . esc_url( $url ) . '">' . esc_html__( 'Contact us', 'modern-job-board-theme' ) . '</a>';
	}

	echo '</aside>';
}
