(function () {
	'use strict';

	const accordion = document.getElementById('faq-accordion');
	if (!accordion) {
		return;
	}

	const searchInput = document.getElementById('faq-search');
	const emptyState = document.getElementById('faq-empty');
	const asideTitle = document.querySelector('[data-faq-aside-title]');
	const asideDesc = document.querySelector('[data-faq-aside-desc]');
	const categoryButtons = document.querySelectorAll('[data-faq-category]');
	const items = accordion.querySelectorAll('[data-faq-item]');

	let activeCategory = accordion.dataset.faqDefault || 'order';
	let categories = {};

	try {
		const dataEl = document.getElementById('faq-categories-data');
		if (dataEl) {
			categories = JSON.parse(dataEl.textContent || '{}');
		}
	} catch (e) {
		categories = {};
	}

	function updateAside(category) {
		const meta = categories[category];
		if (!meta) {
			return;
		}
		if (asideTitle) {
			asideTitle.textContent = meta.label || '';
		}
		if (asideDesc) {
			asideDesc.textContent = meta.description || '';
		}
	}

	function setActiveCategory(category) {
		activeCategory = category;
		categoryButtons.forEach(function (btn) {
			const isActive = btn.dataset.faqCategory === category;
			btn.classList.toggle('faq-category--active', isActive);
			btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
		});
		updateAside(category);
		applyFilters();
	}

	function applyFilters() {
		const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
		let visible = 0;

		items.forEach(function (item) {
			const inCategory = item.dataset.faqCategory === activeCategory;
			const text = item.textContent.toLowerCase();
			const matches = !query || text.indexOf(query) >= 0;
			const show = inCategory && matches;

			item.hidden = !show;
			if (show) {
				visible += 1;
			}
		});

		if (emptyState) {
			emptyState.hidden = visible > 0;
		}
	}

	categoryButtons.forEach(function (btn) {
		btn.addEventListener('click', function () {
			setActiveCategory(btn.dataset.faqCategory);
		});
	});

	if (searchInput) {
		searchInput.addEventListener('input', applyFilters);
	}

	items.forEach(function (item) {
		item.addEventListener('toggle', function () {
			if (!item.open) {
				return;
			}
			items.forEach(function (other) {
				if (other !== item) {
					other.open = false;
				}
			});
		});
	});

	setActiveCategory(activeCategory);
})();
