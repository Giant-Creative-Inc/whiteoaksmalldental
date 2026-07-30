<?php
/**
 * Title: Hero - Split with Content
 * Slug: beanstalk/hero-split-content
 * Categories: beanstalk-hero
 * Keywords: hero, split, content, media, image, cta
 * Description: Split hero with a heading, supporting text and a button beside an open content area for an image, form, video or any blocks.
 * Viewport Width: 1400
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
			<p>A short supporting sentence that introduces your product or service and encourages the visitor to take the next step.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Get Started</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->

		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center","width":"45%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%">

			<!-- wp:group {"backgroundColor":"light","textColor":"dark","style":{"spacing":{"padding":{"top":"var:preset|spacing|64","bottom":"var:preset|spacing|64","left":"var:preset|spacing|48","right":"var:preset|spacing|48"}}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group has-dark-color has-light-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--64);padding-bottom:var(--wp--preset--spacing--64);padding-left:var(--wp--preset--spacing--48);padding-right:var(--wp--preset--spacing--48)">
				<!-- wp:paragraph {"align":"center"} -->
				<p class="has-text-align-center">Replace this area with your content — an image, a Gravity Forms form, a video, or any blocks.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div></div>
<!-- /wp:cover -->
