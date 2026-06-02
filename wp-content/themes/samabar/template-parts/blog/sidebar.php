<?php
/**
 * Blog sidebar widgets.
 *
 * @package Samabar
 */

$blog_url      = samabar_get_blog_url();
$order_url     = samabar_get_order_url();
$categories    = samabar_get_blog_filter_categories();
$active_cat    = samabar_get_active_blog_category_slug();
$recent_query  = new WP_Query(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 4,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);
?>
<aside class="blog-sidebar" aria-label="<?php esc_attr_e( 'نوار کناری وبلاگ', 'samabar' ); ?>">
	<div class="blog-widget">
		<h2 class="blog-widget__title text-headline-md"><?php esc_html_e( 'دسته‌بندی', 'samabar' ); ?></h2>
		<ul class="blog-widget__list">
			<li>
				<a class="blog-widget__link<?php echo '' === $active_cat ? ' is-active' : ''; ?>" href="<?php echo esc_url( $blog_url ); ?>">
					<?php esc_html_e( 'همه مقالات', 'samabar' ); ?>
				</a>
			</li>
			<?php foreach ( $categories as $term ) : ?>
				<li>
					<a class="blog-widget__link<?php echo $active_cat === $term->slug ? ' is-active' : ''; ?>" href="<?php echo esc_url( samabar_get_blog_filter_url( array( 'category' => $term->slug ) ) ); ?>">
						<?php echo esc_html( $term->name ); ?>
						<span class="blog-widget__count"><?php echo esc_html( (string) $term->count ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>

	<?php if ( $recent_query->have_posts() ) : ?>
		<div class="blog-widget">
			<h2 class="blog-widget__title text-headline-md"><?php esc_html_e( 'آخرین مطالب', 'samabar' ); ?></h2>
			<ul class="blog-widget__posts">
				<?php
				while ( $recent_query->have_posts() ) :
					$recent_query->the_post();
					?>
					<li class="blog-widget__post">
						<a href="<?php the_permalink(); ?>">
							<strong><?php the_title(); ?></strong>
							<span><?php echo esc_html( samabar_format_post_date() ); ?></span>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>
		</div>
		<?php wp_reset_postdata(); ?>
	<?php endif; ?>

	<div class="blog-widget blog-widget--cta">
		<span class="material-symbols-outlined icon">local_shipping</span>
		<h2 class="text-headline-md"><?php esc_html_e( 'نیاز به حمل بار دارید؟', 'samabar' ); ?></h2>
		<p class="text-body-md"><?php esc_html_e( 'همین حالا سفارش خود را ثبت کنید و وضعیت را به صورت آنلاین پیگیری کنید.', 'samabar' ); ?></p>
		<a class="btn btn--secondary btn--block-mobile" href="<?php echo esc_url( $order_url ); ?>"><?php esc_html_e( 'ثبت سفارش', 'samabar' ); ?></a>
	</div>
</aside>
