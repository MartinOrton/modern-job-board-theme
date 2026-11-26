/**
 * Editor side of the site blocks: Contact Form (mjb/contact-form) and the
 * knowledge base blocks (mjb/kb-search, mjb/kb-categories, mjb/kb-articles).
 *
 * All are rendered in PHP (inc/contact.php, inc/kb.php), so the editor shows
 * the real output through ServerSideRender and only adds sidebar controls.
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
	var TextControl = wp.components.TextControl;
	var TextareaControl = wp.components.TextareaControl;
	var SelectControl = wp.components.SelectControl;
	var RangeControl = wp.components.RangeControl;
	var Disabled = wp.components.Disabled;
	var ServerSideRender = wp.serverSideRender;
	var data = window.mjbSiteBlocks || {};

	/**
	 * Server-rendered preview with sidebar controls.
	 *
	 * @param {string}   name     Block name.
	 * @param {Function} controls Returns the sidebar controls for the props.
	 * @return {Function} Edit component.
	 */
	function serverEdit( name, controls ) {
		return function ( props ) {
			return el(
				wp.element.Fragment,
				null,
				controls ? el( InspectorControls, null, controls( props ) ) : null,
				el(
					'div',
					useBlockProps(),
					el( Disabled, null, el( ServerSideRender, { block: name, attributes: props.attributes } ) )
				)
			);
		};
	}

	function text( props, key, label, help ) {
		return el( TextControl, {
			label: label,
			help: help,
			value: props.attributes[ key ],
			onChange: function ( value ) {
				var next = {};
				next[ key ] = value;
				props.setAttributes( next );
			},
			__nextHasNoMarginBottom: true,
			__next40pxDefaultSize: true
		} );
	}

	function textarea( props, key, label, help ) {
		return el( TextareaControl, {
			label: label,
			help: help,
			value: props.attributes[ key ],
			onChange: function ( value ) {
				var next = {};
				next[ key ] = value;
				props.setAttributes( next );
			},
			__nextHasNoMarginBottom: true
		} );
	}

	var contact = data.contact || {};

	wp.blocks.registerBlockType( 'mjb/contact-form', {
		apiVersion: 3,
		title: __( 'Contact Form', 'modern-job-board-theme' ),
		description: __( 'Name, email, topic and message. Messages are emailed to the recipient and saved under Contact messages.', 'modern-job-board-theme' ),
		category: 'mjb',
		icon: 'email',
		keywords: [ 'contact', 'form', 'email', 'enquiry' ],
		attributes: {
			recipient: { type: 'string', 'default': '' },
			topics: { type: 'string', 'default': contact.topics || '' },
			submitLabel: { type: 'string', 'default': contact.submitLabel || 'Send message' },
			successMessage: { type: 'string', 'default': contact.successMessage || '' }
		},
		supports: { html: false, align: false, multiple: false },
		edit: serverEdit( 'mjb/contact-form', function ( props ) {
			return el(
				PanelBody,
				{ title: __( 'Form settings', 'modern-job-board-theme' ), initialOpen: true },
				text( props, 'recipient', __( 'Send messages to', 'modern-job-board-theme' ), __( 'Leave empty to use the site admin email', 'modern-job-board-theme' ) + ( contact.adminEmail ? ' (' + contact.adminEmail + ').' : '.' ) + ' ' + __( 'Not shown on the page.', 'modern-job-board-theme' ) ),
				textarea( props, 'topics', __( 'Topics', 'modern-job-board-theme' ), __( 'One per line. Shown in the Topic dropdown and the email subject.', 'modern-job-board-theme' ) ),
				text( props, 'submitLabel', __( 'Button text', 'modern-job-board-theme' ) ),
				textarea( props, 'successMessage', __( 'Message after sending', 'modern-job-board-theme' ) )
			);
		} ),
		save: function () {
			return null;
		}
	} );

	if ( ! data.kb ) {
		return;
	}

	wp.blocks.registerBlockType( 'mjb/kb-search', {
		apiVersion: 3,
		title: __( 'Knowledge Base Search', 'modern-job-board-theme' ),
		description: __( 'Search box that suggests knowledge base articles as you type.', 'modern-job-board-theme' ),
		category: 'mjb',
		icon: 'search',
		keywords: [ 'docs', 'help', 'knowledge base', 'search' ],
		attributes: {
			placeholder: { type: 'string', 'default': data.kb.placeholder || '' }
		},
		supports: { html: false, align: false },
		edit: serverEdit( 'mjb/kb-search', function ( props ) {
			return el(
				PanelBody,
				{ title: __( 'Search box', 'modern-job-board-theme' ), initialOpen: true },
				text( props, 'placeholder', __( 'Placeholder text', 'modern-job-board-theme' ) )
			);
		} ),
		save: function () {
			return null;
		}
	} );

	wp.blocks.registerBlockType( 'mjb/kb-categories', {
		apiVersion: 3,
		title: __( 'Knowledge Base Topics', 'modern-job-board-theme' ),
		description: __( 'A card for each knowledge base topic with its first articles. Edit topics under Knowledge Base → Topics.', 'modern-job-board-theme' ),
		category: 'mjb',
		icon: 'category',
		keywords: [ 'docs', 'help', 'knowledge base', 'categories', 'topics' ],
		attributes: {
			perTopic: { type: 'integer', 'default': 5 }
		},
		supports: { html: false, align: false },
		edit: serverEdit( 'mjb/kb-categories', function ( props ) {
			return el(
				PanelBody,
				{ title: __( 'Topics', 'modern-job-board-theme' ), initialOpen: true },
				el( RangeControl, {
					label: __( 'Articles listed per topic', 'modern-job-board-theme' ),
					value: props.attributes.perTopic,
					min: 3,
					max: 12,
					onChange: function ( value ) {
						props.setAttributes( { perTopic: value || 5 } );
					},
					__nextHasNoMarginBottom: true,
					__next40pxDefaultSize: true
				} )
			);
		} ),
		save: function () {
			return null;
		}
	} );

	var topicOptions = [ { value: '', label: __( 'All topics', 'modern-job-board-theme' ) } ].concat( data.kb.topics || [] );

	wp.blocks.registerBlockType( 'mjb/kb-articles', {
		apiVersion: 3,
		title: __( 'Knowledge Base Articles', 'modern-job-board-theme' ),
		description: __( 'A list of knowledge base articles: the newest, or those in one topic.', 'modern-job-board-theme' ),
		category: 'mjb',
		icon: 'media-document',
		keywords: [ 'docs', 'help', 'knowledge base', 'articles' ],
		attributes: {
			topic: { type: 'string', 'default': '' },
			count: { type: 'integer', 'default': 6 },
			orderBy: { type: 'string', 'default': 'menu_order' }
		},
		supports: { html: false, align: false },
		edit: serverEdit( 'mjb/kb-articles', function ( props ) {
			return el(
				PanelBody,
				{ title: __( 'Articles', 'modern-job-board-theme' ), initialOpen: true },
				el( SelectControl, {
					label: __( 'Topic', 'modern-job-board-theme' ),
					value: props.attributes.topic,
					options: topicOptions,
					onChange: function ( value ) {
						props.setAttributes( { topic: value } );
					},
					__nextHasNoMarginBottom: true,
					__next40pxDefaultSize: true
				} ),
				el( SelectControl, {
					label: __( 'Order', 'modern-job-board-theme' ),
					value: props.attributes.orderBy,
					options: [
						{ value: 'menu_order', label: __( 'Reading order', 'modern-job-board-theme' ) },
						{ value: 'modified', label: __( 'Recently updated', 'modern-job-board-theme' ) },
						{ value: 'title', label: __( 'Title (A to Z)', 'modern-job-board-theme' ) }
					],
					onChange: function ( value ) {
						props.setAttributes( { orderBy: value } );
					},
					__nextHasNoMarginBottom: true,
					__next40pxDefaultSize: true
				} ),
				el( RangeControl, {
					label: __( 'Number of articles', 'modern-job-board-theme' ),
					value: props.attributes.count,
					min: 1,
					max: 24,
					onChange: function ( value ) {
						props.setAttributes( { count: value || 6 } );
					},
					__nextHasNoMarginBottom: true,
					__next40pxDefaultSize: true
				} )
			);
		} ),
		save: function () {
			return null;
		}
	} );
} )( window.wp );
