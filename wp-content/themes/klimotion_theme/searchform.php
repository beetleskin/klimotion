<?php
/**
 * The template for displaying search forms in Cazuela
 *TODO: Suchtext in Übersetzung eintragen!
 * @package Cazuela
 * @since Cazuela 1.0
 */
?>

<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
	<label for="s" class="assistive-text"><?php _e( 'Suche', 'cazuela' ); ?></label>
	<input type="text" class="field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="s" placeholder="<?php esc_attr_e( 'Suche&hellip;', 'cazuela' ); ?>" />
	<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Suche' ); ?>" />
</form>
