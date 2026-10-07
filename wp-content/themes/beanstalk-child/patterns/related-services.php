<?php
/**
 * Title: Related Services
 * Slug: beanstalk-child/related-services
 * Categories: query
 * Inserter: no
 * Description: Three published related services, controlled from service settings.
 */
?>
<!-- wp:group {"align":"full","className":"service-related-section","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|120","bottom":"var:preset|spacing|120"},"blockGap":"var:preset|spacing|64"}}} -->
<div class="wp-block-group alignfull service-related-section" style="padding-top:var(--wp--preset--spacing--120);padding-bottom:var(--wp--preset--spacing--120)">
<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
<div class="wp-block-group">
<!-- wp:group {"layout":{"type":"default"}} --><div class="wp-block-group">
<!-- wp:paragraph {"className":"is-style-eyebrow","fontFamily":"button","textColor":"primary","fontSize":"b-5"} --><p class="is-style-eyebrow has-primary-color has-text-color has-button-font-family has-b-5-font-size">RELATED SERVICES</p><!-- /wp:paragraph -->
<!-- wp:heading {"textColor":"primary-shade-600","fontSize":"experience-heading"} --><h2 class="wp-block-heading has-primary-shade-600-color has-text-color has-experience-heading-font-size">Explore our <em class="has-primary-color">care.</em></h2><!-- /wp:heading -->
</div><!-- /wp:group -->
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button {"className":"is-style-primary"} --><div class="wp-block-button is-style-primary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Book Appointment ↗</a></div><!-- /wp:button --></div><!-- /wp:buttons -->
</div><!-- /wp:group -->
<!-- wp:query {"queryId":194,"namespace":"white-oaks/related-services","query":{"perPage":3,"pages":0,"offset":0,"postType":"service","order":"asc","orderBy":"menu_order","inherit":false,"whiteOaksContext":"white-oaks/related-services"}} -->
<div class="wp-block-query">
<!-- wp:post-template {"layout":{"type":"grid","columnCount":3},"style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} -->
<!-- wp:group {"backgroundColor":"primary-tint-900","layout":{"type":"default"}} --><div class="wp-block-group has-primary-tint-900-background-color has-background">
<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"3/2","sizeSlug":"large"} /-->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|32","right":"var:preset|spacing|32","bottom":"var:preset|spacing|32","left":"var:preset|spacing|32"},"blockGap":"var:preset|spacing|16"}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--32);padding-right:var(--wp--preset--spacing--32);padding-bottom:var(--wp--preset--spacing--32);padding-left:var(--wp--preset--spacing--32)">
<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"h-4","textColor":"primary-shade-600"} /-->
<!-- wp:post-excerpt {"excerptLength":35,"moreText":"EXPLORE NOW →","showMoreOnNewLine":true,"fontSize":"b-5","textColor":"primary-shade-600"} /-->
</div><!-- /wp:group --></div><!-- /wp:group -->
<!-- /wp:post-template -->
</div><!-- /wp:query -->
</div><!-- /wp:group -->
