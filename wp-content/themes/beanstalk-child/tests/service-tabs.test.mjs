import assert from 'node:assert/strict';
import { readFile } from 'node:fs/promises';
import test from 'node:test';

import {
	clampIndex,
	getDirectionalIndex,
	panelVisibility,
	relationshipIds,
} from '../blocks/service-tabs/interaction-helpers.js';

test( 'the default active tab is the first tab', () => {
	assert.equal( clampIndex( 0, 4 ), 0 );
	assert.deepEqual( panelVisibility( 0, 4 ), [ true, false, false, false ] );
} );

test( 'each tab selects exactly one panel', () => {
	for ( let index = 0; index < 4; index++ ) {
		assert.equal( panelVisibility( index, 4 ).filter( Boolean ).length, 1 );
		assert.equal( panelVisibility( index, 4 )[ index ], true );
	}
} );

test( 'desktop arrow keys wrap through the vertical list', () => {
	assert.equal( getDirectionalIndex( 'ArrowDown', 3, 4, false ), 0 );
	assert.equal( getDirectionalIndex( 'ArrowUp', 0, 4, false ), 3 );
	assert.equal( getDirectionalIndex( 'ArrowRight', 0, 4, false ), null );
} );

test( 'mobile arrow keys follow logical horizontal reading order', () => {
	assert.equal( getDirectionalIndex( 'ArrowRight', 1, 4, true ), 2 );
	assert.equal( getDirectionalIndex( 'ArrowLeft', 0, 4, true ), 3 );
	assert.equal( getDirectionalIndex( 'ArrowDown', 0, 4, true ), null );
} );

test( 'Home and End select the first and last tabs', () => {
	assert.equal( getDirectionalIndex( 'Home', 2, 4, false ), 0 );
	assert.equal( getDirectionalIndex( 'End', 1, 4, true ), 3 );
} );

test( 'multiple instances generate non-conflicting relationships', () => {
	const first = relationshipIds( 'service-tabs-alpha', 0 );
	const second = relationshipIds( 'service-tabs-beta', 0 );
	assert.notEqual( first.tabId, second.tabId );
	assert.notEqual( first.panelId, second.panelId );
	assert.equal( first.panelId, 'service-tabs-alpha-panel-0' );
} );

test( 'server renderer contains complete crawlable and accessible markup', async () => {
	const renderer = await readFile( new URL( '../blocks/service-tabs/render.php', import.meta.url ), 'utf8' );
	assert.match( renderer, /role="tablist"/ );
	assert.match( renderer, /role="tab"/ );
	assert.match( renderer, /role="tabpanel"/ );
	assert.match( renderer, /aria-controls/ );
	assert.match( renderer, /aria-labelledby/ );
	assert.match( renderer, /<a href=/ );
	assert.ok( renderer.includes( 'foreach ( $items as' ) );
	assert.doesNotMatch( renderer, /\\shidden(?:=|>)/ );
} );
