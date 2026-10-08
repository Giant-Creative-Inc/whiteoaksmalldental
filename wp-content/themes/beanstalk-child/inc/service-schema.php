<?php
/** Service schema derived from editor content; no copied manual graph. @package BeanstalkChild */
defined( 'ABSPATH' ) || exit;

function white_oaks_service_faqs( $blocks, $in_faq = false ) {
	$questions = array();
	foreach ( $blocks as $block ) {
		$designated = $in_faq || str_contains( $block['attrs']['className'] ?? '', 'home-faqs__list' ) || str_contains( $block['attrs']['className'] ?? '', 'service-faqs' );
		if ( $designated && 'core/details' === $block['blockName'] ) {
			if ( preg_match( '/<summary\b[^>]*>(.*?)<\/summary>/is', $block['innerHTML'], $match ) ) {
				$summary = preg_replace( '/<em\b[^>]*>\s*\d+\s*<\/em>/is', '', $match[1] );
				$summary = preg_replace( '/<strong\b[^>]*aria-hidden=["\x27]true["\x27][^>]*>.*?<\/strong>/is', '', $summary );
				$question = trim( html_entity_decode( wp_strip_all_tags( $summary ), ENT_QUOTES, 'UTF-8' ) );
				$answer = '';
				foreach ( $block['innerBlocks'] as $child ) {
					if ( in_array( $child['blockName'], array( 'core/paragraph', 'core/list' ), true ) ) {
						$answer .= ' ' . wp_strip_all_tags( serialize_block( $child ) );
					}
				}
				$answer = trim( html_entity_decode( $answer, ENT_QUOTES, 'UTF-8' ) );
				if ( $question && $answer && ! str_contains( $answer, '[PLACEHOLDER]' ) ) { $questions[] = array( '@type' => 'Question', 'name' => $question, 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $answer ) ); }
			}
		} else {
			$questions = array_merge( $questions, white_oaks_service_faqs( $block['innerBlocks'] ?? array(), $designated ) );
		}
	}
	return $questions;
}

function white_oaks_service_schema( $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post || 'service' !== $post->post_type ) { return null; }
	$foundation = white_oaks_schema_foundation();
	$dentist = null;
	$website_id = null;
	foreach ( $foundation['@graph'] ?? array() as $entity ) {
		if ( in_array( 'Dentist', (array) ( $entity['@type'] ?? array() ), true ) ) { $dentist = $entity; }
		if ( 'WebSite' === ( $entity['@type'] ?? '' ) ) { $website_id = $entity['@id']; }
	}
	// Until per-location settings exist, use the verified existing clinic entity.
	if ( ! $dentist || empty( $dentist['@id'] ) ) { return null; }
	$url = get_permalink( $post );
	$service_id = $url . '#service';
	$page_id = $url . '#webpage';
	$description = trim( wp_strip_all_tags( $post->post_excerpt ) );
	$service = array( '@type' => 'Service', '@id' => $service_id, 'name' => html_entity_decode( get_the_title( $post ), ENT_QUOTES, 'UTF-8' ), 'serviceType' => get_post_meta( $post_id, '_white_oaks_service_type', true ) ?: html_entity_decode( get_the_title( $post ), ENT_QUOTES, 'UTF-8' ), 'url' => $url, 'provider' => array( '@id' => $dentist['@id'] ), 'mainEntityOfPage' => array( '@id' => $page_id ) );
	$page = array( '@type' => 'WebPage', '@id' => $page_id, 'url' => $url, 'name' => html_entity_decode( get_the_title( $post ), ENT_QUOTES, 'UTF-8' ), 'mainEntity' => array( '@id' => $service_id ), 'about' => array( '@id' => $service_id ), 'inLanguage' => get_bloginfo( 'language' ), 'breadcrumb' => array( '@id' => $url . '#breadcrumb' ) );
	if ( $website_id ) { $page['isPartOf'] = array( '@id' => $website_id ); }
	if ( $description ) { $service['description'] = $description; $page['description'] = $description; }
	if ( isset( $dentist['areaServed'] ) ) { $service['areaServed'] = $dentist['areaServed']; }
	$parent = white_oaks_service_parent( $post_id );
	if ( $parent ) { $service['category'] = $parent->post_title; }
	$image = get_the_post_thumbnail_url( $post, 'full' );
	$graph = array( $page, $service );
	if ( white_oaks_is_service_pillar( $post_id ) ) {
		unset( $page['mainEntity'], $page['about'] );
		$page['@type'] = 'CollectionPage';
		$graph = array( $page );
	}
	if ( $image ) {
		$graph[0]['primaryImageOfPage'] = array( '@id' => $url . '#primaryimage' );
		if ( ! white_oaks_is_service_pillar( $post_id ) ) { $graph[1]['image'] = $image; }
		$graph[] = array( '@type' => 'ImageObject', '@id' => $url . '#primaryimage', 'url' => $image, 'contentUrl' => $image );
	}
	$faq = white_oaks_service_faqs( parse_blocks( $post->post_content ) );
	if ( $faq ) {
		$graph[0]['hasPart'] = array( '@id' => $url . '#faq' );
		$graph[] = array( '@type' => 'FAQPage', '@id' => $url . '#faq', 'mainEntity' => $faq, 'isPartOf' => array( '@id' => $page_id ) );
	}
	$crumbs = array( array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => home_url( '/' ) ) );
	$directory = get_page_by_path( 'services' );
	if ( $directory && 'publish' === $directory->post_status ) { $crumbs[] = array( '@type' => 'ListItem', 'position' => count( $crumbs ) + 1, 'name' => get_the_title( $directory ), 'item' => get_permalink( $directory ) ); }
	foreach ( array_reverse( get_post_ancestors( $post ) ) as $ancestor_id ) {
		if ( 'publish' !== get_post_status( $ancestor_id ) ) { continue; }
		$crumbs[] = array( '@type' => 'ListItem', 'position' => count( $crumbs ) + 1, 'name' => html_entity_decode( get_the_title( $ancestor_id ), ENT_QUOTES, 'UTF-8' ), 'item' => get_permalink( $ancestor_id ) );
	}
	$crumbs[] = array( '@type' => 'ListItem', 'position' => count( $crumbs ) + 1, 'name' => html_entity_decode( get_the_title( $post ), ENT_QUOTES, 'UTF-8' ), 'item' => $url );
	$graph[] = array( '@type' => 'BreadcrumbList', '@id' => $url . '#breadcrumb', 'itemListElement' => $crumbs );
	return array( '@context' => 'https://schema.org', '@graph' => $graph );
}
