<?php
/**
 * Public order tracking helpers and REST API.
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Tracking step labels (display order).
 *
 * @return array<int, array{key: string, label: string}>
 */
function samabar_get_tracking_steps() {
	return array(
		array(
			'key'   => 'registered',
			'label' => 'ثبت سفارش',
		),
		array(
			'key'   => 'confirmed',
			'label' => 'تایید سیستم',
		),
		array(
			'key'   => 'loaded',
			'label' => 'بارگیری',
		),
		array(
			'key'   => 'transit',
			'label' => 'در مسیر',
		),
		array(
			'key'   => 'delivered',
			'label' => 'تحویل',
		),
	);
}

/**
 * Map order status to tracking progress.
 *
 * @param string $status Order status key.
 * @return array{current: int, percent: int, label: string}
 */
function samabar_get_tracking_progress( $status ) {
	$map = array(
		'pending'     => array(
			'current' => 1,
			'percent' => 20,
			'label'   => 'در انتظار بررسی',
		),
		'confirmed'   => array(
			'current' => 2,
			'percent' => 40,
			'label'   => 'تایید شده',
		),
		'loaded'      => array(
			'current' => 3,
			'percent' => 60,
			'label'   => 'بارگیری شده',
		),
		'in_progress' => array(
			'current' => 4,
			'percent' => 80,
			'label'   => 'در مسیر',
		),
		'delivered'   => array(
			'current' => 5,
			'percent' => 100,
			'label'   => 'تحویل شده',
		),
		'cancelled'   => array(
			'current' => 0,
			'percent' => 0,
			'label'   => 'لغو شده',
		),
	);

	return $map[ $status ] ?? $map['pending'];
}

/**
 * Format datetime for tracking timeline.
 *
 * @param int $timestamp Unix timestamp.
 * @return string
 */
function samabar_format_tracking_time( $timestamp ) {
	if ( ! $timestamp ) {
		return '';
	}

	return wp_date( 'Y/m/d H:i', $timestamp );
}

/**
 * Find order post ID by tracking number.
 *
 * @param string $number Order number e.g. SB-00012.
 * @return int Post ID or 0.
 */
function samabar_find_order_id_by_number( $number ) {
	$number = strtoupper( trim( (string) $number ) );

	if ( '' === $number ) {
		return 0;
	}

	$query = new WP_Query(
		array(
			'post_type'      => 'samabar_order',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_query'     => array(
				array(
					'key'   => '_samabar_order_number',
					'value' => $number,
				),
			),
		)
	);

	if ( ! empty( $query->posts[0] ) ) {
		return (int) $query->posts[0];
	}

	if ( preg_match( '/^SB-(\d+)$/i', $number, $matches ) ) {
		$post_id = (int) $matches[1];
		$order   = samabar_get_order( $post_id );
		if ( $order && strtoupper( $order['order_number'] ) === $number ) {
			return $post_id;
		}
	}

	return 0;
}

/**
 * Build timeline events for an order.
 *
 * @param array $order Order data from samabar_get_order().
 * @return array<int, array{title: string, time: string, active: bool}>
 */
