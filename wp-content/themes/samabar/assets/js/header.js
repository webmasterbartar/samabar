(function () {
	'use strict';

	const header = document.getElementById('site-header');
	if (!header) {
		return;
	}

	const menuToggle = header.querySelector('.site-header__menu-toggle');
	const mobileNav = document.getElementById('mobile-nav');
	const overlay = document.getElementById('header-overlay');
	const mobileClose = header.querySelector('.site-header__mobile-close');
	let dropdowns = header.querySelectorAll('.site-header__dropdown');

	let scrollTicking = false;

	function bindDropdowns() {
		dropdowns = header.querySelectorAll('.site-header__dropdown');
		dropdowns.forEach(function (dropdown) {
			const toggle = dropdown.querySelector('.site-header__dropdown-toggle');
			if (!toggle || toggle.dataset.bound === 'true') {
				return;
			}

			toggle.dataset.bound = 'true';
			toggle.addEventListener('click', function (event) {
				event.stopPropagation();
				const isOpen = dropdown.classList.contains('is-open');
				closeDropdowns(null);

				if (!isOpen) {
					dropdown.classList.add('is-open');
					toggle.setAttribute('aria-expanded', 'true');
				}
			});
		});
	}

	function setScrolled() {
		header.classList.toggle('is-scrolled', window.scrollY > 8);
	}

	function openMenu() {
		if (!mobileNav || !overlay || !menuToggle) {
			return;
		}

		mobileNav.classList.add('is-open');
		overlay.classList.add('is-visible');
		overlay.hidden = false;
		menuToggle.setAttribute('aria-expanded', 'true');
		mobileNav.setAttribute('aria-hidden', 'false');
		document.body.classList.add('is-menu-open');
	}

	function closeMenu() {
		if (!mobileNav || !overlay || !menuToggle) {
			return;
		}

		mobileNav.classList.remove('is-open');
		overlay.classList.remove('is-visible');
		menuToggle.setAttribute('aria-expanded', 'false');
		mobileNav.setAttribute('aria-hidden', 'true');
		document.body.classList.remove('is-menu-open');

		window.setTimeout(function () {
			if (!overlay.classList.contains('is-visible')) {
				overlay.hidden = true;
			}
		}, 300);
	}

	function closeDropdowns(except) {
		header.querySelectorAll('.site-header__dropdown').forEach(function (dropdown) {
			if (dropdown !== except) {
				dropdown.classList.remove('is-open');
				const toggle = dropdown.querySelector('.site-header__dropdown-toggle');
				if (toggle) {
					toggle.setAttribute('aria-expanded', 'false');
				}
			}
		});
	}

	menuToggle?.addEventListener('click', function () {
		const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
		if (isOpen) {
			closeMenu();
		} else {
			openMenu();
		}
	});

	mobileClose?.addEventListener('click', closeMenu);
	overlay?.addEventListener('click', closeMenu);

	mobileNav?.querySelectorAll('a').forEach(function (link) {
		link.addEventListener('click', closeMenu);
	});

	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') {
			closeMenu();
			closeDropdowns(null);
		}
	});

	bindDropdowns();

	document.addEventListener('click', function () {
		closeDropdowns(null);
	});

	window.addEventListener('scroll', function () {
		if (!scrollTicking) {
			window.requestAnimationFrame(function () {
				setScrolled();
				scrollTicking = false;
			});
			scrollTicking = true;
		}
	});

	setScrolled();
})();
