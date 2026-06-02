/**
 * Persian (Jalali) datetime picker — centered modal with pickup availability.
 */
(function () {
	'use strict';

	const J = window.SamabarJalali;
	if (!J) {
		return;
	}

	function initPersianDatetimePicker(container) {
		const hidden = container.querySelector('[data-persian-datetime-value]');
		const trigger = container.querySelector('[data-persian-datetime-trigger]');
		const triggerText = container.querySelector('.order-datetime__trigger-text');
		const modal = container.querySelector('[data-persian-datetime-modal]');
		const panel = container.querySelector('[data-persian-datetime-panel]');
		const preview = container.querySelector('[data-persian-datetime-preview]');
		const grid = container.querySelector('[data-persian-datetime-days]');
		const monthLabel = container.querySelector('[data-persian-datetime-month]');
		const hourSelect = container.querySelector('[data-persian-datetime-hour]');
		const minuteSelect = container.querySelector('[data-persian-datetime-minute]');
		const backdrop = container.querySelector('[data-persian-datetime-backdrop]');
		const config = window.samabarOrder || {};

		if (!hidden || !trigger || !modal || !panel || !grid) {
			return;
		}

		const today = J.todayJalaali();
		let viewYear = today.jy;
		let viewMonth = today.jm;
		let selected = J.parseStorage(hidden.value);
		const availabilityCache = {};
		let availabilityLoading = false;

		if (!selected) {
			selected = {
				jy: today.jy,
				jm: today.jm,
				jd: today.jd,
				hour: 9,
				minute: 0,
			};
		} else {
			viewYear = selected.jy;
			viewMonth = selected.jm;
		}

		function monthCacheKey(year, month) {
			return year + '-' + month;
		}

		function getDayStatus(year, month, day) {
			const dateKey = J.formatDateKey(year, month, day);

			if (J.isBeforeToday(year, month, day)) {
				return 'past';
			}

			const monthData = availabilityCache[monthCacheKey(year, month)];
			if (monthData && monthData.days && monthData.days[dateKey]) {
				return monthData.days[dateKey].status;
			}

			return 'available';
		}

		function isDaySelectable(year, month, day) {
			return getDayStatus(year, month, day) === 'available';
		}

		function fetchAvailability(year, month) {
			const cacheKey = monthCacheKey(year, month);

			if (availabilityCache[cacheKey]) {
				return Promise.resolve(availabilityCache[cacheKey]);
			}

			if (!config.availabilityUrl) {
				availabilityCache[cacheKey] = { days: {} };
				return Promise.resolve(availabilityCache[cacheKey]);
			}

			const url = new URL(config.availabilityUrl, window.location.origin);
			url.searchParams.set('year', String(year));
			url.searchParams.set('month', String(month));

			availabilityLoading = true;
			grid.classList.add('is-loading');

			return fetch(url.toString(), {
				headers: {
					Accept: 'application/json',
				},
			})
				.then(function (response) {
					if (!response.ok) {
						throw new Error('availability request failed');
					}
					return response.json();
				})
				.then(function (data) {
					availabilityCache[cacheKey] = data;
					return data;
				})
				.catch(function () {
					availabilityCache[cacheKey] = { days: {} };
					return availabilityCache[cacheKey];
				})
				.finally(function () {
					availabilityLoading = false;
					grid.classList.remove('is-loading');
				});
		}

		function syncTimeSelects() {
			if (hourSelect) {
				hourSelect.value = String(selected.hour);
			}
			if (minuteSelect && minuteSelect.options.length) {
				const options = Array.from(minuteSelect.options).map(function (opt) {
					return parseInt(opt.value, 10);
				});
				let nearest = options[0];
				options.forEach(function (minute) {
					if (Math.abs(minute - selected.minute) < Math.abs(nearest - selected.minute)) {
						nearest = minute;
					}
				});
				selected.minute = nearest;
				minuteSelect.value = String(nearest);
			}
		}

		function updatePreview() {
			if (!preview) {
				return;
			}
			preview.textContent = J.formatDisplay(
				selected.jy,
				selected.jm,
				selected.jd,
				selected.hour,
				selected.minute
			);
		}

		function updateTriggerLabel() {
			const labelEl = triggerText || trigger;
			if (hidden.value) {
				const parsed = J.parseStorage(hidden.value);
				if (parsed) {
					labelEl.textContent = J.formatDisplay(parsed.jy, parsed.jm, parsed.jd, parsed.hour, parsed.minute);
					trigger.classList.add('has-value');
					return;
				}
			}
			labelEl.textContent = trigger.dataset.placeholder || 'انتخاب روز و ساعت...';
			trigger.classList.remove('has-value');
		}

		function ensureSelectedDayIsValid() {
			if (!isDaySelectable(selected.jy, selected.jm, selected.jd)) {
				selected.jy = today.jy;
				selected.jm = today.jm;
				selected.jd = today.jd;

				if (!isDaySelectable(selected.jy, selected.jm, selected.jd)) {
					const monthData = availabilityCache[monthCacheKey(selected.jy, selected.jm)];
					if (monthData && monthData.days) {
						const firstAvailable = Object.keys(monthData.days).find(function (dateKey) {
							return monthData.days[dateKey].status === 'available';
						});
						if (firstAvailable) {
							const parts = firstAvailable.split('/');
							selected.jy = parseInt(parts[0], 10);
							selected.jm = parseInt(parts[1], 10);
							selected.jd = parseInt(parts[2], 10);
						}
					}
				}
			}
		}

		function commitSelection() {
			if (!isDaySelectable(selected.jy, selected.jm, selected.jd)) {
				window.alert('این روز قابل رزرو نیست. لطفاً روز خالی (سبز) انتخاب کنید.');
				return false;
			}

			if (hourSelect) {
				selected.hour = parseInt(hourSelect.value, 10) || 0;
			}
			if (minuteSelect) {
				selected.minute = parseInt(minuteSelect.value, 10) || 0;
			}
			hidden.value = J.formatStorage(selected.jy, selected.jm, selected.jd, selected.hour, selected.minute);
			hidden.dispatchEvent(new Event('change', { bubbles: true }));
			updateTriggerLabel();
			return true;
		}

		function renderCalendar() {
			monthLabel.textContent =
				J.MONTH_NAMES[viewMonth - 1] + ' ' + J.toPersianDigits(viewYear);

			grid.innerHTML = '';

			J.WEEKDAY_NAMES.forEach(function (name) {
				const head = document.createElement('span');
				head.className = 'order-datetime__weekday';
				head.textContent = name;
				grid.appendChild(head);
			});

			const firstGregorian = J.toGregorian(viewYear, viewMonth, 1);
			const firstDate = new Date(firstGregorian.gy, firstGregorian.gm - 1, firstGregorian.gd);
			const startOffset = (firstDate.getDay() + 1) % 7;

			for (let i = 0; i < startOffset; i += 1) {
				const empty = document.createElement('span');
				empty.className = 'order-datetime__day order-datetime__day--empty';
				empty.setAttribute('aria-hidden', 'true');
				grid.appendChild(empty);
			}

			const daysInMonth = J.jalaaliMonthLength(viewYear, viewMonth);

			for (let day = 1; day <= daysInMonth; day += 1) {
				const btn = document.createElement('button');
				const status = getDayStatus(viewYear, viewMonth, day);
				btn.type = 'button';
				btn.className = 'order-datetime__day order-datetime__day--' + status;
				btn.textContent = J.toPersianDigits(day);

				const isToday = viewYear === today.jy && viewMonth === today.jm && day === today.jd;
				const isSelected =
					selected.jy === viewYear && selected.jm === viewMonth && selected.jd === day;

				if (isToday) {
					btn.classList.add('order-datetime__day--today');
				}
				if (isSelected) {
					btn.classList.add('order-datetime__day--selected');
				}

				if (status === 'full') {
					btn.disabled = true;
					btn.title = 'این روز پر است';
					btn.setAttribute('aria-label', 'روز ' + J.toPersianDigits(day) + ' — رزرو شده');
				} else if (status === 'past') {
					btn.disabled = true;
					btn.title = 'روز گذشته';
					btn.setAttribute('aria-label', 'روز ' + J.toPersianDigits(day) + ' — گذشته');
				} else {
					btn.title = 'روز خالی — قابل رزرو';
					btn.setAttribute('aria-label', 'روز ' + J.toPersianDigits(day) + ' — خالی');
				}

				if (status === 'available') {
					btn.addEventListener('click', function () {
						selected.jy = viewYear;
						selected.jm = viewMonth;
						selected.jd = day;
						renderCalendar();
						updatePreview();
					});
				}

				grid.appendChild(btn);
			}
		}

		function loadMonthAndRender() {
			return fetchAvailability(viewYear, viewMonth).then(function () {
				ensureSelectedDayIsValid();
				renderCalendar();
				updatePreview();
			});
		}

		function openPanel() {
			document.body.appendChild(modal);
			modal.hidden = false;
			container.classList.add('is-open');
			trigger.setAttribute('aria-expanded', 'true');
			document.body.classList.add('is-datetime-open');
			viewYear = selected.jy;
			viewMonth = selected.jm;
			loadMonthAndRender().then(function () {
				syncTimeSelects();
				panel.querySelector('[data-persian-datetime-confirm]')?.focus();
			});
		}

		function closePanel() {
			modal.hidden = true;
			container.classList.remove('is-open');
			trigger.setAttribute('aria-expanded', 'false');
			document.body.classList.remove('is-datetime-open');
			container.appendChild(modal);
		}

		container.querySelector('[data-persian-datetime-prev]')?.addEventListener('click', function () {
			viewMonth -= 1;
			if (viewMonth < 1) {
				viewMonth = 12;
				viewYear -= 1;
			}
			loadMonthAndRender();
		});

		container.querySelector('[data-persian-datetime-next]')?.addEventListener('click', function () {
			viewMonth += 1;
			if (viewMonth > 12) {
				viewMonth = 1;
				viewYear += 1;
			}
			loadMonthAndRender();
		});

		container.querySelector('[data-persian-datetime-today]')?.addEventListener('click', function () {
			if (!isDaySelectable(today.jy, today.jm, today.jd)) {
				window.alert('امروز ظرفیت رزرو ندارد. روز دیگری انتخاب کنید.');
				return;
			}
			viewYear = today.jy;
			viewMonth = today.jm;
			selected.jy = today.jy;
			selected.jm = today.jm;
			selected.jd = today.jd;
			loadMonthAndRender();
		});

		container.querySelector('[data-persian-datetime-clear]')?.addEventListener('click', function () {
			hidden.value = '';
			trigger.classList.remove('has-value');
			const labelEl = triggerText || trigger;
			labelEl.textContent = trigger.dataset.placeholder || 'انتخاب روز و ساعت...';
			closePanel();
			hidden.dispatchEvent(new Event('change', { bubbles: true }));
		});

		container.querySelector('[data-persian-datetime-confirm]')?.addEventListener('click', function () {
			if (commitSelection()) {
				closePanel();
			}
		});

		container.querySelector('[data-persian-datetime-close]')?.addEventListener('click', closePanel);
		backdrop?.addEventListener('click', closePanel);

		trigger.addEventListener('click', function (event) {
			event.stopPropagation();
			if (modal.hidden) {
				openPanel();
			} else {
				closePanel();
			}
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' && !modal.hidden) {
				closePanel();
			}
		});

		hourSelect?.addEventListener('change', function () {
			selected.hour = parseInt(hourSelect.value, 10) || 0;
			updatePreview();
		});

		minuteSelect?.addEventListener('change', function () {
			selected.minute = parseInt(minuteSelect.value, 10) || 0;
			updatePreview();
		});

		if (hourSelect && !hourSelect.options.length) {
			for (let h = 0; h < 24; h += 1) {
				const opt = document.createElement('option');
				opt.value = String(h);
				opt.textContent = J.toPersianDigits(J.pad2(h));
				hourSelect.appendChild(opt);
			}
		}

		if (minuteSelect && !minuteSelect.options.length) {
			[0, 15, 30, 45].forEach(function (m) {
				const opt = document.createElement('option');
				opt.value = String(m);
				opt.textContent = J.toPersianDigits(J.pad2(m));
				minuteSelect.appendChild(opt);
			});
		}

		syncTimeSelects();
		updateTriggerLabel();
		fetchAvailability(viewYear, viewMonth);

		return {
			setValue: function (value) {
				hidden.value = value || '';
				const parsed = J.parseStorage(value);
				if (parsed) {
					selected = parsed;
					viewYear = parsed.jy;
					viewMonth = parsed.jm;
				}
				updateTriggerLabel();
			},
			getValue: function () {
				return hidden.value;
			},
			refreshAvailability: function () {
				delete availabilityCache[monthCacheKey(viewYear, viewMonth)];
				return loadMonthAndRender();
			},
		};
	}

	window.SamabarPersianDatetime = {
		init: initPersianDatetimePicker,
	};
})();
