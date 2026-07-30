(function () {
	var header = document.querySelector('.site-header');
	if (!header) return;

	function onScroll() {
		header.classList.toggle('is-scrolled', window.scrollY > 10);
	}

	onScroll();
	window.addEventListener('scroll', onScroll, { passive: true });
})();
