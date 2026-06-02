<?php
/**
 * Footer-related pages: legal content seeding.
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default post content for legal pages.
 *
 * @return array<string, string>
 */
function samabar_get_footer_page_default_content() {
	return array(
		'hosh-hay-asasi'      => '<h2>جمع‌آوری اطلاعات</h2>
<p>سما بار اطلاعاتی مانند نام، شماره تماس، آدرس مبدا و مقصد و جزئیات بار را صرفاً برای ارائه خدمات حمل‌ونقل، پشتیبانی و پیگیری سفارش دریافت می‌کند.</p>
<h2>استفاده از اطلاعات</h2>
<p>اطلاعات شما برای ثبت و مدیریت سفارش، ارتباط با پشتیبانی، ارسال اعلان‌های مرتبط با وضعیت بار و بهبود کیفیت خدمات استفاده می‌شود. ما اطلاعات شخصی شما را به اشخاص ثالث غیرمرتبط واگذار نمی‌کنیم.</p>
<h2>امنیت داده‌ها</h2>
<p>داده‌های کاربران با روش‌های فنی و سازمانی مناسب محافظت می‌شوند. دسترسی به اطلاعات سفارش‌ها محدود به پرسنل مجاز است.</p>
<h2>کوکی‌ها و ذخیره‌سازی محلی</h2>
<p>برای بهبود تجربه کاربری (مانند ورود به داشبورد مشتری) ممکن است از کوکی یا localStorage استفاده شود. می‌توانید این داده‌ها را از مرورگر خود پاک کنید.</p>
<h2>حقوق شما</h2>
<p>در صورت نیاز به اصلاح، حذف یا استعلام درباره اطلاعات شخصی خود، از طریق <a href="' . esc_url( samabar_get_contact_url() ) . '">صفحه تماس</a> با ما در ارتباط باشید.</p>',
		'ghavanin-moghararat' => '<h2>شرایط استفاده از خدمات</h2>
<p>استفاده از وب‌سایت و خدمات سما بار به منزله پذیرش قوانین زیر است. لطفاً قبل از ثبت سفارش، شرایط را با دقت مطالعه کنید.</p>
<h2>ثبت سفارش و پرداخت</h2>
<p>اطلاعات ثبت‌شده در فرم سفارش باید دقیق و کامل باشد. قیمت نهایی پس از بررسی جزئیات بار و مسیر اعلام می‌شود. پرداخت طبق روش‌های اعلام‌شده در زمان ثبت سفارش انجام می‌گیرد.</p>
<h2>مسئولیت بار و بیمه</h2>
<p>بار مشتری باید مطابق قوانین حمل جاده‌ای بسته‌بندی و آماده شود. بیمه بار طبق پوشش انتخابی یا پیش‌فرض خدمات اعمال می‌شود. موارد ممنوعه حمل طبق مقررات جمهوری اسلامی ایران پذیرفته نمی‌شوند.</p>
<h2>لغو و تغییر سفارش</h2>
<p>درخواست لغو یا تغییر سفارش باید در اسرع وقت به پشتیبانی اطلاع داده شود. هزینه‌های ناشی از لغو دیرهنگام یا تغییر مسیر طبق شرایط همان سفارش محاسبه می‌شود.</p>
<h2>محدودیت مسئولیت</h2>
<p>سما بار در قبال تاخیرهای ناشی از شرایط غیرقابل پیش‌بینی (آب‌وهوا، محدودیت‌های راهی و...) در حدود مقررات و قرارداد خدمات پاسخگو است.</p>
<h2>تماس و شکایات</h2>
<p>برای ثبت شکایت یا دریافت راهنمایی بیشتر از <a href="' . esc_url( samabar_get_complaints_url() ) . '">فرم ثبت شکایات</a> یا <a href="' . esc_url( samabar_get_contact_url() ) . '">تماس با ما</a> استفاده کنید.</p>',
	);
}

/**
 * Seed legal page content once per page.
 */
function samabar_seed_footer_page_content() {
	$defaults = samabar_get_footer_page_default_content();

	foreach ( $defaults as $slug => $content ) {
		$page = get_page_by_path( $slug );
		if ( ! $page ) {
			continue;
		}

		if ( get_post_meta( $page->ID, '_samabar_footer_seeded', true ) ) {
			continue;
		}

		if ( trim( wp_strip_all_tags( (string) $page->post_content ) ) !== '' ) {
			update_post_meta( $page->ID, '_samabar_footer_seeded', '1' );
			continue;
		}

		wp_update_post(
			array(
				'ID'           => $page->ID,
				'post_content' => $content,
			)
		);
		update_post_meta( $page->ID, '_samabar_footer_seeded', '1' );
	}
}
add_action( 'init', 'samabar_seed_footer_page_content', 20 );
