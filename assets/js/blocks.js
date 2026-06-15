(function (blocks, element, blockEditor, components, i18n, serverSideRender) {
	'use strict';

	var el = element.createElement;
	var __ = i18n.__;
	var registerBlockType = blocks.registerBlockType;
	var ServerSideRender = serverSideRender;
	var InspectorControls = blockEditor.InspectorControls;
	var MediaUpload = blockEditor.MediaUpload;
	var MediaUploadCheck = blockEditor.MediaUploadCheck;
	var useBlockProps = blockEditor.useBlockProps;
	var PanelBody = components.PanelBody;
	var TextControl = components.TextControl;
	var TextareaControl = components.TextareaControl;
	var SelectControl = components.SelectControl;
	var RangeControl = components.RangeControl;
	var ColorPalette = components.ColorPalette;
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

	var blockDefaults = {
		hero: {
			eyebrow: 'Simple online booking',
			title: 'Launch a professional booking website faster.',
			description: 'Built for service businesses using Webba Booking, Gutenberg, and optional Elementor layouts.',
			buttonText: 'Book an Appointment',
			buttonUrl: '#booking',
			buttonTextColor: '',
			buttonBackgroundColor: '',
			sectionStyle: 'primary-soft',
			features: [{ text: 'Real-time service booking' }, { text: 'Deposits and approvals' }, { text: 'Reminder-ready workflows' }]
		},
		booking: {
			eyebrow: 'Booking',
			title: 'Book your appointment',
			description: 'Choose a service and time below. Users may replace this with the Webba Gutenberg block, Webba shortcode, or Elementor Webba widget.',
			shortcode: '[webbabooking]',
			sectionStyle: 'light'
		},
		services: {
			eyebrow: 'Services',
			title: 'Services designed for easy scheduling.',
			description: 'Show your appointment types with duration, value, and clear booking context.',
			items: [
				{ title: 'Consultations', text: 'Short sessions for first-time clients and follow-ups.', label: '30 min', buttonText: 'View Details', buttonUrl: '#' },
				{ title: 'Treatments', text: 'Longer appointments with clear duration and availability.', label: '60 min', buttonText: 'View Details', buttonUrl: '#' },
				{ title: 'Packages', text: 'Promote bundles, deposits, buffers, and repeat bookings.', label: 'Popular', buttonText: 'View Details', buttonUrl: '#' }
			]
		},
		pricing: {
			eyebrow: 'Pricing',
			title: 'Transparent service packages',
			description: 'Use pricing cards for deposits, service tiers, or promotional packages.',
			sectionStyle: 'light',
			items: [
				{ title: 'Starter', text: 'Single service appointments.', price: '$49', label: 'Basic', buttonText: 'Book Now', buttonUrl: '#booking' },
				{ title: 'Professional', text: 'Packages, deposits, and buffers.', price: '$89', label: 'Popular', buttonText: 'Book Now', buttonUrl: '#booking' },
				{ title: 'Premium', text: 'Advanced scheduling workflows.', price: '$129', label: 'Complete', buttonText: 'Book Now', buttonUrl: '#booking' }
			]
		},
		staff: {
			eyebrow: 'Team',
			title: 'Experienced professionals ready to help.',
			description: 'Introduce the people clients can trust before they book.',
			items: [
				{ title: 'Alex Morgan', text: 'Lead specialist', label: 'Senior', socialLinks: [{ platform: 'linkedin', url: 'https://linkedin.com/' }, { platform: 'instagram', url: 'https://instagram.com/' }] },
				{ title: 'Sam Rivera', text: 'Client care and scheduling', label: 'Support', socialLinks: [{ platform: 'facebook', url: 'https://facebook.com/' }] },
				{ title: 'Taylor Chen', text: 'Service expert', label: 'Provider', socialLinks: [{ platform: 'x', url: 'https://x.com/' }] }
			]
		},
		testimonials: {
			eyebrow: 'Testimonials',
			title: 'Clients appreciate the simple booking flow.',
			description: 'Build trust with short proof points close to the booking area.',
			items: [
				{ title: 'Avery Brooks', text: 'The booking process was easy from my phone.', position: 'Marketing Director', rating: 5 },
				{ title: 'Morgan Lee', text: 'A polished experience from start to finish.', position: 'Operations Lead', rating: 5 },
				{ title: 'Jordan Smith', text: 'The reminders made the appointment simple.', position: 'Client Success Manager', rating: 5 }
			]
		},
		faq: {
			eyebrow: 'FAQ',
			title: 'Frequently asked questions',
			description: 'Answer common booking concerns before clients choose a time.',
			items: [
				{ title: 'Can I reschedule?', text: 'Rescheduling rules are managed in Webba Booking.' },
				{ title: 'Do I need to pay online?', text: 'Payment methods and deposits are configured inside Webba Booking.' },
				{ title: 'Will I receive reminders?', text: 'Reminder emails are configured in Webba Booking.' }
			]
		},
		contactCta: {
			title: 'Ready to schedule your visit?',
			description: 'Use Webba Booking to manage services, availability, deposits, reminders, and payment methods.',
			buttonText: 'Book Now',
			buttonUrl: '#booking',
			sectionStyle: 'dark'
		}
	};

	function headingAttributesWithDefaults(defaults) {
		defaults = defaults || {};

		return Object.assign({}, sharedAttributes, {
			sectionStyle: { type: 'string', default: defaults.sectionStyle || 'default' },
			title: { type: 'string', default: defaults.title || '' },
			eyebrow: { type: 'string', default: defaults.eyebrow || '' },
			description: { type: 'string', default: defaults.description || '' },
			columns: { type: 'number', default: defaults.columns || 3 },
			items: { type: 'array', default: defaults.items || [] }
		});
	}

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

	function socialPlatformOptions() {
		return [
			{ label: __('Facebook', 'webba-starter'), value: 'facebook' },
			{ label: __('Instagram', 'webba-starter'), value: 'instagram' },
			{ label: __('LinkedIn', 'webba-starter'), value: 'linkedin' },
			{ label: __('X', 'webba-starter'), value: 'x' },
			{ label: __('YouTube', 'webba-starter'), value: 'youtube' },
			{ label: __('Website', 'webba-starter'), value: 'website' }
		];
	}

	function updateSocialLink(items, itemIndex, socialIndex, key, value) {
		return (items || []).map(function (item, currentItemIndex) {
			if (currentItemIndex !== itemIndex) {
				return item;
			}

			var nextItem = Object.assign({}, item);
			var nextLinks = (nextItem.socialLinks || []).slice();
			var nextLink = Object.assign({}, nextLinks[socialIndex] || {});
			nextLink[key] = value;
			nextLinks[socialIndex] = nextLink;
			nextItem.socialLinks = nextLinks;
			return nextItem;
		});
	}

	function addSocialLink(items, itemIndex) {
		return (items || []).map(function (item, currentItemIndex) {
			if (currentItemIndex !== itemIndex) {
				return item;
			}

			var nextItem = Object.assign({}, item);
			nextItem.socialLinks = (nextItem.socialLinks || []).concat([{ platform: 'linkedin', url: '' }]);
			return nextItem;
		});
	}

	function removeSocialLink(items, itemIndex, socialIndex) {
		return (items || []).map(function (item, currentItemIndex) {
			if (currentItemIndex !== itemIndex) {
				return item;
			}

			var nextItem = Object.assign({}, item);
			nextItem.socialLinks = (nextItem.socialLinks || []).filter(function (link, currentSocialIndex) {
				return currentSocialIndex !== socialIndex;
			});
			return nextItem;
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
		var showLabel = options.showLabel !== false;
		var showPrice = options.showPrice !== false;
		var showImage = options.showImage !== false;
		var showRating = options.showRating === true;
		var showButtonLink = options.showButtonLink === true;
		var showPosition = options.showPosition === true;
		var showSocialLinks = options.showSocialLinks === true;
		var showLabelColors = options.showLabelColors === true;
		var showButtonColors = options.showButtonColors === true;
		var labelText = options.labelText || __('Label / duration', 'webba-starter');
		var titleLabel = options.titleLabel || __('Title', 'webba-starter');
		var textLabel = options.textLabel || __('Text', 'webba-starter');
		var itemLabel = options.itemLabel || __('Card', 'webba-starter');
		var addItemLabel = options.addItemLabel || __('Add card', 'webba-starter');
		var removeItemLabel = options.removeItemLabel || __('Remove card', 'webba-starter');

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
					title: (item.title || itemLabel) + ' #' + (index + 1),
					initialOpen: index === 0,
					key: index
				},
					el(TextControl, {
						label: titleLabel,
						value: item.title || '',
						onChange: function (value) {
							setItems(updateItem(items, index, 'title', value));
						}
					}),
					el(TextareaControl, {
						label: textLabel,
						value: item.text || '',
						onChange: function (value) {
							setItems(updateItem(items, index, 'text', value));
						}
					}),
					showLabel ? el(TextControl, {
						label: labelText,
						value: item.label || '',
						onChange: function (value) {
							setItems(updateItem(items, index, 'label', value));
						}
					}) : null,
					showLabel && showLabelColors ? el(BaseControl, { label: __('Label text color', 'webba-starter') },
						el(ColorPalette, {
							value: item.labelTextColor || '',
							onChange: function (value) {
								setItems(updateItem(items, index, 'labelTextColor', value || ''));
							}
						})
					) : null,
					showLabel && showLabelColors ? el(BaseControl, { label: __('Label background color', 'webba-starter') },
						el(ColorPalette, {
							value: item.labelBackgroundColor || '',
							onChange: function (value) {
								setItems(updateItem(items, index, 'labelBackgroundColor', value || ''));
							}
						})
					) : null,
					showPrice ? el(TextControl, {
						label: __('Price', 'webba-starter'),
						value: item.price || '',
						onChange: function (value) {
							setItems(updateItem(items, index, 'price', value));
						}
					}) : null,
					showPosition ? el(TextControl, {
						label: __('Author position', 'webba-starter'),
						value: item.position || '',
						onChange: function (value) {
							setItems(updateItem(items, index, 'position', value));
						}
					}) : null,
					showButtonLink ? el(TextControl, {
						label: options.buttonTextLabel || __('Button text', 'webba-starter'),
						value: item.buttonText || '',
						onChange: function (value) {
							setItems(updateItem(items, index, 'buttonText', value));
						}
					}) : null,
					showButtonLink ? el(TextControl, {
						label: options.buttonLinkLabel || __('Button link', 'webba-starter'),
						value: item.buttonUrl || '',
						onChange: function (value) {
							setItems(updateItem(items, index, 'buttonUrl', value));
						}
					}) : null,
					showButtonLink && showButtonColors ? el(BaseControl, { label: __('Button text color', 'webba-starter') },
						el(ColorPalette, {
							value: item.buttonTextColor || '',
							onChange: function (value) {
								setItems(updateItem(items, index, 'buttonTextColor', value || ''));
							}
						})
					) : null,
					showButtonLink && showButtonColors ? el(BaseControl, { label: __('Button background color', 'webba-starter') },
						el(ColorPalette, {
							value: item.buttonBackgroundColor || '',
							onChange: function (value) {
								setItems(updateItem(items, index, 'buttonBackgroundColor', value || ''));
							}
						})
					) : null,
					showRating ? el(RangeControl, {
						label: __('Star rating', 'webba-starter'),
						value: item.rating || 5,
						min: 1,
						max: 5,
						onChange: function (value) {
							setItems(updateItem(items, index, 'rating', value));
						}
					}) : null,
					showImage ? el(BaseControl, { label: __('Image', 'webba-starter') },
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
					) : null,
					showSocialLinks ? el(PanelBody, {
						title: __('Social Profiles', 'webba-starter'),
						initialOpen: false
					},
						(item.socialLinks || []).map(function (socialLink, socialIndex) {
							return el('div', { className: 'webba-editor-social-link', key: socialIndex },
								el(SelectControl, {
									label: __('Platform', 'webba-starter'),
									value: socialLink.platform || 'linkedin',
									options: socialPlatformOptions(),
									onChange: function (value) {
										setItems(updateSocialLink(items, index, socialIndex, 'platform', value));
									}
								}),
								el(TextControl, {
									label: __('Profile URL', 'webba-starter'),
									value: socialLink.url || '',
									onChange: function (value) {
										setItems(updateSocialLink(items, index, socialIndex, 'url', value));
									}
								}),
								el(Button, {
									isDestructive: true,
									onClick: function () {
										setItems(removeSocialLink(items, index, socialIndex));
									}
								}, __('Remove profile', 'webba-starter'))
							);
						}),
						el(Button, {
							variant: 'secondary',
							onClick: function () {
								setItems(addSocialLink(items, index));
							}
						}, __('Add social profile', 'webba-starter'))
					) : null,
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
						}, removeItemLabel)
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
						imageUrl: '',
						buttonText: '',
						buttonUrl: '',
						buttonTextColor: '',
						buttonBackgroundColor: '',
						labelTextColor: '',
						labelBackgroundColor: '',
						position: '',
						socialLinks: []
					}]));
				}
			}, addItemLabel)
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
						},
						showPrice: type === 'pricing',
						showButtonLink: type === 'services' || type === 'pricing',
						showLabel: type !== 'testimonials',
						showLabelColors: type !== 'testimonials',
						showImage: type !== 'testimonials',
						showRating: type === 'testimonials',
						showPosition: type === 'testimonials',
						showSocialLinks: type === 'staff',
						showButtonColors: type === 'services' || type === 'pricing',
						title: type === 'services' ? __('Services', 'webba-starter') : (type === 'pricing' ? __('Pricing Plans', 'webba-starter') : (type === 'staff' ? __('Team Members', 'webba-starter') : __('Testimonials', 'webba-starter'))),
						itemLabel: type === 'services' ? __('Service', 'webba-starter') : (type === 'pricing' ? __('Plan', 'webba-starter') : (type === 'staff' ? __('Team Member', 'webba-starter') : __('Testimonial', 'webba-starter'))),
						addItemLabel: type === 'services' ? __('Add Service', 'webba-starter') : (type === 'pricing' ? __('Add Pricing Plan', 'webba-starter') : (type === 'staff' ? __('Add Team Member', 'webba-starter') : __('Add Testimonial', 'webba-starter'))),
						removeItemLabel: type === 'services' ? __('Remove Service', 'webba-starter') : (type === 'pricing' ? __('Remove Pricing Plan', 'webba-starter') : (type === 'staff' ? __('Remove Team Member', 'webba-starter') : __('Remove Testimonial', 'webba-starter'))),
						buttonTextLabel: type === 'services' ? __('View details text', 'webba-starter') : __('Book now text', 'webba-starter'),
						buttonLinkLabel: type === 'services' ? __('View details link', 'webba-starter') : __('Book now link', 'webba-starter'),
						titleLabel: type === 'testimonials' ? __('Author', 'webba-starter') : __('Title', 'webba-starter'),
						labelText: type === 'staff' ? __('Role / position', 'webba-starter') : __('Label / duration', 'webba-starter'),
						textLabel: type === 'staff' ? __('Bio', 'webba-starter') : (type === 'testimonials' ? __('Quote text', 'webba-starter') : __('Text', 'webba-starter'))
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
							if (type === 'testimonials') {
								return el('article', { className: 'webba-block-card', key: index },
									el('div', { className: 'webba-testimonial-stars' }, '★'.repeat(Math.max(1, Math.min(5, item.rating || 5)))),
									item.text ? el('blockquote', { className: 'webba-testimonial-quote' }, item.text) : null,
									item.title ? el('p', { className: 'webba-testimonial-author' }, item.title) : null,
									item.position ? el('p', { className: 'webba-testimonial-position' }, item.position) : null
								);
							}

							return el('article', { className: 'webba-block-card', key: index },
								item.imageUrl ? el('img', { className: 'webba-block-card__image', src: item.imageUrl, alt: '' }) : null,
								item.label ? el('p', {
									className: 'webba-card-label',
									style: {
										color: item.labelTextColor || undefined,
										backgroundColor: item.labelBackgroundColor || undefined
									}
								}, item.label) : null,
								item.title ? el('h3', null, item.title) : null,
								item.price ? el('p', { className: 'webba-card-price' }, item.price) : null,
								item.text ? el('p', null, item.text) : null,
								type === 'staff' && (item.socialLinks || []).some(function (link) { return !!link.url; }) ? el('div', { className: 'webba-social-links' },
									(item.socialLinks || []).filter(function (link) {
										return !!link.url;
									}).map(function (link, socialIndex) {
										return el('a', {
											className: 'webba-social-link',
											href: link.url,
											key: socialIndex
										}, (link.platform || 'w').slice(0, 1).toUpperCase());
									})
								) : null,
								(type === 'services' || type === 'pricing') && item.buttonText ? el('a', {
									className: 'webba-card-button',
									href: item.buttonUrl || '#',
									style: {
										color: item.buttonTextColor || undefined,
										backgroundColor: item.buttonBackgroundColor || undefined
									}
								}, item.buttonText) : null
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
			attributes: headingAttributesWithDefaults(defaults),
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
			sectionStyle: { type: 'string', default: blockDefaults.hero.sectionStyle },
			title: { type: 'string', default: blockDefaults.hero.title },
			eyebrow: { type: 'string', default: blockDefaults.hero.eyebrow },
			description: { type: 'string', default: blockDefaults.hero.description },
			buttonText: { type: 'string', default: blockDefaults.hero.buttonText },
			buttonUrl: { type: 'string', default: blockDefaults.hero.buttonUrl },
			buttonTextColor: { type: 'string', default: blockDefaults.hero.buttonTextColor },
			buttonBackgroundColor: { type: 'string', default: blockDefaults.hero.buttonBackgroundColor },
			mediaUrl: { type: 'string', default: '' },
			layout: { type: 'string', default: 'media-right' },
			contentPosition: { type: 'string', default: 'left' },
			verticalAlign: { type: 'string', default: 'center' },
			imagePosition: { type: 'string', default: 'center' },
			features: { type: 'array', default: blockDefaults.hero.features }
		}),
		supports: supports,
		edit: function (props) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;

			return el('section', wrapperProps(attributes, 'webba-block webba-hero-block webba-section webba-hero-block--' + (attributes.layout || 'media-right') + ' webba-hero-block--content-' + (attributes.contentPosition || 'left') + ' webba-hero-block--vertical-' + (attributes.verticalAlign || 'center') + ' webba-hero-block--image-' + (attributes.imagePosition || 'center')),
				el(InspectorControls, null,
					el(PanelBody, { title: __('Hero content', 'webba-starter'), initialOpen: true },
						el(TextControl, { label: __('Eyebrow', 'webba-starter'), value: attributes.eyebrow || '', onChange: function (value) { setAttributes({ eyebrow: value }); } }),
						el(TextControl, { label: __('Title', 'webba-starter'), value: attributes.title || '', onChange: function (value) { setAttributes({ title: value }); } }),
						el(TextareaControl, { label: __('Description', 'webba-starter'), value: attributes.description || '', onChange: function (value) { setAttributes({ description: value }); } }),
						el(TextControl, { label: __('Button text', 'webba-starter'), value: attributes.buttonText || '', onChange: function (value) { setAttributes({ buttonText: value }); } }),
						el(TextControl, { label: __('Button URL', 'webba-starter'), value: attributes.buttonUrl || '', onChange: function (value) { setAttributes({ buttonUrl: value }); } }),
						el(BaseControl, { label: __('Button text color', 'webba-starter') },
							el(ColorPalette, {
								value: attributes.buttonTextColor || '',
								onChange: function (value) { setAttributes({ buttonTextColor: value || '' }); }
							})
						),
						el(BaseControl, { label: __('Button background color', 'webba-starter') },
							el(ColorPalette, {
								value: attributes.buttonBackgroundColor || '',
								onChange: function (value) { setAttributes({ buttonBackgroundColor: value || '' }); }
							})
						),
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
						el(SelectControl, {
							label: __('Text position', 'webba-starter'),
							value: attributes.contentPosition || 'left',
							options: [
								{ label: __('Left', 'webba-starter'), value: 'left' },
								{ label: __('Center', 'webba-starter'), value: 'center' },
								{ label: __('Right', 'webba-starter'), value: 'right' }
							],
							onChange: function (value) { setAttributes({ contentPosition: value }); }
						}),
						el(SelectControl, {
							label: __('Vertical position', 'webba-starter'),
							value: attributes.verticalAlign || 'center',
							options: [
								{ label: __('Top', 'webba-starter'), value: 'top' },
								{ label: __('Middle', 'webba-starter'), value: 'center' },
								{ label: __('Bottom', 'webba-starter'), value: 'bottom' }
							],
							onChange: function (value) { setAttributes({ verticalAlign: value }); }
						}),
						el(SelectControl, {
							label: __('Image position', 'webba-starter'),
							value: attributes.imagePosition || 'center',
							options: [
								{ label: __('Left', 'webba-starter'), value: 'left' },
								{ label: __('Center', 'webba-starter'), value: 'center' },
								{ label: __('Right', 'webba-starter'), value: 'right' }
							],
							onChange: function (value) { setAttributes({ imagePosition: value }); }
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
						attributes.buttonText ? el('a', {
							className: 'webba-button',
							href: attributes.buttonUrl || '#',
							style: {
								color: attributes.buttonTextColor || undefined,
								backgroundColor: attributes.buttonBackgroundColor || undefined
							}
						}, attributes.buttonText) : null
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
			sectionStyle: { type: 'string', default: blockDefaults.booking.sectionStyle },
			title: { type: 'string', default: blockDefaults.booking.title },
			eyebrow: { type: 'string', default: blockDefaults.booking.eyebrow },
			description: { type: 'string', default: blockDefaults.booking.description },
			shortcode: { type: 'string', default: blockDefaults.booking.shortcode },
			previewInEditor: { type: 'boolean', default: true }
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
						el(components.ToggleControl, {
							label: __('Render form preview in editor', 'webba-starter'),
							checked: attributes.previewInEditor !== false,
							onChange: function (value) {
								setAttributes({ previewInEditor: value });
							}
						}),
						el('p', null, __('Users may replace this section with the Webba Gutenberg block, Webba shortcode, or Elementor Webba widget.', 'webba-starter'))
					),
					backgroundControls(attributes, setAttributes)
				),
				attributes.previewInEditor === false ? el('div', { className: 'webba-container' },
					el('div', { className: 'webba-booking-card' },
						attributes.eyebrow ? el('p', { className: 'webba-eyebrow' }, attributes.eyebrow) : null,
						attributes.title ? el('h2', null, attributes.title) : null,
						attributes.description ? el('p', null, attributes.description) : null,
						el('div', { className: 'webba-booking-embed' }, attributes.shortcode || '[webbabooking]')
					)
				) : el(ServerSideRender, {
					block: 'webba/booking',
					attributes: attributes
				})
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
		attributes: headingAttributesWithDefaults(blockDefaults.faq),
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
						itemLabel: __('Question', 'webba-starter'),
						addItemLabel: __('Add Question', 'webba-starter'),
						removeItemLabel: __('Remove Question', 'webba-starter'),
						showColumns: false,
						showLabel: false,
						showPrice: false,
						showImage: false,
						textLabel: __('Answer', 'webba-starter')
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
			sectionStyle: { type: 'string', default: blockDefaults.contactCta.sectionStyle },
			title: { type: 'string', default: blockDefaults.contactCta.title },
			description: { type: 'string', default: blockDefaults.contactCta.description },
			buttonText: { type: 'string', default: blockDefaults.contactCta.buttonText },
			buttonUrl: { type: 'string', default: blockDefaults.contactCta.buttonUrl }
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

	registerCardsBlock('webba/services', __('Webba Services', 'webba-starter'), 'grid-view', blockDefaults.services);
	registerCardsBlock('webba/pricing', __('Webba Pricing', 'webba-starter'), 'money-alt', blockDefaults.pricing);
	registerCardsBlock('webba/staff', __('Webba Staff', 'webba-starter'), 'groups', blockDefaults.staff);
	registerCardsBlock('webba/testimonials', __('Webba Testimonials', 'webba-starter'), 'format-quote', blockDefaults.testimonials);
}(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n, window.wp.serverSideRender));
