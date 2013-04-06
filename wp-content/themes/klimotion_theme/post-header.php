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
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail('thumbnail'); ?>
		</a>
	</div><!-- .entry-thumbnail -->
	<?php endif; ?>

	<h1 class="entry-title">
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h1>
	
	<?php if ( is_singular() ): ?>
		<div class="entry-meta">
			<?php thsp_posted_on(); ?>
		</div><!-- .entry-meta -->
	<?php endif; ?>
</header><!-- .entry-header -->