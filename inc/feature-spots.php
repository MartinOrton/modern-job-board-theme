<?php
/**
 * Spot illustrations for the question boxes on feature pages, the Features
 * page and About Us: the answer box (.mjb-fb-answer) and the plan box
 * (.mjb-fb-plan).
 *
 * Each illustration is three layered pieces of inline SVG, like the hero
 * card: a tinted back panel, a white card drawn for the page's topic and a
 * small icon chip. The panel and chip float on their own slow rhythms
 * (.mjb-spot__* in css/styles.css). They are added when the page is shown, so
 * the stored page content stays plain blocks and editors never see SVG.
 *
 * @package ModernJobBoardTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Icon chip glyphs (24 x 24, stroked; Lucide, ISC licence).
 *
 * @return string[]
 */
function mjb_theme_spot_glyphs() {
	return array(
		'send'     => '<path d="M14.5 21.7a.5.5 0 0 0 .9 0l6.5-19a.5.5 0 0 0-.6-.6l-19 6.5a.5.5 0 0 0 0 .9l7.9 3.2a2 2 0 0 1 1.1 1.1z"/><path d="m21.9 2.1-11 11"/>',
		'link'     => '<path d="M10 13a5 5 0 0 0 7.5.5l3-3a5 5 0 0 0-7-7l-1.7 1.7"/><path d="M14 11a5 5 0 0 0-7.5-.5l-3 3a5 5 0 0 0 7 7l1.7-1.7"/>',
		'user'     => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m16 11 2 2 4-4"/>',
		'lock'     => '<rect width="18" height="11" x="3" y="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
		'coins'    => '<circle cx="8" cy="8" r="6"/><path d="M18.1 10.4A6 6 0 1 1 10.3 18"/><path d="M7 6h1v4"/><path d="m16.7 13.9.7.7-2.8 2.8"/>',
		'plus'     => '<path d="M5 12h14"/><path d="M12 5v14"/>',
		'trend'    => '<path d="m22 7-8.5 8.5-5-5L2 17"/><path d="M16 7h6v6"/>',
		'upload'   => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M12 12v6"/><path d="m15 15-3-3-3 3"/>',
		'nodes'    => '<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.6 13.5 6.8 4"/><path d="m15.4 6.5-6.8 4"/>',
		'bell'     => '<g class="mjb-spot__swing"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.9 1.9 0 0 0 3.4 0"/></g>',
		'star'     => '<path d="m12 2 3.1 6.3 6.9 1-5 4.9 1.2 6.8-6.2-3.2-6.2 3.2L7 14.2 2 9.3l6.9-1z"/>',
		'pie'      => '<path d="M21.2 15.9A10 10 0 1 1 8 2.8"/><path d="M22 12A10 10 0 0 0 12 2v10z"/>',
		'case'     => '<rect width="20" height="14" x="2" y="7" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
		'sparkles' => '<path d="M9.9 15.5a2 2 0 0 0-1.4-1.4l-6.1-1.6a.5.5 0 0 1 0-1l6.1-1.6a2 2 0 0 0 1.4-1.4l1.6-6.1a.5.5 0 0 1 1 0l1.6 6.1a2 2 0 0 0 1.4 1.4l6.1 1.6a.5.5 0 0 1 0 1l-6.1 1.6a2 2 0 0 0-1.4 1.4l-1.6 6.1a.5.5 0 0 1-1 0z"/><path d="M20 3v4"/><path d="M22 5h-4"/>',
		'grid'     => '<rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>',
		'badge'    => '<path d="M3.9 8.6a4 4 0 0 1 4.7-4.7 4 4 0 0 1 6.8 0 4 4 0 0 1 4.7 4.7 4 4 0 0 1 0 6.8 4 4 0 0 1-4.7 4.7 4 4 0 0 1-6.8 0 4 4 0 0 1-4.7-4.7 4 4 0 0 1 0-6.8z"/><path d="m9 12 2 2 4-4"/>',
		'check'    => '<path d="M20 6 9 17l-5-5"/>',
		'zap'      => '<path d="M4 14a1 1 0 0 1-.8-1.6l9.9-10.2a.5.5 0 0 1 .9.5l-1.9 6A1 1 0 0 0 13 10h7a1 1 0 0 1 .8 1.6l-9.9 10.2a.5.5 0 0 1-.9-.5l1.9-6A1 1 0 0 0 11 14z"/>',
		'building' => '<path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/>',
	);
}

/**
 * The illustrations. Each has the card's inner markup, a chip glyph and the
 * chip's colour ('teal', 'orange' or 'deep'); 'card' => 'dark' draws a dark
 * card (code). Coordinates are in a 160 x 140 box; the card is x 10-132,
 * y 28-126 and the chip sits top right, so the card's top-right corner stays
 * clear.
 *
 * @return array[]
 */
