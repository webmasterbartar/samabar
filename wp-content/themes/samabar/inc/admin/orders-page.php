<?php
/**
 * Admin orders dashboard page.
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register admin menu.
 */
function samabar_admin_orders_menu() {
	$pending    = samabar_count_orders_by_status()['pending'] ?? 0;
	$menu_title = __( 'سفارش‌ها', 'samabar' );

	if ( $pending > 0 ) {
		$menu_title .= sprintf(
			' <span class="awaiting-mod count-%1$d"><span class="pending-count">%2$s</span></span>',
			$pending,
			number_format_i18n( $pending )
		);
	}

	add_menu_page(
		__( 'سفارش‌های سما بار', 'samabar' ),
		$menu_title,
		'manage_options',
		'samabar-orders',
		'samabar_admin_orders_render',
		'dashicons-clipboard',
		26
	);
}
add_action( 'admin_menu', 'samabar_admin_orders_menu' );

/**
 * Inline @font-face rules for admin (absolute URLs — reliable in wp-admin).
 *
 * @return string
 */
function samabar_admin_font_face_css() {
	$base    = get_template_directory_uri() . '/font/';
	$weights = array(
		400 => 'PeydaWebFaNum-Regular.woff2',
		500 => 'PeydaWebFaNum-Medium.woff2',
		600 => 'PeydaWebFaNum-SemiBold.woff2',
		700 => 'PeydaWebFaNum-Bold.woff2',
	);

	$css = '';
	foreach ( $weights as $weight => $file ) {
		$css .= sprintf(
			"@font-face{font-family:'PeydaWebFaNum';font-style:normal;font-weight:%1\$d;font-display:swap;src:url('%2\$s%3\$s') format('woff2');}",
			$weight,
			esc_url( $base ),
			$file
		);
	}

	return $css;
}

/**
 * Samabar admin screen hooks.
 *
 * @return array<int, string>
 */
function samabar_admin_screen_hooks() {
	return array(
		'toplevel_page_samabar-orders',
		'samabar-orders_page_samabar-pricing',
	);
}

/**
 * Body class on Samabar admin screens.
 *
 * @param string $classes Admin body classes.
 * @return string
 */
function samabar_admin_orders_body_class( $classes ) {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( $screen && in_array( $screen->id, samabar_admin_screen_hooks(), true ) ) {
		$classes .= ' samabar-admin-screen';
	}
	return $classes;
}
add_filter( 'admin_body_class', 'samabar_admin_orders_body_class' );

/**
 * Enqueue admin assets.
 *
 * @param string $hook Current admin page hook.
 */
function samabar_admin_orders_assets( $hook ) {
	if ( ! in_array( $hook, samabar_admin_screen_hooks(), true ) ) {
		return;
	}

	wp_register_style( 'samabar-admin-font-faces', false, array(), SAMABAR_VERSION );
	wp_enqueue_style( 'samabar-admin-font-faces' );
	wp_add_inline_style( 'samabar-admin-font-faces', samabar_admin_font_face_css() );

	wp_enqueue_style(
		'samabar-admin-orders',
		get_template_directory_uri() . '/assets/css/admin/orders.css',
		array( 'samabar-admin-font-faces' ),
		SAMABAR_VERSION
	);
}
add_action( 'admin_enqueue_scripts', 'samabar_admin_orders_assets' );

/**
 * Handle status update form.
 */
