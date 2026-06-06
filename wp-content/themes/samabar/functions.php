<?php
/**
 * Samabar theme functions.
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SAMABAR_VERSION', '0.11.2' );

require get_template_directory() . '/inc/theme-pages.php';
require get_template_directory() . '/inc/jalali.php';
require get_template_directory() . '/inc/orders.php';
require get_template_directory() . '/inc/dashboard.php';
require get_template_directory() . '/inc/faq.php';
require get_template_directory() . '/inc/contact-info.php';
require get_template_directory() . '/inc/contact.php';
require get_template_directory() . '/inc/blog.php';
require get_template_directory() . '/inc/home.php';
require get_template_directory() . '/inc/tariff-import.php';
require get_template_directory() . '/inc/tariff.php';
require get_template_directory() . '/inc/footer-pages.php';
require get_template_directory() . '/inc/test-data.php';

/**
 * Theme setup.
 */
function samabar_setup() {
	load_theme_textdomain( 'samabar', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
}
add_action( 'after_setup_theme', 'samabar_setup' );

/**
 * Force RTL layout for Persian site.
 *
 * @param string $output Language attributes.
 * @return string
 */
function samabar_language_attributes( $output ) {
	return 'dir="rtl" lang="fa"';
}
add_filter( 'language_attributes', 'samabar_language_attributes' );

/**
 * Set document title for theme pages.
 *
 * @param array $title Title parts.
 * @return array
 */
function samabar_document_title( $title ) {
	if ( is_front_page() ) {
		$title['title'] = 'سما بار - حمل بار سریع، امن و هوشمند';
	} elseif ( is_page_template( 'page-khadamat.php' ) || is_page( 'khadamat' ) ) {
		$title['title'] = 'خدمات سما بار - حمل و نقل حرفه‌ای B2B';
	} elseif ( is_page_template( 'page-mohasebe.php' ) || is_page( 'mohasebe' ) ) {
		$title['title'] = 'محاسبه قیمت - سما بار';
	} elseif ( is_page_template( 'page-sabt-sefaresh.php' ) || is_page( 'sabt-sefaresh' ) ) {
		$title['title'] = 'ثبت سفارش - سما بار';
	} elseif ( is_page_template( 'page-peigiry.php' ) || is_page( 'peigiry' ) ) {
		$title['title'] = 'پیگیری بار - سما بار';
	} elseif ( is_page_template( 'page-panel.php' ) || is_page( 'panel' ) ) {
		$title['title'] = 'داشبورد - سما بار';
	} elseif ( is_page_template( 'page-tamas.php' ) || is_page( 'tamas' ) ) {
		$title['title'] = 'تماس با ما - سما بار';
	} elseif ( is_page_template( 'page-darbare.php' ) || is_page( 'darbare' ) ) {
		$title['title'] = 'درباره ما - سما بار';
	} elseif ( is_page_template( 'page-soalat.php' ) || is_page( 'soalat' ) ) {
		$title['title'] = 'سوالات متداول - سما بار';
	} elseif ( is_page_template( 'page-blog.php' ) || is_page( 'blog' ) ) {
		$title['title'] = 'وبلاگ - سما بار';
	} elseif ( is_page_template( 'page-legal.php' ) || is_page( 'hosh-hay-asasi' ) ) {
		$title['title'] = 'حریم خصوصی - سما بار';
	} elseif ( is_page( 'ghavanin-moghararat' ) ) {
		$title['title'] = 'قوانین و مقررات - سما بار';
	} elseif ( is_page_template( 'page-naghshe-site.php' ) || is_page( 'naghshe-site' ) ) {
		$title['title'] = 'نقشه سایت - سما بار';
	} elseif ( is_singular( 'post' ) ) {
		$title['title'] = get_the_title() . ' - وبلاگ سما بار';
	}

	return $title;
}
add_filter( 'document_title_parts', 'samabar_document_title' );

/**
 * Enqueue shared hub route validation script + config.
 *
 * @return void
 */
function samabar_enqueue_route_rules() {
	wp_enqueue_script(
		'samabar-route-rules',
		get_template_directory_uri() . '/assets/js/route-rules.js',
		array(),
		SAMABAR_VERSION,
		true
	);

	wp_localize_script(
		'samabar-route-rules',
		'samabarRouteRules',
		samabar_get_route_rules_config()
	);

	wp_enqueue_style(
		'samabar-route-rules',
		get_template_directory_uri() . '/assets/css/route-rules.css',
		array(),
		SAMABAR_VERSION
	);
}

/**
 * Enqueue theme assets.
 */
function samabar_enqueue_assets() {
	wp_enqueue_style(
		'samabar-style',
		get_stylesheet_uri(),
		array(),
		SAMABAR_VERSION
	);

	wp_enqueue_style(
		'samabar-main',
		get_template_directory_uri() . '/assets/css/main.css',
		array( 'samabar-style' ),
		SAMABAR_VERSION
	);

	wp_enqueue_script(
		'samabar-header',
		get_template_directory_uri() . '/assets/js/header.js',
		array(),
		SAMABAR_VERSION,
		true
	);

	wp_enqueue_script(
		'samabar-footer',
		get_template_directory_uri() . '/assets/js/footer.js',
		array(),
		SAMABAR_VERSION,
		true
	);

	if ( is_front_page() ) {
		samabar_enqueue_route_rules();

		wp_enqueue_script(
			'samabar-hero-form',
			get_template_directory_uri() . '/assets/js/hero-form.js',
			array( 'samabar-route-rules' ),
			SAMABAR_VERSION,
			true
		);

		wp_enqueue_script(
			'samabar-testimonials',
			get_template_directory_uri() . '/assets/js/testimonials.js',
			array(),
			SAMABAR_VERSION,
			true
		);
	}

	if ( is_page_template( 'page-khadamat.php' ) || is_page( 'khadamat' ) ) {
		wp_enqueue_style(
			'samabar-services',
			get_template_directory_uri() . '/assets/css/pages/services.css',
			array( 'samabar-main' ),
			SAMABAR_VERSION
		);
	}

	if ( is_page_template( 'page-mohasebe.php' ) || is_page( 'mohasebe' ) ) {
		wp_enqueue_style(
			'samabar-pricing',
			get_template_directory_uri() . '/assets/css/pages/pricing.css',
			array( 'samabar-main' ),
			SAMABAR_VERSION
		);

		samabar_enqueue_route_rules();

		wp_enqueue_script(
			'samabar-tariff-calc',
			get_template_directory_uri() . '/assets/js/tariff-calc.js',
			array( 'samabar-route-rules' ),
			SAMABAR_VERSION,
			true
		);

		wp_localize_script(
			'samabar-tariff-calc',
			'samabarTariffCalc',
			array(
				'calculateUrl' => rest_url( 'samabar/v1/calculate-freight' ),
				'nonce'        => wp_create_nonce( 'wp_rest' ),
			)
		);

		wp_enqueue_script(
			'samabar-pricing',
			get_template_directory_uri() . '/assets/js/pricing.js',
			array( 'samabar-route-rules', 'samabar-tariff-calc' ),
			SAMABAR_VERSION,
			true
		);
	}

	if ( is_page_template( 'page-sabt-sefaresh.php' ) || is_page( 'sabt-sefaresh' ) ) {
		wp_enqueue_style(
			'samabar-order',
			get_template_directory_uri() . '/assets/css/pages/order.css',
			array( 'samabar-main' ),
			SAMABAR_VERSION
		);

		wp_enqueue_script(
			'samabar-jalali',
			get_template_directory_uri() . '/assets/js/jalali.js',
			array(),
			SAMABAR_VERSION,
			true
		);

		wp_enqueue_script(
			'samabar-persian-datetime',
			get_template_directory_uri() . '/assets/js/persian-datetime.js',
			array( 'samabar-jalali' ),
			SAMABAR_VERSION,
			true
		);

		wp_enqueue_script(
			'samabar-customer-session',
			get_template_directory_uri() . '/assets/js/customer-session.js',
			array(),
			SAMABAR_VERSION,
			true
		);

		samabar_enqueue_route_rules();

		wp_enqueue_script(
			'samabar-tariff-calc',
			get_template_directory_uri() . '/assets/js/tariff-calc.js',
			array( 'samabar-route-rules' ),
			SAMABAR_VERSION,
			true
		);

		wp_enqueue_script(
			'samabar-order',
			get_template_directory_uri() . '/assets/js/order.js',
			array( 'samabar-persian-datetime', 'samabar-customer-session', 'samabar-route-rules', 'samabar-tariff-calc' ),
			SAMABAR_VERSION,
			true
		);

		wp_localize_script(
			'samabar-order',
			'samabarOrder',
			array(
				'baseUrl'         => samabar_get_order_url(),
				'trackingUrl'     => samabar_get_tracking_url(),
				'dashboardUrl'    => samabar_get_dashboard_url(),
				'restUrl'         => rest_url( 'samabar/v1/orders' ),
				'calculateUrl'    => rest_url( 'samabar/v1/calculate-freight' ),
				'availabilityUrl' => rest_url( 'samabar/v1/pickup-availability' ),
				'nonce'           => wp_create_nonce( 'wp_rest' ),
				'serviceLabels'   => samabar_get_service_labels(),
				'cargoLabels'     => samabar_get_cargo_labels(),
			)
		);
	}

	if ( is_page_template( 'page-peigiry.php' ) || is_page( 'peigiry' ) ) {
		wp_enqueue_style(
			'samabar-tracking',
			get_template_directory_uri() . '/assets/css/pages/tracking.css',
			array( 'samabar-main' ),
			SAMABAR_VERSION
		);

		wp_enqueue_script(
			'samabar-tracking',
			get_template_directory_uri() . '/assets/js/tracking.js',
			array(),
			SAMABAR_VERSION,
			true
		);

		wp_localize_script(
			'samabar-tracking',
			'samabarTracking',
			array(
				'trackUrl' => rest_url( 'samabar/v1/track' ),
			)
		);
	}

	if ( is_page_template( 'page-panel.php' ) || is_page( 'panel' ) ) {
		wp_enqueue_style(
			'samabar-dashboard',
			get_template_directory_uri() . '/assets/css/pages/dashboard.css',
			array( 'samabar-main' ),
			SAMABAR_VERSION
		);

		wp_enqueue_script(
			'samabar-customer-session',
			get_template_directory_uri() . '/assets/js/customer-session.js',
			array(),
			SAMABAR_VERSION,
			true
		);

		wp_enqueue_script(
			'samabar-dashboard',
			get_template_directory_uri() . '/assets/js/dashboard.js',
			array( 'samabar-customer-session' ),
			SAMABAR_VERSION,
			true
		);

		wp_localize_script(
			'samabar-dashboard',
			'samabarDashboard',
			array(
				'dashboardUrl' => rest_url( 'samabar/v1/dashboard' ),
			)
		);
	}

	$static_pages = array(
		'page-tamas.php'        => 'tamas',
		'page-darbare.php'      => 'darbare',
		'page-soalat.php'       => 'soalat',
		'page-legal.php'        => 'hosh-hay-asasi',
		'page-naghshe-site.php' => 'naghshe-site',
	);

	foreach ( $static_pages as $template => $slug ) {
		if ( is_page_template( $template ) || is_page( $slug ) || ( 'page-legal.php' === $template && is_page( 'ghavanin-moghararat' ) ) ) {
			wp_enqueue_style(
				'samabar-static-pages',
				get_template_directory_uri() . '/assets/css/pages/static-pages.css',
				array( 'samabar-main' ),
				SAMABAR_VERSION
			);
			break;
		}
	}

	if ( is_page_template( 'page-tamas.php' ) || is_page( 'tamas' ) ) {
		wp_enqueue_script(
			'samabar-contact',
			get_template_directory_uri() . '/assets/js/contact.js',
			array(),
			SAMABAR_VERSION,
			true
		);

		wp_localize_script(
			'samabar-contact',
			'samabarContact',
			array(
				'restUrl' => rest_url( 'samabar/v1/contact' ),
			)
		);
	}

	if ( is_page_template( 'page-soalat.php' ) || is_page( 'soalat' ) ) {
		wp_enqueue_script(
			'samabar-faq',
			get_template_directory_uri() . '/assets/js/faq.js',
			array(),
			SAMABAR_VERSION,
			true
		);
	}

	if ( is_page_template( 'page-test-qa.php' ) || is_page( 'test-qa' ) ) {
		wp_enqueue_style(
			'samabar-qa',
			get_template_directory_uri() . '/assets/css/pages/qa.css',
			array( 'samabar-main' ),
			SAMABAR_VERSION
		);
	}

	if ( is_page_template( 'page-blog.php' ) || is_page( 'blog' ) || is_singular( 'post' ) ) {
		wp_enqueue_style(
			'samabar-blog',
			get_template_directory_uri() . '/assets/css/pages/blog.css',
			array( 'samabar-main' ),
			SAMABAR_VERSION
		);

		wp_enqueue_script(
			'samabar-blog',
			get_template_directory_uri() . '/assets/js/blog.js',
			array(),
			SAMABAR_VERSION,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'samabar_enqueue_assets' );








/**
 * BC:
 * In v2.7.0 the theme removed the `hello_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		wp_body_open();
	}
}

/**
 * Floating call button and contact modal.
 */
function samabar_render_floating_call_button() {
	?>
	<style>
	.floating-call-button {
		position: fixed;
		left: 0;
		top: 40%;
		transform: translateY(-50%);
		background-color: var(--color-secondary);
		color: var(--color-on-secondary);
		border-top-right-radius: 50px;
		border-bottom-right-radius: 50px;
		padding: 15px;
		font-size: 15px;
		display: flex;
		align-items: center;
		box-shadow: 0 4px 14px rgb(0 30 64 / 0.28);
		cursor: pointer;
		z-index: 9999;
		width: 60px;
		text-decoration: none;
		transition: background-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
	}

	.floating-call-button:hover {
		background-color: var(--color-secondary-container);
		box-shadow: 0 6px 18px rgb(0 30 64 / 0.34);
		transform: translateY(-50%) scale(1.03);
	}

	.floating-call-button svg {
		width: 20px;
		height: 20px;
		fill: currentColor;
		flex-shrink: 0;
		transform: scaleX(-1);
	}

	.phone-modal-overlay {
		display: none;
		position: fixed;
		inset: 0;
		background: rgb(0 30 64 / 0.55);
		z-index: 99999;
		opacity: 0;
		transition: opacity 0.3s ease;
	}

	.phone-modal {
		position: fixed;
		bottom: -100%;
		left: 50%;
		transform: translateX(-50%);
		width: 90%;
		max-width: 500px;
		background: var(--color-surface);
		border-radius: 20px 20px 0 0;
		padding: 25px;
		z-index: 100000;
		transition: bottom 0.4s cubic-bezier(0.4, 0, 0.2, 1);
		box-shadow: 0 -4px 24px rgb(0 30 64 / 0.18);
	}

	.phone-modal.active {
		bottom: 0;
	}

	.phone-modal-overlay.active {
		display: block;
		opacity: 1;
	}

	.phone-modal-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 20px;
		padding-bottom: 15px;
		border-bottom: 1px solid var(--color-outline-variant);
	}

	.phone-modal-title {
		font-size: 20px;
		font-weight: 900;
		color: var(--color-primary);
	}

	.phone-modal-close {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
		width: 2.5rem;
		height: 2.5rem;
		margin: 0;
		padding: 0;
		border: none;
		border-radius: var(--radius-full);
		background: var(--color-surface-container);
		color: var(--color-on-surface-variant);
		cursor: pointer;
		line-height: 0;
		transition: background-color 0.2s ease, color 0.2s ease;
	}

	.phone-modal-close:hover {
		background: var(--color-surface-container-high);
		color: var(--color-primary);
	}

	.phone-modal-close:focus-visible {
		outline: 2px solid var(--color-primary-fixed-dim);
		outline-offset: 2px;
	}

	.phone-modal-close svg {
		display: block;
		width: 1.25rem;
		height: 1.25rem;
		fill: currentColor;
	}

	.phone-numbers-list {
		display: grid;
		gap: 15px;
	}

	.phone-number-item {
		display: flex;
		align-items: center;
		padding: 15px;
		background: var(--color-surface-container-low);
		border: 1px solid var(--color-outline-variant);
		border-radius: 12px;
		text-decoration: none;
		color: var(--color-on-surface);
		transition: background-color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
	}

	.phone-number-item:hover {
		background: var(--color-surface-container-high);
		border-color: var(--color-primary-fixed-dim);
		transform: translateY(-2px);
	}

	.phone-number-icon {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 2.5rem;
		height: 2.5rem;
		margin-inline-end: 15px;
		border-radius: var(--radius-md);
		background: rgb(183 16 42 / 0.1);
		color: var(--color-secondary);
		flex-shrink: 0;
	}

	.phone-number-icon .material-symbols-outlined {
		font-size: 1.375rem;
	}

	.phone-number-details {
		display: flex;
		flex: 1;
		justify-content: space-between;
		align-items: center;
		gap: 12px;
	}

	.phone-number {
		padding-inline-end: 12px;
		font-size: 17px;
		font-weight: 600;
		color: var(--color-primary);
		direction: ltr;
	}

	.phone-label {
		font-size: 14px;
		font-weight: 700;
		color: var(--color-on-surface-variant);
	}
	</style>

	<a href="#" id="floatingCallButton" class="floating-call-button" aria-label="<?php esc_attr_e( 'تماس با ما', 'samabar' ); ?>">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
			<path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.11-.21 11.36 11.36 0 003.54.57 1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.25a1 1 0 011 1 11.36 11.36 0 00.57 3.54 1 1 0 01-.21 1.11l-2.2 2.2z"/>
		</svg>
	</a>

	<div class="phone-modal-overlay" id="phone-modal-overlay" hidden>
		<div class="phone-modal" id="phone-modal" role="dialog" aria-modal="true" aria-labelledby="phone-modal-title">
			<div class="phone-modal-header">
				<div class="phone-modal-title" id="phone-modal-title"><?php esc_html_e( 'شماره‌های تماس', 'samabar' ); ?></div>
				<button type="button" class="phone-modal-close" aria-label="<?php esc_attr_e( 'بستن', 'samabar' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
						<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
					</svg>
				</button>
			</div>
			<div class="phone-numbers-list">
				<?php foreach ( samabar_get_site_quick_contacts() as $item ) : ?>
					<a
						href="<?php echo esc_url( $item['href'] ); ?>"
						class="phone-number-item"
						<?php echo ! empty( $item['external'] ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
					>
						<div class="phone-number-icon">
							<span class="material-symbols-outlined" aria-hidden="true"><?php echo esc_html( $item['icon'] ); ?></span>
						</div>
						<div class="phone-number-details">
							<div class="phone-number" dir="ltr"><?php echo esc_html( $item['value'] ); ?></div>
							<div class="phone-label"><?php echo esc_html( $item['label'] ); ?></div>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<script>
	document.addEventListener('DOMContentLoaded', function () {
		const btn = document.getElementById('floatingCallButton');
		const modal = document.getElementById('phone-modal');
		const overlay = document.getElementById('phone-modal-overlay');
		const closeBtn = document.querySelector('.phone-modal-close');
		const tamasElements = document.querySelectorAll('.tamas');

		if (!btn || !modal || !overlay || !closeBtn) {
			return;
		}

		function openModal(event) {
			event.preventDefault();
			overlay.hidden = false;
			overlay.classList.add('active');
			window.setTimeout(function () {
				modal.classList.add('active');
			}, 10);
		}

		function closeModal() {
			modal.classList.remove('active');
			window.setTimeout(function () {
				overlay.classList.remove('active');
				overlay.hidden = true;
			}, 300);
		}

		btn.addEventListener('click', openModal);
		tamasElements.forEach(function (element) {
			element.addEventListener('click', openModal);
		});
		closeBtn.addEventListener('click', closeModal);
		overlay.addEventListener('click', function (event) {
			if (event.target === overlay) {
				closeModal();
			}
		});
	});
	</script>
	<?php
}
add_action( 'wp_footer', 'samabar_render_floating_call_button' );

