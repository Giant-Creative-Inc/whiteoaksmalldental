import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import { readdirSync } from 'node:fs';
import { basename, extname, resolve } from 'node:path';

const componentDirectory = resolve('assets/css/components');
const componentEntries = Object.fromEntries(
  readdirSync(componentDirectory)
    .filter((file) => extname(file) === '.css')
    .sort()
    .map((file) => [
      `components/${basename(file, '.css')}`,
      resolve(componentDirectory, file),
    ]),
);

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
        ...componentEntries,
      },
      output: {
        assetFileNames: '[name].min[extname]',
      },
    },
  },
});
