<?php
/**
 * Animated illustrations for the feature page hero (the Feature Illustration
 * block, mjb/feature-art).
 *
 * Each illustration is inline SVG drawn in the site palette (teals and the
 * accent orange). Motion comes from the .mjb-a-* classes in css/styles.css;
 * a per-element delay is set with style="--d:…".
 *
 * @package ModernJobBoardTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The illustration library.
 *
 * @return array[] key => label, match (keywords used to pick one for a page), svg (inner markup).
 */
function mjb_theme_feature_arts() {
	static $arts = null;

	if ( null !== $arts ) {
		return $arts;
	}

	// Shared pieces: a white panel with window dots, and a text line.
	$panel = '<rect x="48" y="40" width="304" height="220" rx="18" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/><circle cx="70" cy="60" r="4" fill="#F97316"/><circle cx="84" cy="60" r="4" fill="#a9d1cf"/><circle cx="98" cy="60" r="4" fill="#a9d1cf"/>';

	$arts = array(
		'form'      => array(
			'label' => __( 'Job form', 'modern-job-board-theme' ),
			'match' => array( 'submission', 'post-a-job', 'posting' ),
			'svg'   => $panel . '
<rect x="72" y="84" width="256" height="30" rx="8" fill="#f5faf9" stroke="#cfe3e1"/><rect class="mjb-a-grow" x="84" y="95" width="120" height="8" rx="4" fill="#0F766E"/>
<rect x="72" y="124" width="122" height="30" rx="8" fill="#f5faf9" stroke="#cfe3e1"/><rect class="mjb-a-grow" style="--d:.5s" x="84" y="135" width="70" height="8" rx="4" fill="#14b8a6"/>
<rect x="206" y="124" width="122" height="30" rx="8" fill="#f5faf9" stroke="#cfe3e1"/><rect class="mjb-a-grow" style="--d:.9s" x="218" y="135" width="82" height="8" rx="4" fill="#14b8a6"/>
<rect x="72" y="164" width="256" height="44" rx="8" fill="#f5faf9" stroke="#cfe3e1"/><rect class="mjb-a-grow" style="--d:1.3s" x="84" y="175" width="200" height="7" rx="3.5" fill="#a9d1cf"/><rect class="mjb-a-grow" style="--d:1.7s" x="84" y="189" width="150" height="7" rx="3.5" fill="#a9d1cf"/>
<g class="mjb-a-pop" style="--d:2.3s"><rect x="72" y="220" width="104" height="26" rx="13" fill="#F97316"/><rect x="92" y="230" width="64" height="6" rx="3" fill="#fff"/></g>
<g class="mjb-a-pop" style="--d:2.9s"><circle cx="322" cy="222" r="22" fill="#0F766E"/><path d="m312 222 7 7 13-14" fill="none" stroke="#fff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></g>',
		),
		'search'    => array(
			'label' => __( 'Search', 'modern-job-board-theme' ),
			'match' => array( 'search', 'pretty-url', 'filter' ),
			'svg'   => $panel . '
<rect x="72" y="82" width="256" height="36" rx="18" fill="#f5faf9" stroke="#0F766E" stroke-width="2"/><circle cx="92" cy="99" r="7" fill="none" stroke="#0F766E" stroke-width="2.5"/><path d="m97 104 6 6" stroke="#0F766E" stroke-width="2.5" stroke-linecap="round"/>
<rect class="mjb-a-grow" x="112" y="95" width="92" height="8" rx="4" fill="#1f2937"/><rect class="mjb-a-blink" x="210" y="91" width="2.5" height="16" fill="#F97316"/>
<g class="mjb-a-rowin" style="--d:.9s"><rect x="72" y="130" width="256" height="34" rx="10" fill="#E0F2F1"/><rect x="82" y="138" width="18" height="18" rx="5" fill="#0F766E"/><rect x="110" y="140" width="110" height="6" rx="3" fill="#0F766E"/><rect x="110" y="151" width="70" height="5" rx="2.5" fill="#a9d1cf"/><rect x="278" y="141" width="40" height="12" rx="6" fill="#F97316"/></g>
<g class="mjb-a-rowin" style="--d:1.3s"><rect x="72" y="172" width="256" height="34" rx="10" fill="#f5faf9"/><rect x="82" y="180" width="18" height="18" rx="5" fill="#14b8a6"/><rect x="110" y="182" width="130" height="6" rx="3" fill="#94a3b8"/><rect x="110" y="193" width="60" height="5" rx="2.5" fill="#cfe3e1"/></g>
<g class="mjb-a-rowin" style="--d:1.7s"><rect x="72" y="214" width="256" height="34" rx="10" fill="#f5faf9"/><rect x="82" y="222" width="18" height="18" rx="5" fill="#a9d1cf"/><rect x="110" y="224" width="96" height="6" rx="3" fill="#94a3b8"/><rect x="110" y="235" width="76" height="5" rx="2.5" fill="#cfe3e1"/></g>',
		),
		'accounts'  => array(
			'label' => __( 'Accounts and pipeline', 'modern-job-board-theme' ),
			'match' => array( 'account', 'signup', 'registration' ),
			'svg'   => '
<g class="mjb-a-float"><rect x="36" y="60" width="132" height="176" rx="16" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/><circle cx="102" cy="104" r="24" fill="#E0F2F1"/><circle cx="102" cy="97" r="9" fill="#0F766E"/><path d="M84 120a18 14 0 0 1 36 0z" fill="#0F766E"/><rect x="62" y="142" width="80" height="8" rx="4" fill="#1f2937"/><rect x="72" y="158" width="60" height="6" rx="3" fill="#a9d1cf"/><rect x="56" y="180" width="92" height="12" rx="6" fill="#E0F2F1"/><rect class="mjb-a-grow" x="56" y="180" width="70" height="12" rx="6" fill="#14b8a6"/><rect x="62" y="206" width="80" height="16" rx="8" fill="#F97316"/></g>
<rect x="184" y="48" width="184" height="204" rx="16" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/>
<rect x="198" y="64" width="46" height="8" rx="4" fill="#a9d1cf"/><rect x="253" y="64" width="46" height="8" rx="4" fill="#14b8a6"/><rect x="308" y="64" width="46" height="8" rx="4" fill="#0F766E"/>
<rect x="198" y="82" width="46" height="156" rx="8" fill="#f5faf9"/><rect x="253" y="82" width="46" height="156" rx="8" fill="#f5faf9"/><rect x="308" y="82" width="46" height="156" rx="8" fill="#E0F2F1"/>
<rect x="203" y="88" width="36" height="26" rx="6" fill="#fff" stroke="#cfe3e1"/><rect x="203" y="120" width="36" height="26" rx="6" fill="#fff" stroke="#cfe3e1"/><rect x="258" y="88" width="36" height="26" rx="6" fill="#fff" stroke="#cfe3e1"/><rect x="313" y="88" width="36" height="26" rx="6" fill="#fff" stroke="#cfe3e1"/>
<g class="mjb-a-pipeline"><rect x="203" y="152" width="36" height="26" rx="6" fill="#F97316"/><rect x="209" y="160" width="24" height="4" rx="2" fill="#fff"/><rect x="209" y="168" width="16" height="4" rx="2" fill="#fed7aa"/></g>
<g class="mjb-a-pop" style="--d:3.6s"><circle cx="331" cy="212" r="13" fill="#0F766E"/><path d="m325 212 4 4 8-9" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></g>',
		),
		'lock'      => array(
			'label' => __( 'Private documents', 'modern-job-board-theme' ),
			'match' => array( 'private', 'resume', 'cv', 'secure', 'privacy' ),
			'svg'   => '
<g class="mjb-a-float"><path d="M92 44h118l42 42v170a12 12 0 0 1-12 12H92a12 12 0 0 1-12-12V56a12 12 0 0 1 12-12z" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/><path d="M210 44v30a12 12 0 0 0 12 12h30" fill="#E0F2F1" stroke="#cfe3e1" stroke-width="1.5"/>
<circle cx="122" cy="104" r="16" fill="#E0F2F1"/><circle cx="122" cy="100" r="6" fill="#0F766E"/><path d="M110 115a12 9 0 0 1 24 0z" fill="#0F766E"/>
<rect x="148" y="94" width="70" height="8" rx="4" fill="#1f2937"/><rect x="148" y="109" width="48" height="6" rx="3" fill="#a9d1cf"/>
<rect class="mjb-a-grow" x="102" y="142" width="128" height="7" rx="3.5" fill="#a9d1cf"/><rect class="mjb-a-grow" style="--d:.4s" x="102" y="158" width="108" height="7" rx="3.5" fill="#a9d1cf"/><rect class="mjb-a-grow" style="--d:.8s" x="102" y="174" width="120" height="7" rx="3.5" fill="#a9d1cf"/><rect class="mjb-a-grow" style="--d:1.2s" x="102" y="190" width="84" height="7" rx="3.5" fill="#a9d1cf"/></g>
<circle class="mjb-a-ring" cx="286" cy="196" r="52" fill="none" stroke="#14b8a6" stroke-width="2"/>
<circle cx="286" cy="196" r="52" fill="#0F766E"/>
<path class="mjb-a-shackle" d="M270 190v-12a16 16 0 0 1 32 0v12" fill="none" stroke="#fff" stroke-width="6" stroke-linecap="round"/>
<rect x="262" y="188" width="48" height="38" rx="8" fill="#fff"/><circle cx="286" cy="204" r="5" fill="#F97316"/><path d="M286 207v8" stroke="#F97316" stroke-width="4" stroke-linecap="round"/>',
		),
		'pricing'   => array(
			'label' => __( 'Pricing and payments', 'modern-job-board-theme' ),
			'match' => array( 'woocommerce', 'monetiz', 'monetis', 'pricing', 'payment', 'checkout' ),
			'svg'   => '
<rect x="30" y="84" width="100" height="150" rx="14" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/><rect x="46" y="102" width="44" height="7" rx="3.5" fill="#a9d1cf"/><rect x="46" y="120" width="60" height="14" rx="5" fill="#1f2937"/><rect x="46" y="150" width="68" height="5" rx="2.5" fill="#cfe3e1"/><rect x="46" y="163" width="56" height="5" rx="2.5" fill="#cfe3e1"/><rect x="46" y="198" width="68" height="20" rx="10" fill="#E0F2F1"/>
<rect x="270" y="84" width="100" height="150" rx="14" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/><rect x="286" y="102" width="44" height="7" rx="3.5" fill="#a9d1cf"/><rect x="286" y="120" width="60" height="14" rx="5" fill="#1f2937"/><rect x="286" y="150" width="68" height="5" rx="2.5" fill="#cfe3e1"/><rect x="286" y="163" width="56" height="5" rx="2.5" fill="#cfe3e1"/><rect x="286" y="198" width="68" height="20" rx="10" fill="#E0F2F1"/>
<g class="mjb-a-float"><rect x="140" y="58" width="120" height="190" rx="16" fill="#0F766E"/><rect x="170" y="48" width="60" height="20" rx="10" fill="#F97316"/><rect x="158" y="86" width="52" height="7" rx="3.5" fill="#a9d1cf"/><rect x="158" y="104" width="78" height="18" rx="6" fill="#fff"/>
<rect class="mjb-a-grow" x="158" y="142" width="84" height="6" rx="3" fill="#5eead4" opacity=".8"/><rect class="mjb-a-grow" style="--d:.4s" x="158" y="157" width="70" height="6" rx="3" fill="#5eead4" opacity=".8"/><rect class="mjb-a-grow" style="--d:.8s" x="158" y="172" width="78" height="6" rx="3" fill="#5eead4" opacity=".8"/><rect x="158" y="206" width="84" height="24" rx="12" fill="#fff"/></g>
<g class="mjb-a-coin" style="--d:1.2s"><circle cx="72" cy="58" r="17" fill="#F97316"/><circle cx="72" cy="58" r="11" fill="none" stroke="#fed7aa" stroke-width="2.5"/></g>
<g class="mjb-a-coin" style="--d:1.9s"><circle cx="330" cy="54" r="13" fill="#fdba74"/><circle cx="330" cy="54" r="8" fill="none" stroke="#fff" stroke-width="2"/></g>
<g class="mjb-a-coin" style="--d:2.6s"><circle cx="300" cy="264" r="15" fill="#F97316"/><circle cx="300" cy="264" r="9.5" fill="none" stroke="#fed7aa" stroke-width="2.5"/></g>',
		),
		'blocks'    => array(
			'label' => __( 'Editor blocks', 'modern-job-board-theme' ),
			'match' => array( 'gutenberg', 'block', 'shortcode', 'editor' ),
			'svg'   => $panel . '
<rect x="72" y="82" width="256" height="30" rx="8" fill="#E0F2F1"/><rect x="84" y="93" width="110" height="8" rx="4" fill="#0F766E"/>
<rect x="72" y="122" width="122" height="56" rx="8" fill="#f5faf9" stroke="#cfe3e1"/><rect x="84" y="134" width="60" height="6" rx="3" fill="#94a3b8"/><rect x="84" y="147" width="90" height="5" rx="2.5" fill="#cfe3e1"/><rect x="84" y="158" width="72" height="5" rx="2.5" fill="#cfe3e1"/>
<rect x="206" y="122" width="122" height="56" rx="8" fill="none" stroke="#14b8a6" stroke-width="2" stroke-dasharray="6 5"/>
<g class="mjb-a-dropin"><rect x="206" y="122" width="122" height="56" rx="8" fill="#0F766E"/><rect x="218" y="134" width="60" height="6" rx="3" fill="#fff"/><rect x="218" y="147" width="90" height="5" rx="2.5" fill="#5eead4"/><rect x="218" y="158" width="72" height="5" rx="2.5" fill="#5eead4"/></g>
<rect x="72" y="188" width="256" height="50" rx="8" fill="#f5faf9" stroke="#cfe3e1"/><rect x="84" y="200" width="36" height="26" rx="6" fill="#a9d1cf"/><rect x="130" y="203" width="120" height="6" rx="3" fill="#94a3b8"/><rect x="130" y="216" width="80" height="5" rx="2.5" fill="#cfe3e1"/>
<g class="mjb-a-pulse"><circle cx="336" cy="250" r="20" fill="#F97316"/><path d="M336 241v18M327 250h18" stroke="#fff" stroke-width="4" stroke-linecap="round"/></g>',
		),
		'seo'       => array(
			'label' => __( 'Search visibility', 'modern-job-board-theme' ),
			'match' => array( 'seo', 'google', 'schema', 'structured' ),
			'svg'   => $panel . '
<rect x="112" y="52" width="216" height="16" rx="8" fill="#f5faf9" stroke="#cfe3e1"/>
<g class="mjb-a-rowin"><rect x="72" y="86" width="150" height="8" rx="4" fill="#0F766E"/><rect x="72" y="101" width="96" height="6" rx="3" fill="#14b8a6"/><rect x="72" y="114" width="190" height="5" rx="2.5" fill="#cfe3e1"/><g fill="#F97316"><circle cx="76" cy="130" r="4"/><circle cx="88" cy="130" r="4"/><circle cx="100" cy="130" r="4"/><circle cx="112" cy="130" r="4"/><circle cx="124" cy="130" r="4"/></g></g>
<rect x="72" y="150" width="256" height="92" rx="10" fill="#f5faf9"/>
<path d="M88 226h224M88 204h224M88 182h224" stroke="#e3eeed" stroke-width="1.5"/>
<path class="mjb-a-draw" pathLength="1" d="M90 224 132 210l34 6 40-28 36 8 66-44" fill="none" stroke="#0F766E" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
<g class="mjb-a-pop" style="--d:2s"><circle cx="308" cy="176" r="9" fill="#F97316"/><circle cx="308" cy="176" r="3.5" fill="#fff"/></g>
<g class="mjb-a-float"><circle cx="318" cy="98" r="24" fill="#fff" stroke="#0F766E" stroke-width="5"/><path d="m335 116 16 17" stroke="#0F766E" stroke-width="7" stroke-linecap="round"/><path d="m308 99 7 7 13-14" fill="none" stroke="#14b8a6" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></g>',
		),
		'import'    => array(
			'label' => __( 'Fields and imports', 'modern-job-board-theme' ),
			'match' => array( 'custom-field', 'import', 'export', 'csv', 'xml', 'field' ),
			'svg'   => '
<path d="M44 70h62l26 26v118a10 10 0 0 1-10 10H44a10 10 0 0 1-10-10V80a10 10 0 0 1 10-10z" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/><path d="M106 70v18a8 8 0 0 0 8 8h18" fill="#E0F2F1" stroke="#cfe3e1" stroke-width="1.5"/>
<rect x="48" y="112" width="70" height="7" rx="3.5" fill="#0F766E"/><rect x="48" y="130" width="70" height="6" rx="3" fill="#a9d1cf"/><rect x="48" y="146" width="54" height="6" rx="3" fill="#a9d1cf"/><rect x="48" y="162" width="64" height="6" rx="3" fill="#a9d1cf"/><rect x="48" y="186" width="44" height="18" rx="9" fill="#F97316"/>
<path class="mjb-a-flow" d="M142 150h52" stroke="#14b8a6" stroke-width="4" stroke-linecap="round" stroke-dasharray="2 10"/><path d="m190 141 10 9-10 9" fill="none" stroke="#14b8a6" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
<rect x="212" y="56" width="158" height="190" rx="16" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/><rect x="212" y="56" width="158" height="34" rx="16" fill="#0F766E"/><rect x="212" y="74" width="158" height="16" fill="#0F766E"/><rect x="226" y="69" width="40" height="7" rx="3.5" fill="#fff"/><rect x="280" y="69" width="30" height="7" rx="3.5" fill="#5eead4"/><rect x="324" y="69" width="30" height="7" rx="3.5" fill="#5eead4"/>
<path d="M212 126h158M212 162h158M212 198h158" stroke="#e3eeed" stroke-width="1.5"/>
<g class="mjb-a-rowin" style="--d:.6s"><rect x="226" y="104" width="44" height="7" rx="3.5" fill="#1f2937"/><rect x="280" y="104" width="30" height="7" rx="3.5" fill="#a9d1cf"/><rect x="324" y="104" width="24" height="7" rx="3.5" fill="#14b8a6"/></g>
<g class="mjb-a-rowin" style="--d:1.1s"><rect x="226" y="140" width="52" height="7" rx="3.5" fill="#1f2937"/><rect x="280" y="140" width="30" height="7" rx="3.5" fill="#a9d1cf"/><rect x="324" y="140" width="24" height="7" rx="3.5" fill="#F97316"/></g>
<g class="mjb-a-rowin" style="--d:1.6s"><rect x="226" y="176" width="38" height="7" rx="3.5" fill="#1f2937"/><rect x="280" y="176" width="30" height="7" rx="3.5" fill="#a9d1cf"/><rect x="324" y="176" width="24" height="7" rx="3.5" fill="#14b8a6"/></g>
<g class="mjb-a-rowin" style="--d:2.1s"><rect x="226" y="212" width="48" height="7" rx="3.5" fill="#1f2937"/><rect x="280" y="212" width="30" height="7" rx="3.5" fill="#a9d1cf"/><rect x="324" y="212" width="24" height="7" rx="3.5" fill="#14b8a6"/></g>',
		),
		'api'       => array(
			'label' => __( 'API and integrations', 'modern-job-board-theme' ),
			'match' => array( 'api', 'webhook', 'feed', 'integration', 'rest' ),
			'svg'   => '
<g fill="none" stroke="#14b8a6" stroke-width="3" stroke-linecap="round" stroke-dasharray="2 9"><path class="mjb-a-flow" d="M200 150 84 78"/><path class="mjb-a-flow" style="--d:.5s" d="M200 150 322 74"/><path class="mjb-a-flow" style="--d:1s" d="M200 150 78 230"/><path class="mjb-a-flow" style="--d:1.5s" d="M200 150 324 232"/></g>
<circle class="mjb-a-ring" cx="200" cy="150" r="54" fill="none" stroke="#14b8a6" stroke-width="2"/>
<circle cx="200" cy="150" r="54" fill="#0F766E"/><path d="m186 132-18 18 18 18M214 132l18 18-18 18" fill="none" stroke="#fff" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/><path d="m206 126-12 48" stroke="#5eead4" stroke-width="5" stroke-linecap="round"/>
<g class="mjb-a-float"><rect x="40" y="44" width="76" height="62" rx="14" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/><rect x="54" y="60" width="48" height="7" rx="3.5" fill="#0F766E"/><rect x="54" y="74" width="34" height="6" rx="3" fill="#a9d1cf"/><rect x="54" y="87" width="42" height="6" rx="3" fill="#a9d1cf"/></g>
<g class="mjb-a-float" style="--d:1s"><rect x="290" y="40" width="76" height="62" rx="14" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/><circle cx="328" cy="71" r="17" fill="#fff1e6"/><path d="M320 64v14M328 60v22M336 66v10" stroke="#F97316" stroke-width="4" stroke-linecap="round"/></g>
<g class="mjb-a-float" style="--d:2s"><rect x="34" y="200" width="76" height="62" rx="14" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/><path d="M54 242c8-22 28-22 36 0" fill="none" stroke="#14b8a6" stroke-width="4" stroke-linecap="round"/><circle cx="72" cy="221" r="6" fill="#F97316"/></g>
<g class="mjb-a-float" style="--d:3s"><rect x="292" y="202" width="76" height="62" rx="14" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/><rect x="306" y="218" width="48" height="10" rx="5" fill="#E0F2F1"/><rect x="306" y="236" width="48" height="10" rx="5" fill="#0F766E"/><circle cx="346" cy="241" r="3" fill="#5eead4"/></g>',
		),
		'alerts'    => array(
			'label' => __( 'Alerts and saved jobs', 'modern-job-board-theme' ),
			'match' => array( 'alert', 'saved', 'talent', 'notification', 'email' ),
			'svg'   => '
<g class="mjb-a-rowin" style="--d:1s"><rect x="150" y="70" width="216" height="52" rx="14" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/><rect x="164" y="84" width="24" height="24" rx="7" fill="#0F766E"/><rect x="198" y="86" width="96" height="7" rx="3.5" fill="#1f2937"/><rect x="198" y="100" width="62" height="6" rx="3" fill="#a9d1cf"/><path d="M336 84h14v22l-7-5-7 5z" fill="#F97316"/></g>
<g class="mjb-a-rowin" style="--d:1.5s"><rect x="150" y="132" width="216" height="52" rx="14" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/><rect x="164" y="146" width="24" height="24" rx="7" fill="#14b8a6"/><rect x="198" y="148" width="110" height="7" rx="3.5" fill="#1f2937"/><rect x="198" y="162" width="70" height="6" rx="3" fill="#a9d1cf"/><path d="M336 146h14v22l-7-5-7 5z" fill="none" stroke="#a9d1cf" stroke-width="2"/></g>
<g class="mjb-a-rowin" style="--d:2s"><rect x="150" y="194" width="216" height="52" rx="14" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/><rect x="164" y="208" width="24" height="24" rx="7" fill="#a9d1cf"/><rect x="198" y="210" width="84" height="7" rx="3.5" fill="#1f2937"/><rect x="198" y="224" width="56" height="6" rx="3" fill="#a9d1cf"/><path d="M336 208h14v22l-7-5-7 5z" fill="#F97316"/></g>
<circle class="mjb-a-ring" cx="92" cy="150" r="56" fill="none" stroke="#14b8a6" stroke-width="2"/>
<circle cx="92" cy="150" r="56" fill="#0F766E"/>
<g class="mjb-a-swing"><path d="M92 118a22 22 0 0 0-22 22v16l-8 12h60l-8-12v-16a22 22 0 0 0-22-22z" fill="#fff"/><path d="M84 174a8 8 0 0 0 16 0z" fill="#5eead4"/></g>
<g class="mjb-a-pop" style="--d:.6s"><circle cx="120" cy="116" r="13" fill="#F97316" stroke="#0F766E" stroke-width="3"/><rect x="116" y="113" width="8" height="6" rx="3" fill="#fff"/></g>',
		),
		'map'       => array(
			'label' => __( 'Jobs map', 'modern-job-board-theme' ),
			'match' => array( 'map', 'location', 'promotion', 'banner' ),
			'svg'   => '
<rect x="40" y="40" width="320" height="220" rx="20" fill="#E0F2F1" stroke="#cfe3e1" stroke-width="1.5"/>
<path d="M40 120c60-20 110 30 170 6s100-30 150-8M130 40c-12 70 30 120 10 220M262 40c18 60-26 130 4 220" fill="none" stroke="#fff" stroke-width="12" stroke-linecap="round"/>
<path d="M40 196c70 14 120-24 190-6s90 20 130 6" fill="none" stroke="#fff" stroke-width="8" stroke-linecap="round"/>
<rect x="58" y="58" width="52" height="40" rx="8" fill="#cdebe7"/><rect x="290" y="204" width="54" height="40" rx="8" fill="#cdebe7"/><rect x="170" y="60" width="62" height="34" rx="8" fill="#cdebe7"/>
<ellipse class="mjb-a-ring" cx="118" cy="170" rx="16" ry="6" fill="none" stroke="#0F766E" stroke-width="2"/><g class="mjb-a-pin"><path d="M118 122a22 22 0 0 0-22 22c0 16 22 34 22 34s22-18 22-34a22 22 0 0 0-22-22z" fill="#0F766E"/><circle cx="118" cy="144" r="8" fill="#fff"/></g>
<ellipse class="mjb-a-ring" style="--d:.7s" cx="232" cy="134" rx="18" ry="7" fill="none" stroke="#F97316" stroke-width="2"/><g class="mjb-a-pin" style="--d:.7s"><path d="M232 76a26 26 0 0 0-26 26c0 19 26 40 26 40s26-21 26-40a26 26 0 0 0-26-26z" fill="#F97316"/><circle cx="232" cy="102" r="9.5" fill="#fff"/></g>
<ellipse class="mjb-a-ring" style="--d:1.4s" cx="302" cy="182" rx="14" ry="5" fill="none" stroke="#14b8a6" stroke-width="2"/><g class="mjb-a-pin" style="--d:1.4s"><path d="M302 140a19 19 0 0 0-19 19c0 14 19 29 19 29s19-15 19-29a19 19 0 0 0-19-19z" fill="#14b8a6"/><circle cx="302" cy="159" r="7" fill="#fff"/></g>
<g class="mjb-a-rowin" style="--d:2.2s"><rect x="56" y="204" width="150" height="42" rx="12" fill="#fff" stroke="#cfe3e1"/><rect x="68" y="214" width="22" height="22" rx="6" fill="#0F766E"/><rect x="100" y="216" width="80" height="6" rx="3" fill="#1f2937"/><rect x="100" y="228" width="54" height="5" rx="2.5" fill="#a9d1cf"/></g>',
		),
		'dashboard' => array(
			'label' => __( 'Dashboard and reports', 'modern-job-board-theme' ),
			'match' => array( 'admin', 'console', 'analytics', 'report', 'dashboard' ),
			'svg'   => $panel . '
<rect x="72" y="82" width="78" height="46" rx="10" fill="#E0F2F1"/><rect x="82" y="92" width="34" height="6" rx="3" fill="#0F766E"/><rect x="82" y="106" width="50" height="12" rx="4" fill="#0F766E"/>
<rect x="161" y="82" width="78" height="46" rx="10" fill="#f5faf9"/><rect x="171" y="92" width="34" height="6" rx="3" fill="#94a3b8"/><rect x="171" y="106" width="42" height="12" rx="4" fill="#1f2937"/>
<rect x="250" y="82" width="78" height="46" rx="10" fill="#fff1e6"/><rect x="260" y="92" width="34" height="6" rx="3" fill="#F97316"/><rect x="260" y="106" width="46" height="12" rx="4" fill="#F97316"/>
<path d="M72 240h256" stroke="#cfe3e1" stroke-width="1.5"/>
<rect class="mjb-a-rise" x="84" y="198" width="22" height="42" rx="5" fill="#a9d1cf"/><rect class="mjb-a-rise" style="--d:.2s" x="118" y="180" width="22" height="60" rx="5" fill="#14b8a6"/><rect class="mjb-a-rise" style="--d:.4s" x="152" y="206" width="22" height="34" rx="5" fill="#a9d1cf"/><rect class="mjb-a-rise" style="--d:.6s" x="186" y="166" width="22" height="74" rx="5" fill="#0F766E"/><rect class="mjb-a-rise" style="--d:.8s" x="220" y="188" width="22" height="52" rx="5" fill="#14b8a6"/><rect class="mjb-a-rise" style="--d:1s" x="254" y="152" width="22" height="88" rx="5" fill="#0F766E"/><rect class="mjb-a-rise" style="--d:1.2s" x="288" y="174" width="22" height="66" rx="5" fill="#F97316"/>
<path class="mjb-a-draw" style="--d:1.4s" pathLength="1" d="M95 186 129 168l34 22 34-40 34 20 34-34 34 20" fill="none" stroke="#F97316" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>',
		),
		'company'   => array(
			'label' => __( 'Company pages', 'modern-job-board-theme' ),
			'match' => array( 'company', 'careers', 'employer-page', 'directory' ),
			'svg'   => '
<path d="M40 252h320" stroke="#cfe3e1" stroke-width="2" stroke-linecap="round"/>
<rect x="56" y="70" width="116" height="182" rx="10" fill="#0F766E"/><rect x="172" y="128" width="62" height="124" rx="8" fill="#14b8a6"/>
<g fill="#fff"><rect x="72" y="88" width="20" height="20" rx="4"/><rect class="mjb-a-blink" x="104" y="88" width="20" height="20" rx="4"/><rect x="136" y="88" width="20" height="20" rx="4"/><rect class="mjb-a-blink" style="--d:1.2s" x="72" y="122" width="20" height="20" rx="4"/><rect x="104" y="122" width="20" height="20" rx="4"/><rect class="mjb-a-blink" style="--d:2.1s" x="136" y="122" width="20" height="20" rx="4"/><rect x="72" y="156" width="20" height="20" rx="4"/><rect class="mjb-a-blink" style="--d:.6s" x="104" y="156" width="20" height="20" rx="4"/><rect x="136" y="156" width="20" height="20" rx="4"/><rect x="186" y="146" width="14" height="14" rx="3"/><rect class="mjb-a-blink" style="--d:1.6s" x="206" y="146" width="14" height="14" rx="3"/><rect x="186" y="170" width="14" height="14" rx="3"/><rect x="206" y="170" width="14" height="14" rx="3"/></g>
<rect x="98" y="204" width="32" height="48" rx="6" fill="#F97316"/>
<g class="mjb-a-pop"><path d="M114 70V38" stroke="#0F766E" stroke-width="4" stroke-linecap="round"/><path d="M114 40h30l-8 9 8 9h-30z" fill="#F97316"/></g>
<g class="mjb-a-rowin" style="--d:.8s"><rect x="252" y="72" width="116" height="46" rx="12" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/><rect x="264" y="84" width="22" height="22" rx="6" fill="#E0F2F1"/><rect x="294" y="86" width="60" height="6" rx="3" fill="#1f2937"/><rect x="294" y="98" width="40" height="5" rx="2.5" fill="#a9d1cf"/></g>
<g class="mjb-a-rowin" style="--d:1.3s"><rect x="252" y="128" width="116" height="46" rx="12" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/><rect x="264" y="140" width="22" height="22" rx="6" fill="#E0F2F1"/><rect x="294" y="142" width="50" height="6" rx="3" fill="#1f2937"/><rect x="294" y="154" width="44" height="5" rx="2.5" fill="#a9d1cf"/></g>
<g class="mjb-a-rowin" style="--d:1.8s"><rect x="252" y="184" width="116" height="46" rx="12" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/><rect x="264" y="196" width="22" height="22" rx="6" fill="#E0F2F1"/><rect x="294" y="198" width="56" height="6" rx="3" fill="#1f2937"/><rect x="294" y="210" width="36" height="5" rx="2.5" fill="#a9d1cf"/></g>',
		),
		'assistant' => array(
			'label' => __( 'AI assistant', 'modern-job-board-theme' ),
			'match' => array( 'mcp', 'assistant', 'ai-', 'automation' ),
			'svg'   => $panel . '
<g class="mjb-a-rowin"><path d="M150 84h166a12 12 0 0 1 12 12v24a12 12 0 0 1-12 12H150a12 12 0 0 1-12-12V96a12 12 0 0 1 12-12z" fill="#0F766E"/><rect x="152" y="98" width="130" height="7" rx="3.5" fill="#fff"/><rect x="152" y="112" width="92" height="6" rx="3" fill="#5eead4"/></g>
<g class="mjb-a-rowin" style="--d:1s"><circle cx="88" cy="164" r="16" fill="#fff1e6"/><path class="mjb-a-pulse" d="M88 152l3.5 8.5L100 164l-8.500 3.5L88 176l-3.500-8.500L76 164l8.500-3.500z" fill="#F97316"/><rect x="112" y="144" width="150" height="40" rx="12" fill="#f5faf9" stroke="#cfe3e1"/><circle class="mjb-a-blink" cx="134" cy="164" r="5" fill="#14b8a6"/><circle class="mjb-a-blink" style="--d:.25s" cx="152" cy="164" r="5" fill="#14b8a6"/><circle class="mjb-a-blink" style="--d:.5s" cx="170" cy="164" r="5" fill="#14b8a6"/></g>
<g class="mjb-a-rowin" style="--d:2.4s"><rect x="72" y="198" width="256" height="46" rx="12" fill="#E0F2F1"/><rect x="84" y="210" width="22" height="22" rx="6" fill="#0F766E"/><rect x="116" y="212" width="110" height="6" rx="3" fill="#0F766E"/><rect x="116" y="224" width="70" height="5" rx="2.5" fill="#a9d1cf"/></g>
<g class="mjb-a-pop" style="--d:3.2s"><circle cx="304" cy="221" r="13" fill="#0F766E"/><path d="m298 221 4 4 8-9" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></g>',
		),
		'board'     => array(
			'label' => __( 'Job board', 'modern-job-board-theme' ),
			'match' => array(),
			'svg'   => '
<g class="mjb-a-float" style="--d:1.5s"><rect x="76" y="48" width="268" height="64" rx="16" fill="#fff" stroke="#cfe3e1" stroke-width="1.5" opacity=".7"/></g>
<g class="mjb-a-float"><rect x="48" y="96" width="304" height="76" rx="18" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/><rect x="66" y="114" width="40" height="40" rx="10" fill="#0F766E"/><rect x="120" y="118" width="130" height="8" rx="4" fill="#1f2937"/><rect x="120" y="136" width="84" height="6" rx="3" fill="#a9d1cf"/><rect x="284" y="122" width="52" height="24" rx="12" fill="#F97316"/></g>
<g class="mjb-a-float" style="--d:.8s"><rect x="48" y="186" width="304" height="76" rx="18" fill="#fff" stroke="#cfe3e1" stroke-width="1.5"/><rect x="66" y="204" width="40" height="40" rx="10" fill="#14b8a6"/><rect x="120" y="208" width="110" height="8" rx="4" fill="#1f2937"/><rect x="120" y="226" width="96" height="6" rx="3" fill="#a9d1cf"/><rect x="284" y="212" width="52" height="24" rx="12" fill="#E0F2F1"/></g>',
		),
	);

	return $arts;
}

/**
 * Pick the illustration that best fits a feature, from its slug and title.
 *
 * @param string $text Slug and/or title.
 * @return string Illustration key.
 */
function mjb_theme_match_feature_art( $text ) {
	$text = strtolower( (string) $text );

	foreach ( mjb_theme_feature_arts() as $key => $art ) {
		foreach ( $art['match'] as $keyword ) {
			if ( false !== strpos( $text, $keyword ) ) {
				return $key;
			}
		}
	}

	return 'board';
}

/**
 * Illustration markup.
 *
 * @param string $key Illustration key; unknown keys fall back to "board".
 * @return string
 */
function mjb_theme_feature_art( $key ) {
	$arts = mjb_theme_feature_arts();
	$key  = isset( $arts[ $key ] ) ? $key : 'board';

	return sprintf(
		'<figure class="mjb-art mjb-art--%1$s" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="20 20 360 260" role="presentation" focusable="false"><circle cx="286" cy="136" r="114" fill="#d9efec" opacity=".75"/><circle cx="96" cy="208" r="70" fill="#fff1e6" opacity=".9"/>%2$s</svg></figure>',
		esc_attr( $key ),
		$arts[ $key ]['svg']
	);
}
