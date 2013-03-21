<?php 

class LocalGroupsPage {
	
	
	
	public static function local_groups_query() {
		$newQuery = query_posts( array( 'post_type' => array('klimo_localGroups') ) );
		return $newQuery;
	}
	
	
	public static function prepare_map() {
		
		// add group map script
	    wp_register_script('rio-message-script', get_stylesheet_directory_uri() . '/script/klimo_localgroupspage.js', array('jquery'));
	    wp_enqueue_script('rio-message-script');
	}
	
	public static function render_map() {
		echo '<div id="groupmap">-----</div>';
	}
}

?>