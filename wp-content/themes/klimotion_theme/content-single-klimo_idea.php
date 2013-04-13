<?php
/**
 * @package Cazuela
 * @since Cazuela 1.0
 * //TODO: einfügen topic
 */
?>

<?php
	$args = array();
	$args['attachments_meta'] = get_posts( array(
        'post_type' => 'attachment',
        'posts_per_page' => -1,
        'post_parent' => $post->ID,
        'exclude'     => get_post_thumbnail_id()
    ));
	$args['links_meta'] = get_post_meta($post->ID, '_links', true);
	$args['group_meta'] = kpt_get_localgroups_by_idea($post->ID);
	$args['initiator_meta'] = array();
	foreach ($args['group_meta'] as $i => $group) {
		if($group->initiated) {
			$args['initiator_meta'] = $group;
			unset($args['group_meta'][$i]);
			break;
		}
	}
	
	d($args['links_meta']);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-inner">
    	
		<?php get_template_part( 'post', 'header' ); ?>
		// TODO: Box/Klarmachen: WAS kan ich hier eigentlich machen? ähnlich github, noch stärker
		
		
		<?php if( !empty($args['initiator_meta']) ): ?>
		<div class="entry-idea-initiator">
			Initiator:
			<a href="<?php echo get_permalink($args['initiator_meta']->ID); ?>"><?php echo $args['initiator_meta']->post_title; ?></a>
		</div><!-- .entry-idea-initiator -->
		<?php endif ?>
		
		
		<?php if( !empty($args['group_meta']) ): ?>
		<div class="entry-idea-group">
			Beteiligte Gruppen:
			<ul>
				<?php foreach ( $args['group_meta'] as &$group): ?>
		         	<a href="<?php echo get_permalink($group->ID); ?>"><?php echo $group->post_title; ?></a>
		    <?php endforeach; ?>
			</ul>
		</div><!-- .entry-idea-group -->
		<?php endif ?>
			
		<div class="entry-idea-aims">
			Ziele: 
			<?php the_terms($post->ID, "klimo_idea_aims", "", " | "); ?>
		</div><!-- .entry-idea-aims -->
		
		<div class="entry-idea-topics">
			Themen: 
			<?php the_terms($post->ID, "klimo_idea_topics", "", " | "); ?>
		</div><!-- .entry-idea-topics -->
		
		<div class="entry-idea-excerpt">
			<?php the_excerpt() ?>
		</div><!-- .entry-idea-excerpt -->
		
		<div class="entry-content">
			<?php the_content(); ?>
		</div><!-- .entry-content -->
		
		<div class="entry-idea-attachments">
			<?php foreach ( $args['attachments_meta'] as $no => $attachment): ?>
		          <div class="idea-attachment">
		          	<a href="<?php echo wp_get_attachment_url($attachment->ID); ?>" target="_blank" <?php echo ((strpos($attachment->post_mime_type, "image") !== false)? 'class="image-attachment"' : '') ?>>
		          		<?php echo (empty($attachment->post_title)? basename(get_attached_file($attachment->ID)) : $attachment->post_title) ?>
		          	</a>
		          </div><!-- .idea-attachment -->
		    <?php endforeach; ?>
		</div><!-- .entry-idea-attachments -->

		<?php if( !empty($args['links_meta']) ): ?>
		<div class="entry-idea-links">
			<?php foreach ( $args['links_meta'] as $linkl): ?>
		         <div class="idea-link">
		         	<a href="<?php echo $linkl['url']; ?>" target="_blank">
						<?php echo (empty($linkl['text'])? $linkl['url'] : $linkl['text']); ?>
					</a>
		         </div><!-- .idea-link -->
		    <?php endforeach; ?>
		</div><!-- .entry-idea-links -->
		<?php endif ?>
		
	</div><!-- .entry-inner -->

	<?php get_template_part( 'post', 'footer' ); ?>
</article><!-- #post-<?php the_ID(); ?> -->



