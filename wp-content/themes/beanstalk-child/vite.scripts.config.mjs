import { defineConfig } from 'vite';
import { readdirSync } from 'node:fs';
import { basename, resolve } from 'node:path';

export default defineConfig({
  build: {
    emptyOutDir: true,
    minify: true,
    outDir: 'assets/js/build',
    rollupOptions: {
      external: ['@wordpress/interactivity'],
      input: Object.fromEntries(
        readdirSync('assets/js')
          .filter((file) => file.endsWith('.js'))
          .map((file) => [basename(file, '.js'), resolve('assets/js', file)]),
      ),
      output: { entryFileNames: '[name].min.js' },
    },
  },
});
