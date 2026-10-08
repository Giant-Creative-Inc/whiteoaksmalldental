import { getContext, getElement, store, withScope } from '@wordpress/interactivity';

// Keep the native click, focus, Escape and mobile-dialog state independent.
const desktopMouse = window.matchMedia('(min-width: 1025px) and (hover: hover) and (pointer: fine)');
let cancelTransit;
let hoveredContext;
desktopMouse.addEventListener('change', () => {
  cancelTransit?.();
  if (!desktopMouse.matches && hoveredContext) hoveredContext.submenuOpenedBy.hover = false;
});

function inTriangle(point, a, b, c) {
  const side = (p, q, r) => (p.x - r.x) * (q.y - r.y) - (q.x - r.x) * (p.y - r.y);
  const signs = [side(point, a, b), side(point, b, c), side(point, c, a)];
  return !(signs.some((value) => value < 0) && signs.some((value) => value > 0));
}

store('white-oaks/services-menu', {
  actions: {
    openDesktopHover(event) {
      if (event.pointerType !== 'mouse' || !desktopMouse.matches) return;
      const context = getContext('core/navigation');

      hoveredContext = context;
      hoveredContext.submenuOpenedBy.hover = true;
    },
    closeDesktopHover(event) {
      if (event.pointerType !== 'mouse' || !desktopMouse.matches) return;
      const context = getContext('core/navigation');
      const { ref } = getElement();
      cancelTransit?.(false);
      const panel = ref.querySelector(':scope > .wp-block-navigation__submenu-container');
      if (!panel || !context.submenuOpenedBy.hover) return;
      const rect = panel.getBoundingClientRect();
      const origin = { x: event.clientX, y: event.clientY };
      // Use the closest panel edge, supporting both dropdowns and side flyouts.
      const edges = [
        [{ x: rect.left, y: rect.top }, { x: rect.right, y: rect.top }, Math.abs(origin.y - rect.top)],
        [{ x: rect.left, y: rect.bottom }, { x: rect.right, y: rect.bottom }, Math.abs(origin.y - rect.bottom)],
        [{ x: rect.left, y: rect.top }, { x: rect.left, y: rect.bottom }, Math.abs(origin.x - rect.left)],
        [{ x: rect.right, y: rect.top }, { x: rect.right, y: rect.bottom }, Math.abs(origin.x - rect.right)],
      ];
      const [a, b] = edges.sort((first, second) => first[2] - second[2])[0];
      let timer;
      const closeHover = withScope(() => { context.submenuOpenedBy.hover = false; });
      const finish = (close = true) => {
        clearTimeout(timer);
        document.removeEventListener('pointermove', move);
        document.removeEventListener('pointerdown', dismiss);
        document.removeEventListener('keydown', dismiss);
        window.removeEventListener('blur', dismiss);
        window.removeEventListener('resize', dismiss);
        window.removeEventListener('scroll', dismiss, true);
        desktopMouse.removeEventListener('change', dismiss);
        cancelTransit = undefined;
        if (close) closeHover();
      };
      const dismiss = () => finish();
      const move = (pointer) => {
        if (pointer.pointerType !== 'mouse') return finish();
        if (pointer.clientX >= rect.left && pointer.clientX <= rect.right && pointer.clientY >= rect.top && pointer.clientY <= rect.bottom) return finish(false);
        if (!inTriangle({ x: pointer.clientX, y: pointer.clientY }, origin, a, b)) return finish();
        clearTimeout(timer);
        timer = setTimeout(dismiss, 500);
      };
      cancelTransit = finish;
      document.addEventListener('pointermove', move);
      document.addEventListener('pointerdown', dismiss);
      document.addEventListener('keydown', dismiss);
      window.addEventListener('blur', dismiss);
      window.addEventListener('resize', dismiss);
      window.addEventListener('scroll', dismiss, true);
      desktopMouse.addEventListener('change', dismiss);
      timer = setTimeout(dismiss, 500);
    },
  },
  state: {
    get isExpanded() {
      const context = getContext('core/navigation');
      const { ref } = getElement();
      const submenuOpen = Object.values(context.submenuOpenedBy || {}).some(Boolean);
      if (ref.closest('.wp-block-navigation__responsive-container.is-menu-open')) {
        return submenuOpen ? 'true' : 'false';
      }
      return submenuOpen || Object.values(context.overlayOpenedBy || {}).some(Boolean) ? 'true' : 'false';
    },
  },
});
