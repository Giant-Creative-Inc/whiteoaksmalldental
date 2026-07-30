import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [tailwindcss()],
  build: {
    emptyOutDir: false,
    outDir: 'assets/css',
    rollupOptions: {
      input: 'assets/css/tailwind.css',
      output: {
        assetFileNames: (assetInfo) =>
          assetInfo.names?.some((name) => name.endsWith('.css'))
            ? 'custom.css'
            : '[name]-[hash][extname]',
      },
    },
  },
});
