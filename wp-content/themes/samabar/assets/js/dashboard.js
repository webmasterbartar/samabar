(function () {
	'use strict';

	const session = window.samabarCustomer;
	const config = window.samabarDashboard || {};

	const gate = document.getElementById('dashboard-gate');
	const gateForm = document.getElementById('dashboard-gate-form');
	const gateError = document.getElementById('dashboard-gate-error');
	const phoneInput = document.getElementById('dashboard-phone');
	const app = document.getElementById('dashboard-app');
	const loading = document.getElementById('dashboard-loading');
	const logoutBtn = document.getElementById('dashboard-logout');
	const topbarLogout = document.getElementById('dashboard-topbar-logout');
	const profileToggle = document.getElementById('dashboard-profile-toggle');
	const menuToggle = document.getElementById('dashboard-menu-toggle');
	const sidebar = document.getElementById('dashboard-sidebar');
	const overlay = document.getElementById('dashboard-overlay');

	if (!gateForm || !session) {
		return;
	}

	function formatPrice(rial) {
		const toman = Math.round(Number(rial || 0) / 10);
		return toman.toLocaleString('fa-IR');
	}

	function showGate(message) {
		if (gate) {
			gate.hidden = false;
		}
		if (app) {
			app.hidden = true;
		}
		if (loading) {
			loading.hidden = true;
		}
		if (gateError) {
			if (message) {
				gateError.textContent = message;
				gateError.hidden = false;
			} else {
				gateError.hidden = true;
			}
		}
	}

	function showApp() {
		if (gate) {
			gate.hidden = true;
		}
		if (app) {
			app.hidden = false;
		}
		if (loading) {
			loading.hidden = true;
		}
	}

	function showLoading() {
		if (loading) {
			loading.hidden = false;
		}
		if (app) {
			app.hidden = true;
		}
		if (gate) {
			gate.hidden = true;
		}
	}

	function logout() {
		session.clear();
		if (phoneInput) {
			phoneInput.value = '';
		}
		showGate('');
	}

	function renderStats(stats) {
		const wrap = document.querySelector('[data-dashboard-stats]');
		if (!wrap || !stats) {
			return;
		}

		const avg = stats.avg_delivery_days
			? stats.avg_delivery_days.toLocaleString('fa-IR') + ' روز'
			: '—';

		wrap.innerHTML =
			'<article class="dashboard-stat"><span class="dashboard-stat__label">سفارش\u200cهای فعال</span><strong class="dashboard-stat__value">' +
			Number(stats.active || 0).toLocaleString('fa-IR') +
			'</strong></article>' +
			'<article class="dashboard-stat"><span class="dashboard-stat__label">تکمیل شده</span><strong class="dashboard-stat__value">' +
			Number(stats.completed || 0).toLocaleString('fa-IR') +
			'</strong></article>' +
			'<article class="dashboard-stat"><span class="dashboard-stat__label">هزینه ماه جاری</span><strong class="dashboard-stat__value">' +
			formatPrice(stats.month_spend) +
			' <small>تومان</small></strong></article>' +
			'<article class="dashboard-stat"><span class="dashboard-stat__label">میانگین زمان تحویل</span><strong class="dashboard-stat__value">' +
			avg +
			'</strong></article>';
	}

	function renderOrderCard(order) {
		const eta = order.pickup_date ? 'بارگیری: ' + order.pickup_date : 'ثبت: ' + order.created_at;
		const price = order.price_label ? '<span class="dashboard-order-card__price">' + order.price_label + '</span>' : '';

		return (
			'<article class="dashboard-order-card">' +
			'<div class="dashboard-order-card__route">' +
			'<span class="dashboard-order-card__number">' +
			order.order_number +
			'</span>' +
			'<div class="dashboard-order-card__cities">' +
			'<strong>' +
			order.origin +
			'</strong>' +
			'<span class="material-symbols-outlined icon" aria-hidden="true">trending_flat</span>' +
			'<strong>' +
			order.destination +
			'</strong>' +
			'</div>' +
			price +
			'</div>' +
			'<div class="dashboard-order-card__status">' +
			'<span class="dashboard-order-card__badge">' +
			order.status_label +
			'</span>' +
			'<span class="dashboard-order-card__meta">' +
			eta +
			'</span></div>' +
			'<a class="btn btn--primary dashboard-order-card__track" href="' +
			order.tracking_url +
			'">پیگیری</a></article>'
		);
	}

	function renderOrders(list, selector, emptyText) {
		const wrap = document.querySelector(selector);
		if (!wrap) {
			return;
		}

		if (!list || !list.length) {
			wrap.innerHTML = '<p class="dashboard-empty">' + emptyText + '</p>';
			return;
		}

		wrap.innerHTML = list.map(renderOrderCard).join('');
	}

	function renderFeatured(order) {
		const card = document.querySelector('[data-dashboard-featured]');
		if (!card) {
			return;
		}

		if (!order) {
			card.hidden = true;
			return;
		}

		card.hidden = false;
		const numberEl = document.querySelector('[data-featured-number]');
		const originEl = document.querySelector('[data-featured-origin]');
		const destEl = document.querySelector('[data-featured-destination]');
		const progressEl = document.querySelector('[data-featured-progress]');
		const statusEl = document.querySelector('[data-featured-status]');
		const linkEl = document.querySelector('[data-featured-link]');

		if (numberEl) {
			numberEl.textContent = order.order_number;
		}
		if (originEl) {
			originEl.textContent = order.origin;
		}
		if (destEl) {
			destEl.textContent = order.destination;
		}
		if (progressEl) {
			progressEl.style.width = (order.progress || 0) + '%';
		}
		if (statusEl) {
			statusEl.textContent = order.status_label + ' — ' + order.progress + '٪ طی شده';
		}
		if (linkEl) {
			linkEl.href = order.tracking_url;
		}
	}

	function renderPayments(payments) {
		const summary = document.querySelector('[data-dashboard-payments-summary]');
		const tbody = document.querySelector('[data-dashboard-payments]');

		if (summary && payments) {
			summary.innerHTML =
				'<article class="dashboard-stat dashboard-stat--inline">' +
				'<span class="dashboard-stat__label">مجموع پرداخت\u200cها</span>' +
				'<strong class="dashboard-stat__value">' +
				formatPrice(payments.total_spent) +
				' <small>تومان</small></strong></article>' +
				'<article class="dashboard-stat dashboard-stat--inline">' +
				'<span class="dashboard-stat__label">هزینه ماه جاری</span>' +
				'<strong class="dashboard-stat__value">' +
				formatPrice(payments.month_spend) +
				' <small>تومان</small></strong></article>';
		}

		if (!tbody) {
			return;
		}

		if (!payments || !payments.recent || !payments.recent.length) {
			tbody.innerHTML =
				'<tr><td colspan="4" class="dashboard-empty">تراکنشی ثبت نشده است.</td></tr>';
			return;
		}

		tbody.innerHTML = payments.recent
			.map(function (row) {
				return (
					'<tr>' +
					'<td dir="ltr">' +
					row.order_number +
					'</td>' +
					'<td>' +
					row.created_at +
					'</td>' +
					'<td>' +
					row.price_label +
					'</td>' +
					'<td>' +
					row.status_label +
					'</td></tr>'
				);
			})
			.join('');
	}

	function renderProfile(profile, data) {
		const wrap = document.querySelector('[data-dashboard-profile]');
		if (!wrap) {
			return;
		}

		const info = profile || {};
		const name = info.full_name || data.customer_name || '—';
		const company = info.company || data.company || '—';
		const phone = info.phone || data.phone || '—';
		const totalOrders = info.total_orders != null ? info.total_orders : data.stats?.total_orders || 0;

		wrap.innerHTML =
			'<div class="dashboard-profile__card">' +
			'<div class="dashboard-profile__avatar" aria-hidden="true">' +
			'<span class="material-symbols-outlined icon">person</span></div>' +
			'<dl class="dashboard-profile__fields">' +
			'<div class="dashboard-profile__field"><dt>نام و نام خانوادگی</dt><dd>' +
			name +
			'</dd></div>' +
			'<div class="dashboard-profile__field"><dt>شرکت / سازمان</dt><dd>' +
			company +
			'</dd></div>' +
			'<div class="dashboard-profile__field"><dt>شماره موبایل</dt><dd dir="ltr">' +
			phone +
			'</dd></div>' +
			'<div class="dashboard-profile__field"><dt>تعداد سفارش\u200cها</dt><dd>' +
			Number(totalOrders).toLocaleString('fa-IR') +
			'</dd></div>' +
			'</dl></div>' +
			'<p class="dashboard-profile__note text-body-md">اطلاعات پروفایل از آخرین سفارش\u200cهای ثبت\u200cشده با این شماره موبایل استخراج شده است.</p>';
	}

	function renderDashboard(data) {
		const welcome = document.querySelector('[data-dashboard-welcome]');
		if (welcome) {
			const label = data.company || data.customer_name || 'مشتری';
			welcome.textContent = 'خوش\u200cآمدید، ' + label;
		}

		session.save({
			phone: data.phone,
			full_name: data.profile?.full_name || data.customer_name || '',
			company: data.profile?.company || data.company || '',
		});

		renderStats(data.stats);
		renderOrders(data.active_orders, '[data-dashboard-active]', 'سفارش فعالی ندارید.');
		renderOrders(data.history, '[data-dashboard-history]', 'هنوز سفارش تکمیل\u200cشده\u200cای ثبت نشده.');
		renderFeatured(data.featured);
		renderPayments(data.payments);
		renderProfile(data.profile, data);
		showApp();
	}

	function fetchDashboard(phone) {
		showLoading();

		const url = new URL(config.dashboardUrl || '/wp-json/samabar/v1/dashboard', window.location.origin);
		url.searchParams.set('phone', phone);

		return fetch(url.toString(), { headers: { Accept: 'application/json' } })
			.then(function (response) {
				return response.json().then(function (body) {
					if (!response.ok) {
						throw new Error(body.message || 'خطا در دریافت اطلاعات');
					}
					return body;
				});
			})
			.then(function (data) {
				if (!data.found) {
					throw new Error(data.message || 'سفارشی یافت نشد');
				}
				renderDashboard(data);
			})
			.catch(function (err) {
				showGate(err.message || 'سفارشی با این شماره یافت نشد.');
			});
	}

	function resolveInitialPhone() {
		const params = new URLSearchParams(window.location.search);
		const fromUrl = session.normalizePhone(params.get('phone') || '');
		if (fromUrl && session.isValidPhone(fromUrl)) {
			return fromUrl;
		}
		return session.getPhone();
	}

	gateForm.addEventListener('submit', function (event) {
		event.preventDefault();
		const phone = session.normalizePhone(phoneInput ? phoneInput.value : '');
		if (!session.isValidPhone(phone)) {
			showGate('شماره موبایل معتبر نیست.');
			return;
		}

		session.save({ phone: phone });
		fetchDashboard(phone);
	});

	[logoutBtn, topbarLogout].forEach(function (btn) {
		if (btn) {
			btn.addEventListener('click', logout);
		}
	});

	if (profileToggle) {
		profileToggle.addEventListener('click', function () {
			const profileSection = document.getElementById('profile');
			if (profileSection && app && !app.hidden) {
				profileSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
				return;
			}
			logout();
			if (phoneInput) {
				phoneInput.focus();
			}
		});
	}

	if (menuToggle && sidebar && overlay) {
		menuToggle.addEventListener('click', function () {
			const open = sidebar.classList.toggle('is-open');
			overlay.hidden = !open;
			overlay.classList.toggle('is-visible', open);
			menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});

		overlay.addEventListener('click', function () {
			sidebar.classList.remove('is-open');
			overlay.hidden = true;
			overlay.classList.remove('is-visible');
			menuToggle.setAttribute('aria-expanded', 'false');
		});

		sidebar.querySelectorAll('a[href*="#"]').forEach(function (link) {
			link.addEventListener('click', function () {
				sidebar.classList.remove('is-open');
				overlay.hidden = true;
				overlay.classList.remove('is-visible');
				menuToggle.setAttribute('aria-expanded', 'false');
			});
		});
	}

	document.querySelectorAll('[data-dashboard-scroll]').forEach(function (link) {
		link.addEventListener('click', function (event) {
			const href = link.getAttribute('href');
			if (!href || href.charAt(0) !== '#') {
				return;
			}
			const target = document.querySelector(href);
			if (target) {
				event.preventDefault();
				target.scrollIntoView({ behavior: 'smooth', block: 'start' });
			}
		});
	});

	const initialPhone = resolveInitialPhone();
	if (initialPhone) {
		if (phoneInput) {
			phoneInput.value = initialPhone;
		}
		fetchDashboard(initialPhone);
	} else {
		showGate('');
	}
})();
