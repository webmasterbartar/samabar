/**
 * Full site smoke test — run: node scripts/test-site.js
 */
const BASE = process.env.SITE_URL || 'http://samabar.test';

const results = { pass: [], fail: [], warn: [] };

function pass(msg) { results.pass.push(msg); }
function fail(msg) { results.fail.push(msg); }
function warn(msg) { results.warn.push(msg); }

async function fetchUrl(url, opts = {}) {
	const res = await fetch(url, { redirect: 'follow', ...opts });
	const text = await res.text();
	return { res, text, url: res.url };
}

function assert(cond, msg) {
	if (!cond) throw new Error(msg);
}

async function testPage(name, path, checks) {
	const { res, text } = await fetchUrl(BASE + path);
	assert(res.ok, `${name} HTTP ${res.status}`);
	for (const [label, fn] of checks) {
		fn(text, res);
	}
	pass(`${name} (${path})`);
}

function extractAssets(html, base) {
	const assets = new Set();
	const patterns = [
		/(?:src|href)=["']([^"']+\.(?:css|js|woff2?|png|jpe?g|svg|webp))(?:\?[^"']*)?["']/gi,
		/url\(["']?([^"')]+\.(?:woff2?|png|jpe?g|svg))["']?\)/gi,
	];
	for (const re of patterns) {
		let m;
		while ((m = re.exec(html)) !== null) {
			let u = m[1];
			if (u.startsWith('//')) u = 'http:' + u;
			if (u.startsWith('/')) u = new URL(u, base).href;
			if (u.startsWith('http') && u.includes('samabar.test')) assets.add(u);
			if (u.startsWith('http') && u.includes('/wp-content/themes/samabar/')) assets.add(u);
		}
	}
	return [...assets];
}

function extractInternalLinks(html) {
	const links = new Set();
	const re = /href=["']([^"'#]+)["']/gi;
	let m;
	while ((m = re.exec(html)) !== null) {
		const href = m[1];
		if (href.startsWith(BASE) || href.startsWith('/')) {
			const url = href.startsWith('/') ? BASE + href : href;
			if (url.startsWith(BASE) && !url.includes('wp-admin') && !url.includes('wp-login')) {
				links.add(url.split('#')[0].replace(/\/$/, '') || BASE);
			}
		}
	}
	return [...links];
}

async function testAssetsFromPage(pageName, path) {
	const { text } = await fetchUrl(BASE + path);
	const assets = extractAssets(text, BASE);
	let broken = 0;
	for (const asset of assets) {
		try {
			const r = await fetch(asset, { method: 'HEAD' });
			if (!r.ok) {
				const r2 = await fetch(asset);
				if (!r2.ok) {
					fail(`Asset 404 on ${pageName}: ${asset}`);
					broken++;
				}
			}
		} catch (e) {
			fail(`Asset error on ${pageName}: ${asset} — ${e.message}`);
			broken++;
		}
	}
	if (!broken) pass(`${pageName}: ${assets.length} local assets OK`);
}

async function testRestApi() {
	const { text: home } = await fetchUrl(BASE + '/sabt-sefaresh/');
	const nonceMatch = home.match(/"nonce":"([^"]+)"/);
	assert(nonceMatch, 'REST nonce missing on order page');
	const nonce = nonceMatch[1];

	const avail = await fetchUrl(BASE + '/wp-json/samabar/v1/pickup-availability?year=1405&month=3');
	assert(avail.res.ok, 'pickup-availability failed');
	assert(JSON.parse(avail.text).days, 'pickup-availability no days');
	pass('REST pickup-availability');

	const orderPayload = {
		origin_city: 'کرمان',
		origin_address: 'بلوار جمهوری',
		destination_city: 'یزد',
		destination_address: 'میدان امیر چقماق',
		origin: 'کرمان — بلوار جمهوری',
		destination: 'یزد — میدان امیر چقماق',
		full_name: 'تست سایت',
		phone: '09129998877',
		weight: 900,
		cargo_type: 'general',
		service: 'standard',
	};
	const orderRes = await fetch(BASE + '/wp-json/samabar/v1/orders', {
		method: 'POST',
		headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': nonce },
		body: JSON.stringify(orderPayload),
	});
	const orderBody = await orderRes.json();
	assert(orderRes.ok, `order create failed: ${JSON.stringify(orderBody)}`);
	assert(orderBody.order_number, 'no order_number');
	pass(`REST order create: ${orderBody.order_number}`);

	// Reject incomplete order
	const badRes = await fetch(BASE + '/wp-json/samabar/v1/orders', {
		method: 'POST',
		headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': nonce },
		body: JSON.stringify({ full_name: 'x' }),
	});
	assert(badRes.status === 400, 'incomplete order should 400');
	pass('REST order validation (400 on incomplete)');
}

