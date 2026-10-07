import { getContext, getElement, store } from '@wordpress/interactivity';

// Core still handles clicks, keyboard interaction, and the overlay dialog.
// Only the service accordion's expanded binding differs inside the overlay.
store('white-oaks/services-menu', {
  actions: {
    openDesktopHover(event) {
      if (event.pointerType === 'touch' || !window.matchMedia('(min-width: 1025px)').matches) return;
      getContext('core/navigation').submenuOpenedBy.hover = true;
    },
    closeDesktopHover() {
      if (!window.matchMedia('(min-width: 1025px)').matches) return;
      getContext('core/navigation').submenuOpenedBy.hover = false;
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
