<?php
/**
 * Contact form REST API.
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * REST: submit contact message.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function samabar_rest_submit_contact( WP_REST_Request $request ) {
	$name    = sanitize_text_field( $request->get_param( 'full_name' ) );
	$phone   = samabar_sanitize_phone( $request->get_param( 'phone' ) );
	$subject = sanitize_text_field( $request->get_param( 'subject' ) );
	$message = sanitize_textarea_field( $request->get_param( 'message' ) );

	if ( ! $name || ! $subject || ! $message ) {
		return new WP_Error(
			'missing_fields',
			__( 'لطفاً تمام فیلدهای الزامی را پر کنید.', 'samabar' ),
			array( 'status' => 400 )
		);
	}

	if ( $phone && ! samabar_validate_phone( $phone ) ) {
		return new WP_Error(
			'invalid_phone',
			__( 'شماره موبایل معتبر نیست.', 'samabar' ),
			array( 'status' => 400 )
		);
	}

	$to      = get_option( 'admin_email' );
	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	$body    = sprintf(
		"نام: %s\nتلفن: %s\nموضوع: %s\n\n%s",
		$name,
		$phone ?: '—',
		$subject,
		$message
	);

	$sent = wp_mail(
		$to,
		sprintf( '[سما بار] %s', $subject ),
		$body,
		$headers
	);

	if ( ! $sent ) {
		return new WP_Error(
			'mail_failed',
			__( 'ارسال پیام انجام نشد. لطفاً با تلفن تماس بگیرید.', 'samabar' ),
			array( 'status' => 500 )
		);
	}

	return new WP_REST_Response(
		array(
			'success' => true,
			'message' => __( 'پیام شما با موفقیت ارسال شد. به زودی با شما تماس می‌گیریم.', 'samabar' ),
		),
		200
	);
}

/**
 * Register contact REST route.
 */
function samabar_register_contact_routes() {
	register_rest_route(
		'samabar/v1',
		'/contact',
		array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => 'samabar_rest_submit_contact',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'samabar_register_contact_routes' );
