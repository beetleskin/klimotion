<?php
/**
 * Template part for displaying post header.
 *
 * @package Cazuela
 * @since Cazuela 1.0
 */
?>

<?php
	$city = get_post_meta(get_the_ID(), "_city", true);
?>

<header class="entry-header">
	<?php if (has_post_thumbnail() ): ?>
		<div class="entry-thumbnail">
		<?php if (is_singular()): ?>
			<a class="thickbox" href="<?php echo current(wp_get_attachment_image_src( get_post_thumbnail_id(), 'full')); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
		<?php else: ?>
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
		<?php endif; ?>
	</div><!-- .entry-thumbnail -->
	<?php endif; ?>

	<h1 class="entry-title">
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h1>
	
	<div class="entry-meta">
		<?php thsp_posted_on(); ?>
		<?php if ( !is_singular() && !empty($city)): ?>
			<br />Ort: <strong><?php echo $city; ?></strong>
		<?php endif; ?>
	</div><!-- .entry-meta -->
	
</header><!-- .entry-header -->