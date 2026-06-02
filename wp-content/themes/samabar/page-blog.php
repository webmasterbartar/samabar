<?php
/**
 * Template Name: وبلاگ
 * Blog listing page.
 *
 * @package Samabar
 */

get_header();

$blog_url     = samabar_get_blog_url();
$active_cat   = samabar_get_active_blog_category_slug();
$search_query = samabar_get_blog_search_query();
$paged        = samabar_get_blog_paged();

$query = new WP_Query( samabar_get_blog_query_args() );
$posts = $query->posts;
$featured = ! empty( $posts ) && 1 === $paged && '' === $search_query ? array_shift( $posts ) : null;
?>

<main id="primary" class="site-main site-main--blog">
	<section class="blog-hero-band">
		<div class="container">
			<div class="blog-hero">
				<h1 class="blog-hero__title text-headline-xl">وبلاگ و منابع سما بار</h1>
				<p class="blog-hero__text text-body-lg">جدیدترین اخبار، مقالات تخصصی و تحلیل‌های صنعت لجستیک و حمل و نقل B2B را در اینجا دنبال کنید.</p>
				<form class="blog-search" action="<?php echo esc_url( $blog_url ); ?>" method="get" role="search">
					<?php if ( $active_cat ) : ?>
						<input type="hidden" name="category" value="<?php echo esc_attr( $active_cat ); ?>">
					<?php endif; ?>
					<span class="material-symbols-outlined icon blog-search__icon" aria-hidden="true">search</span>
					<input
						class="blog-search__input"
						type="search"
						name="s"
						value="<?php echo esc_attr( $search_query ); ?>"
						placeholder="جستجو در مقالات..."
						autocomplete="off"
					>
					<button class="btn btn--primary blog-search__submit" type="submit">جستجو</button>
				</form>
			</div>
		</div>
	</section>

	<div class="container blog-page">
		<div class="blog-layout">
			<div class="blog-layout__main">
				<?php if ( $featured ) : ?>
					<?php
					get_template_part(
						'template-parts/blog/featured',
						null,
						array( 'post' => $featured )
					);
					?>
				<?php endif; ?>

				<section class="blog-list">
					<div class="blog-list__head">
						<h2 class="text-headline-md">
							<?php
							if ( $search_query ) {
								echo esc_html( 'نتایج جستجو برای «' . $search_query . '»' );
							} elseif ( $active_cat ) {
								$term = get_category_by_slug( $active_cat );
								echo esc_html( $term ? $term->name : __( 'آخرین مقالات', 'samabar' ) );
							} else {
								esc_html_e( 'آخرین مقالات', 'samabar' );
							}
							?>
						</h2>
						<div class="blog-filters" role="tablist" aria-label="<?php esc_attr_e( 'فیلتر دسته‌بندی', 'samabar' ); ?>">
							<a class="blog-filter<?php echo '' === $active_cat ? ' blog-filter--active' : ''; ?>" href="<?php echo esc_url( $blog_url ); ?>">همه</a>
							<?php foreach ( samabar_get_blog_filter_categories() as $term ) : ?>
								<a
									class="blog-filter<?php echo $active_cat === $term->slug ? ' blog-filter--active' : ''; ?>"
									href="<?php echo esc_url( samabar_get_blog_filter_url( array( 'category' => $term->slug ) ) ); ?>"
								>
									<?php echo esc_html( $term->name ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					</div>

					<?php if ( ! empty( $posts ) ) : ?>
						<div class="blog-grid">
							<?php
							foreach ( $posts as $post_item ) {
								get_template_part(
									'template-parts/blog/card',
									null,
									array( 'post' => $post_item )
								);
							}
							?>
						</div>

						<?php if ( $query->max_num_pages > 1 ) : ?>
							<nav class="blog-pagination" aria-label="<?php esc_attr_e( 'صفحه‌بندی', 'samabar' ); ?>">
								<?php
								echo paginate_links(
									array(
										'total'     => $query->max_num_pages,
										'current'   => $paged,
										'base'      => samabar_get_blog_pagination_base(),
										'format'    => get_option( 'permalink_structure' ) ? 'page/%#%/' : '',
										'prev_text' => '<span class="material-symbols-outlined icon">chevron_right</span>',
										'next_text' => '<span class="material-symbols-outlined icon">chevron_left</span>',
										'add_args'  => array_filter(
											array(
												'category' => $active_cat ?: false,
												's'        => $search_query ?: false,
											)
										),
									)
								);
								?>
							</nav>
						<?php endif; ?>
					<?php else : ?>
						<div class="blog-empty">
							<span class="material-symbols-outlined icon">article</span>
							<h3 class="text-headline-md"><?php esc_html_e( 'مقاله‌ای یافت نشد', 'samabar' ); ?></h3>
							<p class="text-body-md"><?php esc_html_e( 'عبارت جستجو یا فیلتر دیگری امتحان کنید.', 'samabar' ); ?></p>
							<a class="btn btn--outline" href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'مشاهده همه مقالات', 'samabar' ); ?></a>
						</div>
					<?php endif; ?>
				</section>

				<section class="blog-newsletter" id="blog-newsletter">
					<div class="blog-newsletter__text">
						<h2 class="text-headline-lg">
							<span class="material-symbols-outlined icon">mark_email_read</span>
							عضویت در خبرنامه
						</h2>
						<p class="text-body-md">برای دریافت آخرین اخبار صنعت لجستیک و مقالات تخصصی سما بار، ایمیل خود را وارد کنید.</p>
					</div>
					<form class="blog-newsletter__form" id="blog-newsletter-form" action="#" method="post">
						<label class="blog-newsletter__field">
							<span class="material-symbols-outlined icon">mail</span>
							<input type="email" name="email" placeholder="آدرس ایمیل شما" dir="ltr" required>
						</label>
						<button class="btn btn--primary" type="submit">عضویت</button>
						<p class="blog-newsletter__notice" id="blog-newsletter-notice" hidden></p>
					</form>
				</section>
			</div>

			<?php get_template_part( 'template-parts/blog/sidebar' ); ?>
		</div>
	</div>
</main>

<?php
wp_reset_postdata();
get_footer();
