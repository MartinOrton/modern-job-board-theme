<?php
/**
 * Body content for the feature pages and the Features page.
 *
 * This is seed copy: it is written once into each page's content as core
 * blocks (see inc/conversion.php) and from then on it is edited in the block
 * editor, not here. The copy itself lives in inc/feature-content.json: for
 * each page, an ordered list of sections.
 *
 *   text   Question heading and a direct answer. The first one on a page is
 *          the answer box.
 *   shot   A real screenshot of the plugin (images/features/*.webp), as a
 *          core Image block with a caption.
 *   code   A real request or response, as a core Code block with a caption.
 *   steps  Numbered steps (core List, ordered).
 *   cards  Cards with an icon each (core List; the icon is a class on the
 *          list item, chosen in the editor sidebar).
 *   table  A reference table (core Table).
 *   plan   Which plan includes the feature.
 *   faq    Questions and answers (core Details), also published as FAQPage
 *          structured data.
 *
 * Every claim is taken from the plugin's own code and screens (Modern Job
 * Board 0.9.0-beta.167), and no heading, question or sentence is used on
 * more than one page. Placeholders in links: {pricing}, {demo}, {docs},
 * {features} and {f:slug} for another feature page. Shortcodes quoted in the
 * copy are written [[mjb_example]] in the JSON and stored in the page as
 * [<strong>mjb_example</strong>] (see mjb_theme_quote_shortcodes()).
 *
 * @package ModernJobBoardTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The seed copy, read from inc/feature-content.json.
 *
 * @return array { pages: array[], index: array, icons: string[] }
 */