function mjb_theme_spot_arts() {
	$star = 'm3 0 .9 1.9 2.1.3-1.5 1.5.4 2.1L3 4.8l-1.9 1 .4-2.1L0 2.2l2.1-.3z';
	$pin  = 'M0 0c-5-6-8-9.5-8-13a8 8 0 0 1 16 0c0 3.5-3 7-8 13z';

	return array(
		// Post a Job form, a field being typed in.
		'form'      => array(
			'glyph' => 'send',
			'svg'   => '<rect x="22" y="40" width="44" height="6" rx="3" fill="#1f2937"/>
<rect x="22" y="54" width="98" height="15" rx="5" fill="#f5faf9" stroke="#cfe3e1"/><rect x="28" y="59.5" width="44" height="4" rx="2" fill="#0F766E"/><rect class="mjb-spot__blink" x="75" y="57" width="1.6" height="9" rx=".8" fill="#F97316"/>
<rect x="22" y="75" width="46" height="15" rx="5" fill="#f5faf9" stroke="#cfe3e1"/><rect x="28" y="80.5" width="26" height="4" rx="2" fill="#14b8a6"/>
<rect x="74" y="75" width="46" height="15" rx="5" fill="#f5faf9" stroke="#cfe3e1"/><rect x="80" y="80.5" width="30" height="4" rx="2" fill="#a9d1cf"/>
<rect x="22" y="100" width="44" height="15" rx="7.5" fill="#F97316"/><rect x="31" y="105.5" width="26" height="4" rx="2" fill="#fff"/>',
		),
		// Predictive search with suggestions; the chip is the pretty URL.
		'search'    => array(
			'glyph' => 'link',
			'svg'   => '<rect x="20" y="40" width="84" height="18" rx="9" fill="#fff" stroke="#0F766E" stroke-width="1.5"/><circle cx="30" cy="49" r="4" fill="none" stroke="#0F766E" stroke-width="1.6"/><path d="m33 52 3 3" stroke="#0F766E" stroke-width="1.6" stroke-linecap="round"/><rect x="41" y="47" width="32" height="4" rx="2" fill="#1f2937"/><rect class="mjb-spot__blink" x="76" y="44.5" width="1.6" height="9" rx=".8" fill="#F97316"/>
<rect x="20" y="64" width="102" height="16" rx="5" fill="#E0F2F1"/><rect x="25" y="68" width="8" height="8" rx="2.5" fill="#0F766E"/><rect x="38" y="70" width="46" height="4" rx="2" fill="#0F766E"/><rect x="100" y="69" width="16" height="6" rx="3" fill="#F97316"/>
<rect x="20" y="84" width="102" height="16" rx="5" fill="#f5faf9"/><rect x="25" y="88" width="8" height="8" rx="2.5" fill="#14b8a6"/><rect x="38" y="90" width="54" height="4" rx="2" fill="#94a3b8"/>
<rect x="20" y="104" width="102" height="16" rx="5" fill="#f5faf9"/><rect x="25" y="108" width="8" height="8" rx="2.5" fill="#a9d1cf"/><rect x="38" y="110" width="40" height="4" rx="2" fill="#cbd5e1"/>',
		),
		// A candidate and an employer, with profile progress.
		'accounts'  => array(
			'glyph' => 'user',
			'svg'   => '<circle cx="32" cy="50" r="10" fill="#E0F2F1"/><circle cx="32" cy="47" r="3.6" fill="#0F766E"/><path d="M25.5 56a6.5 5 0 0 1 13 0z" fill="#0F766E"/>
<rect x="48" y="44" width="40" height="5" rx="2.5" fill="#1f2937"/><rect x="48" y="53" width="26" height="4" rx="2" fill="#a9d1cf"/>
<path d="M20 68h102" stroke="#e5efee"/>
<rect x="22" y="76" width="20" height="20" rx="6" fill="#14b8a6"/><rect x="27" y="81" width="4" height="4" rx="1" fill="#fff"/><rect x="33" y="81" width="4" height="4" rx="1" fill="#fff"/><rect x="27" y="87" width="4" height="4" rx="1" fill="#fff"/><rect x="33" y="87" width="4" height="4" rx="1" fill="#fff"/>
<rect x="48" y="78" width="46" height="5" rx="2.5" fill="#1f2937"/><rect x="48" y="87" width="30" height="4" rx="2" fill="#a9d1cf"/>
<rect x="22" y="106" width="98" height="6" rx="3" fill="#E0F2F1"/><rect class="mjb-spot__fill" x="22" y="106" width="66" height="6" rx="3" fill="#14b8a6"/>',
		),
		// A CV on file, kept private.
		'private'   => array(
			'glyph' => 'lock',
			'svg'   => '<circle cx="31" cy="48" r="8" fill="#E0F2F1"/><circle cx="31" cy="45.5" r="3" fill="#0F766E"/><path d="M25.5 53a5.5 4 0 0 1 11 0z" fill="#0F766E"/>
<rect x="45" y="42" width="40" height="5" rx="2.5" fill="#1f2937"/><rect x="45" y="51" width="28" height="4" rx="2" fill="#a9d1cf"/>
<rect x="22" y="66" width="98" height="4" rx="2" fill="#cfe3e1"/><rect x="22" y="75" width="86" height="4" rx="2" fill="#cfe3e1"/><rect x="22" y="84" width="94" height="4" rx="2" fill="#cfe3e1"/><rect x="22" y="93" width="60" height="4" rx="2" fill="#cfe3e1"/>
<rect x="22" y="106" width="30" height="10" rx="5" fill="#E0F2F1"/><rect x="56" y="106" width="24" height="10" rx="5" fill="#E0F2F1"/>',
		),
		// Checkout: a chosen package and a Pay button.
		'money'     => array(
			'glyph' => 'coins',
			'chip'  => 'orange',
			'svg'   => '<rect x="22" y="40" width="40" height="6" rx="3" fill="#1f2937"/>
<rect x="22" y="54" width="98" height="16" rx="5" fill="#f5faf9" stroke="#cfe3e1"/><circle cx="31" cy="62" r="3.5" fill="none" stroke="#a9d1cf" stroke-width="1.4"/><rect x="38" y="60" width="38" height="4" rx="2" fill="#94a3b8"/><rect x="96" y="59" width="18" height="6" rx="3" fill="#a9d1cf"/>
<rect x="22" y="74" width="98" height="16" rx="5" fill="#E0F2F1" stroke="#14b8a6"/><circle cx="31" cy="82" r="3.5" fill="#0F766E"/><rect x="38" y="80" width="44" height="4" rx="2" fill="#0F766E"/><rect x="96" y="79" width="18" height="6" rx="3" fill="#0F766E"/>
<path d="M22 98h98" stroke="#e5efee"/>
<rect x="22" y="104" width="30" height="5" rx="2.5" fill="#1f2937"/><rect x="78" y="100" width="42" height="15" rx="7.5" fill="#F97316"/><rect x="86" y="105.5" width="26" height="4" rx="2" fill="#fff"/>',
		),
		// Blocks on a page, with an empty slot for the next one.
		'blocks'    => array(
			'glyph' => 'plus',
			'svg'   => '<rect x="22" y="38" width="80" height="13" rx="4" fill="#E0F2F1"/><rect x="27" y="42.5" width="36" height="4" rx="2" fill="#0F766E"/>
<rect x="22" y="57" width="47" height="31" rx="6" fill="#f5faf9" stroke="#cfe3e1"/><path d="m27 83 10-11 7 7 5-5 9 9z" fill="#a9d1cf"/><circle cx="58" cy="65" r="3" fill="#14b8a6"/>
<rect x="73" y="57" width="47" height="31" rx="6" fill="#0F766E"/><rect x="79" y="64" width="30" height="4" rx="2" fill="#fff"/><rect x="79" y="72" width="22" height="3.5" rx="1.75" fill="#99f6e4"/><rect x="79" y="79" width="14" height="5" rx="2.5" fill="#F97316"/>
<rect class="mjb-spot__pulse" x="22" y="94" width="98" height="22" rx="6" fill="#f0fdfa" stroke="#14b8a6" stroke-width="1.3" stroke-dasharray="4 3"/><path d="M71 101v8M67 105h8" stroke="#14b8a6" stroke-width="1.8" stroke-linecap="round"/>',
		),
		// A search result with rich-result stars.
		'seo'       => array(
			'glyph' => 'trend',
			'svg'   => '<circle cx="26" cy="44" r="4" fill="#14b8a6"/><rect x="34" y="42" width="38" height="4" rx="2" fill="#a9d1cf"/>
<rect x="22" y="53" width="80" height="6" rx="3" fill="#0F766E"/>
<g fill="#F97316"><path transform="translate(22 65)" d="' . $star . '"/><path transform="translate(30 65)" d="' . $star . '"/><path transform="translate(38 65)" d="' . $star . '"/><path transform="translate(46 65)" d="' . $star . '"/><path transform="translate(54 65)" d="' . $star . '" opacity=".35"/></g><rect x="64" y="66" width="20" height="3.5" rx="1.75" fill="#cbd5e1"/>
<rect x="22" y="77" width="98" height="4" rx="2" fill="#cbd5e1"/><rect x="22" y="85" width="76" height="4" rx="2" fill="#cbd5e1"/>
<circle cx="26" cy="102" r="4" fill="#cfe3e1"/><rect x="34" y="100" width="32" height="4" rx="2" fill="#e2e8f0"/><rect x="22" y="110" width="64" height="5" rx="2.5" fill="#a9d1cf"/><rect x="22" y="119" width="88" height="3.5" rx="1.75" fill="#e2e8f0"/>',
		),
		// Custom fields: switches, a select and tags.
		'fields'    => array(
			'glyph' => 'upload',
			'svg'   => '<rect x="22" y="42" width="36" height="4" rx="2" fill="#94a3b8"/><rect x="84" y="39" width="20" height="10" rx="5" fill="#14b8a6"/><circle cx="99" cy="44" r="3.6" fill="#fff"/>
<rect x="22" y="56" width="98" height="15" rx="5" fill="#f5faf9" stroke="#cfe3e1"/><rect x="28" y="61.5" width="40" height="4" rx="2" fill="#1f2937"/><path d="m108 62 3 3 3-3" fill="none" stroke="#0F766E" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
<rect x="22" y="81" width="30" height="4" rx="2" fill="#94a3b8"/><rect x="100" y="78" width="20" height="10" rx="5" fill="#cfe3e1"/><circle cx="105" cy="83" r="3.6" fill="#fff"/>
<rect x="22" y="96" width="26" height="10" rx="5" fill="#E0F2F1"/><rect x="52" y="96" width="32" height="10" rx="5" fill="#ffedd5"/><rect x="88" y="96" width="22" height="10" rx="5" fill="#E0F2F1"/>
<rect x="22" y="113" width="60" height="4" rx="2" fill="#cbd5e1"/>',
		),
		// JSON on a dark card, with a live status dot.
		'api'       => array(
			'glyph' => 'nodes',
			'card'  => 'dark',
			'svg'   => '<circle cx="22" cy="40" r="2.2" fill="#F97316"/><circle cx="29" cy="40" r="2.2" fill="#a9d1cf"/><circle cx="36" cy="40" r="2.2" fill="#5eead4"/>
<rect x="22" y="51" width="10" height="4" rx="2" fill="#5eead4"/>
<rect x="30" y="60" width="22" height="4" rx="2" fill="#99f6e4"/><rect x="56" y="60" width="36" height="4" rx="2" fill="#F97316"/>
<rect x="30" y="69" width="18" height="4" rx="2" fill="#99f6e4"/><rect x="52" y="69" width="48" height="4" rx="2" fill="#fed7aa"/>
<rect x="30" y="78" width="26" height="4" rx="2" fill="#99f6e4"/><rect x="60" y="78" width="20" height="4" rx="2" fill="#5eead4"/>
<rect x="38" y="87" width="30" height="4" rx="2" fill="#99f6e4"/><rect x="72" y="87" width="34" height="4" rx="2" fill="#fed7aa"/>
<rect x="22" y="96" width="10" height="4" rx="2" fill="#5eead4"/>
<rect x="22" y="107" width="38" height="11" rx="5.5" fill="#14b8a6" fill-opacity=".22"/><circle class="mjb-spot__pulse" cx="29" cy="112.5" r="2.4" fill="#5eead4"/><rect x="35" y="110.5" width="19" height="4" rx="2" fill="#5eead4"/>',
		),
		// An alert email listing new jobs.
		'alerts'    => array(
			'glyph' => 'bell',
			'dot'   => true,
			'svg'   => '<rect x="22" y="38" width="18" height="13" rx="3" fill="#E0F2F1"/><path d="m23 40 8 5.8 8-5.8" fill="none" stroke="#0F766E" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><rect x="46" y="39" width="40" height="5" rx="2.5" fill="#1f2937"/><rect x="46" y="47" width="26" height="3.5" rx="1.75" fill="#a9d1cf"/>
<rect x="22" y="60" width="98" height="18" rx="5" fill="#f5faf9"/><rect x="27" y="64" width="10" height="10" rx="3" fill="#0F766E"/><rect x="42" y="65" width="44" height="4" rx="2" fill="#1f2937"/><rect x="42" y="71" width="28" height="3" rx="1.5" fill="#a9d1cf"/><rect x="97" y="65" width="17" height="8" rx="4" fill="#F97316"/>
<rect x="22" y="82" width="98" height="18" rx="5" fill="#f5faf9"/><rect x="27" y="86" width="10" height="10" rx="3" fill="#14b8a6"/><rect x="42" y="87" width="50" height="4" rx="2" fill="#94a3b8"/><rect x="42" y="93" width="24" height="3" rx="1.5" fill="#cfe3e1"/>
<rect x="22" y="104" width="98" height="18" rx="5" fill="#f5faf9"/><rect x="27" y="108" width="10" height="10" rx="3" fill="#a9d1cf"/><rect x="42" y="109" width="38" height="4" rx="2" fill="#94a3b8"/><rect x="42" y="115" width="30" height="3" rx="1.5" fill="#cfe3e1"/>',
		),
		// A map with pins; the promoted one bobs.
		'map'       => array(
			'glyph' => 'star',
			'svg'   => '<g clip-path="url(#mjb-sm-{u})"><rect x="16" y="34" width="110" height="86" fill="#eef7f6"/><path d="M16 98c14-6 26 4 40-2s22-14 36-8 22 6 34 2v30H16z" fill="#cdeeea"/><path d="M16 62c26 4 44-8 70-2s30 10 40 6" fill="none" stroke="#fff" stroke-width="5"/><path d="M58 34c-4 22 6 40 2 86" fill="none" stroke="#fff" stroke-width="4"/><rect x="86" y="72" width="24" height="14" rx="4" fill="#d5ede9"/></g>
<g transform="translate(38 92)"><path d="' . $pin . '" fill="#0F766E"/><circle cy="-13" r="3" fill="#fff"/></g>
<g transform="translate(98 68) scale(.85)"><path d="' . $pin . '" fill="#14b8a6"/><circle cy="-13" r="3" fill="#fff"/></g>
<ellipse cx="72" cy="84" rx="5" ry="1.6" fill="#0b5f58" fill-opacity=".18"/>
<g class="mjb-spot__bob"><g transform="translate(72 83) scale(1.1)"><path d="' . $pin . '" fill="#F97316"/><circle cy="-13" r="3" fill="#fff"/></g></g>',
		),
		// The admin dashboard: two stat tiles and a chart.
		'dashboard' => array(
			'glyph' => 'pie',
			'svg'   => '<rect x="22" y="38" width="38" height="22" rx="6" fill="#f5faf9" stroke="#e2efee"/><rect x="27" y="43" width="16" height="6" rx="3" fill="#0F766E"/><rect x="27" y="53" width="24" height="3" rx="1.5" fill="#a9d1cf"/>
<rect x="64" y="38" width="38" height="22" rx="6" fill="#f5faf9" stroke="#e2efee"/><rect x="69" y="43" width="12" height="6" rx="3" fill="#14b8a6"/><rect x="69" y="53" width="22" height="3" rx="1.5" fill="#a9d1cf"/>
<g><rect x="24" y="98" width="9" height="18" rx="3" fill="#E0F2F1"/><rect x="38" y="90" width="9" height="26" rx="3" fill="#E0F2F1"/><rect x="52" y="94" width="9" height="22" rx="3" fill="#E0F2F1"/><rect x="66" y="82" width="9" height="34" rx="3" fill="#E0F2F1"/><rect x="80" y="86" width="9" height="30" rx="3" fill="#a9d1cf"/><rect x="94" y="72" width="9" height="44" rx="3" fill="#0F766E"/><rect x="108" y="78" width="9" height="38" rx="3" fill="#14b8a6"/></g>
<path d="m28.5 88 14-8 14 4 14-12 14 4 14-14 14 4" fill="none" stroke="#F97316" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="98.5" cy="62" r="2.6" fill="#fff" stroke="#F97316" stroke-width="1.6"/>',
		),
		// A company page: cover, logo and open jobs.
		'company'   => array(
			'glyph' => 'case',
			'svg'   => '<path d="M10 42a14 14 0 0 1 14-14h94a14 14 0 0 1 14 14v4H10z" fill="url(#mjb-sb-{u})"/>
<rect x="20" y="36" width="22" height="22" rx="6" fill="#0F766E" stroke="#fff" stroke-width="2.5"/><rect x="25.5" y="43" width="11" height="3" rx="1.5" fill="#fff"/><rect x="25.5" y="49" width="7" height="3" rx="1.5" fill="#99f6e4"/>
<rect x="48" y="51" width="44" height="5" rx="2.5" fill="#1f2937"/><rect x="48" y="59" width="28" height="3.5" rx="1.75" fill="#a9d1cf"/>
<rect x="20" y="70" width="102" height="17" rx="5" fill="#f5faf9"/><rect x="25" y="74.5" width="8" height="8" rx="2.5" fill="#14b8a6"/><rect x="38" y="76.5" width="44" height="4" rx="2" fill="#1f2937"/><rect x="94" y="74" width="22" height="9" rx="4.5" fill="#fff" stroke="#cfe3e1"/>
<rect x="20" y="91" width="102" height="17" rx="5" fill="#f5faf9"/><rect x="25" y="95.5" width="8" height="8" rx="2.5" fill="#a9d1cf"/><rect x="38" y="97.5" width="36" height="4" rx="2" fill="#94a3b8"/><rect x="94" y="95" width="22" height="9" rx="4.5" fill="#fff" stroke="#cfe3e1"/>
<rect x="20" y="115" width="34" height="4" rx="2" fill="#14b8a6"/>',
		),
		// A chat with the AI assistant, still typing.
		'assistant' => array(
			'glyph' => 'sparkles',
			'svg'   => '<rect x="54" y="38" width="52" height="16" rx="8" fill="#0F766E"/><rect x="61" y="44" width="34" height="4" rx="2" fill="#fff"/>
<rect x="20" y="60" width="84" height="32" rx="9" fill="#f5faf9" stroke="#cfe3e1"/><path d="m28 66 1.2 3 3 1.2-3 1.2-1.2 3-1.2-3-3-1.2 3-1.2z" fill="#F97316"/><rect x="36" y="67" width="58" height="4" rx="2" fill="#1f2937"/><rect x="36" y="75" width="48" height="4" rx="2" fill="#94a3b8"/><rect x="36" y="83" width="34" height="4" rx="2" fill="#94a3b8"/>
<rect x="20" y="99" width="36" height="16" rx="8" fill="#E0F2F1"/><circle class="mjb-spot__dot" cx="30" cy="107" r="2.2" fill="#0F766E"/><circle class="mjb-spot__dot" style="animation-delay:.2s" cx="38" cy="107" r="2.2" fill="#0F766E"/><circle class="mjb-spot__dot" style="animation-delay:.4s" cx="46" cy="107" r="2.2" fill="#0F766E"/>',
		),
		// The board itself: search and job listings.
		'board'     => array(
			'glyph' => 'grid',
			'svg'   => '<rect x="20" y="38" width="76" height="13" rx="6.5" fill="#f5faf9" stroke="#cfe3e1"/><circle cx="28" cy="44.5" r="2.8" fill="none" stroke="#0F766E" stroke-width="1.3"/><rect x="35" y="43" width="34" height="3.5" rx="1.75" fill="#94a3b8"/>
<rect x="20" y="58" width="102" height="19" rx="5" fill="#fff" stroke="#cfe3e1"/><rect x="25" y="62.5" width="10" height="10" rx="3" fill="#0F766E"/><rect x="40" y="63.5" width="40" height="4" rx="2" fill="#1f2937"/><rect x="40" y="69.5" width="26" height="3" rx="1.5" fill="#a9d1cf"/><rect x="92" y="63.5" width="25" height="8" rx="4" fill="#ffedd5"/><rect x="96" y="66.4" width="17" height="2.4" rx="1.2" fill="#F97316"/>
<rect x="20" y="81" width="102" height="19" rx="5" fill="#f5faf9"/><rect x="25" y="85.5" width="10" height="10" rx="3" fill="#14b8a6"/><rect x="40" y="86.5" width="48" height="4" rx="2" fill="#94a3b8"/><rect x="40" y="92.5" width="22" height="3" rx="1.5" fill="#cfe3e1"/>
<rect x="20" y="104" width="102" height="19" rx="5" fill="#f5faf9"/><rect x="25" y="108.5" width="10" height="10" rx="3" fill="#a9d1cf"/><rect x="40" y="109.5" width="36" height="4" rx="2" fill="#94a3b8"/><rect x="40" y="115.5" width="28" height="3" rx="1.5" fill="#cfe3e1"/>',
		),
		// The people behind it.
		'team'      => array(
			'glyph' => 'badge',
			'svg'   => '<circle cx="36" cy="56" r="12" fill="#E0F2F1" stroke="#fff" stroke-width="2.5"/><circle cx="36" cy="52.5" r="4" fill="#0F766E"/><path d="M29 63a7 5.5 0 0 1 14 0z" fill="#0F766E"/>
<circle cx="56" cy="56" r="12" fill="#ccfbf1" stroke="#fff" stroke-width="2.5"/><circle cx="56" cy="52.5" r="4" fill="#14b8a6"/><path d="M49 63a7 5.5 0 0 1 14 0z" fill="#14b8a6"/>
<circle cx="76" cy="56" r="12" fill="#ffedd5" stroke="#fff" stroke-width="2.5"/><circle cx="76" cy="52.5" r="4" fill="#F97316"/><path d="M69 63a7 5.5 0 0 1 14 0z" fill="#F97316"/>
<rect x="22" y="78" width="70" height="5" rx="2.5" fill="#1f2937"/><rect x="22" y="88" width="98" height="4" rx="2" fill="#cbd5e1"/><rect x="22" y="96" width="84" height="4" rx="2" fill="#cbd5e1"/>
<rect x="22" y="107" width="34" height="10" rx="5" fill="#E0F2F1"/><rect x="60" y="107" width="28" height="10" rx="5" fill="#E0F2F1"/>',
		),
	);
}

