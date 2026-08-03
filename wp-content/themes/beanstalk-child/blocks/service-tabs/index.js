( function ( blocks, blockEditor, components, data, element, i18n ) {
	'use strict';

	const el = element.createElement;
	const { useEffect } = element;
	const { InnerBlocks, InspectorControls, useBlockProps } = blockEditor;
	const { PanelBody, RangeControl, SelectControl } = components;
	const { __ } = i18n;

	const template = [
		[ 'white-oaks/service-tab', {
			number: '01',
			mobileLabel: 'Cosmetic',
			desktopLabel: 'Cosmetic dental',
			heading: 'Planned around your face, not a shade guide.',
			treatments: [
				{ label: 'Zoom Whitening', url: '#' },
				{ label: 'Veneers', url: '#' },
				{ label: 'Invisalign', url: '#' },
			],
		} ],
		[ 'white-oaks/service-tab', {
			number: '02',
			mobileLabel: 'Restorative',
			desktopLabel: 'Restorative dental',
			heading: 'Thoughtful solutions that restore comfort and function.',
			treatments: [
				{ label: 'Crowns', url: '#' },
				{ label: 'Bridges', url: '#' },
				{ label: 'Root Canal Treatment', url: '#' },
				{ label: 'Root Canal Retreatment', url: '#' },
				{ label: 'Dentures', url: '#' },
				{ label: 'Partial Dentures', url: '#' },
			],
		} ],
		[ 'white-oaks/service-tab', {
			number: '03',
			mobileLabel: 'Implants',
			desktopLabel: 'Implant dentistry',
			heading: 'Advanced implant care planned for lasting stability.',
			treatments: [
				{ label: 'Implants', url: '#' },
				{ label: 'Bone Grafting', url: '#' },
				{ label: 'Sinus Lifting', url: '#' },
				{ label: 'Implant Dentures', url: '#' },
			],
		} ],
		[ 'white-oaks/service-tab', {
			number: '04',
			mobileLabel: 'Specialty',
			desktopLabel: 'Sedation, surgery & family',
			heading: 'Comfortable care for complex needs and every age.',
			treatments: [
				{ label: 'IV Sedation', url: '#' },
				{ label: 'Laughing Gas (Nitrous Oxide)', url: '#' },
				{ label: 'Pediatric Dentistry', url: '#' },
				{ label: 'Wisdom Tooth Extraction', url: '#' },
				{ label: 'Simple & Surgical Extractions', url: '#' },
			],
		} ],
	];

	function Edit( props ) {
		const { attributes, clientId, setAttributes } = props;
		const blockProps = useBlockProps( { className: 'service-tabs-editor' } );
		const children = data.useSelect(
			( select ) => select( 'core/block-editor' ).getBlocks( clientId ),
			[ clientId ]
		);

		useEffect( () => {
			if ( ! attributes.instanceId ) {
				setAttributes( { instanceId: 'service-tabs-' + clientId.replace( /[^a-z0-9-]/gi, '' ) } );
			}
		}, [ attributes.instanceId, clientId ] );

		function selectPanel( childId, index ) {
			setAttributes( { initialActive: index } );
			data.dispatch( 'core/block-editor' ).selectBlock( childId );
		}

		return el(
			'div',
			blockProps,
			el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{ title: __( 'Service tabs settings', 'beanstalk-child' ) },
					el( SelectControl, {
						label: __( 'Panel heading level', 'beanstalk-child' ),
						value: attributes.headingLevel,
						options: [ 2, 3, 4, 5, 6 ].map( ( level ) => ( {
							label: 'H' + level,
							value: level,
						} ) ),
						onChange: ( value ) => setAttributes( { headingLevel: Number( value ) } ),
					} ),
					el( RangeControl, {
						label: __( 'Initially active category', 'beanstalk-child' ),
						min: 1,
						max: Math.max( 1, children.length ),
						value: Math.min( children.length || 1, attributes.initialActive + 1 ),
						onChange: ( value ) => setAttributes( { initialActive: value - 1 } ),
					} )
				)
			),
			el(
				'div',
				{ className: 'service-tabs-editor__selector', role: 'tablist', 'aria-label': __( 'Choose a category to edit', 'beanstalk-child' ) },
				children.map( ( child, index ) =>
					el(
						'button',
						{
							key: child.clientId,
							type: 'button',
							className: index === attributes.initialActive ? 'is-active' : '',
							onClick: () => selectPanel( child.clientId, index ),
						},
						( child.attributes.number || String( index + 1 ).padStart( 2, '0' ) ) + ' — ' +
						( child.attributes.desktopLabel || __( 'Untitled category', 'beanstalk-child' ) )
					)
				)
			),
			! children.length && el(
				'p',
				{ className: 'service-tabs-editor__empty' },
				__( 'Add a Service Category block to begin.', 'beanstalk-child' )
			),
			el( InnerBlocks, {
				allowedBlocks: [ 'white-oaks/service-tab' ],
				template,
				templateLock: false,
				renderAppender: InnerBlocks.ButtonBlockAppender,
			} )
		);
	}

	blocks.registerBlockType( 'white-oaks/service-tabs', {
		edit: Edit,
		save: function () {
			return el( InnerBlocks.Content );
		},
	} );
} )( window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.data, window.wp.element, window.wp.i18n );
