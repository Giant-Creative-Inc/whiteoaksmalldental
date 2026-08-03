( function ( blocks, blockEditor, components, element, i18n ) {
	'use strict';

	const el = element.createElement;
	const { Button, PanelBody } = components;
	const { InspectorControls, RichText, useBlockProps } = blockEditor;
	const { __ } = i18n;

	function Edit( props ) {
		const { attributes, setAttributes } = props;
		const stories = attributes.testimonials || [];
		const blockProps = useBlockProps( { className: 'patient-stories-editor' } );

		function updateStory( index, key, value ) {
			setAttributes( {
				testimonials: stories.map( ( story, storyIndex ) =>
					storyIndex === index ? { ...story, [ key ]: value } : story
				),
			} );
		}

		function addStory() {
			setAttributes( {
				testimonials: [ ...stories, {
					quote: __( 'Add another customer story here.', 'beanstalk-child' ),
					reviewer: __( 'Reviewer Name', 'beanstalk-child' ),
					source: __( 'Review Source', 'beanstalk-child' ),
				} ],
			} );
		}

		function removeStory( index ) {
			if ( stories.length <= 1 ) {
				return;
			}
			setAttributes( { testimonials: stories.filter( ( story, storyIndex ) => storyIndex !== index ) } );
		}

		return el(
			'div',
			blockProps,
			el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{ title: __( 'Patient stories', 'beanstalk-child' ) },
					el( Button, { variant: 'secondary', onClick: addStory }, __( 'Add testimonial', 'beanstalk-child' ) )
				)
			),
			el(
				'div',
				{ className: 'patient-stories-editor__summary' },
				el( RichText, { tagName: 'p', value: attributes.eyebrow, onChange: ( eyebrow ) => setAttributes( { eyebrow } ), placeholder: __( 'Your Eyebrow Here', 'beanstalk-child' ) } ),
				el( RichText, { tagName: 'h2', value: attributes.heading, onChange: ( heading ) => setAttributes( { heading } ), placeholder: __( 'Your Headline Here', 'beanstalk-child' ) } ),
				el( RichText, { tagName: 'p', value: attributes.rating, onChange: ( rating ) => setAttributes( { rating } ), placeholder: '4.9' } ),
				el( RichText, { tagName: 'p', value: attributes.reviewSummary, onChange: ( reviewSummary ) => setAttributes( { reviewSummary } ), placeholder: __( 'Add review summary.', 'beanstalk-child' ) } )
			),
			el(
				'div',
				{ className: 'patient-stories-editor__stories' },
				stories.map( ( story, index ) =>
					el(
						'article',
						{ className: 'patient-stories-editor__story', key: index },
						el( 'p', { className: 'patient-stories-editor__label' }, __( 'Testimonial', 'beanstalk-child' ) + ' ' + ( index + 1 ) ),
						el( RichText, { tagName: 'blockquote', value: story.quote, onChange: ( value ) => updateStory( index, 'quote', value ), placeholder: __( 'Add a customer story.', 'beanstalk-child' ) } ),
						el( RichText, { tagName: 'p', value: story.reviewer, onChange: ( value ) => updateStory( index, 'reviewer', value ), placeholder: __( 'Reviewer Name', 'beanstalk-child' ) } ),
						el( RichText, { tagName: 'p', value: story.source, onChange: ( value ) => updateStory( index, 'source', value ), placeholder: __( 'Review Source', 'beanstalk-child' ) } ),
						el( Button, { isDestructive: true, disabled: stories.length <= 1, onClick: () => removeStory( index ) }, __( 'Remove testimonial', 'beanstalk-child' ) )
					)
				)
			)
		);
	}

	blocks.registerBlockType( 'white-oaks/patient-stories', {
		edit: Edit,
		save: function () {
			return null;
		},
	} );
} )( window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n );
