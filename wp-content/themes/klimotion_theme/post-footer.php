<?php
/**
 * Template part for displaying post footer.
 *
 * @package Cazuela
 * @since Cazuela 1.0
 * //TODO: "Postet in" -> "Erstellt in"
 */
?>

<footer class="entry-meta">
	<div class="entry-cats-tags">
		<?php
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( __( ', ', 'cazuela' ) );
			if ( $categories_list && thsp_categorized_blog() ) :
		?>
		
		<span class="cat-links">	
			<?php printf( __( 'Erstellt in %1$s' ), $categories_list ); ?>
		</span>
		<span class="sep"> | </span>
		<?php endif; // End if categories ?>
	
		<?php
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', __( ', ', 'cazuela' ) );
			if ( $tags_list ) :
		?>
		<span class="tags-links">
			<?php printf( __( 'Tagged %1$s', 'cazuela' ), $tags_list ); ?>
		</span>
		<span class="sep"> | </span>
		<?php endif; // End if $tags_list ?>
	
		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
		<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment' ), __( '1 Comment' ), __( '% Comments') ); 
			if ( is_user_logged_in() ) {
						echo ' | ';
					}?>
		</span>
		<?php endif; ?>
	</div><!-- .entry-cats-tags -->

	<div class="entry-bookmark">
		<?php
			$bookmark_text = __( 'Bookmark the <a href="%1$s" title="Permalink to %2$s" rel="bookmark">permalink</a>.', 'cazuela' );
			printf(
				$bookmark_text,
				get_permalink(),
				the_title_attribute( 'echo=0' )
			);
		?>
	</div><!-- .entry-bookmark -->

	<?php edit_post_link( __( 'Edit' ), '<span class="edit-link">', '</span>' ); ?>
</footer><!-- .entry-meta -->