<?php
/**
 * Template Name: Elementor Full Width
 * Template Post Type: page
 *
 * @package Webba_Starter
 */

get_header();
?>

<main id="primary" class="site-main webba-full-width-template">
	<?php
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
	?>
</main>

<?php
get_footer();
