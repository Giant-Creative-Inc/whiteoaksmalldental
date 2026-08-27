(() => {
  const sections = document.querySelectorAll('.doctors-section');

  sections.forEach((section) => {
    const links = Array.from(section.querySelectorAll('.doctors-section__index-link'));
    const profiles = links
      .map((link) => section.querySelector(link.hash))
      .filter(Boolean);

    if (!links.length || !profiles.length || !('IntersectionObserver' in window)) {
      return;
    }

    const activate = (id) => {
      links.forEach((link) => {
        if (link.hash === `#${id}`) {
          link.setAttribute('aria-current', 'true');
        } else {
          link.removeAttribute('aria-current');
        }
      });
    };

    const observer = new IntersectionObserver(
      (entries) => {
        const visible = entries
          .filter((entry) => entry.isIntersecting)
          .sort((a, b) => b.intersectionRatio - a.intersectionRatio);

        if (visible[0]) {
          activate(visible[0].target.id);
        }
      },
      {
        rootMargin: '-25% 0px -55% 0px',
        threshold: [0, 0.1, 0.25, 0.5],
      },
    );

    profiles.forEach((profile) => observer.observe(profile));
    activate(profiles[0].id);
  });
})();
