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
$logo_url      = $is_demo_nav ? $demo_links['jobs']['url'] : home_url( '/' );
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
                    <a href="<?php echo esc_url( $demo_actions['candidate']['url'] ); ?>" class="btn btn-outline btn-sm btn-icon" aria-label="<?php echo esc_attr( $demo_actions['candidate']['label'] ); ?>">
                        <?php echo mjb_theme_render_nav_icon( 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </a>
                    <a href="<?php echo esc_url( $demo_actions['employer']['url'] ); ?>" class="btn btn-outline btn-sm btn-icon" aria-label="<?php echo esc_attr( $demo_actions['employer']['label'] ); ?>">
                        <?php echo mjb_theme_render_nav_icon( $demo_actions['employer']['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </a>
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
                <div class="nav-links desktop-only">
                    <a href="#features">Features</a>
                    <a href="<?php echo esc_url( home_url( '/docs/' ) ); ?>">Docs</a>
                    <a href="#comparison">Compare</a>
                    <a href="#developers">Developers</a>
                    <a href="#pricing">Pricing</a>
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
                <a href="<?php echo esc_url( $demo_actions['candidate']['url'] ); ?>" class="mobile-link">
                    <?php echo esc_html( $demo_actions['candidate']['label'] ); ?>
                </a>
                <a href="<?php echo esc_url( $demo_actions['employer']['url'] ); ?>" class="mobile-link">
                    <?php echo esc_html( $demo_actions['employer']['label'] ); ?>
                </a>
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
                <a href="#features" class="mobile-link">Features</a>
                <a href="<?php echo esc_url( home_url( '/docs/' ) ); ?>" class="mobile-link">Docs</a>
                <a href="#comparison" class="mobile-link">Compare</a>
                <a href="#developers" class="mobile-link">Developers</a>
                <a href="#pricing" class="mobile-link">Pricing</a>
                <a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>" class="mobile-link" target="_blank" rel="noopener noreferrer">Live Demo</a>
                <a href="<?php echo esc_url( home_url( '/#pricing' ) ); ?>" class="btn btn-primary mobile-btn">Get Started Free</a>
            <?php endif; ?>
        </div>
    </nav>