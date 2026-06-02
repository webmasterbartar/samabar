<?php
/**
 * QA / test users and sample data (local & staging only).
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether test seeding and QA pages are allowed.
 *
 * @return bool
 */
function samabar_is_qa_environment() {
	if ( defined( 'SAMABAR_QA_MODE' ) && SAMABAR_QA_MODE ) {
		return true;
	}

	$host = wp_parse_url( home_url(), PHP_URL_HOST );
	if ( is_string( $host ) && preg_match( '/(\.test|\.local|localhost)$/i', $host ) ) {
		return true;
	}

	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		return true;
	}

	return in_array( wp_get_environment_type(), array( 'local', 'development', 'staging' ), true );
}

/**
 * Default password for seeded QA WordPress accounts.
 *
 * @return string
 */
function samabar_get_qa_default_password() {
	return 'SamabarTest1403!';
}

/**
 * Seeded WordPress users for admin/content testing.
 *
 * @return array<int, array<string, string>>
 */
function samabar_get_qa_wp_users_config() {
	return array(
		array(
			'user_login'   => 'qa_admin',
			'user_email'   => 'qa-admin@samabar.test',
			'display_name' => 'تستر مدیر',
			'role'         => 'administrator',
			'description'  => 'دسترسی کامل — مدیریت سفارش‌ها، قیمت‌ها و محتوا',
		),
		array(
			'user_login'   => 'qa_editor',
			'user_email'   => 'qa-editor@samabar.test',
			'display_name' => 'تستر محتوا',
			'role'         => 'editor',
			'description'  => 'ویرایش نوشته‌ها و صفحات',
		),
		array(
			'user_login'   => 'qa_author',
			'user_email'   => 'qa-author@samabar.test',
			'display_name' => 'تستر نویسنده',
			'role'         => 'author',
			'description'  => 'فقط انتشار نوشته‌های خود',
		),
	);
}

/**
 * Seeded customer personas (phone-based dashboard login).
 *
 * @return array<int, array<string, mixed>>
 */
function samabar_get_qa_customers_config() {
	return array(
		array(
			'phone'       => '09100000001',
			'full_name'   => 'علی محمدی',
			'company'     => 'صنایع فولاد پارس',
			'description' => '۲ سفارش فعال + ۱ تحویل‌شده — تست داشبورد پر',
			'orders'      => array(
				array(
					'seed_key'            => 'qa-ali-active-1',
					'status'              => 'in_progress',
					'origin_city'         => 'تهران',
					'origin_address'      => 'شهرک صنعتی',
					'destination_city'    => 'اصفهان',
					'destination_address' => 'منطقه صنعتی',
					'cargo_type'          => 'b2b',
					'service'             => 'corporate',
					'weight'              => 1200,
				),
				array(
					'seed_key'            => 'qa-ali-active-2',
					'status'              => 'confirmed',
					'origin_city'         => 'کرج',
					'origin_address'      => 'جاده مخصوص',
					'destination_city'    => 'شیراز',
					'destination_address' => 'شهرک حمل',
					'cargo_type'          => 'general',
					'service'             => 'standard',
					'weight'              => 800,
				),
				array(
					'seed_key'            => 'qa-ali-delivered',
					'status'              => 'delivered',
					'origin_city'         => 'تهران',
					'origin_address'      => 'انبار مرکزی',
					'destination_city'    => 'مشهد',
					'destination_address' => 'ترمینال بار',
					'cargo_type'          => 'b2b',
					'service'             => 'express',
					'weight'              => 500,
				),
			),
		),
		array(
			'phone'       => '09100000002',
			'full_name'   => 'سارا رضایی',
			'company'     => 'لجستیک رضایی',
			'description' => '۱ سفارش در انتظار — تست وضعیت pending',
			'orders'      => array(
				array(
					'seed_key'            => 'qa-sara-pending',
					'status'              => 'pending',
					'origin_city'         => 'تهران',
					'origin_address'      => 'جنت‌آباد',
					'destination_city'    => 'تبریز',
					'destination_address' => 'بزرگراه شمال',
					'cargo_type'          => 'fragile',
					'service'             => 'express',
					'weight'              => 350,
				),
			),
		),
		array(
			'phone'       => '09100000003',
			'full_name'   => 'رضا کریمی',
			'company'     => '',
			'description' => 'بدون سفارش — تست داشبورد خالی',
			'orders'      => array(),
		),
		array(
			'phone'       => '09100000004',
			'full_name'   => 'مریم احمدی',
			'company'     => 'بسته‌بندی نوین',
			'description' => 'سفارش لغوشده + بارگیری — تست پیگیری',
			'orders'      => array(
				array(
					'seed_key'            => 'qa-maryam-cancelled',
					'status'              => 'cancelled',
					'origin_city'         => 'اهواز',
					'origin_address'      => 'بندر',
					'destination_city'    => 'تهران',
					'destination_address' => 'انبار غرب',
					'cargo_type'          => 'cold',
					'service'             => 'corporate',
					'weight'              => 200,
				),
				array(
					'seed_key'            => 'qa-maryam-loaded',
					'status'              => 'loaded',
					'origin_city'         => 'قم',
					'origin_address'      => 'شهرک صنعتی',
					'destination_city'    => 'یزد',
					'destination_address' => 'منطقه ۲',
					'cargo_type'          => 'general',
					'service'             => 'standard',
					'weight'              => 650,
				),
			),
		),
	);
}

/**
 * Find seeded order by key.
 *
 * @param string $seed_key Seed identifier.
 * @return int Post ID or 0.
 */
