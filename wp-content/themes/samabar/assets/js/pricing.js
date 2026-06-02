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

	const serviceLabels = {
		normal: 'عادی',
		express: 'اکسپرس (فوری)',
		b2b: 'پروژه‌ای (B2B)',
	};

	const serviceMultipliers = {
		normal: 1,
		express: 1.45,
		b2b: 1.25,
	};

	const cargoMultipliers = {
		industrial: 1.1,
		food: 1.2,
		furniture: 1,
	};

	function formatPrice(value) {
		return value.toLocaleString('fa-IR', { maximumFractionDigits: 1 });
	}

	function calculate() {
		const weight = parseFloat(form.weight.value) || 500;
		const service = form.service.value;
		const cargo = form.cargo.value;

		const base = Math.max(weight * 8.5, 2500000);
		const multiplier = (serviceMultipliers[service] || 1) * (cargoMultipliers[cargo] || 1);
		const min = (base * multiplier) / 1000000;
		const max = min * 1.15;

		const deliveryDays = service === 'express' ? '۱ تا ۲ روز کاری' : service === 'b2b' ? '۳ تا ۵ روز کاری' : '۲ تا ۳ روز کاری';

		priceRangeEl.innerHTML =
			formatPrice(min) +
			' <small>تا</small> ' +
			formatPrice(max) +
			' <span class="pricing-result__price-unit">میلیون تومان</span>';

		deliveryEl.textContent = 'زمان تخمینی تحویل: ' + deliveryDays;
		serviceEl.textContent = 'سرویس پیشنهادی: ' + (serviceLabels[service] || service);

		result.classList.remove('is-empty');
	}

	form.addEventListener('submit', function (event) {
		event.preventDefault();
		calculate();
	});

	if (resetBtn) {
		resetBtn.addEventListener('click', function () {
			form.reset();
			result.classList.add('is-empty');
		});
	}
})();
