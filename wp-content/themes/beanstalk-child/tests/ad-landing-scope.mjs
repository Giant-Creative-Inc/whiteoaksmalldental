import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import postcss from 'postcss';

const css = readFileSync(new URL('../assets/css/landing/build/landing.min.css', import.meta.url), 'utf8');
let selectors = 0;
postcss.parse(css).walkRules((rule) => {
  for (let parent = rule.parent; parent; parent = parent.parent) {
    if (parent.type === 'atrule' && /keyframes$/i.test(parent.name)) return;
  }
  for (const selector of postcss.list.comma(rule.selector)) {
    assert.ok(selector.startsWith('body.white-oaks-ad-landing'), `Unscoped landing CSS: ${selector}`);
    selectors += 1;
  }
});
assert.ok(selectors > 100, 'Compiled landing stylesheet is missing.');
assert.ok(!/#gform_(?:2|3)\b|#gform_wrapper_(?:2|3)\b/.test(css), 'CSS depends on staging form IDs.');
const appointment = readFileSync(new URL('../assets/js/landing/landing-appointment.js', import.meta.url), 'utf8');
assert.ok(!/#gform_3\b/.test(appointment), 'Appointment behavior depends on a staging form ID.');
console.log(`All ${selectors} compiled landing selectors are scoped; form IDs are portable.`);
