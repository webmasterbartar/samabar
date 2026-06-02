<?php
/**
 * Template Name: درباره ما
 * About page.
 *
 * @package Samabar
 */

get_header();

$order_url = samabar_get_order_url();
$hero_img  = 'https://lh3.googleusercontent.com/aida-public/AB6AXuA6N5XAQws6a4ieO5_2ln0jXRRnvmo6bdyaIItO73gCfiGk8N-hlIZ7HI3BxzRj9RK8TjcV28XJY-t7ugNZhTdm1YyoZJpOfwjN82RQmE8UMEM_ssiZP2aM2EKO5YIGZ18M0EWDWk5nACkX33eGVwwp3bL1SIbwieLhBQNQ_1vfGnf-grD4j6b9NEFOlUd1djE_8mYyQTxEijcQie7JfP-XTHYurz97S6f2FILmnIjAc9Vg3xqNVOVjRr-DlVBH8mrrmMvCERAX3tc';
$story_img = 'https://lh3.googleusercontent.com/aida-public/AB6AXuAbiNRi3DAA6b-ZykhorlNFAD7mgnKJzfY1vpZDHBKNO3Lj060SaknU916fFMWI3IYpAL4PYzcZilOlmgi-BjCj0fYi5XVngxXzHOUtcwIwWO0hPrj8h8jYmwnTpyopRQP72NOtMjRuVwphuNFU9wt2aCsRNEGYYGRJMq_LXguoHKU6TMa3Sptv51OQtOsHEPCd9UdqIxuvH_N51ijlTKzywqJKXk0dnwuZDtkZj3T212_3L1DS_2szEG1owdcjqHtpU4nyykTmhS8';
$fleet_img = 'https://lh3.googleusercontent.com/aida-public/AB6AXuBduLOwVfqtlVFsDhZUUIPHUnkmt3re5T5u7N85wfch6WL8JRIwWycq6c85EBR0MUXW2tlewoWDCwW5kKZ9g3zUzoHcmIB1Tqg4zk2KifqS6cYZl8lC2vDGQC-PENDoEIC3Zbhkij5D0wUud_N7siyohf_WnwrGgNeK_E8p76TH2xwcQo84ijvsQvr90d3PHwma_QBdHNZp8pZd4U4rgFJwpEQfs5YlSAH9qP5FCGp8RrUP3UUSaOYch0hY2b3XRw2u9BkdyFHwdJg';
$monitor_img = 'https://lh3.googleusercontent.com/aida-public/AB6AXuBNiNos52g4wlAUeWVI7UxX4kDTAbIjRz-ogVL-Bt--cEbJwHZKuJw6KR_maJvjolU4OFKI_ujn3Q0STfpzdorKKcPU5lVqbffrQwSNwnscqO0kXH8VNmUEwczoBtPo-OCF4M4ZHgKAJ_JpXDQGqx-HgD7sPKQaJXU4iDslWkUOcRGjjIgbfhkaO0SvaNMyf3chgNAQZTqy23RxCqzWV4abV-CiAGQJiMhYDHUr_5PgE4heGo-uqPqvO_wt40mM6wO3c5qbnEJAy3c';
?>

