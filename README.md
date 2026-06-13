# Webba Starter

Webba Starter is a lightweight hybrid WordPress theme for businesses using the Webba Booking plugin. It uses PHP templates, Gutenberg patterns, `theme.json`, the Customizer, TGMPA integration, and One Click Demo Import support.

## Installation

1. Copy the `Webba-Starter` folder into `wp-content/themes/`.
2. Activate **Webba Starter** in Appearance > Themes.
3. Install the recommended plugins from the theme plugin notice.

## Theme Features

- Hybrid WordPress architecture, not Full Site Editing.
- Webba Booking shortcode support with `[webbabooking]`.
- Gutenberg pattern sections and complete demo homepage patterns.
- Optional Elementor Full Width and Elementor Canvas page templates.
- Customizer controls for header CTA, contact details, footer copyright, and default booking shortcode.
- Responsive, mobile-first CSS without Bootstrap, Tailwind, or jQuery.

## Demo Import

The theme registers five One Click Demo Import packs:

- Dental Clinic
- Hair Salon
- Massage & Spa
- Fitness Trainer
- Rental Units

The demo folders contain placeholder files. Build real Gutenberg demo pages, export them to WXR XML, then replace the placeholder files before release.

## TGMPA Plugin Installation

The theme registers:

- Webba Booking (`webba-booking-lite`) as required.
- One Click Demo Import (`one-click-demo-import`) as recommended.
- Elementor (`elementor`) as recommended.

Replace the development TGMPA placeholder in `inc/tgm-plugin-activation/class-tgm-plugin-activation.php` with the official TGMPA library before distribution.

## Webba Booking Setup

Configure services, duration, availability, pricing, deposits, approval rules, reminders, buffers, and payment methods inside Webba Booking. The theme does not write to Webba Booking settings or database tables.

## Gutenberg Patterns

Patterns are available under:

- Webba Demo Pages
- Webba Sections
- Webba Booking Sections

Use these to build exportable demo pages without requiring Elementor.

## Elementor Support

Elementor is optional. Use the included page templates:

- Elementor Full Width
- Elementor Canvas

Users can replace a booking shortcode section with the Elementor Webba widget when Elementor and Webba Booking support it.

## Shortcode Usage

Default shortcode:

```text
[webbabooking]
```

Change the default shortcode in Appearance > Customize > Webba Business Settings.

## Webba Gutenberg Block Usage

If Webba Booking provides a Gutenberg block, insert it into the booking section in place of the shortcode block.

## Recommended Plugins

- Webba Booking
- One Click Demo Import
- Elementor

## Future Demo Expansion

Add a new folder under `demo-content/`, create a matching homepage pattern, export Gutenberg content to `content.xml`, export widgets to `widgets.wie`, export Customizer settings to `customizer.dat`, and add a preview image.
