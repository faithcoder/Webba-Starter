<?php
/**
 * Site header.
 *
 * @package Webba_Starter
 */

$webba_cta_text = get_theme_mod( 'webba_header_cta_text', __( 'Book Now', 'webba-starter' ) );
$webba_cta_url  = get_theme_mod( 'webba_header_cta_url', '#booking' );
$webba_show_cta = get_theme_mod( 'webba_show_header_cta', true );
?>

<header class="webba-site-header">
	<div class="webba-container webba-site-header__inner">
		<div class="webba-site-branding">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a class="webba-site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
			<?php endif; ?>
		</div>

		<button class="webba-menu-toggle" type="button" aria-controls="webba-primary-menu" aria-expanded="false">
			<span class="screen-reader-text"><?php esc_html_e( 'Toggle menu', 'webba-starter' ); ?></span>
			<span></span><span></span><span></span>
		</button>

		<nav id="webba-primary-menu" class="webba-primary-nav" aria-label="<?php esc_attr_e( 'Primary Menu', 'webba-starter' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_class'     => 'webba-menu',
					'container'      => false,
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>

		<?php if ( $webba_show_cta && $webba_cta_text ) : ?>
			<a class="webba-button webba-button--small" href="<?php echo esc_url( $webba_cta_url ); ?>">
				<?php echo esc_html( $webba_cta_text ); ?>
			</a>
		<?php endif; ?>
	</div>
</header>
