(function () {
	'use strict';

	const STORAGE_KEY = 'samabar_order';
	const main = document.querySelector('.site-main--order');
	if (!main) {
		return;
	}

	const step = parseInt(main.dataset.orderStep || '1', 10);
	const config = window.samabarOrder || {};
	const baseUrl = config.baseUrl || '';

	const cargoLabels = config.cargoLabels || {
		b2b: 'صنعتی / B2B',
		general: 'عمومی',
		fragile: 'شکستنی',
		cold: 'حساس / یخچالی',
	};

	const serviceLabels = config.serviceLabels || {
		corporate: 'سازمانی',
		express: 'اکسپرس',
		standard: 'بین‌شهری عادی',
	};

	const servicePrices = config.servicePrices || {
		corporate: 42500000,
		express: 55000000,
		standard: 30000000,
	};

	function loadData() {
		try {
			return JSON.parse(sessionStorage.getItem(STORAGE_KEY) || '{}');
		} catch (e) {
			return {};
		}
	}

	function saveData(data) {
		sessionStorage.setItem(STORAGE_KEY, JSON.stringify(data));
	}

	function mergeData(partial) {
		const data = loadData();
		Object.assign(data, partial);
		saveData(data);
		return data;
	}

	function mergeFormFields(form, extra) {
		const data = loadData();
		new FormData(form).forEach(function (value, key) {
			if (key === 'step') {
				return;
			}
			data[key] = value;
		});
		if (extra) {
			Object.assign(data, extra);
		}
		saveData(data);
		return data;
	}

	function populateForm(form, data) {
		Object.keys(data).forEach(function (key) {
			const fields = form.elements.namedItem(key);
			if (!fields) {
				return;
			}
			if (fields instanceof RadioNodeList || (fields.length && fields[0]?.type === 'radio')) {
				const value = data[key];
				const radio = form.querySelector('[name="' + key + '"][value="' + value + '"]');
				if (radio) {
					radio.checked = true;
				}
			} else if (fields.type === 'radio') {
				const radio = form.querySelector('[name="' + key + '"][value="' + data[key] + '"]');
				if (radio) {
					radio.checked = true;
				}
			} else {
				fields.value = data[key];
			}
		});
	}

	function syncCargoCards(form) {
		if (!form) {
			return;
		}
		form.querySelectorAll('.order-cargo').forEach(function (card) {
			const input = card.querySelector('input[type="radio"]');
			card.classList.toggle('order-cargo--selected', !!(input && input.checked));
		});
	}

	function initCargoCards(form) {
		if (!form) {
			return;
		}
		form.querySelectorAll('.order-cargo input[type="radio"]').forEach(function (input) {
			input.addEventListener('change', function () {
				syncCargoCards(form);
			});
		});
		syncCargoCards(form);
	}

	function stepUrl(num) {
		if (num <= 1) {
			return baseUrl;
		}
		return baseUrl + (baseUrl.indexOf('?') >= 0 ? '&' : '?') + 'step=' + num;
	}

	function normalizePhone(phone) {
		let digits = String(phone || '').replace(/\D/g, '');
		if (digits.indexOf('98') === 0 && digits.length === 12) {
			digits = '0' + digits.slice(2);
		}
		if (digits.length === 10 && digits.charAt(0) === '9') {
			digits = '0' + digits;
		}
		return digits;
	}

	function isValidPhone(phone) {
		return /^09\d{9}$/.test(normalizePhone(phone));
	}

	function persistCustomerSession(contact) {
		if (!window.samabarCustomer || !contact.phone || !isValidPhone(contact.phone)) {
			return;
		}
		window.samabarCustomer.save({
			phone: contact.phone,
			full_name: contact.full_name || '',
			company: contact.company || '',
		});
	}

	function hasValidContact(data) {
		return !!(data.full_name && data.phone && isValidPhone(data.phone));
	}

	function collectContactFields(wrapper) {
		return {
			full_name: wrapper.querySelector('#order-full-name')?.value.trim() || '',
			phone: normalizePhone(wrapper.querySelector('#order-phone')?.value || ''),
			company: wrapper.querySelector('#order-company')?.value.trim() || '',
		};
	}

	function validateContact(wrapper) {
		const contact = collectContactFields(wrapper);
		const phoneInput = wrapper.querySelector('#order-phone');

		if (!contact.full_name) {
			window.alert('لطفاً نام و نام خانوادگی را وارد کنید.');
			wrapper.querySelector('#order-full-name')?.focus();
			return false;
		}

		if (!isValidPhone(contact.phone)) {
			window.alert('شماره موبایل معتبر نیست. مثال: 09123456789');
			phoneInput?.focus();
			return false;
		}

		if (phoneInput) {
			phoneInput.value = contact.phone;
		}

		return true;
	}

	function validateRoute(form) {
		const route = collectRouteFromForm(form);
		if (!route.origin_city || !route.origin_address || !route.destination_city || !route.destination_address) {
			window.alert('لطفاً شهر و آدرس دقیق مبدا و مقصد را کامل وارد کنید.');
			if (!route.origin_city) {
				form.querySelector('#order-origin-city')?.focus();
			} else if (!route.origin_address) {
				form.querySelector('#order-origin-address')?.focus();
			} else if (!route.destination_city) {
				form.querySelector('#order-destination-city')?.focus();
			} else {
				form.querySelector('#order-destination-address')?.focus();
			}
			return false;
		}
		return true;
	}

	function collectStep1Data(form) {
		return Object.assign(
			withRouteSummary(collectRouteFromForm(form)),
			collectContactFields(form),
			{
				pickup_date: form.querySelector('#order-pickup-date')?.value || '',
			}
		);
	}

	function initContactFields(wrapper, data) {
		const fields = ['full_name', 'phone', 'company'];
		fields.forEach(function (key) {
			const input = wrapper.querySelector('[name="' + key + '"]');
			if (input && data[key]) {
				input.value = data[key];
			}
		});

		wrapper.querySelectorAll('.order-contact__input').forEach(function (input) {
			input.addEventListener('input', function () {
				const contact = collectContactFields(wrapper);
				mergeData(contact);
				persistCustomerSession(contact);
			});
		});
	}

	function initRouteFields(form) {
		const selectors = [
			'#order-origin-city',
			'#order-origin-address',
			'#order-origin-detail',
			'#order-destination-city',
			'#order-destination-address',
			'#order-destination-detail',
		];

		selectors.forEach(function (selector) {
			const field = form.querySelector(selector);
			if (!field) {
				return;
			}
			field.addEventListener('input', function () {
				mergeData(withRouteSummary(collectRouteFromForm(form)));
			});
		});
	}

	function formatAddress(city, address, detail) {
		return [city, address, detail].filter(function (part) {
			return part && String(part).trim();
		}).join(' — ');
	}

	function collectRouteFromForm(form) {
		return {
			origin_city: form.querySelector('#order-origin-city')?.value.trim() || '',
			origin_address: form.querySelector('#order-origin-address')?.value.trim() || '',
			origin_detail: form.querySelector('#order-origin-detail')?.value.trim() || '',
			destination_city: form.querySelector('#order-destination-city')?.value.trim() || '',
			destination_address: form.querySelector('#order-destination-address')?.value.trim() || '',
			destination_detail: form.querySelector('#order-destination-detail')?.value.trim() || '',
		};
	}

	function withRouteSummary(route) {
		return Object.assign({}, route, {
			origin: formatAddress(route.origin_city, route.origin_address, route.origin_detail),
			destination: formatAddress(route.destination_city, route.destination_address, route.destination_detail),
		});
	}

	function hasValidRoute(data) {
		if (data.origin_city && data.origin_address && data.destination_city && data.destination_address) {
			return true;
		}
		return !!(data.origin && data.destination);
	}

	function migrateLegacyRoute(data) {
		if (data.origin && !data.origin_city) {
			data.origin_city = data.origin;
		}
		if (data.destination && !data.destination_city) {
			data.destination_city = data.destination;
		}
		if (data.origin_city && data.origin_address) {
			data.origin = formatAddress(data.origin_city, data.origin_address, data.origin_detail);
		}
		if (data.destination_city && data.destination_address) {
			data.destination = formatAddress(data.destination_city, data.destination_address, data.destination_detail);
		}
		return data;
	}

	function formatPrice(num) {
		return Number(num || 0).toLocaleString('fa-IR') + ' ﷼';
	}

	function formatPickupDisplay(value) {
		if (!value) {
			return 'ثبت نشده';
		}
		const J = window.SamabarJalali;
		if (J) {
			const parsed = J.parseStorage(value);
			if (parsed) {
				return J.formatDisplay(parsed.jy, parsed.jm, parsed.jd, parsed.hour, parsed.minute);
			}
		}
		return String(value).replace(/\d/g, function (d) {
			return '۰۱۲۳۴۵۶۷۸۹'[d];
		});
	}

	function formatDimensions(data) {
		const parts = ['dim_length', 'dim_width', 'dim_height']
			.map(function (key) {
				return data[key] ? String(data[key]).trim() : '';
			})
			.filter(Boolean);

		if (!parts.length) {
			return '';
		}

		return parts.join(' × ') + ' m';
	}

	function setReviewRow(wrapper, rowKey, value) {
		const row = wrapper.querySelector('[data-review-row="' + rowKey + '"]');
		const target = wrapper.querySelector('[data-review-' + rowKey + ']');

		if (!row || !target) {
			return;
		}

		if (value) {
			target.textContent = value;
			row.hidden = false;
		} else {
			target.textContent = '—';
			row.hidden = true;
		}
	}

	function syncServiceCards(wrapper) {
		wrapper.querySelectorAll('.order-service, .order-service-card').forEach(function (card) {
			const input = card.querySelector('input[name="service"]');
			card.classList.toggle('is-selected', !!(input && input.checked));
		});
	}

	function selectService(wrapper, serviceKey) {
		const allowed = Object.keys(servicePrices);
		const service = allowed.indexOf(serviceKey) >= 0 ? serviceKey : 'corporate';
		const radio = wrapper.querySelector('[name="service"][value="' + service + '"]');
		if (radio) {
			radio.checked = true;
		}
		syncServiceCards(wrapper);
		return service;
	}

	function initMapStatus() {
		const status = document.querySelector('[data-map-status]');
		const fields = [
			'#order-origin-city',
			'#order-origin-address',
			'#order-destination-city',
			'#order-destination-address',
		].map(function (selector) {
			return document.querySelector(selector);
		});

		if (!status || fields.some(function (field) { return !field; })) {
			return;
		}

		function update() {
			const route = withRouteSummary(collectRouteFromForm(document.getElementById('order-form-step-1')));
			if (route.origin && route.destination) {
				status.textContent = route.origin + ' ← ' + route.destination;
			} else {
				status.textContent = 'مسیر هنوز مشخص نشده است';
			}
		}

		fields.forEach(function (field) {
			field.addEventListener('input', update);
		});
		update();
	}

	function initStep1() {
		const form = document.getElementById('order-form-step-1');
		if (!form) {
			return;
		}

		const data = migrateLegacyRoute(loadData());
		const params = new URLSearchParams(window.location.search);
		if (params.get('origin')) {
			data.origin_city = params.get('origin');
		}
		if (params.get('destination')) {
			data.destination_city = params.get('destination');
		}
		if (params.get('pickup_date')) {
			data.pickup_date = params.get('pickup_date');
		}
		if (params.get('cargo')) {
			const cargoMap = { light: 'general', heavy: 'b2b', refrigerated: 'cold' };
			data.cargo_type = cargoMap[params.get('cargo')] || params.get('cargo');
		}
		saveData(migrateLegacyRoute(data));
		populateForm(form, data);

		if (window.SamabarPersianDatetime && document.getElementById('order-pickup-datetime')) {
			window.SamabarPersianDatetime.init(document.getElementById('order-pickup-datetime'));
		}

		const pickupInput = form.querySelector('#order-pickup-date');
		if (pickupInput) {
			pickupInput.addEventListener('change', function () {
				mergeData({ pickup_date: pickupInput.value || '' });
			});
		}

		initMapStatus();
		initRouteFields(form);
		initContactFields(form, data);

		form.addEventListener('submit', function (event) {
			event.preventDefault();
			if (!validateRoute(form) || !validateContact(form)) {
				return;
			}
			const stepData = collectStep1Data(form);
			mergeData(stepData);
			persistCustomerSession(stepData);
			window.location.href = stepUrl(2);
		});
	}

	function initStep2() {
		const form = document.getElementById('order-form-step-2');
		if (!form) {
			return;
		}

		const data = migrateLegacyRoute(loadData());
		if (!hasValidRoute(data) || !hasValidContact(data)) {
			window.location.href = stepUrl(1);
			return;
		}

		populateForm(form, data);
		initCargoCards(form);

		form.addEventListener('submit', function (event) {
			event.preventDefault();
			const weight = form.querySelector('#order-weight')?.value;
			if (!weight || parseInt(weight, 10) < 1) {
				window.alert('لطفاً وزن محموله را وارد کنید.');
				form.querySelector('#order-weight')?.focus();
				return;
			}
			mergeFormFields(form);
			window.location.href = stepUrl(3);
		});
	}

	function initStep3() {
		const wrapper = document.getElementById('order-form-step-3');
		if (!wrapper) {
			return;
		}

		const data = migrateLegacyRoute(loadData());
		if (!hasValidRoute(data) || !hasValidContact(data)) {
			window.location.href = stepUrl(1);
			return;
		}
		if (!data.weight || parseInt(data.weight, 10) < 1) {
			window.location.href = stepUrl(2);
			return;
		}

		const originEl = wrapper.querySelector('[data-review-origin]');
		const destEl = wrapper.querySelector('[data-review-destination]');
		const pickupEl = wrapper.querySelector('[data-review-pickup]');
		const nameEl = wrapper.querySelector('[data-review-name]');
		const phoneEl = wrapper.querySelector('[data-review-phone]');
		const cargoEl = wrapper.querySelector('[data-review-cargo]');
		const weightEl = wrapper.querySelector('[data-review-weight]');
		const serviceEl = wrapper.querySelector('[data-review-service]');
		const totalEl = wrapper.querySelector('[data-review-total]');

		if (originEl) originEl.textContent = data.origin || '—';
		if (destEl) destEl.textContent = data.destination || '—';
		if (pickupEl) pickupEl.textContent = formatPickupDisplay(data.pickup_date);
		if (nameEl) nameEl.textContent = data.full_name || '—';
		if (phoneEl) phoneEl.textContent = data.phone || '—';
		if (cargoEl) cargoEl.textContent = cargoLabels[data.cargo_type] || data.cargo_type || '—';
		if (weightEl) {
			weightEl.textContent = parseInt(data.weight, 10).toLocaleString('fa-IR') + ' کیلوگرم';
		}

		setReviewRow(wrapper, 'dims', formatDimensions(data));
		setReviewRow(wrapper, 'description', data.description ? String(data.description).trim() : '');
		setReviewRow(wrapper, 'company', data.company ? String(data.company).trim() : '');

		function updateService() {
			const selected = wrapper.querySelector('input[name="service"]:checked');
			const service = selected ? selected.value : selectService(wrapper, data.service || 'corporate');
			const price = servicePrices[service] || servicePrices.corporate;

			if (serviceEl) serviceEl.textContent = serviceLabels[service] || service;
			if (totalEl) totalEl.textContent = formatPrice(price);
			syncServiceCards(wrapper);
			mergeData({ service: service, total_price: price });
		}

		selectService(wrapper, data.service || 'corporate');

		wrapper.querySelectorAll('input[name="service"]').forEach(function (input) {
			input.addEventListener('change', updateService);
		});
		updateService();

		const submitBtn = document.getElementById('order-submit');
		const success = document.getElementById('order-success');
		if (submitBtn && success) {
			submitBtn.addEventListener('click', function () {
				updateService();
				if (!hasValidContact(loadData())) {
					window.alert('اطلاعات تماس ناقص است. لطفاً به مرحله اول برگردید.');
					window.location.href = stepUrl(1);
					return;
				}
				const payload = loadData();
				submitBtn.disabled = true;
				submitBtn.classList.add('is-loading');

				fetch(config.restUrl, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-WP-Nonce': config.nonce,
					},
					body: JSON.stringify(payload),
				})
					.then(function (response) {
						return response.json().then(function (body) {
							if (!response.ok) {
								throw new Error(body.message || 'خطا در ثبت سفارش');
							}
							return body;
						});
					})
					.then(function (result) {
						const payload = loadData();
						sessionStorage.removeItem(STORAGE_KEY);
						if (result.order_number && payload.phone) {
							if (window.samabarCustomer) {
								window.samabarCustomer.save({
									phone: payload.phone,
									full_name: payload.full_name || '',
									company: payload.company || '',
								});
							}
						}
						const numberEl = success.querySelector('[data-order-number]');
						const trackLink = success.querySelector('[data-order-track-link]');
						const dashboardLink = success.querySelector('[data-order-dashboard-link]');
						if (numberEl && result.order_number) {
							numberEl.textContent = 'شماره پیگیری: ' + result.order_number;
							numberEl.hidden = false;
						}
						if (trackLink && result.order_number) {
							const base = config.trackingUrl || '/peigiry/';
							const url = new URL(base, window.location.origin);
							url.searchParams.set('track', result.order_number);
							trackLink.href = url.toString();
						}
						if (dashboardLink && payload.phone) {
							const dashBase = config.dashboardUrl || '/panel/';
							const dashUrl = new URL(dashBase, window.location.origin);
							dashUrl.searchParams.set('phone', payload.phone);
							dashboardLink.href = dashUrl.toString();
						}
						wrapper.hidden = true;
						document.querySelector('.order-steps')?.setAttribute('hidden', '');
						success.hidden = false;
					})
					.catch(function (err) {
						submitBtn.disabled = false;
						submitBtn.classList.remove('is-loading');
						window.alert(err.message || 'ثبت سفارش انجام نشد. لطفاً دوباره تلاش کنید.');
					});
			});
		}
	}

	if (step === 1) {
		initStep1();
	} else if (step === 2) {
		initStep2();
	} else if (step === 3) {
		initStep3();
	}
})();
