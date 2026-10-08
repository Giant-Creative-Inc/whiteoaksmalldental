<?php
/**
 * Title: Standard Service
 * Slug: beanstalk-child/service-starter
 * Categories: text
 * Post Types: service
 * Description: Approved Dental Cleaning layout. Replace Lorem ipsum copy, sample images, FAQs and links before publishing. Each insertion is independent.
 * Viewport Width: 1440
 *
 * @package BeanstalkChild
 */

// Keep site URLs current when the theme moves from Local to staging/production.
$service_site_url = untrailingslashit( esc_url( home_url( '/' ) ) );
$service_content = <<<'SERVICE_BLOCKS'
<!-- wp:columns {"verticalAlignment":"center","className":"invisalign-hero","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|24","left":"var:preset|spacing|48"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center invisalign-hero"><!-- wp:column {"verticalAlignment":"center","width":"53.4%","style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:53.4%"><!-- wp:shortcode -->
[rank_math_breadcrumb]
<!-- /wp:shortcode -->

<!-- wp:heading {"level":1,"textColor":"primary-shade-600","fontSize":"service-hero-heading"} -->
<h1 class="wp-block-heading has-primary-shade-600-color has-text-color has-service-hero-heading-font-size">Lorem ipsum dolor sit amet</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"primary-shade-600","fontSize":"hero-lead"} -->
<p class="has-primary-shade-600-color has-text-color has-hero-lead-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|32"}}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-primary"} -->
<div class="wp-block-button is-style-primary"><a class="wp-block-button__link wp-element-button" href="/contact-us/">Book Appointment ↗</a></div>
<!-- /wp:button -->

