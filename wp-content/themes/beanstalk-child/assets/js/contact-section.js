( () => {
	'use strict';

	const activateTab = ( card, tabs, panels, nextIndex, moveFocus = false ) => {
		const index = Math.max( 0, Math.min( nextIndex, tabs.length - 1 ) );

		tabs.forEach( ( tab, currentIndex ) => {
			const selected = currentIndex === index;
			tab.setAttribute( 'aria-selected', String( selected ) );
			tab.setAttribute( 'tabindex', selected ? '0' : '-1' );
		} );

		panels.forEach( ( panel, currentIndex ) => {
			const selected = currentIndex === index;
			panel.hidden = ! selected;
			panel.classList.toggle( 'is-entering', selected );
			if ( selected ) {
				window.setTimeout( () => panel.classList.remove( 'is-entering' ), 260 );
			}
		} );

		if ( moveFocus ) {
			tabs[ index ].focus();
		}

		card.dataset.activeTab = String( index );
	};

	document.querySelectorAll( '.contact-section__clinic-card' ).forEach( ( card ) => {
		const tablist = card.querySelector( '.contact-section__tabs' );
		const tabs = Array.from( tablist?.querySelectorAll( '.wp-block-button__link' ) || [] );
		const panels = Array.from( card.querySelectorAll( '.contact-section__clinic-panel' ) );

		if ( ! tablist || tabs.length !== panels.length || tabs.length < 2 ) {
			return;
		}

		tablist.setAttribute( 'role', 'tablist' );
		tablist.setAttribute( 'aria-label', 'Choose a clinic' );

		tabs.forEach( ( tab, index ) => {
			const panel = panels[ index ];
			const tabId = `contact-clinic-tab-${ index + 1 }`;
			const panelId = `contact-clinic-panel-${ index + 1 }`;

			tab.id = tabId;
			tab.setAttribute( 'role', 'tab' );
			tab.setAttribute( 'aria-controls', panelId );
			panel.id = panelId;
			panel.setAttribute( 'role', 'tabpanel' );
			panel.setAttribute( 'aria-labelledby', tabId );

			tab.addEventListener( 'click', ( event ) => {
				event.preventDefault();
				activateTab( card, tabs, panels, index );
			} );

			tab.addEventListener( 'keydown', ( event ) => {
				let nextIndex = index;

				if ( event.key === 'ArrowRight' || event.key === 'ArrowDown' ) nextIndex = ( index + 1 ) % tabs.length;
				else if ( event.key === 'ArrowLeft' || event.key === 'ArrowUp' ) nextIndex = ( index - 1 + tabs.length ) % tabs.length;
				else if ( event.key === 'Home' ) nextIndex = 0;
				else if ( event.key === 'End' ) nextIndex = tabs.length - 1;
				else return;

				event.preventDefault();
				activateTab( card, tabs, panels, nextIndex, true );
			} );
		} );

		card.classList.add( 'is-enhanced' );
		activateTab( card, tabs, panels, 0 );
	} );
} )();
