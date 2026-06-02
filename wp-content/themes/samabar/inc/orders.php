<?php
/**
 * Orders custom post type, REST API, and helpers.
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register order post type.
 */
function samabar_register_order_cpt() {
	register_post_type(
		'samabar_order',
		array(
			'labels'              => array(
				'name'          => 'سفارش‌ها',
				'singular_name' => 'سفارش',
			),
			'public'              => false,
			'show_ui'             => false,
			'show_in_menu'        => false,
			'show_in_rest'        => false,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'supports'            => array( 'title' ),
			'delete_with_user'    => false,
		)
	);
}
add_action( 'init', 'samabar_register_order_cpt' );

/**
 * Order status labels.
 *
 * @return array<string, string>
 */
function samabar_get_order_statuses() {
	return array(
		'pending'     => 'در انتظار بررسی',
		'confirmed'   => 'تایید شده',
		'loaded'      => 'بارگیری شده',
		'in_progress' => 'در مسیر',
		'delivered'   => 'تحویل شده',
		'cancelled'   => 'لغو شده',
	);
}

/**
 * Cargo type labels.
 *
 * @return array<string, string>
 */
function samabar_get_cargo_labels() {
	return array(
		'b2b'      => 'صنعتی / B2B',
		'general'  => 'عمومی',
		'fragile'  => 'شکستنی',
		'cold'     => 'حساس / یخچالی',
	);
}

/**
 * Service labels.
 *
 * @return array<string, string>
 */
function samabar_get_service_labels() {
	return array(
		'corporate' => 'سازمانی',
		'express'   => 'اکسپرس',
		'standard'  => 'بین‌شهری عادی',
	);
}

/**
 * Default service prices (Rial).
 *
 * @return array<string, int>
 */
function samabar_get_default_service_prices() {
	return array(
		'corporate' => 42500000,
		'express'   => 55000000,
		'standard'  => 30000000,
	);
}

/**
 * Service prices (Rial) — from dashboard settings.
 *
 * @return array<string, int>
 */
function samabar_get_service_prices() {
	$defaults = samabar_get_default_service_prices();
	$saved    = get_option( 'samabar_service_prices', array() );

	if ( ! is_array( $saved ) ) {
		$saved = array();
	}

	$prices = array();
	foreach ( array_keys( samabar_get_service_labels() ) as $key ) {
		$prices[ $key ] = absint( $saved[ $key ] ?? $defaults[ $key ] ?? 0 );
	}

	return $prices;
}

/**
 * Save service prices from admin.
 *
 * @param array $prices Raw price input.
 * @return array<string, int>
 */
function samabar_save_service_prices( $prices ) {
	$clean = array();

	foreach ( array_keys( samabar_get_service_labels() ) as $key ) {
		$clean[ $key ] = max( 0, absint( $prices[ $key ] ?? 0 ) );
	}

	update_option( 'samabar_service_prices', $clean, false );

	return $clean;
}

/**
 * Sanitize order payload from frontend.
 *
 * @param array $data Raw data.
 * @return array
 */