</div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"46.6%","className":"invisalign-hero__media"} -->
<div class="wp-block-column invisalign-hero__media" style="flex-basis:46.6%"><!-- wp:image {"id":445,"aspectRatio":"3/4","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="https://whiteoaksmalldental.local/wp-content/uploads/2026/10/dental-cleaning-hero-scaled.jpg" alt="A dental professional examining and cleaning a patient’s teeth" class="wp-image-445" style="aspect-ratio:3/4;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:group {"align":"full","className":"is-style-section service-about","backgroundColor":"experience-background","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section service-about has-experience-background-background-color has-background">
<!-- wp:group {"align":"wide","className":"is-style-container service-about__container","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide is-style-container service-about__container">
<!-- wp:group {"className":"is-style-content-stack service-about__content","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-content-stack service-about__content">
<!-- wp:group {"className":"is-style-content-stack service-about__intro","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-content-stack service-about__intro">
<!-- wp:group {"className":"is-style-content-stack service-about__heading-stack","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-content-stack service-about__heading-stack">
<!-- wp:paragraph {"className":"is-style-eyebrow service-about__eyebrow","textColor":"primary","fontSize":"b-5","fontFamily":"button"} -->
<p class="is-style-eyebrow service-about__eyebrow has-primary-color has-text-color has-button-font-family has-b-5-font-size">Lorem ipsum</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"className":"service-about__heading","textColor":"primary-shade-600","fontFamily":"heading"} -->
<h2 class="wp-block-heading service-about__heading has-primary-shade-600-color has-text-color has-heading-font-family">Lorem ipsum dolor sit amet <em>consectetur adipiscing</em> elit sed</h2>
<!-- /wp:heading -->
</div><!-- /wp:group -->
<!-- wp:group {"className":"is-style-content-stack service-about__copy","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-content-stack service-about__copy">
<!-- wp:paragraph {"textColor":"primary-shade-600","fontFamily":"body"} -->
<p class="has-primary-shade-600-color has-text-color has-body-font-family">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet consectetur.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"primary-shade-600","fontFamily":"body"} -->
<p class="has-primary-shade-600-color has-text-color has-body-font-family">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum.</p>
<!-- /wp:paragraph -->
</div><!-- /wp:group -->
</div><!-- /wp:group -->
<!-- wp:buttons {"className":"is-style-actions","layout":{"type":"flex","justifyContent":"left","flexWrap":"wrap"}} -->
<div class="wp-block-buttons is-style-actions">
<!-- wp:button {"className":"is-style-primary"} -->
<div class="wp-block-button is-style-primary"><a class="wp-block-button__link wp-element-button" href="/contact-us/">Book Appointment ↗</a></div>
<!-- /wp:button -->
</div><!-- /wp:buttons -->
</div><!-- /wp:group -->
<!-- wp:image {"id":446,"sizeSlug":"full","linkDestination":"none","className":"service-about__media","align":"full"} -->
<figure class="wp-block-image alignfull size-full service-about__media"><img src="https://whiteoaksmalldental.local/wp-content/uploads/2026/10/dental-cleaning-introduction-scaled.jpg" alt="A dental hygienist providing gentle dental cleaning" class="wp-image-446"/></figure>
<!-- /wp:image -->
</div><!-- /wp:group -->
</div><!-- /wp:group -->

<!-- wp:group {"align":"full","className":"first-visit","backgroundColor":"experience-background","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull first-visit has-experience-background-background-color has-background"><!-- wp:group {"className":"why-invisalign__header"} -->
<div class="wp-block-group why-invisalign__header"><!-- wp:group {"className":"why-invisalign__header-main"} -->
<div class="wp-block-group why-invisalign__header-main"><!-- wp:paragraph {"className":"why-invisalign__eyebrow is-style-eyebrow","textColor":"primary","fontSize":"b-5","fontFamily":"button"} -->
<p class="why-invisalign__eyebrow is-style-eyebrow has-primary-color has-text-color has-b-5-font-size has-button-font-family">Lorem ipsum dolor sit amet</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"why-invisalign__heading","textColor":"primary-shade-600","fontSize":"experience-heading"} -->
<h2 class="wp-block-heading why-invisalign__heading has-primary-shade-600-color has-text-color has-experience-heading-font-size">Lorem ipsum dolor sit <em>amet consectetur</em> adipiscing elit</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"why-invisalign__lead","textColor":"primary-shade-600","fontSize":"b-3"} -->
<p class="why-invisalign__lead has-primary-shade-600-color has-text-color has-b-3-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"why-invisalign__cards"} -->
<div class="wp-block-group why-invisalign__cards"><!-- wp:group {"className":"why-invisalign__card"} -->
<div class="wp-block-group why-invisalign__card"><!-- wp:image {"id":182,"width":"34px","height":"auto","sizeSlug":"full","linkDestination":"none","className":"why-invisalign__icon"} -->
<figure class="wp-block-image size-full is-resized why-invisalign__icon"><img src="https://whiteoaksmalldental.local/wp-content/uploads/2026/08/Direct-insurance-billing-icon.svg" alt="" class="wp-image-182" style="width:34px;height:auto"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"why-invisalign__head"} -->
<div class="wp-block-group why-invisalign__head"><!-- wp:paragraph {"className":"why-invisalign__number","textColor":"primary","fontSize":"b-5"} -->
<p class="why-invisalign__number has-primary-color has-text-color has-b-5-font-size">01</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"why-invisalign__title","textColor":"primary-shade-600","fontSize":"service-benefit-heading"} -->
<h3 class="wp-block-heading has-service-benefit-heading-font-size why-invisalign__title has-primary-shade-600-color has-text-color">Lorem ipsum dolor</h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"why-invisalign__copy","textColor":"primary-shade-600"} -->
<p class="why-invisalign__copy has-primary-shade-600-color has-text-color">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"why-invisalign__card"} -->
<div class="wp-block-group why-invisalign__card"><!-- wp:image {"id":181,"sizeSlug":"full","linkDestination":"none","className":"why-invisalign__icon"} -->
<figure class="wp-block-image size-full why-invisalign__icon"><img src="https://whiteoaksmalldental.local/wp-content/uploads/2026/08/Flexible-payment-options-icon.svg" alt="" class="wp-image-181"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"why-invisalign__head"} -->
<div class="wp-block-group why-invisalign__head"><!-- wp:paragraph {"className":"why-invisalign__number","textColor":"primary","fontSize":"b-5"} -->
<p class="why-invisalign__number has-primary-color has-text-color has-b-5-font-size">02</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"why-invisalign__title","textColor":"primary-shade-600","fontSize":"service-benefit-heading"} -->
<h3 class="wp-block-heading has-service-benefit-heading-font-size why-invisalign__title has-primary-shade-600-color has-text-color">Lorem ipsum dolor</h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"why-invisalign__copy","textColor":"primary-shade-600"} -->
<p class="why-invisalign__copy has-primary-shade-600-color has-text-color">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"why-invisalign__card"} -->
<div class="wp-block-group why-invisalign__card"><!-- wp:image {"id":180,"sizeSlug":"full","linkDestination":"none","className":"why-invisalign__icon"} -->
<figure class="wp-block-image size-full why-invisalign__icon"><img src="https://whiteoaksmalldental.local/wp-content/uploads/2026/08/Transparent-fee-information-icon.svg" alt="" class="wp-image-180"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"why-invisalign__head"} -->
<div class="wp-block-group why-invisalign__head"><!-- wp:paragraph {"className":"why-invisalign__number","textColor":"primary","fontSize":"b-5"} -->
<p class="why-invisalign__number has-primary-color has-text-color has-b-5-font-size">03</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"why-invisalign__title","textColor":"primary-shade-600","fontSize":"service-benefit-heading"} -->
<h3 class="wp-block-heading has-service-benefit-heading-font-size why-invisalign__title has-primary-shade-600-color has-text-color">Lorem ipsum dolor</h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"why-invisalign__copy","textColor":"primary-shade-600"} -->
<p class="why-invisalign__copy has-primary-shade-600-color has-text-color">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"why-invisalign__card"} -->
<div class="wp-block-group why-invisalign__card"><!-- wp:image {"id":179,"sizeSlug":"full","linkDestination":"none","className":"why-invisalign__icon"} -->
<figure class="wp-block-image size-full why-invisalign__icon"><img src="https://whiteoaksmalldental.local/wp-content/uploads/2026/08/Dentist-guided-care-icon.svg" alt="Personalized preventive care icon" class="wp-image-179"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"why-invisalign__head"} -->
<div class="wp-block-group why-invisalign__head"><!-- wp:paragraph {"className":"why-invisalign__number","textColor":"primary","fontSize":"b-5"} -->
<p class="why-invisalign__number has-primary-color has-text-color has-b-5-font-size">04</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"why-invisalign__title","textColor":"primary-shade-600","fontSize":"service-benefit-heading"} -->
<h3 class="wp-block-heading has-service-benefit-heading-font-size why-invisalign__title has-primary-shade-600-color has-text-color">Lorem ipsum dolor</h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"why-invisalign__copy","textColor":"primary-shade-600"} -->
<p class="why-invisalign__copy has-primary-shade-600-color has-text-color">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"first-visit","backgroundColor":"primary-tint-900","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull first-visit has-primary-tint-900-background-color has-background"><!-- wp:columns {"verticalAlignment":"bottom","className":"first-visit__header","style":{"spacing":{"blockGap":"var:preset|spacing|64"}}} -->
<div class="wp-block-columns are-vertically-aligned-bottom first-visit__header"><!-- wp:column {"verticalAlignment":"bottom"} -->
<div class="wp-block-column is-vertically-aligned-bottom"><!-- wp:paragraph {"className":"is-style-eyebrow","textColor":"primary","fontSize":"b-5","fontFamily":"button"} -->
<p class="is-style-eyebrow has-primary-color has-text-color has-b-5-font-size has-button-font-family">Lorem ipsum dolor</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"first-visit__heading","textColor":"primary-shade-600","fontSize":"first-visit-heading"} -->
<h2 class="wp-block-heading first-visit__heading has-primary-shade-600-color has-text-color has-first-visit-heading-font-size">Lorem ipsum<br> dolor sit <em>amet consectetur</em></h2>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"bottom"} -->
<div class="wp-block-column is-vertically-aligned-bottom"><!-- wp:paragraph {"textColor":"primary-shade-600","fontSize":"b-3"} -->
<p class="has-primary-shade-600-color has-text-color has-b-3-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"padding":{"top":"var:preset|spacing|24"}}}} -->
<div class="wp-block-buttons" style="padding-top:var(--wp--preset--spacing--24)"><!-- wp:button {"className":"is-style-text","style":{"dimensions":{"width":"100%"}}} -->
<div class="wp-block-button is-style-text"><a class="wp-block-button__link wp-element-button" href="/contact-us/">Book your consultation ↗</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:group {"className":"first-visit__steps","layout":{"type":"default"}} -->
<div class="wp-block-group first-visit__steps"><!-- wp:group {"className":"first-visit__step","layout":{"type":"default"}} -->
<div class="wp-block-group first-visit__step"><!-- wp:image {"id":155,"sizeSlug":"full","linkDestination":"none","className":"first-visit__media"} -->
<figure class="wp-block-image size-full first-visit__media"><img src="https://whiteoaksmalldental.local/wp-content/uploads/2026/08/oak-dental_services_your-goals.jpg" alt="A dental professional discussing treatment goals with a patient" class="wp-image-155"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"first-visit__content","backgroundColor":"white","layout":{"type":"default"}} -->
<div class="wp-block-group first-visit__content has-white-background-color has-background"><!-- wp:paragraph {"className":"is-style-eyebrow","textColor":"primary","fontSize":"b-6"} -->
<p class="is-style-eyebrow has-primary-color has-text-color has-b-6-font-size">Lorem ipsum</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"first-visit__card-heading","textColor":"primary-shade-600","fontSize":"first-visit-card-heading"} -->
<h3 class="wp-block-heading first-visit__card-heading has-primary-shade-600-color has-text-color has-first-visit-card-heading-font-size">Lorem ipsum dolor sit amet consectetur adipiscing</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"first-visit__copy","textColor":"primary-shade-600","fontSize":"b-5"} -->
<p class="first-visit__copy has-primary-shade-600-color has-text-color has-b-5-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet consectetur adipiscing elit sed.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"first-visit__footer","layout":{"type":"default"}} -->
<div class="wp-block-group first-visit__footer"><!-- wp:paragraph {"textColor":"primary","fontSize":"b-6"} -->
<p class="has-primary-color has-text-color has-b-6-font-size"><em>01 / 05</em></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"first-visit__step first-visit__step\u002d\u002dreverse","layout":{"type":"default"}} -->
<div class="wp-block-group first-visit__step first-visit__step--reverse"><!-- wp:image {"id":151,"sizeSlug":"full","linkDestination":"none","className":"first-visit__media"} -->
<figure class="wp-block-image size-full first-visit__media"><img src="https://whiteoaksmalldental.local/wp-content/uploads/2026/08/oak-dental_services_assessment.jpg" alt="A dentist examining a patient's teeth and bite" class="wp-image-151"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"first-visit__content","backgroundColor":"white","layout":{"type":"default"}} -->
<div class="wp-block-group first-visit__content has-white-background-color has-background"><!-- wp:paragraph {"className":"is-style-eyebrow","textColor":"primary","fontSize":"b-6"} -->
<p class="is-style-eyebrow has-primary-color has-text-color has-b-6-font-size">Lorem ipsum</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"first-visit__card-heading","textColor":"primary-shade-600","fontSize":"first-visit-card-heading"} -->
<h3 class="wp-block-heading first-visit__card-heading has-primary-shade-600-color has-text-color has-first-visit-card-heading-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"first-visit__copy","textColor":"primary-shade-600","fontSize":"b-5"} -->
<p class="first-visit__copy has-primary-shade-600-color has-text-color has-b-5-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet consectetur.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"first-visit__footer","layout":{"type":"default"}} -->
<div class="wp-block-group first-visit__footer"><!-- wp:paragraph {"textColor":"primary","fontSize":"b-6"} -->
<p class="has-primary-color has-text-color has-b-6-font-size"><em>02 / 05</em></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"first-visit__step","layout":{"type":"default"}} -->
<div class="wp-block-group first-visit__step"><!-- wp:image {"id":152,"sizeSlug":"full","linkDestination":"none","className":"first-visit__media"} -->
<figure class="wp-block-image size-full first-visit__media"><img src="https://whiteoaksmalldental.local/wp-content/uploads/2026/08/oak-dental_services_digital-records.jpg" alt="A dental team capturing a digital scan of a patient's smile" class="wp-image-152"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"first-visit__content","backgroundColor":"white","layout":{"type":"default"}} -->
<div class="wp-block-group first-visit__content has-white-background-color has-background"><!-- wp:paragraph {"className":"is-style-eyebrow","textColor":"primary","fontSize":"b-6"} -->
<p class="is-style-eyebrow has-primary-color has-text-color has-b-6-font-size">Lorem ipsum</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"first-visit__card-heading","textColor":"primary-shade-600","fontSize":"first-visit-card-heading"} -->
<h3 class="wp-block-heading first-visit__card-heading has-primary-shade-600-color has-text-color has-first-visit-card-heading-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"first-visit__copy","textColor":"primary-shade-600","fontSize":"b-5"} -->
<p class="first-visit__copy has-primary-shade-600-color has-text-color has-b-5-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"first-visit__footer","layout":{"type":"default"}} -->
<div class="wp-block-group first-visit__footer"><!-- wp:paragraph {"textColor":"primary","fontSize":"b-6"} -->
<p class="has-primary-color has-text-color has-b-6-font-size"><em>03 / 05</em></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"first-visit__step first-visit__step\u002d\u002dreverse","layout":{"type":"default"}} -->
<div class="wp-block-group first-visit__step first-visit__step--reverse"><!-- wp:image {"id":154,"sizeSlug":"full","linkDestination":"none","className":"first-visit__media"} -->
<figure class="wp-block-image size-full first-visit__media"><img src="https://whiteoaksmalldental.local/wp-content/uploads/2026/08/oak-dental_services_treamment-preview.jpg" alt="A dental professional showing a patient a digital treatment preview" class="wp-image-154"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"first-visit__content","backgroundColor":"white","layout":{"type":"default"}} -->
<div class="wp-block-group first-visit__content has-white-background-color has-background"><!-- wp:paragraph {"className":"is-style-eyebrow","textColor":"primary","fontSize":"b-6"} -->
<p class="is-style-eyebrow has-primary-color has-text-color has-b-6-font-size">Lorem ipsum</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"first-visit__card-heading","textColor":"primary-shade-600","fontSize":"first-visit-card-heading"} -->
<h3 class="wp-block-heading first-visit__card-heading has-primary-shade-600-color has-text-color has-first-visit-card-heading-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"first-visit__copy","textColor":"primary-shade-600","fontSize":"b-5"} -->
<p class="first-visit__copy has-primary-shade-600-color has-text-color has-b-5-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"first-visit__footer","layout":{"type":"default"}} -->
<div class="wp-block-group first-visit__footer"><!-- wp:paragraph {"textColor":"primary","fontSize":"b-6"} -->
<p class="has-primary-color has-text-color has-b-6-font-size"><em>04 / 05</em></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"first-visit__step","layout":{"type":"default"}} -->
<div class="wp-block-group first-visit__step"><!-- wp:image {"id":153,"sizeSlug":"full","linkDestination":"none","className":"first-visit__media"} -->
<figure class="wp-block-image size-full first-visit__media"><img src="https://whiteoaksmalldental.local/wp-content/uploads/2026/08/oak-dental_services_next-steps.jpg" alt="A dentist reviewing treatment timing and next steps with a patient" class="wp-image-153"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"first-visit__content","backgroundColor":"white","layout":{"type":"default"}} -->
<div class="wp-block-group first-visit__content has-white-background-color has-background"><!-- wp:paragraph {"className":"is-style-eyebrow","textColor":"primary","fontSize":"b-6"} -->
<p class="is-style-eyebrow has-primary-color has-text-color has-b-6-font-size">Lorem ipsum</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"first-visit__card-heading","textColor":"primary-shade-600","fontSize":"first-visit-card-heading"} -->
<h3 class="wp-block-heading first-visit__card-heading has-primary-shade-600-color has-text-color has-first-visit-card-heading-font-size">Lorem ipsum dolor sit amet consectetur adipiscing</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"first-visit__copy","textColor":"primary-shade-600","fontSize":"b-5"} -->
<p class="first-visit__copy has-primary-shade-600-color has-text-color has-b-5-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"first-visit__footer","layout":{"type":"default"}} -->
<div class="wp-block-group first-visit__footer"><!-- wp:paragraph {"textColor":"primary","fontSize":"b-6"} -->
<p class="has-primary-color has-text-color has-b-6-font-size"><em>05 / 05</em></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"home-faqs","backgroundColor":"experience-background","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull home-faqs has-experience-background-background-color has-background"><!-- wp:group {"align":"wide","className":"home-faqs__inner","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide home-faqs__inner"><!-- wp:group {"className":"home-faqs__intro","layout":{"type":"default"}} -->
<div class="wp-block-group home-faqs__intro"><!-- wp:paragraph {"className":"is-style-eyebrow home-faqs__eyebrow","textColor":"primary","fontSize":"b-6","fontFamily":"button"} -->
<p class="is-style-eyebrow home-faqs__eyebrow has-primary-color has-text-color has-button-font-family has-b-6-font-size"><strong>Lorem ipsum</strong></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"home-faqs__heading","textColor":"primary-shade-600","fontSize":"service-faq-heading","fontFamily":"heading"} -->
<h2 class="wp-block-heading home-faqs__heading has-primary-shade-600-color has-text-color has-heading-font-family has-service-faq-heading-font-size">Lorem ipsum<br><em class="has-primary-color">dolor sit</em></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"home-faqs__copy","textColor":"primary-shade-600","fontSize":"b-5","fontFamily":"body"} -->
<p class="home-faqs__copy has-primary-shade-600-color has-text-color has-body-font-family has-b-5-font-size">Still wondering about something? Call our care team at <a href="tel:+15196866200"><strong>(519) 686-6200</strong></a>.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"home-faqs__list","layout":{"type":"default"}} -->
<div class="wp-block-group home-faqs__list"><!-- wp:details {"className":"home-faqs__item"} -->
<details class="wp-block-details home-faqs__item"><summary><em>01</em><span>Lorem ipsum dolor sit amet consectetur adipiscing elit sed</span><strong aria-hidden="true">+</strong></summary><!-- wp:paragraph {"className":"home-faqs__answer","textColor":"primary-shade-600","fontSize":"b-5","fontFamily":"button"} -->
<p class="home-faqs__answer has-primary-shade-600-color has-text-color has-button-font-family has-b-5-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet consectetur adipiscing elit.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details {"className":"home-faqs__item"} -->
<details class="wp-block-details home-faqs__item"><summary><em>02</em><span>Lorem ipsum dolor sit amet consectetur adipiscing elit</span><strong aria-hidden="true">+</strong></summary><!-- wp:paragraph {"className":"home-faqs__answer","textColor":"primary-shade-600","fontSize":"b-5","fontFamily":"button"} -->
<p class="home-faqs__answer has-primary-shade-600-color has-text-color has-button-font-family has-b-5-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details {"className":"home-faqs__item"} -->
<details class="wp-block-details home-faqs__item"><summary><em>03</em><span>Lorem ipsum dolor sit amet consectetur adipiscing elit</span><strong aria-hidden="true">+</strong></summary><!-- wp:paragraph {"className":"home-faqs__answer","textColor":"primary-shade-600","fontSize":"b-5","fontFamily":"button"} -->
<p class="home-faqs__answer has-primary-shade-600-color has-text-color has-button-font-family has-b-5-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet consectetur.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details {"className":"home-faqs__item"} -->
<details class="wp-block-details home-faqs__item"><summary><em>04</em><span>Lorem ipsum dolor sit amet consectetur adipiscing</span><strong aria-hidden="true">+</strong></summary><!-- wp:paragraph {"className":"home-faqs__answer","textColor":"primary-shade-600","fontSize":"b-5","fontFamily":"button"} -->
<p class="home-faqs__answer has-primary-shade-600-color has-text-color has-button-font-family has-b-5-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet consectetur adipiscing elit.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details {"className":"home-faqs__item"} -->
<details class="wp-block-details home-faqs__item"><summary><em>05</em><span>Lorem ipsum dolor sit amet</span><strong aria-hidden="true">+</strong></summary><!-- wp:paragraph {"className":"home-faqs__answer","textColor":"primary-shade-600","fontSize":"b-5","fontFamily":"button"} -->
<p class="home-faqs__answer has-primary-shade-600-color has-text-color has-button-font-family has-b-5-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet consectetur adipiscing elit sed do.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details {"className":"home-faqs__item"} -->
<details class="wp-block-details home-faqs__item"><summary><em>06</em><span>Lorem ipsum dolor sit amet consectetur adipiscing elit sed do</span><strong aria-hidden="true">+</strong></summary><!-- wp:paragraph {"className":"home-faqs__answer","textColor":"primary-shade-600","fontSize":"b-5","fontFamily":"button"} -->
<p class="home-faqs__answer has-primary-shade-600-color has-text-color has-button-font-family has-b-5-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"experience-section","layout":{"type":"default"},"anchor":"find-a-clinic"} -->
<div class="wp-block-group alignfull experience-section" id="find-a-clinic"><!-- wp:group {"align":"wide","className":"experience-section__inner","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide experience-section__inner"><!-- wp:group {"className":"experience-section__header","layout":{"type":"default"}} -->
<div class="wp-block-group experience-section__header"><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"experience-section__eyebrow"} -->
<p class="experience-section__eyebrow">Lorem ipsum dolor</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"experience-section__title","fontSize":"experience-heading","fontFamily":"heading"} -->
<h2 class="wp-block-heading experience-section__title has-heading-font-family has-experience-heading-font-size">Lorem ipsum dolor sit <em>amet consectetur adipiscing</em></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"experience-section__intro","fontSize":"b-3","fontFamily":"body"} -->
<p class="experience-section__intro has-body-font-family has-b-3-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"experience-section__cards","layout":{"type":"default"}} -->
<div class="wp-block-group experience-section__cards"><!-- wp:group {"className":"experience-card","layout":{"type":"default"}} -->
<div class="wp-block-group experience-card"><!-- wp:cover {"url":"https://whiteoaksmalldental.local/wp-content/uploads/2026/08/White-Oaks-Mall.jpg","id":111,"dimRatio":0,"isUserOverlayColor":true,"isDark":false,"sizeSlug":"full","align":"full","className":"experience-card__media","layout":{"type":"default"}} -->
<div class="wp-block-cover alignfull is-light experience-card__media"><img class="wp-block-cover__image-background wp-image-111 size-full" alt="" src="https://whiteoaksmalldental.local/wp-content/uploads/2026/08/White-Oaks-Mall.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"className":"experience-card__status"} -->
<p class="experience-card__status">Lorem ipsum dolor</p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover -->

