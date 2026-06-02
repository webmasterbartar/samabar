<?php
/**
 * Featured blog post.
 *
 * @package Samabar
 *
 * @var WP_Post $post Post object.
 */

$post = isset( $args['post'] ) ? $args['post'] : null;
if ( ! $post instanceof WP_Post ) {
	return;
}

$category  = samabar_get_primary_category( $post->ID );
$permalink = get_permalink( $post );
?>
<section class="blog-featured">
	<article class="blog-featured__card">
		<a class="blog-featured__media" href="<?php echo esc_url( $permalink ); ?>">
			<?php if ( has_post_thumbnail( $post ) ) : ?>
				<?php echo get_the_post_thumbnail( $post, 'large', array( 'loading' => 'eager' ) ); ?>
			<?php else : ?>
				<div class="blog-featured__placeholder" aria-hidden="true">
					<span class="material-symbols-outlined icon">article</span>
				</div>
			<?php endif; ?>
			<span class="blog-featured__badge"><?php esc_html_e( 'ویژه', 'samabar' ); ?></span>
		</a>
		<div class="blog-featured__body">
			<div class="blog-card__meta">
				<?php if ( $category ) : ?>
					<a class="blog-card__tag" href="<?php echo esc_url( samabar_get_blog_filter_url( array( 'category' => $category->slug ) ) ); ?>">
						<?php echo esc_html( $category->name ); ?>
					</a>
				<?php endif; ?>
				<span class="blog-card__read">
					<span class="material-symbols-outlined icon">schedule</span>
					<?php echo esc_html( samabar_estimate_reading_time( $post->post_content ) ); ?>
				</span>
			</div>
			<h2 class="text-headline-lg">
				<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( get_the_title( $post ) ); ?></a>
			</h2>
			<p class="text-body-md"><?php echo esc_html( wp_trim_words( $post->post_excerpt ?: $post->post_content, 32 ) ); ?></p>
			<div class="blog-featured__foot">
				<div class="blog-card__author">
					<span class="blog-card__avatar"><span class="material-symbols-outlined icon">person</span></span>
					<span><?php echo esc_html( get_the_author_meta( 'display_name', $post->post_author ) ); ?></span>
				</div>
				<a class="blog-card__more" href="<?php echo esc_url( $permalink ); ?>">
					<?php esc_html_e( 'ادامه مطلب', 'samabar' ); ?>
					<span class="material-symbols-outlined icon">arrow_forward</span>
				</a>
			</div>
		</div>
	</article>
</section>
