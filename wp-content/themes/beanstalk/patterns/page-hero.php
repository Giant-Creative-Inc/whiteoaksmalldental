<?php
/**
 * Title: Page Hero
 * Slug: beanstalk/page-hero
 * Categories: beanstalk-hero
 * Keywords: hero, banner
 * Description: Page-top hero with an eyebrow, heading, supporting text and two calls to action beside a full-height, editable image.
 * Viewport Width: 1400
 */
?>
<!-- wp:media-text {"align":"full","mediaPosition":"right","mediaWidth":40,"verticalAlignment":"center","className":"page-hero"} -->
<div class="wp-block-media-text alignfull has-media-on-the-right is-stacked-on-mobile is-vertically-aligned-center page-hero" style="grid-template-columns:auto 40%">

	<div class="wp-block-media-text__content">

		<!-- wp:group {"className":"is-style-section","style":{"spacing":{"blockGap":"var:preset|spacing|32"}},"layout":{"type":"constrained"},"lock":{"move":true,"remove":true}} -->
		<div class="wp-block-group is-style-section">

			<!-- wp:paragraph {"textColor":"primary","fontSize":"b-6","className":"is-style-eyebrow"} -->
			<p class="has-primary-color has-text-color has-b-6-font-size is-style-eyebrow">Your Eyebrow Here</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":1} -->
			<h1 class="wp-block-heading">Your Headline Here</h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontSize":"b-1"} -->
			<p class="has-b-1-font-size">Add supporting text for this section.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons {"layout":{"type":"flex"}} -->
			<div class="wp-block-buttons">
				<!-- wp:button -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Get Started</a></div>
				<!-- /wp:button -->

				<!-- wp:button {"textColor":"dark","className":"is-style-text"} -->
				<div class="wp-block-button is-style-text"><a class="wp-block-button__link has-dark-color has-text-color wp-element-button">Learn More</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->

		</div>
		<!-- /wp:group -->

	</div>

	<figure class="wp-block-media-text__media"></figure>

</div>
<!-- /wp:media-text -->
