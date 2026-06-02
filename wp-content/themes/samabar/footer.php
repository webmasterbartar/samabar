<?php
/**
 * Site footer.
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$home_url              = home_url( '/' );
$services_url          = samabar_get_services_url();
$pricing_url           = samabar_get_pricing_url();
$order_url             = samabar_get_order_url();
$tracking_url          = samabar_get_tracking_url();
$dashboard_url         = samabar_get_dashboard_url();
$faq_url               = samabar_get_faq_url();
$about_url             = samabar_get_about_url();
$contact_url           = samabar_get_contact_url();
$blog_url              = samabar_get_blog_url();
$complaints_url        = samabar_get_complaints_url();
$driver_url            = samabar_get_driver_partnership_url();
$privacy_url           = samabar_get_privacy_url();
$terms_url             = samabar_get_terms_url();
$sitemap_url           = samabar_get_sitemap_url();
$social_links          = samabar_get_social_links();
$site_credit           = samabar_get_site_credit();
?>
<footer class="site-footer">
	<div class="site-footer__cta">
		<div class="container site-footer__cta-inner">
			<div>
				<h2 class="site-footer__cta-title">نیاز به راهنمایی دارید؟</h2>
				<p class="site-footer__cta-text">کارشناسان ما <?php echo esc_html( samabar_get_contact_hours() ); ?> آماده پاسخگویی هستند</p>
			</div>
			<div class="site-footer__cta-actions">
				<a class="site-footer__cta-btn" href="<?php echo esc_url( samabar_get_contact_phone_url() ); ?>">
					<span class="material-symbols-outlined icon icon--sm">call</span>
					تماس با پشتیبانی
				</a>
				<a class="site-footer__cta-btn site-footer__cta-btn--outline" href="<?php echo esc_url( $order_url ); ?>">
					ثبت سفارش آنلاین
				</a>
			</div>
		</div>
	</div>

	<div class="site-footer__main">
		<div class="container">
			<div class="site-footer__grid">
				<div class="site-footer__brand">
					<a class="site-footer__brand-link" href="<?php echo esc_url( $home_url ); ?>">
						<img alt="<?php esc_attr_e( 'لوگوی سما بار', 'samabar' ); ?>" class="site-footer__logo" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCFVxPFKpxa-oit_M8ORyPPLhlwbvOSbAPkUYGCoPPD-ATgcVle1DtnAcu5VgdrMd_kHqTY1sTT6lyQuXbhhWWBF750hLz_ZCtJ0YR31g27ACCh-ao3wlSJQCc0wwDLU767_RJLOwfPhNWxufH-546ZlLJzSy_odif-tSiRnUCyWkDWyROaB9UfoKrdAJXHp6AF4xjjVZ-tjY1lXWE--INtdq_fuBILSSepRY8AdPJkkw4IwneAB9lIbtWyMRRi6qdTlsJXKjGdzXI">
						<span class="site-footer__brand-name">سما بار</span>
					</a>
					<p class="site-footer__description">
						ارائه‌دهنده راهکارهای هوشمند حمل و نقل جاده‌ای در سراسر کشور. ثبت سفارش آنلاین، پیگیری لحظه‌ای و بیمه بار.
					</p>
					<div class="site-footer__social">
						<?php foreach ( $social_links as $social ) : ?>
							<a
								class="site-footer__social-link"
								href="<?php echo esc_url( $social['url'] ); ?>"
								aria-label="<?php echo esc_attr( $social['label'] ); ?>"
								<?php echo ! empty( $social['external'] ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
							>
								<span class="material-symbols-outlined icon"><?php echo esc_html( $social['icon'] ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
					<div class="site-footer__trust">
						<span class="site-footer__trust-item">
							<span class="material-symbols-outlined icon">verified</span>
							مجوز رسمی
						</span>
						<span class="site-footer__trust-item">
							<span class="material-symbols-outlined icon">shield</span>
							بیمه بار
						</span>
					</div>
				</div>

				<details class="site-footer__column site-footer__accordion">
					<summary class="site-footer__accordion-summary">
						<span class="site-footer__column-title">دسترسی سریع</span>
						<span class="material-symbols-outlined icon site-footer__accordion-icon" aria-hidden="true">expand_more</span>
					</summary>
					<nav class="site-footer__links site-footer__links--grid" aria-label="<?php esc_attr_e( 'دسترسی سریع', 'samabar' ); ?>">
						<a class="site-footer__link" href="<?php echo esc_url( $home_url ); ?>"><span class="material-symbols-outlined icon">chevron_left</span>صفحه اصلی</a>
						<a class="site-footer__link" href="<?php echo esc_url( $services_url ); ?>"><span class="material-symbols-outlined icon">chevron_left</span>خدمات</a>
						<a class="site-footer__link" href="<?php echo esc_url( $pricing_url ); ?>"><span class="material-symbols-outlined icon">chevron_left</span>استعلام قیمت</a>
						<a class="site-footer__link" href="<?php echo esc_url( $order_url ); ?>"><span class="material-symbols-outlined icon">chevron_left</span>ثبت سفارش</a>
						<a class="site-footer__link" href="<?php echo esc_url( $tracking_url ); ?>"><span class="material-symbols-outlined icon">chevron_left</span>پیگیری بار</a>
						<a class="site-footer__link" href="<?php echo esc_url( $dashboard_url ); ?>"><span class="material-symbols-outlined icon">chevron_left</span>داشبورد مشتری</a>
						<a class="site-footer__link" href="<?php echo esc_url( $faq_url ); ?>"><span class="material-symbols-outlined icon">chevron_left</span>سوالات متداول</a>
						<a class="site-footer__link" href="<?php echo esc_url( $about_url ); ?>"><span class="material-symbols-outlined icon">chevron_left</span>درباره ما</a>
						<a class="site-footer__link" href="<?php echo esc_url( $blog_url ); ?>"><span class="material-symbols-outlined icon">chevron_left</span>وبلاگ</a>
						<a class="site-footer__link" href="<?php echo esc_url( $contact_url ); ?>"><span class="material-symbols-outlined icon">chevron_left</span>تماس با ما</a>
					</nav>
				</details>

				<details class="site-footer__column site-footer__accordion">
					<summary class="site-footer__accordion-summary">
						<span class="site-footer__column-title">خدمات و پشتیبانی</span>
						<span class="material-symbols-outlined icon site-footer__accordion-icon" aria-hidden="true">expand_more</span>
					</summary>
					<nav class="site-footer__links" aria-label="<?php esc_attr_e( 'خدمات و پشتیبانی', 'samabar' ); ?>">
						<a class="site-footer__link" href="<?php echo esc_url( $services_url ); ?>"><span class="material-symbols-outlined icon">chevron_left</span>حمل درون شهری</a>
						<a class="site-footer__link" href="<?php echo esc_url( $services_url ); ?>"><span class="material-symbols-outlined icon">chevron_left</span>حمل برون شهری</a>
						<a class="site-footer__link" href="<?php echo esc_url( $faq_url ); ?>"><span class="material-symbols-outlined icon">chevron_left</span>سوالات متداول</a>
						<a class="site-footer__link" href="<?php echo esc_url( $complaints_url ); ?>"><span class="material-symbols-outlined icon">chevron_left</span>ثبت شکایات</a>
						<a class="site-footer__link" href="<?php echo esc_url( $driver_url ); ?>"><span class="material-symbols-outlined icon">chevron_left</span>همکاری با رانندگان</a>
					</nav>
				</details>

				<div class="site-footer__column">
					<h3 class="site-footer__column-title">تماس با ما</h3>
					<ul class="site-footer__contact-list">
						<li class="site-footer__contact-item">
							<span class="site-footer__contact-icon"><span class="material-symbols-outlined icon">location_on</span></span>
							<div class="site-footer__contact-body">
								<span class="site-footer__contact-label">آدرس</span>
								<span class="site-footer__contact-value"><?php echo esc_html( samabar_get_contact_address() ); ?></span>
							</div>
						</li>
						<li class="site-footer__contact-item">
							<span class="site-footer__contact-icon"><span class="material-symbols-outlined icon">call</span></span>
							<div class="site-footer__contact-body">
								<span class="site-footer__contact-label">تلفن</span>
								<a class="site-footer__contact-value" href="<?php echo esc_url( samabar_get_contact_phone_url() ); ?>" dir="ltr"><?php echo esc_html( samabar_get_contact_phone_display() ); ?></a>
							</div>
						</li>
						<li class="site-footer__contact-item">
							<span class="site-footer__contact-icon"><span class="material-symbols-outlined icon">mail</span></span>
							<div class="site-footer__contact-body">
								<span class="site-footer__contact-label">ایمیل</span>
								<a class="site-footer__contact-value" href="mailto:<?php echo esc_attr( samabar_get_contact_email() ); ?>" dir="ltr"><?php echo esc_html( samabar_get_contact_email() ); ?></a>
							</div>
						</li>
						<li class="site-footer__contact-item">
							<span class="site-footer__contact-icon"><span class="material-symbols-outlined icon">schedule</span></span>
							<div class="site-footer__contact-body">
								<span class="site-footer__contact-label">ساعت کاری</span>
								<span class="site-footer__contact-value"><?php echo esc_html( samabar_get_contact_hours() ); ?></span>
							</div>
						</li>
					</ul>
				</div>
			</div>

			<div class="site-footer__newsletter">
				<div class="site-footer__newsletter-inner">
					<div class="site-footer__newsletter-text">
						<h3 class="site-footer__newsletter-title">عضویت در خبرنامه</h3>
						<p class="site-footer__newsletter-desc">از تخفیف‌ها، اخبار و راهنماهای حمل بار باخبر شوید</p>
					</div>
					<form class="site-footer__newsletter-form" id="footer-newsletter-form" action="#" method="post" novalidate>
						<input class="site-footer__newsletter-input" type="email" id="footer-newsletter-email" name="email" placeholder="ایمیل خود را وارد کنید" required autocomplete="email">
						<button class="site-footer__newsletter-btn" type="submit">
							<span class="material-symbols-outlined icon icon--sm">send</span>
							عضویت
						</button>
						<p class="site-footer__newsletter-notice" id="footer-newsletter-notice" hidden></p>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="site-footer__bottom">
		<div class="container site-footer__bottom-inner">
			<p class="site-footer__copyright">© <?php echo esc_html( gmdate( 'Y' ) ); ?> سما بار — <?php esc_html_e( 'تمامی حقوق محفوظ است.', 'samabar' ); ?></p>
			<nav class="site-footer__legal" aria-label="<?php esc_attr_e( 'لینک‌های قانونی', 'samabar' ); ?>">
				<a class="site-footer__legal-link" href="<?php echo esc_url( $privacy_url ); ?>">حریم خصوصی</a>
				<a class="site-footer__legal-link" href="<?php echo esc_url( $terms_url ); ?>">قوانین و مقررات</a>
				<a class="site-footer__legal-link" href="<?php echo esc_url( $sitemap_url ); ?>">نقشه سایت</a>
			</nav>
		</div>
	</div>

	<div class="site-footer__credit">
		<div class="container site-footer__credit-inner">
			<p class="site-footer__credit-text">
				<span class="material-symbols-outlined icon site-footer__credit-icon" aria-hidden="true">code</span>
				<?php echo esc_html( $site_credit['prefix'] ); ?>
				<?php if ( ! empty( $site_credit['url'] ) ) : ?>
					<a class="site-footer__credit-link" href="<?php echo esc_url( $site_credit['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $site_credit['name'] ); ?></a>
				<?php else : ?>
					<span class="site-footer__credit-name"><?php echo esc_html( $site_credit['name'] ); ?></span>
				<?php endif; ?>
			</p>
		</div>
	</div>
</footer>

<button type="button" class="site-footer__back-top" id="back-to-top" aria-label="<?php esc_attr_e( 'بازگشت به بالا', 'samabar' ); ?>">
	<span class="material-symbols-outlined icon">keyboard_arrow_up</span>
</button>

<?php wp_footer(); ?>
</body>
</html>
