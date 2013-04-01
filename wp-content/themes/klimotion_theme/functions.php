<?php 

include_once ('functions_render.php');

function new_excerpt_more( $more ) {
	return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">weiterlesen →</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );

?>