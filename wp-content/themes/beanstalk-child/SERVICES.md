# Services — local foundation

Implemented on 2026-10-06. Functionality lives in the child theme at the user's explicit request; no client plugin or deployment allowlist change. No parent changes.

## Editor workflow

Services → Add Service opens Gutenberg with independent starter blocks. Assign Service Categories, set an excerpt and featured image, then choose related services in Service settings. The ordered three selectors take precedence; optional fallback fills remaining positions from the primary assigned category. A selected primary category is used only if it is actually assigned to the service. Directory order uses menu_order, then title. Draft/private services never appear in related cards or the directory.

The four category terms were created locally. Category archives are disabled and the CPT has no archive, preserving the existing Services page route. Service permalinks use /services/{slug}/. Rewrite rules are flushed explicitly during setup, never on public requests.

## Draft pilot and saved state

- Original page: 126, /services/invisalign/, unchanged.
- Local draft service: 423, Dental Cleaning, slug dental-cleaning. Updated from Invisalign on 2026-10-06 at the user's request; category General & Family Dentistry.
- Saved footer template part: 290. Its original post/meta were backed up before adding only [white_oaks_service_directory]. The file footer contains the same shortcode; editor overrides are preserved.
- Local rollback/source records: /tmp/white-oaks-service-implementation/ (private temporary directory; not Git/deployment).
- The pilot copies the original editable content and existing approved Media Library assets. Two malformed copied announcement paragraphs were repaired in the pilot, not the source.
- The directory and related section are empty while no eligible services are published. No additional service bodies were generated or published.

## Schema

service-schema.php generates Service, WebPage, BreadcrumbList, optional ImageObject and FAQPage through the existing managed schema output. It supersedes manual JSON-LD only for the new service CPT. Ordinary page/post schema remains unchanged. Schema provider and areaServed reuse the current foundation Dentist entity. Excerpts supply descriptions; featured images supply schema/card imagery. Only Details blocks inside service-faqs or home-faqs__list become FAQ entities. Empty questions/answers are omitted. Preview output remains suppressed by the existing schema output policy; the generator is integration-tested directly.

## Validation

Local WP-CLI must use Local's PHP runtime and active mysqli socket. Run:

    wp eval-file wp-content/themes/beanstalk-child/tests/services-integration.php

The integration test creates fixtures inside a database transaction and rolls them back. It covers CPT/REST registration, routing archive policy, selected related order, self/draft exclusion, category fallback, six source FAQs, excerpt/category schema inputs, valid managed JSON-LD, native Query rendering, directory publication filtering and anonymous draft REST denial.

PHP syntax and git diff --check passed. Gutenberg pilot inspection found no remaining invalid-block warnings and selected single-service as its template.

## Presentation inventory

Blocks: Group, Post Title, Paragraph, Heading, Buttons/Button, Details, Query, Post Template, Post Featured Image, Post Excerpt, Columns/Column, List/List Item and Shortcode. Existing pilot content additionally uses Image and Cover.

Presets: primary, primary-shade-600, primary-tint-900; hero-heading, hero-lead, experience-heading, h-4, h-6, b-5, b-6; button font family; spacing 16, 32, 40, 64, 120. Existing button/eyebrow registered styles are reused.

Close-token mappings in this foundation: related-card #E1EDF0 → primary-tint-900 (#E9F1F3); footer heading 18.72px → h-6 (18px); directory text 14px → b-6 (12px); related image fixed 260px height → native 3:2 aspect ratio. These are preliminary and are not a claim of visual parity.

WordPress classes: wp-block-group, alignfull, wp-block-query, wp-block-columns, wp-block-column, wp-block-heading, wp-block-button, wp-block-button__link, wp-element-button, wp-block-list, generated has-{preset}-color/background-color/font-size/font-family classes and core generated grid/layout classes.

New semantic classes: service-related-section and service-faqs. Reused registered style classes: is-style-primary and is-style-eyebrow.

Tailwind @apply additions: none. Arbitrary utilities: none. Component stylesheets: none. Plain CSS declarations: none. theme.json changes: none. No build was needed.

## Remaining implementation

This is the first foundation milestone, not the finished 25-service Figma implementation.

