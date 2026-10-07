# Asset loading and WP Rocket removal

Implementation source: `Giant-Creative-Inc/beanstalk`, starter child
`inc/assets.php` at commit `3a911fbfc80c7681dde4297df16ab1f009d98bf7`.
This is a child-only adaptation; the installed parent was not updated.

## What the child now owns

- A filterable CSS/classic-JS/module registry with marker, block, and callback matching.
- Matching includes active template parts, synced patterns, and pre-head dynamic blocks.
- Existing frontend asset handles and dependency order are retained.
- Global CSS stays synchronous. The old parent's blanket async stylesheet filter
  is removed through a child hook, without editing the parent.
- Existing homepage-only below-fold styles use the starter's HTML Tag Processor
  implementation with no-JavaScript fallback. Other routes stay synchronous.
- All editor component CSS stays synchronous and supports unsaved insertions.
- Standalone child scripts are minified during the build: 13,167 to 7,136 bytes
  combined, before compression (46% smaller). These are not all loaded on one page.
- Doctors, gallery, contact, form attribution, patient stories, and click-to-load
  map classic scripts use native WordPress defer. Service Tabs remains a module.
- Existing Adobe Fonts loading, resource hints, image loading, gallery image
  activation, attribution configuration, and custom block ownership are retained.
- File timestamps version local child assets, including block metadata assets.

Configuration lives in `inc/assets.php` through these upstream filters:
`beanstalk_child_component_assets`, `beanstalk_child_deferred_script_handles`,
and `beanstalk_child_below_fold_style_handles`.

## Local checks on 2026-10-06

- New PHP and JavaScript syntax checks, focused Vite script build, loader integration
  checks, and `git diff --check` passed.
- The integration test resolves template parts, detects synced patterns, terminates
  cyclic references, checks native defer and minified paths, preserves URL parameters,
  skips missing assets, verifies noscript fallback, and checks editor loading.
- Real browser testing used `?nowprocket=1`; source and DOM showed no Rocket delayed
  script types. This bypass test is not the same as uninstalling the plugin.
- Service tabs, homepage gallery and testimonials, contact clinic tabs, doctors
  active-state initialization, interactive map activation, and core mobile navigation
  including Escape dismissal worked in the in-app browser.
- Gravity Form 1 rendered with its configured attribution script deferred. No form
  submission or email/webhook test was performed.
- Eight of nine published page permalinks returned 200. `/services/invisalign/`
  returned 404 with both the original and new loaders. Existing service work and
  rewrite configuration need separate review; no rewrite flush was performed.
- 22 externally linked child asset URLs observed in the route scan returned 200.
  Additional block CSS can be emitted inline by WordPress.
- Homepage checks at 320, 375, 768, 1023, 1024, 1100, 1200, 1279, 1280, and 1440px
  found no overflow except 3px at 1023/1024. Those two widths have the same overflow
  under the original loader, caused by existing button markup/layout.
- Chrome DevTools and terminal Chrome failed to launch; verification used the
  in-app browser. No Lighthouse score, performance trace, or production metric
  comparison was obtained. Responsive gutters were not separately remeasured.
- Parent files, existing service/schema/footer work, plugin settings, and database
  content were preserved. No commit, push, or deployment occurred.

Run the read-only integration check in the active WordPress runtime:

```sh
wp eval-file wp-content/themes/beanstalk-child/tests/assets-integration.php
```

From the child directory, `npm run build` includes the new JavaScript production
step; `npm run dev:scripts` watches standalone scripts. A focused script build is
`npx vite build -c vite.scripts.config.mjs`.

## Verification after user removal

After the user reported deactivating WP Rocket on 2026-10-06, the local runtime
confirmed that Rocket is not loaded and its plugin directory is absent. The
Rocket and no-cache companion plugins no longer appear in the installed plugin
list. An `advanced-cache.php` drop-in remains; no Rocket references were found
in it, and it was not modified.

Normal local URLs, with no `nowprocket` parameter, passed the asset integration
checks and served all 22 linked child assets successfully. Homepage service tabs,
gallery advancement (02/11), testimonial advancement, and contact clinic tabs
worked in the real browser without console errors. Gravity Form 1 and its
native deferred attribution script loaded. No form submission was made. The
previously observed Invisalign 404 remains in the route scan.

This confirms the checked local frontend behavior without Rocket. Production
caching, CDN state, real form delivery, and performance metrics still require
separate verification in their target environment.

## Remaining WP Rocket boundary

At the initial review, WP Rocket and its separate Disable Page Caching companion
were active. The recorded settings enabled CSS/JS minification, JS defer/delay, image/iframe lazy loading,
and CDN rewriting; unused-CSS removal and async CSS are disabled. The companion
disabled Rocket page-cache generation. Enabled settings alone do not prove
that a CDN is in use or that GridPane caching is active in production.

The child replaces asset selection, child minification, child script defer, and
selected asynchronous CSS. It intentionally does not delay every script until a
visitor interacts, concatenate all assets, rewrite CDN URLs, or provide server/page
caching. WordPress/plugin scripts retain their owners' dependency and loading rules.
WordPress native image lazy loading and the existing child media behavior remain;
third-party embed lazy loading has not been exhaustively audited.

Before removing WP Rocket on staging or production:

1. Back up/export the current plugin settings and verify the hosting rollback.
2. Confirm GridPane page caching, Redis/object-cache state, CDN use, and cache
   invalidation for content edits, forms, and dynamic pages in that environment.
3. Disable WP Rocket and its no-cache companion on staging, purge the appropriate
   caches, and repeat browser/network checks without the bypass query string.
4. Verify Gravity Forms validation, successful submissions, attribution values,
   email/webhook delivery, review widgets, and other third-party embeds.
5. Compare cold mobile and desktop load metrics with and without Rocket before
   deciding whether any third-party assets need targeted changes.
6. Resolve or explicitly accept the existing route/overflow issues independently.
7. Obtain deployment/removal authorization for production, then verify publicly.

## Design implementation inventory

No new blocks, presets, close-token mappings, WordPress layout classes, semantic
component classes, Tailwind utilities via `@apply`, arbitrary utilities, component
stylesheets, or plain CSS declarations were added. Existing styling files were not
rebuilt or changed. New files are PHP loading infrastructure, a script build config,
compiled JavaScript, a read-only integration check, and this documentation.
