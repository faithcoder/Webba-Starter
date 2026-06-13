<?php
/**
 * Single post template.
 *
 * @package Webba_Starter
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="webba-container webba-content">
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'webba-entry' ); ?>>
				<header class="webba-entry__header">
					<?php the_title( '<h1 class="webba-entry__title">', '</h1>' ); ?>
					<div class="webba-entry__meta">
						<?php echo esc_html( get_the_date() ); ?>
					</div>
				</header>
				<div class="webba-entry__content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();
