<?php
/**
 * Webba custom blocks.
 *
 * @package Webba_Starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shared advanced block supports.
 *
 * @return array
 */
function webba_block_supports() {
	return array(
		'align'      => array( 'wide', 'full' ),
		'anchor'     => true,
		'className'  => true,
		'html'       => false,
		'color'      => array(
			'text'       => true,
			'background' => true,
			'link'       => true,
			'gradients'  => true,
		),
		'typography' => array(
			'fontSize'       => true,
			'lineHeight'     => true,
			'fontFamily'     => true,
			'fontStyle'      => true,
			'fontWeight'     => true,
			'letterSpacing'  => true,
			'textTransform'  => true,
			'textDecoration' => true,
		),
		'spacing'    => array(
			'margin'   => true,
			'padding'  => true,
			'blockGap' => true,
		),
		'border'     => array(
			'color'  => true,
			'radius' => true,
			'style'  => true,
			'width'  => true,
		),
		'dimensions' => array(
			'minHeight' => true,
		),
		'shadow'     => true,
	);
}

/**
 * Shared block attributes.
 *
 * @return array
 */
function webba_shared_block_attributes() {
	return array(
		'align'           => array( 'type' => 'string' ),
		'backgroundImage' => array(
			'type'    => 'string',
			'default' => '',
		),
		'overlayOpacity'  => array(
			'type'    => 'number',
			'default' => 0,
		),
		'sectionStyle'    => array(
			'type'    => 'string',
			'default' => 'default',
		),
	);
}

/**
 * Build wrapper attributes.
 *
 * @param array  $attributes Block attributes.
 * @param string $class_name Class names.
 * @return string
 */
function webba_block_wrapper( $attributes, $class_name ) {
	$style = '';

	if ( ! empty( $attributes['backgroundImage'] ) ) {
		$style .= 'background-image: linear-gradient(rgba(15,23,42,' . esc_attr( (float) $attributes['overlayOpacity'] ) . '), rgba(15,23,42,' . esc_attr( (float) $attributes['overlayOpacity'] ) . ')), url(' . esc_url( $attributes['backgroundImage'] ) . ');';
	}

	if ( ! empty( $attributes['sectionStyle'] ) ) {
		$class_name .= ' webba-block--' . sanitize_html_class( $attributes['sectionStyle'] );
	}

	return get_block_wrapper_attributes(
		array(
			'class' => $class_name,
			'style' => $style,
		)
	);
}

/**
 * Sanitize list attributes.
 *
 * @param mixed $items Items.
 * @return array
 */
function webba_sanitize_items( $items ) {
	return is_array( $items ) ? $items : array();
}

/**
 * Default card content by block type.
 *
 * @param string $type Block type.
 * @return array
 */
