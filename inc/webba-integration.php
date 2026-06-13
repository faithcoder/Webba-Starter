<?php
/**
 * Safe Webba Booking integration helpers.
 *
 * @package Webba_Starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determine whether Webba Booking is active.
 *
 * @return bool
 */
function webba_is_webba_active() {
	if ( class_exists( 'WBK_Model_Utils' ) || class_exists( 'WBK_User_Utils' ) || defined( 'WBK_PLUGIN_URL' ) ) {
		return true;
	}

	include_once ABSPATH . 'wp-admin/includes/plugin.php';

	return is_plugin_active( 'webba-booking-lite/webba-booking-lite.php' ) || is_plugin_active( 'webba-booking/webba-booking.php' );
}

/**
 * Show admin guidance for Webba Booking.
 */
function webba_webba_admin_notices() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	if ( ! webba_is_webba_active() ) {
		printf(
			'<div class="notice notice-warning"><p>%s</p></div>',
			esc_html__( 'Webba Booking plugin is recommended for full booking functionality.', 'webba-starter' )
		);
	}

	printf(
		'<div class="notice notice-info"><p>%s</p></div>',
		esc_html__( 'Services, duration, availability, pricing, deposits, approval rules, reminders, buffers, and payment methods are configured inside Webba Booking, not inside the theme.', 'webba-starter' )
	);
}
add_action( 'admin_notices', 'webba_webba_admin_notices' );
