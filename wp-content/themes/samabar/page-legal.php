<?php
/**
 * Template Name: صفحه قانونی
 * Privacy policy and terms pages.
 *
 * @package Samabar
 */

get_header();
?>

<main id="primary" class="site-main site-main--legal">
	<section class="static-hero static-hero--soft">
		<div class="container static-hero__inner">
			<h1 class="static-hero__title text-headline-xl"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="static-hero__text text-body-lg"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php else : ?>
				<p class="static-hero__text text-body-lg">آخرین به‌روزرسانی: <?php echo esc_html( get_the_modified_date( 'Y/m/d' ) ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<section class="legal-content">
		<div class="container legal-content__inner">
			<article class="legal-content__article">
				<?php
				while ( have_posts() ) :
					the_post();
					the_content();
				endwhile;
				?>
			</article>
			<aside class="legal-content__aside">
				<div class="legal-aside-card">
					<h2 class="text-headline-sm">نیاز به راهنمایی دارید؟</h2>
					<p class="text-body-md">تیم پشتیبانی <?php echo esc_html( samabar_get_contact_hours() ); ?> پاسخگوی شماست.</p>
					<a class="btn btn--secondary btn--block-mobile" href="<?php echo esc_url( samabar_get_contact_url() ); ?>">تماس با پشتیبانی</a>
				</div>
				<nav class="legal-aside-nav" aria-label="<?php esc_attr_e( 'صفحات مرتبط', 'samabar' ); ?>">
					<a href="<?php echo esc_url( samabar_get_privacy_url() ); ?>">حریم خصوصی</a>
					<a href="<?php echo esc_url( samabar_get_terms_url() ); ?>">قوانین و مقررات</a>
					<a href="<?php echo esc_url( samabar_get_sitemap_url() ); ?>">نقشه سایت</a>
				</nav>
			</aside>
		</div>
	</section>
</main>

<?php
get_footer();
