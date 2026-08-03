import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [tailwindcss()],
  build: {
    emptyOutDir: true,
    minify: true,
    outDir: 'blocks/patient-stories/build',
    rollupOptions: {
      input: {
        editor: 'blocks/patient-stories/editor.css',
        style: 'blocks/patient-stories/style.css',
        view: 'blocks/patient-stories/view.js',
      },
      output: {
        entryFileNames: '[name].min.js',
        assetFileNames: '[name].min[extname]',
      },
    },
  },
});