<!-- wp:group {"className":"experience-card__content","layout":{"type":"default"}} -->
<div class="wp-block-group experience-card__content"><!-- wp:group {"className":"experience-card__topline","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group experience-card__topline"><!-- wp:paragraph {"className":"experience-card__eyebrow"} -->
<p class="experience-card__eyebrow">Lorem ipsum dolor</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"experience-card__number"} -->
<p class="experience-card__number">01</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:heading {"level":3,"className":"experience-card__title","fontSize":"experience-card-heading","fontFamily":"heading"} -->
<h3 class="wp-block-heading experience-card__title has-heading-font-family has-experience-card-heading-font-size">Lorem ipsum dolor</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"experience-card__copy","fontSize":"experience-card-copy","fontFamily":"body"} -->
<p class="experience-card__copy has-body-font-family has-experience-card-copy-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"experience-card__meta","layout":{"type":"default"}} -->
<div class="wp-block-group experience-card__meta"><!-- wp:paragraph {"fontSize":"b-6","fontFamily":"body"} -->
<p class="has-body-font-family has-b-6-font-size">Lorem ipsum dolor<br>sit amet consectetur adipiscing</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"experience-card__meta-secondary","fontSize":"b-6","fontFamily":"body"} -->
<p class="experience-card__meta-secondary has-body-font-family has-b-6-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed.<br>do eiusmod tempor</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:buttons {"className":"experience-card__cta"} -->
<div class="wp-block-buttons experience-card__cta"><!-- wp:button {"className":"is-style-text"} -->
<div class="wp-block-button is-style-text"><a class="wp-block-button__link wp-element-button" href="https://whiteoaksmalldental.local/contact-us/"><span>Book at this clinic</span><span aria-hidden="true">↗</span></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"experience-card experience-card\u002d\u002dwellness","layout":{"type":"default"}} -->
<div class="wp-block-group experience-card experience-card--wellness"><!-- wp:cover {"url":"https://whiteoaksmalldental.local/wp-content/uploads/2026/08/Oaks-Dental-Wellness.jpg","id":110,"dimRatio":0,"isUserOverlayColor":true,"isDark":false,"sizeSlug":"full","align":"full","className":"experience-card__media","layout":{"type":"default"}} -->
<div class="wp-block-cover alignfull is-light experience-card__media"><img class="wp-block-cover__image-background wp-image-110 size-full" alt="" src="https://whiteoaksmalldental.local/wp-content/uploads/2026/08/Oaks-Dental-Wellness.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"className":"experience-card__status"} -->
<p class="experience-card__status">Lorem ipsum</p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover -->

