<?php
/**
 * Site-wide contact information.
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Central office address.
 *
 * @return string
 */
function samabar_get_contact_address() {
	return 'بندرعباس، قلعه شاهی، میدان شهدا، خیابان صیادان';
}

/**
 * Phone digits for tel: links.
 *
 * @return string
 */
function samabar_get_contact_phone_raw() {
	return '02146047450';
}

/**
 * Phone formatted for display.
 *
 * @return string
 */
function samabar_get_contact_phone_display() {
	return '۰۲۱-۴۶۰۴۷۴۵۰';
}

/**
 * Phone tel: URL.
 *
 * @return string
 */
function samabar_get_contact_phone_url() {
	return 'tel:' . samabar_get_contact_phone_raw();
}

/**
 * Contact email address.
 *
 * @return string
 */
function samabar_get_contact_email() {
	return 'info@samabar.com';
}

/**
 * Working hours label.
 *
 * @return string
 */
function samabar_get_contact_hours() {
	return 'شنبه تا پنجشنبه ۸ صبح الی ۱۶';
}

/**
 * Social profile URLs (empty string = fall back to contact page).
 *
 * @return array<string, string>
 */
function samabar_get_social_urls() {
	$site_url = home_url( '/' );
	$message  = 'سلام، می‌خواهم در مورد خدمات سما بار اطلاعات بگیرم.';

	return apply_filters(
		'samabar_social_urls',
		array(
			'instagram' => '',
			'telegram'  => 'https://t.me/share/url?url=' . rawurlencode( $site_url ) . '&text=' . rawurlencode( 'سما بار — حمل بار هوشمند' ),
			'linkedin'  => '',
			'whatsapp'  => 'https://wa.me/?text=' . rawurlencode( $message ),
		)
	);
}

/**
 * Footer / site social links with labels and icons.
 *
 * @return array<int, array{key: string, label: string, icon: string, url: string, external: bool}>
 */
function samabar_get_social_links() {
	$urls         = samabar_get_social_urls();
	$contact_url  = function_exists( 'samabar_get_contact_url' ) ? samabar_get_contact_url() : home_url( '/tamas/' );
	$definitions  = array(
		array(
			'key'   => 'instagram',
			'label' => 'اینستاگرام',
			'icon'  => 'photo_camera',
		),
		array(
			'key'   => 'telegram',
			'label' => 'تلگرام',
			'icon'  => 'send',
		),
		array(
			'key'   => 'linkedin',
			'label' => 'لینکدین',
			'icon'  => 'work',
		),
		array(
			'key'   => 'whatsapp',
			'label' => 'واتساپ',
			'icon'  => 'chat',
		),
	);
	$links = array();

	foreach ( $definitions as $item ) {
		$url = isset( $urls[ $item['key'] ] ) ? trim( (string) $urls[ $item['key'] ] ) : '';
		if ( '' === $url ) {
			$url = $contact_url;
		}

		$links[] = array(
			'key'      => $item['key'],
			'label'    => $item['label'],
			'icon'     => $item['icon'],
			'url'      => $url,
			'external' => 0 !== strpos( $url, home_url() ) && 0 !== strpos( $url, 'tel:' ) && 0 !== strpos( $url, 'mailto:' ),
		);
	}

	return apply_filters( 'samabar_social_links', $links );
}

/**
 * Quick contact items for floating call modal.
 *
 * @return array<int, array{href: string, icon: string, value: string, label: string, external?: bool}>
 */
function samabar_get_site_quick_contacts() {
	return apply_filters(
		'samabar_site_quick_contacts',
		array(
			array(
				'href'   => samabar_get_contact_phone_url(),
				'icon'   => 'call',
				'value'  => samabar_get_contact_phone_display(),
				'label'  => 'پشتیبانی تلفنی',
			),
			array(
				'href'     => 'mailto:' . samabar_get_contact_email(),
				'icon'     => 'mail',
				'value'    => samabar_get_contact_email(),
				'label'    => 'ایمیل',
				'external' => true,
			),
			array(
				'href'     => samabar_get_contact_url(),
				'icon'     => 'support_agent',
				'value'    => 'فرم تماس',
				'label'    => 'ارسال پیام',
			),
		)
	);
}

/**
 * Site design / development credit shown in footer.
 *
 * @return array{prefix: string, name: string, url: string}
 */
function samabar_get_site_credit() {
	return apply_filters(
		'samabar_site_credit',
		array(
			'prefix' => 'طراحی و توسعه وب‌سایت توسط',
			'name'   => 'آژانس طراحی سایت و سئو وب مستر',
			'url'    => '',
		)
	);
}
