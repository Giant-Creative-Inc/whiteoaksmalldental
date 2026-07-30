import fs from 'node:fs';
import path from 'node:path';

const root = process.cwd();
const output = process.argv[2] || 'qa-results/semantic.md';
const jsonOutput = process.argv[3] || 'qa-results/semantic.json';
const rules = [
  { categories: /faq/i, types: ['FAQPage'] },
  { categories: /testimonial|review/i, types: ['Review', 'AggregateRating'] },
  { categories: /pricing/i, types: ['Product', 'Offer'] },
];
const warnings = [];
let checked = 0;

for (const theme of ['beanstalk', 'beanstalk-child']) {
  const directory = path.join(root, theme, 'patterns');
  if (!fs.existsSync(directory)) continue;
  for (const file of fs.readdirSync(directory).filter((name) => name.endsWith('.php'))) {
    const source = fs.readFileSync(path.join(directory, file), 'utf8');
    const categories = source.match(/^\s*\*\s*Categories:\s*(.+)$/mi)?.[1] || '';
    const rule = rules.find((candidate) => candidate.categories.test(categories));
    if (!rule) continue;
    checked += 1;
    const label = `${theme}/patterns/${file}`;
    if (!/<script[^>]+type=["']application\/ld\+json["']/i.test(source)) {
      warnings.push({ kind: 'schema-missing', file: label, message: `missing JSON-LD for category “${categories}”.` });
      continue;
    }
    const scripts = [...source.matchAll(/<script[^>]+type=["']application\/ld\+json["'][^>]*>([\s\S]*?)<\/script>/gi)];
    let syntaxValid = true;
    for (const script of scripts) {
      try {
        JSON.parse(script[1]);
      } catch (error) {
        syntaxValid = false;
        warnings.push({ kind: 'schema-json-syntax', file: label, message: `invalid JSON-LD syntax: ${error.message}` });
      }
    }
    if (!syntaxValid) continue;
    if (!rule.types.some((type) => new RegExp(`["']@type["']\\s*:\\s*["']${type}["']`).test(source))) {
      warnings.push({ kind: 'schema-type', file: label, message: `JSON-LD does not use expected type ${rule.types.join(' or ')}.` });
    }
    if (!/metadata["']?\s*:\s*\{[^}]*bindings|register_block_bindings_source|wp:binding/i.test(source)) {
      warnings.push({ kind: 'schema-content', file: label, message: 'schema values do not show a Block Bindings source and may duplicate visible text.' });
    }
  }
}

const lines = [
  '## Semantic HTML and schema',
  '',
  'Heading-order and landmark results are reused from the accessibility artifact.',
  '',
  `Schema-relevant patterns scanned: **${checked}**  `,
  `Warnings: **${warnings.length}**`,
  '',
  ...(warnings.length ? warnings.map((warning) => `- ⚠️ ${warning.file}: ${warning.message}`) : ['✅ No schema-pattern warnings.']),
  '',
  '_Hero and unrelated patterns are intentionally not required to provide schema. Page-level schema belongs to the SEO plugin._',
];
fs.mkdirSync(path.dirname(output), { recursive: true });
fs.writeFileSync(output, `${lines.join('\n')}\n`);
fs.writeFileSync(jsonOutput, `${JSON.stringify({ warnings }, null, 2)}\n`);
