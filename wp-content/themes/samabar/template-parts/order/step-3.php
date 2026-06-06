<?php
/**
 * Order step 3: Review & confirm.
 *
 * @package Samabar
 */

$order_base     = samabar_get_order_url();
$tracking_base  = samabar_get_tracking_url();
$dashboard_base = samabar_get_dashboard_url();
$prev_url       = add_query_arg( 'step', '2', $order_base );
$home_url       = home_url( '/' );
?>
<div class="order-form" id="order-form-step-3">
	<div class="order-step-intro order-step-intro--center">
		<h1 class="order-step-intro__title text-headline-lg">مرور و تایید سفارش</h1>
		<p class="order-step-intro__text text-body-md">جزئیات سفارش و کرایه حمل را بررسی کنید و در صورت تایید، ثبت نهایی را انجام دهید.</p>
	</div>

	<div class="order-layout order-layout--confirm">
		<aside class="order-summary order-summary--confirm">
			<div class="order-summary__head">
				<h2 class="text-headline-md">جزئیات سفارش</h2>
			</div>
			<div class="order-summary__body">
				<div class="order-summary__route">
					<div class="order-summary__route-line" aria-hidden="true"></div>
					<div class="order-summary__route-items">
						<div>
							<span class="order-summary__meta">مبدا</span>
							<p class="order-summary__value" data-review-origin>—</p>
						</div>
						<div>
							<span class="order-summary__meta">مقصد</span>
							<p class="order-summary__value" data-review-destination>—</p>
						</div>
					</div>
				</div>
				<hr class="order-summary__hr">
				<div class="order-summary__row">
					<span>زمان بارگیری</span>
					<strong data-review-pickup>—</strong>
				</div>
				<div class="order-summary__row">
					<span>وزن تقریبی</span>
					<strong data-review-weight>—</strong>
				</div>
				<div class="order-summary__row" data-review-row="dims" hidden>
					<span>ابعاد</span>
					<strong data-review-dims>—</strong>
				</div>
				<div class="order-summary__row" data-review-row="description" hidden>
					<span>توضیحات</span>
					<strong data-review-description>—</strong>
				</div>
				<hr class="order-summary__hr">
				<div class="order-summary__row">
					<span>نام تماس</span>
					<strong data-review-name>—</strong>
				</div>
				<div class="order-summary__row">
					<span>موبایل</span>
					<strong data-review-phone dir="ltr">—</strong>
				</div>
				<div class="order-summary__row" data-review-row="company" hidden>
					<span>شرکت</span>
					<strong data-review-company>—</strong>
				</div>
				<hr class="order-summary__hr">
				<div class="order-summary__total">
					<span class="text-headline-md">کرایه حمل</span>
					<span class="text-headline-lg" data-review-total>—</span>
				</div>
				<button class="btn btn--secondary btn--lg order-summary__submit" type="button" id="order-submit">
					تایید و ثبت سفارش
					<span class="material-symbols-outlined icon icon--filled">check_circle</span>
				</button>
				<div class="order-summary__trust">
					<div><span class="material-symbols-outlined icon">shield</span><span>بیمه بار</span></div>
					<div><span class="material-symbols-outlined icon">support_agent</span><span>پشتیبانی تلفنی</span></div>
					<div><span class="material-symbols-outlined icon">cancel</span><span>لغو آسان</span></div>
				</div>
			</div>
		</aside>
	</div>

	<div class="order-actions order-actions--start">
		<a class="btn btn--outline" href="<?php echo esc_url( $prev_url ); ?>">
			<span class="material-symbols-outlined icon">arrow_forward</span>
			مرحله قبل
		</a>
	</div>
</div>

<div class="order-success" id="order-success" hidden>
	<div class="order-success__inner">
		<span class="material-symbols-outlined icon icon--filled order-success__icon">check_circle</span>
		<h2 class="text-headline-lg">سفارش شما ثبت شد</h2>
		<p class="text-body-md order-success__number" data-order-number hidden></p>
		<p class="text-body-md">کارشناسان ما به زودی با شما تماس می‌گیرند.</p>
		<div class="order-success__actions">
			<a class="btn btn--primary" data-order-dashboard-link href="<?php echo esc_url( $dashboard_base ); ?>">رفتن به داشبورد</a>
			<a class="btn btn--secondary" data-order-track-link href="<?php echo esc_url( $tracking_base ); ?>">پیگیری سفارش</a>
			<a class="btn btn--outline" href="<?php echo esc_url( $home_url ); ?>">بازگشت به صفحه اصلی</a>
		</div>
	</div>
</div>
