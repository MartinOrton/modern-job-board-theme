    <!-- Footer -->
    <?php
    $mjb_footer  = function_exists( 'mjb_theme_footer_menu_tree' ) ? mjb_theme_footer_menu_tree() : null;
    $mjb_tagline = get_bloginfo( 'description' );
    ?>
    <?php if ( $mjb_footer && ! empty( $mjb_footer['columns'] ) ) : ?>
    <footer class="site-footer">
        <div class="container">
            <div class="site-footer__main">
                <div class="site-footer__brand">
                    <a class="site-footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/images/MJB logo.svg' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="592" height="96" loading="lazy" />
                    </a>
                    <?php if ( '' !== $mjb_tagline ) : ?>
                        <p class="site-footer__tagline"><?php echo esc_html( $mjb_tagline ); ?></p>
                    <?php endif; ?>
                </div>
                <nav class="site-footer__nav" aria-label="<?php esc_attr_e( 'Footer', 'modern-job-board-theme' ); ?>">
                    <?php foreach ( $mjb_footer['columns'] as $mjb_col ) : ?>
                        <div class="site-footer__col<?php echo count( $mjb_col['links'] ) > 8 ? ' site-footer__col--wide' : ''; ?>">
                            <?php echo mjb_theme_footer_heading( $mjb_col['item'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the helper. ?>
                            <ul class="site-footer__list">
                                <?php foreach ( $mjb_col['links'] as $mjb_link ) : ?>
                                    <li><?php echo mjb_theme_footer_link( $mjb_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the helper. ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </nav>
            </div>
            <div class="site-footer__bottom">
                <p class="site-footer__copy"><?php echo wp_kses( mjb_theme_footer_copyright(), array( 'a' => array( 'href' => true ) ) ); ?></p>
                <?php if ( ! empty( $mjb_footer['loose'] ) ) : ?>
                    <ul class="site-footer__extra">
                        <?php foreach ( $mjb_footer['loose'] as $mjb_link ) : ?>
                            <li><?php echo mjb_theme_footer_link( $mjb_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the helper. ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </footer>
    <?php else : ?>
    <footer>
        <div class="container footer-content">
            <div class="footer-brand">
                <div class="logo-icon sm">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/images/MJB logo.svg' ); ?>" alt="MJB Logo" />
                </div>
            </div>
            <div class="footer-links">
                <?php if ( has_nav_menu( 'footer' ) ) : ?>
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'footer',
                            'container'      => false,
                            'items_wrap'     => '%3$s',
                            'fallback_cb'    => false,
                            'depth'          => 1,
                            'walker'         => new MJB_Theme_Nav_Walker(),
                            'mjb_variant'    => 'footer',
                        )
                    );
                    ?>
                <?php else : ?>
                    <a href="<?php echo esc_url( home_url( '/#pricing' ) ); ?>">Pricing</a>
                    <a href="<?php echo esc_url( home_url( '/docs/' ) ); ?>">Documentation</a>
                    <a href="https://martinorton.com">Author</a>
                <?php endif; ?>
            </div>
            <div class="footer-copy">Copyright &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Modern Job Board. Developed by <a
                    href="https://martinorton.com">Martin Orton</a>.</div>
        </div>
    </footer>
    <?php endif; ?>

    <!-- Back to Top -->
    <button id="back-to-top" class="back-to-top" aria-label="Back to Top">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 19V5" />
            <path d="m5 12 7-7 7 7" />
        </svg>
    </button>

    <?php wp_footer(); ?>
</body>
</html>
