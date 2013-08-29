<?php
/*
Template Name: klimo_calendar
Template Description: A custom template for making my events calendar look gorgeous and kick ass.
*/

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry-inner">
						<header class="entry-header">
							<?php if (has_post_thumbnail() && is_single()): ?>
								<div class="entry-thumbnail">
									<a class="thickbox" href="<?php echo current(wp_get_attachment_image_src(get_post_thumbnail_id(), 'full')); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
								</div><!-- .entry-thumbnail -->
							<?php endif; ?>
							<h1 class="entry-title">
								<?php the_title(); ?>
							</h1>
						</header><!-- .entry-header -->
					
						<div class="entry-content">
							<?php the_content(); ?>
						</div><!-- .entry-content -->
					</div><!-- .entry-inner -->
				</article><!-- #post-<?php the_ID(); ?> -->

			<?php endwhile; // end of the loop. ?>


		</div><!-- #content .site-content -->
	</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>