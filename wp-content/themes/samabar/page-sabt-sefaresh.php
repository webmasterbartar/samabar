<?php
/**
 * Template Name: ثبت سفارش
 * Single-page order registration.
 *
 * @package Samabar
 */

if ( ! empty( $_GET['step'] ) && (int) $_GET['step'] > 1 ) {
	wp_safe_redirect( samabar_get_order_url() );
	exit;
}

get_header();
?>

<main id="primary" class="site-main site-main--order">

	<div class="container order-main">
		<?php get_template_part( 'template-parts/order/form' ); ?>
	</div>
</main>

<?php
get_footer();
