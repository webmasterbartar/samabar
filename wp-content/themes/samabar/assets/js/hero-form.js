(function () {
	'use strict';

	const form = document.getElementById('hero-form');
	if (!form) {
		return;
	}

	const wrap = form.closest('.hero-form-wrap');
	const errorEl = wrap?.querySelector('[data-hero-route-error]');
	const errorTextEl = wrap?.querySelector('[data-hero-route-error-text]');
	const originSelect = form.querySelector('#hero-origin');
	const destSelect = form.querySelector('#hero-destination');

	function showRouteError(message) {
		const text = String(message || '').trim();

		if (!errorEl || !errorTextEl) {
			if (text) {
				window.alert(text);
			}
			return;
		}

		errorTextEl.textContent = text;
		errorEl.hidden = !text;
		originSelect?.classList.toggle('route-field-error', !!text);
		destSelect?.classList.toggle('route-field-error', !!text);
	}

	function validateRoute(showErrors) {
		const origin = originSelect?.value.trim() || '';
		const dest = destSelect?.value.trim() || '';

		if (!origin || !dest) {
			if (showErrors) {
				showRouteError('شهر مبدا و مقصد را انتخاب کنید.');
			} else {
				showRouteError('');
			}
			return false;
		}

		if (!window.samabarRoute) {
			showRouteError('');
			return true;
		}

		const check = window.samabarRoute.validateRoute(origin, dest);
		if (!check.valid) {
			showRouteError(check.message || 'مسیر انتخاب‌شده معتبر نیست.');
			return false;
		}

		showRouteError('');
		return true;
	}

	function onRouteChange() {
		const origin = originSelect?.value.trim() || '';
		const dest = destSelect?.value.trim() || '';

		if (!origin || !dest) {
			showRouteError('');
			return;
		}

		validateRoute(true);
	}

	originSelect?.addEventListener('change', onRouteChange);
	destSelect?.addEventListener('change', onRouteChange);

	form.addEventListener('submit', function (event) {
		if (!validateRoute(true)) {
			event.preventDefault();
		}
	});
})();
