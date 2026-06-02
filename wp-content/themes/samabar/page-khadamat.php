<?php
/**
 * Template Name: خدمات
 * Template for the services page.
 *
 * @package Samabar
 */

get_header();

$home_url     = home_url( '/' );
$pricing_url  = samabar_get_pricing_url();
$order_url    = samabar_get_order_url();
?>

<main id="primary" class="site-main site-main--services">

	<!-- Hero -->
	<section class="services-hero">
		<div class="services-hero__bg" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuA-Gy0hzbh1KHA2WHlPROfdRG-EO42nyFCYI_Ts0AxXOeFWaipN7f32gcszt4A1Dn4cF-k6XFQ5ccm-UF7s99e0dguKfPHQTyvPxVDuNcvkiYR8fBNfL0T2ArJ7dbFHRC1DCm47SkFyzsdcaGwZQ3ccA1LeMz4jcBFZHdPXPL6ljYea4co0ljyj3Sz0-wUaoP-sjIKSBA-KbXFWMXXYHJYWvuRwKHs1-aj2CrXcaN1H34n1EJjy0alZxtPsbM1yTNT_6ALUCemO3fU');" aria-hidden="true"></div>
		<div class="services-hero__overlay" aria-hidden="true"></div>
		<div class="container services-hero__inner">
			<nav class="services-breadcrumb" aria-label="<?php esc_attr_e( 'مسیر صفحه', 'samabar' ); ?>">
				<a href="<?php echo esc_url( $home_url ); ?>">صفحه اصلی</a>
				<span class="services-breadcrumb__sep" aria-hidden="true">/</span>
				<span aria-current="page">خدمات</span>
			</nav>
			<span class="services-hero__badge">
				<span class="services-hero__badge-dot" aria-hidden="true"></span>
				راهکارهای B2B و سازمانی
			</span>
			<h1 class="services-hero__title text-headline-xl">خدمات حمل و نقل حرفه‌ای برای کسب‌وکارها</h1>
			<p class="services-hero__subtitle text-body-lg">تمرکز روی حمل بار سازمانی، بین‌شهری، پروژه‌ای و تجاری — با قیمت‌گذاری شفاف و پشتیبانی اختصاصی</p>
			<div class="services-hero__actions">
				<a class="btn btn--secondary btn--lg btn--shadow" href="<?php echo esc_url( $order_url ); ?>">ثبت سفارش</a>
				<a class="btn btn--outline-light btn--lg" href="<?php echo esc_url( $pricing_url ); ?>">محاسبه قیمت</a>
			</div>
		</div>
	</section>

	<!-- Quick stats -->
	<section class="services-stats">
		<div class="container services-stats__grid">
			<div class="services-stats__item">
				<span class="material-symbols-outlined services-stats__icon icon icon--filled">domain</span>
				<span class="services-stats__value" dir="ltr">+500</span>
				<span class="services-stats__label">شرکت فعال</span>
			</div>
			<div class="services-stats__item">
				<span class="material-symbols-outlined services-stats__icon icon icon--filled">route</span>
				<span class="services-stats__value">سراسر کشور</span>
				<span class="services-stats__label">پوشش بین‌شهری</span>
			</div>
			<div class="services-stats__item">
				<span class="material-symbols-outlined services-stats__icon icon icon--filled">schedule</span>
				<span class="services-stats__value">۸–۱۶</span>
				<span class="services-stats__label">شنبه تا پنجشنبه</span>
			</div>
			<div class="services-stats__item">
				<span class="material-symbols-outlined services-stats__icon icon icon--filled">verified</span>
				<span class="services-stats__value" dir="ltr">100%</span>
				<span class="services-stats__label">بیمه بار</span>
			</div>
		</div>
	</section>

	<!-- Core Services -->
	<section class="section" id="services-list">
		<div class="container">
			<div class="section-header">
				<h2 class="section-title">خدمات تخصصی سما بار</h2>
				<p class="section-subtitle">راهکارهای متنوع برای نیازهای حمل بار شرکتی، صنعتی و تجاری</p>
			</div>

			<div class="services-page__grid">
				<article class="b2b-card">
					<div class="b2b-card__icon">
						<span class="material-symbols-outlined icon icon--md icon--filled">domain</span>
					</div>
					<h3 class="b2b-card__title text-headline-md">حمل بار شرکتی (B2B)</h3>
					<p class="b2b-card__text text-body-md">مناسب شرکت‌ها، انبارها و کارخانه‌ها — حمل منظم یا قراردادی با گزارش‌دهی دوره‌ای</p>
					<div class="b2b-card__tags">
						<span class="b2b-card__tag">قراردادی</span>
						<span class="b2b-card__tag">گزارش‌دهی</span>
					</div>
				</article>
				<article class="b2b-card">
					<div class="b2b-card__icon">
						<span class="material-symbols-outlined icon icon--md icon--filled">local_shipping</span>
					</div>
					<h3 class="b2b-card__title text-headline-md">حمل بار بین‌شهری</h3>
					<p class="b2b-card__text text-body-md">ارسال بار بین شهرهای ایران — مناسب بارهای تجاری، عمده و پالت‌بندی شده</p>
					<div class="b2b-card__tags">
						<span class="b2b-card__tag">سراسر کشور</span>
						<span class="b2b-card__tag">عمده</span>
					</div>
				</article>
				<article class="b2b-card">
					<div class="b2b-card__icon">
						<span class="material-symbols-outlined icon icon--md icon--filled">engineering</span>
					</div>
					<h3 class="b2b-card__title text-headline-md">حمل بار پروژه‌ای</h3>
					<p class="b2b-card__text text-body-md">پروژه‌های بزرگ سازمانی و صنعتی — حمل چندمرحله‌ای با زمان‌بندی دقیق</p>
					<div class="b2b-card__tags">
						<span class="b2b-card__tag">صنعتی</span>
						<span class="b2b-card__tag">زمان‌بندی</span>
					</div>
				</article>
				<article class="b2b-card b2b-card--featured">
					<div class="b2b-card__icon b2b-card__icon--accent">
						<span class="material-symbols-outlined icon icon--md icon--filled">bolt</span>
					</div>
					<h3 class="b2b-card__title text-headline-md">ارسال سریع (Express)</h3>
					<p class="b2b-card__text text-body-md">ارسال فوری و زمان‌دار — مناسب بارهای حساس زمانی و تحویل در همان روز</p>
					<div class="b2b-card__tags">
						<span class="b2b-card__tag b2b-card__tag--accent">فوری</span>
						<span class="b2b-card__tag">تحویل سریع</span>
					</div>
				</article>
				<article class="b2b-card b2b-card--wide">
					<div class="b2b-card__wide-inner">
						<div class="b2b-card__wide-content">
							<div class="b2b-card__icon">
								<span class="material-symbols-outlined icon icon--md icon--filled">handshake</span>
							</div>
							<div>
								<h3 class="b2b-card__title text-headline-md">لجستیک قراردادی</h3>
								<p class="b2b-card__text text-body-md">همکاری بلندمدت با شرکت‌ها — قیمت‌گذاری سازمانی، ناوگان اختصاصی و یکپارچه‌سازی با سیستم‌های داخلی شما</p>
								<div class="b2b-card__tags">
									<span class="b2b-card__tag">بلندمدت</span>
									<span class="b2b-card__tag">ناوگان اختصاصی</span>
									<span class="b2b-card__tag">API</span>
								</div>
							</div>
						</div>
						<a class="btn btn--outline b2b-card__cta" href="<?php echo esc_url( samabar_get_contact_phone_url() ); ?>">درخواست مشاوره</a>
					</div>
				</article>
			</div>
		</div>
	</section>

	<!-- Brand Advantages -->
	<section class="section org-advantages">
		<div class="container">
			<div class="section-header">
				<h2 class="section-title">مزایای سازمانی سما بار</h2>
				<p class="section-subtitle">چرا شرکت‌ها برای حمل بار سازمانی به سما بار اعتماد می‌کنند</p>
			</div>
			<div class="org-advantages__grid">
				<div class="org-advantage">
					<span class="material-symbols-outlined org-advantage__icon org-advantage__icon--accent">price_check</span>
					<span class="org-advantage__label">شفافیت قیمت‌گذاری</span>
				</div>
				<div class="org-advantage">
					<span class="material-symbols-outlined org-advantage__icon">manage_accounts</span>
					<span class="org-advantage__label">مدیریت حرفه‌ای</span>
				</div>
				<div class="org-advantage">
					<span class="material-symbols-outlined org-advantage__icon">shield</span>
					<span class="org-advantage__label">بیمه کامل بار</span>
				</div>
				<div class="org-advantage">
					<span class="material-symbols-outlined org-advantage__icon">my_location</span>
					<span class="org-advantage__label">رهگیری لحظه‌ای</span>
				</div>
				<div class="org-advantage">
					<span class="material-symbols-outlined org-advantage__icon">verified_user</span>
					<span class="org-advantage__label">رانندگان تایید شده</span>
				</div>
				<div class="org-advantage">
					<span class="material-symbols-outlined org-advantage__icon">corporate_fare</span>
					<span class="org-advantage__label">سیستم سازمانی یکپارچه</span>
				</div>
			</div>
		</div>
	</section>

	<!-- Comparison Table -->
	<section class="section section--tint">
		<div class="container">
			<div class="section-header">
				<h2 class="section-title">مقایسه سرویس‌ها</h2>
				<p class="section-subtitle">انتخاب سرویس مناسب بر اساس نوع کسب‌وکار و اولویت شما</p>
			</div>
			<div class="services-table-wrap">
				<table class="services-table">
					<thead>
						<tr>
							<th scope="col">سرویس</th>
							<th scope="col">کاربرد</th>
							<th scope="col">سرعت</th>
							<th scope="col">نوع مشتری</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="services-table__name" data-label="سرویس">شرکتی</td>
							<td data-label="کاربرد">سازمان‌ها</td>
							<td data-label="سرعت"><span class="services-table__badge services-table__badge--medium">متوسط</span></td>
							<td data-label="نوع مشتری">B2B</td>
						</tr>
						<tr>
							<td class="services-table__name" data-label="سرویس">بین‌شهری</td>
							<td data-label="کاربرد">حمل عمومی</td>
							<td data-label="سرعت"><span class="services-table__badge services-table__badge--medium">متوسط</span></td>
							<td data-label="نوع مشتری">عمومی/شرکتی</td>
						</tr>
						<tr>
							<td class="services-table__name" data-label="سرویس">پروژه‌ای</td>
							<td data-label="کاربرد">پروژه‌های بزرگ</td>
							<td data-label="سرعت"><span class="services-table__badge services-table__badge--planned">برنامه‌ریزی شده</span></td>
							<td data-label="نوع مشتری">سازمانی</td>
						</tr>
						<tr>
							<td class="services-table__name" data-label="سرویس">سریع</td>
							<td data-label="کاربرد">فوری</td>
							<td data-label="سرعت"><span class="services-table__badge services-table__badge--fast">بالا</span></td>
							<td data-label="نوع مشتری">همه</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>

	<!-- Process Steps -->
	<section class="section">
		<div class="container">
			<div class="section-header">
				<h2 class="section-title">فرآیند انجام کار</h2>
				<p class="section-subtitle">از انتخاب سرویس تا تحویل بار — پنج مرحله ساده</p>
			</div>
			<div class="process-steps">
				<div class="process-step">
					<div class="process-step__number">۱</div>
					<span class="process-step__label">انتخاب سرویس</span>
				</div>
				<div class="process-step">
					<div class="process-step__number">۲</div>
					<span class="process-step__label">ثبت درخواست</span>
				</div>
				<div class="process-step">
					<div class="process-step__number">۳</div>
					<span class="process-step__label">قیمت‌گذاری</span>
				</div>
				<div class="process-step">
					<div class="process-step__number">۴</div>
					<span class="process-step__label">انجام حمل</span>
				</div>
				<div class="process-step">
					<div class="process-step__number">۵</div>
					<span class="process-step__label">پیگیری</span>
				</div>
			</div>
		</div>
	</section>


</main>

<?php
get_footer();
