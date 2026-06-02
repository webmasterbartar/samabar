(function () {
	'use strict';

	const carousel = document.querySelector('.testimonials-carousel');
	if (!carousel) {
		return;
	}

	const track = carousel.querySelector('.testimonials-carousel__track');
	const group = carousel.querySelector('.testimonials-carousel__group');

	if (!track || !group || track.dataset.cloned === 'true') {
		return;
	}

	const clone = group.cloneNode(true);
	clone.setAttribute('aria-hidden', 'true');
	track.appendChild(clone);
	track.dataset.cloned = 'true';
})();