- Build the complete Figma service starter layout, including Cover hero, treatment introduction and responsive variants, using the exact supplied assets.
- Finalise related-card/footer typography, borders, numbered headings, 260px image slot and mobile layout. Any CSS exceptions require the documented approval process.
- Position related services before the appointment CTA in the final content/template architecture (the foundation template currently appends the section after Post Content).
- Reconcile visible service breadcrumbs with generated schema and the current noindex Services parent policy.
- Implement a CPT-driven OfferCatalog transition without silently deleting the existing manual location catalog.
- Review schema IDs and clinic settings before future Multisite work; no network conversion occurred.
- Execute the full responsive/visual acceptance suite at 320, 375, 768, 1023, 1024, 1100, 1200, 1279, 1280 and 1440px, including long card names and the populated footer.
- Review pilot, then migrate page 126 with its SEO metadata and URL ownership preserved. The original published page still owns /services/invisalign/.
- Populate remaining services with approved, service-specific content and images. Publishing, Git delivery and deployment remain separate actions.

## Dental Cleaning draft update

Figma source: Dental Cleaning desktop 989:159. Updated the draft title, slug, excerpt, service type, primary category, hero, introduction, benefits, five treatment steps, six FAQs and appointment CTA. Imported exact Figma hero/introduction photos as 95-quality JPGs (attachments 445 and 446); other retained process/clinic/CTA photographs already use JPG. SVG icons remain vectors. The published Invisalign source page was not edited.

The source Figma contains two pricing [PLACEHOLDER] answers; they remain in this unpublished draft and require approved fees before publication. The schema generator follows the six updated FAQ blocks and the new service inputs. Gutenberg inspection found no invalid-block warnings. This content update does not claim completed Figma visual parity.

Added native Media & Text and Group blocks using existing spacing 64 and typography/colour presets. Retained existing service component classes, including historical invisalign-* names as presentation hooks. New semantic classes, close-token mappings, Tailwind utilities, arbitrary utilities, component stylesheets and plain CSS declarations: none.

## About this Service — scoped implementation and QA (2026-10-06)

Target: Dental Cleaning draft service 423, immediately after the hero and before the benefits section. Only its introduction block was replaced; parsed block equality confirms every other top-level block is unchanged. The original Invisalign page is unchanged. No pattern was created, registered, saved or edited. No template changes were needed for this section.

Core blocks: Group, Paragraph, Heading (H2), Buttons, Button and Image. Text, emphasis, link, alt text and Media Library image remain editable. No Custom HTML, shortcode, custom/third-party block, React or website JavaScript was introduced. The user explicitly approved scoped CSS for this section.

Styles: existing Section, Eyebrow and Primary Button; registered Container, Content Stack and Actions as native block styles, with their declarations scoped under .service-about. Registration exposes labels in the editor; no unscoped layout rules were added.

Files changed for this section: theme.json; functions.php (one require); inc/service-about.php; assets/css/tailwind.css; assets/css/components/service-about.css; generated assets/css/build/custom.min.css and assets/css/build/components/service-about.min.css; this report. Existing unrelated asset-loading and service work was preserved. The parent was not modified or rebuilt.

Asset: existing 4096×2730 staged JPG at ../../../../../01-images/service-introduction/dental-cleaning-introduction.jpg, imported attachment 446, /wp-content/uploads/2026/10/dental-cleaning-introduction-scaled.jpg. Native image output includes width/height, srcset and sizes. Meaningful alt: A dental hygienist providing gentle dental cleaning. No new asset import was needed. Mobile buried hero alternatives and the duplicate top cleaning image are excluded; only the visible cleaning photograph is used.

Presets/aliases: experience-background, primary, primary-shade-600; heading/body/button families; experience-heading, b-3, b-5 and new service-about-copy-mobile (15px); spacing 0/12/24/32/48/64/128; root layout gutter. Added serviceAbout custom values source the 734px desktop and 240px mobile image heights, 570.004px heading limit, 32px/36px mobile heading, 22px mobile copy line-height, responsive letter spacing and crop geometry from Figma. Flexible grid ratio 599/1425 keeps columns shrinkable.

