import fs from 'node:fs';
import path from 'node:path';

const root = process.cwd();
const output = process.argv[2] || 'qa-results/patterns-static.md';
const patterns = ['beanstalk', 'beanstalk-child']
  .flatMap((theme) => {
    const directory = path.join(root, theme, 'patterns');
    return fs.existsSync(directory)
      ? fs.readdirSync(directory).filter((file) => file.endsWith('.php')).map((file) => path.join(directory, file))
      : [];
  });

const warnings = [];
for (const file of patterns) {
  const source = fs.readFileSync(file, 'utf8');
  const label = path.relative(root, file);
  for (const field of ['Title', 'Slug', 'Categories']) {
    if (!new RegExp(`^\\s*\\*\\s*${field}:\\s*\\S+`, 'mi').test(source)) {
      warnings.push(`${label}: missing ${field} header.`);
    }
  }
  if (!/^\s*\*\s*Keywords:\s*\S+/mi.test(source)) {
    warnings.push(`${label}: missing inserter Keywords header.`);
  }
  if (!source.includes('<!-- wp:')) {
    warnings.push(`${label}: contains no WordPress block markup; content may not be editable.`);
  }
  const rendered = source.replace(/<\?php[\s\S]*?\?>/g, '');
  if (/<(?:h[1-6]|p|a|button)\b/i.test(rendered) && !/<!--\s+wp:(?:heading|paragraph|button|buttons|navigation|site-title)/i.test(rendered)) {
    warnings.push(`${label}: visible text markup is not paired with an editable core text block.`);
  }
}

const lines = [
  '## Pattern reusability — static audit',
  '',
  `Patterns scanned: **${patterns.length}**  `,
  `Warnings: **${warnings.length}**`,
  '',
  ...(warnings.length ? warnings.map((warning) => `- ⚠️ ${warning}`) : ['✅ No static pattern warnings.']),
];
fs.mkdirSync(path.dirname(output), { recursive: true });
fs.writeFileSync(output, `${lines.join('\n')}\n`);
console.log(lines.join('\n'));
