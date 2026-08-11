document.querySelectorAll( '.wp-block-white-oaks-click-to-load-map' ).forEach( ( map ) => {
	map.addEventListener( 'click', ( event ) => {
		if ( event.target.closest( '[data-map-directions]' ) ) {
			return;
		}

		if (
			event.button !== 0 ||
			event.altKey ||
			event.ctrlKey ||
			event.metaKey ||
			event.shiftKey
		) {
			return;
		}

		event.preventDefault();

		if ( map.classList.contains( 'is-loading' ) || map.classList.contains( 'is-loaded' ) ) {
			return;
		}

		map.classList.add( 'is-loading' );
		map.setAttribute( 'aria-busy', 'true' );

		const iframe = document.createElement( 'iframe' );
		const embedUrl = new URL( 'https://www.google.com/maps' );
		embedUrl.searchParams.set( 'q', map.dataset.mapQuery || '' );
		embedUrl.searchParams.set( 'output', 'embed' );

		iframe.src = embedUrl.toString();
		iframe.title = `Interactive map showing ${ map.dataset.mapQuery || 'this location' }`;
		iframe.loading = 'eager';
		iframe.referrerPolicy = 'no-referrer-when-downgrade';
		iframe.allowFullscreen = true;
		iframe.tabIndex = 0;

		iframe.addEventListener( 'load', () => {
			map.classList.remove( 'is-loading' );
			map.classList.add( 'is-loaded' );
			map.setAttribute( 'aria-busy', 'false' );
			iframe.focus( { preventScroll: true } );
		}, { once: true } );

		map.append( iframe );
	} );
} );
