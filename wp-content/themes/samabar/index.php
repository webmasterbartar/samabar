<?php
/**
 * Fallback template.
 *
 * @package Samabar
 */

get_header();
?>

<main id="primary" class="site-main">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class(); ?>>
				<?php the_title( '<h1>', '</h1>' ); ?>
				<?php the_content(); ?>
			</article>
		<?php endwhile; ?>
	<?php else : ?>
		<p><?php esc_html_e( 'محتوایی یافت نشد.', 'samabar' ); ?></p>
	<?php endif; ?>
</main>

<?php
get_footer();
