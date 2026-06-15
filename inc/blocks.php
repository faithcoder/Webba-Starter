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
 * Sanitize staff social links.
 *
 * @param mixed $links Social links.
 * @return array
 */
function webba_sanitize_social_links( $links ) {
	if ( ! is_array( $links ) ) {
		return array();
	}

	$allowed_platforms = array( 'facebook', 'instagram', 'linkedin', 'x', 'youtube', 'website' );
	$sanitized         = array();

	foreach ( $links as $link ) {
		if ( ! is_array( $link ) ) {
			continue;
		}

		$platform = sanitize_key( $link['platform'] ?? '' );
		$url      = esc_url_raw( $link['url'] ?? '' );

		if ( ! in_array( $platform, $allowed_platforms, true ) || '' === $url ) {
			continue;
		}

		$sanitized[] = array(
			'platform' => $platform,
			'url'      => $url,
		);
	}

	return $sanitized;
}

/**
 * Sanitize a stored color value.
 *
 * @param mixed $color Color value.
 * @return string
 */
function webba_sanitize_color_value( $color ) {
	if ( ! is_string( $color ) ) {
		return '';
	}

	$color = sanitize_hex_color( $color );

	return is_string( $color ) ? $color : '';
}

/**
 * Get SVG icon markup for a staff social link.
 *
 * @param string $platform Platform slug.
 * @return string
 */
