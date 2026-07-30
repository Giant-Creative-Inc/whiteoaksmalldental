import fs from 'node:fs';
import path from 'node:path';
import { chromium } from 'playwright';
import AxeBuilder from '@axe-core/playwright';

const qaDir = path.join(process.cwd(), 'qa-results');
const urls = JSON.parse(fs.readFileSync(path.join(qaDir, 'urls.json'), 'utf8'));
const targets = [{ title: 'Front page', url: urls.front }, ...urls.heroes];
const browser = await chromium.launch();
const warnings = [];
const categories = { contrast: 0, focus: 0, landmarks: 0, headings: 0, altText: 0, other: 0 };
for (const target of targets) {
  const context = await browser.newContext();
  const page = await context.newPage();
  await page.goto(target.url, { waitUntil: 'networkidle' });
  const results = await new AxeBuilder({ page }).withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa']).analyze();
  for (const violation of results.violations) {
    const id = violation.id;
    const category = /contrast/.test(id) ? 'contrast' : /focus|tabindex/.test(id) ? 'focus' : /landmark|region|main/.test(id) ? 'landmarks' : /heading/.test(id) ? 'headings' : /image-alt|object-alt|input-image-alt/.test(id) ? 'altText' : 'other';
    categories[category] += violation.nodes.length;
    warnings.push(`${target.title}: ${id} — ${violation.help} (${violation.nodes.length} node(s)).`);
  }
  await context.close();
}
await browser.close();
const lines = ['## Accessibility (WCAG/AODA)', '', `Pages scanned: **${targets.length}**  `, `Warnings: **${warnings.length}**`, '', `- Contrast: ${categories.contrast}`, `- Keyboard focus: ${categories.focus}`, `- Landmarks: ${categories.landmarks}`, `- Heading structure: ${categories.headings}`, `- Alt text: ${categories.altText}`, `- Other WCAG findings: ${categories.other}`, '', ...(warnings.length ? warnings.map((warning) => `- ⚠️ ${warning}`) : ['✅ No axe violations.'])];
fs.writeFileSync(path.join(qaDir, 'accessibility.md'), `${lines.join('\n')}\n`);
fs.writeFileSync(path.join(qaDir, 'accessibility.json'), JSON.stringify({ categories, warnings }, null, 2));
