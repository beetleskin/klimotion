<?php 


/* front end action hooks */
add_action('init', 'klimo_render_init');
add_filter( 'excerpt_more', 'new_excerpt_more' );

function klimo_render_init() {
	wp_enqueue_style('thickbox');
	wp_enqueue_script('thickbox');
	
	// hide toolbar ...
	if ( current_user_can('subscriber') ) {
		show_admin_bar(false);	
	}
	
}


function new_excerpt_more( $more ) {
	return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">weiterlesen →</a>';
}

?>