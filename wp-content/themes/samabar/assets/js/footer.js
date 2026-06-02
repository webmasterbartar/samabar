(function () {
	'use strict';

	const backTop = document.getElementById('back-to-top');

	if (backTop) {
		let ticking = false;

		function toggleBackTop() {
			backTop.classList.toggle('is-visible', window.scrollY > 400);
		}

		backTop.addEventListener('click', function () {
			window.scrollTo({ top: 0, behavior: 'smooth' });
		});

		window.addEventListener('scroll', function () {
			if (!ticking) {
				window.requestAnimationFrame(function () {
					toggleBackTop();
					ticking = false;
				});
				ticking = true;
			}
		});

		toggleBackTop();
	}

	const footerAccordions = document.querySelectorAll('.site-footer__accordion');
	const desktopFooterMq = window.matchMedia('(min-width: 768px)');

	function syncFooterAccordions() {
		footerAccordions.forEach(function (accordion) {
			if (desktopFooterMq.matches) {
				accordion.setAttribute('open', '');
			} else {
				accordion.removeAttribute('open');
			}
		});
	}

	if (footerAccordions.length) {
		syncFooterAccordions();

		if (typeof desktopFooterMq.addEventListener === 'function') {
			desktopFooterMq.addEventListener('change', syncFooterAccordions);
		} else if (typeof desktopFooterMq.addListener === 'function') {
			desktopFooterMq.addListener(syncFooterAccordions);
		}
	}

	const newsletterForm = document.getElementById('footer-newsletter-form');
	const newsletterNotice = document.getElementById('footer-newsletter-notice');

	if (newsletterForm && newsletterNotice) {
		newsletterForm.addEventListener('submit', function (event) {
			event.preventDefault();

			const emailInput = document.getElementById('footer-newsletter-email');
			const email = emailInput ? emailInput.value.trim() : '';

			newsletterNotice.hidden = false;
			newsletterNotice.classList.remove('is-success', 'is-error');

			if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
				newsletterNotice.textContent = 'لطفاً یک ایمیل معتبر وارد کنید.';
				newsletterNotice.classList.add('is-error');
				return;
			}

			newsletterNotice.textContent = 'عضویت شما ثبت شد. از همراهی شما سپاسگزاریم.';
			newsletterNotice.classList.add('is-success');
			newsletterForm.reset();
		});
	}
})();
