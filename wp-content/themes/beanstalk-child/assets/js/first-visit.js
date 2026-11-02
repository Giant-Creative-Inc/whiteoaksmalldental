/** Enhance editable first-visit core blocks without changing saved content. */
document.querySelectorAll('.first-visit__steps').forEach((track, instance) => {
  const slides = Array.from(track.children).filter((el) => el.classList.contains('first-visit__step'));
  if (slides.length < 2) return;

  let active = 0;
  const section = track.closest('.first-visit');
  const heading = section?.querySelector('.first-visit__heading');
  track.id ||= `first-visit-slides-${instance + 1}`;
  track.setAttribute('role', 'region');
  track.setAttribute('aria-roledescription', 'carousel');
  track.setAttribute('aria-label', heading?.textContent.trim() || 'Your first visit');
  track.classList.add('first-visit__steps--slider');

  const controls = document.createElement('div');
  controls.className = 'first-visit__controls';
  const status = document.createElement('p');
  status.className = 'first-visit__status';
  status.setAttribute('role', 'status');
  status.setAttribute('aria-live', 'polite');
  status.setAttribute('aria-atomic', 'true');

  const makeButton = (label, direction) => {
    const wrapper = document.createElement('div');
    wrapper.className = 'wp-block-button is-style-outline';
    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'wp-block-button__link wp-element-button';
    button.textContent = direction < 0 ? '←' : '→';
    button.setAttribute('aria-label', label);
    button.setAttribute('aria-controls', track.id);
    button.addEventListener('click', () => show(active + direction));
    wrapper.append(button);
    return wrapper;
  };

  const show = (index) => {
    active = Math.max(0, Math.min(index, slides.length - 1));
    previous.querySelector('button').disabled = active === 0;
    next.querySelector('button').disabled = active === slides.length - 1;
    slides.forEach((slide, i) => {
      const inactive = i !== active;
      slide.classList.toggle('first-visit__step--inactive', inactive);
      slide.hidden = inactive;
      slide.inert = inactive;
    });
    status.textContent = `${String(active + 1).padStart(2, '0')} / ${String(slides.length).padStart(2, '0')}`;
  };

  slides.forEach((slide, i) => {
    // Keep the image on the same side between slides to avoid visual jumping.
    slide.classList.remove('first-visit__step--reverse');
    slide.setAttribute('role', 'group');
    slide.setAttribute('aria-roledescription', 'slide');
    slide.setAttribute('aria-label', `${i + 1} of ${slides.length}`);
  });
  const previous = makeButton('Previous slide', -1);
  const next = makeButton('Next slide', 1);
  controls.append(previous, status, next);
  track.after(controls);
  const handleKeys = (event) => {
    if (event.target.closest('a, input, textarea, select, [contenteditable="true"]')) return;
    const targets = { ArrowLeft: active - 1, ArrowRight: active + 1, Home: 0, End: slides.length - 1 };
    if (!(event.key in targets)) return;
    event.preventDefault();
    show(targets[event.key]);
  };
  track.addEventListener('keydown', handleKeys);
  controls.addEventListener('keydown', handleKeys);

  let touchStart;
  track.addEventListener('touchstart', (event) => {
    if (event.touches.length !== 1) return;
    touchStart = { x: event.touches[0].clientX, y: event.touches[0].clientY };
  }, { passive: true });
  track.addEventListener('touchend', (event) => {
    if (!touchStart) return;
    const dx = event.changedTouches[0].clientX - touchStart.x;
    const dy = event.changedTouches[0].clientY - touchStart.y;
    if (Math.abs(dx) > 50 && Math.abs(dx) > Math.abs(dy) * 1.5) show(active + (dx < 0 ? 1 : -1));
    touchStart = undefined;
  }, { passive: true });
  track.addEventListener('touchcancel', () => { touchStart = undefined; }, { passive: true });
  show(0);
});
