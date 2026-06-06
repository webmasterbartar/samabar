(function () {
	'use strict';

	const STORAGE_KEY = 'samabar_order';
	const main = document.querySelector('.site-main--order');
	const form = document.getElementById('order-form');

	if (!main || !form) {
		return;
	}

	const config = window.samabarOrder || {};
	const summary = form.querySelector('[data-order-summary]');
	const success = document.getElementById('order-success');
	const submitBtn = document.getElementById('order-submit');
	const emptyFreightHtml = '<span class="order-summary__freight-mask">＊ ＊ ＊ ＊ ＊</span>';

	let freightReady = false;
	let freightTimer = null;

	function requestFreight(data) {
		if (!window.samabarTariff) {
			return Promise.reject(new Error('سیستم محاسبه کرایه در دسترس نیست.'));
		}
		return window.samabarTariff.requestFreight(
			data.origin_city || data.origin,
			data.destination_city || data.destination,
			parseInt(data.weight, 10)
		);
	}

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

	function formatAddress(city, address, detail) {
		return [city, address, detail].filter(function (part) {
			return part && String(part).trim();
		}).join(' — ');
	}

	function collectRouteFromForm() {
		return {
			origin_city: form.querySelector('#order-origin-city')?.value.trim() || '',
			origin_address: form.querySelector('#order-origin-address')?.value.trim() || '',
			destination_city: form.querySelector('#order-destination-city')?.value.trim() || '',
			destination_address: form.querySelector('#order-destination-address')?.value.trim() || '',
		};
	}

	function withRouteSummary(route) {
		return Object.assign({}, route, {
			origin: formatAddress(route.origin_city, route.origin_address),
			destination: formatAddress(route.destination_city, route.destination_address),
		});
	}

	function collectContactFields() {
		return {
			full_name: form.querySelector('#order-full-name')?.value.trim() || '',
			phone: normalizePhone(form.querySelector('#order-phone')?.value || ''),
			company: form.querySelector('#order-company')?.value.trim() || '',
		};
	}

	function collectFormData() {
		return Object.assign(
			withRouteSummary(collectRouteFromForm()),
			collectContactFields(),
			{
				pickup_date: form.querySelector('#order-pickup-date')?.value || '',
				weight: form.querySelector('#order-weight')?.value.trim() || '',
				dim_length: form.querySelector('#order-dim-length')?.value.trim() || '',
				dim_width: form.querySelector('#order-dim-width')?.value.trim() || '',
				dim_height: form.querySelector('#order-dim-height')?.value.trim() || '',
				description: form.querySelector('#order-description')?.value.trim() || '',
				service: 'corporate',
			}
		);
	}

	function populateForm(data) {
		const prepared = Object.assign({}, data);

		if (window.samabarRoute && window.samabarRoute.resolveCity) {
			if (prepared.origin_city) {
				prepared.origin_city = window.samabarRoute.resolveCity(prepared.origin_city) || prepared.origin_city;
			}
			if (prepared.destination_city) {
				prepared.destination_city =
					window.samabarRoute.resolveCity(prepared.destination_city) || prepared.destination_city;
			}
		}

		Object.keys(prepared).forEach(function (key) {
			const fields = form.elements.namedItem(key);
			if (!fields || fields.type === 'radio') {
				return;
			}
			fields.value = prepared[key];
		});
	}

	function migrateLegacyRoute(data) {
		if (data.origin && !data.origin_city) {
			data.origin_city = data.origin;
		}
		if (data.destination && !data.destination_city) {
			data.destination_city = data.destination;
		}
		if (data.origin_city && data.origin_address) {
			data.origin = formatAddress(data.origin_city, data.origin_address);
		}
		if (data.destination_city && data.destination_address) {
			data.destination = formatAddress(data.destination_city, data.destination_address);
		}
		return data;
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

	function isAllowedRoute(originCity, destinationCity) {
		if (!window.samabarRoute) {
			return true;
		}
		return window.samabarRoute.validateRoute(originCity, destinationCity).valid;
	}

	function showRouteNotice(check) {
		const notice = form.querySelector('[data-route-notice]');
		const statusWrap = document.querySelector('.order-route-status');
		const statusText = document.querySelector('[data-map-status]');

		if (notice) {
			notice.hidden = check.valid;
			if (!check.valid) {
				notice.querySelector('[data-route-notice-text]').textContent = check.message;
			}
		}

		if (statusWrap && statusText) {
			statusWrap.classList.toggle('is-invalid', !check.valid);
			statusWrap.classList.toggle('is-valid', check.valid);
			if (!check.valid) {
				statusText.textContent = check.message;
			}
		}

		form.querySelector('#order-origin-city')?.classList.toggle('route-field-error', !check.valid);
		form.querySelector('#order-destination-city')?.classList.toggle('route-field-error', !check.valid);
	}

	function validateRoute() {
		const route = collectRouteFromForm();

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

		if (window.samabarRoute) {
			const check = window.samabarRoute.validateRoute(route.origin_city, route.destination_city);
			showRouteNotice(check);
			if (!check.valid) {
				window.alert(check.message);
				form.querySelector('#order-destination-city')?.focus();
				return false;
			}
		}

		return true;
	}

	function validateContact() {
		const contact = collectContactFields();
		const phoneInput = form.querySelector('#order-phone');

		if (!contact.full_name) {
			window.alert('لطفاً نام و نام خانوادگی را وارد کنید.');
			form.querySelector('#order-full-name')?.focus();
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

	function formatPrice(rial) {
		if (window.samabarMoney) {
			return window.samabarMoney.format(rial);
		}
		return Math.round(Number(rial || 0) / 10).toLocaleString('fa-IR') + ' تومان';
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

	function setReviewRow(rowKey, value) {
		if (!summary) {
			return;
		}
		const row = summary.querySelector('[data-review-row="' + rowKey + '"]');
		const target = summary.querySelector('[data-review-' + rowKey + ']');

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

	function setFreightDisplay(html) {
		const totalEl = summary?.querySelector('[data-review-total]');
		if (totalEl) {
			totalEl.innerHTML = html;
		}
	}

	function canCalculateFreight(data) {
		return (
			data.origin_city &&
			data.destination_city &&
			isAllowedRoute(data.origin_city, data.destination_city) &&
			parseInt(data.weight, 10) >= 1
		);
	}

	function syncSummary() {
		const data = collectFormData();
		mergeData(data);

		if (!summary) {
			return data;
		}

		const originEl = summary.querySelector('[data-review-origin]');
		const destEl = summary.querySelector('[data-review-destination]');
		const pickupEl = summary.querySelector('[data-review-pickup]');
		const nameEl = summary.querySelector('[data-review-name]');
		const phoneEl = summary.querySelector('[data-review-phone]');
		const weightEl = summary.querySelector('[data-review-weight]');
		const statusText = document.querySelector('[data-map-status]');

		if (originEl) {
			originEl.textContent = data.origin || '—';
		}
		if (destEl) {
			destEl.textContent = data.destination || '—';
		}
		if (pickupEl) {
			pickupEl.textContent = formatPickupDisplay(data.pickup_date);
		}
		if (nameEl) {
			nameEl.textContent = data.full_name || '—';
		}
		if (phoneEl) {
			phoneEl.textContent = data.phone || '—';
		}
		if (weightEl) {
			weightEl.textContent = data.weight
				? parseInt(data.weight, 10).toLocaleString('fa-IR') + ' کیلوگرم'
				: '—';
		}

		setReviewRow('dims', formatDimensions(data));
		setReviewRow('description', data.description || '');
		setReviewRow('company', data.company || '');

		if (statusText) {
			if (data.origin_city && data.destination_city && window.samabarRoute) {
				const check = window.samabarRoute.validateRoute(data.origin_city, data.destination_city);
				showRouteNotice(check);
				if (check.valid && data.origin && data.destination) {
					statusText.textContent = data.origin + ' ← ' + data.destination;
				} else if (!check.valid) {
					statusText.textContent = check.message;
				}
			} else if (data.origin || data.destination) {
				statusText.textContent = (data.origin || '…') + ' ← ' + (data.destination || '…');
			} else {
				statusText.textContent = 'مسیر هنوز مشخص نشده است';
			}
		}

		return data;
	}

	function refreshFreight() {
		const data = syncSummary();

		if (!canCalculateFreight(data)) {
			freightReady = false;
			setFreightDisplay(emptyFreightHtml);
			if (submitBtn) {
				submitBtn.disabled = false;
			}
			return Promise.resolve();
		}

		freightReady = false;
		setFreightDisplay('در حال محاسبه...');
		if (submitBtn) {
			submitBtn.disabled = true;
		}

		return requestFreight(data)
			.then(function (freight) {
				setFreightDisplay(formatPrice(freight.amount));
				mergeData({ total_price: freight.amount });
				freightReady = true;
				if (submitBtn) {
					submitBtn.disabled = false;
				}
			})
			.catch(function (err) {
				freightReady = false;
				setFreightDisplay('—');
				showRouteNotice({
					valid: false,
					message: err.message || 'محاسبه کرایه ممکن نیست.',
				});
				if (submitBtn) {
					submitBtn.disabled = false;
				}
			});
	}

	function scheduleFreightRefresh() {
		clearTimeout(freightTimer);
		freightTimer = setTimeout(refreshFreight, 350);
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

	function submitOrder() {
		const data = mergeData(collectFormData());

		if (!freightReady) {
			return refreshFreight().then(function () {
				if (freightReady) {
					submitOrder();
				}
			});
		}

		if (!submitBtn || !success) {
			return;
		}

		submitBtn.disabled = true;
		submitBtn.classList.add('is-loading');

		fetch(config.restUrl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-WP-Nonce': config.nonce,
			},
			body: JSON.stringify(data),
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
				sessionStorage.removeItem(STORAGE_KEY);
				if (result.order_number && data.phone && window.samabarCustomer) {
					window.samabarCustomer.save({
						phone: data.phone,
						full_name: data.full_name || '',
						company: data.company || '',
					});
				}

				const numberEl = success.querySelector('[data-order-number]');
				const trackLink = success.querySelector('[data-order-track-link]');
				const dashboardLink = success.querySelector('[data-order-dashboard-link]');

				if (numberEl && result.order_number) {
					numberEl.textContent = 'شماره پیگیری: ' + result.order_number;
					numberEl.hidden = false;
				}
				if (trackLink && result.order_number) {
					const url = new URL(config.trackingUrl || '/peigiry/', window.location.origin);
					url.searchParams.set('track', result.order_number);
					trackLink.href = url.toString();
				}
				if (dashboardLink && data.phone) {
					const url = new URL(config.dashboardUrl || '/panel/', window.location.origin);
					url.searchParams.set('phone', data.phone);
					dashboardLink.href = url.toString();
				}

				form.hidden = true;
				success.hidden = false;
				window.scrollTo({ top: 0, behavior: 'smooth' });
			})
			.catch(function (err) {
				submitBtn.disabled = false;
				submitBtn.classList.remove('is-loading');
				window.alert(err.message || 'ثبت سفارش انجام نشد. لطفاً دوباره تلاش کنید.');
			});
	}

	function initFromQuery() {
		const data = migrateLegacyRoute(loadData());
		const params = new URLSearchParams(window.location.search);

		if (params.get('origin')) {
			const origin = params.get('origin');
			data.origin_city = window.samabarRoute?.resolveCity(origin) || origin;
		}
		if (params.get('destination')) {
			const destination = params.get('destination');
			data.destination_city = window.samabarRoute?.resolveCity(destination) || destination;
		}
		if (params.get('weight')) {
			data.weight = params.get('weight');
		}
		if (params.get('pickup_date')) {
			data.pickup_date = params.get('pickup_date');
		}

		saveData(migrateLegacyRoute(data));
		populateForm(data);
	}

	function bindFieldSync() {
		const selectors = [
			'#order-origin-city',
			'#order-origin-address',
			'#order-destination-city',
			'#order-destination-address',
			'#order-weight',
			'#order-dim-length',
			'#order-dim-width',
			'#order-dim-height',
			'#order-description',
			'#order-full-name',
			'#order-phone',
			'#order-company',
		];

		selectors.forEach(function (selector) {
			const field = form.querySelector(selector);
			if (!field) {
				return;
			}
			const handler = function () {
				syncSummary();
				if (
					selector === '#order-origin-city' ||
					selector === '#order-destination-city' ||
					selector === '#order-weight'
				) {
					scheduleFreightRefresh();
				}
				if (selector === '#order-phone' || selector === '#order-full-name' || selector === '#order-company') {
					persistCustomerSession(collectContactFields());
				}
			};
			field.addEventListener('input', handler);
			field.addEventListener('change', handler);
		});

		const pickupInput = form.querySelector('#order-pickup-date');
		if (pickupInput) {
			pickupInput.addEventListener('change', function () {
				syncSummary();
			});
		}
	}

	initFromQuery();

	if (window.SamabarPersianDatetime && document.getElementById('order-pickup-datetime')) {
		window.SamabarPersianDatetime.init(document.getElementById('order-pickup-datetime'));
	}

	bindFieldSync();
	syncSummary();
	refreshFreight();

	form.addEventListener('submit', function (event) {
		event.preventDefault();

		const weight = form.querySelector('#order-weight')?.value;
		if (!weight || parseInt(weight, 10) < 1) {
			window.alert('لطفاً وزن محموله را وارد کنید.');
			form.querySelector('#order-weight')?.focus();
			return;
		}

		if (!validateRoute() || !validateContact()) {
			return;
		}

		mergeData(collectFormData());
		persistCustomerSession(collectContactFields());
		submitOrder();
	});
})();
