/** Animate native Details blocks while keeping one answer open per group. */
const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
const groups = new Map();

for (const item of document.querySelectorAll('main details.wp-block-details')) {
 const summary = item.querySelector(':scope > summary');
 if (!summary) continue;
 const container = item.closest('.home-faqs__list') || item.parentElement;
 if (!groups.has(container)) groups.set(container, []);
 groups.get(container).push({ item, summary, animation: null });
}

for (const entries of groups.values()) {
 const state = { target: entries.find(({ item }) => item.open) || null };
 for (const entry of entries) if (entry !== state.target) entry.item.open = false;

 async function animate(entry, opening) {
  const { item } = entry;
  const from = item.getBoundingClientRect().height;
  entry.animation?.cancel();
  entry.animation = null;
  if (reducedMotion.matches || !item.animate) {
   item.open = opening;
   return;
  }
  // Measure both native states rather than assuming answer or summary heights.
  item.open = false;
  const closedHeight = item.getBoundingClientRect().height;
  item.open = true;
  const openHeight = item.getBoundingClientRect().height;
  const animation = item.animate([
   { height: `${from}px`, overflow: 'hidden' },
   { height: `${opening ? openHeight : closedHeight}px`, overflow: 'hidden' },
  ], { duration: 240, easing: 'ease-in-out' });
  entry.animation = animation;
  try { await animation.finished; } catch { return; }
  if (entry.animation !== animation) return;
  item.open = opening;
  entry.animation = null;
 }

 async function select(target) {
  state.target = target;
  await Promise.all(entries.filter(entry => entry !== target && (entry.item.open || entry.animation)).map(entry => animate(entry, false)));
  if (state.target === target && target && (!target.item.open || target.animation)) await animate(target, true);
 }

 for (const entry of entries) {
  entry.summary.addEventListener('click', event => {
   if (event.target.closest('a, button, input, select, textarea')) return;
   event.preventDefault();
   void select(state.target === entry ? null : entry);
  });
 }
}
