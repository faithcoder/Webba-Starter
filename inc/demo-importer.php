<?php
/**
 * One Click Demo Import integration.
 *
 * @package Webba_Starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register available demo imports.
 *
 * @return array
 */
function webba_ocdi_import_files() {
	$base = trailingslashit( get_template_directory() ) . 'demo-content/';
	$url  = trailingslashit( get_template_directory_uri() ) . 'demo-content/';

	$demos = array(
		'dental'  => esc_html__( 'Dental Clinic', 'webba-starter' ),
		'salon'   => esc_html__( 'Hair Salon', 'webba-starter' ),
		'spa'     => esc_html__( 'Massage & Spa', 'webba-starter' ),
		'fitness' => esc_html__( 'Fitness Trainer', 'webba-starter' ),
		'rental'  => esc_html__( 'Rental Units', 'webba-starter' ),
	);

	$imports = array();

	foreach ( $demos as $slug => $name ) {
		$imports[] = array(
			'import_file_name'           => $name,
			'local_import_file'          => $base . $slug . '/content.xml',
			'local_import_widget_file'   => $base . $slug . '/widgets.wie',
			'local_import_customizer_file' => $base . $slug . '/customizer.dat',
			'import_preview_image_url'   => $url . $slug . '/preview.jpg',
			'preview_url'                => home_url( '/' ),
		);
	}

	return $imports;
}
add_filter( 'ocdi/import_files', 'webba_ocdi_import_files' );

/**
 * Configure site after importing demo content.
 *
 * @param array $selected_import Selected import metadata.
 */
function webba_ocdi_after_import( $selected_import ) {
	$homepage = get_page_by_title( $selected_import['import_file_name'] );

	if ( $homepage instanceof WP_Post ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $homepage->ID );
	}

	$primary_menu = wp_get_nav_menu_object( 'Primary Menu' );

	if ( $primary_menu ) {
		$locations            = get_theme_mod( 'nav_menu_locations', array() );
		$locations['primary'] = $primary_menu->term_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	set_transient( 'webba_ocdi_after_import_notice', true, MINUTE_IN_SECONDS );
}
add_action( 'ocdi/after_import', 'webba_ocdi_after_import' );

/**
 * Show post-import instructions.
 */
function webba_ocdi_after_import_notice() {
	if ( ! get_transient( 'webba_ocdi_after_import_notice' ) ) {
		return;
	}

	delete_transient( 'webba_ocdi_after_import_notice' );

	printf(
		'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
		esc_html__( 'Please install and configure Webba Booking services. Then replace or confirm the booking shortcode/block on your demo page.', 'webba-starter' )
	);
}
add_action( 'admin_notices', 'webba_ocdi_after_import_notice' );
