<?php
/**
 * Template part for displaying post header.
 *
 * @package Cazuela
 * @since Cazuela 1.0
 */
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
	</div><!-- .entry-meta -->
	
</header><!-- .entry-header -->