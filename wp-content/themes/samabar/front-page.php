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
$about_url    = samabar_get_about_url();
$contact_url  = samabar_get_contact_url();
$projects     = samabar_get_home_project_gallery();
$highlights   = samabar_get_home_about_highlights();
$hub_city     = samabar_get_hub_city();
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

			<div class="hero-form-wrap">
				<form class="hero-form" id="hero-form" action="<?php echo esc_url( $order_url ); ?>" method="get">
					<div class="hero-form__field hero-form__field--select">
						<label class="hero-form__label" for="hero-origin">مبدا</label>
						<?php
						echo samabar_get_city_select_markup(
							array(
								'id'       => 'hero-origin',
								'name'     => 'origin',
								'class'    => 'hero-form__input hero-form__select',
								'required' => true,
							)
						);
						?>
					</div>
					<div class="hero-form__field hero-form__field--select">
						<label class="hero-form__label" for="hero-destination">مقصد</label>
						<?php
						echo samabar_get_city_select_markup(
							array(
								'id'       => 'hero-destination',
								'name'     => 'destination',
								'class'    => 'hero-form__input hero-form__select',
								'required' => true,
							)
						);
						?>
					</div>
					<button class="btn btn--primary hero-form__submit" type="submit">ادامه</button>
				</form>
				<p class="route-notice route-notice--error hero-form__route-error" data-hero-route-error hidden role="alert">
					<span class="material-symbols-outlined icon" aria-hidden="true">error</span>
					<span data-hero-route-error-text></span>
				</p>
				<p class="hero-form__route-hint">
					<span class="material-symbols-outlined icon" aria-hidden="true">info</span>
					<?php
					printf(
						/* translators: %s: hub city name */
						esc_html__( 'از %s به همه‌جا، یا از هر شهر به %s — یک طرف مسیر باید %s باشد', 'samabar' ),
						esc_html( $hub_city ),
						esc_html( $hub_city ),
						esc_html( $hub_city )
					);
					?>
				</p>
			</div>
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

	<!-- About Samabar -->
	<section class="section section--tint home-about" id="about-samabar">
		<div class="container home-about__grid">
			<div class="home-about__content">
				<span class="home-about__eyebrow">درباره سما بار</span>
				<h2 class="home-about__title text-headline-lg">همراه مطمئن کسب‌وکارها در حمل و نقل جاده‌ای</h2>
				<p class="home-about__lead text-body-lg">
					سما بار با تکیه بر تجربه عملیاتی و زیرساخت دیجیتال، فرایند حمل بار را از ثبت سفارش تا تحویل نهایی یکپارچه کرده است. ما برای شرکت‌ها، کارخانه‌ها و کسب‌وکارهایی که به زمان‌بندی دقیق و شفافیت هزینه نیاز دارند، راهکار ارائه می‌دهیم.
				</p>
				<p class="home-about__text text-body-md">
					از حمل درون‌شهری با وانت و خاور تا ارسال برون‌شهری با ناوگان سنگین، تیم سما بار در کنار شماست تا بار با امنیت، بیمه و پشتیبانی حرفه‌ای به مقصد برسد. دفتر مرکزی ما در بندرعباس است و خدمات‌رسانی در مسیرهای سراسری کشور انجام می‌شود.
				</p>
				<ul class="home-about__highlights">
					<?php foreach ( $highlights as $item ) : ?>
						<li class="home-about__highlight">
							<span class="material-symbols-outlined icon" aria-hidden="true"><?php echo esc_html( $item['icon'] ); ?></span>
							<span><?php echo esc_html( $item['label'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
				<div class="home-about__actions">
					<a class="btn btn--secondary" href="<?php echo esc_url( $about_url ); ?>">بیشتر درباره ما</a>
					<a class="btn btn--outline" href="<?php echo esc_url( $contact_url ); ?>">تماس با کارشناسان</a>
				</div>
			</div>
			<div class="home-about__aside">
				<div class="home-about__stat-card">
					<span class="material-symbols-outlined icon icon--filled">history</span>
					<strong>+۱۰ سال</strong>
					<span>تجربه عملیاتی در حمل بار</span>
				</div>
				<div class="home-about__stat-card">
					<span class="material-symbols-outlined icon icon--filled">route</span>
					<strong>سراسر ایران</strong>
					<span>پوشش مسیرهای درون‌شهری و بین‌شهری</span>
				</div>
				<div class="home-about__stat-card home-about__stat-card--accent">
					<span class="material-symbols-outlined icon icon--filled">handshake</span>
					<strong>همکاری B2B</strong>
					<span>قراردادهای سازمانی و ارسال دوره‌ای</span>
				</div>
			</div>
		</div>
	</section>

	<!-- Project Gallery -->
	<section class="section home-projects" id="projects">
		<div class="container">
			<div class="section-header">
				<h2 class="section-title">نمونه پروژه‌های اجراشده</h2>
				<p class="section-subtitle">بخشی از پروژه‌های موفق سما بار در حوزه حمل، بارگیری و لجستیک</p>
			</div>
			<div class="home-projects__grid">
				<?php foreach ( $projects as $project ) : ?>
					<figure class="home-projects__item">
						<div class="home-projects__media">
							<img
								src="<?php echo esc_url( samabar_get_theme_asset_url( $project['file'] ) ); ?>"
								alt="<?php echo esc_attr( $project['title'] ); ?>"
								loading="lazy"
								width="640"
								height="480"
							>
						</div>
						<figcaption class="home-projects__caption">
							<strong class="home-projects__title"><?php echo esc_html( $project['title'] ); ?></strong>
							<span class="home-projects__desc"><?php echo esc_html( $project['caption'] ); ?></span>
						</figcaption>
					</figure>
				<?php endforeach; ?>
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
