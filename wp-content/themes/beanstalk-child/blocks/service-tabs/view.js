import { getElement, store } from '@wordpress/interactivity';
import { clampIndex, getDirectionalIndex } from './interaction-helpers.js';

function getRoot( element ) {
	return element.closest( '.white-oaks-service-tabs' );
}

function activateIndex( root, index, focus = false, animate = true ) {
	const tabs = Array.from( root.querySelectorAll( '[role="tab"]' ) );
	const panels = Array.from( root.querySelectorAll( '[role="tabpanel"]' ) );
	const safeIndex = clampIndex( index, tabs.length );

	tabs.forEach( ( tab, currentIndex ) => {
		const selected = currentIndex === safeIndex;
		tab.setAttribute( 'aria-selected', selected ? 'true' : 'false' );
		tab.tabIndex = selected ? 0 : -1;
	} );

	panels.forEach( ( panel, currentIndex ) => {
		panel.classList.remove( 'is-entering' );
		panel.hidden = currentIndex !== safeIndex;
	} );

	const activePanel = panels[ safeIndex ];
	const reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	if ( animate && ! reduceMotion ) {
		activePanel.classList.add( 'is-entering' );
		activePanel.addEventListener(
			'animationend',
			() => activePanel.classList.remove( 'is-entering' ),
			{ once: true }
		);
	}

	root.dataset.activeIndex = String( safeIndex );

	if ( focus ) {
		tabs[ safeIndex ].focus();
	}
}

store( 'white-oaks/service-tabs', {
	actions: {
		activate( event ) {
			const tab = event.currentTarget;
			activateIndex( getRoot( tab ), Number( tab.dataset.serviceTabIndex ), true );
		},
		onKeydown( event ) {
			const tab = event.currentTarget;
			const root = getRoot( tab );
			const tabs = Array.from( root.querySelectorAll( '[role="tab"]' ) );
			const current = tabs.indexOf( tab );
			const mobile = window.matchMedia( '(max-width: 1024px)' ).matches;
			let next = current;

			next = getDirectionalIndex( event.key, current, tabs.length, mobile );

			if ( null === next && 'Enter' !== event.key && ' ' !== event.key ) {
				return;
			}

			event.preventDefault();
			activateIndex( root, null === next ? current : next, true );
		},
	},
	callbacks: {
		init() {
			const root = getElement().ref;
			root.classList.add( 'is-enhanced' );
			activateIndex( root, Number( root.dataset.activeIndex || 0 ), false, false );
		},
	},
} );
