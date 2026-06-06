(function () {
	'use strict';

	const form = document.getElementById('pricing-form');
	const result = document.getElementById('pricing-result');

	if (!form || !result) {
		return;
	}

	const priceRangeEl = result.querySelector('[data-price-range]');
	const deliveryEl = result.querySelector('[data-delivery]');
	const serviceEl = result.querySelector('[data-service]');
	const resetBtn = result.querySelector('[data-reset]');
	const orderLinkEl = result.querySelector('[data-pricing-order-link]');
	const orderBaseUrl = orderLinkEl ? orderLinkEl.getAttribute('href').split('?')[0] : '';
	const formErrorEl = form.querySelector('[data-pricing-form-error]');
	const formErrorTextEl = form.querySelector('[data-pricing-form-error-text]');

	const emptyPriceHtml =
		'<span class="pricing-result__price-mask" aria-hidden="true">＊ ＊ ＊ ＊ ＊</span>' +
		' <span class="pricing-result__price-unit">تومان</span>';

	function setEmptyResult() {
		result.classList.add('is-empty');
		if (priceRangeEl) {
			priceRangeEl.innerHTML = emptyPriceHtml;
		}
		if (deliveryEl) {
			deliveryEl.textContent = '—';
		}
		if (serviceEl) {
			serviceEl.textContent = '—';
		}
		updateOrderLink(false);
	}

	function formatPriceToman(rial) {
		if (window.samabarMoney) {
			return window.samabarMoney.format(rial);
		}
		return Math.round(Number(rial || 0) / 10).toLocaleString('fa-IR') + ' تومان';
	}

	function getFormRoute() {
		return {
			origin: form.querySelector('#pricing-origin')?.value.trim() || '',
			destination: form.querySelector('#pricing-destination')?.value.trim() || '',
			weight: form.querySelector('#pricing-weight')?.value.trim() || '',
		};
	}

	function updateOrderLink(routeReady) {
		if (!orderLinkEl || !orderBaseUrl) {
			return;
		}

		if (!routeReady) {
			orderLinkEl.href = orderBaseUrl;
			orderLinkEl.dataset.routeReady = '';
			return;
		}

		const route = getFormRoute();
		const params = new URLSearchParams();

		if (route.origin) {
			params.set('origin', route.origin);
		}
		if (route.destination) {
			params.set('destination', route.destination);
		}
		if (route.weight) {
			params.set('weight', route.weight);
		}

		const query = params.toString();
		orderLinkEl.href = query ? orderBaseUrl + '?' + query : orderBaseUrl;
		orderLinkEl.dataset.routeReady = '1';
	}

	function showFormError(message) {
		const text = String(message || '').trim();
		if (!formErrorEl || !formErrorTextEl) {
			if (text) {
				window.alert(text);
			}
			return;
		}
		formErrorTextEl.textContent = text;
		formErrorEl.hidden = !text;
		form.querySelector('#pricing-origin')?.classList.toggle('route-field-error', !!text);
		form.querySelector('#pricing-destination')?.classList.toggle('route-field-error', !!text);
	}

	function validateRouteFields(showMissingError) {
		const origin = form.querySelector('#pricing-origin')?.value.trim() || '';
		const dest = form.querySelector('#pricing-destination')?.value.trim() || '';

		if (!origin || !dest) {
			if (showMissingError) {
				showFormError('شهر مبدا و مقصد را انتخاب کنید.');
			} else {
				showFormError('');
			}
			return false;
		}

		if (!window.samabarRoute) {
			showFormError('');
			return true;
		}

		const check = window.samabarRoute.validateRoute(origin, dest);
		if (!check.valid) {
			showFormError(check.message || 'مسیر انتخاب‌شده معتبر نیست.');
			return false;
		}

		showFormError('');
		return true;
	}

	function onRouteChange() {
		const origin = form.querySelector('#pricing-origin')?.value.trim() || '';
		const dest = form.querySelector('#pricing-destination')?.value.trim() || '';

		if (!origin || !dest) {
			showFormError('');
			setEmptyResult();
			return;
		}

		if (!validateRouteFields(true)) {
			setEmptyResult();
		}
	}

	function calculate() {
		if (!validateRouteFields(true)) {
			setEmptyResult();
			return;
		}

		const origin = form.querySelector('#pricing-origin')?.value.trim() || '';
		const dest = form.querySelector('#pricing-destination')?.value.trim() || '';
		const weight = parseInt(form.weight.value, 10) || 0;

		if (!weight || weight < 1) {
			showFormError('وزن محموله را به کیلوگرم وارد کنید.');
			setEmptyResult();
			return;
		}

		if (!window.samabarTariff) {
			showFormError('سیستم محاسبه در دسترس نیست. صفحه را یک‌بار رفرش کنید.');
			return;
		}

		priceRangeEl.textContent = 'در حال محاسبه...';
		deliveryEl.textContent = '';
		serviceEl.textContent = '';

		window.samabarTariff
			.requestFreight(origin, dest, weight)
			.then(function (freight) {
				showFormError('');
				priceRangeEl.innerHTML =
					(freight.amount_label || formatPriceToman(freight.amount)) +
					' <span class="pricing-result__price-unit">کرایه حمل</span>';

				const tons =
					freight.weight_tons >= 1
						? freight.weight_tons.toLocaleString('fa-IR') + ' تن'
						: freight.weight_kg.toLocaleString('fa-IR') + ' کیلوگرم';

				deliveryEl.textContent =
					'وزن: ' +
					tons +
					' — حداقل نرخ پایه: ' +
					(freight.base_label || formatPriceToman(freight.base_rial));

				if (freight.distance_km) {
					serviceEl.textContent = 'مسافت تقریبی: ' + freight.distance_km.toLocaleString('fa-IR') + ' کیلومتر';
				} else {
					serviceEl.textContent = 'مقصد: ' + (freight.destination || dest);
				}

				result.classList.remove('is-empty');
				updateOrderLink(true);
			})
			.catch(function (err) {
				showFormError(err.message || 'محاسبه کرایه ممکن نیست. مسیر یا وزن را بررسی کنید.');
				setEmptyResult();
			});
	}

	form.addEventListener('submit', function (event) {
		event.preventDefault();
		calculate();
	});

	form.querySelector('#pricing-origin')?.addEventListener('change', onRouteChange);
	form.querySelector('#pricing-destination')?.addEventListener('change', onRouteChange);

	if (orderLinkEl) {
		orderLinkEl.addEventListener('click', function (event) {
			if (orderLinkEl.dataset.routeReady !== '1') {
				event.preventDefault();
				showFormError('ابتدا مسیر و وزن را وارد کنید و «محاسبه قیمت» را بزنید.');
			}
		});
	}

	if (resetBtn) {
		resetBtn.addEventListener('click', function () {
			form.reset();
			showFormError('');
			setEmptyResult();
		});
	}

	setEmptyResult();
})();