function mjb_theme_feature_content_data() {
	static $data = null;

	if ( null === $data ) {
		$file = __DIR__ . '/feature-content.json';
		$json = is_readable( $file ) ? file_get_contents( $file ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local theme file.
		$data = json_decode( (string) $json, true );

		if ( ! is_array( $data ) ) {
			$data = array();
		}

		$data += array(
			'pages' => array(),
			'index' => array(),
			'icons' => array(),
		);
	}

	return $data;
}

/**
 * Icons offered for card list items, by name. Each has a mask in
 * css/list-icons.css (.mjb-i-{name}).
 *
 * @return string[]
 */
function mjb_theme_list_icons() {
	$data = mjb_theme_feature_content_data();

	return array_values( array_filter( (array) $data['icons'], 'is_string' ) );
}

/**
 * Replace the link placeholders in seed copy.
 *
 * @param string $html Copy.
 * @return string
 */
function mjb_theme_body_links( $html ) {
	$html = preg_replace_callback(
		'/\{f:([a-z0-9-]+)\}/',
		static function ( $m ) {
			return esc_url( home_url( '/features/' . $m[1] . '/' ) );
		},
		$html
	);

	return strtr(
		$html,
		array(
			'{pricing}'  => esc_url( home_url( '/#pricing' ) ),
			'{demo}'     => esc_url( home_url( '/jobs/' ) ),
			'{docs}'     => esc_url( home_url( '/docs/' ) ),
			'{features}' => esc_url( home_url( '/features/' ) ),
		)
	);
}

/**
 * Make shortcodes quoted in page text safe to store.
 *
 * WordPress's own way to show a shortcode, [[mjb_jobs]], is not enough here:
 * has_shortcode() counts it as the real thing, and both this theme and the
 * plugin use has_shortcode() to decide that a page is a job board page (other
 * header, other layout, plugin assets). So the name is wrapped in an inline
 * tag, which keeps "[" and the name apart: nothing matches, the reader still
 * sees and copies [mjb_jobs], and the block editor keeps the markup.
 *
 * @param string $html Block markup.
 * @return string
 */
function mjb_theme_quote_shortcodes( $html ) {
	if ( false === strpos( $html, '[[mjb_' ) ) {
		return $html;
	}

	return preg_replace( '/\[\[(mjb_[a-z0-9_]+)\]\]/', '[<strong>$1</strong>]', $html );
}

/**
 * Serialized core blocks used by the body sections.
 */
function mjb_theme_b_heading( $text, $anchor = '', $level = 2 ) {
	$tag   = 'h' . (int) $level;
	$attrs = 2 === (int) $level ? '' : ' {"level":' . (int) $level . '}';
	$id    = '' !== $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '';

	return "<!-- wp:heading{$attrs} -->\n<{$tag} class=\"wp-block-heading\"{$id}>{$text}</{$tag}>\n<!-- /wp:heading -->";
}

/**
 * A question heading with an anchor made from its own words, so each section
 * can be linked to directly.
 */
function mjb_theme_b_question( $text ) {
	return mjb_theme_b_heading( $text, sanitize_title( wp_strip_all_tags( $text ) ) );
}

function mjb_theme_b_paragraph( $html, $class = '' ) {
	if ( '' === $class ) {
		return "<!-- wp:paragraph -->\n<p>{$html}</p>\n<!-- /wp:paragraph -->";
	}

	return "<!-- wp:paragraph {\"className\":\"{$class}\"} -->\n<p class=\"{$class}\">{$html}</p>\n<!-- /wp:paragraph -->";
}

/**
 * Numbered steps: items are [ title, text ].
 */
function mjb_theme_b_steps( $items ) {
	$lis = array();

	foreach ( $items as $item ) {
		$lis[] = "<!-- wp:list-item -->\n<li><strong>{$item[0]}</strong> {$item[1]}</li>\n<!-- /wp:list-item -->";
	}

	return "<!-- wp:list {\"ordered\":true,\"className\":\"mjb-fb-steps\"} -->\n<ol class=\"wp-block-list mjb-fb-steps\">" . implode( "\n\n", $lis ) . "</ol>\n<!-- /wp:list -->";
}

/**
 * Icon cards: items are [ icon, title, text ]. The icon is the class
 * mjb-i-{icon} on the list item.
 */
function mjb_theme_b_cards( $items ) {
	$lis = array();

	foreach ( $items as $item ) {
		$class = 'mjb-i-' . sanitize_html_class( $item[0] );
		$lis[] = "<!-- wp:list-item {\"className\":\"{$class}\"} -->\n<li class=\"{$class}\"><strong>{$item[1]}</strong> {$item[2]}</li>\n<!-- /wp:list-item -->";
	}

	return "<!-- wp:list {\"className\":\"mjb-fb-cards\"} -->\n<ul class=\"wp-block-list mjb-fb-cards\">" . implode( "\n\n", $lis ) . "</ul>\n<!-- /wp:list -->";
}

function mjb_theme_b_details( $question, $answer ) {
	return "<!-- wp:details {\"className\":\"mjb-fb-faq__item\"} -->\n<details class=\"wp-block-details mjb-fb-faq__item\"><summary>{$question}</summary><!-- wp:paragraph -->\n<p>{$answer}</p>\n<!-- /wp:paragraph --></details>\n<!-- /wp:details -->";
}

function mjb_theme_b_group( $class, $inner ) {
	return "<!-- wp:group {\"className\":\"{$class}\"} -->\n<div class=\"wp-block-group {$class}\">" . $inner . "</div>\n<!-- /wp:group -->";
}

function mjb_theme_b_table( $rows, $class ) {
	$head = '<thead><tr>' . implode( '', array_map( static function ( $cell ) {
		return '<th>' . $cell . '</th>';
	}, $rows[0] ) ) . '</tr></thead>';
	$body = '';

	foreach ( array_slice( $rows, 1 ) as $row ) {
		$body .= '<tr>' . implode( '', array_map( static function ( $cell ) {
			return '<td>' . $cell . '</td>';
		}, $row ) ) . '</tr>';
	}

	return "<!-- wp:table {\"className\":\"{$class}\"} -->\n<figure class=\"wp-block-table {$class}\"><table class=\"has-fixed-layout\">{$head}<tbody>{$body}</tbody></table></figure>\n<!-- /wp:table -->";
}

/**
 * A screenshot from images/features/ (not linked).
 *
 * @param string $name    File name without extension.
 * @param string $alt     Alternative text.
 * @param string $caption Caption (HTML).
 * @param int    $width   Pixel width of the file.
 * @return string
 */
function mjb_theme_b_shot( $name, $alt, $caption, $width = 0 ) {
	$url   = esc_url( get_template_directory_uri() . '/images/features/' . sanitize_file_name( $name ) . '.webp' );
	$class = 'mjb-fb-shot' . ( $width >= 1500 ? ' mjb-fb-shot--wide' : '' );

	return "<!-- wp:image {\"sizeSlug\":\"full\",\"linkDestination\":\"none\",\"className\":\"{$class}\"} -->\n<figure class=\"wp-block-image size-full {$class}\"><img src=\"{$url}\" alt=\"" . esc_attr( $alt ) . "\"/><figcaption class=\"wp-element-caption\">{$caption}</figcaption></figure>\n<!-- /wp:image -->";
}

/**
 * A screenshot in a browser window with the phone version on top of it,
 * bottom right: a Group (mjb-devices) holding two core Image blocks and a
 * caption. The frames are SVG backgrounds (images/frames/desktop.svg and
 * phone.svg, see .mjb-devices in css/styles.css); the screenshots sit on top.
 *
 * Desktop screenshots are 1280 x 800 CSS px (16:10), phone screenshots
 * 390 x 763, in images/features/desktop/ and images/features/mobile/. To
 * update one, replace the WebP file with one of the same shape: the frames
 * and the page content stay as they are.
 *
 * @param string $name        File name without extension.
 * @param string $alt_desktop Alternative text for the desktop screenshot.
 * @param string $alt_mobile  Alternative text for the phone screenshot.
 * @param string $caption     Caption (HTML).
 * @return string
 */
function mjb_theme_b_devices( $name, $alt_desktop, $alt_mobile, $caption ) {
	$base    = get_template_directory_uri() . '/images/features/';
	$file    = sanitize_file_name( $name ) . '.webp';
	$desktop = esc_url( $base . 'desktop/' . $file );
	$mobile  = esc_url( $base . 'mobile/' . $file );

	$inner = "<!-- wp:image {\"sizeSlug\":\"full\",\"linkDestination\":\"none\",\"className\":\"mjb-devices__desktop\"} -->\n<figure class=\"wp-block-image size-full mjb-devices__desktop\"><img src=\"{$desktop}\" alt=\"" . esc_attr( $alt_desktop ) . "\"/></figure>\n<!-- /wp:image -->"
		. "\n\n<!-- wp:image {\"sizeSlug\":\"full\",\"linkDestination\":\"none\",\"className\":\"mjb-devices__mobile\"} -->\n<figure class=\"wp-block-image size-full mjb-devices__mobile\"><img src=\"{$mobile}\" alt=\"" . esc_attr( $alt_mobile ) . "\"/></figure>\n<!-- /wp:image -->"
		. "\n\n" . mjb_theme_b_paragraph( $caption, 'mjb-devices__caption' );

	return mjb_theme_b_group( 'mjb-devices', $inner );
}

/**
 * A code sample, escaped the way the Code block saves it.
 */
function mjb_theme_b_code( $code ) {
	$code = str_replace( array( '&', '<', '>', '[' ), array( '&amp;', '&lt;', '&gt;', '&#91;' ), $code );

	return "<!-- wp:code {\"className\":\"mjb-fb-code\"} -->\n<pre class=\"wp-block-code mjb-fb-code\"><code>{$code}</code></pre>\n<!-- /wp:code -->";
}

function mjb_theme_b_faq( $heading, $items ) {
	$inner = mjb_theme_b_question( $heading );

	foreach ( $items as $item ) {
		$inner .= "\n\n" . mjb_theme_b_details( $item[0], $item[1] );
	}

	return mjb_theme_b_group( 'mjb-fb-section mjb-fb-faq', $inner );
}

/**
 * Blocks for one section of a feature page.
 *
 * @param array $section Section from the seed copy.
 * @param bool  $first   Whether it opens the page (the answer box).
 * @return string
 */
function mjb_theme_feature_section_blocks( $section, $first = false ) {
	$type    = isset( $section['t'] ) ? $section['t'] : '';
	$heading = isset( $section['h'] ) ? mjb_theme_b_question( $section['h'] ) . "\n\n" : '';

	switch ( $type ) {
		case 'text':
			$paragraphs = implode( "\n\n", array_map( 'mjb_theme_b_paragraph', (array) $section['p'] ) );

			return mjb_theme_b_group( $first ? 'mjb-fb-answer' : 'mjb-fb-section', $heading . $paragraphs );

		case 'shot':
			if ( ! empty( $section['devices'] ) ) {
				return mjb_theme_b_devices( $section['img'], $section['dalt'], $section['malt'], $section['cap'] );
			}

			return mjb_theme_b_shot( $section['img'], $section['alt'], $section['cap'], isset( $section['w'] ) ? (int) $section['w'] : 0 );

		case 'code':
			return mjb_theme_b_group(
				'mjb-fb-section mjb-fb-example',
				$heading . mjb_theme_b_code( $section['code'] ) . "\n\n" . mjb_theme_b_paragraph( $section['cap'], 'mjb-fb-caption' )
			);

		case 'steps':
			return mjb_theme_b_group( 'mjb-fb-section', $heading . mjb_theme_b_steps( $section['items'] ) );

		case 'cards':
			return mjb_theme_b_group( 'mjb-fb-section', $heading . mjb_theme_b_cards( $section['items'] ) );

		case 'table':
			return mjb_theme_b_group(
				'mjb-fb-section',
				$heading . mjb_theme_b_table( $section['rows'], 'mjb-fb-table mjb-fb-table--ref mjb-fb-table--c' . count( $section['rows'][0] ) )
			);

		case 'plan':
			return mjb_theme_b_group( 'mjb-fb-section mjb-fb-plan', $heading . mjb_theme_b_paragraph( $section['p'] ) );

		case 'faq':
			return mjb_theme_b_faq( $section['h'], $section['items'] );
	}

	return '';
}

/**
 * Body blocks for a feature page ('' when there is no copy for the slug).
 *
 * @param string $slug Feature slug.
 * @return string
 */
function mjb_theme_feature_body_blocks( $slug ) {
	$data = mjb_theme_feature_content_data();

	if ( empty( $data['pages'][ $slug ] ) || ! is_array( $data['pages'][ $slug ] ) ) {
		return '';
	}

	$sections = array();

	foreach ( $data['pages'][ $slug ] as $i => $section ) {
		$blocks = mjb_theme_feature_section_blocks( $section, 0 === $i );

		if ( '' !== $blocks ) {
			$sections[] = $blocks;
		}
	}

	return mjb_theme_quote_shortcodes( mjb_theme_body_links( mjb_theme_b_group( 'mjb-feature-body', implode( "\n\n", $sections ) ) ) );
}

/**
 * Features page: the answer that goes before the grid ('' without copy).
 *
 * @return string
 */
function mjb_theme_features_index_intro_blocks() {
	$data = mjb_theme_feature_content_data();
	$c    = $data['index'];

	if ( empty( $c['intro'] ) ) {
		return '';
	}

	return mjb_theme_body_links(
		mjb_theme_b_group(
			'mjb-feature-body mjb-feature-body--intro',
			mjb_theme_b_group( 'mjb-fb-answer', mjb_theme_b_question( $c['intro']['h'] ) . "\n\n" . mjb_theme_b_paragraph( $c['intro']['p'] ) )
		)
	);
}

/**
 * Features page: plans table and FAQ, after the grid ('' without copy).
 *
 * @return string
 */
function mjb_theme_features_index_after_blocks() {
	$data = mjb_theme_feature_content_data();
	$c    = $data['index'];

	if ( empty( $c['plans'] ) ) {
		return '';
	}

	$plans = mjb_theme_b_group(
		'mjb-fb-section mjb-fb-plans',
		mjb_theme_b_question( $c['plans_h'] ) . "\n\n" . mjb_theme_b_table( $c['plans'], 'mjb-fb-table' ) . "\n\n" . mjb_theme_b_paragraph( '<a href="{pricing}">See full pricing</a>' )
	);

	return mjb_theme_quote_shortcodes( mjb_theme_body_links( mjb_theme_b_group( 'mjb-feature-body mjb-feature-body--after', $plans . "\n\n" . mjb_theme_b_faq( $c['faq_h'], $c['faq'] ) ) ) );
}

/**
 * Screenshot sizes, by file name: [ width, height ] in pixels. The captures
 * are 1.5x, so they are shown at two thirds of that.
 *
 * @return array[]
 */
function mjb_theme_feature_shot_sizes() {
	static $sizes = null;

	if ( null === $sizes ) {
		$sizes = array();
		$data  = mjb_theme_feature_content_data();

		foreach ( (array) $data['pages'] as $sections ) {
			foreach ( (array) $sections as $section ) {
				if ( isset( $section['t'], $section['img'], $section['w'], $section['hgt'] ) && 'shot' === $section['t'] ) {
					$sizes[ $section['img'] ] = array( (int) $section['w'], (int) $section['hgt'] );
				}
			}
		}
	}

	return $sizes;
}

/**
 * Give the theme's screenshots a width and height (no layout shift while
 * they load, and WordPress then lazy-loads them). Images picked from the
 * Media Library already have both and are left alone.
 *
 * @param string $html Rendered Image block.
 * @return string
 */
function mjb_theme_feature_shot_dimensions( $html ) {
	if ( false === strpos( $html, '/images/features/' ) || ! preg_match( '/<img\b[^>]*>/i', $html, $img ) || preg_match( '/\swidth=/i', $img[0] ) ) {
		return $html;
	}

	if ( ! preg_match( '#/images/features/(?:(desktop|mobile)/)?([a-z0-9-]+)\.webp#i', $img[0], $m ) ) {
		return $html;
	}

	// Device screenshots have fixed shapes (see mjb_theme_b_devices()).
	if ( '' !== $m[1] ) {
		$size = 'desktop' === strtolower( $m[1] ) ? array( 1280, 800 ) : array( 390, 763 );
		$tag  = preg_replace( '/<img\b/i', '<img width="' . $size[0] . '" height="' . $size[1] . '"', $img[0], 1 );

		return str_replace( $img[0], $tag, $html );
	}

	$m[1]  = $m[2];
	$sizes = mjb_theme_feature_shot_sizes();

	if ( empty( $sizes[ $m[1] ] ) ) {
		return $html;
	}

	list( $width, $height ) = $sizes[ $m[1] ];
	$tag                    = preg_replace( '/<img\b/i', '<img width="' . (int) round( $width / 1.5 ) . '" height="' . (int) round( $height / 1.5 ) . '"', $img[0], 1 );

	return str_replace( $img[0], $tag, $html );
}
add_filter( 'render_block_core/image', 'mjb_theme_feature_shot_dimensions' );

/**
 * The theme's screenshots are pictures, not links: drop a link that only
 * opens the screenshot file itself (older seeded content had one), and add
 * the file's modification time to its address so a replaced screenshot is
 * shown at once instead of a cached copy. Links to anything else are kept.
 *
 * @param string $html Rendered Image block.
 * @return string
 */
function mjb_theme_feature_shot_render( $html ) {
	if ( false === strpos( $html, '/images/features/' ) ) {
		return $html;
	}

	$html = preg_replace( '#<a\b[^>]*\shref="[^"]*/images/features/[^"]*\.webp"[^>]*>\s*(<img\b[^>]*>)\s*</a>#i', '$1', $html );

	return preg_replace_callback(
		'#(<img\b[^>]*\ssrc=")([^"?]*/images/features/((?:desktop/|mobile/)?[a-z0-9-]+\.webp))"#i',
		static function ( $m ) {
			$file = get_template_directory() . '/images/features/' . $m[3];

			return is_readable( $file ) ? $m[1] . $m[2] . '?ver=' . filemtime( $file ) . '"' : $m[0];
		},
		$html
	);
}
add_filter( 'render_block_core/image', 'mjb_theme_feature_shot_render', 11 );

/**
 * Add the column heading to every cell of a reference table, so the phone
 * layout (one card per row) can label each value.
 *
 * @param string $html  Rendered Table block.
 * @param array  $block Parsed block.
 * @return string
 */
function mjb_theme_table_cell_labels( $html, $block ) {
	if ( false === strpos( $html, 'mjb-fb-table--ref' ) || ! preg_match( '/<thead\b.*?<\/thead>/is', $html, $head ) || ! preg_match_all( '/<th\b[^>]*>(.*?)<\/th>/is', $head[0], $cells ) ) {
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
add_filter( 'render_block_core/table', 'mjb_theme_table_cell_labels', 10, 2 );

/**
 * Safety net for a shortcode typed into page text with double brackets while
 * the plugin is not active: WordPress only turns [[x]] into [x] for
 * shortcodes it knows. (Seeded copy does not use double brackets: see
 * mjb_theme_quote_shortcodes().)
 *
 * @param string $content Rendered content.
 * @return string
 */
function mjb_theme_show_quoted_shortcodes( $content ) {
	if ( false === strpos( $content, '[[mjb_' ) ) {
		return $content;
	}

	return preg_replace( '/\[\[(mjb_[a-z0-9_]+)\]\]/', '[$1]', $content );
}
add_filter( 'the_content', 'mjb_theme_show_quoted_shortcodes', 12 );

/**
 * FAQPage structured data from the FAQ blocks on the current page.
 *
 * Built from the page content at render time, so it always matches what the
 * page shows, including any edits made in the block editor. Google now limits
 * FAQ rich results to a few kinds of site, but answer engines and AI search
 * still read this markup.
 */
function mjb_theme_faq_schema() {
	if ( ! is_singular() || is_front_page() ) {
		return;
	}

	$post = get_queried_object();

	if ( ! $post instanceof WP_Post || false === strpos( $post->post_content, 'mjb-fb-faq__item' ) ) {
		return;
	}

	$items = array();
	$walk  = static function ( $blocks ) use ( &$walk, &$items ) {
		foreach ( $blocks as $block ) {
			if ( 'core/details' === $block['blockName'] && mjb_theme_block_has_class( $block, 'mjb-fb-faq__item' ) ) {
				$question = preg_match( '/<summary[^>]*>(.*?)<\/summary>/is', $block['innerHTML'], $m ) ? $m[1] : '';
				$answer   = '';

				foreach ( $block['innerBlocks'] as $inner ) {
					$answer .= ' ' . do_shortcode( render_block( $inner ) );
				}

				$question = trim( html_entity_decode( wp_strip_all_tags( mjb_theme_doc_text( $question ) ), ENT_QUOTES, 'UTF-8' ) );
				$answer   = trim( preg_replace( '/\s+/', ' ', html_entity_decode( wp_strip_all_tags( $answer ), ENT_QUOTES, 'UTF-8' ) ) );
				$answer   = preg_replace( '/\[\[([a-z0-9_]+)\]\]/', '[$1]', $answer );

				if ( '' !== $question && '' !== $answer ) {
					$items[] = array(
						'@type'          => 'Question',
						'name'           => $question,
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text'  => $answer,
						),
					);
				}
			}

			if ( ! empty( $block['innerBlocks'] ) ) {
				$walk( $block['innerBlocks'] );
			}
		}
	};

	$walk( parse_blocks( $post->post_content ) );

	if ( empty( $items ) ) {
		return;
	}

	$data = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $items,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-encoded.
}
add_action( 'wp_head', 'mjb_theme_faq_schema', 21 );

/**
 * Meta description from the page excerpt (the intro line under the title),
 * unless an SEO plugin is active and already writes one.
 */
function mjb_theme_meta_description() {
	if ( ! is_singular() || defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || class_exists( 'The_SEO_Framework\Load' ) ) {
		return;
	}

	$post = get_queried_object();

	if ( ! $post instanceof WP_Post || '' === trim( (string) $post->post_excerpt ) ) {
		return;
	}

	$text = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( mjb_theme_doc_text( $post->post_excerpt ) ) ) );

	if ( function_exists( 'mb_strlen' ) && mb_strlen( $text ) > 160 ) {
		$text = rtrim( mb_substr( $text, 0, 157 ), " ,.;:" ) . '…';
	}

	echo '<meta name="description" content="' . esc_attr( $text ) . '">' . "\n";
}
add_action( 'wp_head', 'mjb_theme_meta_description', 1 );
