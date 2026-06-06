<?php
/**
 * Front page content helpers.
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build a theme asset URL (handles spaces in filenames).
 *
 * @param string $relative_path Path relative to theme root.
 * @return string
 */
function samabar_get_theme_asset_url( $relative_path ) {
	$relative_path = ltrim( str_replace( '\\', '/', $relative_path ), '/' );
	$parts         = explode( '/', $relative_path );
	$encoded       = implode( '/', array_map( 'rawurlencode', $parts ) );

	return get_template_directory_uri() . '/' . $encoded;
}

/**
 * Featured project images for the home page gallery.
 *
 * @return array<int, array{file: string, title: string, caption: string}>
 */
function samabar_get_home_project_gallery() {
	return apply_filters(
		'samabar_home_project_gallery',
		array(
			array(
				'file'    => 'aks/11.webp',
				'title'   => 'حمل بار سنگین',
				'caption' => 'جابجایی تجهیزات صنعتی با ناوگان تخصصی',
			),
			array(
				'file'    => 'aks/ChatGPT Image Jun 3, 2026, 10_35_13 AM 1.webp',
				'title'   => 'لجستیک بین‌شهری',
				'caption' => 'ارسال بار به سراسر کشور با رهگیری آنلاین',
			),
			array(
				'file'    => 'aks/ChatGPT Image Jun 3, 2026, 10_37_22 AM 1.webp',
				'title'   => 'حمل درون‌شهری',
				'caption' => 'تحویل سریع بار درون شهر با ناوگان سبک',
			),
			array(
				'file'    => 'aks/ChatGPT Image Jun 3, 2026, 10_41_01 AM 1.webp',
				'title'   => 'باربری سازمانی',
				'caption' => 'همکاری مستمر با کسب‌وکارها و زنجیره تأمین',
			),
		)
	);
}

/**
 * Highlight bullets for the home about section.
 *
 * @return array<int, array{icon: string, label: string}>
 */
function samabar_get_home_about_highlights() {
	return array(
		array(
			'icon'  => 'local_shipping',
			'label' => 'ناوگان گسترده درون‌شهری و برون‌شهری',
		),
		array(
			'icon'  => 'verified',
			'label' => 'رانندگان تأییدشده و بیمه معتبر بار',
		),
		array(
			'icon'  => 'monitoring',
			'label' => 'ثبت سفارش و پیگیری لحظه‌ای آنلاین',
		),
		array(
			'icon'  => 'support_agent',
			'label' => 'پشتیبانی تخصصی در ساعات کاری',
		),
	);
}
