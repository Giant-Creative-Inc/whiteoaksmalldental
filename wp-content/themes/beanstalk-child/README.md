# White Oaks Mall Dental child theme

Client-specific WordPress blocks, design tokens, and Tailwind utilities belong
in this child theme. The Beanstalk parent is an immutable dependency.

## Tailwind development

Install dependencies and start the child-theme watcher:

```sh
npm ci
npm run dev
```

Generate the production stylesheet:

```sh
npm run build
```

When editing the Click-to-load Map block's CSS or frontend JavaScript, run its
focused watcher in a second terminal:

```sh
npm run dev:map
```

## Click-to-load Map block

Insert **Click-to-load Map** from the block inserter, then configure its
location name, full address, Google Maps link, button label, and preview image
in the block sidebar. The preview and link remain usable without JavaScript;
on the frontend, a normal click loads the Google Maps iframe in place. The
block's CSS and JavaScript are registered through `block.json`, so WordPress
loads them only on pages where the block is present.

## Conditional CSS and JavaScript

`inc/assets.php` adapts the Beanstalk starter's filterable asset registry to this
client's compiled files and existing handles. It matches saved page markup,
active template parts, synced patterns, and blocks rendered before the document
head. Frontend pages load only matching component assets. Editor component CSS
loads synchronously so newly inserted, unsaved sections are styled immediately.

Global parent CSS, child CSS, and `shared.css` remain synchronous. Only the
existing homepage below-fold stylesheet allowlist uses asynchronous links, with
`noscript` fallbacks. `home-sections.css` retains its established
`meet-dentists`, `home-faqs`, and `footer-cta` marker aliases.

The build compiles every standalone `assets/js/*.js` file into
`assets/js/build/*.min.js`. The component registry loads the doctors, gallery,
and contact scripts only when their semantic markers are present. Attribution
keeps its existing form-specific configuration and handle. Reviewed classic
child scripts use WordPress's native `defer`; Service Tabs remains a WordPress
Interactivity API module. Custom blocks continue owning assets in `block.json`.

Watch standalone JavaScript in a second terminal with `npm run dev:scripts`.
Commit production files under `assets/js/build/` along with the source changes.
See [ASSET-LOADING.md](ASSET-LOADING.md) for the WP Rocket replacement boundary,
local verification, and removal checklist.

## JSON-LD schema

The controlled sitewide Organization, WebSite, and Dentist foundation graph is
stored in `schema/foundation.json`. Administrators can add one complete
page-specific JSON-LD object through the **Custom JSON-LD Schema** panel on any
Page or Post. The object must use `https://schema.org` as `@context` and contain
an `@graph` array.

Valid page-specific schema is rendered server-side in `wp_head`. Rank Math's
JSON-LD is disabled only for an item with a valid managed graph; clearing the
field restores Rank Math output. Invalid JSON is rejected without replacing
the last valid value. Schema values participate in WordPress revisions, and the
panel records the editor, timestamp, and before/after hashes for its latest 50
changes.

## Global horizontal gutters

The site gutter is defined once at `settings.custom.layout.gutter` in
`theme.json` and is also used by the root Global Styles padding. Prefer native
constrained Groups and `.has-global-padding > .alignfull` for ordinary page
sections; they need no component gutter rule. Existing full-width flow wrappers
are covered centrally in `assets/css/shared.css`. If a new flow wrapper cannot
use the native constrained layout, add the semantic `site-gutter` class so the
shared rule can compose `px-site-gutter`. Do not add gutter declarations to
individual component stylesheets or introduce a separate section gutter value
unless the approved design intentionally breaks the global alignment.

Tailwind scans only this child theme's `parts`, `patterns`, `templates`, and
PHP files. Preflight is intentionally disabled to preserve WordPress block
defaults. Commit `assets/css/custom.css`; do not commit `node_modules`.

## Comfortable care section

The General & Family Dentistry pillar (post 499) stores this one-off section as
editable core blocks in post content. Its scoped component is
`assets/css/components/comfortable-care.css`, imported through `tailwind.css`.
`assets/js/comfortable-care.js` shuffles and loops the five original attachment
images, adds inert frontend duplicates, and supports pause, slider-only hover pause,
keyboard-focus pause,
visibility suspension, and reduced motion. The pause control is visually hidden until keyboard focus. No pattern or template stores it.

Build CSS with `npx vite build` and frontend scripts with
`npx vite build -c vite.scripts.config.mjs` from this child theme.
The mobile/desktop switch is 768px; styling values come from child theme presets.

Comfortable-care image `sizes` hints derive from the child tokens (207.45px below
768px, 389px above), adjusted for each source ratio and cover crop. The five small originals load eagerly at normal priority,
avoiding viewport-sized auto hints before CSS layout; loop copies strip the
lazy-only auto prefix. Exact scoped Figma tokens provide 29.7px paragraph leading
and photo heights of 292px / 155.721px. The General & Family Dentistry
CollectionPage links to a Service entity and the existing clinic provider.
