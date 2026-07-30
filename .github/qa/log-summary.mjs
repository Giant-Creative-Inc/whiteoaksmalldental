import fs from 'node:fs';
import path from 'node:path';

const [title, logPath, outputPath] = process.argv.slice(2);
const log = fs.existsSync(logPath) ? fs.readFileSync(logPath, 'utf8').trim() : 'No tool output was produced.';
const warningLines = log.split('\n').filter((line) => /warning|error|invalid|violation/i.test(line)).length;
const markdown = [`## ${title}`, '', `Potential warning lines: **${warningLines}**`, '', '<details><summary>Tool output</summary>', '', '```text', log.slice(0, 50000), '```', '', '</details>', ''].join('\n');
fs.mkdirSync(path.dirname(outputPath), { recursive: true });
fs.writeFileSync(outputPath, markdown);
