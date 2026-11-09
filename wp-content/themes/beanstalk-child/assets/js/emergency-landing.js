const hero = document.querySelector('#emergency-hero');
const bar = document.querySelector('#emergency-call-bar');

if (hero && bar) {
  const dayFormat = new Intl.DateTimeFormat('en-CA', {
    weekday: 'long',
    timeZone: 'America/Toronto',
  });
  const schedule = Array.from(document.querySelectorAll('#view-hours .location-info__hours-row'), (row) => ({
    day: row.firstElementChild?.textContent.trim(),
    hours: row.lastElementChild?.textContent.trim(),
  })).filter(({ day, hours }) => day && hours);
  const hoursLabels = document.querySelectorAll('.emergency-today-hours');
  const cardLabel = hero.querySelector('.emergency-card-hours__label');
  const stickyLabel = bar.querySelector('.is-style-eyebrow');
  const weeklySummary = hero.querySelector('.emergency-weekly-hours');

  const formatHours = (hours) => hours.replace(/:00\b/g, '').replace(/\b[AP]M\b/g, (period) => period.toLowerCase());
  const groups = [];
  schedule.forEach(({ day, hours }) => {
    const last = groups.at(-1);
    if (last?.hours === hours) {
      last.end = day;
    } else {
      groups.push({ start: day, end: day, hours });
    }
  });
  if (weeklySummary && groups.length) {
    weeklySummary.textContent = groups.map(({ start, end, hours }) => {
      const day = start === end ? start.slice(0, 3) : `${start.slice(0, 3)}–${end.slice(0, 3)}`;
      const compactHours = formatHours(hours).toLowerCase()
        .replace(/^(\d{1,2}(?::\d{2})?) am\s*[–-]\s*(\d{1,2}(?::\d{2})?) pm$/, '$1–$2');
      return `${day} ${compactHours}`;
    }).join(' · ');
  }

  const updateHours = () => {
    const today = dayFormat.format(new Date());
    const { hours } = schedule.find(({ day }) => day === today) ?? {};
    if (!hours) return;

    hoursLabels.forEach((label) => {
      label.textContent = hero.contains(label) ? hours.replace(/\b[AP]M\b/g, (period) => period.toLowerCase()).replace(/\s*–\s*/g, ' – ') : formatHours(hours).replace(/\s*–\s*/g, ' – ');
    });
    const label = /^closed$/i.test(hours) ? 'Today’s hours' : 'Open today';
    if (cardLabel) cardLabel.textContent = label;
    if (stickyLabel) stickyLabel.textContent = label;
  };

  updateHours();
  window.setInterval(updateHours, 60000);

  const mobile = window.matchMedia('(max-width: 781px)');
  const updateBar = () => {
    bar.hidden = !mobile.matches || hero.getBoundingClientRect().bottom > 0;
    document.body.classList.toggle('has-emergency-call-bar', !bar.hidden);
  };

  bar.hidden = true;
  new IntersectionObserver(updateBar).observe(hero);
  mobile.addEventListener('change', updateBar);
  window.addEventListener('pageshow', updateBar);
  updateBar();
}

document.querySelectorAll('#emergency-faqs details').forEach((question) => {
  question.setAttribute('name', 'emergency-faq');
});
