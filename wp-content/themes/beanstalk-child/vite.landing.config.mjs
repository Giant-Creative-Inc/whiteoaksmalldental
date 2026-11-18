import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import postcss from 'postcss';

// This independent entry never regenerates the production site's shared assets.
export default defineConfig({
  plugins: [tailwindcss(), {
    name: 'scope-paid-media-landing-css',
    generateBundle(_options, bundle) {
      for (const asset of Object.values(bundle)) {
        if (asset.type !== 'asset' || !asset.fileName.endsWith('.css')) continue;
        const root = postcss.parse(String(asset.source));
        root.walkRules((rule) => {
          for (let parent = rule.parent; parent; parent = parent.parent) {
            if (parent.type === 'atrule' && /keyframes$/i.test(parent.name)) return;
          }
          rule.selector = postcss.list.comma(rule.selector).map((selector) => {
            const scope = 'body.white-oaks-ad-landing';
            if (/^(?:body|html|:root|:host)(?=[\s.:#[>+~]|$)/.test(selector)) {
              return selector.replace(/^(?:body|html|:root|:host)/, scope);
            }
            if (selector.startsWith('.admin-bar')) return scope + selector;
            return `${scope} ${selector}`;
          }).join(',');
        });
        asset.source = root.toString();
      }
    },
  }],
  build: {
    emptyOutDir: true,
    outDir: 'assets/css/landing/build',
    rollupOptions: {
      input: { landing: 'assets/css/landing/tailwind.css' },
      output: { assetFileNames: '[name].min[extname]' },
    },
  },
});
