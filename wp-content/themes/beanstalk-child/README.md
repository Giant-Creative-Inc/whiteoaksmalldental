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

Tailwind scans only this child theme's `parts`, `patterns`, `templates`, and
PHP files. Preflight is intentionally disabled to preserve WordPress block
defaults. Commit `assets/css/custom.css`; do not commit `node_modules`.
