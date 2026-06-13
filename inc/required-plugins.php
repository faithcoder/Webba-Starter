<?php
/**
 * Recommended and required plugins.
 *
 * @package Webba_Starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$webba_tgmpa = WEBBA_DIR . 'inc/tgm-plugin-activation/class-tgm-plugin-activation.php';

if ( file_exists( $webba_tgmpa ) ) {
	require_once $webba_tgmpa;
}

/**
 * Register plugins with TGMPA when available.
 */
function webba_register_required_plugins() {
	if ( ! function_exists( 'tgmpa' ) ) {
		return;
	}

	$plugins = array(
		array(
			'name'     => esc_html__( 'Webba Booking', 'webba-starter' ),
			'slug'     => 'webba-booking-lite',
			'required' => true,
		),
		array(
			'name'     => esc_html__( 'One Click Demo Import', 'webba-starter' ),
			'slug'     => 'one-click-demo-import',
			'required' => false,
		),
		array(
			'name'     => esc_html__( 'Elementor', 'webba-starter' ),
			'slug'     => 'elementor',
			'required' => false,
		),
	);

	$config = array(
		'id'           => 'webba-starter',
		'default_path' => '',
		'menu'         => 'webba-install-plugins',
		'has_notices'  => true,
		'dismissable'  => true,
		'is_automatic' => false,
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'webba_register_required_plugins' );