function samabar_admin_orders_handle_actions() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( empty( $_POST['samabar_order_action'] ) || empty( $_POST['order_id'] ) ) {
		return;
	}

	if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'samabar_update_order_' . (int) $_POST['order_id'] ) ) {
		return;
	}

	$order_id = (int) $_POST['order_id'];
	$action   = sanitize_key( wp_unslash( $_POST['samabar_order_action'] ) );

	if ( 'update_status' === $action && ! empty( $_POST['status'] ) ) {
		samabar_update_order_status( $order_id, sanitize_key( wp_unslash( $_POST['status'] ) ) );
	}

	$redirect_args = array(
		'page'    => 'samabar-orders',
		'updated' => '1',
	);

	if ( ! empty( $_POST['redirect_list'] ) ) {
		if ( ! empty( $_POST['status_filter'] ) ) {
			$redirect_args['status'] = sanitize_key( wp_unslash( $_POST['status_filter'] ) );
		}
		if ( ! empty( $_POST['search_query'] ) ) {
			$redirect_args['s'] = sanitize_text_field( wp_unslash( $_POST['search_query'] ) );
		}
		if ( ! empty( $_POST['paged'] ) ) {
			$redirect_args['paged'] = max( 1, (int) $_POST['paged'] );
		}
	} else {
		$redirect_args['order'] = $order_id;
	}

	wp_safe_redirect( add_query_arg( $redirect_args, admin_url( 'admin.php' ) ) );
	exit;
}
add_action( 'admin_init', 'samabar_admin_orders_handle_actions' );

/**
 * Render admin page.
 */
function samabar_admin_orders_render() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$view_order_id = isset( $_GET['order'] ) ? (int) $_GET['order'] : 0;

	if ( $view_order_id ) {
		samabar_admin_orders_render_detail( $view_order_id );
		return;
	}

	samabar_admin_orders_render_list();
}

/**
 * Render orders list.
 */
