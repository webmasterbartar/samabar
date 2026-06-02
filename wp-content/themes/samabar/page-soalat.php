<?php
/**
 * Template Name: سوالات متداول
 * FAQ page.
 *
 * @package Samabar
 */

get_header();

$categories = samabar_get_faq_categories();
$items      = samabar_get_faq_items();
$contact_url = samabar_get_contact_url();
$default_cat = 'order';
?>

<main id="primary" class="site-main site-main--faq">
	<section class="faq-hero">
		<div class="container faq-hero__inner">
			<h1 class="faq-hero__title text-headline-xl">سوالات متداول</h1>
			<p class="faq-hero__text text-body-lg">پاسخ پرسش‌های خود را درباره خدمات حمل و نقل، قیمت‌گذاری و روند کار سما بار بیابید.</p>
			<div class="faq-search">
				<span class="material-symbols-outlined icon faq-search__icon" aria-hidden="true">search</span>
				<input class="faq-search__input" type="search" id="faq-search" placeholder="جستجو در سوالات..." autocomplete="off">
			</div>
		</div>
	</section>

	<section class="faq-categories">
		<div class="container">
			<div class="faq-categories__grid" role="tablist" aria-label="<?php esc_attr_e( 'دسته‌بندی سوالات', 'samabar' ); ?>">
				<?php foreach ( $categories as $slug => $cat ) : ?>
					<button
						type="button"
						class="faq-category<?php echo $slug === $default_cat ? ' faq-category--active' : ''; ?>"
						data-faq-category="<?php echo esc_attr( $slug ); ?>"
						role="tab"
						aria-selected="<?php echo $slug === $default_cat ? 'true' : 'false'; ?>"
					>
						<span class="material-symbols-outlined icon" aria-hidden="true"><?php echo esc_html( $cat['icon'] ); ?></span>
						<span><?php echo esc_html( $cat['label'] ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="faq-content">
		<div class="container faq-content__grid">
			<aside class="faq-content__aside">
				<h2 class="text-headline-lg" data-faq-aside-title><?php echo esc_html( $categories[ $default_cat ]['label'] ); ?></h2>
				<p class="text-body-md" data-faq-aside-desc><?php echo esc_html( $categories[ $default_cat ]['description'] ); ?></p>
				<div class="faq-support-card">
					<div class="faq-support-card__head">
						<span class="material-symbols-outlined icon icon--filled">support_agent</span>
						<strong>نیاز به راهنمایی بیشتر دارید؟</strong>
					</div>
					<p class="text-label-sm">پشتیبانی در <?php echo esc_html( samabar_get_contact_hours() ); ?> پاسخگوی شماست.</p>
					<a class="btn btn--outline btn--block-mobile" href="<?php echo esc_url( samabar_get_contact_url() ); ?>">تماس با پشتیبانی</a>
				</div>
			</aside>

			<div class="faq-accordion" id="faq-accordion" data-faq-default="<?php echo esc_attr( $default_cat ); ?>">
				<?php foreach ( $items as $index => $item ) : ?>
					<details
						class="faq-accordion__item"
						data-faq-item
						data-faq-category="<?php echo esc_attr( $item['category'] ); ?>"
						<?php echo ( 0 === $index && $item['category'] === $default_cat ) ? 'open' : ''; ?>
						<?php echo $item['category'] !== $default_cat ? 'hidden' : ''; ?>
					>
						<summary class="faq-accordion__summary">
							<span><?php echo esc_html( $item['question'] ); ?></span>
							<span class="material-symbols-outlined icon faq-accordion__chevron" aria-hidden="true">expand_more</span>
						</summary>
						<div class="faq-accordion__body">
							<p class="text-body-md"><?php echo esc_html( $item['answer'] ); ?></p>
						</div>
					</details>
				<?php endforeach; ?>
				<p class="faq-empty" id="faq-empty" hidden>سوالی با این عبارت یافت نشد.</p>
			</div>
		</div>
	</section>
</main>

<script type="application/json" id="faq-categories-data"><?php echo wp_json_encode( $categories, JSON_UNESCAPED_UNICODE ); ?></script>

<?php
get_footer();
