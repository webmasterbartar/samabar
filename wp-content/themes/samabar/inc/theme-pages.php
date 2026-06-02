<?php
/**
 * Theme pages registry and URL helpers.
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registered theme pages (slug => config).
 *
 * @return array<string, array{title: string, template: string}>
 */
function samabar_get_theme_pages() {
	return array(
		'khadamat' => array(
			'title'    => 'خدمات',
			'template' => 'page-khadamat.php',
		),
		'mohasebe' => array(
			'title'    => 'محاسبه قیمت',
			'template' => 'page-mohasebe.php',
		),
		'sabt-sefaresh' => array(
			'title'    => 'ثبت سفارش',
			'template' => 'page-sabt-sefaresh.php',
		),
		'peigiry' => array(
			'title'    => 'پیگیری بار',
			'template' => 'page-peigiry.php',
		),
		'panel' => array(
			'title'    => 'داشبورد',
			'template' => 'page-panel.php',
		),
		'tamas' => array(
			'title'    => 'تماس با ما',
			'template' => 'page-tamas.php',
		),
		'darbare' => array(
			'title'    => 'درباره ما',
			'template' => 'page-darbare.php',
		),
		'soalat' => array(
			'title'    => 'سوالات متداول',
			'template' => 'page-soalat.php',
		),
		'blog' => array(
			'title'    => 'وبلاگ',
			'template' => 'page-blog.php',
		),
		'hosh-hay-asasi' => array(
			'title'    => 'حریم خصوصی',
			'template' => 'page-legal.php',
		),
		'ghavanin-moghararat' => array(
			'title'    => 'قوانین و مقررات',
			'template' => 'page-legal.php',
		),
		'naghshe-site' => array(
			'title'    => 'نقشه سایت',
			'template' => 'page-naghshe-site.php',
		),
		'test-qa' => array(
			'title'    => 'راهنمای QA',
			'template' => 'page-test-qa.php',
		),
	);
}

/**
 * Ensure a single theme page exists with the correct template.
 *
 * @param string $slug     Page slug.
 * @param string $title    Page title.
 * @param string $template Template filename.
 * @return int Page ID or 0 on failure.
 */
function samabar_ensure_theme_page( $slug, $title, $template ) {
	$page = get_page_by_path( $slug );

	if ( $page ) {
		if ( get_page_template_slug( $page->ID ) !== $template ) {
			update_post_meta( $page->ID, '_wp_page_template', $template );
		}
		return (int) $page->ID;
	}

	$page_id = wp_insert_post(
		array(
			'post_title'  => $title,
			'post_name'   => $slug,
			'post_status' => 'publish',
			'post_type'   => 'page',
		)
	);

	if ( $page_id && ! is_wp_error( $page_id ) ) {
		update_post_meta( $page_id, '_wp_page_template', $template );
		return (int) $page_id;
	}

	return 0;
}

/**
 * Create or repair all theme pages.
 */
function samabar_ensure_theme_pages() {
	foreach ( samabar_get_theme_pages() as $slug => $config ) {
		samabar_ensure_theme_page( $slug, $config['title'], $config['template'] );
	}
}
add_action( 'init', 'samabar_ensure_theme_pages', 5 );

/**
 * Run setup when theme is activated (local or live server).
 */
function samabar_activate_theme() {
	samabar_ensure_theme_pages();
	if ( function_exists( 'samabar_seed_footer_page_content' ) ) {
		samabar_seed_footer_page_content();
	}
	samabar_seed_blog_content();
	if ( function_exists( 'samabar_seed_test_data' ) ) {
		samabar_seed_test_data();
	}
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'samabar_activate_theme' );

/**
 * Get permalink for a theme page by slug.
 *
 * @param string $slug Page slug.
 * @return string
 */
function samabar_get_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	if ( $page ) {
		return get_permalink( $page );
	}
	return home_url( '/' . $slug . '/' );
}

/**
 * Services page URL.
 *
 * @return string
 */
function samabar_get_services_url() {
	return samabar_get_page_url( 'khadamat' );
}

/**
 * Pricing page URL.
 *
 * @return string
 */
function samabar_get_pricing_url() {
	return samabar_get_page_url( 'mohasebe' );
}

/**
 * Home page section anchor URL.
 *
 * @param string $section_id HTML id without hash.
 * @return string
 */
function samabar_get_home_section_url( $section_id ) {
	return home_url( '/#' . ltrim( $section_id, '#' ) );
}

/**
 * Order registration flow URL.
 *
 * @return string
 */
function samabar_get_order_url() {
	return samabar_get_page_url( 'sabt-sefaresh' );
}

/**
 * Order tracking page URL.
 *
 * @param string $order_number Optional order number query arg.
 * @return string
 */
