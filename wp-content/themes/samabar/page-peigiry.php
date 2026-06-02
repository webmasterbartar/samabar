<?php
/**
 * Template Name: پیگیری بار
 * Order tracking page.
 *
 * @package Samabar
 */

get_header();

$tracking_url = samabar_get_tracking_url();
$order_url    = samabar_get_order_url();
$prefill      = isset( $_GET['track'] ) ? sanitize_text_field( wp_unslash( $_GET['track'] ) ) : '';
?>

<main id="primary" class="site-main site-main--tracking">
	<div class="container tracking-main">

		<section class="tracking-hero">
			<h1 class="tracking-hero__title text-headline-xl">پیگیری وضعیت بار شما</h1>
			<p class="tracking-hero__text text-body-lg">برای اطلاع از وضعیت لحظه‌ای محموله، کد پیگیری یا شماره سفارش را وارد کنید.</p>

			<form class="tracking-search" id="tracking-form" action="<?php echo esc_url( $tracking_url ); ?>" method="get">
				<div class="tracking-search__field">
					<span class="material-symbols-outlined icon tracking-search__icon" aria-hidden="true">search</span>
					<input
						class="tracking-search__input"
						type="text"
						name="track"
						id="tracking-input"
						value="<?php echo esc_attr( $prefill ); ?>"
						placeholder="مثال: SB-00012"
						autocomplete="off"
						required
					>
					<button class="btn btn--primary tracking-search__submit" type="submit">مشاهده وضعیت</button>
				</div>
			</form>
		</section>

		<div class="tracking-state tracking-state--loading" id="tracking-loading" hidden>
			<span class="tracking-state__spinner" aria-hidden="true"></span>
			<p>در حال دریافت وضعیت...</p>
		</div>

		<div class="tracking-state tracking-state--error" id="tracking-error" hidden>
			<span class="material-symbols-outlined icon">error</span>
			<p data-tracking-error-text>سفارشی با این کد یافت نشد.</p>
			<a class="btn btn--outline" href="<?php echo esc_url( $order_url ); ?>">ثبت سفارش جدید</a>
		</div>

		<section class="tracking-result" id="tracking-result" hidden>
			<div class="tracking-grid">
				<div class="tracking-grid__main">
					<div class="tracking-card">
						<h2 class="tracking-card__title text-headline-md">
							وضعیت فعلی: <span data-tracking-status-label>—</span>
						</h2>
						<div class="tracking-steps" data-tracking-steps aria-label="مراحل پیگیری"></div>
					</div>

					<div class="tracking-card tracking-route-card">
						<div class="tracking-route-card__head">
							<h3 class="text-headline-md">
								<span class="material-symbols-outlined icon">map</span>
								مسیر محموله
							</h3>
							<span class="tracking-route-card__live">
								<span class="tracking-route-card__dot" aria-hidden="true"></span>
								<span data-tracking-updated>بروزرسانی لحظه‌ای</span>
							</span>
						</div>
						<div class="tracking-route-visual">
							<div class="tracking-route-visual__point tracking-route-visual__point--origin">
								<span class="tracking-route-visual__icon" aria-hidden="true">
									<span class="material-symbols-outlined icon">trip_origin</span>
								</span>
								<div class="tracking-route-visual__text">
									<span class="tracking-route-visual__label">مبدا</span>
									<p data-tracking-origin>—</p>
								</div>
							</div>
							<div class="tracking-route-visual__path" aria-hidden="true">
								<span class="tracking-route-visual__path-line"></span>
								<span class="tracking-route-visual__truck">
									<span class="material-symbols-outlined icon">local_shipping</span>
								</span>
							</div>
							<div class="tracking-route-visual__point tracking-route-visual__point--dest">
								<span class="tracking-route-visual__icon" aria-hidden="true">
									<span class="material-symbols-outlined icon">location_on</span>
								</span>
								<div class="tracking-route-visual__text">
									<span class="tracking-route-visual__label">مقصد</span>
									<p data-tracking-destination>—</p>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="tracking-grid__side">
					<div class="tracking-card">
						<div class="tracking-info__head">
							<div>
								<span class="tracking-info__meta">شماره سفارش</span>
								<strong class="tracking-info__number" data-tracking-number>—</strong>
							</div>
							<span class="tracking-info__badge" data-tracking-badge>—</span>
						</div>
						<div class="tracking-info__route">
							<div class="tracking-info__stop">
								<span class="tracking-info__stop-icon" aria-hidden="true">
									<span class="material-symbols-outlined icon">location_on</span>
								</span>
								<div class="tracking-info__stop-body">
									<span class="tracking-info__meta">مبدا</span>
									<p data-tracking-origin-side>—</p>
								</div>
							</div>
							<div class="tracking-info__connector" aria-hidden="true"></div>
							<div class="tracking-info__stop">
								<span class="tracking-info__stop-icon tracking-info__stop-icon--dest" aria-hidden="true">
									<span class="material-symbols-outlined icon">flag</span>
								</span>
								<div class="tracking-info__stop-body">
									<span class="tracking-info__meta">مقصد</span>
									<p data-tracking-destination-side>—</p>
								</div>
							</div>
						</div>
						<hr class="tracking-info__hr">
						<div class="tracking-info__meta-grid">
							<div class="tracking-info__meta-cell">
								<span class="tracking-info__meta">نوع سرویس</span>
								<strong data-tracking-service>—</strong>
							</div>
							<div class="tracking-info__meta-cell">
								<span class="tracking-info__meta">نوع بار</span>
								<strong data-tracking-cargo>—</strong>
							</div>
							<div class="tracking-info__meta-cell">
								<span class="tracking-info__meta">وزن</span>
								<strong data-tracking-weight>—</strong>
							</div>
							<div class="tracking-info__meta-cell" data-tracking-pickup-wrap hidden>
								<span class="tracking-info__meta">زمان بارگیری</span>
								<strong data-tracking-pickup>—</strong>
							</div>
						</div>
					</div>

					<div class="tracking-card tracking-driver" id="tracking-driver" hidden>
						<h3 class="tracking-card__subtitle">اطلاعات راننده</h3>
						<div class="tracking-driver__row">
							<div class="tracking-driver__avatar">
								<span class="material-symbols-outlined icon">person</span>
							</div>
							<div class="tracking-driver__body">
								<strong data-tracking-driver-name>—</strong>
								<span class="tracking-driver__verified">
									<span class="material-symbols-outlined icon icon--filled">verified</span>
									راننده تایید شده
								</span>
							</div>
							<div class="tracking-driver__plate-wrap">
								<span class="tracking-info__meta">پلاک</span>
								<span class="tracking-driver__plate" data-tracking-driver-plate dir="ltr">—</span>
							</div>
						</div>
						<a class="btn btn--outline tracking-driver__call" data-tracking-driver-phone href="tel:" hidden>
							<span class="material-symbols-outlined icon">call</span>
							تماس با راننده
						</a>
					</div>

					<div class="tracking-card tracking-events">
						<h3 class="tracking-card__subtitle">تاریخچه رویدادها</h3>
						<div class="tracking-events__list" data-tracking-timeline></div>
					</div>

					<div class="tracking-support">
						<h3 class="tracking-support__title">نیاز به کمک دارید؟</h3>
						<div class="tracking-support__grid">
							<a class="tracking-support__item" href="<?php echo esc_url( samabar_get_contact_phone_url() ); ?>">
								<span class="material-symbols-outlined icon">support_agent</span>
								<span>تماس با پشتیبانی</span>
							</a>
							<a class="tracking-support__item" href="<?php echo esc_url( $order_url ); ?>">
								<span class="material-symbols-outlined icon">add_box</span>
								<span>ثبت سفارش</span>
							</a>
							<a class="tracking-support__item" href="<?php echo esc_url( samabar_get_faq_url() ); ?>">
								<span class="material-symbols-outlined icon">help</span>
								<span>سوالات متداول</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>

	</div>
</main>

<?php
get_footer();
