import fs from 'node:fs';
import path from 'node:path';
import { chromium, firefox, webkit } from 'playwright';
import pixelmatch from 'pixelmatch';
import { PNG } from 'pngjs';

const mode = process.argv[2] || 'parity';
const root = process.cwd();
const qaDir = path.join(root, 'qa-results');
const urls = JSON.parse(fs.readFileSync(path.join(qaDir, 'urls.json'), 'utf8'));
const artifacts = path.join(qaDir, mode);
fs.mkdirSync(artifacts, { recursive: true });

function crop(png, width, height) {
  const result = new PNG({ width, height });
  PNG.bitblt(png, result, 0, 0, width, height, 0, 0);
  return result;
}

const warnings = [];
if (mode === 'parity') {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });
  await page.goto('http://localhost:8888/wp-login.php');
  await page.getByLabel('Username or Email Address').fill(process.env.WP_ADMIN_USER || 'qa-admin');
  await page.locator('#user_pass').fill(process.env.WP_ADMIN_PASSWORD || 'qa-only-local-password');
  await page.getByRole('button', { name: 'Log In' }).click();
  for (const hero of urls.heroes) {
    await page.goto(`http://localhost:8888/wp-admin/post.php?post=${hero.id}&action=edit`, { waitUntil: 'domcontentloaded' });
    const iframe = page.locator('iframe[name="editor-canvas"]');
    await page.locator('iframe[name="editor-canvas"], .editor-styles-wrapper').first().waitFor({ state: 'visible' });
    const editorRoot = (await iframe.count()) ? iframe.contentFrame().locator('.editor-styles-wrapper') : page.locator('.editor-styles-wrapper');
    await editorRoot.waitFor({ state: 'visible' });
    const editorPath = path.join(artifacts, `${hero.slug}-editor.png`);
    await editorRoot.screenshot({ path: editorPath });
    await page.goto(hero.url, { waitUntil: 'load' });
    const frontendPath = path.join(artifacts, `${hero.slug}-frontend.png`);
    await page.locator('main, .wp-site-blocks').first().screenshot({ path: frontendPath });
    const editor = PNG.sync.read(fs.readFileSync(editorPath));
    const frontend = PNG.sync.read(fs.readFileSync(frontendPath));
    const width = Math.min(editor.width, frontend.width);
    const height = Math.min(editor.height, frontend.height);
    const a = crop(editor, width, height);
    const b = crop(frontend, width, height);
    const diff = new PNG({ width, height });
    const changed = pixelmatch(a.data, b.data, diff.data, width, height, { threshold: 0.1 });
    const percentage = (changed / (width * height)) * 100;
    fs.writeFileSync(path.join(artifacts, `${hero.slug}-diff.png`), PNG.sync.write(diff));
    const side = new PNG({ width: width * 2, height });
    PNG.bitblt(a, side, 0, 0, width, height, 0, 0);
    PNG.bitblt(b, side, 0, 0, width, height, width, 0);
    fs.writeFileSync(path.join(artifacts, `${hero.slug}-side-by-side.png`), PNG.sync.write(side));
    if (percentage > 5) warnings.push(`${hero.title}: ${percentage.toFixed(2)}% pixel difference (threshold 5%).`);
  }
  await browser.close();
  const lines = ['## Pattern editor/front-end parity', '', `Hero patterns compared: **${urls.heroes.length}**  `, `Warnings: **${warnings.length}**`, '', ...(warnings.length ? warnings.map((warning) => `- ⚠️ ${warning}`) : ['✅ No parity threshold warnings.']), '', '_Pixel diffs flag obvious drift but do not replace manual sign-off in wp-admin._'];
  fs.writeFileSync(path.join(qaDir, 'patterns-visual.md'), `${lines.join('\n')}\n`);
} else {
  const engines = { chromium, firefox, webkit };
  const widths = [320, 375, 768, 1024, 1440];
  const targets = [{ slug: 'front-page', url: urls.front }, ...urls.heroes];
  for (const [engineName, engine] of Object.entries(engines)) {
    let browser;
    try {
      browser = await engine.launch();
      for (const width of widths) {
        const page = await browser.newPage({ viewport: { width, height: 1000 } });
        for (const target of targets) {
          await page.goto(target.url, { waitUntil: 'networkidle' });
          await page.screenshot({ path: path.join(artifacts, `${target.slug}-${engineName}-${width}.png`), fullPage: true });
        }
        await page.close();
      }
    } catch (error) {
      warnings.push(`${engineName}: ${error.message}`);
    } finally {
      await browser?.close();
    }
  }
  const lines = ['## Responsive and cross-browser', '', `Screenshots requested: **${targets.length * widths.length * 3}**  `, `Warnings: **${warnings.length}**`, '', ...(warnings.length ? warnings.map((warning) => `- ⚠️ ${warning}`) : ['✅ Chromium, Firefox, and WebKit captures completed.']), '', '_This covers browser-engine differences, not real physical devices. Real-device testing remains manual/future work._'];
  fs.writeFileSync(path.join(qaDir, 'responsive.md'), `${lines.join('\n')}\n`);
}