Close-token mappings: desktop heading #1B2E33 → primary-shade-600 #0C3035; desktop eyebrow-heading gap 28px → 32px; right inset 130px → 128px; desktop body line-height 29.7px → 30px; desktop paragraph separation 29.7px → 32px; mobile paragraph separation 22px → 24px; mobile gutter 20px → shared root gutter 24px. At 1440px, experience-heading reaches 74.88px/71.136px; it remains fluid at narrower desktop widths. Existing global Button style is retained, rather than redefining global element styling. These are intentional design-system mappings, not pixel-exact parity.

Responsive behavior: text first then image below at <=781px; image left/text right at >=782px. Use a 32px grid gap and shared 24px right inset at 782–1200px to preserve usable copy width, then 64px gap and 128px right inset above 1200px. Desktop and mobile emphasis states match their frames. Full-width imagery is the explicit Figma edge-to-edge exception; container edges are equal 0px. Mobile text gutters are equal 24px. Desktop text padding is intentionally asymmetric because the image bleeds to the edge. No body/global gutters were added.

Measured results (pixels):

| Width | Container L/R | Text inset L/R | Grid gap | Heading stack | Intro stack | Paragraph stack | Content/action separation | Actions gap | Section overflow |
|---:|---|---|---:|---:|---:|---:|---:|---:|---|
| 320 | 0/0 | 24/24 | 0px | 12px | 12px | 24px | 32px | 32px | none |
| 375 | 0/0 | 24/24 | 0px | 12px | 12px | 24px | 32px | 32px | none |
| 768 | 0/0 | 24/24 | 0px | 12px | 12px | 24px | 32px | 32px | none |
| 781 | 0/0 | 24/24 | 0px | 12px | 12px | 24px | 32px | 32px | none |
| 782 | 0/0 | 0/24 | 32px | 32px | 24px | 32px | 48px | 32px | none |
| 783 | 0/0 | 0/24 | 32px | 32px | 24px | 32px | 48px | 32px | none |
| 1023 | 0/0 | 0/24 | 32px | 32px | 24px | 32px | 48px | 32px | none |
| 1024 | 0/0 | 0/24 | 32px | 32px | 24px | 32px | 48px | 32px | none |
| 1100 | 0/0 | 0/24 | 32px | 32px | 24px | 32px | 48px | 32px | none |
| 1199 | 0/0 | 0/24 | 32px | 32px | 24px | 32px | 48px | 32px | none |
| 1200 | 0/0 | 0/24 | 32px | 32px | 24px | 32px | 48px | 32px | none |
| 1201 | 0/0 | 0/128 | 64px | 32px | 24px | 32px | 48px | 32px | none |
| 1279 | 0/0 | 0/128 | 64px | 32px | 24px | 32px | 48px | 32px | none |
| 1280 | 0/0 | 0/128 | 64px | 32px | 24px | 32px | 48px | 32px | none |
| 1440 | 0/0 | 0/128 | 64px | 32px | 24px | 32px | 48px | 32px | none |

Additional breakpoint checks included 781/782/783 and 1199/1200/1201. Required widths 320, 375, 768, 1023, 1024, 1100, 1200, 1279, 1280 and 1440 all passed section overflow checks. Desktop/mobile screenshots and measurement JSON are in ignored qa-artifacts/service-about/. The mobile heading is 32px/36px and copy 15px/22px; the desktop photo is 734px high and the mobile photo is 240px high.

Accessibility: one H2 under the existing H1; no additional main landmark; source order puts editable copy and the appointment link before the meaningful photo. Actual keyboard Tab navigation reached Book Appointment with :focus-visible and a 2px primary-colour outline. Measured contrast: body 13.07:1, eyebrow 4.77:1, button 5.15:1. No captured browser console errors. Gutenberg inspection found no invalid/recovery warnings, confirmed the heading/image and draft status. Child theme.json passed the current WordPress schema; PHP lint, child CSS build and git diff --check passed.

Known unrelated issue: the later opening-list button extends past the viewport at 782–1024px (3.27px at 1024px). It is outside .service-about and was not edited. No claim that the complete service page passes visual parity.

WordPress classes: wp-block-group, wp-block-heading, wp-block-buttons, wp-block-button, wp-block-button__link, wp-element-button, wp-block-image, size-full, wp-image-446, alignfull, alignwide and generated colour/font-family/font-size/layout classes. Registered style classes: is-style-section, is-style-container, is-style-content-stack, is-style-actions, is-style-eyebrow, is-style-primary. Semantic classes: service-about; service-about__container/content/intro/heading-stack/copy/eyebrow/heading/media.

