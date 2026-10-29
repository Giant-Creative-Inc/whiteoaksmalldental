<?php
/**
 * Apply the location contact layout to Local's existing editable page content.
 *
 * Preview: wp eval-file wp-content/themes/beanstalk-child/tools/location-contact-layout.php
 * Apply: append apply to that command. Saves a temporary backup before writing.
 * This tool never runs from a web request or during normal theme loading.
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
if ( wp_parse_url( home_url(), PHP_URL_HOST ) !== 'whiteoaksmalldental.local' ) {
    WP_CLI::error( 'This migration is restricted to the Local site.' );
}
$post = get_page_by_path( 'locations/white-oaks-mall' );
if ( ! $post ) { WP_CLI::error( 'Location page not found.' ); }
$before = $post->post_content;
$blocks=parse_blocks($before);
$targets=[];
foreach($blocks as $i=>$b) if(($b['attrs']['anchor']??'')==='view-hours')$targets[]=$i;
if ( ! $targets ) { WP_CLI::error( 'Getting here section not found.' ); }
if ( str_contains( serialize_block( $blocks[$targets[0]] ), 'location-contact-layout' ) ) {
    $after = $before;
} else {
    $section=$blocks[$targets[0]];
    $old=serialize_block($section);
    $form=$section['innerBlocks'][1]['innerBlocks'][0];
    $map=$section['innerBlocks'][1]['innerBlocks'][1]['innerBlocks'][0];
    $hours=$section['innerBlocks'][1]['innerBlocks'][1]['innerBlocks'][1]['innerBlocks'][0];
    if(($form['attrs']['className']??'')!=='contact-section__form-card'||($hours['attrs']['className']??'')!=='location-info__hours')WP_CLI::error('Unexpected source structure.');
    // Preserve the form's editable content; remove the constrained layout that narrows it.
    $form['attrs']['layout']=['type'=>'default'];
    unset($form['attrs']['style']);
    $map['attrs']['className']='location-contact-layout__map';
    $hourBlocks=array_values(array_filter($hours['innerBlocks'],fn($b)=>($b['attrs']['className']??'')!=='location-info__booking'));
    if(count($hourBlocks)!==2)WP_CLI::error('Unexpected hours content.');
    $hoursMarkup='<!-- wp:group {"className":"location-info__hours","backgroundColor":"white","layout":{"type":"default"}} --><div class="wp-block-group location-info__hours has-white-background-color has-background">'.serialize_blocks($hourBlocks).'</div><!-- /wp:group -->';
    $layout='<!-- wp:columns {"align":"wide","className":"location-contact-layout","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|24","left":"var:preset|spacing|48"}}}} --><div class="wp-block-columns alignwide location-contact-layout"><!-- wp:column {"width":"56%","layout":{"type":"default"}} --><div class="wp-block-column" style="flex-basis:56%">'.serialize_block($form).'</div><!-- /wp:column --><!-- wp:column {"width":"44%","style":{"spacing":{"blockGap":"var:preset|spacing|24"}},"layout":{"type":"default"}} --><div class="wp-block-column" style="flex-basis:44%">'.serialize_block($map).$hoursMarkup.'</div><!-- /wp:column --></div><!-- /wp:columns -->';
    $section['innerBlocks'][1]=parse_blocks($layout)[0];
    $new=serialize_block($section);
    if(substr_count($before,$old)!==1)WP_CLI::error('Source match failed.');
    $after=str_replace($old,$new,$before);
}
// The approved page has one Getting here section, including one Areas we serve list.
foreach ( array_slice( $targets, 1 ) as $duplicate_index ) {
    $duplicate = serialize_block( $blocks[ $duplicate_index ] );
    if ( substr_count( $after, $duplicate ) !== 1 ) { WP_CLI::error( 'Duplicate section match failed.' ); }
    $after = str_replace( $duplicate, '', $after );
}
if ( $after === $before ) { WP_CLI::success( 'Layout is already applied; one section remains.' ); return; }
if ( ! in_array( 'apply', $args ?? array(), true ) ) {
    WP_CLI::success( 'Preview passed. Append apply to save the layout.' );
    return;
}
$backup = wp_tempnam( 'white-oaks-location-contact-before.html' );
if ( ! $backup || false === file_put_contents( $backup, $before ) ) {
    WP_CLI::error( 'Could not save the content backup.' );
}
$result = wp_update_post( wp_slash( array( 'ID' => $post->ID, 'post_content' => $after ) ), true );
if ( is_wp_error( $result ) ) { WP_CLI::error( $result->get_error_message() ); }
if ( get_post( $post->ID )->post_content !== $after ) { WP_CLI::error( 'Saved readback differs.' ); }
WP_CLI::success( 'Saved and verified location content. Backup: ' . $backup );
