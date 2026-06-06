(function (window) {
	'use strict';

	const config = window.samabarRouteRules || {};
	const messages = config.messages || {};
	const allCities = config.allCities || config.servedCities || [];

	function normalizeCityKey(city) {
		return String(city || '')
			.trim()
			.replace(/\s+/g, '')
			.replace(/‌/g, '')
			.replace(/ي/g, 'ی')
			.replace(/ك/g, 'ک')
			.toLowerCase();
	}

	function isHubCity(city) {
		const key = normalizeCityKey(city);
		if (!key) {
			return false;
		}

		const aliases = [config.hubCity].concat(config.hubAliases || []);
		return aliases.some(function (alias) {
			return normalizeCityKey(alias) === key;
		});
	}

	function isServedCity(city) {
		const served = allCities.length ? allCities : config.servedCities || [];
		if (!served.length) {
			return true;
		}

		const key = normalizeCityKey(city);
		return served.some(function (label) {
			return normalizeCityKey(label) === key;
		});
	}

	function resolveCity(city) {
		const raw = String(city || '').trim();
		if (!raw) {
			return '';
		}

		if (isHubCity(raw)) {
			return config.hubCity || 'بندرعباس';
		}

		const key = normalizeCityKey(raw);
		const match = allCities.find(function (label) {
			return normalizeCityKey(label) === key;
		});

		return match || '';
	}

	function validateRoute(originCity, destinationCity) {
		const origin = String(originCity || '').trim();
		const dest = String(destinationCity || '').trim();

		if (!origin || !dest) {
			return { valid: false, message: messages.missing || 'شهر مبدا و مقصد را وارد کنید.' };
		}

		if (normalizeCityKey(origin) === normalizeCityKey(dest)) {
			return { valid: false, message: messages.sameCity || 'مبدا و مقصد نمی‌توانند یک شهر باشند.' };
		}

		const originHub = isHubCity(origin);
		const destHub = isHubCity(dest);

		if (!originHub && !destHub) {
			return {
				valid: false,
				message:
					messages.invalidRoute ||
					'یک طرف مسیر باید بندرعباس باشد.',
			};
		}

		if (originHub && destHub) {
			return {
				valid: false,
				message: messages.bothHub || 'مسیر داخل بندرعباس ثبت نمی‌شود.',
			};
		}

		const otherCity = originHub ? dest : origin;
		if (!isServedCity(otherCity)) {
			return {
				valid: false,
				message: messages.notServed || 'این شهر در نرخنامه ثبت نشده است.',
			};
		}

		return { valid: true, message: '' };
	}

	window.samabarRoute = {
		normalizeCityKey: normalizeCityKey,
		isHubCity: isHubCity,
		isServedCity: isServedCity,
		resolveCity: resolveCity,
		validateRoute: validateRoute,
		hubCity: config.hubCity || 'بندرعباس',
		allCities: allCities,
	};
})(window);