function webba_default_card_block_content( $type ) {
	$defaults = array(
		'services'     => array(
			'eyebrow'     => __( 'Services', 'webba-starter' ),
			'title'       => __( 'Services designed for easy scheduling.', 'webba-starter' ),
			'description' => __( 'Show durations, outcomes, and booking-friendly service details.', 'webba-starter' ),
			'items'       => array(
				array(
					'title' => __( 'Consultation', 'webba-starter' ),
					'text'  => __( 'A focused first appointment.', 'webba-starter' ),
					'label' => __( '30 min', 'webba-starter' ),
				),
				array(
					'title' => __( 'Treatment', 'webba-starter' ),
					'text'  => __( 'A longer session with a specialist.', 'webba-starter' ),
					'label' => __( '60 min', 'webba-starter' ),
				),
				array(
					'title' => __( 'Follow-up', 'webba-starter' ),
					'text'  => __( 'Keep clients coming back.', 'webba-starter' ),
					'label' => __( '15 min', 'webba-starter' ),
				),
			),
		),
		'pricing'      => array(
			'eyebrow' => __( 'Pricing', 'webba-starter' ),
			'title'   => __( 'Transparent service packages.', 'webba-starter' ),
			'items'   => array(
				array(
					'title' => __( 'Starter', 'webba-starter' ),
					'text'  => __( 'Single service booking.', 'webba-starter' ),
					'price' => '$49',
				),
				array(
					'title' => __( 'Professional', 'webba-starter' ),
					'text'  => __( 'Packages and deposits.', 'webba-starter' ),
					'price' => '$89',
				),
				array(
					'title' => __( 'Premium', 'webba-starter' ),
					'text'  => __( 'Advanced scheduling.', 'webba-starter' ),
					'price' => '$129',
				),
			),
		),
		'staff'        => array(
			'eyebrow' => __( 'Team', 'webba-starter' ),
			'title'   => __( 'Meet the professionals.', 'webba-starter' ),
			'items'   => array(
				array(
					'title' => __( 'Alex Morgan', 'webba-starter' ),
					'text'  => __( 'Lead specialist', 'webba-starter' ),
					'label' => __( 'Senior', 'webba-starter' ),
				),
				array(
					'title' => __( 'Sam Rivera', 'webba-starter' ),
					'text'  => __( 'Client care', 'webba-starter' ),
					'label' => __( 'Support', 'webba-starter' ),
				),
				array(
					'title' => __( 'Taylor Chen', 'webba-starter' ),
					'text'  => __( 'Service expert', 'webba-starter' ),
					'label' => __( 'Provider', 'webba-starter' ),
				),
			),
		),
		'testimonials' => array(
			'eyebrow' => __( 'Testimonials', 'webba-starter' ),
			'title'   => __( 'Clients appreciate the simple booking flow.', 'webba-starter' ),
			'items'   => array(
				array(
					'title'  => __( 'Avery Brooks', 'webba-starter' ),
					'text'   => __( 'The booking process was easy from my phone.', 'webba-starter' ),
					'rating' => 5,
				),
				array(
					'title'  => __( 'Morgan Lee', 'webba-starter' ),
					'text'   => __( 'A polished experience from start to finish.', 'webba-starter' ),
					'rating' => 5,
				),
				array(
					'title'  => __( 'Jordan Smith', 'webba-starter' ),
					'text'   => __( 'The reminders made the appointment simple.', 'webba-starter' ),
					'rating' => 5,
				),
			),
		),
	);

	return $defaults[ $type ] ?? array();
}

/**
 * Render Webba Hero.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function webba_render_hero_block( $attributes ) {
	$title       = $attributes['title'] ?? __( 'Launch a polished booking website.', 'webba-starter' );
	$eyebrow     = $attributes['eyebrow'] ?? __( 'Webba Booking ready', 'webba-starter' );
	$description = $attributes['description'] ?? '';
	$button_text = $attributes['buttonText'] ?? __( 'Book Now', 'webba-starter' );
	$button_url  = $attributes['buttonUrl'] ?? '#booking';
	$media_url   = $attributes['mediaUrl'] ?? '';
	$layout      = $attributes['layout'] ?? 'media-right';
	$content_pos = $attributes['contentPosition'] ?? 'left';
	$vertical    = $attributes['verticalAlign'] ?? 'center';
	$image_pos   = $attributes['imagePosition'] ?? 'center';
	$features    = webba_sanitize_items( $attributes['features'] ?? array() );

	ob_start();
	?>
	<section <?php echo webba_block_wrapper( $attributes, 'webba-block webba-hero-block webba-section webba-hero-block--' . sanitize_html_class( $layout ) . ' webba-hero-block--content-' . sanitize_html_class( $content_pos ) . ' webba-hero-block--vertical-' . sanitize_html_class( $vertical ) . ' webba-hero-block--image-' . sanitize_html_class( $image_pos ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="webba-container webba-hero-block__grid">
			<div class="webba-hero-block__content">
				<?php if ( $eyebrow ) : ?><p class="webba-eyebrow"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
				<h1><?php echo esc_html( $title ); ?></h1>
				<?php if ( $description ) : ?><p class="webba-hero-block__description"><?php echo esc_html( $description ); ?></p><?php endif; ?>
				<?php if ( $button_text ) : ?><a class="webba-button" href="<?php echo esc_url( $button_url ); ?>"><?php echo esc_html( $button_text ); ?></a><?php endif; ?>
			</div>
			<div class="webba-hero-block__visual">
				<?php if ( $media_url ) : ?>
					<img src="<?php echo esc_url( $media_url ); ?>" alt="">
				<?php else : ?>
					<div class="webba-hero-block__panel">
						<strong><?php esc_html_e( 'Booking highlights', 'webba-starter' ); ?></strong>
						<ul>
							<?php foreach ( $features as $feature ) : ?>
								<?php if ( ! empty( $feature['text'] ) ) : ?><li><?php echo esc_html( $feature['text'] ); ?></li><?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Render card-based blocks.
 *
 * @param array  $attributes Block attributes.
 * @param string $type       Block type.
 * @return string
 */
