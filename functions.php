<?php
/**
 * Modern Job Board Theme Functions and Definitions
 *
 * @package ModernJobBoardTheme
 */

define( 'MJB_THEME_VERSION', '0.9.0-beta.97' );

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
				'css/fonts.css',
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
	// DM Sans is served from the theme: no requests to Google Fonts.
	wp_enqueue_style(
		'mjb-theme-fonts',
		get_template_directory_uri() . '/css/fonts.css',
		array(),
		MJB_THEME_VERSION
	);

	wp_enqueue_style(
		'mjb-main-style',
		get_template_directory_uri() . '/css/styles.css',
		array( 'mjb-theme-fonts' ),
		MJB_THEME_VERSION
	);

	wp_enqueue_style(
		'mjb-theme-style',
		get_stylesheet_uri(),
		array( 'mjb-main-style' ),
		MJB_THEME_VERSION
	);

	// Icons for card lists in page content (feature pages).
	if ( is_singular() && ! is_front_page() ) {
		wp_enqueue_style(
			'mjb-list-icons',
			get_template_directory_uri() . '/css/list-icons.css',
			array( 'mjb-main-style' ),
			MJB_THEME_VERSION
		);
	}

	// Home page polish layer: front page only, additive, scoped to body.home.
	if ( is_front_page() ) {
		wp_enqueue_style(
			'mjb-home-style',
			get_template_directory_uri() . '/css/home.css',
			array( 'mjb-main-style' ),
			MJB_THEME_VERSION
		);
	}

	wp_enqueue_script(
		'mjb-main-script',
		get_template_directory_uri() . '/js/script.js',
		array(),
		MJB_THEME_VERSION,
		true
	);

	// Features mega menu: keyboard, touch and aria-expanded handling.
	wp_enqueue_script(
		'mjb-mega-menu',
		get_template_directory_uri() . '/js/mega-menu.js',
		array(),
		MJB_THEME_VERSION,
		true
	);

	// Home page pointer effects (hero card tilt, card spotlight). Front page only.
	if ( is_front_page() ) {
		wp_enqueue_script(
			'mjb-home-script',
			get_template_directory_uri() . '/js/home.js',
			array(),
			MJB_THEME_VERSION,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'mjb_theme_scripts' );

/**
 * Mark the page for the screenshot fade-in before it is first painted (see
 * .mjb-intro in css/styles.css and js/script.js), only when the visitor has
 * not asked for less motion. If js/script.js never runs, the mark is removed
 * once the page has loaded, so the screenshots can't stay hidden.
 */
function mjb_theme_intro_flag() {
	if ( is_admin() ) {
		return;
	}
	echo "<script>(function(d){if(!('IntersectionObserver' in window)||window.matchMedia('(prefers-reduced-motion: reduce)').matches){return;}d.classList.add('mjb-intro');window.addEventListener('load',function(){if(!d.classList.contains('mjb-intro-ready')){d.classList.remove('mjb-intro');}});})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'mjb_theme_intro_flag', 1 );

/**
 * Enqueue landing styles in the block editor (parent frame + block assets).
 */
function mjb_theme_editor_assets() {
	wp_enqueue_style(
		'mjb-theme-fonts-editor',
		get_template_directory_uri() . '/css/fonts.css',
		array(),
		MJB_THEME_VERSION
	);

	wp_enqueue_style(
		'mjb-main-style-editor',
		get_template_directory_uri() . '/css/styles.css',
		array( 'mjb-theme-fonts-editor' ),
		MJB_THEME_VERSION
	);

	// Card list icons, for the icon picker in the sidebar.
	wp_enqueue_style(
		'mjb-list-icons-editor',
		get_template_directory_uri() . '/css/list-icons.css',
		array(),
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
		'mjb-list-icons-block-editor',
		get_template_directory_uri() . '/css/list-icons.css',
		array( 'mjb-main-style-block-editor' ),
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

	// baseURL lets the editor resolve relative URLs in the CSS (such as
	// ../images/frames/desktop.svg) from the css/ folder, not from wp-admin/.
	$editor_settings['styles'][] = array(
		'css'     => $editor_css,
		'baseURL' => get_template_directory_uri() . '/css/',
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
		'mjb_candidate_login',
		'mjb_employer_login',
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
 * Demo navbar link definitions (all under /jobs/ except marketing root logo).
 *
 * @return array<string, array<string, string>>
 */
function mjb_theme_get_demo_nav_links() {
	$jobs_url = mjb_theme_get_plugin_page_url( 'mjb_jobs', 'mjb_jobs_page_id', '/jobs/' );

	$company_url = get_post_type_archive_link( 'company' );
	if ( ! $company_url ) {
		$company_url = home_url( '/jobs/companies/' );
	}

	$blog_id  = (int) get_option( 'mjb_blog_page_id' );
	$blog_url = ( $blog_id && get_post_status( $blog_id ) === 'publish' )
		? get_permalink( $blog_id )
		: home_url( '/jobs/blog/' );

	return array(
		'jobs' => array(
			'label' => __( 'Search for Jobs', 'modern-job-board-theme' ),
			'url'   => $jobs_url,
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
 * Demo navbar action button definitions (Niceboard-style: dropdown Login / Register).
 * URLs stay under /jobs/ so the demo feels like one board app.
 *
 * @return array<string, array<string, mixed>>
 */
function mjb_theme_get_demo_nav_actions() {
	$candidate_login = mjb_theme_get_plugin_page_url( 'mjb_candidate_login', 'mjb_candidate_login_page_id', '/jobs/candidate-login/' );
	$employer_login  = mjb_theme_get_plugin_page_url( 'mjb_employer_login', 'mjb_employer_login_page_id', '/jobs/recruiter-login/' );
	$candidate_reg   = mjb_theme_get_plugin_page_url( 'mjb_candidate_registration', 'mjb_candidate_registration_page_id', '/jobs/candidate-registration/' );
	$employer_reg    = mjb_theme_get_plugin_page_url( 'mjb_employer_registration', 'mjb_employer_registration_page_id', '/jobs/recruiter-registration/' );
	$candidate_dash  = mjb_theme_get_plugin_page_url( 'mjb_candidate_dashboard', 'mjb_candidate_dashboard_page_id', '/jobs/candidate-dashboard/' );
	$employer_dash   = mjb_theme_get_plugin_page_url( 'mjb_dashboard', 'mjb_employer_dashboard_page_id', '/jobs/recruiter-dashboard/' );

	$candidate = array(
		'label'     => __( 'Job seeker', 'modern-job-board-theme' ),
		'tooltip'   => __( 'Job Seeker', 'modern-job-board-theme' ),
		'icon'      => 'user',
		'logged_in' => false,
		'items'     => array(
			array(
				'label' => __( 'Login', 'modern-job-board-theme' ),
				'url'   => $candidate_login,
			),
			array(
				'label' => __( 'Register', 'modern-job-board-theme' ),
				'url'   => $candidate_reg,
			),
		),
	);

	$employer = array(
		'label'     => __( 'Recruiter', 'modern-job-board-theme' ),
		'tooltip'   => __( 'Recruiter', 'modern-job-board-theme' ),
		'icon'      => 'briefcase',
		'logged_in' => false,
		'items'     => array(
			array(
				'label' => __( 'Login', 'modern-job-board-theme' ),
				'url'   => $employer_login,
			),
			array(
				'label' => __( 'Register', 'modern-job-board-theme' ),
				'url'   => $employer_reg,
			),
		),
	);

	if ( is_user_logged_in() ) {
		$user  = wp_get_current_user();
		$roles = (array) $user->roles;
		$is_admin = user_can( $user, 'manage_options' );

		if ( $is_admin || in_array( 'candidate', $roles, true ) ) {
			$candidate['logged_in'] = true;
			$candidate['tooltip']   = __( 'Job Seeker', 'modern-job-board-theme' );
			$candidate['items']     = array(
				array(
					'label' => __( 'Dashboard', 'modern-job-board-theme' ),
					'url'   => $candidate_dash,
				),
			);
		}
		if ( $is_admin || in_array( 'employer', $roles, true ) ) {
			$employer['logged_in'] = true;
			$employer['tooltip']   = __( 'Recruiter', 'modern-job-board-theme' );
			$employer['items']     = array(
				array(
					'label' => __( 'Dashboard', 'modern-job-board-theme' ),
					'url'   => $employer_dash,
				),
			);
		}
	}

	return array(
		'candidate' => $candidate,
		'employer'  => $employer,
		'post_job'  => array(
			'label' => __( 'Post a Job', 'modern-job-board-theme' ),
			'url'   => mjb_theme_get_plugin_page_url( 'mjb_job_form', 'mjb_job_form_page_id', '/jobs/post-a-job/' ),
		),
	);
}

/**
 * Render a demo nav icon dropdown (Login / Register), styled like job social share menus.
 *
 * @param string               $key    Action key (candidate|employer).
 * @param array<string, mixed> $action Action definition from mjb_theme_get_demo_nav_actions().
 * @return void
 */
function mjb_theme_render_nav_auth_dropdown( $key, $action ) {
	$key     = sanitize_key( $key );
	$label   = isset( $action['label'] ) ? (string) $action['label'] : '';
	$tooltip = isset( $action['tooltip'] ) ? (string) $action['tooltip'] : $label;
	$icon    = isset( $action['icon'] ) ? (string) $action['icon'] : 'user';
	$items   = isset( $action['items'] ) && is_array( $action['items'] ) ? $action['items'] : array();
	$menu_id = 'mjb-nav-auth-menu-' . $key;
	$btn_label = $tooltip !== '' ? $tooltip : $label;

	if ( empty( $items ) ) {
		return;
	}
	?>
	<div class="mjb-nav-auth" data-mjb-nav-auth>
		<button type="button"
			class="btn btn-outline btn-sm btn-icon mjb-nav-auth__toggle"
			data-mjb-nav-auth-toggle
			aria-expanded="false"
			aria-haspopup="menu"
			aria-controls="<?php echo esc_attr( $menu_id ); ?>"
			aria-label="<?php echo esc_attr( $btn_label ); ?>">
			<span class="mjb-nav-auth__toggle-icon" aria-hidden="true">
				<?php echo mjb_theme_render_nav_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span>
			<span class="mjb-nav-auth__tooltip" role="tooltip" aria-hidden="true"><?php echo esc_html( $btn_label ); ?></span>
		</button>
		<div id="<?php echo esc_attr( $menu_id ); ?>"
			class="mjb-nav-auth__menu"
			data-mjb-nav-auth-menu
			hidden
			role="menu"
			aria-label="<?php echo esc_attr( $label ); ?>">
			<?php foreach ( $items as $item ) : ?>
				<?php
				$item_label = isset( $item['label'] ) ? (string) $item['label'] : '';
				$item_url   = isset( $item['url'] ) ? (string) $item['url'] : '#';
				if ( $item_label === '' || $item_url === '' || $item_url === '#' ) {
					continue;
				}
				?>
				<a href="<?php echo esc_url( $item_url ); ?>"
					class="mjb-nav-auth__item"
					role="menuitem">
					<?php echo esc_html( $item_label ); ?>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
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
		case 'candidate':
			if ( ! is_page() ) {
				return false;
			}
			$post = get_post();
			return ( $post instanceof WP_Post ) && (
				has_shortcode( $post->post_content, 'mjb_candidate_registration' )
				|| has_shortcode( $post->post_content, 'mjb_candidate_login' )
				|| has_shortcode( $post->post_content, 'mjb_candidate_dashboard' )
			);
		case 'employer':
			if ( ! is_page() ) {
				return false;
			}
			$post = get_post();
			return ( $post instanceof WP_Post ) && (
				has_shortcode( $post->post_content, 'mjb_employer_registration' )
				|| has_shortcode( $post->post_content, 'mjb_employer_login' )
				|| has_shortcode( $post->post_content, 'mjb_dashboard' )
			);
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

/**
 * Feature pages: "Learn More" links on the home feature cards and the /features/ pages they point to.
 */
require get_template_directory() . '/inc/feature-pages.php';

/**
 * Keep the version in the home page badge ("WordPress Plugin • v0.9.0-beta.N")
 * current: when the plugin is active, the number shown is the installed
 * plugin's version, whatever number is stored in the page. The badge is any
 * element with the class "badge", in a Custom HTML block (as on the home page)
 * or a Paragraph block. Remove the version from the badge in the block editor
 * and nothing is added back.
 *
 * @param string $html Rendered block.
 * @return string
 */
function mjb_theme_badge_plugin_version( $html ) {
	if ( ! defined( 'MJB_VERSION' ) || false === strpos( $html, 'badge' ) || false === strpos( $html, 'WordPress Plugin' ) ) {
		return $html;
	}

	$updated = preg_replace(
		'/(<(?:p|div|span)\b[^>]*\bclass="[^"]*\bbadge\b[^"]*"[^>]*>(?:(?!<\/(?:p|div)>).)*?WordPress Plugin\s*(?:\x{2022}|&bull;|&#8226;)\s*v)\d+\.\d+(?:\.\d+)?(?:-[a-z]+(?:\.\d+)?)?/su',
		'${1}' . esc_html( MJB_VERSION ),
		$html,
		1
	);

	return is_string( $updated ) ? $updated : $html;
}
add_filter( 'render_block_core/paragraph', 'mjb_theme_badge_plugin_version' );
add_filter( 'render_block_core/html', 'mjb_theme_badge_plugin_version' );

/**
 * Breadcrumbs and the Features mega menu.
 */
require get_template_directory() . '/inc/navigation.php';

/**
 * Block editor: Feature Grid block, patterns, page excerpts.
 */
require get_template_directory() . '/inc/blocks.php';

/**
 * Menus managed under Appearance > Menus (walker, footer location, first-run menus).
 */
require get_template_directory() . '/inc/menus.php';

/**
 * One-off conversion of generated content into editable blocks.
 */
require get_template_directory() . '/inc/conversion.php';

/**
 * Footer columns, contact form, legal pages and knowledge base.
 */
require get_template_directory() . '/inc/site.php';
