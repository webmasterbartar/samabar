<?php
/**
 * Single-page order form.
 *
 * @package Samabar
 */

$hub_city        = samabar_get_hub_city();
$tracking_base   = samabar_get_tracking_url();
$dashboard_base  = samabar_get_dashboard_url();
$home_url        = home_url( '/' );
?>
<form class="order-form" id="order-form" novalidate>
	<div class="order-step-intro">
		<h1 class="order-step-intro__title text-headline-lg">ثبت سفارش حمل بار</h1>
		<p class="order-step-intro__text text-body-md">مسیر، مشخصات بار و اطلاعات تماس را در همین صفحه وارد کنید. کرایه بر اساس نرخنامه به‌صورت خودکار محاسبه می‌شود.</p>
	</div>

	<div class="order-layout">
		<div class="order-layout__main">
			<div class="order-route">
				<p class="route-notice" role="note">
					<span class="material-symbols-outlined icon" aria-hidden="true">info</span>
					<span>
						<?php
						printf(
							/* translators: %s: hub city */
							esc_html__( 'بارگیری از %1$s به سراسر کشور، یا از هر شهر به %1$s. یک طرف مسیر همیشه %1$s است.', 'samabar' ),
							esc_html( $hub_city )
						);
						?>
					</span>
				</p>
				<p class="route-notice route-notice--error" data-route-notice hidden role="alert">
					<span class="material-symbols-outlined icon" aria-hidden="true">error</span>
					<span data-route-notice-text></span>
				</p>

				<article class="order-card order-route-card">
					<div class="order-route-grid">
						<section class="order-route-col order-route-col--origin">
							<div class="order-route-col__head">
								<span class="order-route-col__icon order-route-col__icon--origin">
									<span class="material-symbols-outlined icon icon--filled">radio_button_checked</span>
								</span>
								<h2 class="order-route-col__title">مبدا بارگیری</h2>
							</div>
							<label class="order-field order-field--compact">
								<span class="order-field__label">شهر / استان <span class="order-section__req">*</span></span>
								<div class="order-field__wrap">
									<span class="material-symbols-outlined icon">location_city</span>
									<?php
									echo samabar_get_city_select_markup(
										array(
											'id'       => 'order-origin-city',
											'name'     => 'origin_city',
											'class'    => 'order-field__input order-field__select',
											'required' => true,
										)
									);
									?>
								</div>
							</label>
							<label class="order-field order-field--compact">
								<span class="order-field__label">آدرس دقیق <span class="order-section__req">*</span></span>
								<textarea class="order-field__textarea order-field__textarea--compact" name="origin_address" id="order-origin-address" rows="2" placeholder="خیابان، پایانه یا انبار..." required></textarea>
							</label>
						</section>

						<div class="order-route-grid__divider" aria-hidden="true">
							<span class="material-symbols-outlined icon">arrow_downward</span>
						</div>

						<section class="order-route-col order-route-col--dest">
							<div class="order-route-col__head">
								<span class="order-route-col__icon order-route-col__icon--dest">
									<span class="material-symbols-outlined icon icon--filled">location_on</span>
								</span>
								<h2 class="order-route-col__title">مقصد تخلیه</h2>
							</div>
							<label class="order-field order-field--compact">
								<span class="order-field__label">شهر / استان <span class="order-section__req">*</span></span>
								<div class="order-field__wrap">
									<span class="material-symbols-outlined icon">location_city</span>
									<?php
									echo samabar_get_city_select_markup(
										array(
											'id'       => 'order-destination-city',
											'name'     => 'destination_city',
											'class'    => 'order-field__input order-field__select',
											'required' => true,
										)
									);
									?>
								</div>
							</label>
							<label class="order-field order-field--compact">
								<span class="order-field__label">آدرس دقیق <span class="order-section__req">*</span></span>
								<textarea class="order-field__textarea order-field__textarea--compact" name="destination_address" id="order-destination-address" rows="2" placeholder="کارخانه، شهرک صنعتی..." required></textarea>
							</label>
						</section>
					</div>
				</article>

				<article class="order-card">
					<div class="order-card__head">
						<span class="order-card__icon">
							<span class="material-symbols-outlined icon icon--filled">inventory_2</span>
						</span>
						<h2 class="order-card__title text-headline-md">مشخصات محموله</h2>
					</div>
					<div class="order-panel order-panel--flat">
						<label class="order-field">
							<span class="order-field__label">وزن تقریبی (کیلوگرم) <span class="order-section__req">*</span></span>
							<div class="order-field__suffix">
								<input class="order-field__input order-field__input--plain" type="number" name="weight" id="order-weight" min="1" placeholder="مثلاً: ۱۵۰۰" required>
								<span class="order-field__unit">KG</span>
							</div>
						</label>
						<div class="order-field">
							<span class="order-field__label">ابعاد تقریبی (اختیاری)</span>
							<div class="order-dims">
								<input class="order-field__input order-field__input--plain" type="number" name="dim_length" id="order-dim-length" placeholder="طول (m)" step="0.1">
								<input class="order-field__input order-field__input--plain" type="number" name="dim_width" id="order-dim-width" placeholder="عرض (m)" step="0.1">
								<input class="order-field__input order-field__input--plain" type="number" name="dim_height" id="order-dim-height" placeholder="ارتفاع (m)" step="0.1">
							</div>
						</div>
						<label class="order-field">
							<span class="order-field__label">توضیحات تکمیلی (اختیاری)</span>
							<textarea class="order-field__textarea" name="description" id="order-description" rows="3" placeholder="هرگونه توضیحاتی که راننده باید در مورد بار بداند..."></textarea>
						</label>
					</div>
				</article>

				<article class="order-card">
					<div class="order-card__head">
						<span class="order-card__icon order-card__icon--contact">
							<span class="material-symbols-outlined icon icon--filled">person</span>
						</span>
						<h2 class="order-card__title text-headline-md">اطلاعات تماس</h2>
					</div>
					<p class="order-contact__hint text-body-md">برای هماهنگی بارگیری با شما تماس می‌گیریم.</p>
					<div class="order-contact__fields">
						<label class="order-contact__field">
							<span class="order-contact__label">نام و نام خانوادگی <span class="order-section__req">*</span></span>
							<input class="order-contact__input" type="text" name="full_name" id="order-full-name" autocomplete="name" placeholder="مثال: علی محمدی" required>
						</label>
						<label class="order-contact__field">
							<span class="order-contact__label">شماره موبایل <span class="order-section__req">*</span></span>
							<input class="order-contact__input order-contact__input--ltr" type="tel" name="phone" id="order-phone" autocomplete="tel" inputmode="numeric" placeholder="09123456789" required>
						</label>
						<label class="order-contact__field">
							<span class="order-contact__label">نام شرکت <small>(اختیاری)</small></span>
							<input class="order-contact__input" type="text" name="company" id="order-company" autocomplete="organization" placeholder="مثال: شرکت فولاد آریا">
						</label>
					</div>
				</article>
			</div>
		</div>

		<aside class="order-layout__side" data-order-summary>
			<div class="order-route-status order-card">
				<span class="material-symbols-outlined icon order-route-status__icon">route</span>
				<div class="order-route-status__body">
					<span class="order-route-status__label">وضعیت مسیر</span>
					<p class="order-route-status__text" data-map-status>مسیر هنوز مشخص نشده است</p>
				</div>
			</div>

			<div class="order-card order-card--compact">
				<h3 class="order-card__title text-headline-md">
					<span class="material-symbols-outlined icon">calendar_month</span>
					زمان بارگیری <small>(اختیاری)</small>
				</h3>
				<div class="order-datetime" id="order-pickup-datetime">
					<input type="hidden" name="pickup_date" id="order-pickup-date" data-persian-datetime-value>

					<button
						type="button"
						class="order-datetime__trigger"
						id="order-pickup-trigger"
						data-persian-datetime-trigger
						data-placeholder="انتخاب روز و ساعت..."
						aria-expanded="false"
						aria-haspopup="dialog"
					>
						<span class="material-symbols-outlined order-datetime__trigger-icon" aria-hidden="true">event</span>
						<span class="order-datetime__trigger-text">انتخاب روز و ساعت...</span>
						<span class="material-symbols-outlined order-datetime__trigger-chevron" aria-hidden="true">expand_more</span>
					</button>

					<div class="order-datetime-modal" data-persian-datetime-modal hidden>
						<div class="order-datetime-modal__backdrop" data-persian-datetime-backdrop></div>
						<div class="order-datetime__dialog" data-persian-datetime-panel role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'انتخاب تاریخ و ساعت', 'samabar' ); ?>">
							<div class="order-datetime__dialog-head">
								<div>
									<h4 class="order-datetime__dialog-title">انتخاب زمان بارگیری</h4>
									<p class="order-datetime__dialog-preview" data-persian-datetime-preview>تاریخی انتخاب نشده</p>
								</div>
								<button type="button" class="order-datetime__close" data-persian-datetime-close aria-label="<?php esc_attr_e( 'بستن', 'samabar' ); ?>">
									<span class="material-symbols-outlined icon">close</span>
								</button>
							</div>

							<div class="order-datetime__calendar">
								<div class="order-datetime__head">
									<button type="button" class="order-datetime__nav" data-persian-datetime-next aria-label="<?php esc_attr_e( 'ماه بعد', 'samabar' ); ?>">
										<span class="material-symbols-outlined icon">chevron_left</span>
									</button>
									<strong class="order-datetime__month" data-persian-datetime-month></strong>
									<button type="button" class="order-datetime__nav" data-persian-datetime-prev aria-label="<?php esc_attr_e( 'ماه قبل', 'samabar' ); ?>">
										<span class="material-symbols-outlined icon">chevron_right</span>
									</button>
								</div>
								<div class="order-datetime__days" data-persian-datetime-days></div>
								<div class="order-datetime__legend" aria-hidden="true">
									<span class="order-datetime__legend-item order-datetime__legend-item--available">روز خالی</span>
									<span class="order-datetime__legend-item order-datetime__legend-item--full">رزرو شده</span>
								</div>
							</div>

							<div class="order-datetime__time">
								<label class="order-datetime__time-field">
									<span class="order-datetime__time-label">ساعت</span>
									<select data-persian-datetime-hour aria-label="<?php esc_attr_e( 'ساعت', 'samabar' ); ?>"></select>
								</label>
								<span class="order-datetime__time-sep">:</span>
								<label class="order-datetime__time-field">
									<span class="order-datetime__time-label">دقیقه</span>
									<select data-persian-datetime-minute aria-label="<?php esc_attr_e( 'دقیقه', 'samabar' ); ?>"></select>
								</label>
							</div>

							<div class="order-datetime__dialog-foot">
								<button type="button" class="order-datetime__link" data-persian-datetime-clear>پاک کردن</button>
								<button type="button" class="order-datetime__link" data-persian-datetime-today>رفتن به امروز</button>
								<button type="button" class="btn btn--primary order-datetime__confirm" data-persian-datetime-confirm>تایید و ثبت</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="order-summary order-summary--sidebar">
				<div class="order-summary__head">
					<h2 class="text-headline-md">خلاصه سفارش</h2>
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
						<span class="text-headline-lg order-summary__freight" data-review-total>
							<span class="order-summary__freight-mask">＊ ＊ ＊ ＊ ＊</span>
						</span>
					</div>
					<button class="btn btn--secondary btn--lg order-summary__submit" type="submit" id="order-submit">
						تایید و ثبت سفارش
						<span class="material-symbols-outlined icon icon--filled">check_circle</span>
					</button>
					<div class="order-summary__trust">
						<div><span class="material-symbols-outlined icon">shield</span><span>بیمه بار</span></div>
						<div><span class="material-symbols-outlined icon">support_agent</span><span>پشتیبانی تلفنی</span></div>
						<div><span class="material-symbols-outlined icon">cancel</span><span>لغو آسان</span></div>
					</div>
				</div>
			</div>
		</aside>
	</div>
</form>

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