function webba_get_social_icon_svg( $platform ) {
	$icons = array(
		'facebook'  => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 21v-7h2.4l.4-3h-2.8V9.1c0-.9.3-1.6 1.7-1.6H16V4.8c-.3 0-.9-.1-1.9-.1-2.8 0-4.6 1.7-4.6 4.8V11H7v3h2.6v7h3.9Z" fill="currentColor"/></svg>',
		'instagram' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.8 3h8.4A4.8 4.8 0 0 1 21 7.8v8.4a4.8 4.8 0 0 1-4.8 4.8H7.8A4.8 4.8 0 0 1 3 16.2V7.8A4.8 4.8 0 0 1 7.8 3Zm0 1.8A3 3 0 0 0 4.8 7.8v8.4a3 3 0 0 0 3 3h8.4a3 3 0 0 0 3-3V7.8a3 3 0 0 0-3-3H7.8Zm8.85 1.35a1.05 1.05 0 1 1 0 2.1 1.05 1.05 0 0 1 0-2.1ZM12 7.5A4.5 4.5 0 1 1 7.5 12 4.5 4.5 0 0 1 12 7.5Zm0 1.8A2.7 2.7 0 1 0 14.7 12 2.7 2.7 0 0 0 12 9.3Z" fill="currentColor"/></svg>',
		'linkedin'  => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.2 8.3a1.7 1.7 0 1 1 0-3.4 1.7 1.7 0 0 1 0 3.4ZM4.7 9.9h3V19h-3V9.9Zm4.9 0h2.8v1.2h.1c.4-.7 1.4-1.5 2.8-1.5 3 0 3.6 2 3.6 4.5V19h-3v-4.2c0-1 0-2.4-1.5-2.4s-1.7 1.1-1.7 2.3V19h-3V9.9Z" fill="currentColor"/></svg>',
		'x'         => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18.9 4H21l-4.6 5.3L21.8 20h-4.2l-3.3-4.8L10.1 20H8l4.9-5.6L7.7 4h4.3l3 4.4L18.9 4Zm-.8 14.5h1.2L10.6 5.4H9.3l8.8 13.1Z" fill="currentColor"/></svg>',
		'youtube'   => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.6 8.2a2.8 2.8 0 0 0-2-2C17.8 5.7 12 5.7 12 5.7s-5.8 0-7.6.5a2.8 2.8 0 0 0-2 2A29 29 0 0 0 2 12a29 29 0 0 0 .4 3.8 2.8 2.8 0 0 0 2 2c1.8.5 7.6.5 7.6.5s5.8 0 7.6-.5a2.8 2.8 0 0 0 2-2A29 29 0 0 0 22 12a29 29 0 0 0-.4-3.8ZM10.1 15.2V8.8l5.5 3.2-5.5 3.2Z" fill="currentColor"/></svg>',
		'website'   => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3a9 9 0 1 0 9 9 9 9 0 0 0-9-9Zm6.9 8h-3.1a14.8 14.8 0 0 0-1.3-5.1A7.2 7.2 0 0 1 18.9 11ZM12 4.8c.8 0 2.2 2.2 2.8 6.2H9.2C9.8 7 11.2 4.8 12 4.8ZM9.5 5.9A14.8 14.8 0 0 0 8.2 11H5.1a7.2 7.2 0 0 1 4.4-5.1ZM5.1 13h3.1a14.8 14.8 0 0 0 1.3 5.1A7.2 7.2 0 0 1 5.1 13Zm6.9 6.2c-.8 0-2.2-2.2-2.8-6.2h5.6c-.6 4-2 6.2-2.8 6.2Zm2.5-1.1a14.8 14.8 0 0 0 1.3-5.1h3.1a7.2 7.2 0 0 1-4.4 5.1Z" fill="currentColor"/></svg>',
	);

	return $icons[ $platform ] ?? '';
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
			'description' => __( 'Show your appointment types with duration, value, and clear booking context.', 'webba-starter' ),
			'items'       => array(
				array(
					'title'      => __( 'Consultations', 'webba-starter' ),
					'text'       => __( 'Short sessions for first-time clients and follow-ups.', 'webba-starter' ),
					'label'      => __( '30 min', 'webba-starter' ),
					'buttonText' => __( 'View Details', 'webba-starter' ),
					'buttonUrl'  => '#',
				),
				array(
					'title'      => __( 'Treatments', 'webba-starter' ),
					'text'       => __( 'Longer appointments with clear duration and availability.', 'webba-starter' ),
					'label'      => __( '60 min', 'webba-starter' ),
					'buttonText' => __( 'View Details', 'webba-starter' ),
					'buttonUrl'  => '#',
				),
				array(
					'title'      => __( 'Packages', 'webba-starter' ),
					'text'       => __( 'Promote bundles, deposits, buffers, and repeat bookings.', 'webba-starter' ),
					'label'      => __( 'Popular', 'webba-starter' ),
					'buttonText' => __( 'View Details', 'webba-starter' ),
					'buttonUrl'  => '#',
				),
			),
		),
		'pricing'      => array(
			'eyebrow'     => __( 'Pricing', 'webba-starter' ),
			'title'       => __( 'Transparent service packages', 'webba-starter' ),
			'description' => __( 'Use pricing cards for deposits, service tiers, or promotional packages.', 'webba-starter' ),
			'sectionStyle' => 'light',
			'items'       => array(
				array(
					'title'      => __( 'Starter', 'webba-starter' ),
					'text'       => __( 'Single service appointments.', 'webba-starter' ),
					'price'      => '$49',
					'label'      => __( 'Basic', 'webba-starter' ),
					'buttonText' => __( 'Book Now', 'webba-starter' ),
					'buttonUrl'  => '#booking',
				),
				array(
					'title'      => __( 'Professional', 'webba-starter' ),
					'text'       => __( 'Packages, deposits, and buffers.', 'webba-starter' ),
					'price'      => '$89',
					'label'      => __( 'Popular', 'webba-starter' ),
					'buttonText' => __( 'Book Now', 'webba-starter' ),
					'buttonUrl'  => '#booking',
				),
				array(
					'title'      => __( 'Premium', 'webba-starter' ),
					'text'       => __( 'Advanced scheduling workflows.', 'webba-starter' ),
					'price'      => '$129',
					'label'      => __( 'Complete', 'webba-starter' ),
					'buttonText' => __( 'Book Now', 'webba-starter' ),
					'buttonUrl'  => '#booking',
				),
			),
		),
		'staff'        => array(
			'eyebrow'     => __( 'Team', 'webba-starter' ),
			'title'       => __( 'Experienced professionals ready to help.', 'webba-starter' ),
			'description' => __( 'Introduce the people clients can trust before they book.', 'webba-starter' ),
			'items'       => array(
				array(
					'title'       => __( 'Alex Morgan', 'webba-starter' ),
					'text'        => __( 'Lead specialist', 'webba-starter' ),
					'label'       => __( 'Senior', 'webba-starter' ),
					'socialLinks' => array(
						array(
							'platform' => 'linkedin',
							'url'      => 'https://linkedin.com/',
						),
						array(
							'platform' => 'instagram',
							'url'      => 'https://instagram.com/',
						),
					),
				),
				array(
					'title'       => __( 'Sam Rivera', 'webba-starter' ),
					'text'        => __( 'Client care and scheduling', 'webba-starter' ),
					'label'       => __( 'Support', 'webba-starter' ),
					'socialLinks' => array(
						array(
							'platform' => 'facebook',
							'url'      => 'https://facebook.com/',
						),
					),
				),
				array(
					'title'       => __( 'Taylor Chen', 'webba-starter' ),
					'text'        => __( 'Service expert', 'webba-starter' ),
					'label'       => __( 'Provider', 'webba-starter' ),
					'socialLinks' => array(
						array(
							'platform' => 'x',
							'url'      => 'https://x.com/',
						),
					),
				),
			),
		),
		'testimonials' => array(
			'eyebrow'     => __( 'Testimonials', 'webba-starter' ),
			'title'       => __( 'Clients appreciate the simple booking flow.', 'webba-starter' ),
			'description' => __( 'Build trust with short proof points close to the booking area.', 'webba-starter' ),
			'items'       => array(
				array(
					'title'    => __( 'Avery Brooks', 'webba-starter' ),
					'text'     => __( 'The booking process was easy from my phone.', 'webba-starter' ),
					'position' => __( 'Marketing Director', 'webba-starter' ),
					'rating'   => 5,
				),
				array(
					'title'    => __( 'Morgan Lee', 'webba-starter' ),
					'text'     => __( 'A polished experience from start to finish.', 'webba-starter' ),
					'position' => __( 'Operations Lead', 'webba-starter' ),
					'rating'   => 5,
				),
				array(
					'title'    => __( 'Jordan Smith', 'webba-starter' ),
					'text'     => __( 'The reminders made the appointment simple.', 'webba-starter' ),
					'position' => __( 'Client Success Manager', 'webba-starter' ),
					'rating'   => 5,
				),
			),
		),
	);

	return $defaults[ $type ] ?? array();
}

