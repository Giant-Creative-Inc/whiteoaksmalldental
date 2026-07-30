# Beanstalk Parent Theme

Beanstalk is Giant Creative Inc.'s shared native WordPress block parent theme.
Client branding and site-specific additions belong in a child theme whose
`style.css` contains `Template: beanstalk`.

Repository-wide onboarding, Local setup, GridPane hybrid deployment, GitHub,
QA, and client-theme instructions are documented in the
[root README](../README.md).

## Theme development

From this directory:

```sh
nvm install
nvm use
npm ci
composer install
```

Start the development watchers:

```sh
npm run dev
```

Generate a production build and run the local checks:

```sh
npm run build
npm run qa:local
composer validate --strict
composer lint:php
composer audit --locked
```

`theme.json` is the source of truth for reusable colors, typography, spacing,
line heights, gradients, and layout widths. The build regenerates
`assets/css/theme-tokens.css`, template critical CSS, and the compiled
`assets/css/custom.css`.

Do not edit generated token or critical-CSS files by hand. Do not commit
`node_modules/` or `vendor/`. A production deployment must install Composer's
non-development dependencies so SVG sanitization remains available.

## Theme structure

- `theme.json` — global design tokens and element styles.
- `templates/` — full-site editing templates.
- `parts/` — shared template parts such as the header and footer.
- `patterns/` — reusable shared-library block patterns.
- `inc/` — focused PHP setup, assets, media, styles, and pattern modules.
- `assets/css/tailwind.css` — Vite/Tailwind entry without Preflight.
- `assets/css/custom.css` — compiled production stylesheet.
- `scripts/` — token, critical CSS, and validation utilities.

Keep shared fixes and reusable patterns in Beanstalk. Keep one-client
overrides, branding, and additions in that client's child theme.
