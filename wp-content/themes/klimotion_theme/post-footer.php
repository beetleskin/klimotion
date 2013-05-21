<?php
/**
 * Template part for displaying post footer.
 *
 * @package Cazuela
 * @since Cazuela 1.0
 * //TODO: "Postet in" -> "Erstellt in"
 */
?>

<?php 

	/* get data to be rendered */
	$categories_list = get_the_category_list( __( ', ', 'cazuela' ) );
	$tags_list = get_the_tag_list( '', __( ', ', 'cazuela' ) );
	$renderCommentsLink = ( !post_password_required() && ( comments_open() || '0' != get_comments_number() ) );
	$userCanEditPost = user_can_edit_post(get_current_user_id(), $post->ID);
	
?>

<?php if ( !empty($categories_list) || !empty($tags_list) || $renderCommentsLink || $userCanEditPost ) : ?>
	
	<footer class="entry-meta">
		<div class="entry-cats-tags">
			<?php if ( $categories_list ) : ?>
				<span class="cat-links">	
					<?php printf( __( 'Erstellt in %1$s' ), $categories_list ); ?>
				</span>
				<span class="sep"> | </span>
			<?php endif; // End if categories ?>
		
			<?php if ( $tags_list ) : ?>
				<span class="tags-links">
					<?php printf( __( 'Tagged %1$s', 'cazuela' ), $tags_list ); ?>
				</span>
				<span class="sep"> | </span>
			<?php endif; // End if tags ?>
		
			<?php if ( $renderCommentsLink ) : ?>
				<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment' ), __( '1 Comment' ), __( '% Comments') ); ?></span>
			<?php endif; ?>
		</div><!-- .entry-cats-tags -->
	
	
		<?php edit_post_link( __( 'Edit' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
	
<?php endif; // End if render_footer ?>



