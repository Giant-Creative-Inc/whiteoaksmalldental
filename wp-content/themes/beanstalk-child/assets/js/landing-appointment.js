const formPanel = document.getElementById('landing-appointment-form');

if (formPanel) {
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  let observer;
  let animation;

  document.addEventListener('click', (event) => {
    const link = event.target.closest('a[href="#book-appointment"], a[href="#landing-appointment-form"]');
    if (!link || event.defaultPrevented || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

    observer?.disconnect();
    animation?.cancel();
    if (reducedMotion.matches) return;

    observer = new IntersectionObserver(([entry], currentObserver) => {
      if (!entry.isIntersecting) return;
      currentObserver.disconnect();
      if (reducedMotion.matches) return;

      animation = formPanel.animate(
        [0, -4, 4, -2, 2, 0].map((offset) => ({ transform: `translateX(${offset}px)` })),
        { duration: 450, easing: 'ease-out' }
      );
    }, { threshold: 0.2 });
    observer.observe(formPanel);
  });
}

if (formPanel?.closest('.ad-landing-main')?.querySelector('.wisdom-teeth-removal')) {
  formPanel.addEventListener('focusout', (event) => {
    const field = event.target;
    if (field.matches('#gform_3 input[type="tel"]') && !/\d/.test(field.value)) {
      field.value = '';
    }
  });
}
