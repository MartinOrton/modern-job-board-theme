<?php
/**
 * Knowledge base topic: its articles in reading order, with the topic navigation.
 *
 * @package ModernJobBoardTheme
 */

get_header();

$mjb_topic    = get_queried_object();
$mjb_articles = $mjb_topic instanceof WP_Term ? mjb_theme_kb_articles( $mjb_topic ) : array();
?>
<section class="mjb-content-hero alignfull mjb-kb-hero">
	<div class="mjb-content-hero__inner container">
		<?php mjb_theme_breadcrumbs(); ?>
		<h1 class="mjb-content-hero__title mjb-kb-hero__title"><?php echo $mjb_topic instanceof WP_Term ? mjb_theme_kb_icon( $mjb_topic ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the helper. ?><?php single_term_title(); ?></h1>
		<?php if ( $mjb_topic instanceof WP_Term && '' !== $mjb_topic->description ) : ?>
			<p class="mjb-content-hero__intro"><?php echo esc_html( $mjb_topic->description ); ?></p>
		<?php endif; ?>
		<p class="mjb-kb-meta">
			<span>
				<?php
				/* translators: %d: number of articles */
				echo esc_html( sprintf( _n( '%d article', '%d articles', count( $mjb_articles ), 'modern-job-board-theme' ), count( $mjb_articles ) ) );
				?>
			</span>
		</p>
	</div>
</section>

<div class="container mjb-kb mjb-kb--topic">
	<aside class="mjb-kb__nav">
		<?php mjb_theme_kb_nav( 0, $mjb_topic instanceof WP_Term ? $mjb_topic->term_id : 0 ); ?>
	</aside>

	<main id="primary" class="site-main site-main--content mjb-kb__main">
		<?php if ( $mjb_articles ) : ?>
			<ol class="mjb-kb-list">
				<?php foreach ( $mjb_articles as $mjb_article ) : ?>
					<li class="mjb-kb-list__item">
						<a class="mjb-kb-list__link" href="<?php echo esc_url( get_permalink( $mjb_article ) ); ?>">
							<span class="mjb-kb-list__title"><?php echo esc_html( mjb_theme_plain_title( $mjb_article ) ); ?></span>
							<?php if ( has_excerpt( $mjb_article ) ) : ?>
								<span class="mjb-kb-list__text"><?php echo esc_html( get_the_excerpt( $mjb_article ) ); ?></span>
							<?php endif; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ol>
		<?php else : ?>
			<p><?php esc_html_e( 'There are no articles in this topic yet.', 'modern-job-board-theme' ); ?></p>
		<?php endif; ?>

		<?php mjb_theme_kb_help(); ?>
	</main>
</div>
<?php
get_footer();