/**
 * Plan box illustration: a small pricing card in the plan's colours.
 *
 * @param string $tier 'free', 'pro' or 'business'.
 * @return array
 */
function mjb_theme_spot_plan_art( $tier ) {
	$tiers = array(
		'free'     => array( '#E0F2F1', '#0F766E', '#0F766E', 'check', 'teal' ),
		'pro'      => array( '#ffedd5', '#ea580c', '#F97316', 'zap', 'orange' ),
		'business' => array( '#ccfbf1', '#134e4a', '#134e4a', 'building', 'deep' ),
	);
	list( $light, $dark, $button, $glyph, $chip ) = isset( $tiers[ $tier ] ) ? $tiers[ $tier ] : $tiers['free'];

	$check = '';
	foreach ( array( array( 76, 60 ), array( 88, 52 ), array( 100, 66 ) ) as $row ) {
		$check .= sprintf(
			'<circle cx="26" cy="%1$d" r="4" fill="%2$s"/><path d="m24 %1$d 1.5 1.5 3-3" fill="none" stroke="#fff" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/><rect x="34" y="%3$d" width="%4$d" height="4" rx="2" fill="#cbd5e1"/>',
			$row[0],
			$dark,
			$row[0] - 2,
			$row[1]
		);
	}

	return array(
		'glyph' => $glyph,
		'chip'  => $chip,
		'back'  => 'plain',
		'svg'   => sprintf( '<rect x="22" y="39" width="32" height="11" rx="5.5" fill="%1$s"/><rect x="27" y="43" width="22" height="3.2" rx="1.6" fill="%2$s"/>', $light, $dark )
			. '<rect x="22" y="56" width="40" height="9" rx="3" fill="#1f2937"/><rect x="66" y="59" width="18" height="4" rx="2" fill="#94a3b8"/>'
			. $check
			. sprintf( '<rect x="22" y="110" width="98" height="12" rx="6" fill="%s"/><rect x="55" y="114.5" width="32" height="3" rx="1.5" fill="#fff"/>', $button ),
	);
}

