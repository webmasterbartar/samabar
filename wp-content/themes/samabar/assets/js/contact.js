(function () {
	'use strict';

	const form = document.getElementById('contact-form');
	if (!form) {
		return;
	}

	const config = window.samabarContact || {};
	const notice = document.getElementById('contact-notice');
	const submitBtn = document.getElementById('contact-submit');
	const subjectInput = document.getElementById('contact-subject');

	if (subjectInput) {
		const params = new URLSearchParams(window.location.search);
		const subject = params.get('subject');
		if (subject && !subjectInput.value.trim()) {
			subjectInput.value = subject;
		}
	}

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

	function showNotice(message, type) {
		if (!notice) {
			return;
		}
		notice.textContent = message;
		notice.hidden = false;
		notice.classList.remove('is-success', 'is-error');
		notice.classList.add(type === 'success' ? 'is-success' : 'is-error');
	}

	form.addEventListener('submit', function (event) {
		event.preventDefault();

		const payload = {
			full_name: form.querySelector('#contact-name')?.value.trim() || '',
			phone: normalizePhone(form.querySelector('#contact-phone')?.value || ''),
			subject: form.querySelector('#contact-subject')?.value.trim() || '',
			message: form.querySelector('#contact-message')?.value.trim() || '',
		};

		if (submitBtn) {
			submitBtn.disabled = true;
		}

		fetch(config.restUrl || '/wp-json/samabar/v1/contact', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				Accept: 'application/json',
			},
			body: JSON.stringify(payload),
		})
			.then(function (response) {
				return response.json().then(function (body) {
					if (!response.ok) {
						throw new Error(body.message || 'خطا در ارسال پیام');
					}
					return body;
				});
			})
			.then(function (data) {
				showNotice(data.message || 'پیام شما ارسال شد.', 'success');
				form.reset();
			})
			.catch(function (err) {
				showNotice(err.message || 'ارسال پیام انجام نشد.', 'error');
			})
			.finally(function () {
				if (submitBtn) {
					submitBtn.disabled = false;
				}
			});
	});
})();