function samabar_build_tracking_timeline( $order ) {
	$post_id = (int) ( $order['id'] ?? 0 );
	$status  = $order['status'] ?? 'pending';
	$log     = $post_id ? get_post_meta( $post_id, '_samabar_status_log', true ) : array();
	$titles  = samabar_get_status_event_titles();
	$events  = array();

	if ( is_array( $log ) && ! empty( $log ) ) {
		foreach ( $log as $index => $entry ) {
			$key = $entry['status'] ?? '';
			$events[] = array(
				'title'  => $titles[ $key ] ?? ( $entry['label'] ?? $key ),
				'time'   => samabar_format_tracking_time( (int) ( $entry['time'] ?? 0 ) ),
				'active' => $index === count( $log ) - 1,
			);
		}

		return array_reverse( $events );
	}

	$created = (int) ( $order['created_ts'] ?? 0 );

	$events[] = array(
		'title'  => $titles['pending'],
		'time'   => samabar_format_tracking_time( $created ),
		'active' => 'pending' === $status,
	);

	if ( in_array( $status, array( 'confirmed', 'loaded', 'in_progress', 'delivered' ), true ) ) {
		$events[] = array(
			'title'  => $titles['confirmed'],
			'time'   => samabar_format_tracking_time( $created + HOUR_IN_SECONDS * 2 ),
			'active' => 'confirmed' === $status,
		);
	}

	if ( in_array( $status, array( 'loaded', 'in_progress', 'delivered' ), true ) ) {
		$events[] = array(
			'title'  => $titles['loaded'],
			'time'   => samabar_format_tracking_time( $created + HOUR_IN_SECONDS * 4 ),
			'active' => 'loaded' === $status,
		);
	}

	if ( in_array( $status, array( 'in_progress', 'delivered' ), true ) ) {
		$events[] = array(
			'title'  => $titles['in_progress'],
			'time'   => samabar_format_tracking_time( $created + HOUR_IN_SECONDS * 6 ),
			'active' => 'in_progress' === $status,
		);
	}

	if ( 'delivered' === $status ) {
		$events[] = array(
			'title'  => $titles['delivered'],
			'time'   => samabar_format_tracking_time( $created + DAY_IN_SECONDS ),
			'active' => true,
		);
	}

	if ( 'cancelled' === $status ) {
		$events = array(
			array(
				'title'  => $titles['pending'],
				'time'   => samabar_format_tracking_time( $created ),
				'active' => false,
			),
			array(
				'title'  => $titles['cancelled'],
				'time'   => samabar_format_tracking_time( $created + HOUR_IN_SECONDS ),
				'active' => true,
			),
		);
	}

	return array_reverse( $events );
}

/**
 * Public tracking payload for frontend.
 *
 * @param int $post_id Order post ID.
 * @return array|null
 */
function samabar_get_order_tracking( $post_id ) {
	$order = samabar_get_order( $post_id );
	if ( ! $order ) {
		return null;
	}

	$progress = samabar_get_tracking_progress( $order['status'] );
	$driver   = array(
		'name'  => get_post_meta( $post_id, '_samabar_driver_name', true ),
		'phone' => get_post_meta( $post_id, '_samabar_driver_phone', true ),
		'plate' => get_post_meta( $post_id, '_samabar_driver_plate', true ),
	);

	$has_driver = ! empty( $driver['name'] );

	return array(
		'found'         => true,
		'order_number'  => $order['order_number'],
		'status'        => $order['status'],
		'status_label'  => $progress['label'],
		'current_step'  => $progress['current'],
		'progress'      => $progress['percent'],
		'steps'         => samabar_get_tracking_steps(),
		'origin'        => $order['origin'],
		'destination'   => $order['destination'],
		'cargo_label'   => $order['cargo_label'],
		'service_label' => $order['service_label'],
		'weight'        => $order['weight'],
		'pickup_date'   => $order['pickup_date'] ? samabar_format_pickup_date( $order['pickup_date'] ) : '',
		'timeline'      => samabar_build_tracking_timeline( $order ),
		'driver'        => $has_driver ? array(
			'name'  => $driver['name'],
			'phone' => $driver['phone'],
			'plate' => $driver['plate'],
		) : null,
		'is_cancelled'  => 'cancelled' === $order['status'],
	);
}

/**
 * REST: track order by number.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response
 */
function samabar_rest_track_order( WP_REST_Request $request ) {
	$number  = sanitize_text_field( $request->get_param( 'number' ) );
	$post_id = samabar_find_order_id_by_number( $number );

	if ( ! $post_id ) {
		return new WP_REST_Response(
			array(
				'found'   => false,
				'message' => __( 'سفارشی با این کد پیگیری یافت نشد.', 'samabar' ),
			),
			404
		);
	}

	return new WP_REST_Response( samabar_get_order_tracking( $post_id ), 200 );
}

/**
 * Register tracking REST route.
 */
function samabar_register_tracking_routes() {
	register_rest_route(
		'samabar/v1',
		'/track',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'samabar_rest_track_order',
			'permission_callback' => '__return_true',
			'args'                => array(
				'number' => array(
					'required'          => true,
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'samabar_register_tracking_routes' );
