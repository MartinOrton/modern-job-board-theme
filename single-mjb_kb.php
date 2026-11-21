<?php
/**
 * Knowledge base article: topic navigation, the article with "On this page",
 * and previous / next in reading order.
 *
 * @package ModernJobBoardTheme
 */

get_header();

while ( have_posts() ) :
	the_post();

	$mjb_post  = get_post();
	$mjb_topic = mjb_theme_kb_topic_of( $mjb_post );

	// Render the article first so its headings can be given ids and listed.
	ob_start();
	the_content();
	$mjb_article = mjb_theme_kb_headings( (string) ob_get_clean() );

	$mjb_order = mjb_theme_kb_reading_order();
	$mjb_ids   = wp_list_pluck( $mjb_order, 'ID' );
	$mjb_index = array_search( $mjb_post->ID, $mjb_ids, true );
	$mjb_prev  = false !== $mjb_index && $mjb_index > 0 ? $mjb_order[ $mjb_index - 1 ] : null;
	$mjb_next  = false !== $mjb_index && $mjb_index < count( $mjb_order ) - 1 ? $mjb_order[ $mjb_index + 1 ] : null;
	?>
	<section class="mjb-content-hero alignfull mjb-kb-hero">
		<div class="mjb-content-hero__inner container">
			<?php mjb_theme_breadcrumbs(); ?>
			<h1 class="mjb-content-hero__title"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="mjb-content-hero__intro"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
			<p class="mjb-kb-meta">
				<?php if ( $mjb_topic ) : ?>
					<a class="mjb-kb-meta__topic" href="<?php echo esc_url( get_term_link( $mjb_topic ) ); ?>"><?php echo mjb_theme_kb_icon( $mjb_topic ) . esc_html( $mjb_topic->name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the helper. ?></a>
				<?php endif; ?>
				<span>
					<?php
					/* translators: %s: date */
					printf( esc_html__( 'Updated %s', 'modern-job-board-theme' ), '<time datetime="' . esc_attr( get_the_modified_date( 'c' ) ) . '">' . esc_html( get_the_modified_date() ) . '</time>' );
					?>
				</span>
				<span>
					<?php
					$mjb_minutes = mjb_theme_kb_minutes( $mjb_post );
					/* translators: %d: minutes */
					echo esc_html( sprintf( _n( '%d minute read', '%d minute read', $mjb_minutes, 'modern-job-board-theme' ), $mjb_minutes ) );
					?>
				</span>
			</p>
		</div>
	</section>

	<div class="container mjb-kb<?php echo count( $mjb_article['toc'] ) >= 2 ? ' has-toc' : ''; ?>">
		<aside class="mjb-kb__nav">
			<?php mjb_theme_kb_nav( $mjb_post->ID, $mjb_topic ? $mjb_topic->term_id : 0 ); ?>
		</aside>

		<main id="primary" class="site-main site-main--content mjb-kb__main">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'mjb-kb-article' ); ?>>
				<div class="entry-content">
					<?php echo $mjb_article['html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Rendered post content. ?>
				</div>
			</article>

			<?php if ( $mjb_prev || $mjb_next ) : ?>
				<nav class="mjb-kb-pager" aria-label="<?php esc_attr_e( 'More articles', 'modern-job-board-theme' ); ?>">
					<?php if ( $mjb_prev ) : ?>
						<a class="mjb-kb-pager__link mjb-kb-pager__link--prev" href="<?php echo esc_url( get_permalink( $mjb_prev ) ); ?>" rel="prev">
							<span class="mjb-kb-pager__label"><?php esc_html_e( 'Previous', 'modern-job-board-theme' ); ?></span>
							<span class="mjb-kb-pager__title"><?php echo esc_html( mjb_theme_plain_title( $mjb_prev ) ); ?></span>
						</a>
					<?php endif; ?>
					<?php if ( $mjb_next ) : ?>
						<a class="mjb-kb-pager__link mjb-kb-pager__link--next" href="<?php echo esc_url( get_permalink( $mjb_next ) ); ?>" rel="next">
							<span class="mjb-kb-pager__label"><?php esc_html_e( 'Next', 'modern-job-board-theme' ); ?></span>
							<span class="mjb-kb-pager__title"><?php echo esc_html( mjb_theme_plain_title( $mjb_next ) ); ?></span>
						</a>
					<?php endif; ?>
				</nav>
			<?php endif; ?>

			<?php mjb_theme_kb_help(); ?>
		</main>

		<?php if ( count( $mjb_article['toc'] ) >= 2 ) : ?>
			<aside class="mjb-kb__toc">
				<nav class="mjb-kb-toc" aria-label="<?php esc_attr_e( 'On this page', 'modern-job-board-theme' ); ?>">
					<p class="mjb-kb-toc__title"><?php esc_html_e( 'On this page', 'modern-job-board-theme' ); ?></p>
					<ul>
						<?php foreach ( $mjb_article['toc'] as $mjb_heading ) : ?>
							<li class="mjb-kb-toc__item mjb-kb-toc__item--h<?php echo esc_attr( (string) $mjb_heading['level'] ); ?>"><a href="#<?php echo esc_attr( $mjb_heading['id'] ); ?>"><?php echo esc_html( $mjb_heading['text'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>
			</aside>
		<?php endif; ?>
	</div>
	<?php
endwhile;

get_footer();