/**
 * The full SVG for an illustration.
 *
 * @param array $art One entry from mjb_theme_spot_arts() or mjb_theme_spot_plan_art().
 * @return string
 */
function mjb_theme_spot_svg( $art ) {
	static $n = 0;
	++$n;
	$u = 's' . $n;

	$glyphs = mjb_theme_spot_glyphs();
	$chips  = array(
		'teal'   => array( '#0F766E', '#14b8a6' ),
		'orange' => array( '#ea580c', '#fb923c' ),
		'deep'   => array( '#134e4a', '#0F766E' ),
	);
	$chip   = isset( $art['chip'], $chips[ $art['chip'] ] ) ? $chips[ $art['chip'] ] : $chips['teal'];
	$dark   = isset( $art['card'] ) && 'dark' === $art['card'];

	$back = isset( $art['back'] ) && 'plain' === $art['back']
		? '<rect x="34" y="10" width="112" height="104" rx="20" transform="rotate(8 90 62)" fill="#fff" fill-opacity=".7" stroke="#0F766E" stroke-opacity=".14"/>'
		: '<rect x="34" y="10" width="112" height="104" rx="20" transform="rotate(8 90 62)" fill="url(#mjb-sb-{u})" fill-opacity=".75"/>';

	$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 140" focusable="false">'
		. '<defs>'
		. '<linearGradient id="mjb-sb-{u}" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#ccfbf1"/><stop offset="1" stop-color="#99d5cf"/></linearGradient>'
		. '<linearGradient id="mjb-sc-{u}" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="' . $chip[0] . '"/><stop offset="1" stop-color="' . $chip[1] . '"/></linearGradient>'
		. '<filter id="mjb-sf-{u}" x="-25%" y="-20%" width="150%" height="160%"><feDropShadow dx="0" dy="6" stdDeviation="6" flood-color="#0b5f58" flood-opacity=".2"/></filter>'
		. '<clipPath id="mjb-sm-{u}"><rect x="16" y="34" width="110" height="86" rx="9"/></clipPath>'
		. '</defs>'
		. '<g class="mjb-spot__back">' . $back . '</g>'
		. '<g class="mjb-spot__card"><rect x="10" y="28" width="122" height="98" rx="14" fill="' . ( $dark ? '#134e4a' : '#fff' ) . '"' . ( $dark ? '' : ' stroke="#d5e8e6"' ) . ' filter="url(#mjb-sf-{u})"/>' . $art['svg'] . '</g>'
		. '<g class="mjb-spot__chip"><rect x="110" y="4" width="44" height="44" rx="14" fill="url(#mjb-sc-{u})" filter="url(#mjb-sf-{u})"/>'
		. '<g transform="translate(121 15) scale(.92)" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">' . $glyphs[ $art['glyph'] ] . '</g>'
		. ( ! empty( $art['dot'] ) ? '<circle cx="148" cy="9" r="4" fill="#F97316" stroke="#fff" stroke-width="1.5"/>' : '' )
		. '</g></svg>';

	return str_replace( '{u}', $u, $svg );
}

