<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Cazuela
 * @since Cazuela 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-inner">
		<header class="entry-header">
			<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h1>
		</header><!-- .entry-header -->
	
		<div class="entry-content">
			<?php the_content(); ?>
		</div><!-- .entry-content -->
	</div><!-- .entry-inner -->
</article><!-- #post-<?php the_ID(); ?> -->
