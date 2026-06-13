<?php
/**
 * Theme functions.
 *
 * @package Webba_Starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WEBBA_VERSION', '1.0.0' );
define( 'WEBBA_DIR', trailingslashit( get_template_directory() ) );
define( 'WEBBA_URI', trailingslashit( get_template_directory_uri() ) );

if ( ! function_exists( 'webba_setup' ) ) {
	/**
	 * Register theme supports, menus, and editor assets.
	 */
	function webba_setup() {
		load_theme_textdomain( 'webba-starter', WEBBA_DIR . 'languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-logo', array(
			'height'      => 80,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		) );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		) );

		add_editor_style( array( 'assets/css/main.css', 'assets/css/blocks.css' ) );

		register_nav_menus( array(
			'primary' => esc_html__( 'Primary Menu', 'webba-starter' ),
			'footer'  => esc_html__( 'Footer Menu', 'webba-starter' ),
		) );
	}
}
add_action( 'after_setup_theme', 'webba_setup' );

/**
 * Set a readable content width.
 */
function webba_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'webba_content_width', 760 );
}
add_action( 'after_setup_theme', 'webba_content_width', 0 );

/**
 * Enqueue front-end assets.
 */
function webba_enqueue_assets() {
	wp_enqueue_style(
		'webba-main',
		WEBBA_URI . 'assets/css/main.css',
		array(),
		WEBBA_VERSION
	);

	wp_enqueue_style(
		'webba-blocks',
		WEBBA_URI . 'assets/css/blocks.css',
		array( 'webba-main' ),
		WEBBA_VERSION
	);

	wp_enqueue_script(
		'webba-main',
		WEBBA_URI . 'assets/js/main.js',
		array(),
		WEBBA_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'webba_enqueue_assets' );

/**
 * Return the configured Webba Booking shortcode.
 *
 * @return string
 */
function webba_get_booking_shortcode() {
	$shortcode = get_theme_mod( 'webba_default_shortcode', '[webbabooking]' );

	if ( '' === trim( $shortcode ) ) {
		return '[webbabooking]';
	}

	return wp_kses_post( $shortcode );
}

/**
 * Register Gutenberg pattern categories.
 */
function webba_register_pattern_categories() {
	if ( ! function_exists( 'register_block_pattern_category' ) ) {
		return;
	}

	register_block_pattern_category(
		'webba-demo-pages',
		array( 'label' => esc_html__( 'Webba Demo Pages', 'webba-starter' ) )
	);

	register_block_pattern_category(
		'webba-sections',
		array( 'label' => esc_html__( 'Webba Sections', 'webba-starter' ) )
	);

	register_block_pattern_category(
		'webba-booking-sections',
		array( 'label' => esc_html__( 'Webba Booking Sections', 'webba-starter' ) )
	);
}
add_action( 'init', 'webba_register_pattern_categories' );

$webba_includes = array(
	'inc/customizer.php',
	'inc/webba-integration.php',
	'inc/required-plugins.php',
	'inc/demo-importer.php',
	'inc/blocks.php',
);

foreach ( $webba_includes as $webba_file ) {
	$webba_path = WEBBA_DIR . $webba_file;

	if ( file_exists( $webba_path ) ) {
		require_once $webba_path;
	}
}
