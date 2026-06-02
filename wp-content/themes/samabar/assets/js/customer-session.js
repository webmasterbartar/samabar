(function (window) {
	'use strict';

	const STORAGE_KEY = 'samabar_customer';

	function normalizePhone(value) {
		let phone = String(value || '').replace(/\D+/g, '');
		if (phone.indexOf('98') === 0 && phone.length === 12) {
			phone = '0' + phone.slice(2);
		}
		if (phone.length === 10 && phone.charAt(0) === '9') {
			phone = '0' + phone;
		}
		return phone;
	}

	function isValidPhone(value) {
		return /^09\d{9}$/.test(normalizePhone(value));
	}

	function load() {
		try {
			return JSON.parse(localStorage.getItem(STORAGE_KEY) || 'null');
		} catch (e) {
			return null;
		}
	}

	function save(data) {
		const current = load() || {};
		const next = Object.assign({}, current, data || {});
		if (next.phone) {
			next.phone = normalizePhone(next.phone);
		}
		localStorage.setItem(STORAGE_KEY, JSON.stringify(next));
		return next;
	}

	function clear() {
		localStorage.removeItem(STORAGE_KEY);
	}

	function getPhone() {
		const customer = load();
		return customer && customer.phone ? normalizePhone(customer.phone) : '';
	}

	window.samabarCustomer = {
		load: load,
		save: save,
		clear: clear,
		getPhone: getPhone,
		normalizePhone: normalizePhone,
		isValidPhone: isValidPhone,
		STORAGE_KEY: STORAGE_KEY,
	};
})(window);
