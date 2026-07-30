<?php
/**
 * Title: Hero - Centered with Form
 * Slug: beanstalk/hero-centered-form
 * Categories: beanstalk-hero
 * Keywords: hero, form, gravity forms, cta, lead, centered
 * Description: Centered hero with an eyebrow, heading, supporting text and a Gravity Forms form below.
 * Viewport Width: 1400
 *
 * Requires Gravity Forms. The form block ships with no form selected — choose the
 * site's form from the block's dropdown after inserting (form IDs differ per site).
 */
?>
<!-- wp:cover {"overlayColor":"dark","isUserOverlayColor":true,"align":"full","className":"beanstalk-hero","style":{"spacing":{"padding":{"top":"var:preset|spacing|96","bottom":"var:preset|spacing|96"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull beanstalk-hero" style="padding-top:var(--wp--preset--spacing--96);padding-bottom:var(--wp--preset--spacing--96)"><span aria-hidden="true" class="wp-block-cover__background has-dark-background-color has-background-dim-100 has-background-dim"></span><div class="wp-block-cover__inner-container">

	<!-- wp:group {"layout":{"type":"constrained","contentSize":"820px"}} -->
	<div class="wp-block-group">

		<!-- wp:paragraph {"align":"center","textColor":"primary","className":"is-style-eyebrow"} -->
		<p class="has-text-align-center has-primary-color has-text-color is-style-eyebrow">EYEBROW TEXT</p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"textAlign":"center","level":1} -->
		<h1 class="wp-block-heading has-text-align-center">Your Headline Here</h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center"} -->
		<p class="has-text-align-center">A short supporting sentence that introduces your product or service and encourages the visitor to fill out the form.</p>
		<!-- /wp:paragraph -->

		<!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|32"}}},"layout":{"type":"constrained","contentSize":"560px"}} -->
		<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--32)">
			<!-- wp:gravityforms/form /-->
		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</div></div>
<!-- /wp:cover -->
