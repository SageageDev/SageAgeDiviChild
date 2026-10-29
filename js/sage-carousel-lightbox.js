/* Image carousels (Divi Group Carousel with images set to Open in Lightbox): clicking an
   image opens Divi's lightbox (Magnific Popup) as a gallery of that carousel's images, so the arrows, the keyboard
   arrows and a swipe go to the next/previous image. Divi's own handler opens one image at a time; this one runs first
   (capture phase) and takes over the click. */
(function () {
	function items(carousel) {
		var seen = {}, list = [];
		// the carousel clones slides to loop; the real ones come first in order without the clone class
		carousel.querySelectorAll('.et_pb_group_carousel_slide:not(.et_pb_group_carousel_clone) a.et_pb_lightbox_image').forEach(function (a) {
			var href = a.getAttribute('href');
			if (!href || seen[href]) return;
			seen[href] = true;
			var img = a.querySelector('img');
			list.push({ src: href, title: img ? (img.getAttribute('title') || img.getAttribute('alt') || '') : '' });
		});
		return list;
	}

	document.addEventListener('click', function (e) {
		var link = e.target.closest && e.target.closest('.et_pb_group_carousel a.et_pb_lightbox_image');
		var $ = window.jQuery;
		if (!link || !$ || !$.magnificPopup) return;
		var carousel = link.closest('.et_pb_group_carousel');
		var list = items(carousel);
		var index = list.map(function (i) { return i.src; }).indexOf(link.getAttribute('href'));
		if (index < 0) return;
		e.preventDefault();
		e.stopPropagation();
		$.magnificPopup.open({
			items: list,
			type: 'image',
			mainClass: 'mfp-fade sage-carousel-lightbox',
			removalDelay: 300,
			gallery: { enabled: true, navigateByImgClick: true, preload: [1, 1], tCounter: '%curr% of %total%' },
			autoFocusLast: false
		}, index);
	}, true);

	// swipe left/right on touch screens
	var startX = null;
	document.addEventListener('touchstart', function (e) {
		startX = e.target.closest('.sage-carousel-lightbox') ? e.touches[0].clientX : null;
	}, { passive: true });
	document.addEventListener('touchend', function (e) {
		if (startX === null || !window.jQuery || !window.jQuery.magnificPopup.instance.isOpen) return;
		var dx = e.changedTouches[0].clientX - startX;
		startX = null;
		if (Math.abs(dx) < 40) return;
		var mfp = window.jQuery.magnificPopup.instance;
		dx < 0 ? mfp.next() : mfp.prev();
	}, { passive: true });
})();