Tailwind utilities are composed only via @apply in the focused stylesheet; none are stored in post content. Utilities include grid, grid-cols-1, grid-cols-service-about, flex, flex-wrap, min-w-0, max-w-site-wide, max-w-service-about-heading, p-0, m-0, gap-0, gap-wp-12/24/32/48/64, py-wp-48, px-site-gutter, pl-0, pr-wp-128, pr-site-gutter, col-start-1/2, row-start-1, self-center/stretch, font-heading/body/button, font-normal/bold, text-b-3/b-5, named service-about text/leading/tracking aliases, uppercase, italic/not-italic, text-primary/primary-shade-600, w-full, h-full, named image-height utilities, overflow-hidden, block, object-cover/center/right, origin-service-about and scale-service-about. Arbitrary utilities: none. Plain CSS property declarations in selectors: none; the approved scoped CSS uses @apply and named WordPress-variable aliases. Tailwind was already installed; no dependencies were added. Preflight remains disabled.

Page-content change is reviewable in qa-artifacts/service-about/section.diff. Post and metadata backup: /tmp/white-oaks-service-about/post-before.json. No commit, push, publication, deployment or network conversion occurred.

## Left-edge image correction (2026-10-06)

The user requested the About image extend left like the hero image extends right. On screens wider than the 1440px content limit, the image previously began at the centred container edge. The native Image block now has Full width alignment, avoiding WordPress's generated auto margins without !important. The scoped desktop figure uses w-auto and -ml-service-about-bleed; the named spacing alias derives the excess viewport width from the WordPress wide-size variable. No new visual preset, arbitrary utility, plain selector declaration, block, pattern, shortcode or JavaScript was added. Existing semantic class service-about__media is retained; WordPress class alignfull is added to the Image. Only this Image's alignment attribute changed in post content.

At 2200px the image left edge changed from 380px to 0px; its right edge stayed 985.30px and heading position stayed 1049.30px. Image left edge was verified at 0px without section overflow at 320, 375, 768, 1023, 1024, 1100, 1200, 1279, 1280, 1440, 1920, 2200 and 2560px. Mobile remains full width beneath the text. Scoped source stylesheet and the two child compiled CSS files were rebuilt; no parent change. Screenshot: qa-artifacts/service-about/wide-left.png. Backup: /tmp/white-oaks-service-about/content-before-left-bleed.html. No publication or Git delivery.

## Related Services draft fallback — October 6

Dental Cleaning draft 423 now includes an editable native Query section in page content, before the final appointment CTA. Query No Results holds Dental Scaling, Zoom Whitening, and Pediatric Dentistry sample cards with JPG media 455–457 and decorative arrow 458. Placeholder actions are plain text, without nonexistent links. Published eligible services replace the entire placeholder group automatically. No service posts were published. Removed the old related-pattern reference from single-service.html; no pattern was created or edited for this section.

Validation: transaction-only fixtures confirmed empty fallback, automatic replacement by published service cards, and unchanged adjacent content; fixtures rolled back and 423 remains draft. Child build and git diff --check passed. Section overflow was zero at 320, 375, 768, 781, 782, 783, 1023, 1024, 1100, 1200, 1279, 1280, and 1440. Images render 260px high. Final desktop evidence: qa-artifacts/related-services/desktop.png. This does not claim complete page parity or editor validation.

Implementation inventory: core Group, Heading, Paragraph, Buttons/Button, Image, Query, Post Template, Featured Image, Post Title, Post Excerpt, Read More, Query No Results. Presets: white, primary, primary-shade-600, primary-tint-900; heading/body/button font families; b-5, experience-heading, new service-card-heading; spacing 8/12/16/24/32/36/48/64/120. Native classes include alignfull/alignwide, preset typography/color classes, is-layout-grid/flex, and core block classes. Semantic classes are related-services and its container/header/heading-stack/eyebrow/heading/query/grid/card/content/media/link/arrow/samples descendants, plus related-services--with-placeholder. Existing Section, Container, Content Stack, Actions, and Primary styles reused.

