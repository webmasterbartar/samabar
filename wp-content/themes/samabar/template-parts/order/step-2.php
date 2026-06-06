<?php
/**
 * Order step 2: Cargo.
 *
 * @package Samabar
 */

$order_base = samabar_get_order_url();
$prev_url   = add_query_arg( 'step', '1', $order_base );
$next_url   = add_query_arg( 'step', '3', $order_base );
?>
<form class="order-form" id="order-form-step-2" action="<?php echo esc_url( $next_url ); ?>" method="get">
	<input type="hidden" name="step" value="3">

	<div class="order-step-intro">
		<h1 class="order-step-intro__title text-headline-lg">اطلاعات محموله</h1>
		<p class="order-step-intro__text text-body-md">وزن و جزئیات بار را وارد کنید تا کرایه نهایی محاسبه شود.</p>
	</div>

	<section class="order-panel">
		<label class="order-field">
			<span class="order-field__label">وزن تقریبی (کیلوگرم) <span class="order-section__req">*</span></span>
			<div class="order-field__suffix">
				<input class="order-field__input order-field__input--plain" type="number" name="weight" id="order-weight" min="1" placeholder="مثلاً: ۱۵۰۰" required>
				<span class="order-field__unit">KG</span>
			</div>
		</label>
		<div class="order-field">
			<span class="order-field__label">ابعاد تقریبی (اختیاری)</span>
			<div class="order-dims">
				<input class="order-field__input order-field__input--plain" type="number" name="dim_length" placeholder="طول (m)" step="0.1">
				<input class="order-field__input order-field__input--plain" type="number" name="dim_width" placeholder="عرض (m)" step="0.1">
				<input class="order-field__input order-field__input--plain" type="number" name="dim_height" placeholder="ارتفاع (m)" step="0.1">
			</div>
		</div>
		<label class="order-field">
			<span class="order-field__label">توضیحات تکمیلی (اختیاری)</span>
			<textarea class="order-field__textarea" name="description" rows="3" placeholder="هرگونه توضیحاتی که راننده باید در مورد بار بداند..."></textarea>
		</label>
	</section>

	<div class="order-actions order-actions--between">
		<a class="btn btn--outline" href="<?php echo esc_url( $prev_url ); ?>">
			<span class="material-symbols-outlined icon">arrow_forward</span>
			مرحله قبل
		</a>
		<button class="btn btn--secondary btn--lg" type="submit">
			مرحله بعد
			<span class="material-symbols-outlined icon">arrow_back</span>
		</button>
	</div>
</form>
