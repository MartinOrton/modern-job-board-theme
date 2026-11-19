<?php
/**
 * Knowledge base search results (/?s=…&post_type=mjb_kb).
 *
 * @package ModernJobBoardTheme
 */

get_header();

global $wp_query;

$mjb_query = get_search_query();
$mjb_total = (int) $wp_query->found_posts;
?>
<section class="mjb-content-hero alignfull mjb-kb-hero">
	<div class="mjb-content-hero__inner container">
		<?php mjb_theme_breadcrumbs(); ?>
		<h1 class="mjb-content-hero__title">
			<?php
			if ( '' === $mjb_query ) {
				esc_html_e( 'Search the documentation', 'modern-job-board-theme' );
			} else {
				/* translators: %s: search terms */
				printf( esc_html__( 'Results for “%s”', 'modern-job-board-theme' ), esc_html( $mjb_query ) );
			}
			?>
		</h1>
		<div class="mjb-content-hero__blocks">
			<?php echo mjb_theme_kb_render_search( array() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the renderer. ?>
		</div>
	</div>
</section>

<main id="primary" class="site-main site-main--content container mjb-kb-results">
	<?php if ( '' !== $mjb_query ) : ?>
		<p class="mjb-kb-results__count">
			<?php
			/* translators: %d: number of articles */
			echo esc_html( sprintf( _n( '%d article found', '%d articles found', $mjb_total, 'modern-job-board-theme' ), $mjb_total ) );
			?>
		</p>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>
		<ol class="mjb-kb-list">
			<?php
			while ( have_posts() ) :
				the_post();
				$mjb_topic = mjb_theme_kb_topic_of( get_post() );
				?>
				<li class="mjb-kb-list__item">
					<a class="mjb-kb-list__link" href="<?php the_permalink(); ?>">
						<?php if ( $mjb_topic ) : ?>
							<span class="mjb-kb-card__topic"><?php echo esc_html( $mjb_topic->name ); ?></span>
						<?php endif; ?>
						<span class="mjb-kb-list__title"><?php echo esc_html( mjb_theme_plain_title( get_post() ) ); ?></span>
						<span class="mjb-kb-list__text"><?php echo esc_html( get_the_excerpt() ); ?></span>
					</a>
				</li>
			<?php endwhile; ?>
		</ol>
		<?php
		the_posts_pagination(
			array(
				'mid_size'  => 1,
				'prev_text' => __( 'Previous', 'modern-job-board-theme' ),
				'next_text' => __( 'Next', 'modern-job-board-theme' ),
			)
		);
		?>
	<?php elseif ( '' !== $mjb_query ) : ?>
		<div class="mjb-kb-noresults">
			<p><?php esc_html_e( 'No articles match your search. Try a shorter or different word, or browse the topics below.', 'modern-job-board-theme' ); ?></p>
		</div>
		<?php echo mjb_theme_kb_render_categories( array( 'perTopic' => 3 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the renderer. ?>
	<?php else : ?>
		<?php echo mjb_theme_kb_render_categories( array( 'perTopic' => 3 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the renderer. ?>
	<?php endif; ?>

	<?php mjb_theme_kb_help(); ?>
</main>
<?php
get_footer();
