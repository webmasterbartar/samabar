<?php
/**
 * Template Name: نقشه سایت
 * HTML sitemap page.
 *
 * @package Samabar
 */

get_header();

$sections = samabar_get_sitemap_sections();
?>

<main id="primary" class="site-main site-main--sitemap">
	<section class="static-hero static-hero--soft">
		<div class="container static-hero__inner">
			<h1 class="static-hero__title text-headline-xl">نقشه سایت</h1>
			<p class="static-hero__text text-body-lg">دسترسی سریع به تمام صفحات، خدمات و مطالب سما بار</p>
		</div>
	</section>

	<section class="sitemap-content">
		<div class="container sitemap-content__grid">
			<?php foreach ( $sections as $section ) : ?>
				<div class="sitemap-section">
					<h2 class="sitemap-section__title text-headline-md"><?php echo esc_html( $section['title'] ); ?></h2>
					<ul class="sitemap-section__list">
						<?php foreach ( $section['links'] as $link ) : ?>
							<li>
								<a class="sitemap-section__link" href="<?php echo esc_url( $link['url'] ); ?>">
									<span class="material-symbols-outlined icon" aria-hidden="true">chevron_left</span>
									<?php echo esc_html( $link['label'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
</main>

<?php
get_footer();
