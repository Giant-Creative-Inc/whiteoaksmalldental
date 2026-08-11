import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [tailwindcss()],
  build: {
    emptyOutDir: true,
    minify: true,
    outDir: 'blocks/click-to-load-map/build',
    rollupOptions: {
      input: {
        editor: 'blocks/click-to-load-map/editor.css',
        style: 'blocks/click-to-load-map/style.css',
        view: 'blocks/click-to-load-map/view.js',
      },
      output: {
        entryFileNames: '[name].min.js',
        assetFileNames: '[name].min[extname]',
      },
    },
  },
});