function samabar_admin_orders_render_list() {
	$status_filter = isset( $_GET['status'] ) ? sanitize_key( wp_unslash( $_GET['status'] ) ) : '';
	$search        = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
	$paged         = isset( $_GET['paged'] ) ? max( 1, (int) $_GET['paged'] ) : 1;

	$result         = samabar_query_orders(
		array(
			'status'   => $status_filter,
			'search'   => $search,
			'paged'    => $paged,
			'per_page' => 15,
		)
	);
	$status_counts  = samabar_count_orders_by_status();
	$status_labels  = samabar_get_order_statuses();
	$total_orders   = array_sum( $status_counts );
	$pending_count  = $status_counts['pending'] ?? 0;
	$today_count    = samabar_admin_count_today_orders();
	$list_url       = admin_url( 'admin.php?page=samabar-orders' );
	$pricing_url    = admin_url( 'admin.php?page=samabar-pricing' );
	$list_updated   = isset( $_GET['updated'] );
	?>
	<div class="wrap samabar-admin" dir="rtl">
		<?php if ( $list_updated ) : ?>
			<div class="notice notice-success is-dismissible"><p>وضعیت سفارش به‌روزرسانی شد.</p></div>
		<?php endif; ?>
		<header class="samabar-admin__header">
			<div class="samabar-admin__header-main">
				<div class="samabar-admin__logo">
					<span class="dashicons dashicons-local-shipping"></span>
				</div>
				<div>
					<h1 class="samabar-admin__title">سفارش‌های سما بار</h1>
					<p class="samabar-admin__subtitle">مدیریت و پیگیری سفارش‌های ثبت‌شده از سایت</p>
				</div>
			</div>
		</header>

		<div class="samabar-admin__stats">
			<div class="samabar-stat samabar-stat--primary">
				<span class="samabar-stat__value"><?php echo esc_html( number_format_i18n( $total_orders ) ); ?></span>
				<span class="samabar-stat__label">کل سفارش‌ها</span>
			</div>
			<div class="samabar-stat samabar-stat--warning">
				<span class="samabar-stat__value"><?php echo esc_html( number_format_i18n( $pending_count ) ); ?></span>
				<span class="samabar-stat__label">در انتظار بررسی</span>
			</div>
			<div class="samabar-stat samabar-stat--success">
				<span class="samabar-stat__value"><?php echo esc_html( number_format_i18n( $status_counts['delivered'] ?? 0 ) ); ?></span>
				<span class="samabar-stat__label">تحویل شده</span>
			</div>
			<div class="samabar-stat samabar-stat--info">
				<span class="samabar-stat__value"><?php echo esc_html( number_format_i18n( $today_count ) ); ?></span>
				<span class="samabar-stat__label">سفارش امروز</span>
			</div>
		</div>

		<div class="samabar-admin__toolbar">
			<div class="samabar-admin__filters">
				<a class="samabar-filter<?php echo '' === $status_filter ? ' is-active' : ''; ?>" href="<?php echo esc_url( $list_url ); ?>">همه</a>
				<?php foreach ( $status_labels as $key => $label ) : ?>
					<a class="samabar-filter samabar-filter--<?php echo esc_attr( $key ); ?><?php echo $status_filter === $key ? ' is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'status', $key, $list_url ) ); ?>">
						<?php echo esc_html( $label ); ?>
						<span class="samabar-filter__count"><?php echo esc_html( number_format_i18n( $status_counts[ $key ] ?? 0 ) ); ?></span>
					</a>
				<?php endforeach; ?>
			</div>

			<form class="samabar-admin__search" method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
				<input type="hidden" name="page" value="samabar-orders">
				<?php if ( $status_filter ) : ?>
					<input type="hidden" name="status" value="<?php echo esc_attr( $status_filter ); ?>">
				<?php endif; ?>
				<input type="search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="جستجو در سفارش‌ها...">
				<button type="submit" class="button">جستجو</button>
			</form>

			<a class="button samabar-admin__pricing-link" href="<?php echo esc_url( $pricing_url ); ?>">
				<span class="dashicons dashicons-money-alt" aria-hidden="true"></span>
				تنظیم قیمت‌ها
			</a>
		</div>

		<?php if ( empty( $result['items'] ) ) : ?>
			<div class="samabar-admin__empty">
				<span class="dashicons dashicons-clipboard"></span>
				<h2>سفارشی یافت نشد</h2>
				<p>هنوز سفارشی ثبت نشده یا فیلتر انتخابی نتیجه‌ای ندارد.</p>
			</div>
		<?php else : ?>
			<div class="samabar-orders-grid">
				<?php foreach ( $result['items'] as $order ) : ?>
					<?php samabar_admin_render_order_card( $order, $status_labels, $status_filter, $search, $paged ); ?>
				<?php endforeach; ?>
			</div>

			<?php if ( $result['pages'] > 1 ) : ?>
				<div class="samabar-admin__pagination">
					<?php
					echo wp_kses_post(
						paginate_links(
							array(
								'base'      => add_query_arg( 'paged', '%#%', $list_url ),
								'format'    => '',
								'current'   => $paged,
								'total'     => $result['pages'],
								'prev_text' => '← قبلی',
								'next_text' => 'بعدی →',
							)
						)
					);
					?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Count today's orders.
 *
 * @return int
 */
function samabar_admin_count_today_orders() {
	$query = new WP_Query(
		array(
			'post_type'      => 'samabar_order',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'date_query'     => array(
				array(
					'after' => 'today',
				),
			),
			'fields'         => 'ids',
		)
	);

	return (int) $query->found_posts;
}

/**
 * Render single order card in list.
 *
 * @param array  $order         Order data.
 * @param array  $status_labels Status options.
 * @param string $status_filter Current list filter.
 * @param string $search        Current search query.
 * @param int    $paged         Current page.
 */
function samabar_admin_render_order_card( $order, $status_labels = array(), $status_filter = '', $search = '', $paged = 1 ) {
	if ( empty( $status_labels ) ) {
		$status_labels = samabar_get_order_statuses();
	}

	$detail_url = add_query_arg(
		array(
			'page'  => 'samabar-orders',
			'order' => $order['id'],
		),
		admin_url( 'admin.php' )
	);
	?>
	<article class="samabar-order-card">
		<div class="samabar-order-card__head">
			<div>
				<span class="samabar-order-card__number"><?php echo esc_html( $order['order_number'] ); ?></span>
				<span class="samabar-badge samabar-badge--<?php echo esc_attr( $order['status'] ); ?>"><?php echo esc_html( $order['status_label'] ); ?></span>
			</div>
			<time class="samabar-order-card__date"><?php echo esc_html( $order['created_at'] ); ?></time>
		</div>

		<div class="samabar-order-card__route">
			<div class="samabar-order-card__point">
				<span class="samabar-order-card__dot samabar-order-card__dot--origin"></span>
				<div>
					<span class="samabar-order-card__meta">مبدا</span>
					<strong><?php echo esc_html( $order['origin'] ); ?></strong>
				</div>
			</div>
			<div class="samabar-order-card__line" aria-hidden="true"></div>
			<div class="samabar-order-card__point">
				<span class="samabar-order-card__dot samabar-order-card__dot--dest"></span>
				<div>
					<span class="samabar-order-card__meta">مقصد</span>
					<strong><?php echo esc_html( $order['destination'] ); ?></strong>
				</div>
			</div>
		</div>

		<div class="samabar-order-card__meta-grid">
			<div>
				<span class="samabar-order-card__meta">مشتری</span>
				<span><?php echo esc_html( $order['full_name'] ?: '—' ); ?></span>
			</div>
			<div>
				<span class="samabar-order-card__meta">موبایل</span>
				<span dir="ltr"><?php echo esc_html( $order['phone'] ?: '—' ); ?></span>
			</div>
			<div>
				<span class="samabar-order-card__meta">محموله</span>
				<span><?php echo esc_html( $order['cargo_label'] ); ?></span>
			</div>
			<div>
				<span class="samabar-order-card__meta">مبلغ</span>
				<strong class="samabar-order-card__price"><?php echo esc_html( samabar_format_price( $order['total_price'] ) ); ?></strong>
			</div>
		</div>

		<form class="samabar-order-card__status-form" method="post" action="">
			<?php wp_nonce_field( 'samabar_update_order_' . $order['id'] ); ?>
			<input type="hidden" name="order_id" value="<?php echo esc_attr( (string) $order['id'] ); ?>">
			<input type="hidden" name="samabar_order_action" value="update_status">
			<input type="hidden" name="redirect_list" value="1">
			<?php if ( $status_filter ) : ?>
				<input type="hidden" name="status_filter" value="<?php echo esc_attr( $status_filter ); ?>">
			<?php endif; ?>
			<?php if ( $search ) : ?>
				<input type="hidden" name="search_query" value="<?php echo esc_attr( $search ); ?>">
			<?php endif; ?>
			<?php if ( $paged > 1 ) : ?>
				<input type="hidden" name="paged" value="<?php echo esc_attr( (string) $paged ); ?>">
			<?php endif; ?>
			<label class="screen-reader-text" for="samabar-status-<?php echo esc_attr( (string) $order['id'] ); ?>">وضعیت سفارش</label>
			<select id="samabar-status-<?php echo esc_attr( (string) $order['id'] ); ?>" name="status" class="samabar-order-card__status-select">
				<?php foreach ( $status_labels as $key => $label ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $order['status'], $key ); ?>>
						<?php echo esc_html( $label ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<button type="submit" class="button button-small button-primary">ذخیره وضعیت</button>
		</form>

		<a class="samabar-order-card__link" href="<?php echo esc_url( $detail_url ); ?>">
			مشاهده جزئیات
			<span class="dashicons dashicons-arrow-left-alt"></span>
		</a>
	</article>
	<?php
}

/**
 * Render order detail view.
 *
 * @param int $order_id Order post ID.
 */
function samabar_admin_orders_render_detail( $order_id ) {
	$order = samabar_get_order( $order_id );

	if ( ! $order ) {
		echo '<div class="wrap"><p>سفارش یافت نشد.</p></div>';
		return;
	}

	$list_url       = admin_url( 'admin.php?page=samabar-orders' );
	$status_labels  = samabar_get_order_statuses();
	$updated        = isset( $_GET['updated'] );
	$has_dimensions = $order['dim_length'] || $order['dim_width'] || $order['dim_height'];
	$status_log     = get_post_meta( $order_id, '_samabar_status_log', true );
	$event_titles   = samabar_get_status_event_titles();
	?>
	<div class="wrap samabar-admin samabar-admin--detail" dir="rtl">
		<?php if ( $updated ) : ?>
			<div class="notice notice-success is-dismissible"><p>وضعیت سفارش به‌روزرسانی شد. کاربر در صفحه پیگیری تغییر را می‌بیند.</p></div>
		<?php endif; ?>

		<a class="samabar-admin__back" href="<?php echo esc_url( $list_url ); ?>">
			<span class="dashicons dashicons-arrow-right-alt"></span>
			بازگشت به لیست سفارش‌ها
		</a>

		<header class="samabar-detail__header">
			<div class="samabar-detail__header-main">
				<div class="samabar-detail__icon">
					<span class="dashicons dashicons-clipboard"></span>
				</div>
				<div>
					<div class="samabar-detail__badges">
						<span class="samabar-order-card__number"><?php echo esc_html( $order['order_number'] ); ?></span>
						<span class="samabar-badge samabar-badge--<?php echo esc_attr( $order['status'] ); ?>"><?php echo esc_html( $order['status_label'] ); ?></span>
					</div>
					<h1 class="samabar-detail__title"><?php echo esc_html( $order['origin'] ); ?> ← <?php echo esc_html( $order['destination'] ); ?></h1>
					<p class="samabar-detail__date">ثبت شده در <?php echo esc_html( $order['created_at'] ); ?></p>
				</div>
			</div>
			<div class="samabar-detail__price-box">
				<span class="samabar-detail__price-label">مبلغ قابل پرداخت</span>
				<strong class="samabar-detail__price"><?php echo esc_html( samabar_format_price( $order['total_price'] ) ); ?></strong>
			</div>
		</header>

		<div class="samabar-detail__grid">
			<section class="samabar-detail__panel">
				<h2 class="samabar-detail__panel-title">
					<span class="dashicons dashicons-location"></span>
					مسیر حمل
				</h2>
				<div class="samabar-detail__route">
					<div class="samabar-detail__route-item">
						<span class="samabar-order-card__dot samabar-order-card__dot--origin"></span>
						<div>
							<span class="samabar-order-card__meta">مبدا بارگیری</span>
							<p><strong><?php echo esc_html( $order['origin_city'] ?: '—' ); ?></strong></p>
							<p><?php echo esc_html( $order['origin_address'] ?: $order['origin'] ); ?></p>
							<?php if ( $order['origin_detail'] ) : ?>
								<p class="samabar-detail__muted"><?php echo esc_html( $order['origin_detail'] ); ?></p>
							<?php endif; ?>
						</div>
					</div>
					<div class="samabar-detail__route-item">
						<span class="samabar-order-card__dot samabar-order-card__dot--dest"></span>
						<div>
							<span class="samabar-order-card__meta">مقصد تخلیه</span>
							<p><strong><?php echo esc_html( $order['destination_city'] ?: '—' ); ?></strong></p>
							<p><?php echo esc_html( $order['destination_address'] ?: $order['destination'] ); ?></p>
							<?php if ( $order['destination_detail'] ) : ?>
								<p class="samabar-detail__muted"><?php echo esc_html( $order['destination_detail'] ); ?></p>
							<?php endif; ?>
						</div>
					</div>
					<?php if ( $order['pickup_date'] ) : ?>
						<div class="samabar-detail__route-item samabar-detail__route-item--muted">
							<span class="dashicons dashicons-calendar-alt"></span>
							<div>
								<span class="samabar-order-card__meta">زمان بارگیری</span>
								<p><?php echo esc_html( samabar_format_pickup_date( $order['pickup_date'] ) ); ?></p>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</section>

			<section class="samabar-detail__panel">
				<h2 class="samabar-detail__panel-title">
					<span class="dashicons dashicons-archive"></span>
					اطلاعات محموله
				</h2>
				<dl class="samabar-detail__dl">
					<div><dt>نوع محموله</dt><dd><?php echo esc_html( $order['cargo_label'] ); ?></dd></div>
					<div><dt>وزن تقریبی</dt><dd><?php echo esc_html( number_format_i18n( $order['weight'] ) ); ?> کیلوگرم</dd></div>
					<?php if ( $has_dimensions ) : ?>
						<div>
							<dt>ابعاد</dt>
							<dd>
								<?php
								echo esc_html(
									trim(
										implode(
											' × ',
											array_filter(
												array(
													$order['dim_length'] ? $order['dim_length'] . 'm طول' : '',
													$order['dim_width'] ? $order['dim_width'] . 'm عرض' : '',
													$order['dim_height'] ? $order['dim_height'] . 'm ارتفاع' : '',
												)
											)
										)
									)
								);
								?>
							</dd>
						</div>
					<?php endif; ?>
					<div><dt>سرویس انتخابی</dt><dd><?php echo esc_html( $order['service_label'] ); ?></dd></div>
				</dl>
				<?php if ( $order['description'] ) : ?>
					<div class="samabar-detail__note">
						<strong>توضیحات مشتری</strong>
						<p><?php echo esc_html( $order['description'] ); ?></p>
					</div>
				<?php endif; ?>
			</section>

			<section class="samabar-detail__panel">
				<h2 class="samabar-detail__panel-title">
					<span class="dashicons dashicons-id"></span>
					اطلاعات تماس
				</h2>
				<dl class="samabar-detail__dl">
					<div><dt>نام</dt><dd><?php echo esc_html( $order['full_name'] ?: '—' ); ?></dd></div>
					<div><dt>موبایل</dt><dd dir="ltr"><?php echo esc_html( $order['phone'] ?: '—' ); ?></dd></div>
					<?php if ( $order['company'] ) : ?>
						<div><dt>شرکت</dt><dd><?php echo esc_html( $order['company'] ); ?></dd></div>
					<?php endif; ?>
				</dl>
			</section>

			<section class="samabar-detail__panel samabar-detail__panel--status">
				<h2 class="samabar-detail__panel-title">
					<span class="dashicons dashicons-update"></span>
					مدیریت وضعیت
				</h2>
				<p class="samabar-detail__status-help">وضعیت انتخاب‌شده بلافاصله در صفحه پیگیری سایت برای مشتری نمایش داده می‌شود.</p>
				<form method="post" action="">
					<?php wp_nonce_field( 'samabar_update_order_' . $order['id'] ); ?>
					<input type="hidden" name="order_id" value="<?php echo esc_attr( (string) $order['id'] ); ?>">
					<input type="hidden" name="samabar_order_action" value="update_status">
					<label class="samabar-detail__status-label" for="samabar-status">وضعیت سفارش</label>
					<select id="samabar-status" name="status" class="samabar-detail__status-select">
						<?php foreach ( $status_labels as $key => $label ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $order['status'], $key ); ?>>
								<?php echo esc_html( $label ); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<button type="submit" class="button button-primary samabar-detail__submit">ذخیره وضعیت</button>
				</form>

				<?php if ( is_array( $status_log ) && ! empty( $status_log ) ) : ?>
					<div class="samabar-detail__status-log">
						<h3>تاریخچه تغییر وضعیت</h3>
						<ul>
							<?php foreach ( array_reverse( $status_log ) as $entry ) : ?>
								<li>
									<strong><?php echo esc_html( $event_titles[ $entry['status'] ?? '' ] ?? ( $entry['label'] ?? '' ) ); ?></strong>
									<span><?php echo esc_html( samabar_format_tracking_time( (int) ( $entry['time'] ?? 0 ) ) ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</section>
		</div>
	</div>
	<?php
}
