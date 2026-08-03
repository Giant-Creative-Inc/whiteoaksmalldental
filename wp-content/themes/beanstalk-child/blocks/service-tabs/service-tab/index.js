( function ( blocks, blockEditor, components, data, element, i18n ) {
	'use strict';

	const el = element.createElement;
	const { InspectorControls, RichText, useBlockProps } = blockEditor;
	const { Button, PanelBody, TextControl } = components;
	const { __ } = i18n;

	function Edit( props ) {
		const { attributes, clientId, isSelected, setAttributes } = props;
		const parentId = data.useSelect(
			( select ) => select( 'core/block-editor' ).getBlockRootClientId( clientId ),
			[ clientId ]
		);
		const parent = data.useSelect(
			( select ) => parentId ? select( 'core/block-editor' ).getBlock( parentId ) : null,
			[ parentId ]
		);
		const siblings = parent ? parent.innerBlocks : [];
		const index = siblings.findIndex( ( block ) => block.clientId === clientId );
		const active = parent && parent.attributes.initialActive === index;
		const blockProps = useBlockProps( {
			className: 'service-tab-editor' + ( isSelected || active ? ' is-selected' : '' ),
		} );

		function updateTreatment( treatmentIndex, key, value ) {
			const treatments = attributes.treatments.map( ( treatment, currentIndex ) =>
				currentIndex === treatmentIndex ? { ...treatment, [ key ]: value } : treatment
			);
			setAttributes( { treatments } );
		}

		function removeTreatment( treatmentIndex ) {
			setAttributes( {
				treatments: attributes.treatments.filter( ( treatment, currentIndex ) => currentIndex !== treatmentIndex ),
			} );
		}

		return el(
			'section',
			blockProps,
			el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{ title: __( 'Category labels', 'beanstalk-child' ) },
					el( TextControl, {
						label: __( 'Category number', 'beanstalk-child' ),
						value: attributes.number,
						onChange: ( value ) => setAttributes( { number: value } ),
					} ),
					el( TextControl, {
						label: __( 'Mobile label', 'beanstalk-child' ),
						value: attributes.mobileLabel,
						onChange: ( value ) => setAttributes( { mobileLabel: value } ),
					} ),
					el( TextControl, {
						label: __( 'Desktop label', 'beanstalk-child' ),
						value: attributes.desktopLabel,
						onChange: ( value ) => setAttributes( { desktopLabel: value } ),
					} )
				)
			),
			el( 'p', { className: 'service-tab-editor__label' }, attributes.number + ' — ' + attributes.desktopLabel ),
			el( RichText, {
				tagName: 'h3',
				className: 'service-tab-editor__heading',
				value: attributes.heading,
				allowedFormats: [],
				placeholder: __( 'Panel heading…', 'beanstalk-child' ),
				onChange: ( value ) => setAttributes( { heading: value } ),
			} ),
			el(
				'div',
				{ className: 'service-tab-editor__treatments' },
				attributes.treatments.map( ( treatment, treatmentIndex ) =>
					el(
						'div',
						{ className: 'service-tab-editor__treatment', key: treatmentIndex },
						el( TextControl, {
							label: __( 'Treatment name', 'beanstalk-child' ),
							value: treatment.label,
							onChange: ( value ) => updateTreatment( treatmentIndex, 'label', value ),
						} ),
						el( TextControl, {
							label: __( 'Treatment URL', 'beanstalk-child' ),
							type: 'url',
							value: treatment.url,
							onChange: ( value ) => updateTreatment( treatmentIndex, 'url', value ),
						} ),
						el( Button, {
							isDestructive: true,
							variant: 'tertiary',
							onClick: () => removeTreatment( treatmentIndex ),
						}, __( 'Remove treatment', 'beanstalk-child' ) )
					)
				),
				el( Button, {
					variant: 'secondary',
					onClick: () => setAttributes( {
						treatments: [ ...attributes.treatments, { label: '', url: '' } ],
					} ),
				}, __( 'Add treatment', 'beanstalk-child' ) )
			),
			el(
				'div',
				{ className: 'service-tab-editor__appointment' },
				el( TextControl, {
					label: __( 'Appointment button label', 'beanstalk-child' ),
					value: attributes.buttonLabel,
					onChange: ( value ) => setAttributes( { buttonLabel: value } ),
				} ),
				el( TextControl, {
					label: __( 'Appointment button URL', 'beanstalk-child' ),
					type: 'url',
					value: attributes.buttonUrl,
					onChange: ( value ) => setAttributes( { buttonUrl: value } ),
				} )
			)
		);
	}

	blocks.registerBlockType( 'white-oaks/service-tab', {
		edit: Edit,
		save: function () {
			return null;
		},
	} );
} )( window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.data, window.wp.element, window.wp.i18n );
