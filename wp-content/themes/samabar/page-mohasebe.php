<?php
/**
 * Template Name: محاسبه قیمت
 * Template for the pricing estimation page.
 *
 * @package Samabar
 */

get_header();

$home_url     = home_url( '/' );
$pricing_url  = samabar_get_pricing_url();
$order_url    = samabar_get_order_url();
$hub_city     = samabar_get_hub_city();
?>

<main id="primary" class="site-main site-main--pricing">

	<section class="pricing-hero">
		<div class="container pricing-hero__inner">
			<nav class="pricing-breadcrumb" aria-label="<?php esc_attr_e( 'مسیر صفحه', 'samabar' ); ?>">
				<a href="<?php echo esc_url( $home_url ); ?>">صفحه اصلی</a>
				<span class="pricing-breadcrumb__sep" aria-hidden="true">/</span>
				<span aria-current="page">محاسبه قیمت</span>
			</nav>
			<h1 class="pricing-hero__title text-headline-xl">محاسبه سریع هزینه حمل بار</h1>
			<p class="pricing-hero__subtitle text-body-lg">قیمت‌گذاری شفاف، بدون تماس، فوری</p>
		</div>
	</section>

	<section class="section pricing-main">
		<div class="container">
			<div class="pricing-layout">
				<div class="pricing-form-panel">
					<h2 class="pricing-form-panel__title text-headline-md">
						<span class="material-symbols-outlined icon icon--filled">calculate</span>
						اطلاعات محموله
					</h2>
					<form class="pricing-form" id="pricing-form" action="#" method="get">
						<p class="route-notice" role="note">
							<span class="material-symbols-outlined icon" aria-hidden="true">info</span>
							<span>
								<?php
								printf(
									/* translators: %s: hub city name */
									esc_html__( 'کرایه برای بارگیری از %1$s به سراسر کشور، یا از هر شهر به %1$s محاسبه می‌شود. یک طرف مسیر باید %1$s باشد.', 'samabar' ),
									esc_html( $hub_city )
								);
								?>
							</span>
						</p>
						<div class="pricing-form__row pricing-form__row--2">
							<div class="pricing-form__field">
								<label class="pricing-form__label" for="pricing-origin">مبدا</label>
								<div class="pricing-form__input-wrap">
									<span class="material-symbols-outlined icon">location_on</span>
									<?php
									echo samabar_get_city_select_markup(
										array(
											'id'       => 'pricing-origin',
											'name'     => 'origin',
											'class'    => 'pricing-form__input pricing-form__select',
											'required' => true,
										)
									);
									?>
								</div>
							</div>
							<div class="pricing-form__field">
								<label class="pricing-form__label" for="pricing-destination">مقصد</label>
								<div class="pricing-form__input-wrap">
									<span class="material-symbols-outlined icon">flag</span>
									<?php
									echo samabar_get_city_select_markup(
										array(
											'id'       => 'pricing-destination',
											'name'     => 'destination',
											'class'    => 'pricing-form__input pricing-form__select',
											'required' => true,
										)
									);
									?>
								</div>
							</div>
						</div>
						<p class="route-notice route-notice--error pricing-form__alert" data-pricing-form-error hidden role="alert">
							<span class="material-symbols-outlined icon" aria-hidden="true">error</span>
							<span data-pricing-form-error-text></span>
						</p>
						<div class="pricing-form__row pricing-form__row--2">
							<div class="pricing-form__field">
								<label class="pricing-form__label" for="pricing-weight">وزن تقریبی (کیلوگرم)</label>
								<input class="pricing-form__input" id="pricing-weight" name="weight" type="number" min="1" placeholder="مثال: 500" value="500">
							</div>
							<div class="pricing-form__field">
								<label class="pricing-form__label" for="pricing-service">نوع سرویس</label>
								<select class="pricing-form__input pricing-form__select" id="pricing-service" name="service">
									<option value="normal">عادی</option>
									<option value="express">اکسپرس (فوری)</option>
									<option value="b2b">پروژه‌ای (B2B)</option>
								</select>
							</div>
						</div>
						<div class="pricing-form__actions">
							<button class="btn btn--secondary btn--lg" type="submit">محاسبه قیمت</button>
						</div>
					</form>
				</div>

				<aside class="pricing-result is-empty" id="pricing-result" aria-live="polite">
					<div>
						<div class="pricing-result__head">
							<h3 class="pricing-result__title text-headline-md">کرایه حمل</h3>
							<span class="material-symbols-outlined icon icon--filled">payments</span>
						</div>
						<div class="pricing-result__body">
							<span class="pricing-result__label">مبلغ کرایه</span>
							<div class="pricing-result__price-range" data-price-range>
								<span class="pricing-result__price-mask" aria-hidden="true">＊ ＊ ＊ ＊ ＊</span>
								<span class="pricing-result__price-unit">تومان</span>
							</div>
							<div class="pricing-result__meta">
								<div class="pricing-result__meta-item">
									<span class="material-symbols-outlined icon icon--filled">schedule</span>
									<span data-delivery>—</span>
								</div>
								<div class="pricing-result__meta-item">
									<span class="material-symbols-outlined icon icon--filled">verified</span>
									<span data-service>—</span>
								</div>
							</div>
						</div>
					</div>
					<div class="pricing-result__actions">
						<a class="btn btn--on-primary btn--lg" href="<?php echo esc_url( $order_url ); ?>" data-pricing-order-link>ثبت سفارش با این قیمت</a>
						<button class="btn btn--ghost-light btn--lg" type="button" data-reset>تغییر اطلاعات</button>
					</div>
				</aside>
			</div>
		</div>
	</section>

	<section class="section pricing-info">
		<div class="container">
			<div class="pricing-info__grid">
				<div class="pricing-info__panel">
					<h3 class="pricing-info__panel-title text-headline-md">عوامل موثر بر قیمت</h3>
					<div class="pricing-factor">
						<span class="material-symbols-outlined pricing-factor__icon icon">route</span>
						<div>
							<h4 class="pricing-factor__title">مسافت مبدا تا مقصد</h4>
							<p class="pricing-factor__text">مسیرهای طولانی‌تر و صعب‌العبور هزینه بیشتری دارند.</p>
						</div>
					</div>
					<div class="pricing-factor">
						<span class="material-symbols-outlined pricing-factor__icon icon">weight</span>
						<div>
							<h4 class="pricing-factor__title">وزن و حجم بار</h4>
							<p class="pricing-factor__text">نیاز به ناوگان بزرگتر یا تجهیزات خاص.</p>
						</div>
					</div>
					<div class="pricing-factor">
						<span class="material-symbols-outlined pricing-factor__icon icon">speed</span>
						<div>
							<h4 class="pricing-factor__title">سطح سرویس (عادی/فوری)</h4>
							<p class="pricing-factor__text">سرویس‌های اکسپرس دارای ضریب قیمتی بالاتری هستند.</p>
						</div>
					</div>
				</div>
				<div class="pricing-info__panel">
					<h3 class="pricing-info__panel-title pricing-info__panel-title--plain text-headline-md">چرا قیمت‌گذاری سما بار؟</h3>
					<div class="pricing-benefits">
						<div class="pricing-benefit">
							<span class="material-symbols-outlined icon">phone_disabled</span>
							<span class="pricing-benefit__label">بدون نیاز به تماس</span>
						</div>
						<div class="pricing-benefit">
							<span class="material-symbols-outlined icon">handshake</span>
							<span class="pricing-benefit__label">بدون چانه‌زنی</span>
						</div>
						<div class="pricing-benefit pricing-benefit--wide">
							<span class="material-symbols-outlined icon">bolt</span>
							<span class="pricing-benefit__label">دریافت نتیجه فوری و سیستمی</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