function samabar_sanitize_order_data( $data ) {
	$allowed_cargo   = array_keys( samabar_get_cargo_labels() );
	$allowed_service = array_keys( samabar_get_service_labels() );

	$cargo   = sanitize_key( $data['cargo_type'] ?? 'b2b' );
	$service = sanitize_key( $data['service'] ?? 'corporate' );

	if ( ! in_array( $cargo, $allowed_cargo, true ) ) {
		$cargo = 'b2b';
	}
	if ( ! in_array( $service, $allowed_service, true ) ) {
		$service = 'corporate';
	}

	$prices = samabar_get_service_prices();

	$origin_city          = sanitize_text_field( $data['origin_city'] ?? '' );
	$origin_address       = sanitize_textarea_field( $data['origin_address'] ?? '' );
	$origin_detail        = sanitize_text_field( $data['origin_detail'] ?? '' );
	$destination_city     = sanitize_text_field( $data['destination_city'] ?? '' );
	$destination_address  = sanitize_textarea_field( $data['destination_address'] ?? '' );
	$destination_detail   = sanitize_text_field( $data['destination_detail'] ?? '' );

	$origin      = samabar_format_order_address( $origin_city, $origin_address, $origin_detail );
	$destination = samabar_format_order_address( $destination_city, $destination_address, $destination_detail );

	if ( empty( $origin ) && ! empty( $data['origin'] ) ) {
		$origin = sanitize_text_field( $data['origin'] );
	}
	if ( empty( $destination ) && ! empty( $data['destination'] ) ) {
		$destination = sanitize_text_field( $data['destination'] );
	}

	return array(
		'origin'              => $origin,
		'destination'         => $destination,
		'origin_city'         => $origin_city,
		'origin_address'      => $origin_address,
		'origin_detail'       => $origin_detail,
		'destination_city'    => $destination_city,
		'destination_address' => $destination_address,
		'destination_detail'  => $destination_detail,
		'pickup_date'         => sanitize_text_field( $data['pickup_date'] ?? '' ),
		'cargo_type'   => $cargo,
		'weight'       => absint( $data['weight'] ?? 0 ),
		'dim_length'   => sanitize_text_field( $data['dim_length'] ?? '' ),
		'dim_width'    => sanitize_text_field( $data['dim_width'] ?? '' ),
		'dim_height'   => sanitize_text_field( $data['dim_height'] ?? '' ),
		'description'  => sanitize_textarea_field( $data['description'] ?? '' ),
		'service'      => $service,
		'total_price'  => $prices[ $service ] ?? $prices['corporate'],
		'full_name'    => sanitize_text_field( $data['full_name'] ?? '' ),
		'phone'        => samabar_sanitize_phone( $data['phone'] ?? '' ),
		'company'      => sanitize_text_field( $data['company'] ?? '' ),
	);
}

/**
 * Build a single-line address from parts.
 *
 * @param string $city     City or province.
 * @param string $address  Street address.
 * @param string $detail   Optional unit/plate/postal code.
 * @return string
 */
function samabar_format_order_address( $city, $address, $detail = '' ) {
	$parts = array_filter(
		array(
			trim( (string) $city ),
			trim( (string) $address ),
			trim( (string) $detail ),
		)
	);

	return implode( ' — ', $parts );
}

/**
 * Normalize Iranian mobile number.
 *
 * @param string $phone Raw phone.
 * @return string
 */
function samabar_sanitize_phone( $phone ) {
	$phone = preg_replace( '/\D+/', '', (string) $phone );

	if ( 0 === strpos( $phone, '98' ) && 12 === strlen( $phone ) ) {
		$phone = '0' . substr( $phone, 2 );
	}

	if ( 10 === strlen( $phone ) && '9' === $phone[0] ) {
		$phone = '0' . $phone;
	}

	return $phone;
}

/**
 * Validate Iranian mobile number.
 *
 * @param string $phone Sanitized phone.
 * @return bool
 */
function samabar_validate_phone( $phone ) {
	return (bool) preg_match( '/^09\d{9}$/', $phone );
}

/**
 * Create order post from sanitized data.
 *
 * @param array $data Sanitized order data.
 * @return int|WP_Error Post ID.
 */
