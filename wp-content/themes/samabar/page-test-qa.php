<?php
/**
 * Template Name: راهنمای QA
 * Internal QA guide — only useful on local/staging.
 *
 * @package Samabar
 */

if ( ! samabar_is_qa_environment() ) {
	wp_safe_redirect( home_url( '/' ) );
	exit;
}

get_header();

$manifest  = get_option( 'samabar_test_data_manifest', array() );
$password  = $manifest['password'] ?? samabar_get_qa_default_password();
$wp_users  = $manifest['wp_users'] ?? array();
$customers = $manifest['customers'] ?? array();

$pages = array(
	array( 'label' => 'صفحه اصلی', 'url' => home_url( '/' ) ),
	array( 'label' => 'ثبت سفارش', 'url' => samabar_get_order_url() ),
	array( 'label' => 'محاسبه قیمت', 'url' => samabar_get_pricing_url() ),
	array( 'label' => 'پیگیری بار', 'url' => samabar_get_tracking_url() ),
	array( 'label' => 'داشبورد', 'url' => samabar_get_dashboard_url() ),
	array( 'label' => 'خدمات', 'url' => samabar_get_services_url() ),
	array( 'label' => 'وبلاگ', 'url' => samabar_get_blog_url() ),
	array( 'label' => 'سوالات متداول', 'url' => samabar_get_faq_url() ),
	array( 'label' => 'تماس', 'url' => samabar_get_contact_url() ),
	array( 'label' => 'درباره', 'url' => samabar_get_about_url() ),
	array( 'label' => 'پیشخوان وردپرس', 'url' => admin_url() ),
	array( 'label' => 'مدیریت سفارش‌ها', 'url' => admin_url( 'admin.php?page=samabar-orders' ) ),
);
?>

<main id="primary" class="site-main site-main--qa">
	<div class="container qa-page">
		<header class="qa-hero">
			<span class="qa-hero__badge">فقط محیط تست</span>
			<h1 class="text-headline-xl">راهنمای تست سما بار</h1>
			<p class="text-body-lg">با حساب‌های زیر سایت را مرور کنید، باگ‌ها را یادداشت کنید و گزارش دهید.</p>
		</header>

		<section class="qa-section">
			<h2 class="text-headline-md">۱. مشتریان تست (داشبورد)</h2>
			<p class="qa-section__lead text-body-md">ورود با شماره موبایل در <a href="<?php echo esc_url( samabar_get_dashboard_url() ); ?>">/panel/</a> — بدون رمز عبور.</p>
			<div class="qa-cards">
				<?php foreach ( $customers as $customer ) : ?>
					<article class="qa-card">
						<h3 class="text-headline-sm"><?php echo esc_html( $customer['name'] ); ?></h3>
						<p class="qa-card__phone" dir="ltr"><?php echo esc_html( $customer['phone'] ); ?></p>
						<p class="text-body-md"><?php echo esc_html( $customer['description'] ); ?></p>
						<a class="btn btn--primary" href="<?php echo esc_url( $customer['dashboard'] ); ?>">ورود سریع به داشبورد</a>
						<?php if ( ! empty( $customer['orders'] ) ) : ?>
							<ul class="qa-card__orders">
								<?php foreach ( $customer['orders'] as $order ) : ?>
									<li>
										<a href="<?php echo esc_url( $order['tracking_url'] ); ?>">
											<strong dir="ltr"><?php echo esc_html( $order['number'] ); ?></strong>
											<span><?php echo esc_html( $order['status_label'] ); ?></span>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		</section>

		<section class="qa-section">
			<h2 class="text-headline-md">۲. کاربران وردپرس (پیشخوان)</h2>
			<p class="qa-section__lead text-body-md">رمز همه حساب‌ها: <code dir="ltr" class="qa-code"><?php echo esc_html( $password ); ?></code></p>
			<div class="qa-table-wrap">
				<table class="qa-table">
					<thead>
						<tr>
							<th>نام کاربری</th>
							<th>نقش</th>
							<th>کاربرد</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $wp_users as $user ) : ?>
							<tr>
								<td><code dir="ltr"><?php echo esc_html( $user['login'] ); ?></code></td>
								<td><?php echo esc_html( $user['role'] ); ?></td>
								<td><?php echo esc_html( $user['description'] ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<p class="text-body-md"><a class="btn btn--outline" href="<?php echo esc_url( wp_login_url() ); ?>">ورود به wp-admin</a></p>
		</section>

		<section class="qa-section">
			<h2 class="text-headline-md">۳. چک‌لیست تست</h2>
			<div class="qa-checklist">
				<details class="qa-checklist__group" open>
					<summary>ثبت سفارش (۳ مرحله)</summary>
					<ul>
						<li>انتخاب مبدا/مقصد و اعتبارسنجی فیلدها</li>
						<li>انتخاب نوع بار، وزن و سرویس</li>
						<li>تکمیل اطلاعات تماس و ثبت نهایی</li>
						<li>دکمه «رفتن به داشبورد» بعد از موفقیت</li>
					</ul>
				</details>
				<details class="qa-checklist__group">
					<summary>داشبورد مشتری</summary>
					<ul>
						<li>ورود با هر ۴ شماره تست</li>
						<li>09100000003 — داشبورد بدون سفارش</li>
						<li>تغییر شماره، خروج و session</li>
					</ul>
				</details>
				<details class="qa-checklist__group">
					<summary>پیگیری و قیمت</summary>
					<ul>
						<li>جستجو با شماره سفارش‌های بالا</li>
						<li>سفارش نامعتبر — پیام خطا</li>
						<li>محاسبه قیمت</li>
					</ul>
				</details>
				<details class="qa-checklist__group">
					<summary>صفحات عمومی</summary>
					<ul>
						<li>تماس، FAQ، وبلاگ، منوی موبایل</li>
					</ul>
				</details>
				<details class="qa-checklist__group">
					<summary>پیشخوان (qa_admin)</summary>
					<ul>
						<li>مدیریت سفارش‌ها و تغییر وضعیت</li>
						<li>تنظیم قیمت سرویس‌ها</li>
					</ul>
				</details>
			</div>
		</section>

		<section class="qa-section">
			<h2 class="text-headline-md">۴. لینک سریع</h2>
			<div class="qa-links">
				<?php foreach ( $pages as $page ) : ?>
					<a class="qa-links__item" href="<?php echo esc_url( $page['url'] ); ?>"><?php echo esc_html( $page['label'] ); ?></a>
				<?php endforeach; ?>
			</div>
		</section>

		<section class="qa-section qa-section--report">
			<h2 class="text-headline-md">۵. قالب گزارش باگ</h2>
			<pre class="qa-report-template">صفحه:
دستگاه/مرورگر:
مراحل انجام:
نتیجه مورد انتظار:
نتیجه واقعی:</pre>
		</section>
	</div>
</main>

<?php
get_footer();
