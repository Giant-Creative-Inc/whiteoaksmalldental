<?php
/**
 * Title: Hero - Centered
 * Slug: beanstalk/hero-centered
 * Categories: beanstalk-hero
 * Keywords: hero, banner, cover, cta
 * Description: Centered hero with an eyebrow, heading, subheading and call-to-action over a full-width Cover.
 * Viewport Width: 1400
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
		<p class="has-text-align-center">A short supporting sentence that introduces your product or service and encourages the visitor to take the next step.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
		<div class="wp-block-buttons">
			<!-- wp:button -->
			<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Get Started</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->

	</div>
	<!-- /wp:group -->

</div></div>
<!-- /wp:cover -->
