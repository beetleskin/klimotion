<?php
/**
 * @package Cazuela
 * @since Cazuela 1.0
 * //TODO: einfügen topic
 */
?>

<?php
	$args = array();
	$args['contact_meta'] = get_post_meta($post->ID, '_contact', true);
	$args['homepage_meta'] = get_post_meta($post->ID, '_homepage', true);
	$args['city_meta'] = get_post_meta($post->ID, '_city', true);
	$args['zip_meta'] = get_post_meta($post->ID, '_zip', true);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-inner">
    	
		<?php get_template_part( 'post', 'header' ); ?>
		// TODO: Thickbox;
			
		<div class="entry-group-district">
			Landkreis: 
			<?php the_terms($post->ID, "klimo_districts", "", " | "); ?>
		</div><!-- .entry-idea-district -->

		<div class="entry-content">
			<?php the_content(); ?>
		</div><!-- .entry-content -->
		
		<?php if( !empty($args['homepage_meta']) ): ?>
		<div class="entry-group-homepage">
			Homepage: 
			<a href="<?php echo $args['homepage_meta']; ?>" target="_blank"><?php echo $args['homepage_meta']; ?></a>
		</div><!-- .entry-group-homepage -->
		<?php endif ?>
		
		<?php if( !empty($args['city_meta']) ): ?>
		<div class="entry-group-city">
			Stadt: 
			<?php echo $args['zip_meta']; ?> <?php echo $args['city_meta']; ?>
		</div><!-- .entry-group-city -->
		<?php endif ?>
		
		<?php if($args['contact_meta']['publish'] === true): ?>
		<div class="entry-group-contact">
			<strong>Ansprechpartner:</strong>
			<p>
				<?php echo $args['contact_meta']['surname']; ?> <?php echo $args['contact_meta']['surname']; ?><br />
				<a href="mailto:<?php echo $args['contact_meta']['mail']; ?>"><?php echo $args['contact_meta']['mail']; ?></a><br />
				<?php echo $args['contact_meta']['phone']; ?><br />
			</p>
		</div><!-- .entry-group-contact -->
		<?php endif ?>
		
	</div><!-- .entry-inner -->

	<?php get_template_part( 'post', 'footer' ); ?>
</article><!-- #post-<?php the_ID(); ?> -->