async function testInternalLinks() {
	const { text } = await fetchUrl(BASE + '/');
	const links = extractInternalLinks(text);
	const uniquePaths = [...new Set(links.map((u) => u.replace(BASE, '') || '/'))];
	for (const path of uniquePaths) {
		if (path.includes('tel:') || path.includes('mailto:')) continue;
		const { res } = await fetchUrl(BASE + path);
		if (!res.ok) fail(`Broken link from homepage: ${path} → ${res.status}`);
	}
	pass(`Homepage internal links: ${uniquePaths.length} paths checked`);
}

async function main() {
	console.log(`\n🔍 Testing ${BASE}\n`);

	try {
		await testPage('Homepage', '/', [
			['has hero', (t) => assert(t.includes('hero-form'), 'missing hero form')],
			['has header', (t) => assert(t.includes('site-header'), 'missing header')],
			['has footer', (t) => assert(t.includes('site-footer'), 'missing footer')],
			['RTL', (t) => assert(t.includes('dir="rtl"'), 'not RTL')],
			['testimonials JS', (t) => assert(t.includes('testimonials.js'), 'missing testimonials.js')],
		]);

		await testPage('Services', '/khadamat/', [
			['template', (t) => assert(t.includes('site-main'), 'missing main')],
			['services CSS', (t) => assert(t.includes('services.css'), 'missing services.css')],
			['order CTA', (t) => assert(t.includes('sabt-sefaresh'), 'missing order link')],
		]);

		await testPage('Pricing', '/mohasebe/', [
			['pricing form', (t) => assert(t.includes('pricing-form'), 'missing pricing form')],
			['pricing JS', (t) => assert(t.includes('pricing.js'), 'missing pricing.js')],
		]);

		await testPage('Order step 1', '/sabt-sefaresh/', [
			['step 1 form', (t) => assert(t.includes('order-form-step-1'), 'missing step 1')],
			['order JS', (t) => assert(t.includes('order.js'), 'missing order.js')],
			['jalali', (t) => assert(t.includes('jalali.js'), 'missing jalali.js')],
			['data-order-step="1"', (t) => assert(t.includes('data-order-step="1"'), 'wrong step')],
		]);

		await testPage('Order step 2', '/sabt-sefaresh/?step=2', [
			['step 2', (t) => assert(t.includes('order-form-step-2'), 'missing step 2')],
		]);

		await testPage('Order step 3', '/sabt-sefaresh/?step=3', [
			['step 3', (t) => assert(t.includes('order-form-step-3'), 'missing step 3')],
			['no demo breakdown', (t) => assert(!t.includes('۴۰,۰۰۰,۰۰۰'), 'demo price in HTML')],
			['review hooks', (t) => assert(t.includes('data-review-total'), 'missing review total')],
		]);

		await testPage('Hero prefill', '/sabt-sefaresh/?origin=تهران&destination=مشهد&cargo=heavy', [
			['samabarOrder config', (t) => assert(t.includes('samabarOrder'), 'missing config')],
		]);

		// 404 should not be 500
		const { res: r404 } = await fetchUrl(BASE + '/this-page-does-not-exist-xyz/');
		if (r404.status >= 500) fail(`404 page returns ${r404.status}`);
		else pass(`404 page returns ${r404.status} (not 500)`);

		await testRestApi();
		await testInternalLinks();

		for (const [name, path] of [
			['Homepage', '/'],
			['Order', '/sabt-sefaresh/'],
			['Pricing', '/mohasebe/'],
			['Services', '/khadamat/'],
		]) {
			await testAssetsFromPage(name, path);
		}

		// WP JSON index
		const { res: wpJson } = await fetchUrl(BASE + '/wp-json/');
		assert(wpJson.ok, 'wp-json index failed');
		pass('WordPress REST index');

	} catch (e) {
		fail(e.message);
	}

	console.log('\n--- RESULTS ---');
	console.log(`✅ Passed: ${results.pass.length}`);
	results.pass.forEach((m) => console.log('  ✓', m));
	if (results.warn.length) {
		console.log(`⚠️  Warnings: ${results.warn.length}`);
		results.warn.forEach((m) => console.log('  !', m));
	}
	if (results.fail.length) {
		console.log(`❌ Failed: ${results.fail.length}`);
		results.fail.forEach((m) => console.log('  ✗', m));
		process.exit(1);
	}
	console.log('\nAll site tests passed.\n');
}

main();
