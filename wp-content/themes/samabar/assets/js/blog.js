(function () {
	'use strict';

	const newsletterForm = document.getElementById('blog-newsletter-form');
	const newsletterNotice = document.getElementById('blog-newsletter-notice');

	if (newsletterForm) {
		newsletterForm.addEventListener('submit', function (event) {
			event.preventDefault();
			if (!newsletterNotice) {
				return;
			}
			newsletterNotice.hidden = false;
			newsletterNotice.textContent = 'عضویت شما ثبت شد. از همراهی شما سپاسگزاریم.';
			newsletterNotice.classList.add('is-success');
			newsletterForm.reset();
		});
	}

	document.querySelectorAll('[data-copy-link]').forEach(function (button) {
		button.addEventListener('click', function () {
			const url = button.getAttribute('data-copy-link');
			const notice = document.getElementById('blog-copy-notice');
			if (!url) {
				return;
			}

			const onCopied = function () {
				button.classList.add('is-copied');
				if (notice) {
					notice.hidden = false;
				}
				setTimeout(function () {
					button.classList.remove('is-copied');
					if (notice) {
						notice.hidden = true;
					}
				}, 1800);
			};

			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(url).then(onCopied).catch(function () {
					window.prompt('لینک مقاله:', url);
				});
				return;
			}

			window.prompt('لینک مقاله:', url);
		});
	});

	const progressBar = document.getElementById('blog-read-progress');
	const articleContent = document.getElementById('blog-article-content');

	if (progressBar && articleContent) {
		const updateProgress = function () {
			const rect = articleContent.getBoundingClientRect();
			const viewport = window.innerHeight;
			const total = articleContent.offsetHeight + rect.top;
			const scrolled = window.scrollY + viewport - rect.top;
			const percent = Math.min(100, Math.max(0, (scrolled / total) * 100));
			progressBar.style.width = percent + '%';
		};

		window.addEventListener('scroll', updateProgress, { passive: true });
		window.addEventListener('resize', updateProgress);
		updateProgress();
	}

	const tocWidget = document.getElementById('blog-toc-widget');
	const tocNav = document.getElementById('blog-toc');

	if (tocWidget && tocNav && articleContent) {
		const headings = articleContent.querySelectorAll('h2, h3');
		if (headings.length) {
			const list = document.createElement('ol');
			list.className = 'blog-toc__list';

			headings.forEach(function (heading, index) {
				if (!heading.id) {
					heading.id = 'blog-section-' + (index + 1);
				}

				const item = document.createElement('li');
				item.className = 'blog-toc__item blog-toc__item--level-' + heading.tagName.toLowerCase();

				const link = document.createElement('a');
				link.className = 'blog-toc__link';
				link.href = '#' + heading.id;
				link.textContent = heading.textContent.trim();
				link.addEventListener('click', function (event) {
					event.preventDefault();
					heading.scrollIntoView({ behavior: 'smooth', block: 'start' });
					history.replaceState(null, '', '#' + heading.id);
				});

				item.appendChild(link);
				list.appendChild(item);
			});

			tocNav.appendChild(list);
			tocWidget.hidden = false;

			const tocLinks = tocNav.querySelectorAll('.blog-toc__link');
			const observer = new IntersectionObserver(
				function (entries) {
					entries.forEach(function (entry) {
						if (!entry.isIntersecting) {
							return;
						}
						const id = entry.target.getAttribute('id');
						tocLinks.forEach(function (link) {
							link.classList.toggle('is-active', link.getAttribute('href') === '#' + id);
						});
					});
				},
				{
					rootMargin: '-20% 0px -70% 0px',
					threshold: 0,
				}
			);

			headings.forEach(function (heading) {
				observer.observe(heading);
			});
		}
	}
})();