Scoped stylesheet assets/css/components/related-services.css composes named Tailwind utilities via @apply for grid/flex, responsive columns, widths, margins/padding, gap, image fit, typography, border/background, and focus outline; custom named aliases resolve WordPress variables. Arbitrary utilities: none. Plain CSS declarations added: none outside Tailwind configuration/variant definitions. Author-approved scoped styling uses the existing child build. Close-token mappings: Figma 28px heading-stack gap uses 32px; Figma card gaps 20px mobile/40px desktop use 24px/36px because saved WordPress Global Styles override spacing-20 and spacing-40. Saved Global Styles were preserved. Component asset registration lives in inc/related-services.php. All changes remain local and uncommitted.

### Related Services container correction

Removed the section-specific content-width-plus-gutters alias. The outer Group now uses shared px-site-gutter, and the existing inner Group uses max-w-site-wide, mx-auto, and px-0. This matches adjacent clinic/first-visit content exactly: rendered left/right edges match at 320, 375, 768, 781, 782, 783, 1023, 1024, 1100, 1200, 1279, 1280, 1440, and 1728. At 1440 edges are 24/1416; at 1728 they are 144/1584. No section overflow at any tested width. Child build and diff checks passed. Evidence: qa-artifacts/related-services/gutters-corrected.png. Only related-services.css and its generated child assets changed for this correction; blocks, presets, WordPress classes, and semantic classes are unchanged. Tailwind utility changes: px-site-gutter on outer, max-w-site-wide/px-0/mx-auto on inner. New arbitrary utilities, close-token mappings, plain CSS declarations, and component stylesheets: none.

## Shared footer services — October 6

User explicitly changed target from draft page content to the shared footer and approved scoped styling. Replaced the pre-existing service-directory shortcode immediately after the footer's brand/visit/explore/hours region and before legal text. Updated child parts/footer.html and the active saved footer template part 290, preserving adjacent saved content exactly. Dental Cleaning post 423 remains unchanged and draft. Shared placement verified on both Dental Cleaning preview and homepage. Backups in /tmp/white-oaks-footer; local content diff qa-artifacts/footer-services/saved-footer.diff.

Four native Query Loops use service_category terms 5–8. Each renders published Service titles as links, ordered by menu order; each Query No Results contains editable unlinked Figma service names. A category's placeholders disappear as soon as that category has published records. The category taxonomy is intentionally non-public, so inc/footer-services.php supplies a scoped query_loop_block_query_vars filter to apply its terms (core otherwise skips non-viewable taxonomy filters). This is a documented functionality exception alongside approved styling; no custom block or shortcode was introduced. Existing legacy shortcode registration remains unused by this footer. Categories containing no posts remain visible through placeholders.

Blocks: Group/Row, Paragraph, Heading H2/H3, Query, Post Template, linked Post Title H4, Query No Results, List/List Item. Editable Site Editor fields: heading, labels, numbers, category headings, placeholder lists, query layout; live Service title/URL derives from its source Service record. No patterns created or edited; no Custom HTML, React, third-party blocks, custom blocks, or JavaScript added. No image assets exist in the supplied frames; no downloads/imports required. No duplicated visible Figma layers implemented.

Presets: primary, primary-shade-600, oak-orange-shade-900, inherited primary-tint-900 background; new service-directory-divider palette rgba(31,120,132,0.2); Iowan body/heading and approved Inter button family replacing Figma SF Pro; b-5; spacing 12/16/24/32/64. New custom footerServices typography tokens: title 33.84 desktop/26 mobile; category 18.72 desktop/17 mobile; leading 1.25; title tracking -0.025em; category tracking -0.015em; links 17.4px leading. Existing eyebrow tracking 2.16px reused. Mobile/desktop Figma heading color difference uses existing inherited primary-shade-600 consistently rather than changing adjacent footer colours.

Close mappings: header gap 15 to16; number-heading gap18 to16; heading-list gap22 to24; mobile top27 to32; desktop top30/bottom34 and mobile bottom30 to32. Desktop/mobile lists use 12px token gap with17.4px line height rather than fixed list-height tracks; variable published content can wrap and grow. Inter is the approved font substitution. This is not a claim of pixel-exact Figma parity.

