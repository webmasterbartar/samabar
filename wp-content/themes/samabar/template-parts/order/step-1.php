<?php
/**
 * Order step 1: Route.
 *
 * @package Samabar
 */

$order_base = samabar_get_order_url();
?>
<form class="order-form" id="order-form-step-1" action="<?php echo esc_url( add_query_arg( 'step', '2', $order_base ) ); ?>" method="get">
	<input type="hidden" name="step" value="2">

	<div class="order-layout">
		<div class="order-layout__main">
			<div class="order-route">
				<article class="order-card order-route-card">
					<div class="order-route-grid">
						<section class="order-route-col order-route-col--origin">
							<div class="order-route-col__head">
								<span class="order-route-col__icon order-route-col__icon--origin">
									<span class="material-symbols-outlined icon icon--filled">radio_button_checked</span>
								</span>
								<h2 class="order-route-col__title">مبدا بارگیری</h2>
							</div>
							<div class="order-route-col__row">
								<label class="order-field order-field--compact">
									<span class="order-field__label">شهر / استان <span class="order-section__req">*</span></span>
									<div class="order-field__wrap">
										<span class="material-symbols-outlined icon">location_city</span>
										<input class="order-field__input" type="text" name="origin_city" id="order-origin-city" placeholder="تهران" required>
									</div>
								</label>
								<label class="order-field order-field--compact">
									<span class="order-field__label">پلاک / واحد <small>(اختیاری)</small></span>
									<input class="order-field__input order-field__input--plain" type="text" name="origin_detail" id="order-origin-detail" placeholder="پلاک ۱۲">
								</label>
							</div>
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
							<div class="order-route-col__row">
								<label class="order-field order-field--compact">
									<span class="order-field__label">شهر / استان <span class="order-section__req">*</span></span>
									<div class="order-field__wrap">
										<span class="material-symbols-outlined icon">location_city</span>
										<input class="order-field__input" type="text" name="destination_city" id="order-destination-city" placeholder="تبریز" required>
									</div>
								</label>
								<label class="order-field order-field--compact">
									<span class="order-field__label">پلاک / واحد <small>(اختیاری)</small></span>
									<input class="order-field__input order-field__input--plain" type="text" name="destination_detail" id="order-destination-detail" placeholder="سوله ۵">
								</label>
							</div>
							<label class="order-field order-field--compact">
								<span class="order-field__label">آدرس دقیق <span class="order-section__req">*</span></span>
								<textarea class="order-field__textarea order-field__textarea--compact" name="destination_address" id="order-destination-address" rows="2" placeholder="کارخانه، شهرک صنعتی..." required></textarea>
							</label>
						</section>
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

		<aside class="order-layout__side">
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
		</aside>
	</div>

	<div class="order-actions order-actions--end">
		<button class="btn btn--secondary btn--lg" type="submit">
			تایید و مرحله بعد
			<span class="material-symbols-outlined icon">arrow_back</span>
		</button>
	</div>
</form>
