<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
$is_demo_nav   = mjb_theme_is_demo();
$navbar_class  = 'navbar scrolled';
$navbar_class .= $is_demo_nav ? ' navbar--demo' : '';
$demo_links    = $is_demo_nav ? mjb_theme_get_demo_nav_links() : array();
$demo_actions  = $is_demo_nav ? mjb_theme_get_demo_nav_actions() : array();
// Demo logo always returns to the marketing site root; other demo links stay under /jobs/.
$logo_url      = home_url( '/' );
?>

    <!-- Navbar -->
    <nav class="<?php echo esc_attr( $navbar_class ); ?>" id="navbar">
        <div class="container navbar-content<?php echo $is_demo_nav ? ' navbar-content--demo' : ''; ?>">
            <?php if ( has_custom_logo() ) : ?>
                <div class="logo logo--custom">
                    <div class="logo-icon">
                        <?php the_custom_logo(); ?>
                    </div>
                </div>
            <?php else : ?>
                <a href="<?php echo esc_url( $logo_url ); ?>" class="logo">
                    <div class="logo-icon">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/images/MJB logo.svg' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
                    </div>
                </a>
            <?php endif; ?>

            <?php if ( $is_demo_nav ) : ?>
                <?php if ( has_nav_menu( 'demo' ) ) : ?>
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'demo',
                            'container'      => 'div',
                            'container_class' => 'nav-links nav-links--center desktop-only',
                            'menu_class'     => '',
                            'fallback_cb'    => false,
                            'depth'          => 1,
                        )
                    );
                    ?>
                <?php else : ?>
                    <div class="nav-links nav-links--center desktop-only">
                        <?php foreach ( $demo_links as $key => $link ) : ?>
                            <a href="<?php echo esc_url( $link['url'] ); ?>"<?php echo mjb_theme_is_demo_nav_active( $key ) ? ' class="active" aria-current="page"' : ''; ?>>
                                <?php echo esc_html( $link['label'] ); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="nav-actions desktop-only">
                    <?php
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper.
                    mjb_theme_render_nav_auth_dropdown( 'candidate', $demo_actions['candidate'] );
                    mjb_theme_render_nav_auth_dropdown( 'employer', $demo_actions['employer'] );
                    ?>
                    <a href="<?php echo esc_url( $demo_actions['post_job']['url'] ); ?>" class="btn btn-primary btn-sm">
                        <?php echo esc_html( $demo_actions['post_job']['label'] ); ?>
                    </a>
                </div>
            <?php elseif ( has_nav_menu( 'primary' ) ) : ?>
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'container'      => 'div',
                        'container_class' => 'nav-links desktop-only',
                        'menu_class'     => '',
                        'fallback_cb'    => false,
                        'depth'          => 1,
                    )
                );
                ?>
            <?php else : ?>
                <?php
                // Section anchors must target the marketing home even when viewing /docs/.
                $home_features    = is_front_page() ? '#features' : home_url( '/#features' );
                $home_comparison  = is_front_page() ? '#comparison' : home_url( '/#comparison' );
                $home_developers  = is_front_page() ? '#developers' : home_url( '/#developers' );
                $home_pricing     = is_front_page() ? '#pricing' : home_url( '/#pricing' );
                ?>
                <div class="nav-links desktop-only">
                    <a href="<?php echo esc_url( $home_features ); ?>">Features</a>
                    <a href="<?php echo esc_url( home_url( '/docs/' ) ); ?>"<?php echo is_page( 'docs' ) ? ' class="active" aria-current="page"' : ''; ?>>Docs</a>
                    <a href="<?php echo esc_url( $home_comparison ); ?>">Compare</a>
                    <a href="<?php echo esc_url( $home_developers ); ?>">Developers</a>
                    <a href="<?php echo esc_url( $home_pricing ); ?>">Pricing</a>
                    <a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>" class="btn btn-outline btn-sm" target="_blank" rel="noopener noreferrer">Live Demo</a>
                    <a href="<?php echo esc_url( home_url( '/#pricing' ) ); ?>" class="btn btn-primary btn-sm">Get Started Free</a>
                </div>
            <?php endif; ?>

            <button class="mobile-toggle" id="mobile-toggle" aria-label="Toggle menu">
                <svg class="icon-menu" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" x2="20" y1="12" y2="12" />
                    <line x1="4" x2="20" y1="6" y2="6" />
                    <line x1="4" x2="20" y1="18" y2="18" />
                </svg>
                <svg class="icon-close hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>
        <div class="mobile-menu" id="mobile-menu">
            <?php if ( $is_demo_nav ) : ?>
                <?php foreach ( $demo_links as $key => $link ) : ?>
                    <a href="<?php echo esc_url( $link['url'] ); ?>" class="mobile-link<?php echo mjb_theme_is_demo_nav_active( $key ) ? ' active' : ''; ?>">
                        <?php echo esc_html( $link['label'] ); ?>
                    </a>
                <?php endforeach; ?>
                <?php if ( ! empty( $demo_actions['candidate']['items'] ) ) : ?>
                    <?php foreach ( $demo_actions['candidate']['items'] as $item ) : ?>
                        <a href="<?php echo esc_url( $item['url'] ); ?>" class="mobile-link">
                            <?php
                            echo esc_html(
                                sprintf(
                                    /* translators: %s: action (Login, Register, Dashboard) */
                                    __( 'Job seeker: %s', 'modern-job-board-theme' ),
                                    $item['label']
                                )
                            );
                            ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php if ( ! empty( $demo_actions['employer']['items'] ) ) : ?>
                    <?php foreach ( $demo_actions['employer']['items'] as $item ) : ?>
                        <a href="<?php echo esc_url( $item['url'] ); ?>" class="mobile-link">
                            <?php
                            echo esc_html(
                                sprintf(
                                    /* translators: %s: action (Login, Register, Dashboard) */
                                    __( 'Recruiter: %s', 'modern-job-board-theme' ),
                                    $item['label']
                                )
                            );
                            ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
                <a href="<?php echo esc_url( $demo_actions['post_job']['url'] ); ?>" class="btn btn-primary mobile-btn">
                    <?php echo esc_html( $demo_actions['post_job']['label'] ); ?>
                </a>
            <?php elseif ( has_nav_menu( 'primary' ) ) : ?>
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => '',
                        'fallback_cb'    => false,
                        'depth'          => 1,
                        'mjb_mobile'     => true,
                    )
                );
                ?>
            <?php else : ?>
                <?php
                $home_features   = is_front_page() ? '#features' : home_url( '/#features' );
                $home_comparison = is_front_page() ? '#comparison' : home_url( '/#comparison' );
                $home_developers = is_front_page() ? '#developers' : home_url( '/#developers' );
                $home_pricing    = is_front_page() ? '#pricing' : home_url( '/#pricing' );
                ?>
                <a href="<?php echo esc_url( $home_features ); ?>" class="mobile-link">Features</a>
                <a href="<?php echo esc_url( home_url( '/docs/' ) ); ?>" class="mobile-link<?php echo is_page( 'docs' ) ? ' active' : ''; ?>"<?php echo is_page( 'docs' ) ? ' aria-current="page"' : ''; ?>>Docs</a>
                <a href="<?php echo esc_url( $home_comparison ); ?>" class="mobile-link">Compare</a>
                <a href="<?php echo esc_url( $home_developers ); ?>" class="mobile-link">Developers</a>
                <a href="<?php echo esc_url( $home_pricing ); ?>" class="mobile-link">Pricing</a>
                <a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>" class="mobile-link" target="_blank" rel="noopener noreferrer">Live Demo</a>
                <a href="<?php echo esc_url( home_url( '/#pricing' ) ); ?>" class="btn btn-primary mobile-btn">Get Started Free</a>
            <?php endif; ?>
        </div>
    </nav>