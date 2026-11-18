# Paid-media landing pages

This is an opt-in addition to production `main`, not a merge of staging.

## Activation and isolation

Only Pages with the `ad-landing` template **and** one of these exact slugs opt in: `invisalign`, `wisdom-teeth`, `emergency`. Registering the template does not assign it to existing pages.

- `bootstrap.php` adds a body scope, supplies the approved design settings for that request, binds existing blocks, and enqueues landing-only assets.
- Theme JSON may resolve before the main query, so its in-request static resolver cache is cleared on `wp` only for opted-in landing pages. No options or database content are written.
- `design.json` contains the approved staging settings. Existing production `settings` and `styles` in the root theme.json are unchanged. The root JSON adds template/part metadata only.
- Existing production CSS, JavaScript, header/footer parts, and shared renderers remain byte-identical. On opted-in landing requests only, legacy child/component styles are replaced by the isolated bundle so old `!important` logo rules cannot compete. The normal parent/core/block styles still load.
- The landing stylesheet is compiled from the approved child source sections and their shared presentation dependencies. The build prefixes every non-keyframe selector with `body.white-oaks-ad-landing`. It never rebuilds existing production CSS.
- Gallery, first-visit, appointment, and FAQ enhancements live in `assets/js/landing/` and load only on opted-in pages. Emergency keeps its native named Details grouping; the other two retain the approved animated accordion.
- Runtime code and selectors do not depend on staging page or Gravity Forms IDs. The review-summary font is bound by the scoped renderer, not by changes to the shared patient-stories block.

## Build and checks

Run from this child theme with the project Node version:

```sh
npm ci
npm run build:landing
npm run test:landing
php -l inc/ad-landing/bootstrap.php
```

The build emits only `assets/css/landing/build/landing.min.css`. Tailwind Preflight stays disabled. PostCSS is an explicit build dependency. The two supplied SVGs are exact existing landing assets; their public theme-relative URL is preserved.

The sources reuse the previously reviewed native-block contracts, presets, semantic classes, Tailwind @apply rules, and existing presentation declarations. They do not introduce new visual values beyond the approved staging design. This entry is separate from the existing production Tailwind entry so its build cannot rewrite unrelated compiled files.

## Validation and limits

Local preview used current main plus this branch, with a temporary request-only theme/content overlay; the active local theme and database were not changed. Approved block snapshots were used for the three landing pages. Some preview imagery was served from staging because media has not yet been transferred.

The three pages passed 54 responsive width/overflow/gutter checks from 320 to 1440px; the 375px and 1440px focal typography, prices, and gallery dimensions matched the verified staging values. The homepage and Privacy Policy loaded identical normalized stylesheet rules and measured typography/width/height against untouched main at both reference widths. Absolute vertical offsets on the homepage were not a pixel comparison because lazy-loaded imagery settles asynchronously.

Unit checks enforce opt-in boundaries, unchanged callbacks for unrelated pages, portable form selectors, and complete compiled CSS scoping. Both JSON files passed the official WordPress theme.json schema. PHP and JavaScript syntax checks passed. No leads or emails were submitted.

The PR does not migrate page content, uploads, form definitions, form routing, SEO metadata, or saved editor overrides. Production activation and delivery remain separate manual steps. See MANUAL-TRANSFER.md.

## Implementation inventory

- Native blocks: existing editable Group/Row/Columns, Cover/Image, Heading/Paragraph/List, Details, Buttons, template parts, and the existing patient-stories block. No new custom block or page builder.
- Presets: the approved staging palette, font, spacing, layout, and custom-value settings are supplied only to opted-in requests. Existing production presets are not replaced on unrelated requests.
- WordPress classes: normal `wp-block-*`, `has-*-color`, `has-*-background-color`, `has-*-font-size`, native layout classes, and `has-patient-stories-source-font-size`.
- Scope class added: `white-oaks-ad-landing`. Existing semantic landing/hero/pricing/comparison/sedation/removal/first-visit/FAQ/gallery/header/footer classes are retained.
- Tailwind utilities and existing source declarations are retained in the focused files under assets/css/landing/components; the active landing entry imports each file. Their generated selectors receive the body scope at build time.
- Close-token substitutions and new inferred design values: none. No new arbitrary utility or plain-CSS visual exception was authored; existing approved source declarations/utilities were carried into the isolated bundle.