function samabar_get_tracking_url( $order_number = '' ) {
	$url = samabar_get_page_url( 'peigiry' );
	if ( $order_number ) {
		$url = add_query_arg( 'track', rawurlencode( $order_number ), $url );
	}
	return $url;
}

/**
 * FAQ page URL.
 *
 * @return string
 */
function samabar_get_faq_url() {
	return samabar_get_page_url( 'soalat' );
}

/**
 * Contact page URL.
 *
 * @param string $subject Optional pre-filled subject query arg.
 * @return string
 */
function samabar_get_contact_url( $subject = '' ) {
	$url = samabar_get_page_url( 'tamas' );
	if ( $subject ) {
		$url = add_query_arg( 'subject', rawurlencode( $subject ), $url );
	}
	return $url;
}

/**
 * Complaints form URL (contact page with subject).
 *
 * @return string
 */
function samabar_get_complaints_url() {
	return samabar_get_contact_url( 'ثبت شکایت' );
}

/**
 * Driver partnership inquiry URL.
 *
 * @return string
 */
function samabar_get_driver_partnership_url() {
	return samabar_get_contact_url( 'همکاری با رانندگان' );
}

/**
 * Privacy policy page URL.
 *
 * @return string
 */
function samabar_get_privacy_url() {
	return samabar_get_page_url( 'hosh-hay-asasi' );
}

/**
 * Terms page URL.
 *
 * @return string
 */
function samabar_get_terms_url() {
	return samabar_get_page_url( 'ghavanin-moghararat' );
}

/**
 * HTML sitemap page URL.
 *
 * @return string
 */
function samabar_get_sitemap_url() {
	return samabar_get_page_url( 'naghshe-site' );
}

/**
 * Grouped links for the HTML sitemap page.
 *
 * @return array<int, array{title: string, links: array<int, array{label: string, url: string}>}>
 */
function samabar_get_sitemap_sections() {
	$sections = array(
		array(
			'title' => 'صفحات اصلی',
			'links' => array(
				array(
					'label' => 'صفحه اصلی',
					'url'   => home_url( '/' ),
				),
				array(
					'label' => 'خدمات',
					'url'   => samabar_get_services_url(),
				),
				array(
					'label' => 'استعلام قیمت',
					'url'   => samabar_get_pricing_url(),
				),
				array(
					'label' => 'ثبت سفارش',
					'url'   => samabar_get_order_url(),
				),
				array(
					'label' => 'پیگیری بار',
					'url'   => samabar_get_tracking_url(),
				),
				array(
					'label' => 'داشبورد مشتری',
					'url'   => samabar_get_dashboard_url(),
				),
			),
		),
		array(
			'title' => 'اطلاعات و پشتیبانی',
			'links' => array(
				array(
					'label' => 'درباره ما',
					'url'   => samabar_get_about_url(),
				),
				array(
					'label' => 'وبلاگ',
					'url'   => samabar_get_blog_url(),
				),
				array(
					'label' => 'سوالات متداول',
					'url'   => samabar_get_faq_url(),
				),
				array(
					'label' => 'تماس با ما',
					'url'   => samabar_get_contact_url(),
				),
				array(
					'label' => 'ثبت شکایات',
					'url'   => samabar_get_complaints_url(),
				),
				array(
					'label' => 'همکاری با رانندگان',
					'url'   => samabar_get_driver_partnership_url(),
				),
			),
		),
		array(
			'title' => 'قوانین',
			'links' => array(
				array(
					'label' => 'حریم خصوصی',
					'url'   => samabar_get_privacy_url(),
				),
				array(
					'label' => 'قوانین و مقررات',
					'url'   => samabar_get_terms_url(),
				),
				array(
					'label' => 'نقشه سایت',
					'url'   => samabar_get_sitemap_url(),
				),
			),
		),
	);

	$posts = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 20,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	if ( $posts ) {
		$post_links = array();
		foreach ( $posts as $post ) {
			$post_links[] = array(
				'label' => get_the_title( $post ),
				'url'   => get_permalink( $post ),
			);
		}
		$sections[] = array(
			'title' => 'آخرین مطالب وبلاگ',
			'links' => $post_links,
		);
	}

	return apply_filters( 'samabar_sitemap_sections', $sections );
}

/**
 * About page URL.
 *
 * @return string
 */
function samabar_get_about_url() {
	return samabar_get_page_url( 'darbare' );
}

/**
 * Blog page URL.
 *
 * @return string
 */
function samabar_get_blog_url() {
	return samabar_get_page_url( 'blog' );
}

/**
 * Customer dashboard URL.
 *
 * @return string
 */
function samabar_get_dashboard_url() {
	return samabar_get_page_url( 'panel' );
}
