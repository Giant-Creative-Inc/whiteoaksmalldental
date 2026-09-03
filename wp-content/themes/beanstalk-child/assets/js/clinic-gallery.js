document.querySelectorAll( '.clinic-gallery' ).forEach( ( gallery ) => {
	const slides = Array.from( gallery.querySelectorAll( '.clinic-gallery__slide' ) );
	const current = gallery.querySelector( '.clinic-gallery__fraction-current' );
	const previous = gallery.querySelector( '[data-clinic-gallery-previous]' );
	const next = gallery.querySelector( '[data-clinic-gallery-next]' );

	if ( slides.length < 2 || ! current || ! previous || ! next ) {
		return;
	}

	let activeIndex = 0;
	let isAnimating = false;
	const reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );
	const animationDuration = 360;
	const lightbox = document.createElement( 'dialog' );
	const lightboxImage = document.createElement( 'img' );
	const lightboxClose = document.createElement( 'button' );
	let lightboxOpener = null;

	const hydrateSlide = ( slide ) => {
		const image = slide.querySelector( 'img' );

		if ( ! image?.dataset.gallerySrc ) {
			return Promise.resolve();
		}

		const src = image.dataset.gallerySrc;
		const srcset = image.dataset.gallerySrcset;
		const sizes = image.dataset.gallerySizes;

		delete image.dataset.gallerySrc;
		delete image.dataset.gallerySrcset;
		delete image.dataset.gallerySizes;

		return new Promise( ( resolve ) => {
			image.addEventListener( 'load', resolve, { once: true } );
			image.addEventListener( 'error', resolve, { once: true } );
			image.loading = 'eager';

			if ( sizes ) {
				image.sizes = sizes;
			}

			if ( srcset ) {
				image.srcset = srcset;
			}

			image.src = src;

			if ( image.complete ) {
				resolve();
			}
		} );
	};

	lightbox.className = 'clinic-gallery-lightbox';
	lightbox.setAttribute( 'aria-label', 'Clinic gallery image preview' );
	lightboxImage.className = 'clinic-gallery-lightbox__image';
	lightboxClose.className = 'clinic-gallery-lightbox__close';
	lightboxClose.type = 'button';
	lightboxClose.setAttribute( 'aria-label', 'Close image preview' );
	lightboxClose.textContent = '×';
	lightbox.append( lightboxImage, lightboxClose );
	document.body.append( lightbox );

	const openLightbox = ( slide ) => {
		const image = slide.querySelector( 'img' );

		if ( ! image ) {
			return;
		}

		lightboxOpener = slide;
		lightboxImage.src = image.dataset.galleryFullSrc || image.currentSrc || image.src;
		lightboxImage.alt = image.alt;
		lightbox.showModal();
		lightboxClose.focus();
	};

	lightboxClose.addEventListener( 'click', () => lightbox.close() );
	lightbox.addEventListener( 'click', ( event ) => {
		if ( event.target === lightbox ) {
			lightbox.close();
		}
	} );
	lightbox.addEventListener( 'close', () => lightboxOpener?.focus() );

	slides.forEach( ( slide ) => {
		slide.setAttribute( 'role', 'button' );
		slide.setAttribute( 'aria-label', 'Open image in lightbox' );
		slide.addEventListener( 'click', () => openLightbox( slide ) );
		slide.addEventListener( 'keydown', ( event ) => {
			if ( 'Enter' === event.key || ' ' === event.key ) {
				event.preventDefault();
				openLightbox( slide );
			}
		} );
	} );

	const showSlide = async ( index, direction = 'next', animate = true ) => {
		if ( isAnimating ) {
			return;
		}

		const nextIndex = ( index + slides.length ) % slides.length;
		const outgoingSlide = slides[ activeIndex ];
		const incomingSlide = slides[ nextIndex ];
		const shouldAnimate = animate && ! reducedMotion.matches && nextIndex !== activeIndex;

		if ( shouldAnimate ) {
			isAnimating = true;
			await hydrateSlide( incomingSlide );
		}

		if ( ! shouldAnimate ) {
			slides.forEach( ( slide, slideIndex ) => {
				const isActive = slideIndex === nextIndex;
				slide.hidden = false;
				slide.dataset.galleryState = isActive ? 'active' : 'inactive';
				slide.setAttribute( 'aria-hidden', String( ! isActive ) );
				slide.tabIndex = isActive ? 0 : -1;
			} );
			activeIndex = nextIndex;
			current.textContent = String( activeIndex + 1 ).padStart( 2, '0' );
			return;
		}

		incomingSlide.hidden = false;
		incomingSlide.tabIndex = 0;
		outgoingSlide.tabIndex = -1;
		incomingSlide.dataset.galleryState = direction === 'next' ? 'enter-right' : 'enter-left';
		incomingSlide.setAttribute( 'aria-hidden', 'false' );
		outgoingSlide.setAttribute( 'aria-hidden', 'true' );

		window.requestAnimationFrame( () => {
			outgoingSlide.dataset.galleryState = direction === 'next' ? 'exit-left' : 'exit-right';
			incomingSlide.dataset.galleryState = 'active';
		} );

		activeIndex = nextIndex;
		current.textContent = String( activeIndex + 1 ).padStart( 2, '0' );

		window.setTimeout( () => {
			outgoingSlide.dataset.galleryState = 'inactive';
			isAnimating = false;
		}, animationDuration );
	};

	previous.addEventListener( 'click', () => showSlide( activeIndex - 1, 'previous' ) );
	next.addEventListener( 'click', () => showSlide( activeIndex + 1, 'next' ) );
	showSlide( 0, 'next', false );
} );
