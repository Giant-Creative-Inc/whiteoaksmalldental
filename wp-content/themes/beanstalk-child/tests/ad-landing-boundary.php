<?php
/** Run directly with PHP; no database, server, or form submissions required. */
define( 'ABSPATH', __DIR__ . '/' );
class WP_Post {
	public $post_type = 'page';
	public $post_name = 'invisalign';
}
class WP_Theme_JSON_Resolver {
	public static $resets = 0;
	public static function clean_cached_data() { ++self::$resets; }
}
function add_action( ...$args ) {}
function add_filter( ...$args ) {}
function is_admin() { return $GLOBALS['request']['admin']; }
function did_action( $name ) { return $GLOBALS['request']['queried']; }
function is_page( $slug = null ) { return $GLOBALS['request']['page'] && ( null === $slug || $GLOBALS['request']['slug'] === $slug ); }
function get_queried_object_id() { return 91024; }
function get_post( $id ) { $post = new WP_Post(); $post->post_name = $GLOBALS['request']['slug']; return $post; }
function get_page_template_slug( $id ) { return $GLOBALS['request']['template']; }
function absint( $value ) { return abs( (int) $value ); }
function wp_unslash( $value ) { return $value; }
function wp_json_file_decode( $path, $options ) { return json_decode( file_get_contents( $path ), true, 512, JSON_THROW_ON_ERROR ); }
function get_stylesheet_directory_uri() { return 'https://example.test/wp-content/themes/beanstalk-child'; }
function beanstalk_child_asset_version( $path ) { return 'test'; }
function wp_enqueue_style( ...$args ) { $GLOBALS['assets'][] = $args; }
function wp_enqueue_script( ...$args ) { $GLOBALS['assets'][] = $args; }
function wp_dequeue_script( ...$args ) { $GLOBALS['assets'][] = $args; }
function wp_dequeue_style( ...$args ) { $GLOBALS['assets'][] = $args; }
function wp_styles() { return (object) array( 'queue' => array( 'white-oaks-component-forms' ) ); }
function check( $condition, $message ) { if ( ! $condition ) { throw new RuntimeException( $message ); } }

require dirname( __DIR__ ) . '/inc/ad-landing/bootstrap.php';
$GLOBALS['request'] = array( 'admin' => false, 'queried' => true, 'page' => true, 'slug' => 'invisalign', 'template' => '' );
$GLOBALS['assets'] = array();
$sentinel = new stdClass();
$original = '<div class="patient-stories__inner">Existing live markup</div>';
foreach ( array(
	array( 'slug' => 'invisalign', 'template' => '' ),
	array( 'slug' => 'about', 'template' => 'ad-landing' ),
	array( 'page' => false ),
	array( 'queried' => false ),
) as $case ) {
	$before = $GLOBALS['request'];
	$GLOBALS['request'] = array_merge( $before, $case );
	check( ! white_oaks_ad_landing_context(), 'Unrelated request opted into landing support.' );
	check( white_oaks_ad_landing_design( $sentinel ) === $sentinel, 'Unrelated design data changed.' );
	check( white_oaks_ad_landing_body_class( array( 'original' ) ) === array( 'original' ), 'Unrelated body classes changed.' );
	check( white_oaks_ad_landing_render( $original, array( 'blockName' => 'white-oaks/patient-stories' ) ) === $original, 'Unrelated renderer changed.' );
	white_oaks_ad_landing_assets();
	white_oaks_ad_landing_reset_design_cache();
	check( array() === $GLOBALS['assets'] && 0 === WP_Theme_JSON_Resolver::$resets, 'Unrelated assets or cache changed.' );
	$GLOBALS['request'] = $before;
}
foreach ( array( 'invisalign', 'wisdom-teeth', 'emergency' ) as $slug ) {
	$GLOBALS['request']['slug'] = $slug;
	$GLOBALS['request']['template'] = 'ad-landing';
	check( white_oaks_ad_landing_context(), 'Approved slug/template did not opt in.' );
	check( in_array( 'white-oaks-ad-landing', white_oaks_ad_landing_body_class( array() ), true ), 'Missing CSS scope class.' );
	white_oaks_ad_landing_assets();
}
$GLOBALS['request']['admin'] = true;
$_GET['post'] = 84025;
check( white_oaks_ad_landing_context(), 'Editor depended on staging page IDs.' );
unset( $_GET['post'] );
check( ! white_oaks_ad_landing_context(), 'Unrelated admin screen opted in.' );
echo "Landing opt-in boundary checks passed.\n";
