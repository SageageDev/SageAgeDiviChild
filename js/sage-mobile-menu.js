// Divi's mobile menu toggle (.mobile_menu_bar) is a plain <span>: give it button semantics, open it with Enter/Space,
// and keep aria-expanded in step with Divi's opened/closed class on .mobile_nav. The menu's height cap is in css/sage-header.css.
(function () {
	document.querySelectorAll('.mobile_nav .mobile_menu_bar').forEach(function (bar) {
		var nav = bar.closest('.mobile_nav');

		function sync() {
			bar.setAttribute('aria-expanded', nav.classList.contains('opened') ? 'true' : 'false');
		}

		bar.setAttribute('role', 'button');
		bar.setAttribute('tabindex', '0');
		bar.setAttribute('aria-label', 'Menu');
		sync();
		new MutationObserver(sync).observe(nav, { attributes: true, attributeFilter: ['class'] });
		bar.addEventListener('keydown', function (e) {
			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				bar.click();
			}
		});
	});
})();
