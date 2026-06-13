<?php
/**
 * Site footer.
 *
 * @package Webba_Starter
 */

$webba_phone     = get_theme_mod( 'webba_phone', '' );
$webba_email     = get_theme_mod( 'webba_email', '' );
$webba_copyright = get_theme_mod(
	'webba_footer_copyright',
	sprintf(
		/* translators: %s: current year. */
		__( 'Copyright %s. All rights reserved.', 'webba-starter' ),
		gmdate( 'Y' )
	)
);
?>

<footer class="webba-site-footer">
	<div class="webba-container webba-site-footer__inner">
		<div>
			<strong><?php bloginfo( 'name' ); ?></strong>
			<p><?php echo esc_html( $webba_copyright ); ?></p>
		</div>

		<nav class="webba-footer-nav" aria-label="<?php esc_attr_e( 'Footer Menu', 'webba-starter' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'menu_class'     => 'webba-footer-menu',
					'container'      => false,
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>

		<?php if ( $webba_phone || $webba_email ) : ?>
			<div class="webba-footer-contact">
				<?php if ( $webba_phone ) : ?>
					<a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $webba_phone ) ); ?>"><?php echo esc_html( $webba_phone ); ?></a>
				<?php endif; ?>
				<?php if ( $webba_email ) : ?>
					<a href="<?php echo esc_url( 'mailto:' . $webba_email ); ?>"><?php echo esc_html( $webba_email ); ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</footer>
