/**
 * Browser E2E via Playwright core — run: node scripts/e2e-browser.js
 */
const { chromium } = require('playwright');

const BASE = process.env.SITE_URL || 'http://samabar.test';
const results = { pass: [], fail: [] };

function ok(msg) { results.pass.push(msg); console.log('  ✓', msg); }
function bad(msg) { results.fail.push(msg); console.log('  ✗', msg); }

async function run() {
	console.log(`\n🌐 Browser E2E — ${BASE}\n`);
	const browser = await chromium.launch({
		headless: true,
		channel: 'msedge',
	});
	const context = await browser.newContext({ locale: 'fa-IR' });
	const page = await context.newPage();

	try {
		// Homepage
		await page.goto(BASE + '/', { waitUntil: 'networkidle' });
		if (await page.locator('.site-header').isVisible()) ok('Homepage header visible');
		else bad('Homepage header missing');
		if (await page.locator('.hero-form').isVisible()) ok('Hero form visible');
		else bad('Hero form missing');

		// Pricing
		await page.goto(BASE + '/mohasebe/', { waitUntil: 'networkidle' });
		await page.locator('#pricing-form').evaluate((f) => f.requestSubmit());
		await page.waitForTimeout(300);
		const empty = await page.locator('#pricing-result.is-empty').count();
		if (empty === 0) ok('Pricing calculator shows result');
		else bad('Pricing result empty after submit');

		// Hero → order prefill
		await page.goto(BASE + '/', { waitUntil: 'networkidle' });
		await page.fill('#hero-origin', 'تهران');
		await page.fill('#hero-destination', 'مشهد');
		await page.selectOption('#hero-cargo', 'heavy');
		await page.locator('#hero-form').evaluate((f) => f.requestSubmit());
		await page.waitForURL(/sabt-sefaresh/);
		const originVal = await page.inputValue('#order-origin-city');
		const destVal = await page.inputValue('#order-destination-city');
		if (originVal === 'تهران' && destVal === 'مشهد') ok('Hero form prefills order cities');
		else bad(`Hero prefill failed: ${originVal} / ${destVal}`);

		// Full order flow
		await page.fill('#order-origin-address', 'خیابان آزادی ۱۰');
		await page.fill('#order-destination-address', 'بلوار وکیل آباد');
		await page.fill('#order-full-name', 'تست مرورگر');
		await page.fill('#order-phone', '09123456789');
		await page.locator('#order-form-step-1').evaluate((f) => f.requestSubmit());
		await page.waitForURL(/step=2/);
		ok('Step 1 → 2 navigation');

		await page.fill('#order-weight', '800');
		await page.locator('#order-form-step-2').evaluate((f) => f.requestSubmit());
		await page.waitForURL(/step=3/);
		ok('Step 2 → 3 navigation');

		const reviewName = await page.locator('[data-review-name]').textContent();
		const reviewTotal = await page.locator('[data-review-total]').textContent();
		if (reviewName.includes('تست مرورگر')) ok('Step 3 shows contact name');
		else bad('Step 3 name wrong: ' + reviewName);
		if (reviewTotal && reviewTotal !== '—') ok('Step 3 shows price: ' + reviewTotal.trim());
		else bad('Step 3 price missing');

		await page.locator('input[name="service"][value="express"]').check();
		await page.waitForTimeout(200);
		const expressTotal = await page.locator('[data-review-total]').textContent();
		if (expressTotal.includes('۵۵') || expressTotal.includes('55')) ok('Express price updates total');
		else bad('Express price not updated: ' + expressTotal);

		// Pickup calendar
		await page.goto(BASE + '/sabt-sefaresh/', { waitUntil: 'domcontentloaded' });
		await page.locator('#order-pickup-trigger').click();
		if (await page.locator('[data-persian-datetime-panel]').isVisible()) ok('Pickup calendar opens');
		else bad('Pickup calendar did not open');

		// Step 3 guard (fresh context)
		const ctx2 = await browser.newContext();
		const p2 = await ctx2.newPage();
		await p2.goto(BASE + '/sabt-sefaresh/?step=3');
		await p2.waitForURL(/sabt-sefaresh/);
		if (!p2.url().includes('step=3')) ok('Step 3 redirects without session');
		else bad('Step 3 accessible without data');
		await ctx2.close();

		// Services page
		await page.goto(BASE + '/khadamat/', { waitUntil: 'networkidle' });
		if (await page.locator('.site-main').isVisible()) ok('Services page loads');
		else bad('Services page broken');

		// Footer order link
		await page.goto(BASE + '/');
		await page.locator('.site-footer a[href*="sabt-sefaresh"]').first().click();
		await page.waitForURL(/sabt-sefaresh/);
		ok('Footer order link works');

	} catch (err) {
		bad('Unhandled: ' + err.message);
	} finally {
		await browser.close();
	}

	console.log('\n--- BROWSER E2E ---');
	console.log(`Passed: ${results.pass.length}, Failed: ${results.fail.length}`);
	if (results.fail.length) process.exit(1);
	console.log('All browser tests passed.\n');
}

run().catch((e) => {
	console.error(e);
	process.exit(1);
});
