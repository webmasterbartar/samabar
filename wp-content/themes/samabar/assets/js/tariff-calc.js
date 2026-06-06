(function (window) {
	'use strict';

	function getConfig() {
		return window.samabarTariffCalc || window.samabarOrder || window.samabarPricing || {};
	}

	function requestFreight(originCity, destinationCity, weightKg) {
		const config = getConfig();
		const url = config.calculateUrl || '/wp-json/samabar/v1/calculate-freight';

		return fetch(url, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-WP-Nonce': config.nonce || '',
			},
			body: JSON.stringify({
				origin_city: originCity,
				destination_city: destinationCity,
				weight: weightKg,
			}),
		}).then(function (response) {
			return response.json().then(function (body) {
				if (!response.ok) {
					const err = new Error(body.message || 'خطا در محاسبه کرایه');
					err.code = body.code || 'calculation_error';
					err.data = body.data || {};
					throw err;
				}
				return body.freight;
			});
		});
	}

	window.samabarTariff = {
		requestFreight: requestFreight,
	};

	window.samabarMoney = {
		rialToToman: function (rial) {
			return Math.round(Number(rial || 0) / 10);
		},
		format: function (rial) {
			return window.samabarMoney.rialToToman(rial).toLocaleString('fa-IR') + ' تومان';
		},
	};
})(window);
