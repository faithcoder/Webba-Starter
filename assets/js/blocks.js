(function (blocks, element, blockEditor, components, i18n) {
	'use strict';

	var el = element.createElement;
	var __ = i18n.__;
	var registerBlockType = blocks.registerBlockType;
	var InspectorControls = blockEditor.InspectorControls;
	var MediaUpload = blockEditor.MediaUpload;
	var MediaUploadCheck = blockEditor.MediaUploadCheck;
	var useBlockProps = blockEditor.useBlockProps;
	var PanelBody = components.PanelBody;
	var TextControl = components.TextControl;
	var TextareaControl = components.TextareaControl;
	var SelectControl = components.SelectControl;
	var RangeControl = components.RangeControl;
	var Button = components.Button;
	var BaseControl = components.BaseControl;

	var supports = {
		align: ['wide', 'full'],
		anchor: true,
		className: true,
		html: false,
		color: {
			text: true,
			background: true,
			link: true,
			gradients: true
		},
		typography: {
			fontSize: true,
			lineHeight: true,
			fontFamily: true,
			fontStyle: true,
			fontWeight: true,
			letterSpacing: true,
			textTransform: true,
			textDecoration: true
		},
		spacing: {
			margin: true,
			padding: true,
			blockGap: true
		},
		border: {
			color: true,
			radius: true,
			style: true,
			width: true
		},
		dimensions: {
			minHeight: true
		},
		shadow: true
	};

	var sharedAttributes = {
		align: { type: 'string' },
		backgroundImage: { type: 'string', default: '' },
		overlayOpacity: { type: 'number', default: 0 },
		sectionStyle: { type: 'string', default: 'default' }
	};

	var headingAttributes = Object.assign({}, sharedAttributes, {
		title: { type: 'string', default: '' },
		eyebrow: { type: 'string', default: '' },
		description: { type: 'string', default: '' },
		columns: { type: 'number', default: 3 },
		items: { type: 'array', default: [] }
	});

	function styleOptions() {
		return [
			{ label: __('Default', 'webba-starter'), value: 'default' },
			{ label: __('Light', 'webba-starter'), value: 'light' },
			{ label: __('Dark', 'webba-starter'), value: 'dark' },
			{ label: __('Soft Primary', 'webba-starter'), value: 'primary-soft' },
			{ label: __('Premium', 'webba-starter'), value: 'premium' }
		];
	}

	function parseItems(value) {
		return value.split('\n').filter(Boolean).map(function (line) {
			var parts = line.split('|');
			return {
				title: (parts[0] || '').trim(),
				text: (parts[1] || '').trim(),
				price: (parts[2] || '').trim(),
				label: (parts[3] || '').trim(),
				imageUrl: (parts[4] || '').trim()
			};
		});
	}

	function parseFeatures(value) {
		return value.split('\n').filter(Boolean).map(function (line) {
			return { text: line.trim() };
		});
	}

	function stringifyFeatures(features) {
		return (features || []).map(function (feature) {
			return feature.text || '';
		}).join('\n');
	}

	function backgroundControls(attributes, setAttributes) {
		return el(PanelBody, { title: __('Design and background', 'webba-starter'), initialOpen: false },
			el(SelectControl, {
				label: __('Section style', 'webba-starter'),
				value: attributes.sectionStyle || 'default',
				options: styleOptions(),
				onChange: function (value) {
					setAttributes({ sectionStyle: value });
				}
			}),
			el(RangeControl, {
				label: __('Background overlay opacity', 'webba-starter'),
				value: attributes.overlayOpacity || 0,
				min: 0,
				max: 0.85,
				step: 0.05,
				onChange: function (value) {
					setAttributes({ overlayOpacity: value });
				}
			}),
			el(MediaUploadCheck, null,
				el(MediaUpload, {
					onSelect: function (media) {
						setAttributes({ backgroundImage: media.url || '' });
					},
					allowedTypes: ['image'],
					render: function (obj) {
						return el(Button, { variant: 'secondary', onClick: obj.open }, attributes.backgroundImage ? __('Replace background image', 'webba-starter') : __('Choose background image', 'webba-starter'));
					}
				})
			),
			attributes.backgroundImage ? el(Button, { isDestructive: true, onClick: function () { setAttributes({ backgroundImage: '' }); } }, __('Remove background image', 'webba-starter')) : null
		);
	}

	function wrapperProps(attributes, className) {
		var style = {};
		if (attributes.backgroundImage) {
			style.backgroundImage = 'linear-gradient(rgba(15,23,42,' + (attributes.overlayOpacity || 0) + '), rgba(15,23,42,' + (attributes.overlayOpacity || 0) + ')), url(' + attributes.backgroundImage + ')';
		}
		return useBlockProps({
			className: className + ' webba-block--' + (attributes.sectionStyle || 'default'),
			style: style
		});
	}

	function headingControls(attributes, setAttributes) {
		return el(PanelBody, { title: __('Section content', 'webba-starter'), initialOpen: true },
			el(TextControl, { label: __('Eyebrow', 'webba-starter'), value: attributes.eyebrow || '', onChange: function (value) { setAttributes({ eyebrow: value }); } }),
			el(TextControl, { label: __('Title', 'webba-starter'), value: attributes.title || '', onChange: function (value) { setAttributes({ title: value }); } }),
			el(TextareaControl, { label: __('Description', 'webba-starter'), value: attributes.description || '', onChange: function (value) { setAttributes({ description: value }); } })
		);
	}

	function updateItem(items, index, key, value) {
		return (items || []).map(function (item, itemIndex) {
			if (itemIndex !== index) {
				return item;
			}

			var next = Object.assign({}, item);
			next[key] = value;
			return next;
		});
	}

	function moveItem(items, index, direction) {
		var next = (items || []).slice();
		var target = index + direction;

		if (target < 0 || target >= next.length) {
			return next;
		}

		var current = next[index];
		next[index] = next[target];
		next[target] = current;
		return next;
	}

	function cardControls(items, setItems, options) {
		options = options || {};

		return el(PanelBody, { title: options.title || __('Cards', 'webba-starter'), initialOpen: true },
			(options.showColumns === false) ? null : el(RangeControl, {
				label: __('Columns', 'webba-starter'),
				value: options.columns || 3,
				min: 1,
				max: 4,
				onChange: options.onColumnsChange
			}),
			(items || []).map(function (item, index) {
				return el(PanelBody, {
					title: (item.title || __('Card', 'webba-starter')) + ' #' + (index + 1),
					initialOpen: index === 0,
					key: index
				},
					el(TextControl, {
						label: __('Title', 'webba-starter'),
						value: item.title || '',
						onChange: function (value) {
							setItems(updateItem(items, index, 'title', value));
						}
					}),
					el(TextareaControl, {
						label: __('Text', 'webba-starter'),
						value: item.text || '',
						onChange: function (value) {
							setItems(updateItem(items, index, 'text', value));
						}
					}),
					el(TextControl, {
						label: __('Label / duration', 'webba-starter'),
						value: item.label || '',
						onChange: function (value) {
							setItems(updateItem(items, index, 'label', value));
						}
					}),
					el(TextControl, {
						label: __('Price', 'webba-starter'),
						value: item.price || '',
						onChange: function (value) {
							setItems(updateItem(items, index, 'price', value));
						}
					}),
					el(BaseControl, { label: __('Image', 'webba-starter') },
						item.imageUrl ? el('img', { className: 'webba-editor-card-image', src: item.imageUrl, alt: '' }) : null,
						el('div', { className: 'webba-editor-control-row' },
							el(MediaUploadCheck, null,
								el(MediaUpload, {
									onSelect: function (media) {
										setItems(updateItem(items, index, 'imageUrl', media.url || ''));
									},
									allowedTypes: ['image'],
									render: function (obj) {
										return el(Button, { variant: 'secondary', onClick: obj.open }, item.imageUrl ? __('Replace', 'webba-starter') : __('Choose', 'webba-starter'));
									}
								})
							),
							item.imageUrl ? el(Button, {
								isDestructive: true,
								onClick: function () {
									setItems(updateItem(items, index, 'imageUrl', ''));
								}
							}, __('Remove', 'webba-starter')) : null
						)
					),
					el('div', { className: 'webba-editor-control-row' },
						el(Button, {
							variant: 'secondary',
							disabled: index === 0,
							onClick: function () {
								setItems(moveItem(items, index, -1));
							}
						}, __('Move up', 'webba-starter')),
						el(Button, {
							variant: 'secondary',
							disabled: index === items.length - 1,
							onClick: function () {
								setItems(moveItem(items, index, 1));
							}
						}, __('Move down', 'webba-starter')),
						el(Button, {
							isDestructive: true,
							onClick: function () {
								setItems(items.filter(function (current, itemIndex) {
									return itemIndex !== index;
								}));
							}
						}, __('Remove card', 'webba-starter'))
					)
				);
			}),
			el(Button, {
				variant: 'primary',
				onClick: function () {
					setItems((items || []).concat([{
						title: __('New card', 'webba-starter'),
						text: '',
						label: '',
						price: '',
						imageUrl: ''
					}]));
				}
			}, __('Add card', 'webba-starter'))
		);
	}

	function cardsEdit(type, defaults) {
		return function (props) {
			var attributes = Object.assign({}, defaults, props.attributes);
			var setAttributes = props.setAttributes;
			var items = attributes.items || defaults.items || [];

			return el('div', wrapperProps(attributes, 'webba-block webba-section webba-' + type + '-block'),
				el(InspectorControls, null,
					headingControls(attributes, setAttributes),
					cardControls(items, function (nextItems) {
						setAttributes({ items: nextItems });
					}, {
						columns: attributes.columns || 3,
						onColumnsChange: function (value) {
							setAttributes({ columns: value });
						}
					}),
					backgroundControls(attributes, setAttributes)
				),
				el('div', { className: 'webba-container' },
					el('div', { className: 'webba-section-heading' },
						attributes.eyebrow ? el('p', { className: 'webba-eyebrow' }, attributes.eyebrow) : null,
						attributes.title ? el('h2', null, attributes.title) : null,
						attributes.description ? el('p', null, attributes.description) : null
					),
					el('div', { className: 'webba-block-grid webba-block-grid--' + (attributes.columns || 3) },
						items.map(function (item, index) {
							return el('article', { className: 'webba-block-card', key: index },
								item.imageUrl ? el('img', { className: 'webba-block-card__image', src: item.imageUrl, alt: '' }) : null,
								item.label ? el('p', { className: 'webba-card-label' }, item.label) : null,
								item.title ? el('h3', null, item.title) : null,
								item.price ? el('p', { className: 'webba-card-price' }, item.price) : null,
								item.text ? el('p', null, item.text) : null
							);
						})
					)
				)
			);
		};
	}

	function registerCardsBlock(name, title, icon, defaults) {
		registerBlockType(name, {
			title: title,
			icon: icon,
			category: 'webba',
			attributes: headingAttributes,
			supports: supports,
			edit: cardsEdit(name.replace('webba/', ''), defaults),
			save: function () {
				return null;
			}
		});
	}

	registerBlockType('webba/hero', {
		title: __('Webba Hero', 'webba-starter'),
		icon: 'cover-image',
		category: 'webba',
		attributes: Object.assign({}, sharedAttributes, {
			title: { type: 'string', default: 'Launch a polished booking website.' },
			eyebrow: { type: 'string', default: 'Webba Booking ready' },
			description: { type: 'string', default: 'Create a modern service business homepage with booking, service highlights, and a strong conversion path.' },
			buttonText: { type: 'string', default: 'Book Now' },
			buttonUrl: { type: 'string', default: '#booking' },
			mediaUrl: { type: 'string', default: '' },
			layout: { type: 'string', default: 'media-right' },
			features: { type: 'array', default: [{ text: 'Real-time service booking' }, { text: 'Deposits and approvals' }, { text: 'Reminder-ready workflows' }] }
		}),
		supports: supports,
		edit: function (props) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;

			return el('section', wrapperProps(attributes, 'webba-block webba-hero-block webba-section webba-hero-block--' + (attributes.layout || 'media-right')),
				el(InspectorControls, null,
					el(PanelBody, { title: __('Hero content', 'webba-starter'), initialOpen: true },
						el(TextControl, { label: __('Eyebrow', 'webba-starter'), value: attributes.eyebrow || '', onChange: function (value) { setAttributes({ eyebrow: value }); } }),
						el(TextControl, { label: __('Title', 'webba-starter'), value: attributes.title || '', onChange: function (value) { setAttributes({ title: value }); } }),
						el(TextareaControl, { label: __('Description', 'webba-starter'), value: attributes.description || '', onChange: function (value) { setAttributes({ description: value }); } }),
						el(TextControl, { label: __('Button text', 'webba-starter'), value: attributes.buttonText || '', onChange: function (value) { setAttributes({ buttonText: value }); } }),
						el(TextControl, { label: __('Button URL', 'webba-starter'), value: attributes.buttonUrl || '', onChange: function (value) { setAttributes({ buttonUrl: value }); } }),
						el(SelectControl, {
							label: __('Layout', 'webba-starter'),
							value: attributes.layout || 'media-right',
							options: [
								{ label: __('Media right', 'webba-starter'), value: 'media-right' },
								{ label: __('Media left', 'webba-starter'), value: 'media-left' },
								{ label: __('Centered', 'webba-starter'), value: 'centered' }
							],
							onChange: function (value) { setAttributes({ layout: value }); }
						}),
						el(TextareaControl, {
							label: __('Feature highlights', 'webba-starter'),
							help: __('One feature per line.', 'webba-starter'),
							value: stringifyFeatures(attributes.features || []),
							onChange: function (value) { setAttributes({ features: parseFeatures(value) }); }
						})
					),
					el(PanelBody, { title: __('Hero image', 'webba-starter'), initialOpen: false },
						el(MediaUploadCheck, null,
							el(MediaUpload, {
								onSelect: function (media) { setAttributes({ mediaUrl: media.url || '' }); },
								allowedTypes: ['image'],
								render: function (obj) {
									return el(Button, { variant: 'secondary', onClick: obj.open }, attributes.mediaUrl ? __('Replace image', 'webba-starter') : __('Choose image', 'webba-starter'));
								}
							})
						),
						attributes.mediaUrl ? el(Button, { isDestructive: true, onClick: function () { setAttributes({ mediaUrl: '' }); } }, __('Remove image', 'webba-starter')) : null
					),
					backgroundControls(attributes, setAttributes)
				),
				el('div', { className: 'webba-container webba-hero-block__grid' },
					el('div', { className: 'webba-hero-block__content' },
						attributes.eyebrow ? el('p', { className: 'webba-eyebrow' }, attributes.eyebrow) : null,
						el('h1', null, attributes.title),
						attributes.description ? el('p', { className: 'webba-hero-block__description' }, attributes.description) : null,
						attributes.buttonText ? el('a', { className: 'webba-button', href: attributes.buttonUrl || '#' }, attributes.buttonText) : null
					),
					el('div', { className: 'webba-hero-block__visual' },
						attributes.mediaUrl ? el('img', { src: attributes.mediaUrl, alt: '' }) : el('div', { className: 'webba-hero-block__panel' },
							el('strong', null, __('Booking highlights', 'webba-starter')),
							el('ul', null, (attributes.features || []).map(function (feature, index) {
								return el('li', { key: index }, feature.text);
							}))
						)
					)
				)
			);
		},
		save: function () {
			return null;
		}
	});

	registerBlockType('webba/booking', {
		title: __('Webba Booking', 'webba-starter'),
		icon: 'calendar-alt',
		category: 'webba',
		attributes: Object.assign({}, sharedAttributes, {
			title: { type: 'string', default: 'Book your appointment' },
			eyebrow: { type: 'string', default: 'Booking' },
			description: { type: 'string', default: 'Choose a service and time below.' },
			shortcode: { type: 'string', default: '[webbabooking]' }
		}),
		supports: supports,
		edit: function (props) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;

			return el('section', wrapperProps(attributes, 'webba-block webba-section webba-booking-block'),
				el(InspectorControls, null,
					headingControls(attributes, setAttributes),
					el(PanelBody, { title: __('Booking source', 'webba-starter'), initialOpen: true },
						el(TextControl, { label: __('Webba shortcode', 'webba-starter'), value: attributes.shortcode || '', onChange: function (value) { setAttributes({ shortcode: value }); } }),
						el('p', null, __('Users may replace this section with the Webba Gutenberg block, Webba shortcode, or Elementor Webba widget.', 'webba-starter'))
					),
					backgroundControls(attributes, setAttributes)
				),
				el('div', { className: 'webba-container' },
					el('div', { className: 'webba-booking-card' },
						attributes.eyebrow ? el('p', { className: 'webba-eyebrow' }, attributes.eyebrow) : null,
						attributes.title ? el('h2', null, attributes.title) : null,
						attributes.description ? el('p', null, attributes.description) : null,
						el('div', { className: 'webba-booking-embed' }, attributes.shortcode || '[webbabooking]')
					)
				)
			);
		},
		save: function () {
			return null;
		}
	});

	registerBlockType('webba/faq', {
		title: __('Webba FAQ', 'webba-starter'),
		icon: 'editor-help',
		category: 'webba',
		attributes: headingAttributes,
		supports: supports,
		edit: function (props) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;
			var items = attributes.items || [];

			return el('section', wrapperProps(attributes, 'webba-block webba-section webba-faq-block'),
				el(InspectorControls, null,
					headingControls(attributes, setAttributes),
					cardControls(items, function (nextItems) {
						setAttributes({ items: nextItems });
					}, {
						title: __('Questions', 'webba-starter'),
						showColumns: false
					}),
					backgroundControls(attributes, setAttributes)
				),
				el('div', { className: 'webba-container webba-narrow' },
					attributes.eyebrow ? el('p', { className: 'webba-eyebrow' }, attributes.eyebrow) : null,
					attributes.title ? el('h2', null, attributes.title) : null,
					attributes.description ? el('p', null, attributes.description) : null,
					items.map(function (item, index) {
						return el('details', { className: 'webba-faq', key: index, open: index === 0 },
							el('summary', null, item.title),
							el('p', null, item.text)
						);
					})
				)
			);
		},
		save: function () {
			return null;
		}
	});

	registerBlockType('webba/contact-cta', {
		title: __('Webba Contact CTA', 'webba-starter'),
		icon: 'megaphone',
		category: 'webba',
		attributes: Object.assign({}, sharedAttributes, {
			title: { type: 'string', default: 'Ready to schedule your visit?' },
			description: { type: 'string', default: 'Use Webba Booking to manage services, availability, deposits, reminders, and payment methods.' },
			buttonText: { type: 'string', default: 'Book Now' },
			buttonUrl: { type: 'string', default: '#booking' }
		}),
		supports: supports,
		edit: function (props) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;

			return el('section', wrapperProps(attributes, 'webba-block webba-section webba-cta-block'),
				el(InspectorControls, null,
					el(PanelBody, { title: __('CTA content', 'webba-starter'), initialOpen: true },
						el(TextControl, { label: __('Title', 'webba-starter'), value: attributes.title || '', onChange: function (value) { setAttributes({ title: value }); } }),
						el(TextareaControl, { label: __('Description', 'webba-starter'), value: attributes.description || '', onChange: function (value) { setAttributes({ description: value }); } }),
						el(TextControl, { label: __('Button text', 'webba-starter'), value: attributes.buttonText || '', onChange: function (value) { setAttributes({ buttonText: value }); } }),
						el(TextControl, { label: __('Button URL', 'webba-starter'), value: attributes.buttonUrl || '', onChange: function (value) { setAttributes({ buttonUrl: value }); } })
					),
					backgroundControls(attributes, setAttributes)
				),
				el('div', { className: 'webba-container webba-cta-block__inner' },
					el('div', null, el('h2', null, attributes.title), attributes.description ? el('p', null, attributes.description) : null),
					attributes.buttonText ? el('a', { className: 'webba-button', href: attributes.buttonUrl || '#' }, attributes.buttonText) : null
				)
			);
		},
		save: function () {
			return null;
		}
	});

	registerCardsBlock('webba/services', __('Webba Services', 'webba-starter'), 'grid-view', {
		eyebrow: 'Services',
		title: 'Services designed for easy scheduling.',
		description: 'Show durations, outcomes, and booking-friendly service details.',
		items: [
			{ title: 'Consultation', text: 'A focused first appointment.', label: '30 min' },
			{ title: 'Treatment', text: 'A longer session with a specialist.', label: '60 min' },
			{ title: 'Follow-up', text: 'Keep clients coming back.', label: '15 min' }
		]
	});
	registerCardsBlock('webba/pricing', __('Webba Pricing', 'webba-starter'), 'money-alt', {
		eyebrow: 'Pricing',
		title: 'Transparent service packages.',
		items: [
			{ title: 'Starter', text: 'Single service booking.', price: '$49' },
			{ title: 'Professional', text: 'Packages and deposits.', price: '$89' },
			{ title: 'Premium', text: 'Advanced scheduling.', price: '$129' }
		]
	});
	registerCardsBlock('webba/staff', __('Webba Staff', 'webba-starter'), 'groups', {
		eyebrow: 'Team',
		title: 'Meet the professionals.',
		items: [
			{ title: 'Alex Morgan', text: 'Lead specialist', label: 'Senior' },
			{ title: 'Sam Rivera', text: 'Client care', label: 'Support' },
			{ title: 'Taylor Chen', text: 'Service expert', label: 'Provider' }
		]
	});
	registerCardsBlock('webba/testimonials', __('Webba Testimonials', 'webba-starter'), 'format-quote', {
		eyebrow: 'Testimonials',
		title: 'Clients appreciate the simple booking flow.',
		items: [
			{ title: 'Clear and fast', text: 'The booking process was easy from my phone.' },
			{ title: 'Professional', text: 'A polished experience from start to finish.' },
			{ title: 'Helpful reminders', text: 'The reminders made the appointment simple.' }
		]
	});
}(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n));
