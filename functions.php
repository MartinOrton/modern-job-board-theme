<?php
/**
 * Modern Job Board Theme Functions and Definitions
 *
 * @package ModernJobBoardTheme
 */

define( 'MJB_THEME_VERSION', '0.9.0-beta.4' );

if ( ! function_exists( 'mjb_theme_setup' ) ) {
	/**
	 * Theme setup.
	 */
	function mjb_theme_setup() {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		add_theme_support(
			'custom-logo',
			array(
				'height'      => 40,
				'width'       => 160,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);

		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-styles' );

		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'modern-job-board-theme' ),
				'demo'    => esc_html__( 'Live Demo Menu', 'modern-job-board-theme' ),
			)
		);

		add_editor_style(
			array(
				'https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap',
				'css/styles.css',
				'css/editor.css',
				'style.css',
			)
		);
	}
}
add_action( 'after_setup_theme', 'mjb_theme_setup' );

/**
 * Enqueue frontend scripts and styles.
 */
function mjb_theme_scripts() {
	wp_enqueue_style(
		'mjb-google-fonts',
		'https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'mjb-main-style',
		get_template_directory_uri() . '/css/styles.css',
		array( 'mjb-google-fonts' ),
		MJB_THEME_VERSION
	);

	wp_enqueue_style(
		'mjb-theme-style',
		get_stylesheet_uri(),
		array( 'mjb-main-style' ),
		MJB_THEME_VERSION
	);

	wp_enqueue_script(
		'mjb-main-script',
		get_template_directory_uri() . '/js/script.js',
		array(),
		MJB_THEME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'mjb_theme_scripts' );

/**
 * Enqueue landing styles in the block editor (parent frame + block assets).
 */
function mjb_theme_editor_assets() {
	wp_enqueue_style(
		'mjb-google-fonts-editor',
		'https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'mjb-main-style-editor',
		get_template_directory_uri() . '/css/styles.css',
		array( 'mjb-google-fonts-editor' ),
		MJB_THEME_VERSION
	);

	wp_enqueue_style(
		'mjb-editor-style',
		get_template_directory_uri() . '/css/editor.css',
		array( 'mjb-main-style-editor' ),
		MJB_THEME_VERSION
	);
}
add_action( 'enqueue_block_editor_assets', 'mjb_theme_editor_assets' );

/**
 * Load landing CSS inside the editor iframe as well.
 */
function mjb_theme_block_assets() {
	if ( ! is_admin() ) {
		return;
	}

	wp_enqueue_style(
		'mjb-main-style-block-editor',
		get_template_directory_uri() . '/css/styles.css',
		array(),
		MJB_THEME_VERSION
	);

	wp_enqueue_style(
		'mjb-editor-style-block-editor',
		get_template_directory_uri() . '/css/editor.css',
		array( 'mjb-main-style-block-editor' ),
		MJB_THEME_VERSION
	);
}
add_action( 'enqueue_block_assets', 'mjb_theme_block_assets' );

/**
 * Inject editor canvas styles for the home/docs HTML landing blocks.
 *
 * @param array $editor_settings Editor settings.
 * @return array
 */
function mjb_landing_block_editor_styles( $editor_settings ) {
	$editor_css  = file_get_contents( get_template_directory() . '/css/styles.css' );
	$editor_css .= file_get_contents( get_template_directory() . '/css/editor.css' );

	if ( ! isset( $editor_settings['styles'] ) ) {
		$editor_settings['styles'] = array();
	}

	$editor_settings['styles'][] = array(
		'css' => $editor_css,
	);

	return $editor_settings;
}
add_filter( 'block_editor_settings_all', 'mjb_landing_block_editor_styles' );

/**
 * Whether the current request should use the Live Demo navbar.
 *
 * @return bool
 */
function mjb_theme_is_demo() {
	if ( is_admin() ) {
		return false;
	}

	if ( is_front_page() || is_page( 'docs' ) ) {
		return false;
	}

	if ( is_singular( array( 'job_listing', 'company' ) ) ) {
		return true;
	}

	if ( is_post_type_archive( array( 'job_listing', 'company' ) ) ) {
		return true;
	}

	if ( is_tax( array( 'job_type', 'job_category', 'job_location' ) ) ) {
		return true;
	}

	if ( is_singular( 'post' ) || ( is_home() && ! is_front_page() ) ) {
		return true;
	}

	if ( is_category() || is_tag() || is_author() || is_date() ) {
		return true;
	}

	if ( ! is_page() ) {
		return false;
	}

	$demo_shortcodes = array(
		'mjb_jobs',
		'mjb_job_form',
		'mjb_dashboard',
		'mjb_candidate_dashboard',
		'mjb_candidate_registration',
		'mjb_employer_registration',
	);

	global $post;
	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	foreach ( $demo_shortcodes as $shortcode ) {
		if ( has_shortcode( $post->post_content, $shortcode ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Add demo body class for navbar styling.
 *
 * @param array $classes Body classes.
 * @return array
 */
function mjb_theme_demo_body_class( $classes ) {
	if ( mjb_theme_is_demo() ) {
		$classes[] = 'mjb-demo';
	}

	return $classes;
}
add_filter( 'body_class', 'mjb_theme_demo_body_class' );

/**
 * Resolve a plugin shortcode page URL when Modern Job Board is active.
 *
 * @param string $shortcode    Shortcode name.
 * @param string $option_key   Cached page option key.
 * @param string $fallback_path Fallback path.
 * @return string
 */
function mjb_theme_get_plugin_page_url( $shortcode, $option_key, $fallback_path ) {
	if ( class_exists( 'MJB_Page_Resolver' ) ) {
		return MJB_Page_Resolver::get_page_url( $shortcode, $option_key, array(), $fallback_path );
	}

	return home_url( $fallback_path );
}

/**
 * Demo navbar link definitions.
 *
 * @return array<string, array<string, string>>
 */
function mjb_theme_get_demo_nav_links() {
	$blog_page_id = (int) get_option( 'page_for_posts' );
	$blog_url     = $blog_page_id ? get_permalink( $blog_page_id ) : home_url( '/blog/' );

	$company_url = get_post_type_archive_link( 'company' );
	if ( ! $company_url ) {
		$company_url = home_url( '/company/' );
	}

	return array(
		'jobs' => array(
			'label' => __( 'Search for Jobs', 'modern-job-board-theme' ),
			'url'   => mjb_theme_get_plugin_page_url( 'mjb_jobs', 'mjb_jobs_page_id', '/jobs/' ),
		),
		'companies' => array(
			'label' => __( 'Companies', 'modern-job-board-theme' ),
			'url'   => $company_url,
		),
		'blog' => array(
			'label' => __( 'Blog', 'modern-job-board-theme' ),
			'url'   => $blog_url,
		),
	);
}

/**
 * Demo navbar action button definitions.
 *
 * @return array<string, array<string, string>>
 */
function mjb_theme_get_demo_nav_actions() {
	return array(
		'candidate' => array(
			'label' => __( 'Candidate dashboard', 'modern-job-board-theme' ),
			'url'   => mjb_theme_get_plugin_page_url( 'mjb_candidate_dashboard', 'mjb_candidate_dashboard_page_id', '/candidate-dashboard/' ),
			'icon'  => 'user',
		),
		'employer' => array(
			'label' => __( 'Employer dashboard', 'modern-job-board-theme' ),
			'url'   => mjb_theme_get_plugin_page_url( 'mjb_dashboard', 'mjb_employer_dashboard_page_id', '/employer-dashboard/' ),
			'icon'  => 'briefcase',
		),
		'post_job' => array(
			'label' => __( 'Post a Job', 'modern-job-board-theme' ),
			'url'   => mjb_theme_get_plugin_page_url( 'mjb_job_form', 'mjb_job_form_page_id', '/post-a-job/' ),
		),
	);
}

/**
 * Whether a demo nav item should be marked active.
 *
 * @param string $item Nav item key.
 * @return bool
 */
function mjb_theme_is_demo_nav_active( $item ) {
	switch ( $item ) {
		case 'jobs':
			if ( is_singular( 'job_listing' )
				|| is_post_type_archive( 'job_listing' )
				|| is_tax( array( 'job_type', 'job_category', 'job_location' ) ) ) {
				return true;
			}
			if ( ! is_page() ) {
				return false;
			}
			$post = get_post();
			return ( $post instanceof WP_Post ) && has_shortcode( $post->post_content, 'mjb_jobs' );
		case 'companies':
			return is_singular( 'company' ) || is_post_type_archive( 'company' );
		case 'blog':
			return is_singular( 'post' )
				|| ( is_home() && ! is_front_page() )
				|| is_category()
				|| is_tag()
				|| is_author()
				|| is_date();
		default:
			return false;
	}
}

/**
 * Add mobile-link class to assigned menus rendered in the mobile drawer.
 *
 * @param array    $atts Existing link attributes.
 * @param WP_Post  $item Menu item.
 * @param stdClass $args Menu args.
 * @return array
 */
function mjb_theme_mobile_nav_link_attributes( $atts, $item, $args ) {
	unset( $item );

	if ( empty( $args->mjb_mobile ) ) {
		return $atts;
	}

	$class = isset( $atts['class'] ) ? trim( (string) $atts['class'] ) : '';
	$atts['class'] = $class === '' ? 'mobile-link' : $class . ' mobile-link';

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'mjb_theme_mobile_nav_link_attributes', 10, 3 );

/**
 * Render an inline Lucide-style icon for the demo navbar.
 *
 * @param string $name Icon name.
 * @param int    $size Pixel size.
 * @return string
 */
function mjb_theme_render_nav_icon( $name, $size = 18 ) {
	$icons = array(
		'user' => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
		'briefcase' => '<rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
	);

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	$size = max( 12, (int) $size );

	return sprintf(
		'<svg class="nav-icon nav-icon--%1$s" xmlns="http://www.w3.org/2000/svg" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
		esc_attr( $name ),
		$size,
		$icons[ $name ]
	);
}