Scoped component assets/css/components/footer-services.css imported via child tailwind.css and registered by inc/footer-services.php, loaded through functions.php. Semantic classes: footer-services and header/eyebrow/title/columns/column/number/category/query/list/service descendants. Native classes: wp-block-group/heading/query/post-template/post-title/query-no-results/list, native layout flex/default. Existing footer wrapper remains unchanged. New component uses named @apply utilities for flex/grid/columns, margins/padding, border placement, type, colours, opacity, wrapping, and focus outline. No arbitrary utilities or plain selector declarations; @theme aliases reference WordPress variables. Tailwind Preflight remains disabled. Prior Container/Content Stack style registrations have scoped implementations; this section therefore uses its own approved semantic styling without claiming those styles provide the new geometry. No Actions block is required.

QA: child CSS build, PHP lint, theme.json schema validation, git diff --check passed. Transaction fixture proved published titles/links render only in assigned category and replace its No Results list; all test records rolled back. Adjacent saved footer content matched before/after after removing only the replacement. Site Editor shows native editable blocks and no invalid/recovery warning. Console errors on preview: none. New linked titles have scoped :focus-visible 2px primary outlines; current placeholder state contains no service links, so keyboard traversal of generated links was not browser-tested. Existing footer navigation focus remains unchanged. Muted list text uses70% opacity on the existing dark palette against the light background; no essential state depends solely on colour.

Responsive measured section gutters: 24px left/right at 320,375,768,781,782,783,1023,1024,1100,1200,1279,1280,1281,1440. Shared footer wrapper controls gutters. Single column through781; four equal flexible columns from782. Column widths: 272 at320,327 at375,720 at768; 243.75 at1023,244 at1024,263 at1100,288 at1200,307.75 at1279,308 at1280,348 at1440. Section overflow zero at all tested widths. Header gap16 and list gap12 are explicit token mappings; number/category gap16, query margin24, header/grid margin32. No Actions gap applies. Desktop and mobile captures: qa-artifacts/footer-services/1440.png and375.png. Full-page pre-existing overflow elsewhere was not changed. No commit, push, deployment, or Service publication performed.

### Footer placeholder links and hover

User requested # destinations for unavailable services. All 25 fallback list items now contain native editable links with href="#", in both child footer.html and saved shared footer 290. Published service destinations remain dynamic permalinks. Existing scoped underline-on-hover rule now applies to these links; browser confirmed :hover and computed underline. Keyboard Tab confirmed visible 2px focus outline on Dental Cleaning. No new blocks, presets, mappings, classes, utilities, stylesheets, CSS declarations, or JavaScript added for this update. Only list item anchor markup changed. Backup /tmp/white-oaks-footer/before-placeholder-links.html; screenshot qa-artifacts/footer-services/hover.png. git diff --check passed.

### Related Services placeholder action links

The three editable Explore Now placeholder paragraphs in Dental Cleaning draft 423 now contain href="#" anchors. Existing dynamic Read More URLs remain unchanged. Scoped related-services.css adds text-primary/no-underline, underline on hover, and outline-2/outline-primary/outline-offset-2 on focus-visible via @apply. No new blocks, presets, close mappings, semantic classes, arbitrary utilities, stylesheets, or plain CSS declarations. Browser confirmed three anchors, computed hover underline, and visible 2px keyboard focus. Child build and git diff --check passed. Draft remains draft. Backup /tmp/white-oaks-related/before-action-links.html; proof qa-artifacts/related-services/linked-actions.png.

### Related image and arrow interaction

Draft 423 native placeholder Image blocks now have editable custom # links; dynamic Featured Image has isLink=true for the Service permalink. Scoped related-services.css adds clipped 5% image zoom with300ms transition and a4px rightward arrow translation on action-row hover/focus-within with200ms transition. Keyboard image links have an inset2px primary focus outline. Reduced-motion removes transitions and resets scale/translation. Browser confirmed three image anchors, hover scale1.05, arrow translate4px, and reduced-motion translate0px. Native blocks/presets/classes unchanged apart from image link attributes/anchors; added named @apply utilities block/w-full/h-full/overflow-hidden, transition-transform/duration-300/duration-200, scale-105/scale-100, translate-x-wp-4/translate-x-0, transition-none and outline utilities in the existing component stylesheet. New arbitrary utilities/plain CSS declarations/close-token mappings/stylesheets: none. Child build and diff check passed; page remains draft. Backup /tmp/white-oaks-related/before-image-links.html. Screenshot qa-artifacts/related-services/image-arrow-hover.png.
