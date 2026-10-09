/** Enhance only this section's editable core images. */
for (const section of document.querySelectorAll('.comfortable-care')) {
 const viewport = section.querySelector('.comfortable-care__viewport'), track = section.querySelector('.comfortable-care__track'), control = section.querySelector('.comfortable-care__pause .wp-block-button__link');
 if (!viewport || !track || !control) continue;
 const button = document.createElement('button');
 for (const a of control.attributes) if (a.name !== 'href') button.setAttribute(a.name, a.value);
 button.type = 'button'; button.textContent = 'Pause images'; control.replaceWith(button);
 const images = [...track.children];
 for (let i = images.length - 1; i > 0; i--) { const j = Math.floor(Math.random() * (i + 1)); [images[i], images[j]] = [images[j], images[i]]; }
 for (const image of images) track.append(image);
 // Set responsive hints before URLs so detached copies cannot request viewport-sized sources.
 for (const image of images) {
  const clone = image.cloneNode(false);
  clone.setAttribute('aria-hidden', 'true'); clone.inert = true;
  for (const child of image.childNodes) {
   if (child.nodeType !== 1 || child.tagName !== 'IMG') { clone.append(child.cloneNode(true)); continue; }
   const img = document.createElement('img');
   for (const attr of child.attributes) if (!['src', 'srcset', 'sizes', 'loading', 'alt'].includes(attr.name)) img.setAttribute(attr.name, attr.value);
   img.alt = ''; img.loading = 'eager'; img.sizes = child.sizes.replace(/^auto,\s*/, '');
   if (child.srcset) img.srcset = child.srcset;
   img.src = child.src; clone.append(img);
  }
  track.append(clone);
 }

 const reduced = matchMedia('(prefers-reduced-motion: reduce)');
 let paused = reduced.matches, hovered = false, focused = false, visible = false, offset = 0, distance = 0, last = 0, frame = 0;
 const measure = () => { distance = track.children[images.length].offsetLeft - track.children[0].offsetLeft; offset %= distance || 1; };
 const paint = () => { track.style.transform = `translateX(${-offset}px)`; };
 const tick = now => { if (last && distance) offset = (offset + Math.min(now - last, 50) * 0.025) % distance; last = now; paint(); frame = requestAnimationFrame(tick); };
 const update = () => { cancelAnimationFrame(frame); last = 0; button.textContent = paused ? 'Play images' : 'Pause images'; button.setAttribute('aria-pressed', String(paused)); if (!paused && !hovered && !focused && visible && !document.hidden) frame = requestAnimationFrame(tick); };
 button.addEventListener('click', () => { paused = !paused; update(); });
 viewport.addEventListener('pointerenter', () => { hovered = true; update(); }); viewport.addEventListener('pointerleave', () => { hovered = false; update(); });
 section.addEventListener('focusin', () => { focused = true; update(); }); section.addEventListener('focusout', () => { focused = false; update(); });
 reduced.addEventListener('change', () => { if (reduced.matches) paused = true; update(); }); document.addEventListener('visibilitychange', update);
 new ResizeObserver(() => { measure(); paint(); }).observe(viewport); new IntersectionObserver(([entry]) => { visible = entry.isIntersecting; update(); }).observe(viewport);
 measure(); update();
}
