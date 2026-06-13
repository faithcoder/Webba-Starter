<?php
/**
 * Customizer settings.
 *
 * @package Webba_Starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize checkbox values.
 *
 * @param mixed $checked Checkbox value.
 * @return bool
 */
function webba_sanitize_checkbox( $checked ) {
	return (bool) $checked;
}

/**
 * Sanitize shortcode-like text.
 *
 * @param string $value Setting value.
 * @return string
 */
function webba_sanitize_shortcode( $value ) {
	return wp_kses_post( trim( $value ) );
}

/**
 * Register customizer options.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function webba_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'webba_business_settings',
		array(
			'title'       => esc_html__( 'Webba Business Settings', 'webba-starter' ),
			'description' => esc_html__( 'Configure booking calls to action and business contact details.', 'webba-starter' ),
			'priority'    => 35,
		)
	);

	$settings = array(
		'webba_header_cta_text'    => array(
			'default'           => esc_html__( 'Book Now', 'webba-starter' ),
			'sanitize_callback' => 'sanitize_text_field',
			'label'             => esc_html__( 'Header CTA Text', 'webba-starter' ),
			'type'              => 'text',
		),
		'webba_header_cta_url'     => array(
			'default'           => '#booking',
			'sanitize_callback' => 'esc_url_raw',
			'label'             => esc_html__( 'Header CTA URL', 'webba-starter' ),
			'type'              => 'url',
		),
		'webba_footer_copyright'   => array(
			'default'           => sprintf(
				/* translators: %s: current year. */
				esc_html__( 'Copyright %s. All rights reserved.', 'webba-starter' ),
				gmdate( 'Y' )
			),
			'sanitize_callback' => 'sanitize_text_field',
			'label'             => esc_html__( 'Footer Copyright', 'webba-starter' ),
			'type'              => 'text',
		),
		'webba_phone'              => array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'label'             => esc_html__( 'Phone', 'webba-starter' ),
			'type'              => 'text',
		),
		'webba_email'              => array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_email',
			'label'             => esc_html__( 'Email', 'webba-starter' ),
			'type'              => 'email',
		),
		'webba_default_shortcode'  => array(
			'default'           => '[webbabooking]',
			'sanitize_callback' => 'webba_sanitize_shortcode',
			'label'             => esc_html__( 'Default Booking Shortcode', 'webba-starter' ),
			'type'              => 'text',
		),
		'webba_show_header_cta'    => array(
			'default'           => true,
			'sanitize_callback' => 'webba_sanitize_checkbox',
			'label'             => esc_html__( 'Show Header CTA', 'webba-starter' ),
			'type'              => 'checkbox',
		),
	);

	foreach ( $settings as $setting_id => $args ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $args['default'],
				'sanitize_callback' => $args['sanitize_callback'],
			)
		);

		$wp_customize->add_control(
			$setting_id,
			array(
				'label'   => $args['label'],
				'section' => 'webba_business_settings',
				'type'    => $args['type'],
			)
		);
	}
}
add_action( 'customize_register', 'webba_customize_register' );
