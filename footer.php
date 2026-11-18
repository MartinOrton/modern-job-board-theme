    <!-- Footer -->
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
