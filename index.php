<?php
/**
 * Main template.
 *
 * @package Webba_Starter
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="webba-container webba-content">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'webba-entry' ); ?>>
					<header class="webba-entry__header">
						<?php the_title( '<h1 class="webba-entry__title">', '</h1>' ); ?>
					</header>
					<div class="webba-entry__content">
						<?php the_content(); ?>
					</div>
				</article>
			<?php endwhile; ?>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'No content found.', 'webba-starter' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
