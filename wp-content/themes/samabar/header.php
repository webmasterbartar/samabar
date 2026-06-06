<?php
/**
 * Site header.
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$home_url     = home_url( '/' );
$services_url = samabar_get_services_url();
$pricing_url  = samabar_get_pricing_url();
$order_url     = samabar_get_order_url();
$tracking_url  = samabar_get_tracking_url();
$dashboard_url = samabar_get_dashboard_url();
$faq_url       = samabar_get_faq_url();
$about_url     = samabar_get_about_url();
$contact_url   = samabar_get_contact_url();
$blog_url      = samabar_get_blog_url();
$is_home       = is_front_page();
$is_services   = is_page_template( 'page-khadamat.php' ) || is_page( 'khadamat' );
$is_pricing    = is_page_template( 'page-mohasebe.php' ) || is_page( 'mohasebe' );
$is_tracking   = is_page_template( 'page-peigiry.php' ) || is_page( 'peigiry' );
$is_about      = is_page_template( 'page-darbare.php' ) || is_page( 'darbare' );
$is_contact    = is_page_template( 'page-tamas.php' ) || is_page( 'tamas' );
$is_faq        = is_page_template( 'page-soalat.php' ) || is_page( 'soalat' );
$is_blog       = is_page_template( 'page-blog.php' ) || is_page( 'blog' ) || is_singular( 'post' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" id="site-header">
	<div class="site-header__topbar">
		<div class="container site-header__topbar-inner">
			<div class="site-header__topbar-info">
				<a class="site-header__topbar-link" href="<?php echo esc_url( samabar_get_contact_phone_url() ); ?>">
					<span class="material-symbols-outlined icon icon--sm">call</span>
					<?php echo esc_html( samabar_get_contact_phone_display() ); ?>
				</a>
				<span class="site-header__topbar-divider" aria-hidden="true"></span>
				<span class="site-header__topbar-text">
					<span class="material-symbols-outlined icon icon--sm">schedule</span>
					<?php echo esc_html( samabar_get_contact_hours() ); ?>
				</span>
			</div>
			<div class="site-header__topbar-actions">
				<a class="site-header__topbar-link" href="<?php echo esc_url( $order_url ); ?>">راهنمای ثبت سفارش</a>
			</div>
		</div>
	</div>

	<div class="site-header__main">
		<div class="container site-header__inner">
			<a class="site-header__brand" href="<?php echo esc_url( $home_url ); ?>">
				<span class="site-header__brand-text">
					<span class="site-header__title">سما بار</span>
					<span class="site-header__tagline">حمل بار هوشمند</span>
				</span>
			</a>

			<nav class="site-header__nav" aria-label="<?php esc_attr_e( 'منوی اصلی', 'samabar' ); ?>">
				<a class="site-header__nav-link<?php echo $is_home ? ' site-header__nav-link--active' : ''; ?>" href="<?php echo esc_url( $home_url ); ?>">صفحه اصلی</a>
				<a class="site-header__nav-link<?php echo $is_services ? ' site-header__nav-link--active' : ''; ?>" href="<?php echo esc_url( $services_url ); ?>">خدمات</a>
				<a class="site-header__nav-link<?php echo $is_pricing ? ' site-header__nav-link--active' : ''; ?>" href="<?php echo esc_url( $pricing_url ); ?>">قیمت‌ها</a>
				<a class="site-header__nav-link<?php echo $is_tracking ? ' site-header__nav-link--active' : ''; ?>" href="<?php echo esc_url( $tracking_url ); ?>">پیگیری بار</a>
				<a class="site-header__nav-link<?php echo $is_about ? ' site-header__nav-link--active' : ''; ?>" href="<?php echo esc_url( $about_url ); ?>">درباره ما</a>
				<a class="site-header__nav-link<?php echo $is_blog ? ' site-header__nav-link--active' : ''; ?>" href="<?php echo esc_url( $blog_url ); ?>">وبلاگ</a>
				<a class="site-header__nav-link<?php echo $is_contact ? ' site-header__nav-link--active' : ''; ?>" href="<?php echo esc_url( $contact_url ); ?>">تماس با ما</a>
			</nav>

			<div class="site-header__actions">
				<a class="site-header__phone" href="<?php echo esc_url( samabar_get_contact_phone_url() ); ?>" aria-label="<?php esc_attr_e( 'تماس با پشتیبانی', 'samabar' ); ?>">
					<span class="material-symbols-outlined icon">call</span>
				</a>
				<a class="btn btn--ghost btn--sm btn--desktop-only" href="<?php echo esc_url( $dashboard_url ); ?>">ورود / داشبورد</a>
				<a class="btn btn--secondary btn--sm" href="<?php echo esc_url( $order_url ); ?>">ثبت سفارش</a>
				<button type="button" class="site-header__menu-toggle" aria-label="<?php esc_attr_e( 'باز کردن منو', 'samabar' ); ?>" aria-expanded="false" aria-controls="mobile-nav">
					<span class="site-header__menu-icon" aria-hidden="true">
						<span></span>
						<span></span>
						<span></span>
					</span>
				</button>
			</div>
		</div>
	</div>

	<div class="site-header__overlay" id="header-overlay" hidden></div>

	<aside class="site-header__mobile" id="mobile-nav" aria-hidden="true" aria-label="<?php esc_attr_e( 'منوی موبایل', 'samabar' ); ?>">
		<div class="site-header__mobile-head">
			<span class="site-header__mobile-title">منو</span>
			<button type="button" class="site-header__mobile-close" aria-label="<?php esc_attr_e( 'بستن منو', 'samabar' ); ?>">
				<span class="material-symbols-outlined icon">close</span>
			</button>
		</div>

		<div class="site-header__mobile-body">
			<nav class="site-header__mobile-nav">
				<a class="site-header__mobile-link<?php echo $is_home ? ' site-header__mobile-link--active' : ''; ?>" href="<?php echo esc_url( $home_url ); ?>">صفحه اصلی</a>
				<a class="site-header__mobile-link<?php echo $is_services ? ' site-header__mobile-link--active' : ''; ?>" href="<?php echo esc_url( $services_url ); ?>">خدمات</a>
				<a class="site-header__mobile-link<?php echo $is_pricing ? ' site-header__mobile-link--active' : ''; ?>" href="<?php echo esc_url( $pricing_url ); ?>">قیمت‌ها</a>
				<a class="site-header__mobile-link<?php echo $is_tracking ? ' site-header__mobile-link--active' : ''; ?>" href="<?php echo esc_url( $tracking_url ); ?>">پیگیری بار</a>
				<a class="site-header__mobile-link<?php echo $is_about ? ' site-header__mobile-link--active' : ''; ?>" href="<?php echo esc_url( $about_url ); ?>">درباره ما</a>
				<a class="site-header__mobile-link<?php echo $is_blog ? ' site-header__mobile-link--active' : ''; ?>" href="<?php echo esc_url( $blog_url ); ?>">وبلاگ</a>
				<a class="site-header__mobile-link<?php echo $is_contact ? ' site-header__mobile-link--active' : ''; ?>" href="<?php echo esc_url( $contact_url ); ?>">تماس با ما</a>
				<a class="site-header__mobile-link<?php echo $is_faq ? ' site-header__mobile-link--active' : ''; ?>" href="<?php echo esc_url( $faq_url ); ?>">سوالات متداول</a>
			</nav>

			<div class="site-header__mobile-contact">
				<a class="site-header__mobile-contact-item" href="<?php echo esc_url( samabar_get_contact_phone_url() ); ?>">
					<span class="material-symbols-outlined icon">call</span>
					<?php echo esc_html( samabar_get_contact_phone_display() ); ?>
				</a>
			</div>
		</div>

		<div class="site-header__mobile-foot">
			<a class="btn btn--ghost btn--block-mobile" href="<?php echo esc_url( $dashboard_url ); ?>">ورود / داشبورد</a>
			<a class="btn btn--secondary btn--block-mobile" href="<?php echo esc_url( $order_url ); ?>">ثبت سفارش</a>
		</div>
	</aside>
</header>
