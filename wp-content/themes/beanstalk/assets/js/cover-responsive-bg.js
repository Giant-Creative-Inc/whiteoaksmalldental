/**
 * Adds a "Responsive Background" panel to the core/cover block sidebar with
 * Desktop / Tablet / Mobile image pickers (art direction).
 *
 * Desktop drives Cover's own `url`/`id` attributes (identical to the toolbar
 * "Add media" flow). Tablet/Mobile are stored as extra attributes and applied
 * on the front end by beanstalk_cover_responsive_background() in functions.php,
 * which wraps Cover's <img> in a <picture>. Plain wp.* globals — no build step.
 */
( function ( wp ) {
	'use strict';

	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var addFilter = wp.hooks.addFilter;
	var createHigherOrderComponent = wp.compose.createHigherOrderComponent;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var MediaUpload = wp.blockEditor.MediaUpload;
	var MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
	var PanelBody = wp.components.PanelBody;
	var Button = wp.components.Button;
	var __ = wp.i18n.__;

	// 1. Register the extra attributes on core/cover.
	addFilter(
		'blocks.registerBlockType',
		'beanstalk/cover-responsive-bg-attributes',
		function ( settings, name ) {
			if ( 'core/cover' !== name ) {
				return settings;
			}
			settings.attributes = Object.assign( {}, settings.attributes, {
				tabletImage: { type: 'object' },
				mobileImage: { type: 'object' },
			} );
			return settings;
		}
	);

	// One labelled media picker row (thumbnail + Select/Replace + Remove).
	function mediaRow( label, help, value, onSelect, onRemove ) {
		return el(
			'div',
			{ style: { marginBottom: '20px' } },
			el( 'p', { style: { margin: '0 0 2px', fontWeight: 600 } }, label ),
			help
				? el(
						'p',
						{ className: 'components-base-control__help', style: { margin: '0 0 8px' } },
						help
				  )
				: null,
			value && value.url
				? el( 'img', {
						src: value.url,
						alt: '',
						style: {
							display: 'block',
							width: '100%',
							height: 'auto',
							marginBottom: '8px',
							borderRadius: '2px',
						},
				  } )
				: null,
			el(
				MediaUploadCheck,
				null,
				el( MediaUpload, {
					onSelect: onSelect,
					allowedTypes: [ 'image' ],
					value: value ? value.id : undefined,
					render: function ( open ) {
						return el(
							Button,
							{ variant: 'secondary', onClick: open.open },
							value && value.url
								? __( 'Replace', 'beanstalk' )
								: __( 'Select image', 'beanstalk' )
						);
					},
				} )
			),
			value && value.url
				? el(
						Button,
						{
							variant: 'link',
							isDestructive: true,
							onClick: onRemove,
							style: { marginLeft: '8px' },
						},
						__( 'Remove', 'beanstalk' )
				  )
				: null
		);
	}

	// 2. Inject the sidebar panel for core/cover.
	var withResponsiveBackgroundPanel = createHigherOrderComponent( function ( BlockEdit ) {
		return function ( props ) {
			if ( 'core/cover' !== props.name ) {
				return el( BlockEdit, props );
			}

			var a = props.attributes;
			var set = props.setAttributes;

			return el(
				Fragment,
				null,
				el( BlockEdit, props ),
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{
							title: __( 'Responsive Background', 'beanstalk' ),
							initialOpen: false,
						},
						mediaRow(
							__( 'Desktop image', 'beanstalk' ),
							__( 'The main Cover background, shown above 1024px.', 'beanstalk' ),
							a.url ? { id: a.id, url: a.url } : undefined,
							function ( m ) {
								set( {
									url: m.url,
									id: m.id,
									backgroundType: 'image',
									dimRatio: 100 === a.dimRatio ? 50 : a.dimRatio,
								} );
							},
							function () {
								set( { url: undefined, id: undefined } );
							}
						),
						mediaRow(
							__( 'Tablet image', 'beanstalk' ),
							__( 'Optional. Used at 1024px and below.', 'beanstalk' ),
							a.tabletImage,
							function ( m ) {
								set( { tabletImage: { id: m.id, url: m.url } } );
							},
							function () {
								set( { tabletImage: undefined } );
							}
						),
						mediaRow(
							__( 'Mobile image', 'beanstalk' ),
							__( 'Optional. Used at 767px and below.', 'beanstalk' ),
							a.mobileImage,
							function ( m ) {
								set( { mobileImage: { id: m.id, url: m.url } } );
							},
							function () {
								set( { mobileImage: undefined } );
							}
						)
					)
				)
			);
		};
	}, 'withResponsiveBackgroundPanel' );

	addFilter(
		'editor.BlockEdit',
		'beanstalk/cover-responsive-bg-panel',
		withResponsiveBackgroundPanel
	);
} )( window.wp );
