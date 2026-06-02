<?php
/**
 * Minimal header for order flow.
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$home_url   = home_url( '/' );
$order_url  = samabar_get_order_url();
$order_step = max( 1, min( 3, (int) ( $_GET['step'] ?? 1 ) ) );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'order-flow' ); ?>>
<?php wp_body_open(); ?>

<header class="order-header">
	<div class="container order-header__inner">
		<a class="order-header__brand" href="<?php echo esc_url( $home_url ); ?>">
			<span class="material-symbols-outlined icon icon--filled">local_shipping</span>
			<span class="order-header__title">سما بار</span>
		</a>

		<?php get_template_part( 'template-parts/order/step-indicator', null, array( 'step' => $order_step ) ); ?>

		<a class="order-header__close" href="<?php echo esc_url( $home_url ); ?>" aria-label="<?php esc_attr_e( 'بستن و بازگشت', 'samabar' ); ?>">
			<span class="material-symbols-outlined icon">close</span>
		</a>
	</div>
</header>
