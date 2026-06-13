<?php
/**
 * Page template.
 *
 * @package Webba_Starter
 */

get_header();
?>

<main id="primary" class="site-main">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'webba-entry' ); ?>>
			<?php if ( ! is_front_page() ) : ?>
				<header class="webba-container webba-entry__header">
					<?php the_title( '<h1 class="webba-entry__title">', '</h1>' ); ?>
				</header>
			<?php endif; ?>
			<div class="webba-entry__content">
				<?php the_content(); ?>
			</div>
		</article>
	<?php endwhile; ?>
</main>

<?php
get_footer();
