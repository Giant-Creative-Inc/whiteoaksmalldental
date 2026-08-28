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

## Content-aware component CSS

Place reusable section styles in `assets/css/components/<component>.css` and
give the section's outer block the matching semantic class `<component>`.
Vite automatically builds every component source to
`assets/css/build/components/<component>.min.css`. WordPress scans the current
page's saved block markup and loads only the matching component stylesheets.

`home-sections.css` predates the one-component-per-file convention and is
activated by the `meet-dentists`, `home-faqs`, or `footer-cta` classes. New
components should use one root class and one matching file instead of extending
that compatibility group.

The header, footer, global buttons, and navigation remain in `shared.css`.
Custom blocks continue declaring their own frontend styles through `block.json`.
WP Rocket may combine the selected stylesheets into one cached file per page.

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
