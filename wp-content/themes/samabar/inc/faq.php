<?php
/**
 * FAQ content for the FAQ page.
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * FAQ category definitions.
 *
 * @return array<string, array{label: string, icon: string, description: string}>
 */
function samabar_get_faq_categories() {
	return array(
		'order'    => array(
			'label'       => __( 'سفارش و ثبت', 'samabar' ),
			'icon'        => 'local_shipping',
			'description' => __( 'مراحل و قوانین مربوط به ثبت سفارش حمل بار در سیستم یکپارچه سما بار.', 'samabar' ),
		),
		'pricing'  => array(
			'label'       => __( 'قیمت‌ها', 'samabar' ),
			'icon'        => 'payments',
			'description' => __( 'نحوه محاسبه هزینه، پرداخت و فاکتور رسمی سفارش‌ها.', 'samabar' ),
		),
		'insurance' => array(
			'label'       => __( 'بیمه و امنیت', 'samabar' ),
			'icon'        => 'verified_user',
			'description' => __( 'پوشش بیمه، مسئولیت بار و استانداردهای ایمنی حمل.', 'samabar' ),
		),
		'tracking' => array(
			'label'       => __( 'پیگیری بار', 'samabar' ),
			'icon'        => 'share_location',
			'description' => __( 'رهگیری لحظه‌ای، وضعیت سفارش و اطلاع‌رسانی تحویل.', 'samabar' ),
		),
		'drivers'  => array(
			'label'       => __( 'رانندگان', 'samabar' ),
			'icon'        => 'badge',
			'description' => __( 'همکاری با رانندگان، صلاحیت‌ها و فرایند تخصیص ناوگان.', 'samabar' ),
		),
	);
}

/**
 * FAQ items grouped by category slug.
 *
 * @return array<int, array{category: string, question: string, answer: string}>
 */
function samabar_get_faq_items() {
	return array(
		array(
			'category' => 'order',
			'question' => __( 'چگونه می‌توانم یک سفارش حمل بار ثبت کنم؟', 'samabar' ),
			'answer'   => __( 'از صفحه «ثبت سفارش» وارد فرایند سه‌مرحله‌ای شوید: مبدا و مقصد، مشخصات بار و انتخاب سرویس. پس از تایید، شماره پیگیری SB-xxxxx برای شما صادر می‌شود و می‌توانید وضعیت را از داشبورد یا صفحه پیگیری ببینید.', 'samabar' ),
		),
		array(
			'category' => 'order',
			'question' => __( 'آیا امکان لغو سفارش پس از ثبت نهایی وجود دارد؟', 'samabar' ),
			'answer'   => __( 'بله، تا قبل از تخصیص راننده و بارگیری می‌توانید با پشتیبانی تماس بگیرید. پس از بارگیری، لغو مشروط به شرایط قرارداد و هزینه‌های انجام‌شده است.', 'samabar' ),
		),
		array(
			'category' => 'order',
			'question' => __( 'چه نوع بارهایی توسط سما بار حمل می‌شوند؟', 'samabar' ),
			'answer'   => __( 'محموله‌های عمومی، صنعتی B2B، شکستنی و حساس/یخچالی را پوشش می‌دهیم. بارهای ممنوعه یا نیازمند مجوز ویژه باید پیش از ثبت با کارشناس هماهنگ شوند.', 'samabar' ),
		),
		array(
			'category' => 'order',
			'question' => __( 'محدودیت وزنی یا حجمی برای بارها چگونه محاسبه می‌شود؟', 'samabar' ),
			'answer'   => __( 'هزینه بر اساس وزن اعلامی، ابعاد و نوع سرویس محاسبه می‌شود. در صورت اختلاف وزن یا حجم در محل بارگیری، مبلغ نهایی پس از توافق با مشتری به‌روزرسانی می‌شود.', 'samabar' ),
		),
		array(
			'category' => 'pricing',
			'question' => __( 'هزینه حمل بار چگونه محاسبه می‌شود؟', 'samabar' ),
			'answer'   => __( 'بر اساس مسافت، نوع سرویس (سازمانی، اکسپرس، بین‌شهری عادی)، وزن و نوع بار. در صفحه محاسبه قیمت می‌توانید برآورد اولیه دریافت کنید.', 'samabar' ),
		),
		array(
			'category' => 'pricing',
			'question' => __( 'آیا قیمت اعلام‌شده نهایی است؟', 'samabar' ),
			'answer'   => __( 'قیمت ثبت‌شده در مرحله نهایی سفارش، مبنای قرارداد است. تغییر فقط در صورت تغییر مشخصات بار یا مسیر با هماهنگی شما انجام می‌شود.', 'samabar' ),
		),
		array(
			'category' => 'insurance',
			'question' => __( 'آیا بارهای ارسالی بیمه می‌شوند؟', 'samabar' ),
			'answer'   => __( 'بله، تمامی بارها تا سقف پایه بیمه هستند. برای محموله‌های با ارزش بالا امکان افزایش سقف بیمه وجود دارد.', 'samabar' ),
		),
		array(
			'category' => 'insurance',
			'question' => __( 'در صورت آسیب به بار چه اقدامی انجام می‌شود؟', 'samabar' ),
			'answer'   => __( 'گزارش فوری به پشتیبانی و ثبت در سیستم پیگیری انجام می‌شود. پرونده بیمه طبق مستندات و قرارداد پیگیری خواهد شد.', 'samabar' ),
		),
		array(
			'category' => 'tracking',
			'question' => __( 'چگونه وضعیت بار را پیگیری کنم؟', 'samabar' ),
			'answer'   => __( 'با شماره سفارش SB-xxxxx در صفحه «پیگیری بار» یا از داشبورد مشتری (با همان شماره موبایل ثبت سفارش) وضعیت لحظه‌ای را ببینید.', 'samabar' ),
		),
		array(
			'category' => 'tracking',
			'question' => __( 'چقدر زمان می‌برد تا راننده به مبدا برسد؟', 'samabar' ),
			'answer'   => __( 'بسته به شهر، زمان ثبت و نوع سرویس، معمولاً بین ۳۰ تا ۹۰ دقیقه تا حضور راننده در محل زمان می‌برد.', 'samabar' ),
		),
		array(
			'category' => 'drivers',
			'question' => __( 'رانندگان سما بار چگونه انتخاب می‌شوند؟', 'samabar' ),
			'answer'   => __( 'تمامی رانندگان فرایند احراز هویت، بررسی مدارک و صلاحیت فنی را طی کرده‌اند و بر اساس نوع بار و مسیر تخصیص داده می‌شوند.', 'samabar' ),
		),
		array(
			'category' => 'drivers',
			'question' => __( 'چگونه می‌توانم به عنوان راننده همکاری کنم؟', 'samabar' ),
			'answer'   => __( 'از طریق بخش «همکاری با ما» یا تماس با پشتیبانی، مدارک خود را ارسال کنید. پس از بررسی، به شبکه ناوگان اضافه می‌شوید.', 'samabar' ),
		),
	);
}
