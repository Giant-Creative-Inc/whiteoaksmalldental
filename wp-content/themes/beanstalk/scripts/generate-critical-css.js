const { mkdir, readFile, writeFile } = require('node:fs/promises');
const { watch } = require('node:fs');
const path = require('node:path');

const themeRoot = path.resolve(__dirname, '..');
const sourcePath = path.join(themeRoot, 'assets/css/critical.css');
const outputDirectory = path.join(themeRoot, 'assets/css/critical');
const templateNames = ['front-page', 'index', 'page', 'page-no-title', 'single'];
const watchMode = process.argv.includes('--watch');

function minifyCss(css) {
  return css
    .replace(/\/\*[\s\S]*?\*\//g, '')
    .replace(/\s+/g, ' ')
    .replace(/\s*([{}:;,])\s*/g, '$1')
    .trim();
}

async function generateCriticalCss() {
  const source = await readFile(sourcePath, 'utf8');
  const output = `${minifyCss(source)}\n`;

  await mkdir(outputDirectory, { recursive: true });
  await Promise.all(
    templateNames.map((templateName) =>
      writeFile(path.join(outputDirectory, `${templateName}.css`), output, 'utf8'),
    ),
  );

  console.log(`Generated critical CSS for ${templateNames.length} templates`);
}

async function main() {
  await generateCriticalCss();

  if (watchMode) {
    console.log('Watching assets/css/critical.css for changes');

    let generationTimer;

    watch(sourcePath, () => {
      clearTimeout(generationTimer);
      generationTimer = setTimeout(() => {
        generateCriticalCss().catch((error) => {
          console.error(error);
          process.exitCode = 1;
        });
      }, 100);
    });
  }
}

main().catch((error) => {
  console.error(error);
  process.exitCode = 1;
});
