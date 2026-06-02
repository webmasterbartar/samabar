<?php
/**
 * Front page template.
 *
 * @package Samabar
 */

get_header();

$services_url = samabar_get_services_url();
$pricing_url  = samabar_get_pricing_url();
$order_url    = samabar_get_order_url();
?>

<main id="primary" class="site-main site-main--front">

	<!-- Hero -->
	<section class="hero">
		<div class="container hero__inner">
			<span class="hero__badge">
				<span class="hero__badge-dot" aria-hidden="true"></span>
				بیش از ۱۲۰ هزار بار موفق
			</span>
			<h1 class="hero__title text-headline-xl">حمل بار سریع، امن و هوشمند در سراسر ایران</h1>
			<p class="hero__subtitle text-body-lg">ثبت سفارش آنلاین، اتصال به بزرگترین ناوگان حمل‌ونقل و پیگیری لحظه‌ای محموله.</p>

			<div class="hero__actions">
				<a class="btn btn--secondary btn--lg btn--shadow" href="<?php echo esc_url( $order_url ); ?>">ثبت سفارش حمل بار</a>
				<a class="btn btn--outline btn--lg" href="<?php echo esc_url( $pricing_url ); ?>">استعلام قیمت</a>
			</div>

			<form class="hero-form" id="hero-form" action="<?php echo esc_url( $order_url ); ?>" method="get">
				<div class="hero-form__field">
					<label class="hero-form__label" for="hero-origin">مبدا</label>
					<input class="hero-form__input" id="hero-origin" name="origin" placeholder="شهر یا آدرس مبدا" type="text">
				</div>
				<div class="hero-form__field">
					<label class="hero-form__label" for="hero-destination">مقصد</label>
					<input class="hero-form__input" id="hero-destination" name="destination" placeholder="شهر یا آدرس مقصد" type="text">
				</div>
				<div class="hero-form__field hero-form__field--select">
					<label class="hero-form__label" for="hero-cargo">نوع بار</label>
					<select class="hero-form__input hero-form__select" id="hero-cargo" name="cargo" required>
						<option disabled selected value="">انتخاب کنید</option>
						<option value="light">سبک</option>
						<option value="heavy">سنگین</option>
						<option value="refrigerated">یخچال‌دار</option>
					</select>
				</div>
				<button class="btn btn--primary hero-form__submit" type="submit">ادامه</button>
			</form>
		</div>
	</section>

	<!-- Trust Stats -->
	<section class="stats">
		<div class="container stats__grid">
			<div class="stats__item">
				<span class="material-symbols-outlined stats__icon icon icon--lg icon--filled">task_alt</span>
				<span class="stats__value" dir="ltr">+120,000</span>
				<span class="stats__label">بار موفق</span>
			</div>
			<div class="stats__item">
				<span class="material-symbols-outlined stats__icon icon icon--lg icon--filled">directions_car</span>
				<span class="stats__value" dir="ltr">+5,000</span>
				<span class="stats__label">راننده فعال</span>
			</div>
			<div class="stats__item">
				<span class="material-symbols-outlined stats__icon icon icon--lg icon--filled">map</span>
				<span class="stats__value">سراسر کشور</span>
				<span class="stats__label">پوشش کامل</span>
			</div>
			<div class="stats__item">
				<span class="material-symbols-outlined stats__icon icon icon--lg icon--filled">sentiment_satisfied</span>
				<span class="stats__value" dir="ltr">98%</span>
				<span class="stats__label">رضایت مشتری</span>
			</div>
		</div>
	</section>

	<!-- Services -->
	<section class="section">
		<div class="container">
			<div class="section-header">
				<h2 class="section-title">خدمات اصلی سما بار</h2>
				<p class="section-subtitle">ارائه دهنده انواع خدمات حمل و نقل جاده‌ای متناسب با نیازهای شما</p>
			</div>

			<div class="services__grid">
				<a class="card card--padded-sm card--hover service-card" href="<?php echo esc_url( $services_url ); ?>">
					<div class="service-card__icon-wrap">
						<span class="material-symbols-outlined icon icon--md">local_shipping</span>
					</div>
					<h3 class="service-card__title text-headline-md">حمل درون شهری</h3>
					<p class="service-card__text text-body-md">جابجایی سریع و ایمن بارهای شما در سطح شهر با وانت، نیسان و خاور.</p>
				</a>
				<a class="card card--padded-sm card--hover service-card" href="<?php echo esc_url( $services_url ); ?>">
					<div class="service-card__icon-wrap">
						<span class="material-symbols-outlined icon icon--md">directions_bus</span>
					</div>
					<h3 class="service-card__title text-headline-md">حمل برون شهری</h3>
					<p class="service-card__text text-body-md">ارسال بار به تمام نقاط کشور با کامیون، تریلی و ماشین‌های سنگین.</p>
				</a>
				<a class="card card--padded-sm card--hover service-card" href="<?php echo esc_url( $services_url ); ?>">
					<div class="service-card__icon-wrap">
						<span class="material-symbols-outlined icon icon--md">agriculture</span>
					</div>
					<h3 class="service-card__title text-headline-md">بارهای سنگین</h3>
					<p class="service-card__text text-body-md">حمل ماشین‌آلات و تجهیزات سنگین صنعتی با رعایت تمام اصول ایمنی.</p>
				</a>
				<a class="card card--padded-sm card--hover service-card" href="<?php echo esc_url( $services_url ); ?>">
					<div class="service-card__icon-wrap">
						<span class="material-symbols-outlined icon icon--md">ac_unit</span>
					</div>
					<h3 class="service-card__title text-headline-md">بارهای ویژه</h3>
					<p class="service-card__text text-body-md">حمل مواد فاسدشدنی و حساس با ماشین‌های یخچال‌دار و مخصوص.</p>
				</a>
			</div>

			<div class="services__actions">
				<a class="btn btn--outline" href="<?php echo esc_url( $services_url ); ?>">مشاهده همه خدمات</a>
			</div>
		</div>
	</section>

	<!-- How it Works -->
	<section class="section section--tint">
		<div class="container">
			<div class="section-header">
				<h2 class="section-title">سما بار چگونه کار می‌کند؟</h2>
				<p class="section-subtitle">چهار مرحله ساده برای رساندن بار شما به مقصد</p>
			</div>

			<div class="steps">
				<div class="step">
					<div class="step__number">۱</div>
					<h3 class="step__title text-headline-sm">ثبت سفارش</h3>
					<p class="step__text">مشخصات بار و مبدا/مقصد را وارد کنید</p>
				</div>
				<div class="step">
					<div class="step__number">۲</div>
					<h3 class="step__title text-headline-sm">انتخاب راننده</h3>
					<p class="step__text">نزدیک‌ترین و مناسب‌ترین راننده پیدا می‌شود</p>
				</div>
				<div class="step">
					<div class="step__number">۳</div>
					<h3 class="step__title text-headline-sm">بارگیری</h3>
					<p class="step__text">راننده در محل حاضر شده و بارگیری انجام می‌شود</p>
				</div>
				<div class="step">
					<div class="step__number">۴</div>
					<h3 class="step__title text-headline-sm">تحویل و پرداخت</h3>
					<p class="step__text">بار در مقصد تحویل داده شده و هزینه پرداخت می‌شود</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Advantages -->
	<section class="section">
		<div class="container advantages">
			<div>
				<h2 class="advantages__title text-headline-lg">چرا سما بار را انتخاب کنید؟</h2>
				<div class="advantages__list">
					<div class="advantage-item">
						<span class="material-symbols-outlined advantage-item__icon icon">check_circle</span>
						<div>
							<h4 class="advantage-item__title text-headline-sm">قیمت‌گذاری شفاف</h4>
							<p class="advantage-item__text text-body-md">بدون هزینه‌های پنهان، قیمت نهایی قبل از ثبت سفارش مشخص می‌شود.</p>
						</div>
					</div>
					<div class="advantage-item">
						<span class="material-symbols-outlined advantage-item__icon icon">support_agent</span>
						<div>
							<h4 class="advantage-item__title text-headline-sm">پشتیبانی تلفنی</h4>
							<p class="advantage-item__text text-body-md">تیم پشتیبانی ما <?php echo esc_html( samabar_get_contact_hours() ); ?> پاسخگوی شماست.</p>
						</div>
					</div>
					<div class="advantage-item">
						<span class="material-symbols-outlined advantage-item__icon icon">my_location</span>
						<div>
							<h4 class="advantage-item__title text-headline-sm">پیگیری لحظه‌ای</h4>
							<p class="advantage-item__text text-body-md">موقعیت دقیق بار خود را بر روی نقشه به صورت آنلاین مشاهده کنید.</p>
						</div>
					</div>
					<div class="advantage-item">
						<span class="material-symbols-outlined advantage-item__icon icon">shield</span>
						<div>
							<h4 class="advantage-item__title text-headline-sm">بیمه بار</h4>
							<p class="advantage-item__text text-body-md">تمام بارهای ارسالی توسط سما بار دارای بیمه معتبر هستند.</p>
						</div>
					</div>
				</div>
			</div>

			<div class="advantages__panel">
				<div class="advantages__badges">
					<div class="badge-card">
						<span class="material-symbols-outlined badge-card__icon icon icon--lg">verified</span>
						<span class="badge-card__title">رانندگان تایید شده</span>
					</div>
					<div class="badge-card">
						<span class="material-symbols-outlined badge-card__icon icon icon--lg">speed</span>
						<span class="badge-card__title">سرعت بالا</span>
					</div>
					<div class="badge-card">
						<span class="material-symbols-outlined badge-card__icon icon icon--lg">payments</span>
						<span class="badge-card__title">پرداخت امن</span>
					</div>
					<div class="badge-card">
						<span class="material-symbols-outlined badge-card__icon icon icon--lg">handshake</span>
						<span class="badge-card__title">تضمین کیفیت</span>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- CTA -->
	<section class="section cta-section">
		<div class="container">
			<div class="cta-section__inner">
				<h2 class="cta-section__title">همین الان بار خود را ثبت کنید</h2>
				<p class="cta-section__text">با وارد کردن شماره تماس، کارشناسان ما در سریع‌ترین زمان با شما تماس می‌گیرند.</p>
				<form class="cta-section__form" action="#" method="get">
					<input class="form-input form-input--ltr" placeholder="0912 345 6789" type="tel">
					<button class="btn btn--secondary btn--nowrap" type="submit">ثبت درخواست</button>
				</form>
			</div>
		</div>
	</section>

	<!-- Testimonials -->
	<section class="section section--muted">
		<div class="container">
			<div class="section-header">
				<h2 class="section-title">نظرات مشتریان ما</h2>
				<p class="section-subtitle">تجربه شرکت‌ها و افرادی که به سما بار اعتماد کرده‌اند</p>
			</div>
		</div>

		<div class="testimonials-carousel">
			<div class="testimonials-carousel__track">
				<div class="testimonials-carousel__group">
					<article class="card card--padded-md testimonial-card">
						<div class="testimonial-card__stars">
							<span class="material-symbols-outlined icon icon--filled">star</span>
							<span class="material-symbols-outlined icon icon--filled">star</span>
							<span class="material-symbols-outlined icon icon--filled">star</span>
							<span class="material-symbols-outlined icon icon--filled">star</span>
							<span class="material-symbols-outlined icon icon--filled">star</span>
						</div>
						<p class="testimonial-card__quote text-body-md">"بسیار سریع و با قیمت مناسب. پشتیبانی عالی بود و بار ما بدون هیچ آسیبی به مقصد رسید."</p>
						<div class="testimonial-card__author">
							<div class="testimonial-card__avatar">م</div>
							<div>
								<div class="testimonial-card__name">محمد احمدی</div>
								<div class="testimonial-card__role">مدیر فروش</div>
							</div>
						</div>
					</article>
					<article class="card card--padded-md testimonial-card">
						<div class="testimonial-card__stars">
							<span class="material-symbols-outlined icon icon--filled">star</span>
							<span class="material-symbols-outlined icon icon--filled">star</span>
							<span class="material-symbols-outlined icon icon--filled">star</span>
							<span class="material-symbols-outlined icon icon--filled">star</span>
							<span class="material-symbols-outlined icon icon--filled">star</span>
						</div>
						<p class="testimonial-card__quote text-body-md">"برای شرکت ما که ارسال روزانه داریم، سما بار بهترین گزینه است. شفافیت در قیمت‌ها و پیگیری آنلاین واقعا کمک‌کننده است."</p>
						<div class="testimonial-card__author">
							<div class="testimonial-card__avatar">س</div>
							<div>
								<div class="testimonial-card__name">سارا رضایی</div>
								<div class="testimonial-card__role">مدیر لجستیک</div>
							</div>
						</div>
					</article>
					<article class="card card--padded-md testimonial-card">
						<div class="testimonial-card__stars">
							<span class="material-symbols-outlined icon icon--filled">star</span>
							<span class="material-symbols-outlined icon icon--filled">star</span>
							<span class="material-symbols-outlined icon icon--filled">star</span>
							<span class="material-symbols-outlined icon icon--filled">star</span>
							<span class="material-symbols-outlined icon icon--filled">star_half</span>
						</div>
						<p class="testimonial-card__quote text-body-md">"راننده‌ها بسیار خوش‌برخورد و حرفه‌ای هستند. از بیمه بار هم استفاده کردیم که خیالمان را راحت کرد."</p>
						<div class="testimonial-card__author">
							<div class="testimonial-card__avatar">ع</div>
							<div>
								<div class="testimonial-card__name">علی کریمی</div>
								<div class="testimonial-card__role">صاحب کسب‌وکار</div>
							</div>
						</div>
					</article>
				</div>
			</div>
		</div>
	</section>

	<!-- FAQ -->
	<section class="section section--tint" id="faq">
		<div class="container container--narrow">
			<div class="section-header">
				<h2 class="section-title">سوالات متداول</h2>
			</div>

			<div class="faq__list">
				<details class="card faq__item">
					<summary>
						<span>هزینه حمل بار چگونه محاسبه می‌شود؟</span>
						<span class="material-symbols-outlined icon">expand_more</span>
					</summary>
					<p class="text-body-md">هزینه بر اساس مسافت، نوع ماشین، وزن بار و شرایط ویژه (مثل نیاز به کارگر یا بیمه اضافی) محاسبه می‌شود. قیمت اعلام شده نهایی بوده و تغییر نمی‌کند.</p>
				</details>
				<details class="card faq__item">
					<summary>
						<span>آیا بارهای ارسالی بیمه می‌شوند؟</span>
						<span class="material-symbols-outlined icon">expand_more</span>
					</summary>
					<p class="text-body-md">بله، تمامی بارهای ارسالی توسط سما بار تا سقف مشخصی به صورت پایه بیمه هستند. برای بارهای با ارزش بالا، امکان افزایش سقف بیمه وجود دارد.</p>
				</details>
				<details class="card faq__item">
					<summary>
						<span>چقدر زمان می‌برد تا راننده به مبدا برسد؟</span>
						<span class="material-symbols-outlined icon">expand_more</span>
					</summary>
					<p class="text-body-md">بسته به زمان ثبت سفارش و نوع ماشین درخواستی، معمولا بین ۳۰ تا ۹۰ دقیقه زمان می‌برد تا راننده در محل حاضر شود.</p>
				</details>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
