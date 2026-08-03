document.querySelectorAll( '.wp-block-white-oaks-patient-stories' ).forEach( ( carousel ) => {
	const slides = Array.from( carousel.querySelectorAll( '[data-patient-story-slide]' ) );
	const counters = Array.from( carousel.querySelectorAll( '[data-patient-story-counter]' ) );
	const previousButtons = Array.from( carousel.querySelectorAll( '[data-patient-story-previous]' ) );
	const nextButtons = Array.from( carousel.querySelectorAll( '[data-patient-story-next]' ) );
	let activeIndex = 0;

	if ( slides.length < 2 || ! previousButtons.length || ! nextButtons.length ) {
		return;
	}

	function showSlide( requestedIndex, focusSlide = false ) {
		activeIndex = ( requestedIndex + slides.length ) % slides.length;
		slides.forEach( ( slide, index ) => {
			slide.hidden = index !== activeIndex;
		} );

		const activeSlide = slides[ activeIndex ];
		activeSlide.classList.add( 'is-entering' );
		requestAnimationFrame( () => {
			requestAnimationFrame( () => activeSlide.classList.remove( 'is-entering' ) );
		} );

		counters.forEach( ( counter ) => {
			counter.textContent = String( activeIndex + 1 ).padStart( 2, '0' ) + ' / ' + String( slides.length ).padStart( 2, '0' );
		} );

		if ( focusSlide ) {
			activeSlide.focus( { preventScroll: true } );
		}
	}

	previousButtons.forEach( ( button ) => button.addEventListener( 'click', () => showSlide( activeIndex - 1, true ) ) );
	nextButtons.forEach( ( button ) => button.addEventListener( 'click', () => showSlide( activeIndex + 1, true ) ) );
	showSlide( 0 );
} );