<main id="primary" class="site-main site-main--about">
	<section class="about-hero">
		<div class="about-hero__bg" style="background-image: url('<?php echo esc_url( $hero_img ); ?>');" aria-hidden="true"></div>
		<div class="about-hero__overlay" aria-hidden="true"></div>
		<div class="container about-hero__inner">
			<h1 class="about-hero__title text-headline-xl">سما بار؛ راهکار مدرن حمل‌ونقل برای کسب‌وکارها</h1>
			<p class="about-hero__text text-body-lg">ما در سما بار با تکیه بر فناوری‌های نوین، فرایند پیچیده لجستیک را ساده، شفاف و سریع کرده‌ایم تا شما تنها بر توسعه کسب‌وکار خود تمرکز کنید.</p>
		</div>
	</section>

	<section class="about-story">
		<div class="container about-story__grid">
			<div class="about-story__content">
				<h2 class="text-headline-lg">از سنتی تا دیجیتال، مسیر ما برای بهبود لجستیک</h2>
				<p class="text-body-md">لجستیک سنتی همواره با چالش‌هایی چون عدم شفافیت هزینه‌ها، تاخیر در رهگیری و پیچیدگی‌های ارتباطی روبرو بوده است. سما بار با هدف رفع این موانع متولد شد.</p>
				<p class="text-body-md">ماموریت ما، دیجیتالی کردن کامل زنجیره تامین کالا از لحظه ثبت درخواست تا تحویل نهایی است. ما به شفافیت کامل، سرعت عمل و قابلیت اطمینان بالا اعتقاد داریم و این ارزش‌ها را در بستر یک پلتفرم هوشمند به مشتریان خود ارائه می‌دهیم.</p>
			</div>
			<div class="about-story__media">
				<img src="<?php echo esc_url( $story_img ); ?>" alt="<?php esc_attr_e( 'عملیات لجستیک دیجیتال', 'samabar' ); ?>" loading="lazy">
			</div>
		</div>
	</section>

	<section class="about-values">
		<div class="container">
			<div class="section-header">
				<h2 class="section-title">ارزش‌های کلیدی ما</h2>
				<p class="section-subtitle">اصولی که سما بار بر پایه آن‌ها بنا شده است</p>
			</div>
			<div class="about-values__grid">
				<article class="about-value-card">
					<span class="material-symbols-outlined icon">price_check</span>
					<h3 class="text-headline-md">قیمت‌گذاری شفاف</h3>
					<p class="text-body-md">محاسبه دقیق و هوشمند هزینه‌ها بدون هیچ‌گونه هزینه پنهان.</p>
				</article>
				<article class="about-value-card">
					<span class="material-symbols-outlined icon">location_on</span>
					<h3 class="text-headline-md">رهگیری لحظه‌ای</h3>
					<p class="text-body-md">پیگیری دقیق موقعیت بار شما از مبدا تا مقصد به صورت زنده.</p>
				</article>
				<article class="about-value-card">
					<span class="material-symbols-outlined icon">support_agent</span>
					<h3 class="text-headline-md">پشتیبانی حرفه‌ای</h3>
					<p class="text-body-md">تیم پشتیبانی ما <?php echo esc_html( samabar_get_contact_hours() ); ?> در کنار شماست.</p>
				</article>
				<article class="about-value-card">
					<span class="material-symbols-outlined icon">verified</span>
					<h3 class="text-headline-md">رانندگان تایید شده</h3>
					<p class="text-body-md">همکاری با ناوگان مجرب و تایید صلاحیت شده برای امنیت بیشتر.</p>
				</article>
			</div>
		</div>
	</section>

	<section class="about-stats">
		<div class="container about-stats__grid">
			<div class="about-stats__item">
				<strong class="about-stats__value">+۵۰,۰۰۰</strong>
				<span class="about-stats__label">سفارش موفق</span>
			</div>
			<div class="about-stats__item">
				<strong class="about-stats__value">۳۱</strong>
				<span class="about-stats__label">استان فعال</span>
			</div>
			<div class="about-stats__item">
				<strong class="about-stats__value">+۱۰,۰۰۰</strong>
				<span class="about-stats__label">راننده مجرب</span>
			</div>
			<div class="about-stats__item">
				<strong class="about-stats__value">٪۹۸</strong>
				<span class="about-stats__label">رضایت مشتریان</span>
			</div>
		</div>
	</section>

	<section class="about-gallery">
		<div class="container about-gallery__grid">
			<div class="about-gallery__item about-gallery__item--wide">
				<img src="<?php echo esc_url( $fleet_img ); ?>" alt="<?php esc_attr_e( 'ناوگان حمل و نقل', 'samabar' ); ?>" loading="lazy">
			</div>
			<div class="about-gallery__item">
				<img src="<?php echo esc_url( $monitor_img ); ?>" alt="<?php esc_attr_e( 'مرکز پایش عملیات', 'samabar' ); ?>" loading="lazy">
			</div>
		</div>
	</section>

	<section class="about-cta">
		<div class="container about-cta__inner">
			<h2 class="text-headline-lg">آماده ارتقای سیستم حمل‌ونقل خود هستید؟</h2>
			<p class="text-body-md">به شبکه گسترده سما بار بپیوندید و لجستیک کسب‌وکار خود را متحول کنید.</p>
			<a class="btn btn--secondary btn--lg" href="<?php echo esc_url( $order_url ); ?>">همین حالا سفارش خود را ثبت کنید</a>
		</div>
	</section>
</main>

<?php
get_footer();
