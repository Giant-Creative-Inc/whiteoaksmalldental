/** Enhance editable first-visit core blocks without changing saved content. */
document.querySelectorAll('.first-visit__steps, .landing-first-visit__steps').forEach((track, instance) => {
  const prefix = track.classList.contains('landing-first-visit__steps') ? 'landing-first-visit' : 'first-visit';
  const slides = Array.from(track.children).filter((el) => el.classList.contains(`${prefix}__step`));
  if (slides.length < 2) return;

  let active = 0;
  const section = track.closest(`.${prefix}`);
  const heading = section?.querySelector(`.${prefix}__heading`);
  track.id ||= `${prefix}-slides-${instance + 1}`;
  track.setAttribute('role', 'region');
  track.setAttribute('aria-roledescription', 'carousel');
  track.setAttribute('aria-label', heading?.textContent.trim() || 'Your first visit');
  track.classList.add(`${prefix}__steps--slider`);

  const controls = document.createElement('div');
  controls.className = `${prefix}__controls`;
  const status = document.createElement('p');
  status.className = `${prefix}__status`;
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
    const previousButton = previous.querySelector('button');
    const nextButton = next.querySelector('button');
    const focusedButton = document.activeElement;
    previousButton.disabled = active === 0;
    nextButton.disabled = active === slides.length - 1;
    if (focusedButton === previousButton && previousButton.disabled) nextButton.focus({ preventScroll: true });
    if (focusedButton === nextButton && nextButton.disabled) previousButton.focus({ preventScroll: true });
    slides.forEach((slide, i) => {
      const inactive = i !== active;
      slide.classList.toggle(`${prefix}__step--inactive`, inactive);
      slide.hidden = inactive;
      slide.inert = inactive;
    });
    status.textContent = `${String(active + 1).padStart(2, '0')} / ${String(slides.length).padStart(2, '0')}`;
  };

  slides.forEach((slide, i) => {
    // Keep the image on the same side between slides to avoid visual jumping.
    if (prefix === "first-visit") slide.classList.remove(`${prefix}__step--reverse`);
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
