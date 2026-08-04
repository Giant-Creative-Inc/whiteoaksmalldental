import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [tailwindcss()],
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
