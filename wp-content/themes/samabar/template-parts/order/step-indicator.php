<?php
/**
 * Order step indicator.
 *
 * @package Samabar
 */

$step   = isset( $args['step'] ) ? (int) $args['step'] : 1;
$labels = array( 'مسیر', 'محموله', 'بررسی' );
?>
<div class="order-steps" aria-label="<?php esc_attr_e( 'مراحل ثبت سفارش', 'samabar' ); ?>">
	<span class="order-steps__mobile">مرحله <?php echo esc_html( (string) $step ); ?> از ۳</span>
	<ol class="order-steps__list">
		<?php foreach ( $labels as $index => $label ) : ?>
			<?php
			$num        = $index + 1;
			$is_active  = $num === $step;
			$is_done    = $num < $step;
			$item_class = 'order-steps__item';
			if ( $is_active ) {
				$item_class .= ' order-steps__item--active';
			} elseif ( $is_done ) {
				$item_class .= ' order-steps__item--done';
			}
			?>
			<li class="<?php echo esc_attr( $item_class ); ?>">
				<span class="order-steps__number"><?php echo esc_html( (string) $num ); ?></span>
				<span class="order-steps__label"><?php echo esc_html( $label ); ?></span>
			</li>
			<?php if ( $num < 3 ) : ?>
				<li class="order-steps__line<?php echo $num < $step ? ' order-steps__line--done' : ''; ?>" aria-hidden="true"></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ol>
</div>
