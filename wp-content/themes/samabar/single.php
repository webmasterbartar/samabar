<?php
/**
 * Single blog post template.
 *
 * @package Samabar
 */

get_header();

$blog_url = samabar_get_blog_url();
?>

<div class="blog-read-progress" id="blog-read-progress" aria-hidden="true"></div>

<main id="primary" class="site-main site-main--blog-single">
	<?php
	while ( have_posts() ) :
		the_post();

		$post_id    = get_the_ID();
		$category   = samabar_get_primary_category( $post_id );
		$tags       = get_the_tags( $post_id );
		$related    = samabar_get_related_posts( $post_id, 3 );
		$adjacent   = samabar_get_blog_adjacent_posts( $post_id );
		$excerpt    = has_excerpt( $post_id ) ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 36 );
		$thumb_url  = samabar_get_post_thumbnail_url( $post_id, 'large' );
		$order_url  = samabar_get_order_url();
		$author_id  = (int) get_the_author_meta( 'ID' );
		$author_bio = samabar_get_blog_author_bio( $author_id );
		?>
		<section class="blog-single-hero<?php echo $thumb_url ? ' blog-single-hero--has-image' : ''; ?>">
			<?php if ( $thumb_url ) : ?>
				<div class="blog-single-hero__bg" style="background-image: url('<?php echo esc_url( $thumb_url ); ?>');" aria-hidden="true"></div>
			<?php endif; ?>
			<div class="blog-single-hero__overlay" aria-hidden="true"></div>
			<div class="container blog-single-hero__inner">
				<nav class="blog-breadcrumb blog-breadcrumb--hero" aria-label="<?php esc_attr_e( 'مسیر صفحه', 'samabar' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'صفحه اصلی', 'samabar' ); ?></a>
					<span aria-hidden="true">/</span>
					<a href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'وبلاگ', 'samabar' ); ?></a>
					<?php if ( $category ) : ?>
						<span aria-hidden="true">/</span>
						<a href="<?php echo esc_url( samabar_get_blog_filter_url( array( 'category' => $category->slug ) ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
					<?php endif; ?>
				</nav>

				<div class="blog-single-hero__content">
					<?php if ( $category ) : ?>
						<a class="blog-card__tag blog-card__tag--hero" href="<?php echo esc_url( samabar_get_blog_filter_url( array( 'category' => $category->slug ) ) ); ?>">
							<?php echo esc_html( $category->name ); ?>
						</a>
					<?php endif; ?>
					<h1 class="blog-single-hero__title text-headline-xl"><?php the_title(); ?></h1>
					<div class="blog-article__meta blog-article__meta--hero">
						<span class="blog-article__meta-item">
							<span class="material-symbols-outlined icon">person</span>
							<?php the_author(); ?>
						</span>
						<span class="blog-article__meta-item">
							<span class="material-symbols-outlined icon">calendar_today</span>
							<?php echo esc_html( samabar_format_post_date( $post_id ) ); ?>
						</span>
						<span class="blog-article__meta-item">
							<span class="material-symbols-outlined icon">schedule</span>
							<?php echo esc_html( samabar_estimate_reading_time( get_the_content() ) ); ?>
						</span>
					</div>
				</div>
			</div>
		</section>

		<div class="container blog-single">
			<div class="blog-single__layout">
				<article <?php post_class( 'blog-article blog-article--card' ); ?>>
					<?php if ( $excerpt ) : ?>
						<p class="blog-article__lead text-body-lg"><?php echo esc_html( $excerpt ); ?></p>
					<?php endif; ?>

					<div class="blog-article__content" id="blog-article-content">
						<?php the_content(); ?>
					</div>

					<footer class="blog-article__footer">
						<?php if ( ! empty( $tags ) ) : ?>
							<div class="blog-article__tags">
								<span class="blog-article__tags-label"><?php esc_html_e( 'برچسب‌ها:', 'samabar' ); ?></span>
								<?php foreach ( $tags as $tag ) : ?>
									<a class="blog-article__tag" href="<?php echo esc_url( get_tag_link( $tag ) ); ?>"><?php echo esc_html( $tag->name ); ?></a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<div class="blog-article__share">
							<div class="blog-article__share-label">
								<span class="material-symbols-outlined icon">share</span>
								<span><?php esc_html_e( 'اشتراک‌گذاری', 'samabar' ); ?></span>
							</div>
							<div class="blog-article__share-actions">
								<a class="blog-article__share-link" href="<?php echo esc_url( 'https://t.me/share/url?url=' . rawurlencode( get_permalink() ) . '&text=' . rawurlencode( get_the_title() ) ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Telegram">
									<span class="material-symbols-outlined icon">send</span>
								</a>
								<a class="blog-article__share-link" href="<?php echo esc_url( 'https://wa.me/?text=' . rawurlencode( get_the_title() . ' ' . get_permalink() ) ); ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
									<span class="material-symbols-outlined icon">chat</span>
								</a>
								<button type="button" class="blog-article__share-link" data-copy-link="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php esc_attr_e( 'کپی لینک', 'samabar' ); ?>">
									<span class="material-symbols-outlined icon">link</span>
								</button>
							</div>
							<p class="blog-article__share-notice" id="blog-copy-notice" hidden><?php esc_html_e( 'لینک مقاله کپی شد.', 'samabar' ); ?></p>
						</div>
					</footer>

					<div class="blog-author">
						<span class="blog-author__avatar">
							<?php echo get_avatar( $author_id, 96, '', '', array( 'class' => 'blog-author__avatar-img' ) ); ?>
						</span>
						<div>
							<strong class="blog-author__name"><?php the_author(); ?></strong>
							<p class="blog-author__bio text-body-md"><?php echo esc_html( $author_bio ); ?></p>
						</div>
					</div>

					<?php if ( $adjacent['prev'] || $adjacent['next'] ) : ?>
						<nav class="blog-post-nav" aria-label="<?php esc_attr_e( 'مقالات قبلی و بعدی', 'samabar' ); ?>">
							<?php if ( $adjacent['next'] ) : ?>
								<a class="blog-post-nav__link blog-post-nav__link--next" href="<?php echo esc_url( get_permalink( $adjacent['next'] ) ); ?>">
									<span class="blog-post-nav__label"><?php esc_html_e( 'مقاله بعدی', 'samabar' ); ?></span>
									<strong><?php echo esc_html( get_the_title( $adjacent['next'] ) ); ?></strong>
								</a>
							<?php else : ?>
								<span class="blog-post-nav__link blog-post-nav__link--empty"></span>
							<?php endif; ?>

							<?php if ( $adjacent['prev'] ) : ?>
								<a class="blog-post-nav__link blog-post-nav__link--prev" href="<?php echo esc_url( get_permalink( $adjacent['prev'] ) ); ?>">
									<span class="blog-post-nav__label"><?php esc_html_e( 'مقاله قبلی', 'samabar' ); ?></span>
									<strong><?php echo esc_html( get_the_title( $adjacent['prev'] ) ); ?></strong>
								</a>
							<?php else : ?>
								<span class="blog-post-nav__link blog-post-nav__link--empty"></span>
							<?php endif; ?>
						</nav>
					<?php endif; ?>

					<div class="blog-article-cta">
						<div class="blog-article-cta__text">
							<span class="material-symbols-outlined icon">local_shipping</span>
							<div>
								<h2 class="text-headline-md"><?php esc_html_e( 'آماده حمل بار هستید؟', 'samabar' ); ?></h2>
								<p class="text-body-md"><?php esc_html_e( 'با ثبت سفارش آنلاین در سما بار، هزینه را شفاف ببینید و وضعیت محموله را لحظه‌ای پیگیری کنید.', 'samabar' ); ?></p>
							</div>
						</div>
						<a class="btn btn--secondary" href="<?php echo esc_url( $order_url ); ?>"><?php esc_html_e( 'ثبت سفارش', 'samabar' ); ?></a>
					</div>
				</article>

				<?php get_template_part( 'template-parts/blog/single-sidebar' ); ?>
			</div>

			<?php if ( ! empty( $related ) ) : ?>
				<section class="blog-related">
					<div class="blog-related__head">
						<h2 class="text-headline-md"><?php esc_html_e( 'مقالات مرتبط', 'samabar' ); ?></h2>
						<a class="blog-related__more" href="<?php echo esc_url( $blog_url ); ?>">
							<?php esc_html_e( 'مشاهده همه', 'samabar' ); ?>
							<span class="material-symbols-outlined icon">arrow_forward</span>
						</a>
					</div>
					<div class="blog-grid blog-grid--related">
						<?php
						foreach ( $related as $related_post ) {
							get_template_part(
								'template-parts/blog/card',
								null,
								array( 'post' => $related_post )
							);
						}
						?>
					</div>
				</section>
			<?php endif; ?>

			<section class="blog-newsletter blog-newsletter--single" id="blog-newsletter">
				<div class="blog-newsletter__text">
					<h2 class="text-headline-lg">
						<span class="material-symbols-outlined icon">mark_email_read</span>
						<?php esc_html_e( 'عضویت در خبرنامه', 'samabar' ); ?>
					</h2>
					<p class="text-body-md"><?php esc_html_e( 'برای دریافت آخرین مقالات تخصصی لجستیک و اخبار سما بار، ایمیل خود را وارد کنید.', 'samabar' ); ?></p>
				</div>
				<form class="blog-newsletter__form" id="blog-newsletter-form" action="#" method="post">
					<label class="blog-newsletter__field">
						<span class="material-symbols-outlined icon">mail</span>
						<input type="email" name="email" placeholder="آدرس ایمیل شما" dir="ltr" required>
					</label>
					<button class="btn btn--primary" type="submit"><?php esc_html_e( 'عضویت', 'samabar' ); ?></button>
					<p class="blog-newsletter__notice" id="blog-newsletter-notice" hidden></p>
				</form>
			</section>

			<div class="blog-single__back">
				<a class="btn btn--outline" href="<?php echo esc_url( $blog_url ); ?>">
					<span class="material-symbols-outlined icon">arrow_forward</span>
					<?php esc_html_e( 'بازگشت به وبلاگ', 'samabar' ); ?>
				</a>
			</div>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();
