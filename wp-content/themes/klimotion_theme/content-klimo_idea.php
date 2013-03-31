<?php
/**
 * @package Cazuela
 * @since Cazuela 1.0
 * //TODO: einfügen topic
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php get_template_part( 'post', 'lead' ); ?>

		<div class="idea-picture">
			<?php $picture = "http://klimotion.marina.bplaced.de/filemanager/wordpress/wp-content/uploads/2013/03/DSC_1343.jpg" ?>
			<img src="<?php echo $picture?>"/>
			
	    <div class="entry-inner">
		<?php get_template_part( 'post', 'header' ); ?>
		<div class="idea-author">
			<p>Name des Eintragenden</p>
		<div class="idea-group">
			<p>initierende Gruppe</p>
			
		<div class="idea-goals">
			<?php $goals= array(
				'C02 einsparen',
				'Wasser einsparen',
				'Öffentlichkeit gewinnen',
			)?>
			<?php foreach ( $goals as &$goal): ?>
		          <div class=""><a href="#"><?php echo $goal?></a></div>
		    <?php endforeach; ?>

		<div class="idea-content-excerpt">
			<p>Umsetzung: Bäume ausreißen macht Spaß und schützt die Umwelt ... NICHT! Deshalb laden wir euch alles ein unso!</p>
		<div class="entry-content">
			<?php the_content(); ?>
		<div class="idea-attachment">
			
			<?php $pdfs= array(
				'Lustige Anleitung'	=>	'http://www.adobe.com/enterprise/accessibility/pdfs/acro6_pg_ue.pdf',
				'Hurz'	=> 'http://www.wdr.de/tv/quarks/global/pdf/liebe.pdf',
			)?>
			<?php foreach ( $pdfs as $pdf => $pdfurl): ?>
		          <div class=""><a href="<?php echo $pdfurl?>"><?php echo $pdf?></a></div>
		    <?php endforeach; ?>

		<div class="idea-link">
			
			<?php $files= array(
				'messagetoio'	=>	'http://message-to-rio.de/',
				'ähnliches projekt'	=>	'http://www.global-youth-life.org/',
				'ne'	=>	'http://www.google.de',
			)?>
			<?php foreach ( $files as $file => $url): ?>
		         <div class=""><a href="<?php echo $url?>"><?php echo $file?></a></div>
		    <?php endforeach; ?>
		    
		</div>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( '<span>Pages:</span>', 'cazuela' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
	</div><!-- .entry-inner -->

	<?php get_template_part( 'post', 'footer' ); ?>
</article><!-- #post-<?php the_ID(); ?> -->



