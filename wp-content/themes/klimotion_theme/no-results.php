<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Cazuela
 * @since Cazuela 1.0
 */
?>

<article id="post-0" class="post no-results not-found">
	<header class="entry-header">
		<h1 class="entry-title"><?php echo __( 'Keine Inhalte gefunden!' ); ?></h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php if ( is_search() ) : ?>

			<p><?php echo __( 'Für deine Suche gibt es keine Ergebnisse. Versuch etwas anderes!' ); ?></p>
			<?php get_search_form(); ?>

		<?php elseif ( is_tax() ) : ?>
			<p><?php echo __( 'Entschuldige, für deine Anfrage gibt es noch keine Inhalte.' ); ?></p>
			<?php 
				$term = get_queried_object();
				if($term && $term->taxonomy) {
					if( $term->taxonomy == "klimo_idea_topics" || $term->taxonomy == "klimo_idea_aims") {
						echo '<p><a href="' . home_url('newideapage') . '">Hier</a> kannst du deine eigene <strong>Idee</strong> erstellen</p>';
					} else if( $term->taxonomy == "klimo_districts" || $term->taxnonomy == "klimo_scopes" ) {
						echo '<p><a href="' . home_url('newgrouppage') . '">Hier</a> kannst du eine neue <strong>Lokalgruppe</strong> erstellen</p>';
					}
				}
			?>
			<?php get_search_form(); ?>

		<?php else : ?>

			<p><?php echo __( 'Entschuldige, für deine Anfrage gibt es noch keine Inhalte.<br />Vielleicht hilft die Suche.' ); ?></p>
			<?php get_search_form(); ?>

		<?php endif; ?>
	</div><!-- .entry-content -->
</article><!-- #post-0 .post .no-results .not-found -->