<!-- wp:group {"className":"experience-card__content","layout":{"type":"default"}} -->
<div class="wp-block-group experience-card__content"><!-- wp:group {"className":"experience-card__topline","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group experience-card__topline"><!-- wp:paragraph {"className":"experience-card__eyebrow"} -->
<p class="experience-card__eyebrow">Lorem ipsum dolor sit amet consectetur</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"experience-card__number"} -->
<p class="experience-card__number">02</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:heading {"level":3,"className":"experience-card__title","fontSize":"experience-card-heading","fontFamily":"heading"} -->
<h3 class="wp-block-heading experience-card__title has-heading-font-family has-experience-card-heading-font-size">Lorem ipsum dolor</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"experience-card__copy","fontSize":"experience-card-copy","fontFamily":"body"} -->
<p class="experience-card__copy has-body-font-family has-experience-card-copy-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"experience-card__meta","layout":{"type":"default"}} -->
<div class="wp-block-group experience-card__meta"><!-- wp:paragraph {"fontSize":"b-6","fontFamily":"body"} -->
<p class="has-body-font-family has-b-6-font-size">Lorem ipsum<br>dolor sit amet consectetur</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"experience-card__meta-secondary","fontSize":"b-6","fontFamily":"body"} -->
<p class="experience-card__meta-secondary has-body-font-family has-b-6-font-size">Lorem ipsum<br>dolor sit amet</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:buttons {"className":"experience-card__cta"} -->
<div class="wp-block-buttons experience-card__cta"><!-- wp:button {"className":"is-style-text"} -->
<div class="wp-block-button is-style-text"><a class="wp-block-button__link wp-element-button" href="/contact-us/"><span>Join the opening list</span><span aria-hidden="true">↗</span></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"is-style-section service-related-section related-services related-services\u002d\u002dwith-placeholder","backgroundColor":"white","layout":{"type":"constrained"},"metadata":{"name":"Related Services"}} -->
<div class="wp-block-group alignfull is-style-section service-related-section related-services related-services--with-placeholder has-white-background-color has-background">
<!-- wp:group {"align":"wide","className":"is-style-container related-services__container","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide is-style-container related-services__container">
<!-- wp:group {"className":"related-services__header","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
<div class="wp-block-group related-services__header">
<!-- wp:group {"className":"is-style-content-stack related-services__heading-stack","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-content-stack related-services__heading-stack">
<!-- wp:paragraph {"className":"is-style-eyebrow related-services__eyebrow","textColor":"primary","fontSize":"b-5","fontFamily":"button"} -->
<p class="is-style-eyebrow related-services__eyebrow has-primary-color has-text-color has-button-font-family has-b-5-font-size">Lorem ipsum</p><!-- /wp:paragraph -->
<!-- wp:heading {"className":"related-services__heading","textColor":"primary-shade-600","fontFamily":"heading"} -->
<h2 class="wp-block-heading related-services__heading has-primary-shade-600-color has-text-color has-heading-font-family">Lorem ipsum <em class="has-primary-color">dolor sit</em></h2><!-- /wp:heading -->
</div><!-- /wp:group -->
<!-- wp:buttons {"className":"is-style-actions"} -->
<div class="wp-block-buttons is-style-actions"><!-- wp:button {"className":"is-style-primary"} -->
<div class="wp-block-button is-style-primary"><a class="wp-block-button__link wp-element-button" href="/contact-us/">Book Appointment ↗</a></div><!-- /wp:button -->
</div><!-- /wp:buttons -->
</div><!-- /wp:group -->
<!-- wp:query {"queryId":423,"namespace":"white-oaks/related-services","className":"related-services__query","query":{"perPage":3,"pages":0,"offset":0,"postType":"service","order":"asc","orderBy":"menu_order","inherit":false,"whiteOaksContext":"white-oaks/related-services"}} -->
<div class="wp-block-query related-services__query">
<!-- wp:post-template {"className":"related-services__grid","layout":{"type":"grid","columnCount":3}} -->
<!-- wp:group {"className":"related-services__card","backgroundColor":"primary-tint-900","textColor":"primary-shade-600","layout":{"type":"default"}} -->
<div class="wp-block-group related-services__card has-primary-shade-600-color has-primary-tint-900-background-color has-text-color has-background">
<!-- wp:post-featured-image {"sizeSlug":"large","className":"related-services__media","isLink":true} /-->
<!-- wp:group {"className":"is-style-content-stack related-services__content","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-content-stack related-services__content">
<!-- wp:post-title {"level":3,"fontSize":"service-card-heading","fontFamily":"heading"} /-->
<!-- wp:post-excerpt {"excerptLength":55,"fontSize":"b-5","fontFamily":"body"} /-->
<!-- wp:group {"className":"related-services__link","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group related-services__link">
<!-- wp:read-more {"content":"EXPLORE NOW","fontSize":"b-5","fontFamily":"button","textColor":"primary"} /-->
<!-- wp:image {"id":458,"sizeSlug":"full","linkDestination":"none","className":"related-services__arrow"} --><figure class="wp-block-image size-full related-services__arrow"><img src="https://whiteoaksmalldental.local/wp-content/uploads/2026/10/explore-arrow.svg" alt="" class="wp-image-458"/></figure><!-- /wp:image -->
</div><!-- /wp:group -->
</div><!-- /wp:group -->
</div><!-- /wp:group -->
<!-- /wp:post-template -->
<!-- wp:query-no-results -->
<!-- wp:group {"className":"related-services__grid related-services__samples","layout":{"type":"grid","columnCount":3},"metadata":{"name":"Placeholder cards — no related services"}} -->
<div class="wp-block-group related-services__grid related-services__samples">
<!-- wp:group {"className":"related-services__card","backgroundColor":"primary-tint-900","textColor":"primary-shade-600","layout":{"type":"default"},"metadata":{"name":"Sample service card"}} -->
<div class="wp-block-group related-services__card has-primary-shade-600-color has-primary-tint-900-background-color has-text-color has-background">
<!-- wp:image {"id":455,"sizeSlug":"full","linkDestination":"custom","className":"related-services__media","href":"#"} --><figure class="wp-block-image size-full related-services__media"><a href="#"><img src="https://whiteoaksmalldental.local/wp-content/uploads/2026/10/dental-scaling-scaled.jpg" alt="A dental professional providing scaling and polishing care" class="wp-image-455"/></a></figure><!-- /wp:image -->
<!-- wp:group {"className":"is-style-content-stack related-services__content","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-content-stack related-services__content">
<!-- wp:heading {"level":3,"fontSize":"service-card-heading","fontFamily":"heading"} -->
<h3 class="wp-block-heading has-heading-font-family has-service-card-heading-font-size">Lorem ipsum</h3><!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"b-5","fontFamily":"body"} -->
<p class="has-body-font-family has-b-5-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem.</p><!-- /wp:paragraph -->
<!-- wp:group {"className":"related-services__link","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group related-services__link"><!-- wp:paragraph {"textColor":"primary","fontSize":"b-5","fontFamily":"button"} -->
<p class="has-primary-color has-text-color has-button-font-family has-b-5-font-size"><a href="#">EXPLORE NOW</a></p><!-- /wp:paragraph -->
<!-- wp:image {"id":458,"sizeSlug":"full","linkDestination":"none","className":"related-services__arrow"} --><figure class="wp-block-image size-full related-services__arrow"><img src="https://whiteoaksmalldental.local/wp-content/uploads/2026/10/explore-arrow.svg" alt="" class="wp-image-458"/></figure><!-- /wp:image -->
</div><!-- /wp:group -->
</div><!-- /wp:group -->
</div><!-- /wp:group --><!-- wp:group {"className":"related-services__card","backgroundColor":"primary-tint-900","textColor":"primary-shade-600","layout":{"type":"default"},"metadata":{"name":"Sample service card"}} -->
<div class="wp-block-group related-services__card has-primary-shade-600-color has-primary-tint-900-background-color has-text-color has-background">
<!-- wp:image {"id":456,"sizeSlug":"full","linkDestination":"custom","className":"related-services__media","href":"#"} --><figure class="wp-block-image size-full related-services__media"><a href="#"><img src="https://whiteoaksmalldental.local/wp-content/uploads/2026/10/zoom-whitening-scaled.jpg" alt="A patient receiving a professional teeth-whitening treatment" class="wp-image-456"/></a></figure><!-- /wp:image -->
<!-- wp:group {"className":"is-style-content-stack related-services__content","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-content-stack related-services__content">
<!-- wp:heading {"level":3,"fontSize":"service-card-heading","fontFamily":"heading"} -->
<h3 class="wp-block-heading has-heading-font-family has-service-card-heading-font-size">Lorem ipsum</h3><!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"b-5","fontFamily":"body"} -->
<p class="has-body-font-family has-b-5-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore.</p><!-- /wp:paragraph -->
<!-- wp:group {"className":"related-services__link","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group related-services__link"><!-- wp:paragraph {"textColor":"primary","fontSize":"b-5","fontFamily":"button"} -->
<p class="has-primary-color has-text-color has-button-font-family has-b-5-font-size"><a href="#">EXPLORE NOW</a></p><!-- /wp:paragraph -->
<!-- wp:image {"id":458,"sizeSlug":"full","linkDestination":"none","className":"related-services__arrow"} --><figure class="wp-block-image size-full related-services__arrow"><img src="https://whiteoaksmalldental.local/wp-content/uploads/2026/10/explore-arrow.svg" alt="" class="wp-image-458"/></figure><!-- /wp:image -->
</div><!-- /wp:group -->
</div><!-- /wp:group -->
</div><!-- /wp:group --><!-- wp:group {"className":"related-services__card","backgroundColor":"primary-tint-900","textColor":"primary-shade-600","layout":{"type":"default"},"metadata":{"name":"Sample service card"}} -->
<div class="wp-block-group related-services__card has-primary-shade-600-color has-primary-tint-900-background-color has-text-color has-background">
<!-- wp:image {"id":457,"sizeSlug":"full","linkDestination":"custom","className":"related-services__media","href":"#"} --><figure class="wp-block-image size-full related-services__media"><a href="#"><img src="https://whiteoaksmalldental.local/wp-content/uploads/2026/10/pediatric-dentistry-scaled.jpg" alt="A child smiling during a dental visit" class="wp-image-457"/></a></figure><!-- /wp:image -->
<!-- wp:group {"className":"is-style-content-stack related-services__content","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-content-stack related-services__content">
<!-- wp:heading {"level":3,"fontSize":"service-card-heading","fontFamily":"heading"} -->
<h3 class="wp-block-heading has-heading-font-family has-service-card-heading-font-size">Lorem ipsum</h3><!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"b-5","fontFamily":"body"} -->
<p class="has-body-font-family has-b-5-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p><!-- /wp:paragraph -->
<!-- wp:group {"className":"related-services__link","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group related-services__link"><!-- wp:paragraph {"textColor":"primary","fontSize":"b-5","fontFamily":"button"} -->
<p class="has-primary-color has-text-color has-button-font-family has-b-5-font-size"><a href="#">EXPLORE NOW</a></p><!-- /wp:paragraph -->
<!-- wp:image {"id":458,"sizeSlug":"full","linkDestination":"none","className":"related-services__arrow"} --><figure class="wp-block-image size-full related-services__arrow"><img src="https://whiteoaksmalldental.local/wp-content/uploads/2026/10/explore-arrow.svg" alt="" class="wp-image-458"/></figure><!-- /wp:image -->
</div><!-- /wp:group -->
</div><!-- /wp:group -->
</div><!-- /wp:group -->
</div><!-- /wp:group -->
<!-- /wp:query-no-results -->
</div><!-- /wp:query -->
</div><!-- /wp:group -->
</div><!-- /wp:group --><!-- wp:cover {"url":"https://whiteoaksmalldental.local/wp-content/uploads/2026/08/cta-bg.jpg","id":75,"dimRatio":0,"isUserOverlayColor":true,"lock":{"move":false,"remove":false},"align":"full","className":"is-style-content-height footer-cta","layout":{"type":"default"}} -->
<div class="wp-block-cover alignfull is-style-content-height footer-cta"><img class="wp-block-cover__image-background wp-image-75" alt="" src="https://whiteoaksmalldental.local/wp-content/uploads/2026/08/cta-bg.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"className":"footer-cta__content is-layout-flex wp-block-group-is-layout-flex","layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group footer-cta__content is-layout-flex wp-block-group-is-layout-flex"><!-- wp:heading {"className":"footer-cta__heading","textColor":"primary-shade-600","fontSize":"hero-heading","fontFamily":"heading"} -->
<h2 class="wp-block-heading footer-cta__heading has-primary-shade-600-color has-text-color has-heading-font-family has-hero-heading-font-size">Lorem ipsum dolor sit<br>amet consectetur adipiscing</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"footer-cta__copy","textColor":"primary-shade-600","fontSize":"emergency-copy","fontFamily":"body"} -->
<p class="footer-cta__copy has-primary-shade-600-color has-text-color has-body-font-family has-emergency-copy-font-size">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"footer-cta__buttons is-layout-flex wp-block-buttons-is-layout-flex","layout":{"type":"flex","justifyContent":"left","orientation":"horizontal","flexWrap":"wrap"}} -->
<div class="wp-block-buttons footer-cta__buttons is-layout-flex wp-block-buttons-is-layout-flex"><!-- wp:button {"className":"is-style-primary footer-cta__primary"} -->
<div class="wp-block-button is-style-primary footer-cta__primary"><a class="wp-block-button__link wp-element-button" href="/contact-us/">Book Appointment ↗</a></div>
<!-- /wp:button -->

<!-- wp:button {"textColor":"button-text","className":"is-style-primary footer-cta__phone","backgroundColor":"white"} -->
<div class="wp-block-button is-style-primary footer-cta__phone"><a class="wp-block-button__link has-white-background-color has-background has-button-text-color has-text-color wp-element-button" href="tel:+15196866200">Call (519) 686-6200 ↗</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->


SERVICE_BLOCKS;

echo str_replace(
	array( 'https://whiteoaksmalldental.local', 'https:\/\/whiteoaksmalldental.local' ),
	array( $service_site_url, str_replace( '/', '\/', $service_site_url ) ),
	$service_content
);
