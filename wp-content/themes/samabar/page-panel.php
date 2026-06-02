<?php
/**
 * Template Name: داشبورد مشتری
 * Customer dashboard page.
 *
 * @package Samabar
 */

get_header( 'dashboard' );

$order_url    = samabar_get_order_url();
$pricing_url  = samabar_get_pricing_url();
$tracking_url = samabar_get_tracking_url();
$home_url     = home_url( '/' );
$prefill_phone = isset( $_GET['phone'] ) ? samabar_sanitize_phone( wp_unslash( $_GET['phone'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
if ( ! samabar_validate_phone( $prefill_phone ) ) {
	$prefill_phone = '';
}
?>

<div class="dashboard-shell">
	<?php get_template_part( 'template-parts/dashboard/sidebar' ); ?>

	<div class="dashboard-main">
		<header class="dashboard-topbar">
			<div class="dashboard-topbar__inner">
				<div class="dashboard-topbar__start">
					<button type="button" class="dashboard-topbar__menu" id="dashboard-menu-toggle" aria-label="<?php esc_attr_e( 'باز کردن منو', 'samabar' ); ?>" aria-expanded="false" aria-controls="dashboard-sidebar">
						<span class="material-symbols-outlined icon">menu</span>
					</button>
					<h1 class="dashboard-topbar__title" data-dashboard-welcome>داشبورد مشتری</h1>
				</div>
				<div class="dashboard-topbar__actions">
					<a class="dashboard-topbar__home" href="<?php echo esc_url( $home_url ); ?>">
						<span class="material-symbols-outlined icon">home</span>
						<span>صفحه اصلی</span>
					</a>
					<a class="dashboard-topbar__icon" href="<?php echo esc_url( samabar_get_contact_phone_url() ); ?>" aria-label="<?php esc_attr_e( 'پشتیبانی', 'samabar' ); ?>">
						<span class="material-symbols-outlined icon">notifications</span>
					</a>
					<button type="button" class="dashboard-topbar__icon" id="dashboard-profile-toggle" aria-label="<?php esc_attr_e( 'تغییر شماره', 'samabar' ); ?>">
						<span class="material-symbols-outlined icon">account_circle</span>
					</button>
					<button type="button" class="dashboard-topbar__icon dashboard-topbar__icon--logout" id="dashboard-topbar-logout" aria-label="<?php esc_attr_e( 'خروج', 'samabar' ); ?>">
						<span class="material-symbols-outlined icon">logout</span>
					</button>
				</div>
			</div>
		</header>

		<div class="dashboard-content">
			<section class="dashboard-gate" id="dashboard-gate">
				<div class="dashboard-gate__card">
					<span class="material-symbols-outlined icon dashboard-gate__icon">account_circle</span>
					<h2 class="text-headline-md">ورود به داشبورد</h2>
					<p class="text-body-md">برای مشاهده سفارش‌های خود، شماره موبایلی که با آن سفارش ثبت کرده‌اید را وارد کنید.</p>
					<form class="dashboard-gate__form" id="dashboard-gate-form">
						<label class="dashboard-gate__label" for="dashboard-phone">شماره موبایل</label>
						<input class="dashboard-gate__input" type="tel" id="dashboard-phone" name="phone" inputmode="numeric" placeholder="مثال: 09123456789" autocomplete="tel" value="<?php echo esc_attr( $prefill_phone ); ?>" required>
						<button class="btn btn--primary btn--block-mobile" type="submit">مشاهده داشبورد</button>
					</form>
					<p class="dashboard-gate__error" id="dashboard-gate-error" hidden></p>
					<p class="dashboard-gate__back">
						<a href="<?php echo esc_url( $home_url ); ?>">
							<span class="material-symbols-outlined icon">arrow_forward</span>
							بازگشت به صفحه اصلی
						</a>
					</p>
				</div>
			</section>

			<div class="dashboard-app-content" id="dashboard-app" hidden>
				<section class="dashboard-actions">
					<a class="dashboard-actions__btn dashboard-actions__btn--primary" href="<?php echo esc_url( $order_url ); ?>">
						<span class="material-symbols-outlined icon">add_circle</span>
						ثبت سفارش جدید
					</a>
					<a class="dashboard-actions__btn dashboard-actions__btn--outline" href="<?php echo esc_url( $pricing_url ); ?>">
						<span class="material-symbols-outlined icon">calculate</span>
						محاسبه قیمت
					</a>
					<a class="dashboard-actions__btn dashboard-actions__btn--ghost" href="<?php echo esc_url( samabar_get_contact_phone_url() ); ?>">
						<span class="material-symbols-outlined icon">headset_mic</span>
						پشتیبانی
					</a>
					<a class="dashboard-actions__btn dashboard-actions__btn--ghost" href="<?php echo esc_url( $home_url ); ?>">
						<span class="material-symbols-outlined icon">home</span>
						بازگشت به صفحه اصلی
					</a>
				</section>

				<section class="dashboard-stats" data-dashboard-stats></section>

				<div class="dashboard-grid">
					<div class="dashboard-grid__main">
						<section class="dashboard-section" id="active-orders">
							<div class="dashboard-section__head">
								<h2 class="text-headline-md">سفارش‌های فعال</h2>
								<a class="dashboard-section__link" href="#history-orders" data-dashboard-scroll>مشاهده تاریخچه</a>
							</div>
							<div class="dashboard-orders" data-dashboard-active></div>
						</section>

						<section class="dashboard-section" id="history-orders">
							<div class="dashboard-section__head">
								<h2 class="text-headline-md">تاریخچه</h2>
							</div>
							<div class="dashboard-orders" data-dashboard-history></div>
						</section>

						<section class="dashboard-section" id="payments">
							<div class="dashboard-section__head">
								<h2 class="text-headline-md">پرداخت‌ها</h2>
							</div>
							<div class="dashboard-payments-summary" data-dashboard-payments-summary></div>
							<div class="dashboard-payments-table-wrap">
								<table class="dashboard-payments-table">
									<thead>
										<tr>
											<th scope="col">شماره سفارش</th>
											<th scope="col">تاریخ</th>
											<th scope="col">مبلغ</th>
											<th scope="col">وضعیت</th>
										</tr>
									</thead>
									<tbody data-dashboard-payments></tbody>
								</table>
							</div>
						</section>

						<section class="dashboard-section" id="profile">
							<div class="dashboard-section__head">
								<h2 class="text-headline-md">پروفایل</h2>
							</div>
							<div class="dashboard-profile" data-dashboard-profile></div>
						</section>
					</div>

					<div class="dashboard-grid__side">
						<div class="dashboard-live" data-dashboard-featured hidden>
							<div class="dashboard-live__head">
								<div>
									<span class="dashboard-live__meta">آخرین وضعیت محموله</span>
									<strong class="dashboard-live__number" data-featured-number>—</strong>
								</div>
								<span class="material-symbols-outlined icon">sensors</span>
							</div>
							<div class="dashboard-live__route">
								<div class="dashboard-live__cities">
									<span data-featured-origin>—</span>
									<span data-featured-destination>—</span>
								</div>
								<div class="dashboard-live__track">
									<span class="dashboard-live__progress" data-featured-progress style="width:0%"></span>
								</div>
								<p class="dashboard-live__status" data-featured-status>—</p>
							</div>
							<a class="btn btn--outline dashboard-live__link" data-featured-link href="<?php echo esc_url( $tracking_url ); ?>">پیگیری محموله</a>
						</div>
					</div>
				</div>
			</div>

			<div class="dashboard-state dashboard-state--loading" id="dashboard-loading" hidden>
				<span class="dashboard-state__spinner" aria-hidden="true"></span>
				<p>در حال بارگذاری...</p>
			</div>
		</div>
	</div>
</div>

<?php
get_footer( 'dashboard' );
