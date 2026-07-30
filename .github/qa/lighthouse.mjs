import fs from 'node:fs';
import path from 'node:path';
import { spawnSync } from 'node:child_process';

const root = process.cwd();
const qaDir = path.join(root, 'qa-results');
const urls = JSON.parse(fs.readFileSync(path.join(qaDir, 'urls.json'), 'utf8'));
const targets = [urls.front, ...urls.heroes.map((hero) => hero.url)];
fs.rmSync(path.join(root, '.lighthouseci'), { recursive: true, force: true });
const args = ['lhci', 'collect', '--numberOfRuns=1', ...targets.map((url) => `--url=${url}`)];
const run = spawnSync('npx', args, { cwd: root, encoding: 'utf8' });
fs.writeFileSync(path.join(qaDir, 'lighthouse.log'), `${run.stdout}\n${run.stderr}`);
const manifestPath = path.join(root, '.lighthouseci', 'manifest.json');
const manifest = fs.existsSync(manifestPath) ? JSON.parse(fs.readFileSync(manifestPath, 'utf8')) : [];
const reportPaths = manifest.length
  ? manifest.map((item) => item.jsonPath)
  : fs.existsSync(path.join(root, '.lighthouseci'))
    ? fs.readdirSync(path.join(root, '.lighthouseci'))
      .filter((file) => /^lhr-.*\.json$/.test(file))
      .map((file) => path.join(root, '.lighthouseci', file))
    : [];
const warnings = [];
const rows = [];
for (const reportPath of reportPaths) {
  const report = JSON.parse(fs.readFileSync(reportPath, 'utf8'));
  const url = report.finalDisplayedUrl || report.finalUrl || report.requestedUrl || 'Unknown URL';
  const score = Math.round((report.categories.performance?.score || 0) * 100);
  if (score < 80) warnings.push(`${url}: performance ${score}.`);
  rows.push(`| ${url} | ${score}${score < 80 ? ' ⚠️' : ''} |`);
}
if (!reportPaths.length) warnings.push('Lighthouse produced no reports; inspect the uploaded log.');
const lines = ['## Performance (Core Web Vitals)', '', '| URL | Performance score |', '|---|---:|', ...rows, '', `Warnings: **${warnings.length}**`, '', ...(warnings.length ? warnings.map((warning) => `- ⚠️ ${warning}`) : ['✅ All performance scores are at least 80.'])];
fs.writeFileSync(path.join(qaDir, 'performance.md'), `${lines.join('\n')}\n`);
