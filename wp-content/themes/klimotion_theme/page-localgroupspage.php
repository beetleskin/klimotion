<?php
/**
 * Template Name: Lokale Gruppen
 *
 * @package Cazuela
 * @since Cazuela 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php
				// Before Content theme hook callback
				thsp_hook_before_content();
			?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

			<?php endwhile; // end of the loop. ?>
			
			<?php
				$lgPage = new LocalGroupsPage();
				$lgPage->renderMap();
				$lgPage->postRender();
			?>
			
			<?php
				$lgPage->local_groups_query();
			?>
			<div id="article_wrap">
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content' ); ?>

			<?php endwhile; // end of the loop. ?>
			</div>

			<?php
				// After Content theme hook callback
				thsp_hook_after_content();
			?>

		</div><!-- #content .site-content -->
	</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>