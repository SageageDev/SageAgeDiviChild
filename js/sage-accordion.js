// Accordions: clicking the open item's title (or its − icon) closes it. Divi's own handler ignores that click and
// leaves .et_pb_accordion_toggling stuck on the accordion, so take the click first (capture) and stop it there.
// Titles also become keyboard-operable buttons that report aria-expanded.
(function () {
	if (document.body.classList.contains('et-fb')) return;

	var titles = document.querySelectorAll('.et_pb_accordion_item > .et_pb_toggle_title');
	if (!titles.length) return;

	function sync() {
		titles.forEach(function (t) {
			t.setAttribute('aria-expanded', t.parentNode.classList.contains('et_pb_toggle_open') ? 'true' : 'false');
		});
	}

	titles.forEach(function (t) {
		t.setAttribute('role', 'button');
		t.setAttribute('tabindex', '0');
		t.addEventListener('keydown', function (e) {
			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				t.click();
			}
		});
		// Divi swaps the open/close classes when its slide animation ends.
		new MutationObserver(sync).observe(t.parentNode, { attributes: true, attributeFilter: ['class'] });
	});
	sync();

	document.addEventListener('click', function (e) {
		var title = e.target.closest && e.target.closest('.et_pb_accordion_item.et_pb_toggle_open > .et_pb_toggle_title');
		if (!title || !window.jQuery || !window.et_pb_toggle_collapse) return;
		e.stopPropagation();
		window.et_pb_toggle_collapse(window.jQuery(title.parentNode));
	}, true);
})();