/**
 * Which illustration a page's answer box gets, by page slug.
 *
 * @param string $slug Page slug.
 * @return string Key in mjb_theme_spot_arts().
 */
function mjb_theme_spot_key( $slug ) {
	$map = array(
		'frontend-job-submission'          => 'form',
		'predictive-search-pretty-urls'    => 'search',
		'employer-candidate-accounts'      => 'accounts',
		'private-resumes-cvs'              => 'private',
		'woocommerce-monetization-pro'     => 'money',
		'gutenberg-blocks'                 => 'blocks',
		'seo-ready'                        => 'seo',
		'custom-fields-imports-pro'        => 'fields',
		'rest-api-feeds-webhooks-business' => 'api',
		'job-alerts-saved-jobs-talent'     => 'alerts',
		'jobs-map-promotions'              => 'map',
		'admin-dashboard-jobs-console'     => 'dashboard',
		'company-pages-careers-mode'       => 'company',
		'recruiter-mcp-business'           => 'assistant',
		'about-us'                         => 'team',
	);

	if ( isset( $map[ $slug ] ) ) {
		return $map[ $slug ];
	}

	// A renamed page: match on words in its slug, else the board itself.
	$words = array(
		'submission' => 'form',
		'search'     => 'search',
		'account'    => 'accounts',
		'resume'     => 'private',
		'cv'         => 'private',
		'monet'      => 'money',
		'woocommerce' => 'money',
		'block'      => 'blocks',
		'seo'        => 'seo',
		'field'      => 'fields',
		'api'        => 'api',
		'alert'      => 'alerts',
		'map'        => 'map',
		'dashboard'  => 'dashboard',
		'company'    => 'company',
		'mcp'        => 'assistant',
		'about'      => 'team',
	);
	foreach ( $words as $word => $key ) {
		if ( false !== strpos( $slug, $word ) ) {
			return $key;
		}
	}

	return 'board';
}

