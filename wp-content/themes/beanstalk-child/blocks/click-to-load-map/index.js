( function ( blocks, blockEditor, components, element, i18n ) {
	'use strict';

	const el = element.createElement;
	const { Button, PanelBody, TextControl } = components;
	const { InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } = blockEditor;
	const { __ } = i18n;

	function Edit( { attributes, setAttributes } ) {
		const blockProps = useBlockProps( { className: 'click-to-load-map-editor' } );
		const preview = attributes.imageUrl
			? el( 'img', {
				className: 'click-to-load-map-editor__image',
				src: attributes.imageUrl,
				alt: attributes.imageAlt || '',
			} )
			: el( 'p', { className: 'click-to-load-map-editor__placeholder' }, __( 'Choose a map preview image.', 'beanstalk-child' ) );

		return el(
			'div',
			blockProps,
			el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{ title: __( 'Map settings', 'beanstalk-child' ), initialOpen: true },
					el( TextControl, {
						label: __( 'Location name', 'beanstalk-child' ),
						value: attributes.locationName,
						onChange: ( locationName ) => setAttributes( { locationName } ),
					} ),
					el( TextControl, {
						label: __( 'Full address', 'beanstalk-child' ),
						value: attributes.address,
						onChange: ( address ) => setAttributes( { address } ),
					} ),
					el( TextControl, {
						label: __( 'Google Maps link', 'beanstalk-child' ),
						type: 'url',
						value: attributes.mapsUrl,
						onChange: ( mapsUrl ) => setAttributes( { mapsUrl } ),
					} ),
					el( TextControl, {
						label: __( 'Button label', 'beanstalk-child' ),
						value: attributes.buttonLabel,
						onChange: ( buttonLabel ) => setAttributes( { buttonLabel } ),
					} ),
					el( TextControl, {
						label: __( 'Directions button label', 'beanstalk-child' ),
						value: attributes.directionsLabel,
						onChange: ( directionsLabel ) => setAttributes( { directionsLabel } ),
					} ),
					el( TextControl, {
						label: __( 'Preview image alternative text', 'beanstalk-child' ),
						value: attributes.imageAlt,
						onChange: ( imageAlt ) => setAttributes( { imageAlt } ),
					} )
				)
			),
			preview,
			el(
				'div',
				{ className: 'click-to-load-map-editor__actions' },
				el(
					MediaUploadCheck,
					null,
					el( MediaUpload, {
						allowedTypes: [ 'image' ],
						value: attributes.imageId,
						onSelect: ( media ) => setAttributes( {
							imageId: media.id,
							imageUrl: media.url,
							imageAlt: media.alt || attributes.imageAlt,
						} ),
						render: ( { open } ) => el(
							Button,
							{ variant: 'primary', onClick: open },
							attributes.imageUrl ? __( 'Replace preview image', 'beanstalk-child' ) : __( 'Choose preview image', 'beanstalk-child' )
						),
					} )
				),
				el( 'span', null, attributes.buttonLabel || __( 'Use interactive map', 'beanstalk-child' ) ),
				el( 'span', null, attributes.directionsLabel || __( 'Get directions', 'beanstalk-child' ) )
			)
		);
	}

	blocks.registerBlockType( 'white-oaks/click-to-load-map', {
		edit: Edit,
		save: function () {
			return null;
		},
	} );
} )( window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n );
