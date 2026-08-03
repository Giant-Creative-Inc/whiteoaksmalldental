export function clampIndex( index, count ) {
	if ( count < 1 ) {
		return 0;
	}

	return Math.min( Math.max( Number( index ) || 0, 0 ), count - 1 );
}

export function getDirectionalIndex( key, current, count, mobile ) {
	if ( 'Home' === key ) {
		return 0;
	}

	if ( 'End' === key ) {
		return count - 1;
	}

	if ( mobile && 'ArrowRight' === key ) {
		return ( current + 1 ) % count;
	}

	if ( mobile && 'ArrowLeft' === key ) {
		return ( current - 1 + count ) % count;
	}

	if ( ! mobile && 'ArrowDown' === key ) {
		return ( current + 1 ) % count;
	}

	if ( ! mobile && 'ArrowUp' === key ) {
		return ( current - 1 + count ) % count;
	}

	return null;
}

export function relationshipIds( instanceId, index ) {
	return {
		tabId: `${ instanceId }-tab-${ index }`,
		panelId: `${ instanceId }-panel-${ index }`,
	};
}

export function panelVisibility( activeIndex, count ) {
	return Array.from( { length: count }, ( value, index ) => index === activeIndex );
}