/**
 * The plan tier a feature page belongs to, from its slug.
 *
 * @param string $slug Page slug.
 * @return string
 */
function mjb_theme_spot_tier( $slug ) {
	if ( preg_match( '/-business$/', $slug ) ) {
		return 'business';
	}

	return preg_match( '/-pro$/', $slug ) ? 'pro' : 'free';
}

/**
 * Add the illustration to the first answer box and the first plan box on a page.
 *
 * @param string $html  Rendered block.
 * @param array  $block Block data.
 * @return string
 */
function mjb_theme_spot_render( $html, $block ) {
	static $done = array();

	if ( is_admin() || is_feed() || wp_is_json_request() || empty( $block['attrs']['className'] ) ) {
		return $html;
	}

	$classes = preg_split( '/\s+/', $block['attrs']['className'] );
	$type    = in_array( 'mjb-fb-answer', $classes, true ) ? 'answer' : ( in_array( 'mjb-fb-plan', $classes, true ) ? 'plan' : '' );
	$post    = get_post();

	if ( '' === $type || ! $post instanceof WP_Post || isset( $done[ $post->ID ][ $type ] ) ) {
		return $html;
	}

	if ( ! preg_match( '/^\s*(<div\b[^>]*\bclass="([^"]*)"[^>]*>)(.*)<\/div>\s*$/is', $html, $m ) ) {
		return $html;
	}

	$done[ $post->ID ][ $type ] = true;

	if ( 'plan' === $type ) {
		$art = mjb_theme_spot_plan_art( mjb_theme_spot_tier( $post->post_name ) );
	} else {
		$arts = mjb_theme_spot_arts();
		$art  = $arts[ mjb_theme_spot_key( $post->post_name ) ];
	}

	$open = str_replace( 'class="' . $m[2] . '"', 'class="' . $m[2] . ' has-mjb-spot"', $m[1] );

	return $open
		. '<div class="mjb-spot" aria-hidden="true">' . mjb_theme_spot_svg( $art ) . '</div>'
		. '<div class="mjb-spot-text">' . $m[3] . '</div></div>';
}
add_filter( 'render_block_core/group', 'mjb_theme_spot_render', 10, 2 );
