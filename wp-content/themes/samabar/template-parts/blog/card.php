<?php
/**
 * Blog post card.
 *
 * @package Samabar
 *
 * @var WP_Post $post Optional. Post object.
 */

$post = isset( $args['post'] ) ? $args['post'] : null;
if ( ! $post instanceof WP_Post ) {
	return;
}

$category  = samabar_get_primary_category( $post->ID );
$permalink = get_permalink( $post );
?>
<article class="blog-card post-<?php echo esc_attr( (string) $post->ID ); ?> post type-post status-publish">
	<a class="blog-card__media" href="<?php echo esc_url( $permalink ); ?>">
		<?php if ( has_post_thumbnail( $post ) ) : ?>
			<?php echo get_the_post_thumbnail( $post, 'medium_large', array( 'loading' => 'lazy' ) ); ?>
		<?php else : ?>
			<div class="blog-card__placeholder" aria-hidden="true">
				<span class="material-symbols-outlined icon">article</span>
			</div>
		<?php endif; ?>
	</a>
	<div class="blog-card__body">
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
		<h3 class="text-headline-md">
			<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( get_the_title( $post ) ); ?></a>
		</h3>
		<p class="text-body-md"><?php echo esc_html( wp_trim_words( $post->post_excerpt ?: $post->post_content, 20 ) ); ?></p>
		<div class="blog-card__foot">
			<span><?php echo esc_html( samabar_format_post_date( $post->ID ) ); ?></span>
			<a class="blog-card__foot-link" href="<?php echo esc_url( $permalink ); ?>" aria-label="<?php esc_attr_e( 'ادامه مطلب', 'samabar' ); ?>">
				<span class="material-symbols-outlined icon">arrow_forward</span>
			</a>
		</div>
	</div>
</article>
