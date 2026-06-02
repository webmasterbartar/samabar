<?php
/**
 * Template Name: تماس با ما
 * Contact page.
 *
 * @package Samabar
 */

get_header();
?>

<main id="primary" class="site-main site-main--contact">
	<section class="static-hero static-hero--soft">
		<div class="container static-hero__inner">
			<h1 class="static-hero__title text-headline-xl">ارتباط با تیم سما بار</h1>
			<p class="static-hero__text text-body-lg">ما همیشه آماده پاسخگویی به سوالات شما و ارائه بهترین راهکارهای حمل و نقل هستیم. با ما در تماس باشید.</p>
		</div>
	</section>

	<section class="contact-cards">
		<div class="container contact-cards__grid">
			<article class="contact-card">
				<span class="contact-card__icon" aria-hidden="true"><span class="material-symbols-outlined icon icon--filled">call</span></span>
				<h2 class="text-headline-md">تلفن</h2>
				<p class="text-body-md" dir="ltr"><a href="<?php echo esc_url( samabar_get_contact_phone_url() ); ?>"><?php echo esc_html( samabar_get_contact_phone_display() ); ?></a></p>
			</article>
			<article class="contact-card">
				<span class="contact-card__icon" aria-hidden="true"><span class="material-symbols-outlined icon icon--filled">mail</span></span>
				<h2 class="text-headline-md">ایمیل</h2>
				<p class="text-body-md" dir="ltr"><a href="mailto:<?php echo esc_attr( samabar_get_contact_email() ); ?>"><?php echo esc_html( samabar_get_contact_email() ); ?></a></p>
			</article>
			<article class="contact-card">
				<span class="contact-card__icon" aria-hidden="true"><span class="material-symbols-outlined icon icon--filled">schedule</span></span>
				<h2 class="text-headline-md">ساعات کاری</h2>
				<p class="text-body-md"><?php echo esc_html( samabar_get_contact_hours() ); ?></p>
			</article>
			<article class="contact-card">
				<span class="contact-card__icon" aria-hidden="true"><span class="material-symbols-outlined icon icon--filled">share</span></span>
				<h2 class="text-headline-md">شبکه‌های اجتماعی</h2>
				<div class="contact-card__social">
					<a href="#" aria-label="<?php esc_attr_e( 'وب‌سایت', 'samabar' ); ?>"><span class="material-symbols-outlined icon">language</span></a>
					<a href="#" aria-label="<?php esc_attr_e( 'لینک', 'samabar' ); ?>"><span class="material-symbols-outlined icon">link</span></a>
				</div>
			</article>
		</div>
	</section>

	<section class="contact-layout">
		<div class="container contact-layout__grid">
			<div class="contact-form-card">
				<h2 class="text-headline-lg">ارسال پیام</h2>
				<form class="contact-form" id="contact-form">
					<div class="contact-form__row">
						<label class="contact-form__field">
							<span class="contact-form__label">نام و نام خانوادگی</span>
							<input class="contact-form__input" type="text" name="full_name" id="contact-name" placeholder="مثال: علی محمدی" required>
						</label>
						<label class="contact-form__field">
							<span class="contact-form__label">شماره تماس</span>
							<input class="contact-form__input" type="tel" name="phone" id="contact-phone" inputmode="numeric" placeholder="09123456789" dir="ltr">
						</label>
					</div>
					<label class="contact-form__field">
						<span class="contact-form__label">موضوع</span>
						<input class="contact-form__input" type="text" name="subject" id="contact-subject" placeholder="موضوع پیام شما" required>
					</label>
					<label class="contact-form__field">
						<span class="contact-form__label">پیام</span>
						<textarea class="contact-form__input contact-form__textarea" name="message" id="contact-message" rows="5" placeholder="متن پیام خود را اینجا بنویسید..." required></textarea>
					</label>
					<button class="btn btn--secondary btn--block-mobile" type="submit" id="contact-submit">ارسال پیام</button>
					<p class="contact-form__notice" id="contact-notice" hidden></p>
				</form>
			</div>

			<div class="contact-location">
				<div class="contact-location__body">
					<h2 class="text-headline-lg">آدرس دفتر مرکزی</h2>
					<p class="contact-location__line text-body-md">
						<span class="material-symbols-outlined icon icon--filled">location_on</span>
						<span><?php echo esc_html( samabar_get_contact_address() ); ?></span>
					</p>
					<p class="contact-location__line text-body-md">
						<span class="material-symbols-outlined icon icon--filled">call</span>
						<a href="<?php echo esc_url( samabar_get_contact_phone_url() ); ?>" dir="ltr"><?php echo esc_html( samabar_get_contact_phone_display() ); ?></a>
					</p>
					<p class="contact-location__line text-body-md">
						<span class="material-symbols-outlined icon icon--filled">mail</span>
						<a href="mailto:<?php echo esc_attr( samabar_get_contact_email() ); ?>" dir="ltr"><?php echo esc_html( samabar_get_contact_email() ); ?></a>
					</p>
					<p class="contact-location__line text-body-md">
						<span class="material-symbols-outlined icon icon--filled">schedule</span>
						<span><?php echo esc_html( samabar_get_contact_hours() ); ?></span>
					</p>
				</div>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
