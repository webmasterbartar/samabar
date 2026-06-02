/**
 * Jalali (Persian) calendar conversion utilities.
 * Based on the jalaali algorithm.
 */
(function (global) {
	'use strict';

	function div(a, b) {
		return ~~(a / b);
	}

	function mod(a, b) {
		return a - ~~(a / b) * b;
	}

	function isLeapJalaaliYear(jy) {
		const breaks = [
			-61, 9, 38, 199, 426, 686, 756, 818, 1111, 1181, 1210, 1635, 2060, 2097, 2192, 2262,
			2324, 2394, 2456, 3178,
		];
		let bl = breaks.length;
		let jp = breaks[0];
		let jump = 0;
		let leap = 0;
		let n;
		let i;

		if (jy < jp || jy >= breaks[bl - 1]) {
			throw new Error('Invalid Jalaali year ' + jy);
		}

		for (i = 1; i < bl; i += 1) {
			jump = breaks[i] - jp;
			if (jy < breaks[i]) {
				break;
			}
			jp = breaks[i];
		}

		n = jy - jp;

		if (jump - n < 6) {
			n = n - jump + div(jump + 4, 33) * 33;
		}

		leap = mod(mod(n + 1, 33) - 1, 4);
		if (leap === -1) {
			leap = 4;
		}

		return leap === 0;
	}

	function jalaaliMonthLength(jy, jm) {
		if (jm <= 6) {
			return 31;
		}
		if (jm <= 11) {
			return 30;
		}
		return isLeapJalaaliYear(jy) ? 30 : 29;
	}

	function toJalaali(gy, gm, gd) {
		const gdm = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
		let jy = gy <= 1600 ? 0 : 979;
		let gy2 = gy - (gy <= 1600 ? 621 : 1600);
		let days =
			365 * gy2 +
			div(gy2 + 3, 4) -
			div(gy2 + 99, 100) +
			div(gy2 + 399, 400) -
			80 +
			gd +
			gdm[gm - 1];

		jy += 33 * div(days, 12053);
		days = mod(days, 12053);
		jy += 4 * div(days, 1461);
		days = mod(days, 1461);

		if (days > 365) {
			jy += div(days - 1, 365);
			days = mod(days - 1, 365);
		}

		let jm = days < 186 ? 1 + div(days, 31) : 7 + div(days - 186, 30);
		let jd = 1 + mod(days < 186 ? days : days - 186, days < 186 ? 31 : 30);

		return { jy: jy, jm: jm, jd: jd };
	}

	function toGregorian(jy, jm, jd) {
		let gy = jy <= 979 ? 621 : 1600;
		jy -= jy <= 979 ? 0 : 979;
		let days =
			365 * jy +
			div(jy, 33) * 8 +
			div(mod(jy, 33) + 3, 4) +
			78 +
			jd +
			(jm < 7 ? (jm - 1) * 31 : (jm - 7) * 30 + 186);
		gy += 400 * div(days, 146097);
		days = mod(days, 146097);

		if (days > 36524) {
			gy += 100 * div(--days, 36524);
			days = mod(days, 36524);
			if (days >= 365) {
				days += 1;
			}
		}

		gy += 4 * div(days, 1461);
		days = mod(days, 1461);

		if (days > 365) {
			gy += div(days - 1, 365);
			days = mod(days - 1, 365);
		}

		let gd = days + 1;
		const salA = [
			0, 31, (gy % 4 === 0 && gy % 100 !== 0) || gy % 400 === 0 ? 29 : 28, 31, 30, 31, 30, 31,
			31, 30, 31, 30, 31,
		];
		let gm = 0;

		for (gm = 0; gm < 13 && gd > salA[gm]; gm += 1) {
			gd -= salA[gm];
		}

		return { gy: gy, gm: gm, gd: gd };
	}

	function todayJalaali() {
		const now = new Date();
		return toJalaali(now.getFullYear(), now.getMonth() + 1, now.getDate());
	}

	function pad2(n) {
		return String(n).padStart(2, '0');
	}

	function toPersianDigits(str) {
		return String(str).replace(/\d/g, function (d) {
			return '۰۱۲۳۴۵۶۷۸۹'[d];
		});
	}

	function formatDisplay(jy, jm, jd, hour, minute) {
		return (
			toPersianDigits(jy + '/' + pad2(jm) + '/' + pad2(jd)) +
			' — ' +
			toPersianDigits(pad2(hour) + ':' + pad2(minute))
		);
	}

	function formatStorage(jy, jm, jd, hour, minute) {
		return jy + '/' + pad2(jm) + '/' + pad2(jd) + ' ' + pad2(hour) + ':' + pad2(minute);
	}

	function parseStorage(value) {
		if (!value) {
			return null;
		}
		const match = String(value).match(/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})(?:\s+(\d{1,2}):(\d{2}))?/);
		if (!match) {
			return null;
		}
		return {
			jy: parseInt(match[1], 10),
			jm: parseInt(match[2], 10),
			jd: parseInt(match[3], 10),
			hour: match[4] !== undefined ? parseInt(match[4], 10) : 9,
			minute: match[5] !== undefined ? parseInt(match[5], 10) : 0,
		};
	}

	function formatDateKey(jy, jm, jd) {
		return jy + '/' + pad2(jm) + '/' + pad2(jd);
	}

	function isBeforeToday(jy, jm, jd) {
		const today = todayJalaali();
		if (jy < today.jy) {
			return true;
		}
		if (jy > today.jy) {
			return false;
		}
		if (jm < today.jm) {
			return true;
		}
		if (jm > today.jm) {
			return false;
		}
		return jd < today.jd;
	}

	global.SamabarJalali = {
		toJalaali: toJalaali,
		toGregorian: toGregorian,
		jalaaliMonthLength: jalaaliMonthLength,
		isLeapJalaaliYear: isLeapJalaaliYear,
		todayJalaali: todayJalaali,
		toPersianDigits: toPersianDigits,
		formatDisplay: formatDisplay,
		formatStorage: formatStorage,
		parseStorage: parseStorage,
		formatDateKey: formatDateKey,
		isBeforeToday: isBeforeToday,
		pad2: pad2,
		MONTH_NAMES: [
			'فروردین',
			'اردیبهشت',
			'خرداد',
			'تیر',
			'مرداد',
			'شهریور',
			'مهر',
			'آبان',
			'آذر',
			'دی',
			'بهمن',
			'اسفند',
		],
		WEEKDAY_NAMES: ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'],
	};
})(window);