function samabar_get_qa_seeded_order_id( $seed_key ) {
	$query = new WP_Query(
		array(
			'post_type'      => 'samabar_order',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_query'     => array(
				array(
					'key'   => '_samabar_test_seed_key',
					'value' => $seed_key,
				),
			),
		)
	);

	return ! empty( $query->posts[0] ) ? (int) $query->posts[0] : 0;
}

/**
 * Create a test order.
 *
 * @param array $customer Customer row.
 * @param array $order    Order config.
 * @return int|WP_Error
 */
function samabar_seed_qa_order( $customer, $order ) {
	$seed_key = $order['seed_key'] ?? '';
	if ( $seed_key && samabar_get_qa_seeded_order_id( $seed_key ) ) {
		return samabar_get_qa_seeded_order_id( $seed_key );
	}

	$data = samabar_sanitize_order_data(
		array_merge(
			$order,
			array(
				'full_name' => $customer['full_name'],
				'phone'     => $customer['phone'],
				'company'   => $customer['company'] ?? '',
			)
		)
	);

	$post_id = samabar_create_order( $data );
	if ( is_wp_error( $post_id ) ) {
		return $post_id;
	}

	if ( $seed_key ) {
		update_post_meta( $post_id, '_samabar_test_seed_key', $seed_key );
	}

	$status = sanitize_key( $order['status'] ?? 'pending' );
	if ( 'pending' !== $status ) {
		samabar_update_order_status( $post_id, $status );
	}

	return $post_id;
}

/**
 * Create QA WordPress user.
 *
 * @param array $config User config.
 * @return int User ID.
 */
function samabar_seed_qa_wp_user( $config ) {
	$login = $config['user_login'];
	$user  = get_user_by( 'login', $login );

	if ( $user ) {
		return (int) $user->ID;
	}

	$user_id = wp_insert_user(
		array(
			'user_login'   => $login,
			'user_pass'    => samabar_get_qa_default_password(),
			'user_email'   => $config['user_email'],
			'display_name' => $config['display_name'],
			'role'         => $config['role'],
			'description'  => $config['description'] ?? '',
		)
	);

	return is_wp_error( $user_id ) ? 0 : (int) $user_id;
}

/**
 * Build manifest for QA page.
 *
 * @return array<string, mixed>
 */
function samabar_build_qa_manifest() {
	$manifest = array(
		'password'  => samabar_get_qa_default_password(),
		'wp_users'  => array(),
		'customers' => array(),
	);

	foreach ( samabar_get_qa_wp_users_config() as $config ) {
		$user = get_user_by( 'login', $config['user_login'] );
		if ( ! $user ) {
			continue;
		}
		$manifest['wp_users'][] = array(
			'login'       => $config['user_login'],
			'email'       => $config['user_email'],
			'role'        => $config['role'],
			'description' => $config['description'],
		);
	}

	foreach ( samabar_get_qa_customers_config() as $customer ) {
		$row = array(
			'phone'       => $customer['phone'],
			'name'        => $customer['full_name'],
			'description' => $customer['description'],
			'dashboard'   => add_query_arg( 'phone', $customer['phone'], samabar_get_dashboard_url() ),
			'orders'      => array(),
		);

		foreach ( $customer['orders'] as $order ) {
			$post_id = samabar_get_qa_seeded_order_id( $order['seed_key'] );
			if ( ! $post_id ) {
				continue;
			}
			$data = samabar_get_order( $post_id );
			if ( ! $data ) {
				continue;
			}
			$row['orders'][] = array(
				'number'       => $data['order_number'],
				'status'       => $data['status'],
				'status_label' => $data['status_label'],
				'tracking_url' => samabar_get_tracking_url( $data['order_number'] ),
			);
		}

		$manifest['customers'][] = $row;
	}

	return $manifest;
}

/**
 * Seed QA users and orders once.
 */
function samabar_seed_test_data() {
	if ( ! samabar_is_qa_environment() ) {
		return;
	}

	if ( get_option( 'samabar_test_data_seeded' ) ) {
		return;
	}

	foreach ( samabar_get_qa_wp_users_config() as $config ) {
		samabar_seed_qa_wp_user( $config );
	}

	foreach ( samabar_get_qa_customers_config() as $customer ) {
		foreach ( $customer['orders'] as $order ) {
			samabar_seed_qa_order( $customer, $order );
		}
	}

	update_option( 'samabar_test_data_seeded', 1, false );
	update_option( 'samabar_test_data_manifest', samabar_build_qa_manifest(), false );
}
add_action( 'init', 'samabar_seed_test_data', 25 );

/**
 * QA page URL.
 *
 * @return string
 */
function samabar_get_qa_url() {
	return samabar_get_page_url( 'test-qa' );
}

/**
 * Admin bar link for quick QA access.
 *
 * @param WP_Admin_Bar $bar Admin bar.
 */
function samabar_qa_admin_bar_link( $bar ) {
	if ( ! samabar_is_qa_environment() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$bar->add_node(
		array(
			'id'    => 'samabar-qa',
			'title' => 'راهنمای QA',
			'href'  => samabar_get_qa_url(),
		)
	);
}
add_action( 'admin_bar_menu', 'samabar_qa_admin_bar_link', 100 );

/**
 * Noindex QA page.
 */
function samabar_qa_robots() {
	if ( is_page_template( 'page-test-qa.php' ) ) {
		echo '<meta name="robots" content="noindex, nofollow">' . "\n";
	}
}
add_action( 'wp_head', 'samabar_qa_robots', 1 );

/**
 * Refresh manifest when viewing QA page.
 */
function samabar_refresh_qa_manifest() {
	if ( is_page_template( 'page-test-qa.php' ) && samabar_is_qa_environment() ) {
		update_option( 'samabar_test_data_manifest', samabar_build_qa_manifest(), false );
	}
}
add_action( 'template_redirect', 'samabar_refresh_qa_manifest' );
