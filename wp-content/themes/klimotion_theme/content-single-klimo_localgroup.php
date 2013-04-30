<?php
/**
 * @package Cazuela
 * @since Cazuela 1.0
 * //TODO: einfügen topic
 */
?>

<?php
	$args = array();
	$args['member_meta'] = get_post_meta($post->ID, '_member', true);
	$args['contact_meta'] = get_post_meta($post->ID, '_contact', true);
	$args['homepage_meta'] = get_post_meta($post->ID, '_homepage', true);
	$args['city_meta'] = get_post_meta($post->ID, '_city', true);
	$args['zip_meta'] = get_post_meta($post->ID, '_zip', true);
	$args['ideas_meta'] = kpt_get_ideas_by_localgroup($post->ID);
	
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-inner">
    	
		<?php get_template_part( 'klimo_localgroup', 'header' ); ?>

		<div class="localgroup-location">
			<div class="entry-group-district">
				Landkreis: 
				<?php the_terms($post->ID, "klimo_districts", "", " | "); ?>
			</div><!-- .entry-group-district -->
			
			<?php if( !empty($args['city_meta']) ): ?>
			<div class="entry-group-city">
				Ort: 
				<?php echo $args['zip_meta']; ?> <?php echo $args['city_meta']; ?>
			</div><!-- .entry-group-city -->
			<?php endif ?>
		</div>
		
		<div class="localgroup_info">
			<div class="entry-group-scopes">
				Wirkungskreise:
				<?php the_terms($post->ID, "klimo_scopes", "", " | "); ?>
			</div><!-- .entry-grou-scopes -->
			
			<?php if( !empty($args['member_meta']) ): ?>
			<div class="entry-group-member">
				Mitglieder: 
				<?php echo $args['member_meta']; ?>
			</div><!-- .entry-grou-member -->
			<?php endif ?>
			
			<?php if( !empty($args['homepage_meta']) ): ?>
			<div class="entry-group-homepage">
				Homepage: 
				<a href="<?php echo $args['homepage_meta']; ?>" target="_blank"><?php echo $args['homepage_meta']; ?></a>
			</div><!-- .entry-group-homepage -->
			<?php endif ?>
			</div>	
			<div class="entry-content">
				<?php the_content(); ?>
		</div><!-- .entry-content -->
		
		<?php if( !empty($args['ideas_meta']) ): ?>
		<div class="entry-group-ideas">
			<p>Ideen:</p>
			<ul>
				<?php foreach ( $args['ideas_meta'] as &$idea): ?>
		         	<li><a href="<?php echo get_permalink($idea->ID); ?>"><?php echo $idea->post_title; ?></a></li>
		    <?php endforeach; ?>
			</ul>
		</div><!-- .entry-group-ideas -->
		<?php endif ?>
		
		
		<?php if($args['contact_meta']['publish'] === true): ?>
		<div class="entry-group-contact">
			<strong>Ansprechpartner:</strong>
			<p>
				<?php echo $args['contact_meta']['surname']; ?> <?php echo $args['contact_meta']['name']; ?><br />
				<a href="mailto:<?php echo $args['contact_meta']['mail']; ?>"><?php echo $args['contact_meta']['mail']; ?></a><br />
				<?php echo $args['contact_meta']['phone']; ?><br />
			</p>
		</div><!-- .entry-group-contact -->
		<?php endif ?>
		
	</div><!-- .entry-inner -->

	<?php get_template_part( 'post', 'footer' ); ?>
</article><!-- #post-<?php the_ID(); ?> -->




