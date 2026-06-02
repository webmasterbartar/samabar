<?php
/**
 * Template Name: ثبت سفارش
 * Three-step order registration flow.
 *
 * @package Samabar
 */

$step = max( 1, min( 3, (int) ( $_GET['step'] ?? 1 ) ) );

get_header();
?>

<main id="primary" class="site-main site-main--order" data-order-step="<?php echo esc_attr( (string) $step ); ?>">

	<div class="container order-main">
		<?php get_template_part( 'template-parts/order/step-indicator', null, array( 'step' => $step ) ); ?>

		<?php
		if ( 1 === $step ) {
			get_template_part( 'template-parts/order/step', '1' );
		} elseif ( 2 === $step ) {
			get_template_part( 'template-parts/order/step', '2' );
		} else {
			get_template_part( 'template-parts/order/step', '3' );
		}
		?>
	</div>
</main>

<?php
get_footer();
