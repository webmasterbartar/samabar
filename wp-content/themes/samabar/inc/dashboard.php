<?php
/**
 * Customer dashboard helpers and REST API.
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Active order statuses for dashboard lists.
 *
 * @return array<int, string>
 */
function samabar_get_dashboard_active_statuses() {
	return array( 'pending', 'confirmed', 'loaded', 'in_progress' );
}

/**
 * Completed order statuses.
 *
 * @return array<int, string>
 */
function samabar_get_dashboard_completed_statuses() {
	return array( 'delivered' );
}

/**
 * Query orders by customer phone.
 *
 * @param string $phone Sanitized phone.
 * @return array<int, array>
 */
function samabar_get_customer_orders( $phone ) {
	$phone = samabar_sanitize_phone( $phone );
	if ( ! samabar_validate_phone( $phone ) ) {
		return array();
	}

	$query = new WP_Query(
		array(
			'post_type'      => 'samabar_order',
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'meta_query'     => array(
				array(
					'key'   => '_samabar_phone',
					'value' => $phone,
				),
			),
		)
	);

	$orders = array();
	foreach ( $query->posts as $post ) {
		$order = samabar_get_order( $post->ID );
		if ( $order ) {
			$orders[] = $order;
		}
	}

	return $orders;
}

/**
 * Short city label for dashboard cards.
 *
 * @param array  $order   Order data.
 * @param string $which   origin|destination.
 * @return string
 */
function samabar_dashboard_route_label( $order, $which ) {
	if ( 'origin' === $which ) {
		return $order['origin_city'] ?: $order['origin'];
	}

	return $order['destination_city'] ?: $order['destination'];
}

/**
 * Format order for dashboard list/card.
 *
 * @param array $order Order data.
 * @return array
 */
function samabar_format_dashboard_order( $order ) {
	$progress = samabar_get_tracking_progress( $order['status'] );

	return array(
		'id'            => $order['id'],
		'order_number'  => $order['order_number'],
		'origin'        => samabar_dashboard_route_label( $order, 'origin' ),
		'destination'   => samabar_dashboard_route_label( $order, 'destination' ),
		'status'        => $order['status'],
		'status_label'  => $progress['label'],
		'progress'      => $progress['percent'],
		'total_price'   => $order['total_price'],
		'price_label'   => samabar_format_price( $order['total_price'] ),
		'service_label' => $order['service_label'],
		'cargo_label'   => $order['cargo_label'],
		'pickup_date'   => $order['pickup_date'] ? samabar_format_pickup_date( $order['pickup_date'] ) : '',
		'created_at'    => $order['created_at'],
		'tracking_url'  => samabar_get_tracking_url( $order['order_number'] ),
	);
}

/**
 * Build dashboard stats from customer orders.
 *
 * @param array $orders Customer orders.
 * @return array
 */
function samabar_build_dashboard_stats( $orders ) {
	$active_statuses     = samabar_get_dashboard_active_statuses();
	$completed_statuses  = samabar_get_dashboard_completed_statuses();
	$active              = 0;
	$completed           = 0;
	$month_spend         = 0;
	$total_spent         = 0;
	$delivery_durations  = array();
	$month_start         = strtotime( wp_date( 'Y-m-01' ) );

	foreach ( $orders as $order ) {
		$status = $order['status'] ?? 'pending';
		$price  = (int) ( $order['total_price'] ?? 0 );
		$total_spent += $price;

		if ( in_array( $status, $active_statuses, true ) ) {
			++$active;
		}

		if ( in_array( $status, $completed_statuses, true ) ) {
			++$completed;

			$log = get_post_meta( $order['id'], '_samabar_status_log', true );
			if ( is_array( $log ) ) {
				foreach ( $log as $entry ) {
					if ( 'delivered' === ( $entry['status'] ?? '' ) && ! empty( $entry['time'] ) ) {
						$duration = ( (int) $entry['time'] - (int) $order['created_ts'] ) / DAY_IN_SECONDS;
						if ( $duration > 0 ) {
							$delivery_durations[] = $duration;
						}
						break;
					}
				}
			}
		}

		if ( ! empty( $order['created_ts'] ) && $order['created_ts'] >= $month_start ) {
			$month_spend += (int) ( $order['total_price'] ?? 0 );
		}
	}

	$avg_delivery = 0;
	if ( ! empty( $delivery_durations ) ) {
		$avg_delivery = round( array_sum( $delivery_durations ) / count( $delivery_durations ), 1 );
	}

	return array(
		'active'            => $active,
		'completed'         => $completed,
		'month_spend'       => $month_spend,
		'total_spent'       => $total_spent,
		'avg_delivery_days' => $avg_delivery,
		'total_orders'      => count( $orders ),
	);
}