function samabar_create_order( $data ) {
	if ( empty( $data['origin'] ) || empty( $data['destination'] ) || empty( $data['weight'] ) ) {
		return new WP_Error( 'missing_fields', __( 'اطلاعات سفارش ناقص است.', 'samabar' ), array( 'status' => 400 ) );
	}

	if ( empty( $data['full_name'] ) || empty( $data['phone'] ) ) {
		return new WP_Error( 'missing_contact', __( 'نام و شماره موبایل الزامی است.', 'samabar' ), array( 'status' => 400 ) );
	}

	if ( ! samabar_validate_phone( $data['phone'] ) ) {
		return new WP_Error( 'invalid_phone', __( 'شماره موبایل معتبر نیست.', 'samabar' ), array( 'status' => 400 ) );
	}

	if ( ! empty( $data['pickup_date'] ) && ! samabar_is_pickup_datetime_available( $data['pickup_date'] ) ) {
		return new WP_Error(
			'pickup_unavailable',
			__( 'زمان بارگیری انتخاب‌شده دیگر در دسترس نیست. لطفاً روز دیگری انتخاب کنید.', 'samabar' ),
			array( 'status' => 400 )
		);
	}

	$title = sprintf(
		/* translators: 1: origin city, 2: destination city */
		__( 'سفارش %1$s ← %2$s', 'samabar' ),
		$data['origin_city'] ?: $data['origin'],
		$data['destination_city'] ?: $data['destination']
	);

	$post_id = wp_insert_post(
		array(
			'post_type'   => 'samabar_order',
			'post_title'  => $title,
			'post_status' => 'publish',
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		return $post_id;
	}

	update_post_meta( $post_id, '_samabar_origin', $data['origin'] );
	update_post_meta( $post_id, '_samabar_destination', $data['destination'] );
	update_post_meta( $post_id, '_samabar_origin_city', $data['origin_city'] );
	update_post_meta( $post_id, '_samabar_origin_address', $data['origin_address'] );
	update_post_meta( $post_id, '_samabar_origin_detail', $data['origin_detail'] );
	update_post_meta( $post_id, '_samabar_destination_city', $data['destination_city'] );
	update_post_meta( $post_id, '_samabar_destination_address', $data['destination_address'] );
	update_post_meta( $post_id, '_samabar_destination_detail', $data['destination_detail'] );
	update_post_meta( $post_id, '_samabar_pickup_date', $data['pickup_date'] );
	update_post_meta( $post_id, '_samabar_cargo_type', $data['cargo_type'] );
	update_post_meta( $post_id, '_samabar_weight', $data['weight'] );
	update_post_meta( $post_id, '_samabar_dim_length', $data['dim_length'] );
	update_post_meta( $post_id, '_samabar_dim_width', $data['dim_width'] );
	update_post_meta( $post_id, '_samabar_dim_height', $data['dim_height'] );
	update_post_meta( $post_id, '_samabar_description', $data['description'] );
	update_post_meta( $post_id, '_samabar_service', $data['service'] );
	update_post_meta( $post_id, '_samabar_total_price', $data['total_price'] );
	update_post_meta( $post_id, '_samabar_status', 'pending' );
	samabar_log_order_status( $post_id, 'pending' );
	update_post_meta( $post_id, '_samabar_order_number', samabar_generate_order_number( $post_id ) );
	update_post_meta( $post_id, '_samabar_full_name', $data['full_name'] );
	update_post_meta( $post_id, '_samabar_phone', $data['phone'] );
	update_post_meta( $post_id, '_samabar_company', $data['company'] );

	return $post_id;
}

/**
 * Generate display order number.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function samabar_generate_order_number( $post_id ) {
	return 'SB-' . str_pad( (string) $post_id, 5, '0', STR_PAD_LEFT );
}

/**
 * Get full order data array.
 *
 * @param int $post_id Post ID.
 * @return array|null
 */
function samabar_get_order( $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post || 'samabar_order' !== $post->post_type ) {
		return null;
	}

	$cargo_labels   = samabar_get_cargo_labels();
	$service_labels = samabar_get_service_labels();
	$status_labels  = samabar_get_order_statuses();

	$cargo_type = get_post_meta( $post_id, '_samabar_cargo_type', true );
	$service    = get_post_meta( $post_id, '_samabar_service', true );
	$status     = get_post_meta( $post_id, '_samabar_status', true ) ?: 'pending';

	return array(
		'id'            => $post_id,
		'order_number'  => get_post_meta( $post_id, '_samabar_order_number', true ),
		'origin'              => get_post_meta( $post_id, '_samabar_origin', true ),
		'destination'         => get_post_meta( $post_id, '_samabar_destination', true ),
		'origin_city'         => get_post_meta( $post_id, '_samabar_origin_city', true ),
		'origin_address'      => get_post_meta( $post_id, '_samabar_origin_address', true ),
		'origin_detail'       => get_post_meta( $post_id, '_samabar_origin_detail', true ),
		'destination_city'    => get_post_meta( $post_id, '_samabar_destination_city', true ),
		'destination_address' => get_post_meta( $post_id, '_samabar_destination_address', true ),
		'destination_detail'  => get_post_meta( $post_id, '_samabar_destination_detail', true ),
		'pickup_date'         => get_post_meta( $post_id, '_samabar_pickup_date', true ),
		'cargo_type'    => $cargo_type,
		'cargo_label'   => $cargo_labels[ $cargo_type ] ?? $cargo_type,
		'weight'        => (int) get_post_meta( $post_id, '_samabar_weight', true ),
		'dim_length'    => get_post_meta( $post_id, '_samabar_dim_length', true ),
		'dim_width'     => get_post_meta( $post_id, '_samabar_dim_width', true ),
		'dim_height'    => get_post_meta( $post_id, '_samabar_dim_height', true ),
		'description'   => get_post_meta( $post_id, '_samabar_description', true ),
		'service'       => $service,
		'service_label' => $service_labels[ $service ] ?? $service,
		'total_price'   => (int) get_post_meta( $post_id, '_samabar_total_price', true ),
		'status'        => $status,
		'status_label'  => $status_labels[ $status ] ?? $status,
		'full_name'     => get_post_meta( $post_id, '_samabar_full_name', true ),
		'phone'         => get_post_meta( $post_id, '_samabar_phone', true ),
		'company'       => get_post_meta( $post_id, '_samabar_company', true ),
		'created_at'    => get_the_date( 'Y/m/d H:i', $post ),
		'created_ts'    => strtotime( $post->post_date ),
	);
}

