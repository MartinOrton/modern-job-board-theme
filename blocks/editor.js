/**
 * Editor side of the Feature Grid (mjb/feature-grid) and Feature Illustration
 * (mjb/feature-art) blocks, and the icon picker for card lists.
 *
 * Both are rendered in PHP (inc/blocks.php), so the editor shows the real
 * output through ServerSideRender and only adds the sidebar controls.
 * Plain ES5 on the wp.* globals: no build step.
 */
( function ( wp ) {
	'use strict';

	if ( ! wp || ! wp.blocks || ! wp.element || ! wp.blockEditor || ! wp.components || ! wp.serverSideRender ) {
		return;
	}

	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var PanelBody = wp.components.PanelBody;
	var SelectControl = wp.components.SelectControl;
	var RangeControl = wp.components.RangeControl;
	var Disabled = wp.components.Disabled;
	var ServerSideRender = wp.serverSideRender;

	wp.blocks.registerBlockType( 'mjb/feature-grid', {
		apiVersion: 3,
		title: __( 'Feature Grid', 'modern-job-board-theme' ),
		description: __( 'Tiles for the features on the home page: icon, title, description and a Learn More link. Edit the cards on the home page to change them.', 'modern-job-board-theme' ),
		category: 'mjb',
		icon: 'screenoptions',
		keywords: [ 'features', 'cards', 'tiles' ],
		attributes: {
			show: { type: 'string', 'enum': [ 'all', 'related' ], 'default': 'all' },
			count: { type: 'integer', 'default': 3 },
			headingLevel: { type: 'integer', 'default': 2 }
		},
		supports: { html: false, align: false },

		edit: function ( props ) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;

			return el(
				wp.element.Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Features', 'modern-job-board-theme' ), initialOpen: true },
						el( SelectControl, {
							label: __( 'Show', 'modern-job-board-theme' ),
							value: attributes.show,
							options: [
								{ label: __( 'All features', 'modern-job-board-theme' ), value: 'all' },
								{ label: __( 'Related features (excludes this page)', 'modern-job-board-theme' ), value: 'related' }
							],
							onChange: function ( value ) {
								setAttributes( { show: value } );
							},
							__nextHasNoMarginBottom: true,
							__next40pxDefaultSize: true
						} ),
						'related' === attributes.show
							? el( RangeControl, {
								label: __( 'Number of features', 'modern-job-board-theme' ),
								value: attributes.count,
								min: 1,
								max: 6,
								onChange: function ( value ) {
									setAttributes( { count: value || 3 } );
								},
								__nextHasNoMarginBottom: true,
								__next40pxDefaultSize: true
							} )
							: null,
						el( SelectControl, {
							label: __( 'Tile title heading', 'modern-job-board-theme' ),
							value: String( attributes.headingLevel ),
							options: [
								{ label: 'H2', value: '2' },
								{ label: 'H3', value: '3' }
							],
							onChange: function ( value ) {
								setAttributes( { headingLevel: parseInt( value, 10 ) || 2 } );
							},
							__nextHasNoMarginBottom: true,
							__next40pxDefaultSize: true
						} )
					)
				),
				el(
					'div',
					useBlockProps(),
					el(
						Disabled,
						null,
						el( ServerSideRender, { block: 'mjb/feature-grid', attributes: attributes } )
					)
				)
			);
		},

		save: function () {
			return null;
		}
	} );

	var artOptions = ( window.mjbThemeBlocks && window.mjbThemeBlocks.arts ) || [ { value: 'auto', label: 'Automatic' } ];

	wp.blocks.registerBlockType( 'mjb/feature-art', {
		apiVersion: 3,
		title: __( 'Feature Illustration', 'modern-job-board-theme' ),
		description: __( 'An animated illustration in the site colours. "Automatic" picks one to suit the page.', 'modern-job-board-theme' ),
		category: 'mjb',
		icon: 'format-image',
		keywords: [ 'illustration', 'animation', 'hero' ],
		attributes: {
			art: { type: 'string', 'default': 'auto' }
		},
		supports: { html: false, align: false },

		edit: function ( props ) {
			return el(
				wp.element.Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Illustration', 'modern-job-board-theme' ), initialOpen: true },
						el( SelectControl, {
							label: __( 'Show', 'modern-job-board-theme' ),
							value: props.attributes.art,
							options: artOptions,
							onChange: function ( value ) {
								props.setAttributes( { art: value } );
							},
							__nextHasNoMarginBottom: true,
							__next40pxDefaultSize: true
						} )
					)
				),
				el(
					'div',
					useBlockProps(),
					el(
						Disabled,
						null,
						el( ServerSideRender, { block: 'mjb/feature-art', attributes: props.attributes } )
					)
				)
			);
		},

		save: function () {
			return null;
		}
	} );
	/*
	 * Icon picker for card lists.
	 *
	 * A List block with the class "mjb-fb-cards" shows its items as cards, and
	 * each List Item carries its icon as a class: mjb-i-{name}. This adds a
	 * "Card icon" panel to the sidebar of those list items, so the icon is
	 * chosen by clicking it. The masks are in css/list-icons.css.
	 */
	var iconNames = ( window.mjbThemeBlocks && window.mjbThemeBlocks.icons ) || [];

	if ( iconNames.length && wp.hooks && wp.compose && wp.data ) {
		var Button = wp.components.Button;
		var ICON_CLASS = /(^|\s)mjb-i-[a-z0-9-]+/g;

		var currentIcon = function ( className ) {
			var match = /(?:^|\s)mjb-i-([a-z0-9-]+)/.exec( className || '' );

			return match ? match[ 1 ] : '';
		};

		var withIconClass = function ( className, name ) {
			var rest = ( className || '' ).replace( ICON_CLASS, ' ' ).replace( /\s+/g, ' ' ).trim();
			var next = name ? ( rest + ' mjb-i-' + name ).trim() : rest;

			return next || undefined;
		};

		var label = function ( name ) {
			return name.replace( /-/g, ' ' );
		};

		var withCardIcon = wp.compose.createHigherOrderComponent( function ( BlockEdit ) {
			return function ( props ) {
				var isCard = wp.data.useSelect(
					function ( select ) {
						if ( 'core/list-item' !== props.name ) {
							return false;
						}

						var editor = select( 'core/block-editor' );
						var parent = editor.getBlockRootClientId( props.clientId );
						var attributes = parent ? editor.getBlockAttributes( parent ) : null;

						return !! ( attributes && /(^|\s)mjb-fb-cards(\s|$)/.test( attributes.className || '' ) );
					},
					[ props.name, props.clientId ]
				);

				if ( ! isCard ) {
					return el( BlockEdit, props );
				}

				var selected = currentIcon( props.attributes.className );

				return el(
					wp.element.Fragment,
					null,
					el( BlockEdit, props ),
					el(
						InspectorControls,
						null,
						el(
							PanelBody,
							{ title: __( 'Card icon', 'modern-job-board-theme' ), initialOpen: true },
							el(
								'div',
								{ className: 'mjb-icon-picker', role: 'group', 'aria-label': __( 'Card icon', 'modern-job-board-theme' ) },
								iconNames.map( function ( name ) {
									return el(
										Button,
										{
											key: name,
											className: 'mjb-icon-picker__button',
											label: label( name ),
											showTooltip: true,
											isPressed: name === selected,
											onClick: function () {
												props.setAttributes( { className: withIconClass( props.attributes.className, name === selected ? '' : name ) } );
											}
										},
										el( 'span', { className: 'mjb-icon-picker__icon mjb-i-' + name, 'aria-hidden': 'true' } )
									);
								} )
							),
							el(
								'p',
								{ className: 'mjb-icon-picker__help' },
								selected
									? __( 'Click the selected icon again to remove it.', 'modern-job-board-theme' )
									: __( 'Choose the icon that suits this point.', 'modern-job-board-theme' )
							)
						)
					)
				);
			};
		}, 'withCardIcon' );

		wp.hooks.addFilter( 'editor.BlockEdit', 'mjb/card-icon', withCardIcon );

		// The picker lives in the sidebar, outside the editor canvas.
		var pickerCss = '.mjb-icon-picker{display:grid;grid-template-columns:repeat(6,1fr);gap:4px;max-height:264px;overflow-y:auto;padding:2px}' +
			'.mjb-icon-picker .mjb-icon-picker__button{justify-content:center;width:100%;min-width:0;height:36px;padding:0;color:#1e1e1e;border-radius:6px}' +
			'.mjb-icon-picker .mjb-icon-picker__button.is-pressed,.mjb-icon-picker .mjb-icon-picker__button.is-pressed:hover{color:#fff;background:#0f766e}' +
			'.mjb-icon-picker__icon{display:block;width:20px;height:20px;background:currentColor;-webkit-mask:var(--mjb-li-icon) center/contain no-repeat;mask:var(--mjb-li-icon) center/contain no-repeat}' +
			'.mjb-icon-picker__help{margin:12px 0 0;font-size:12px;color:#757575}';
		var pickerStyle = document.createElement( 'style' );

		pickerStyle.appendChild( document.createTextNode( pickerCss ) );
		document.head.appendChild( pickerStyle );
	}
} )( window.wp );
