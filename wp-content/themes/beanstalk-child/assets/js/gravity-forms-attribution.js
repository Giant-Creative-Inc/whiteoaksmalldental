( () => {
	'use strict';

	const config = window.whiteOaksFormAttribution;

	if ( ! config || window.whiteOaksFormAttributionLoaded ) {
		return;
	}

	window.whiteOaksFormAttributionLoaded = true;

	const campaignKeys = [
		'utm_source',
		'utm_medium',
		'utm_campaign',
		'utm_term',
		'utm_content',
		'gclid',
		'gbraid',
		'wbraid',
		'fbclid',
		'msclkid',
	];

	const readCookie = () => {
		const prefix = `${ config.cookieName }=`;
		const cookie = document.cookie
			.split( ';' )
			.map( ( item ) => item.trim() )
			.find( ( item ) => item.startsWith( prefix ) );

		if ( ! cookie ) {
			return null;
		}

		try {
			const value = JSON.parse( decodeURIComponent( cookie.slice( prefix.length ) ) );
			return value && 'string' === typeof value.landing_page && 'string' === typeof value.captured_at
				? value
				: null;
		} catch ( error ) {
			return null;
		}
	};

	const query = new URLSearchParams( window.location.search );
	let firstTouch = readCookie();

	if ( ! firstTouch ) {
		firstTouch = {
			landing_page: window.location.href,
			referrer: document.referrer || '',
			captured_at: new Date().toISOString(),
		};

		campaignKeys.forEach( ( key ) => {
			if ( query.has( key ) ) {
				firstTouch[ key ] = query.get( key ) || '';
			}
		} );

		const expires = new Date();
		expires.setDate( expires.getDate() + Number( config.cookieDays || 90 ) );

		const attributes = [
			`${ config.cookieName }=${ encodeURIComponent( JSON.stringify( firstTouch ) ) }`,
			`expires=${ expires.toUTCString() }`,
			'path=/',
			'SameSite=Lax',
		];

		if ( 'https:' === window.location.protocol ) {
			attributes.push( 'Secure' );
		}

		document.cookie = attributes.join( '; ' );
	}

	const attributionValue = ( key ) => {
		if ( 'landing_page' === key ) {
			return firstTouch.landing_page || '';
		}

		if ( 'referrer' === key ) {
			return firstTouch.referrer || '';
		}

		if ( 'form_timestamp' === key ) {
			return new Date().toISOString();
		}

		return query.has( key ) ? query.get( key ) || '' : firstTouch[ key ] || '';
	};

	const populateForm = ( formId ) => {
		const fields = config.forms?.[ formId ];
		const form = document.querySelector( `#gform_${ formId }` );

		if ( ! fields || ! form ) {
			return;
		}

		bindPhone( formId, fields.phone );

		Object.entries( fields ).forEach( ( [ key, fieldId ] ) => {
			if ( 'phone' === key ) {
				return;
			}

			const input = form.querySelector( `#input_${ formId }_${ fieldId }` );
			if ( input && '' === input.value ) {
				input.value = attributionValue( key );
			}
		} );
	};

	const formatPhone = ( value ) => {
		const digits = value.replace( /\D/g, '' ).slice( 0, 11 );
		const hasCountryCode = 11 === digits.length;
		const national = hasCountryCode ? digits.slice( 1 ) : digits;
		let formatted = '';

		if ( national.length ) {
			formatted = `(${ national.slice( 0, 3 ) }`;
		}
		if ( national.length > 3 ) {
			formatted += `) ${ national.slice( 3, 6 ) }`;
		}
		if ( national.length > 6 ) {
			formatted += `-${ national.slice( 6, 10 ) }`;
		}

		return hasCountryCode ? `1 ${ formatted }` : formatted;
	};

	const syncPhone = ( visibleInput ) => {
		visibleInput.value = formatPhone( visibleInput.value );
	};

	function bindPhone( formId, fieldId ) {
		const input = document.querySelector( `#input_${ formId }_${ fieldId }_visible, #input_${ formId }_${ fieldId }` );

		if ( ! input || 'true' === input.dataset.whiteOaksPhoneBound ) {
			return;
		}

		input.dataset.whiteOaksPhoneBound = 'true';
		input.inputMode = 'numeric';
		input.addEventListener( 'input', () => syncPhone( input ) );
	}

	document.addEventListener( 'submit', ( event ) => {
		const formId = event.target.id?.match( /^gform_(\d+)$/ )?.[ 1 ];
		if ( formId && config.forms?.[ formId ] ) {
			const phoneId = config.forms[ formId ].phone;
			const phoneInput = event.target.querySelector( `#input_${ formId }_${ phoneId }_visible, #input_${ formId }_${ phoneId }` );
			if ( phoneInput ) {
				syncPhone( phoneInput );
			}
			populateForm( formId );
		}
	}, true );

	const populateConfiguredForms = () => Object.keys( config.forms || {} ).forEach( populateForm );

	document.addEventListener( 'DOMContentLoaded', populateConfiguredForms );
	document.addEventListener( 'gform/postRender', ( event ) => populateForm( String( event.detail?.formId || '' ) ) );

	if ( window.jQuery ) {
		window.jQuery( document ).on( 'gform_post_render', ( event, formId ) => populateForm( String( formId ) ) );
	}

	populateConfiguredForms();
} )();
