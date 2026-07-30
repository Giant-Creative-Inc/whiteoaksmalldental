import fs from 'node:fs';
import path from 'node:path';
import { jsonrepair } from 'jsonrepair';

const reportPath = process.argv[2] || 'qa-artifacts/qa-semantic/semantic.json';
const changedListPath = process.argv[3] || 'qa-safe-fixes/jsonld-files.txt';
const changed = [];
const allowedTheme = /^beanstalk(?:-child)?\/.*\.php$/;

if (fs.existsSync(reportPath)) {
  const report = JSON.parse(fs.readFileSync(reportPath, 'utf8'));
  const files = [...new Set((report.warnings || [])
    .filter((warning) => warning.kind === 'schema-json-syntax' && allowedTheme.test(warning.file || ''))
    .map((warning) => warning.file))];

  for (const file of files) {
    if (!fs.existsSync(file)) continue;
    const source = fs.readFileSync(file, 'utf8');
    let repairedAny = false;
    const repaired = source.replace(
      /(<script[^>]+type=["']application\/ld\+json["'][^>]*>)([\s\S]*?)(<\/script>)/gi,
      (match, open, json, close) => {
        try {
          JSON.parse(json);
          return match;
        } catch {
          const value = JSON.parse(jsonrepair(json));
          repairedAny = true;
          return `${open}${JSON.stringify(value)}${close}`;
        }
      },
    );
    if (repairedAny && repaired !== source) {
      fs.writeFileSync(file, repaired);
      changed.push(file);
    }
  }
}

console.log(changed.length ? `Repaired JSON-LD syntax in: ${changed.join(', ')}` : 'No JSON-LD syntax repairs were needed.');
fs.mkdirSync(path.dirname(changedListPath), { recursive: true });
fs.writeFileSync(changedListPath, changed.length ? `${changed.sort().join('\n')}\n` : '');
