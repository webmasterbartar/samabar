(function () {
	'use strict';

	const form = document.getElementById('tracking-form');
	const input = document.getElementById('tracking-input');
	const loading = document.getElementById('tracking-loading');
	const errorBox = document.getElementById('tracking-error');
	const result = document.getElementById('tracking-result');
	const config = window.samabarTracking || {};

	if (!form || !input) {
		return;
	}

	function show(el) {
		if (el) {
			el.hidden = false;
		}
	}

	function hide(el) {
		if (el) {
			el.hidden = true;
		}
	}

	function hideAll() {
		hide(loading);
		hide(errorBox);
		hide(result);
	}

	function formatWeight(value) {
		return Number(value || 0).toLocaleString('fa-IR') + ' کیلوگرم';
	}

	function renderSteps(data) {
		const wrap = document.querySelector('[data-tracking-steps]');
		if (!wrap || !data.steps) {
			return;
		}

		const current = data.current_step || 0;
		const percent = data.progress || 0;
		const isCancelled = data.is_cancelled;

		wrap.innerHTML =
			'<div class="tracking-steps__track" aria-hidden="true">' +
			'<span class="tracking-steps__line"></span>' +
			'<span class="tracking-steps__progress" style="width:' +
			(isCancelled ? 0 : percent) +
			'%"></span></div>' +
			'<div class="tracking-steps__list"></div>';

		const list = wrap.querySelector('.tracking-steps__list');

		data.steps.forEach(function (step, index) {
			const stepNum = index + 1;
			const isDone = !isCancelled && stepNum < current;
			const isCurrent = !isCancelled && stepNum === current;
			const item = document.createElement('div');
			item.className = 'tracking-steps__item';
			if (isDone) {
				item.classList.add('tracking-steps__item--done');
			}
			if (isCurrent) {
				item.classList.add('tracking-steps__item--current');
			}

			let marker = '';
			if (isDone) {
				marker = '<span class="material-symbols-outlined icon">check</span>';
			} else if (isCurrent) {
				marker = '<span class="tracking-steps__pulse"></span>';
			}

			item.innerHTML =
				'<span class="tracking-steps__marker">' +
				marker +
				'</span><span class="tracking-steps__label">' +
				step.label +
				'</span>';
			list.appendChild(item);
		});
	}

	function renderTimeline(events) {
		const list = document.querySelector('[data-tracking-timeline]');
		if (!list) {
			return;
		}
		list.innerHTML = '';
		(events || []).forEach(function (event, index) {
			const item = document.createElement('div');
			item.className = 'tracking-events__item' + (event.active ? ' is-active' : '');
			item.innerHTML =
				'<span class="tracking-events__dot" aria-hidden="true"></span>' +
				'<div class="tracking-events__body">' +
				'<strong>' +
				event.title +
				'</strong>' +
				'<span>' +
				event.time +
				'</span>' +
				'</div>';
			list.appendChild(item);
		});
	}

	function fillText(selector, value) {
		document.querySelectorAll(selector).forEach(function (el) {
			el.textContent = value || '—';
		});
	}

	function renderOrder(data) {
		fillText('[data-tracking-status-label]', data.status_label);
		fillText('[data-tracking-number]', data.order_number);
		fillText('[data-tracking-badge]', data.status_label);
		fillText('[data-tracking-origin]', data.origin);
		fillText('[data-tracking-destination]', data.destination);
		fillText('[data-tracking-origin-side]', data.origin);
		fillText('[data-tracking-destination-side]', data.destination);
		fillText('[data-tracking-service]', data.service_label);
		fillText('[data-tracking-cargo]', data.cargo_label);
		fillText('[data-tracking-weight]', formatWeight(data.weight));

		const pickupWrap = document.querySelector('[data-tracking-pickup-wrap]');
		if (pickupWrap) {
			if (data.pickup_date) {
				fillText('[data-tracking-pickup]', data.pickup_date);
				pickupWrap.hidden = false;
			} else {
				pickupWrap.hidden = true;
			}
		}

		renderSteps(data);
		renderTimeline(data.timeline);

		const routeVisual = document.querySelector('.tracking-route-visual');
		if (routeVisual) {
			routeVisual.style.setProperty('--tracking-progress', (data.progress || 0) + '%');
		}

		const driverCard = document.getElementById('tracking-driver');
		if (driverCard && data.driver) {
			driverCard.hidden = false;
			const nameEl = document.querySelector('[data-tracking-driver-name]');
			const plateEl = document.querySelector('[data-tracking-driver-plate]');
			const phoneLink = document.querySelector('[data-tracking-driver-phone]');
			if (nameEl) {
				nameEl.textContent = data.driver.name;
			}
			if (plateEl) {
				plateEl.textContent = data.driver.plate || '—';
			}
			if (phoneLink && data.driver.phone) {
				phoneLink.href = 'tel:' + data.driver.phone;
				phoneLink.hidden = false;
			}
		} else if (driverCard) {
			driverCard.hidden = true;
		}

		show(result);
	}

	function track(number) {
		const code = String(number || '').trim();
		if (!code) {
			return;
		}

		hideAll();
		show(loading);

		const url = new URL(config.trackUrl || '/wp-json/samabar/v1/track', window.location.origin);
		url.searchParams.set('number', code);

		fetch(url.toString(), { headers: { Accept: 'application/json' } })
			.then(function (response) {
				return response.json().then(function (body) {
					if (!response.ok) {
						throw new Error(body.message || 'سفارش یافت نشد');
					}
					return body;
				});
			})
			.then(function (data) {
				hide(loading);
				if (!data.found) {
					throw new Error(data.message || 'سفارش یافت نشد');
				}
				renderOrder(data);
			})
			.catch(function (err) {
				hide(loading);
				const msg = document.querySelector('[data-tracking-error-text]');
				if (msg) {
					msg.textContent = err.message || 'سفارشی با این کد پیگیری یافت نشد.';
				}
				show(errorBox);
			});
	}

	form.addEventListener('submit', function (event) {
		event.preventDefault();
		const value = input.value.trim();
		if (!value) {
			return;
		}
		const url = new URL(window.location.href);
		url.searchParams.set('track', value);
		window.history.replaceState({}, '', url.toString());
		track(value);
	});

	const initial = input.value.trim() || new URLSearchParams(window.location.search).get('track');
	if (initial) {
		input.value = initial;
		track(initial);
	}
})();