/**
 * REST: create order.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function samabar_rest_create_order( WP_REST_Request $request ) {
	$data    = samabar_sanitize_order_data( $request->get_json_params() );
	$post_id = samabar_create_order( $data );

	if ( is_wp_error( $post_id ) ) {
		return $post_id;
	}

	$order = samabar_get_order( $post_id );

	return new WP_REST_Response(
		array(
			'success'      => true,
			'order_id'     => $post_id,
			'order_number' => $order['order_number'],
			'message'      => __( 'سفارش با موفقیت ثبت شد.', 'samabar' ),
		),
		201
	);
}

/**
 * Daily pickup capacity (bookings per day).
 *
 * @return int
 */
function samabar_get_pickup_daily_capacity() {
	$capacity = (int) apply_filters( 'samabar_pickup_daily_capacity', 5 );

	return max( 1, $capacity );
}

/**
 * Count active pickup bookings grouped by Jalali date for a month.
 *
 * @param int $jy Jalali year.
 * @param int $jm Jalali month.
 * @return array<string, int>
 */
function samabar_count_pickups_by_day_for_month( $jy, $jm ) {
	$month_prefix = samabar_jalaali_date_key( $jy, $jm, 1 );
	$month_prefix = substr( $month_prefix, 0, 8 );

	$query = new WP_Query(
		array(
			'post_type'      => 'samabar_order',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => '_samabar_pickup_date',
					'value'   => $month_prefix,
					'compare' => 'LIKE',
				),
				array(
					'key'     => '_samabar_status',
					'value'   => 'cancelled',
					'compare' => '!=',
				),
			),
		)
	);

	$counts = array();

	foreach ( $query->posts as $post_id ) {
		$pickup   = get_post_meta( $post_id, '_samabar_pickup_date', true );
		$date_key = samabar_pickup_date_key( $pickup );

		if ( ! $date_key ) {
			continue;
		}

		if ( ! isset( $counts[ $date_key ] ) ) {
			$counts[ $date_key ] = 0;
		}

		++$counts[ $date_key ];
	}

	return $counts;
}

/**
 * Get pickup availability map for a Jalali month.
 *
 * @param int $jy Jalali year.
 * @param int $jm Jalali month.
 * @return array
 */
function samabar_get_pickup_availability( $jy, $jm ) {
	$capacity = samabar_get_pickup_daily_capacity();
	$bookings = samabar_count_pickups_by_day_for_month( $jy, $jm );
	$days     = array();

	$days_in_month = 31;
	if ( $jm >= 7 && $jm <= 11 ) {
		$days_in_month = 30;
	} elseif ( 12 === $jm ) {
		$days_in_month = samabar_is_jalaali_leap_year( $jy ) ? 30 : 29;
	}

	for ( $day = 1; $day <= $days_in_month; $day++ ) {
		$date_key = samabar_jalaali_date_key( $jy, $jm, $day );
		$booked   = $bookings[ $date_key ] ?? 0;

		if ( samabar_is_jalaali_date_before_today( $jy, $jm, $day ) ) {
			$status = 'past';
		} elseif ( $booked >= $capacity ) {
			$status = 'full';
		} else {
			$status = 'available';
		}

		$days[ $date_key ] = array(
			'booked'    => $booked,
			'capacity'  => $capacity,
			'remaining' => max( 0, $capacity - $booked ),
			'status'    => $status,
		);
	}

	return array(
		'year'     => (int) $jy,
		'month'    => (int) $jm,
		'capacity' => $capacity,
		'days'     => $days,
	);
}

