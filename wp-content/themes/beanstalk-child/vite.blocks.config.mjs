import { defineConfig } from 'vite';

export default defineConfig({
  build: {
    emptyOutDir: true,
    minify: true,
    outDir: 'blocks/service-tabs/build',
    rollupOptions: {
      external: ['@wordpress/interactivity'],
      input: {
        editor: 'blocks/service-tabs/editor.css',
        style: 'blocks/service-tabs/style.css',
        view: 'blocks/service-tabs/view.js',
      },
      output: {
        entryFileNames: '[name].min.js',
        assetFileNames: '[name].min[extname]',
      },
    },
  },
});
