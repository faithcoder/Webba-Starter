<?php
/**
 * Booking section.
 *
 * Users may replace this default shortcode with the Webba Gutenberg block,
 * a custom Webba shortcode, or the Elementor Webba widget.
 *
 * @package Webba_Starter
 */

?>
<section id="booking" class="webba-section webba-booking-section">
	<div class="webba-container webba-booking-card">
		<div class="webba-section-heading">
			<p class="webba-eyebrow"><?php esc_html_e( 'Booking', 'webba-starter' ); ?></p>
			<h2><?php esc_html_e( 'Choose a time that works for you.', 'webba-starter' ); ?></h2>
		</div>
		<div class="webba-booking-embed">
			<?php echo do_shortcode( webba_get_booking_shortcode() ); ?>
		</div>
	</div>
</section>
