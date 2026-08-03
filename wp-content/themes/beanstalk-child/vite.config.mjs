import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [tailwindcss()],
  build: {
    emptyOutDir: true,
    outDir: 'assets/css/build',
    rollupOptions: {
      input: {
        custom: 'assets/css/tailwind.css',
        home: 'assets/css/home.css',
        shared: 'assets/css/shared.css',
      },
      output: {
        assetFileNames: '[name].min[extname]',
      },
    },
  },
});