function webba_render_cards_block( $attributes, $type ) {
	$defaults    = webba_default_card_block_content( $type );
	$title       = $attributes['title'] ?? ( $defaults['title'] ?? '' );
	$eyebrow     = $attributes['eyebrow'] ?? ( $defaults['eyebrow'] ?? '' );
	$description = $attributes['description'] ?? ( $defaults['description'] ?? '' );
	$items       = webba_sanitize_items( $attributes['items'] ?? ( $defaults['items'] ?? array() ) );
	$columns     = max( 1, min( 4, absint( $attributes['columns'] ?? 3 ) ) );

	ob_start();
	?>
	<section <?php echo webba_block_wrapper( $attributes, 'webba-block webba-section webba-' . sanitize_html_class( $type ) . '-block' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="webba-container">
			<?php if ( $title || $eyebrow || $description ) : ?>
				<div class="webba-section-heading">
					<?php if ( $eyebrow ) : ?><p class="webba-eyebrow"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
					<?php if ( $title ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?>
					<?php if ( $description ) : ?><p><?php echo esc_html( $description ); ?></p><?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="webba-block-grid webba-block-grid--<?php echo esc_attr( $columns ); ?>">
				<?php foreach ( $items as $item ) : ?>
					<article class="webba-block-card">
						<?php if ( 'testimonials' === $type ) : ?>
							<div class="webba-testimonial-stars" aria-label="<?php echo esc_attr( sprintf( __( '%d out of 5 stars', 'webba-starter' ), absint( $item['rating'] ?? 5 ) ) ); ?>">
								<?php echo esc_html( str_repeat( '★', max( 1, min( 5, absint( $item['rating'] ?? 5 ) ) ) ) ); ?>
							</div>
							<?php if ( ! empty( $item['text'] ) ) : ?><blockquote class="webba-testimonial-quote"><?php echo esc_html( $item['text'] ); ?></blockquote><?php endif; ?>
							<?php if ( ! empty( $item['title'] ) ) : ?><p class="webba-testimonial-author"><?php echo esc_html( $item['title'] ); ?></p><?php endif; ?>
						<?php else : ?>
							<?php if ( ! empty( $item['imageUrl'] ) ) : ?><img class="webba-block-card__image" src="<?php echo esc_url( $item['imageUrl'] ); ?>" alt=""><?php endif; ?>
							<?php if ( ! empty( $item['label'] ) ) : ?><p class="webba-card-label"><?php echo esc_html( $item['label'] ); ?></p><?php endif; ?>
							<?php if ( ! empty( $item['title'] ) ) : ?><h3><?php echo esc_html( $item['title'] ); ?></h3><?php endif; ?>
							<?php if ( ! empty( $item['price'] ) ) : ?><p class="webba-card-price"><?php echo esc_html( $item['price'] ); ?></p><?php endif; ?>
							<?php if ( ! empty( $item['text'] ) ) : ?><p><?php echo esc_html( $item['text'] ); ?></p><?php endif; ?>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Convert a Webba shortcode string into Gutenberg block attributes.
 *
 * @param string $shortcode Shortcode text.
 * @return array
 */
function webba_parse_booking_shortcode_attributes( $shortcode ) {
	$attributes = array();

	if ( ! preg_match( '/^\s*\[webbabooking([^\]]*)\]\s*$/', $shortcode, $matches ) ) {
		return $attributes;
	}

	$shortcode_attributes = shortcode_parse_atts( $matches[1] );

	if ( ! is_array( $shortcode_attributes ) ) {
		return $attributes;
	}

	if ( ! empty( $shortcode_attributes['service'] ) ) {
		$attributes['service'] = sanitize_text_field( $shortcode_attributes['service'] );
	}

	foreach ( array( 'category', 'location', 'staff' ) as $key ) {
		if ( ! empty( $shortcode_attributes[ $key ] ) ) {
			$attributes[ $key ] = array_filter( array_map( 'absint', explode( ',', $shortcode_attributes[ $key ] ) ) );
		}
	}

	if ( ! empty( $shortcode_attributes['category_list'] ) && 'yes' === $shortcode_attributes['category_list'] ) {
		$attributes['categoryList'] = true;
	}

	return $attributes;
}

/**
 * Render the real Webba Booking form and enqueue plugin assets when possible.
 *
 * @param string $shortcode Shortcode text.
 * @return string
 */
function webba_render_booking_form_embed( $shortcode ) {
	$shortcode = trim( $shortcode );

	if ( '' === $shortcode ) {
		$shortcode = webba_get_booking_shortcode();
	}

	if ( class_exists( 'WBK_Gutenberg_Booking_Form_Block' ) && method_exists( 'WBK_Gutenberg_Booking_Form_Block', 'render' ) ) {
		$output = WBK_Gutenberg_Booking_Form_Block::render(
			webba_parse_booking_shortcode_attributes( $shortcode ),
			'',
			null
		);

		if ( '' !== trim( $output ) ) {
			return $output;
		}
	}

	$output = do_shortcode( wp_kses_post( $shortcode ) );

	if ( trim( $output ) === $shortcode && current_user_can( 'activate_plugins' ) ) {
		return '<p class="webba-booking-warning">' . esc_html__( 'Webba Booking shortcode is not rendering. Confirm the Webba Booking plugin is active and at least one service is configured.', 'webba-starter' ) . '</p>';
	}

	return $output;
}

/**
 * Enqueue Webba Booking assets early when the page contains the theme booking block.
 */
function webba_enqueue_booking_block_assets() {
	if ( is_admin() || ! is_singular() || ! function_exists( 'has_block' ) ) {
		return;
	}

	$post = get_post();

	if ( ! $post instanceof WP_Post || ! has_block( 'webba/booking', $post ) ) {
		return;
	}

	if ( class_exists( 'WBK_Gutenberg_Booking_Form_Block' ) && method_exists( 'WBK_Gutenberg_Booking_Form_Block', 'enqueue_frontend_assets' ) ) {
		WBK_Gutenberg_Booking_Form_Block::enqueue_frontend_assets();
	}
}
add_action( 'wp_enqueue_scripts', 'webba_enqueue_booking_block_assets', 20 );

/**
 * Render Webba Booking block.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function webba_render_booking_block( $attributes ) {
	$title       = $attributes['title'] ?? __( 'Book your appointment', 'webba-starter' );
	$eyebrow     = $attributes['eyebrow'] ?? __( 'Booking', 'webba-starter' );
	$description = $attributes['description'] ?? '';
	$shortcode   = $attributes['shortcode'] ?? webba_get_booking_shortcode();

	if ( '' === trim( $shortcode ) ) {
		$shortcode = webba_get_booking_shortcode();
	}

	ob_start();
	?>
	<section <?php echo webba_block_wrapper( $attributes, 'webba-block webba-section webba-booking-block' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="webba-container">
			<div class="webba-booking-card">
				<?php if ( $eyebrow ) : ?><p class="webba-eyebrow"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
				<?php if ( $title ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?>
				<?php if ( $description ) : ?><p><?php echo esc_html( $description ); ?></p><?php endif; ?>
				<div class="webba-booking-embed">
					<?php echo webba_render_booking_form_embed( $shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Render FAQ block.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function webba_render_faq_block( $attributes ) {
	$title       = $attributes['title'] ?? __( 'Frequently asked questions', 'webba-starter' );
	$eyebrow     = $attributes['eyebrow'] ?? __( 'FAQ', 'webba-starter' );
	$description = $attributes['description'] ?? '';
	$items       = webba_sanitize_items( $attributes['items'] ?? array() );

	ob_start();
	?>
	<section <?php echo webba_block_wrapper( $attributes, 'webba-block webba-section webba-faq-block' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="webba-container webba-narrow">
			<?php if ( $eyebrow ) : ?><p class="webba-eyebrow"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
			<?php if ( $title ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?>
			<?php if ( $description ) : ?><p><?php echo esc_html( $description ); ?></p><?php endif; ?>
			<?php foreach ( $items as $item ) : ?>
				<details class="webba-faq">
					<summary><?php echo esc_html( $item['title'] ?? '' ); ?></summary>
					<p><?php echo esc_html( $item['text'] ?? '' ); ?></p>
				</details>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Render CTA block.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function webba_render_cta_block( $attributes ) {
	$title       = $attributes['title'] ?? __( 'Ready to book?', 'webba-starter' );
	$description = $attributes['description'] ?? '';
	$button_text = $attributes['buttonText'] ?? __( 'Book Now', 'webba-starter' );
	$button_url  = $attributes['buttonUrl'] ?? '#booking';

	ob_start();
	?>
	<section <?php echo webba_block_wrapper( $attributes, 'webba-block webba-section webba-cta-block' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="webba-container webba-cta-block__inner">
			<div>
				<h2><?php echo esc_html( $title ); ?></h2>
				<?php if ( $description ) : ?><p><?php echo esc_html( $description ); ?></p><?php endif; ?>
			</div>
			<?php if ( $button_text ) : ?><a class="webba-button" href="<?php echo esc_url( $button_url ); ?>"><?php echo esc_html( $button_text ); ?></a><?php endif; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Register block asset handles.
 */
function webba_register_block_assets() {
	wp_register_script(
		'webba-blocks-editor',
		WEBBA_URI . 'assets/js/blocks.js',
		array( 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-components', 'wp-server-side-render' ),
		WEBBA_VERSION,
		true
	);

	wp_register_style(
		'webba-blocks',
		WEBBA_URI . 'assets/css/blocks.css',
		array(),
		WEBBA_VERSION
	);
}
add_action( 'init', 'webba_register_block_assets', 5 );

/**
 * Register Webba blocks.
 */
function webba_register_blocks() {
	$shared = webba_shared_block_attributes();

	$heading_attrs = array_merge(
		$shared,
		array(
			'title'       => array( 'type' => 'string' ),
			'eyebrow'     => array( 'type' => 'string' ),
			'description' => array( 'type' => 'string' ),
			'columns'     => array(
				'type'    => 'number',
				'default' => 3,
			),
			'items'       => array(
				'type'    => 'array',
				'default' => array(),
			),
		)
	);

	$blocks = array(
		'webba/hero'         => array(
			'title'           => __( 'Webba Hero', 'webba-starter' ),
			'description'     => __( 'Advanced hero section for Webba demo pages.', 'webba-starter' ),
			'icon'            => 'cover-image',
			'attributes'      => array_merge(
				$shared,
				array(
					'title'       => array( 'type' => 'string' ),
					'eyebrow'     => array( 'type' => 'string' ),
					'description' => array( 'type' => 'string' ),
					'buttonText'  => array( 'type' => 'string' ),
					'buttonUrl'   => array( 'type' => 'string' ),
					'mediaUrl'    => array( 'type' => 'string' ),
					'layout'      => array(
						'type'    => 'string',
						'default' => 'media-right',
					),
					'contentPosition' => array(
						'type'    => 'string',
						'default' => 'left',
					),
					'verticalAlign' => array(
						'type'    => 'string',
						'default' => 'center',
					),
					'imagePosition' => array(
						'type'    => 'string',
						'default' => 'center',
					),
					'features'    => array(
						'type'    => 'array',
						'default' => array(
							array( 'text' => __( 'Real-time service booking', 'webba-starter' ) ),
							array( 'text' => __( 'Deposits and approvals', 'webba-starter' ) ),
							array( 'text' => __( 'Reminder-ready workflows', 'webba-starter' ) ),
						),
					),
				)
			),
			'render_callback' => 'webba_render_hero_block',
		),
		'webba/services'     => array(
			'title'           => __( 'Webba Services', 'webba-starter' ),
			'description'     => __( 'Service cards with images, labels, and durations.', 'webba-starter' ),
			'icon'            => 'grid-view',
			'attributes'      => $heading_attrs,
			'render_callback' => function( $attributes ) {
				return webba_render_cards_block( $attributes, 'services' );
			},
		),
		'webba/pricing'      => array(
			'title'           => __( 'Webba Pricing', 'webba-starter' ),
			'description'     => __( 'Pricing and package cards.', 'webba-starter' ),
			'icon'            => 'money-alt',
			'attributes'      => $heading_attrs,
			'render_callback' => function( $attributes ) {
				return webba_render_cards_block( $attributes, 'pricing' );
			},
		),
		'webba/staff'        => array(
			'title'           => __( 'Webba Staff', 'webba-starter' ),
			'description'     => __( 'Staff or provider cards.', 'webba-starter' ),
			'icon'            => 'groups',
			'attributes'      => $heading_attrs,
			'render_callback' => function( $attributes ) {
				return webba_render_cards_block( $attributes, 'staff' );
			},
		),
		'webba/testimonials' => array(
			'title'           => __( 'Webba Testimonials', 'webba-starter' ),
			'description'     => __( 'Client testimonial cards.', 'webba-starter' ),
			'icon'            => 'format-quote',
			'attributes'      => $heading_attrs,
			'render_callback' => function( $attributes ) {
				return webba_render_cards_block( $attributes, 'testimonials' );
			},
		),
		'webba/booking'      => array(
			'title'           => __( 'Webba Booking', 'webba-starter' ),
			'description'     => __( 'Booking section with shortcode or Webba block replacement guidance.', 'webba-starter' ),
			'icon'            => 'calendar-alt',
			'attributes'      => array_merge(
				$shared,
				array(
					'title'       => array( 'type' => 'string' ),
					'eyebrow'     => array( 'type' => 'string' ),
					'description' => array( 'type' => 'string' ),
					'shortcode'   => array(
						'type'    => 'string',
						'default' => '[webbabooking]',
					),
					'previewInEditor' => array(
						'type'    => 'boolean',
						'default' => true,
					),
				)
			),
			'render_callback' => 'webba_render_booking_block',
		),
		'webba/faq'          => array(
			'title'           => __( 'Webba FAQ', 'webba-starter' ),
			'description'     => __( 'Accordion FAQ section.', 'webba-starter' ),
			'icon'            => 'editor-help',
			'attributes'      => $heading_attrs,
			'render_callback' => 'webba_render_faq_block',
		),
		'webba/contact-cta'  => array(
			'title'           => __( 'Webba Contact CTA', 'webba-starter' ),
			'description'     => __( 'Conversion call to action section.', 'webba-starter' ),
			'icon'            => 'megaphone',
			'attributes'      => array_merge(
				$shared,
				array(
					'title'       => array( 'type' => 'string' ),
					'description' => array( 'type' => 'string' ),
					'buttonText'  => array( 'type' => 'string' ),
					'buttonUrl'   => array( 'type' => 'string' ),
				)
			),
			'render_callback' => 'webba_render_cta_block',
		),
	);

	foreach ( $blocks as $name => $args ) {
		register_block_type(
			$name,
			array_merge(
				$args,
				array(
					'api_version'   => 2,
					'category'      => 'webba',
					'supports'      => webba_block_supports(),
					'editor_script' => 'webba-blocks-editor',
					'style'         => 'webba-blocks',
				)
			)
		);
	}
}
add_action( 'init', 'webba_register_blocks' );

/**
 * Register the block category.
 *
 * @param array $categories Block categories.
 * @return array
 */
function webba_block_categories( $categories ) {
	return array_merge(
		array(
			array(
				'slug'  => 'webba',
				'title' => __( 'Webba Blocks', 'webba-starter' ),
				'icon'  => 'calendar-alt',
			),
		),
		$categories
	);
}
add_filter( 'block_categories_all', 'webba_block_categories' );

/**
 * Enqueue editor block script and styles.
 */
function webba_enqueue_block_editor_assets() {
	wp_enqueue_script( 'webba-blocks-editor' );

	wp_enqueue_style(
		'webba-blocks-editor',
		WEBBA_URI . 'assets/css/blocks.css',
		array(),
		WEBBA_VERSION
	);
}
add_action( 'enqueue_block_editor_assets', 'webba_enqueue_block_editor_assets' );
