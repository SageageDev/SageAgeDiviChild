// A fixed Theme Builder header (sections set to Position: Fixed) covers the top of the page.
// Publish its rendered height as --sage-header-h; css/sage-header.css pushes #et-main-area down by it.
(function () {
	var sections = document.querySelectorAll('.et-l--header .et_pb_section--fixed');
	if (!sections.length) return;

	function update() {
		var h = 0;
		sections.forEach(function (s) {
			h = Math.max(h, s.getBoundingClientRect().height);
		});
		if (h) document.documentElement.style.setProperty('--sage-header-h', Math.round(h) + 'px');
	}

	update();
	// The header animates down from a taller size while its CSS loads, and that shrink is in its padding,
	// so watch the border box (not the default content box) and re-measure when transitions finish.
	if (window.ResizeObserver) {
		var ro = new ResizeObserver(update);
		sections.forEach(function (s) { ro.observe(s, { box: 'border-box' }); });
	}
	sections.forEach(function (s) { s.addEventListener('transitionend', update); });
	window.addEventListener('resize', update);
	window.addEventListener('load', update);
})();