/**
 * Whether a Jalali year is leap.
 *
 * @param int $jy Jalali year.
 * @return bool
 */
function samabar_is_jalaali_leap_year( $jy ) {
	$breaks = array( -61, 9, 38, 199, 426, 686, 756, 818, 1111, 1181, 1210, 1635, 2060, 2097, 2192, 2262, 2324, 2394, 2456, 3178 );
	$bl     = count( $breaks );
	$jp     = $breaks[0];

	if ( $jy < $jp || $jy >= $breaks[ $bl - 1 ] ) {
		return false;
	}

	for ( $i = 1; $i < $bl; $i++ ) {
		$jump = $breaks[ $i ] - $jp;
		if ( $jy < $breaks[ $i ] ) {
			break;
		}
		$jp = $breaks[ $i ];
	}

	$n = $jy - $jp;

	if ( $jump - $n < 6 ) {
		$n = $n - $jump + (int) ( ( $jump + 4 ) / 33 ) * 33;
	}

	$leap = ( ( ( $n + 1 ) % 33 ) - 1 ) % 4;
	if ( -1 === $leap ) {
		$leap = 4;
	}

	return 0 === $leap;
}

/**
 * Whether a pickup datetime can be booked.
 *
 * @param string $value Stored pickup value.
 * @return bool
 */
function samabar_is_pickup_datetime_available( $value ) {
	$date_key = samabar_pickup_date_key( $value );

	if ( ! $date_key || ! preg_match( '/^(\d{4})\/(\d{2})\/(\d{2})/', $date_key, $matches ) ) {
		return false;
	}

	$availability = samabar_get_pickup_availability( (int) $matches[1], (int) $matches[2] );

	return isset( $availability['days'][ $date_key ] ) && 'available' === $availability['days'][ $date_key ]['status'];
}

/**
 * REST: pickup availability for a month.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function samabar_rest_get_pickup_availability( WP_REST_Request $request ) {
	$year  = max( 1300, (int) $request->get_param( 'year' ) );
	$month = max( 1, min( 12, (int) $request->get_param( 'month' ) ) );

	return new WP_REST_Response( samabar_get_pickup_availability( $year, $month ), 200 );
}

/**
 * Register REST routes.
 */
function samabar_register_order_routes() {
	register_rest_route(
		'samabar/v1',
		'/orders',
		array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => 'samabar_rest_create_order',
			'permission_callback' => '__return_true',
		)
	);

	register_rest_route(
		'samabar/v1',
		'/pickup-availability',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'samabar_rest_get_pickup_availability',
			'permission_callback' => '__return_true',
			'args'                => array(
				'year'  => array(
					'required'          => true,
					'type'              => 'integer',
					'sanitize_callback' => 'absint',
				),
				'month' => array(
					'required'          => true,
					'type'              => 'integer',
					'sanitize_callback' => 'absint',
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'samabar_register_order_routes' );

/**
 * Count orders by status.
 *
 * @return array<string, int>
 */
function samabar_count_orders_by_status() {
	global $wpdb;

	$counts = array_fill_keys( array_keys( samabar_get_order_statuses() ), 0 );
	$rows   = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT pm.meta_value AS status, COUNT(*) AS total
			FROM {$wpdb->postmeta} pm
			INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
			WHERE pm.meta_key = %s
			AND p.post_type = %s
			AND p.post_status = 'publish'
			GROUP BY pm.meta_value",
			'_samabar_status',
			'samabar_order'
		)
	);

	foreach ( $rows as $row ) {
		if ( isset( $counts[ $row->status ] ) ) {
			$counts[ $row->status ] = (int) $row->total;
		}
	}

	return $counts;
}

