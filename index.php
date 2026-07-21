<?php
/**
 * The main template file
 *
 * @package ModernJobBoardTheme
 */

get_header();
?>

<?php
if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
        ?>
        <section class="mjb-content-hero alignfull">
            <div class="mjb-content-hero__inner container">
                <h1 class="mjb-content-hero__title"><?php the_title(); ?></h1>
                <?php if ( has_excerpt() ) : ?>
                    <p class="mjb-content-hero__intro"><?php echo esc_html( get_the_excerpt() ); ?></p>
                <?php endif; ?>
            </div>
        </section>

        <main id="primary" class="site-main site-main--content container">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
        </main>
        <?php
    endwhile;
else :
    ?>
    <section class="mjb-content-hero alignfull">
        <div class="mjb-content-hero__inner container">
            <h1 class="mjb-content-hero__title"><?php esc_html_e( 'Page not found', 'modern-job-board-theme' ); ?></h1>
        </div>
    </section>

    <main id="primary" class="site-main site-main--content container">
        <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'modern-job-board-theme' ); ?></p>
    </main>
    <?php
endif;
?>

<?php
get_footer();