/**
 * Dashboard payload for a phone number.
 *
 * @param string $phone Sanitized phone.
 * @return array|null
 */
function samabar_get_dashboard_data( $phone ) {
	$phone  = samabar_sanitize_phone( $phone );
	$orders = samabar_get_customer_orders( $phone );

	if ( empty( $orders ) ) {
		return null;
	}

	$active_statuses = samabar_get_dashboard_active_statuses();
	$done_statuses   = array_merge( samabar_get_dashboard_completed_statuses(), array( 'cancelled' ) );

	$active_orders = array();
	$history       = array();
	$featured      = null;

	foreach ( $orders as $order ) {
		$formatted = samabar_format_dashboard_order( $order );

		if ( in_array( $order['status'], $active_statuses, true ) ) {
			$active_orders[] = $formatted;
			if ( ! $featured || 'in_progress' === $order['status'] ) {
				$featured = $formatted;
			}
		} elseif ( in_array( $order['status'], $done_statuses, true ) ) {
			$history[] = $formatted;
		}
	}

	if ( ! $featured && ! empty( $active_orders ) ) {
		$featured = $active_orders[0];
	}

	$latest   = $orders[0];
	$payments = array();
	$stats    = samabar_build_dashboard_stats( $orders );

	foreach ( array_slice( $orders, 0, 8 ) as $order ) {
		$payments[] = array(
			'order_number' => $order['order_number'],
			'created_at'   => $order['created_at'],
			'price_label'  => samabar_format_price( $order['total_price'] ),
			'status_label' => samabar_get_tracking_progress( $order['status'] )['label'],
		);
	}

	return array(
		'found'         => true,
		'phone'         => $phone,
		'customer_name' => $latest['full_name'] ?: __( 'مشتری', 'samabar' ),
		'company'       => $latest['company'] ?: '',
		'profile'       => array(
			'phone'        => $phone,
			'full_name'    => $latest['full_name'] ?: '',
			'company'      => $latest['company'] ?: '',
			'total_orders' => count( $orders ),
		),
		'stats'         => $stats,
		'payments'      => array(
			'month_spend' => $stats['month_spend'],
			'total_spent' => $stats['total_spent'],
			'recent'      => $payments,
		),
		'active_orders' => $active_orders,
		'history'       => $history,
		'featured'      => $featured,
	);
}

/**
 * REST: customer dashboard by phone.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function samabar_rest_get_dashboard( WP_REST_Request $request ) {
	$phone = samabar_sanitize_phone( $request->get_param( 'phone' ) );

	if ( ! samabar_validate_phone( $phone ) ) {
		return new WP_Error(
			'invalid_phone',
			__( 'شماره موبایل معتبر نیست.', 'samabar' ),
			array( 'status' => 400 )
		);
	}

	$data = samabar_get_dashboard_data( $phone );

	if ( ! $data ) {
		return new WP_REST_Response(
			array(
				'found'   => false,
				'message' => __( 'سفارشی با این شماره موبایل یافت نشد.', 'samabar' ),
			),
			404
		);
	}

	return new WP_REST_Response( $data, 200 );
}

/**
 * Register dashboard REST route.
 */
function samabar_register_dashboard_routes() {
	register_rest_route(
		'samabar/v1',
		'/dashboard',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'samabar_rest_get_dashboard',
			'permission_callback' => '__return_true',
			'args'                => array(
				'phone' => array(
					'required'          => true,
					'type'              => 'string',
					'sanitize_callback' => 'samabar_sanitize_phone',
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'samabar_register_dashboard_routes' );
