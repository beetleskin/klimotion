<?php
/**
 * Template part for displaying post header.
 *
 * @package Cazuela
 * @since Cazuela 1.0
 */
?>
<link href="http://klimotion.de/wp-content/uploads/2013/04/klimo-fav.ico" rel="shortcut icon" />

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
	
	<?php if (!is_page()): ?>
	<div class="entry-meta">
		<?php thsp_posted_on(); ?>
	</div><!-- .entry-meta -->
	<?php endif; ?>
</header><!-- .entry-header -->