#!/usr/bin/env node

const fs = require('node:fs');
const path = require('node:path');

const themeRoot = path.resolve(__dirname, '..');
const files = [
  path.join(themeRoot, 'theme.json'),
  path.join(themeRoot, '..', 'beanstalk-child', 'theme.json'),
];

function assertTokenArray(settings, pathParts, file) {
  let value = settings;

  for (const part of pathParts) {
    value = value?.[part];
  }

  if (!Array.isArray(value)) {
    throw new Error(`${file}: settings.${pathParts.join('.')} must be an array`);
  }

  const slugs = new Set();

  for (const token of value) {
    if (!token || typeof token.slug !== 'string' || token.slug === '') {
      throw new Error(`${file}: settings.${pathParts.join('.')} has a token without a slug`);
    }

    if (slugs.has(token.slug)) {
      throw new Error(`${file}: duplicate ${pathParts.join('.')} slug "${token.slug}"`);
    }

    slugs.add(token.slug);
  }
}

for (const file of files) {
  const theme = JSON.parse(fs.readFileSync(file, 'utf8'));

  if (3 !== theme.version) {
    throw new Error(`${file}: theme.json version must be 3`);
  }

  const settings = theme.settings || {};
  assertTokenArray(settings, ['color', 'palette'], file);
  assertTokenArray(settings, ['typography', 'fontFamilies'], file);
  assertTokenArray(settings, ['typography', 'fontSizes'], file);
  assertTokenArray(settings, ['spacing', 'spacingSizes'], file);

  if (!settings.custom?.lineHeight || 'object' !== typeof settings.custom.lineHeight) {
    throw new Error(`${file}: settings.custom.lineHeight must be an object`);
  }

  console.log(`${path.relative(themeRoot, file)}: token structure valid`);
}
