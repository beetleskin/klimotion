<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Cazuela
 * @since Cazuela 1.0
 */

get_header(); ?>

		<section id="primary" class="content-area">
			<div id="content" class="site-content" role="main">

			<?php
				// Before Content theme hook callback
				thsp_hook_before_content();
			?>

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title">
						<?php
							if ( is_category() ) {
								echo '<span>' . single_cat_title( '', false ) . '</span>';

							} elseif ( is_tag() ) {
								echo '<span>' . single_tag_title( '', false ) . '</span>';

							} elseif ( is_author() ) {
								the_post();
								echo '<span class="vcard">' . get_the_author() . '</span>';
								rewind_posts();

							} elseif ( is_day() ) {
								echo '<span>' . get_the_date() . '</span>';

							} elseif ( is_month() ) {
								echo '<span>' . get_the_date( 'F Y' ) . '</span>';

							} elseif ( is_year() ) {
								echo '<span>' . get_the_date( 'Y' ) . '</span>';
							} elseif ( is_tax() ) {
								echo '<span>' . single_term_title() . '</span>';
							}
						?>
					</h1>
					<?php
						if ( is_category() ) {
							// show an optional category description
							$category_description = category_description();
							if ( ! empty( $category_description ) )
								echo apply_filters( 'category_archive_meta', '<div class="taxonomy-description">' . $category_description . '</div>' );

						} elseif ( is_tag() ) {
							// show an optional tag description
							$tag_description = tag_description();
							if ( ! empty( $tag_description ) )
								echo apply_filters( 'tag_archive_meta', '<div class="taxonomy-description">' . $tag_description . '</div>' );
						} elseif ( is_tax() ) {
							// show an optional tax description
							$term = get_queried_object();
							$term_description = $term->description;
							if ( ! empty( $term->description ) )
								echo apply_filters( 'tag_archive_meta', '<div class="taxonomy-description">' . $term->description . '</div>' );
						}
					?>
				</header><!-- .page-header -->

				<?php thsp_content_nav( 'nav-above' ); ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<div class="archive-post">
					<?php
						/* Include the Post-Format-specific template for the content.
						 * If you want to overload this in a child theme then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'content', get_post_type() );
					?>
					</div><!-- .archive-post -->

				<?php endwhile; ?>

				<?php thsp_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<?php get_template_part( 'no-results', 'archive' ); ?>

			<?php endif; ?>

			<?php
				// After Content theme hook callback
				thsp_hook_after_content();
			?>

			</div><!-- #content .site-content -->
		</section><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>