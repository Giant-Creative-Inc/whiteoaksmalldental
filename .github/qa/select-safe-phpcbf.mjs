import fs from 'node:fs';
import path from 'node:path';

const input = process.argv[2] || 'qa-artifacts/qa-standards/phpcs.json';
const outputDir = process.argv[3] || 'qa-safe-fixes';
const allowedSource = /(Whitespace|WhiteSpace|Indent|Spacing|LineEndings|EndFile|OpeningFunctionBrace|ClosingBrace|ScopeIndent)/i;
const allowedTheme = /^beanstalk(?:-child)?\//;

fs.mkdirSync(outputDir, { recursive: true });
const files = new Set();
const sniffs = new Set();

if (fs.existsSync(input)) {
  const report = JSON.parse(fs.readFileSync(input, 'utf8'));
  for (const [reportedFile, details] of Object.entries(report.files || {})) {
    const relative = path.isAbsolute(reportedFile) ? path.relative(process.cwd(), reportedFile) : reportedFile;
    if (!allowedTheme.test(relative) || !fs.existsSync(relative)) continue;
    for (const message of details.messages || []) {
      if (message.fixable && allowedSource.test(message.source || '')) {
        files.add(relative);
        sniffs.add(message.source.split('.').slice(0, 3).join('.'));
      }
    }
  }
}

fs.writeFileSync(path.join(outputDir, 'phpcbf-files.txt'), files.size ? `${[...files].sort().join('\n')}\n` : '');
fs.writeFileSync(path.join(outputDir, 'phpcbf-sniffs.txt'), sniffs.size ? `${[...sniffs].sort().join(',')}\n` : '');
console.log(`Selected ${files.size} file(s) and ${sniffs.size} formatting sniff(s) for PHPCBF.`);
