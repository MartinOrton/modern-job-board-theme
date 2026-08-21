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

		$post_obj = get_post();
		$content  = ( $post_obj instanceof WP_Post ) ? $post_obj->post_content : '';

		// Login shortcodes render the full jobs-board chrome (hero + container + panels).
		// Do not wrap them in the editorial page hero/article shell — that breaks padding/colors.
		// Docs reference uses its own sidebar shell (docs-shell) and must not nest in .container.
		$is_full_board_layout = $content
			&& (
				has_shortcode( $content, 'mjb_candidate_login' )
				|| has_shortcode( $content, 'mjb_employer_login' )
				|| is_page( 'docs' )
				|| false !== strpos( $content, 'docs-shell' )
			);

		if ( $is_full_board_layout ) {
			the_content();
			continue;
		}

		$hero_intro = '';
		if ( has_excerpt() ) {
			$hero_intro = get_the_excerpt();
		}
		?>
		<section class="mjb-content-hero alignfull">
			<div class="mjb-content-hero__inner container">
				<h1 class="mjb-content-hero__title"><?php the_title(); ?></h1>
				<?php if ( $hero_intro !== '' ) : ?>
					<p class="mjb-content-hero__intro"><?php echo esc_html( $hero_intro ); ?></p>
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
