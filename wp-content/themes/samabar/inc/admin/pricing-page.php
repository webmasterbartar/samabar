<?php
/**
 * Admin pricing settings page.
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register pricing submenu.
 */
function samabar_admin_pricing_menu() {
	add_submenu_page(
		'samabar-orders',
		__( 'تنظیم قیمت‌ها', 'samabar' ),
		__( 'قیمت‌ها', 'samabar' ),
		'manage_options',
		'samabar-pricing',
		'samabar_admin_pricing_render'
	);
}
add_action( 'admin_menu', 'samabar_admin_pricing_menu' );

/**
 * Handle pricing form save.
 */
function samabar_admin_pricing_handle_save() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( empty( $_POST['samabar_pricing_action'] ) || 'save_prices' !== $_POST['samabar_pricing_action'] ) {
		return;
	}

	if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'samabar_save_pricing' ) ) {
		return;
	}

	$raw = isset( $_POST['service_price'] ) && is_array( $_POST['service_price'] )
		? wp_unslash( $_POST['service_price'] )
		: array();

	samabar_save_service_prices( $raw );

	wp_safe_redirect(
		add_query_arg(
			array(
				'page'    => 'samabar-pricing',
				'updated' => '1',
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}
add_action( 'admin_init', 'samabar_admin_pricing_handle_save' );

/**
 * Service descriptions for admin form.
 *
 * @return array<string, string>
 */
function samabar_get_service_descriptions() {
	return array(
		'corporate' => 'سرویس سازمانی — پیشنهاد برای محموله‌های صنعتی',
		'express'   => 'سرویس اکسپرس — تحویل سریع',
		'standard'  => 'بین‌شهری عادی — گزینه مقرون‌به‌صرفه',
	);
}

/**
 * Render pricing settings page.
 */
function samabar_admin_pricing_render() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$labels       = samabar_get_service_labels();
	$descriptions = samabar_get_service_descriptions();
	$prices       = samabar_get_service_prices();
	$orders_url   = admin_url( 'admin.php?page=samabar-orders' );
	$updated      = isset( $_GET['updated'] ) && '1' === $_GET['updated'];
	?>
	<div class="wrap samabar-admin samabar-admin--pricing" dir="rtl">
		<header class="samabar-admin__header">
			<div class="samabar-admin__header-main">
				<div class="samabar-admin__logo">
					<span class="dashicons dashicons-money-alt" aria-hidden="true"></span>
				</div>
				<div>
					<h1 class="samabar-admin__title">تنظیم قیمت سرویس‌ها</h1>
					<p class="samabar-admin__subtitle">قیمت‌های نمایش‌داده‌شده هنگام ثبت سفارش</p>
				</div>
			</div>
		</header>

		<?php if ( $updated ) : ?>
			<div class="notice notice-success is-dismissible"><p>قیمت‌ها با موفقیت ذخیره شد.</p></div>
		<?php endif; ?>

		<form class="samabar-pricing" method="post" action="<?php echo esc_url( admin_url( 'admin.php?page=samabar-pricing' ) ); ?>">
			<?php wp_nonce_field( 'samabar_save_pricing' ); ?>
			<input type="hidden" name="samabar_pricing_action" value="save_prices">

			<div class="samabar-pricing__grid">
				<?php foreach ( $labels as $key => $label ) : ?>
					<div class="samabar-pricing-card">
						<div class="samabar-pricing-card__head">
							<h2 class="samabar-pricing-card__title"><?php echo esc_html( $label ); ?></h2>
							<span class="samabar-pricing-card__key"><?php echo esc_html( $key ); ?></span>
						</div>
						<p class="samabar-pricing-card__desc"><?php echo esc_html( $descriptions[ $key ] ?? '' ); ?></p>
						<label class="samabar-pricing-card__field">
							<span class="samabar-pricing-card__label">قیمت (ریال)</span>
							<div class="samabar-pricing-card__input-wrap">
								<input
									type="number"
									name="service_price[<?php echo esc_attr( $key ); ?>]"
									value="<?php echo esc_attr( (string) $prices[ $key ] ); ?>"
									min="0"
									step="1000"
									required
									dir="ltr"
									class="samabar-pricing-card__input"
								>
								<span class="samabar-pricing-card__unit">﷼</span>
							</div>
							<span class="samabar-pricing-card__preview">
								نمایش برای مشتری: <?php echo esc_html( samabar_format_price( $prices[ $key ] ) ); ?>
							</span>
						</label>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="samabar-pricing__actions">
				<button type="submit" class="button button-primary button-hero">ذخیره قیمت‌ها</button>
				<a class="button button-secondary" href="<?php echo esc_url( $orders_url ); ?>">بازگشت به سفارش‌ها</a>
			</div>
		</form>

		<p class="samabar-pricing__hint">مبالغ سرویس را به <strong>ریال</strong> وارد کنید. در سایت برای مشتری به <strong>تومان</strong> نمایش داده می‌شود.</p>
	</div>
	<?php
}
