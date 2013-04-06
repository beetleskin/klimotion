<?php
/**
 * @package Cazuela
 * @since Cazuela 1.0
 * //TODO: einfügen topic
 */
?>

<?php
	$args = array();
    $args['group_meta'] = get_post_meta($post->ID, '_group', TRUE);
	$args['group_meta'] = get_post($args['group_meta']);
	$args['attachments_meta'] = get_posts( array(
        'post_type' => 'attachment',
        'posts_per_page' => -1,
        'post_parent' => $post->ID,
        'exclude'     => get_post_thumbnail_id()
    ));
	$args['links_meta'] = get_post_meta($post->ID, '_links', TRUE);
	d($args);
	d($post);
	
            
	

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-inner">
    	
		<?php get_template_part( 'post', 'header' ); ?>
		// TODO: Thickbox;
		
		<div class="entry-idea-group">
			<p>Gruppe:
				<a href="<?php echo get_permalink($args['group_meta']->ID); ?>">
					<?php echo $args['group_meta']->post_title; ?>
				</a>
			</p>
		</div><!-- .entry-idea-group -->
			
		<div class="entry-idea-aims">
			<?php the_terms($post->ID, "klimo_idea_aims", "", " | "); ?>
		</div><!-- .entry-idea-aims -->
		
		<div class="entry-idea-topics">
			<?php the_terms($post->ID, "klimo_idea_topics", "", " | "); ?>
		</div><!-- .entry-idea-topics -->

		<div class="entry-idea-excerpt">
			<p><?php the_excerpt() ?></p>
		</div><!-- .entry-idea-excerpt -->
		
		<div class="entry-content">
			<?php the_content(); ?>
		</div><!-- .entry-content -->
		
		<div class="entry-idea-attachments">
			<?php foreach ( $args['attachments_meta'] as $no => $attachment): ?>
		          <div class="idea-attachment">
		          	<a href="<?php echo wp_get_attachment_url($attachment->ID); ?>" target="_blank" <?php echo ((strpos($attachment->post_mime_type, "image") !== false)? 'class="image-attachment"' : '') ?>>
		          		<?php echo (empty($attachment->post_title)? "Datei " . $no : $attachment->post_title) ?>
		          	</a>
		          </div><!-- .idea-attachment -->
		    <?php endforeach; ?>
		</div><!-- .entry-idea-attachments -->

		<div class="entry-idea-links">
			<?php foreach ( $args['links_meta'] as $linkl): ?>
		         <div class="idea-link">
		         	<a href="<?php echo $linkl['url']; ?>" target="_blank">
						<?php echo (empty($linkl['text'])? $linkl['url'] : $linkl['text']); ?>
					</a>
		         </div><!-- .idea-link -->
		    <?php endforeach; ?>
		</div><!-- .entry-idea-links -->
		
		
		
		
		
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( '<span>Pages:</span>', 'cazuela' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
	</div><!-- .entry-inner -->

	<?php get_template_part( 'post', 'footer' ); ?>
</article><!-- #post-<?php the_ID(); ?> -->



