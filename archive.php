<?php
/**
 * Archive template.
 *
 * @package Webba_Starter
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="webba-container webba-content">
		<header class="webba-entry__header">
			<?php the_archive_title( '<h1 class="webba-entry__title">', '</h1>' ); ?>
			<?php the_archive_description( '<div class="webba-entry__description">', '</div>' ); ?>
		</header>
		<?php if ( have_posts() ) : ?>
			<div class="webba-post-grid">
				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'webba-card' ); ?>>
						<?php the_title( '<h2 class="webba-card__title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
						<div class="webba-card__text"><?php the_excerpt(); ?></div>
					</article>
				<?php endwhile; ?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'No posts found.', 'webba-starter' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