/**
 * Default content by block type.
 *
 * @param string $type Block type.
 * @return array
 */
function webba_default_block_content( $type ) {
	$card_defaults = webba_default_card_block_content( $type );

	if ( ! empty( $card_defaults ) ) {
		return $card_defaults;
	}

	$defaults = array(
		'hero'        => array(
			'eyebrow'      => __( 'Simple online booking', 'webba-starter' ),
			'title'        => __( 'Launch a professional booking website faster.', 'webba-starter' ),
			'description'  => __( 'Built for service businesses using Webba Booking, Gutenberg, and optional Elementor layouts.', 'webba-starter' ),
			'buttonText'   => __( 'Book an Appointment', 'webba-starter' ),
			'buttonUrl'    => '#booking',
			'buttonTextColor' => '',
			'buttonBackgroundColor' => '',
			'sectionStyle' => 'primary-soft',
			'features'     => array(
				array( 'text' => __( 'Real-time service booking', 'webba-starter' ) ),
				array( 'text' => __( 'Deposits and approvals', 'webba-starter' ) ),
				array( 'text' => __( 'Reminder-ready workflows', 'webba-starter' ) ),
			),
		),
		'booking'     => array(
			'eyebrow'      => __( 'Booking', 'webba-starter' ),
			'title'        => __( 'Book your appointment', 'webba-starter' ),
			'description'  => __( 'Choose a service and time below. Users may replace this with the Webba Gutenberg block, Webba shortcode, or Elementor Webba widget.', 'webba-starter' ),
			'shortcode'    => '[webbabooking]',
			'sectionStyle' => 'light',
		),
		'faq'         => array(
			'eyebrow'     => __( 'FAQ', 'webba-starter' ),
			'title'       => __( 'Frequently asked questions', 'webba-starter' ),
			'description' => __( 'Answer common booking concerns before clients choose a time.', 'webba-starter' ),
			'items'       => array(
				array(
					'title' => __( 'Can I reschedule?', 'webba-starter' ),
					'text'  => __( 'Rescheduling rules are managed in Webba Booking.', 'webba-starter' ),
				),
				array(
					'title' => __( 'Do I need to pay online?', 'webba-starter' ),
					'text'  => __( 'Payment methods and deposits are configured inside Webba Booking.', 'webba-starter' ),
				),
				array(
					'title' => __( 'Will I receive reminders?', 'webba-starter' ),
					'text'  => __( 'Reminder emails are configured in Webba Booking.', 'webba-starter' ),
				),
			),
		),
		'contact-cta' => array(
			'title'        => __( 'Ready to schedule your visit?', 'webba-starter' ),
			'description'  => __( 'Use Webba Booking to manage services, availability, deposits, reminders, and payment methods.', 'webba-starter' ),
			'buttonText'   => __( 'Book Now', 'webba-starter' ),
			'buttonUrl'    => '#booking',
			'sectionStyle' => 'dark',
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
	$defaults    = webba_default_block_content( 'hero' );
	$title       = $attributes['title'] ?? $defaults['title'];
	$eyebrow     = $attributes['eyebrow'] ?? $defaults['eyebrow'];
	$description = $attributes['description'] ?? $defaults['description'];
	$button_text = $attributes['buttonText'] ?? $defaults['buttonText'];
	$button_url  = $attributes['buttonUrl'] ?? $defaults['buttonUrl'];
	$button_text_color = webba_sanitize_color_value( $attributes['buttonTextColor'] ?? $defaults['buttonTextColor'] );
	$button_background = webba_sanitize_color_value( $attributes['buttonBackgroundColor'] ?? $defaults['buttonBackgroundColor'] );
	$media_url   = $attributes['mediaUrl'] ?? '';
	$layout      = $attributes['layout'] ?? 'media-right';
	$content_pos = $attributes['contentPosition'] ?? 'left';
	$vertical    = $attributes['verticalAlign'] ?? 'center';
	$image_pos   = $attributes['imagePosition'] ?? 'center';
	$features    = webba_sanitize_items( $attributes['features'] ?? $defaults['features'] );
	$button_style = '';

	if ( $button_text_color ) {
		$button_style .= 'color:' . $button_text_color . ';';
	}

	if ( $button_background ) {
		$button_style .= 'background-color:' . $button_background . ';';
	}

	ob_start();
	?>
	<section <?php echo webba_block_wrapper( $attributes, 'webba-block webba-hero-block webba-section webba-hero-block--' . sanitize_html_class( $layout ) . ' webba-hero-block--content-' . sanitize_html_class( $content_pos ) . ' webba-hero-block--vertical-' . sanitize_html_class( $vertical ) . ' webba-hero-block--image-' . sanitize_html_class( $image_pos ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="webba-container webba-hero-block__grid">
			<div class="webba-hero-block__content">
				<?php if ( $eyebrow ) : ?><p class="webba-eyebrow"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
				<h1><?php echo esc_html( $title ); ?></h1>
				<?php if ( $description ) : ?><p class="webba-hero-block__description"><?php echo esc_html( $description ); ?></p><?php endif; ?>
				<?php if ( $button_text ) : ?><a class="webba-button" style="<?php echo esc_attr( $button_style ); ?>" href="<?php echo esc_url( $button_url ); ?>"><?php echo esc_html( $button_text ); ?></a><?php endif; ?>
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
					<?php
					$label_text_color       = webba_sanitize_color_value( $item['labelTextColor'] ?? '' );
					$label_background_color = webba_sanitize_color_value( $item['labelBackgroundColor'] ?? '' );
					$button_text_color      = webba_sanitize_color_value( $item['buttonTextColor'] ?? '' );
					$button_background      = webba_sanitize_color_value( $item['buttonBackgroundColor'] ?? '' );
					$label_style            = '';
					$button_style           = '';

					if ( $label_text_color ) {
						$label_style .= 'color:' . $label_text_color . ';';
					}

					if ( $label_background_color ) {
						$label_style .= 'background-color:' . $label_background_color . ';';
					}

					if ( $button_text_color ) {
						$button_style .= 'color:' . $button_text_color . ';';
					}

					if ( $button_background ) {
						$button_style .= 'background-color:' . $button_background . ';';
					}
					?>
					<article class="webba-block-card">
						<?php if ( 'testimonials' === $type ) : ?>
							<div class="webba-testimonial-stars" aria-label="<?php echo esc_attr( sprintf( __( '%d out of 5 stars', 'webba-starter' ), absint( $item['rating'] ?? 5 ) ) ); ?>">
								<?php echo esc_html( str_repeat( '★', max( 1, min( 5, absint( $item['rating'] ?? 5 ) ) ) ) ); ?>
							</div>
							<?php if ( ! empty( $item['text'] ) ) : ?><blockquote class="webba-testimonial-quote"><?php echo esc_html( $item['text'] ); ?></blockquote><?php endif; ?>
							<?php if ( ! empty( $item['title'] ) ) : ?><p class="webba-testimonial-author"><?php echo esc_html( $item['title'] ); ?></p><?php endif; ?>
							<?php if ( ! empty( $item['position'] ) ) : ?><p class="webba-testimonial-position"><?php echo esc_html( $item['position'] ); ?></p><?php endif; ?>
						<?php else : ?>
							<?php if ( ! empty( $item['imageUrl'] ) ) : ?><img class="webba-block-card__image" src="<?php echo esc_url( $item['imageUrl'] ); ?>" alt=""><?php endif; ?>
							<?php if ( ! empty( $item['label'] ) ) : ?><p class="webba-card-label" style="<?php echo esc_attr( $label_style ); ?>"><?php echo esc_html( $item['label'] ); ?></p><?php endif; ?>
							<?php if ( ! empty( $item['title'] ) ) : ?><h3><?php echo esc_html( $item['title'] ); ?></h3><?php endif; ?>
							<?php if ( ! empty( $item['price'] ) ) : ?><p class="webba-card-price"><?php echo esc_html( $item['price'] ); ?></p><?php endif; ?>
							<?php if ( ! empty( $item['text'] ) ) : ?><p><?php echo esc_html( $item['text'] ); ?></p><?php endif; ?>
							<?php if ( 'staff' === $type ) : ?>
								<?php $social_links = webba_sanitize_social_links( $item['socialLinks'] ?? array() ); ?>
								<?php if ( ! empty( $social_links ) ) : ?>
									<div class="webba-social-links">
										<?php foreach ( $social_links as $social_link ) : ?>
											<?php $icon = webba_get_social_icon_svg( $social_link['platform'] ); ?>
											<?php if ( $icon ) : ?>
												<a class="webba-social-link" href="<?php echo esc_url( $social_link['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( ucfirst( $social_link['platform'] ) ); ?>">
													<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
												</a>
											<?php endif; ?>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							<?php endif; ?>
							<?php if ( in_array( $type, array( 'services', 'pricing' ), true ) && ! empty( $item['buttonText'] ) ) : ?>
								<a class="webba-card-button" style="<?php echo esc_attr( $button_style ); ?>" href="<?php echo esc_url( $item['buttonUrl'] ?? '#' ); ?>"><?php echo esc_html( $item['buttonText'] ); ?></a>
							<?php endif; ?>
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
	$defaults    = webba_default_block_content( 'booking' );
	$title       = $attributes['title'] ?? $defaults['title'];
	$eyebrow     = $attributes['eyebrow'] ?? $defaults['eyebrow'];
	$description = $attributes['description'] ?? $defaults['description'];
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
	$defaults    = webba_default_block_content( 'faq' );
	$title       = $attributes['title'] ?? $defaults['title'];
	$eyebrow     = $attributes['eyebrow'] ?? $defaults['eyebrow'];
	$description = $attributes['description'] ?? $defaults['description'];
	$items       = webba_sanitize_items( $attributes['items'] ?? $defaults['items'] );

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
	$defaults    = webba_default_block_content( 'contact-cta' );
	$title       = $attributes['title'] ?? $defaults['title'];
	$description = $attributes['description'] ?? $defaults['description'];
	$button_text = $attributes['buttonText'] ?? $defaults['buttonText'];
	$button_url  = $attributes['buttonUrl'] ?? $defaults['buttonUrl'];

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

	$heading_attrs = static function( $defaults = array() ) use ( $shared ) {
		return array_merge(
			$shared,
			array(
				'sectionStyle' => array(
					'type'    => 'string',
					'default' => $defaults['sectionStyle'] ?? 'default',
				),
				'title'       => array(
					'type'    => 'string',
					'default' => $defaults['title'] ?? '',
				),
				'eyebrow'     => array(
					'type'    => 'string',
					'default' => $defaults['eyebrow'] ?? '',
				),
				'description' => array(
					'type'    => 'string',
					'default' => $defaults['description'] ?? '',
				),
				'columns'     => array(
					'type'    => 'number',
					'default' => $defaults['columns'] ?? 3,
				),
				'items'       => array(
					'type'    => 'array',
					'default' => $defaults['items'] ?? array(),
				),
			)
		);
	};

	$blocks = array(
		'webba/hero'         => array(
			'title'           => __( 'Webba Hero', 'webba-starter' ),
			'description'     => __( 'Advanced hero section for Webba booking pages.', 'webba-starter' ),
			'icon'            => 'cover-image',
			'attributes'      => array_merge(
				$shared,
				array(
					'sectionStyle' => array(
						'type'    => 'string',
						'default' => webba_default_block_content( 'hero' )['sectionStyle'],
					),
					'title'       => array(
						'type'    => 'string',
						'default' => webba_default_block_content( 'hero' )['title'],
					),
					'eyebrow'     => array(
						'type'    => 'string',
						'default' => webba_default_block_content( 'hero' )['eyebrow'],
					),
					'description' => array(
						'type'    => 'string',
						'default' => webba_default_block_content( 'hero' )['description'],
					),
					'buttonText'  => array(
						'type'    => 'string',
						'default' => webba_default_block_content( 'hero' )['buttonText'],
					),
					'buttonUrl'   => array(
						'type'    => 'string',
						'default' => webba_default_block_content( 'hero' )['buttonUrl'],
					),
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
						'default' => webba_default_block_content( 'hero' )['features'],
					),
				)
			),
			'render_callback' => 'webba_render_hero_block',
		),
		'webba/services'     => array(
			'title'           => __( 'Webba Services', 'webba-starter' ),
			'description'     => __( 'Service cards with images, labels, and durations.', 'webba-starter' ),
			'icon'            => 'grid-view',
			'attributes'      => $heading_attrs( webba_default_block_content( 'services' ) ),
			'render_callback' => function( $attributes ) {
				return webba_render_cards_block( $attributes, 'services' );
			},
		),
		'webba/pricing'      => array(
			'title'           => __( 'Webba Pricing', 'webba-starter' ),
			'description'     => __( 'Pricing and package cards.', 'webba-starter' ),
			'icon'            => 'money-alt',
			'attributes'      => $heading_attrs( webba_default_block_content( 'pricing' ) ),
			'render_callback' => function( $attributes ) {
				return webba_render_cards_block( $attributes, 'pricing' );
			},
		),
		'webba/staff'        => array(
			'title'           => __( 'Webba Staff', 'webba-starter' ),
			'description'     => __( 'Staff or provider cards.', 'webba-starter' ),
			'icon'            => 'groups',
			'attributes'      => $heading_attrs( webba_default_block_content( 'staff' ) ),
			'render_callback' => function( $attributes ) {
				return webba_render_cards_block( $attributes, 'staff' );
			},
		),
		'webba/testimonials' => array(
			'title'           => __( 'Webba Testimonials', 'webba-starter' ),
			'description'     => __( 'Client testimonial cards.', 'webba-starter' ),
			'icon'            => 'format-quote',
			'attributes'      => $heading_attrs( webba_default_block_content( 'testimonials' ) ),
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
					'sectionStyle' => array(
						'type'    => 'string',
						'default' => webba_default_block_content( 'booking' )['sectionStyle'],
					),
					'title'       => array(
						'type'    => 'string',
						'default' => webba_default_block_content( 'booking' )['title'],
					),
					'eyebrow'     => array(
						'type'    => 'string',
						'default' => webba_default_block_content( 'booking' )['eyebrow'],
					),
					'description' => array(
						'type'    => 'string',
						'default' => webba_default_block_content( 'booking' )['description'],
					),
					'shortcode'   => array(
						'type'    => 'string',
						'default' => webba_default_block_content( 'booking' )['shortcode'],
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
			'attributes'      => $heading_attrs( webba_default_block_content( 'faq' ) ),
			'render_callback' => 'webba_render_faq_block',
		),
		'webba/contact-cta'  => array(
			'title'           => __( 'Webba Contact CTA', 'webba-starter' ),
			'description'     => __( 'Conversion call to action section.', 'webba-starter' ),
			'icon'            => 'megaphone',
			'attributes'      => array_merge(
				$shared,
				array(
					'sectionStyle' => array(
						'type'    => 'string',
						'default' => webba_default_block_content( 'contact-cta' )['sectionStyle'],
					),
					'title'       => array(
						'type'    => 'string',
						'default' => webba_default_block_content( 'contact-cta' )['title'],
					),
					'description' => array(
						'type'    => 'string',
						'default' => webba_default_block_content( 'contact-cta' )['description'],
					),
					'buttonText'  => array(
						'type'    => 'string',
						'default' => webba_default_block_content( 'contact-cta' )['buttonText'],
					),
					'buttonUrl'   => array(
						'type'    => 'string',
						'default' => webba_default_block_content( 'contact-cta' )['buttonUrl'],
					),
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
