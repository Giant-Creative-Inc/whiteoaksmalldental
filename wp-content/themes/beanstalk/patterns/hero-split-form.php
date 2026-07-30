<?php
/**
 * Title: Hero - Split with Form
 * Slug: beanstalk/hero-split-form
 * Categories: beanstalk-hero
 * Keywords: hero, form, split, gravity forms, cta, lead
 * Description: Split hero with a heading and supporting text beside a Gravity Forms form.
 * Viewport Width: 1400
 *
 * Requires Gravity Forms. The form block ships with no form selected — choose the
 * site's form from the block's dropdown after inserting (form IDs differ per site).
 */
?>
<!-- wp:cover {"overlayColor":"dark","isUserOverlayColor":true,"align":"full","className":"beanstalk-hero","style":{"spacing":{"padding":{"top":"var:preset|spacing|96","bottom":"var:preset|spacing|96"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull beanstalk-hero" style="padding-top:var(--wp--preset--spacing--96);padding-bottom:var(--wp--preset--spacing--96)"><span aria-hidden="true" class="wp-block-cover__background has-dark-background-color has-background-dim-100 has-background-dim"></span><div class="wp-block-cover__inner-container">

	<!-- wp:columns {"verticalAlignment":"center"} -->
	<div class="wp-block-columns are-vertically-aligned-center">

		<!-- wp:column {"verticalAlignment":"center","width":"55%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%">

			<!-- wp:paragraph {"textColor":"primary","className":"is-style-eyebrow"} -->
			<p class="has-primary-color has-text-color is-style-eyebrow">EYEBROW TEXT</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":1} -->
			<h1 class="wp-block-heading">Your Headline Here</h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>A short supporting sentence that introduces your product or service and encourages the visitor to fill out the form.</p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center","width":"45%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%">

			<!-- wp:gravityforms/form /-->

		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div></div>
<!-- /wp:cover -->