/**
 * Query orders for admin list.
 *
 * @param array $args Query args.
 * @return array{items: array, total: int}
 */
function samabar_query_orders( $args = array() ) {
	$defaults = array(
		'status'   => '',
		'search'   => '',
		'paged'    => 1,
		'per_page' => 20,
	);

	$args = wp_parse_args( $args, $defaults );

	$query_args = array(
		'post_type'      => 'samabar_order',
		'post_status'    => 'publish',
		'posts_per_page' => (int) $args['per_page'],
		'paged'          => max( 1, (int) $args['paged'] ),
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	if ( $args['status'] ) {
		$query_args['meta_query'] = array(
			array(
				'key'   => '_samabar_status',
				'value' => sanitize_key( $args['status'] ),
			),
		);
	}

	if ( $args['search'] ) {
		$query_args['s'] = sanitize_text_field( $args['search'] );
	}

	$query = new WP_Query( $query_args );
	$items = array();

	foreach ( $query->posts as $post ) {
		$items[] = samabar_get_order( $post->ID );
	}

	return array(
		'items' => $items,
		'total' => (int) $query->found_posts,
		'pages' => (int) $query->max_num_pages,
	);
}

/**
 * Event titles shown on the public tracking timeline.
 *
 * @return array<string, string>
 */
function samabar_get_status_event_titles() {
	return array(
		'pending'     => 'ثبت سفارش در سیستم',
		'confirmed'   => 'تایید سفارش توسط کارشناس',
		'loaded'      => 'بارگیری تکمیل شد',
		'in_progress' => 'خروج محموله و حرکت به سمت مقصد',
		'delivered'   => 'تحویل محموله به گیرنده',
		'cancelled'   => 'سفارش لغو شد',
	);
}

/**
 * Append a status change to the order log.
 *
 * @param int    $post_id Post ID.
 * @param string $status  Status key.
 */
function samabar_log_order_status( $post_id, $status ) {
	$labels = samabar_get_order_statuses();
	if ( ! isset( $labels[ $status ] ) ) {
		return;
	}

	$log   = get_post_meta( $post_id, '_samabar_status_log', true );
	$log   = is_array( $log ) ? $log : array();
	$last  = end( $log );

	if ( $last && ( $last['status'] ?? '' ) === $status ) {
		return;
	}

	if ( empty( $log ) && 'pending' !== $status ) {
		$post = get_post( $post_id );
		if ( $post ) {
			$log[] = array(
				'status' => 'pending',
				'label'  => $labels['pending'],
				'time'   => strtotime( $post->post_date ),
			);
		}
	}

	$log[] = array(
		'status' => $status,
		'label'  => $labels[ $status ],
		'time'   => time(),
	);

	update_post_meta( $post_id, '_samabar_status_log', $log );
}

/**
 * Update order status.
 *
 * @param int    $post_id Post ID.
 * @param string $status  Status key.
 * @return bool
 */
function samabar_update_order_status( $post_id, $status ) {
	$statuses = samabar_get_order_statuses();
	if ( ! isset( $statuses[ $status ] ) ) {
		return false;
	}

	$current = get_post_meta( $post_id, '_samabar_status', true ) ?: 'pending';
	if ( $current === $status ) {
		return true;
	}

	update_post_meta( $post_id, '_samabar_status', $status );
	samabar_log_order_status( $post_id, $status );

	return true;
}

/**
 * Format pickup date for display (Persian digits).
 *
 * @param string $value Stored value.
 * @return string
 */
function samabar_format_pickup_date( $value ) {
	if ( ! $value ) {
		return '';
	}

	$persian_digits = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
	return str_replace( range( 0, 9 ), $persian_digits, $value );
}

/**
 * Format price for display.
 *
 * @param int $price Price in Rial.
 * @return string
 */
function samabar_format_price( $price ) {
	return number_format_i18n( $price ) . ' ﷼';
}

require get_template_directory() . '/inc/tracking.php';
require get_template_directory() . '/inc/admin/orders-page.php';
require get_template_directory() . '/inc/admin/pricing-page.php';
