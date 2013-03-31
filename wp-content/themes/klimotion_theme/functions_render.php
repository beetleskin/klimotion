<?php 

class LocalGroupsPage {
	
	
	
	public static function local_groups_query() {
		// TODO: query_posts or get_posts ?
		$newQuery = query_posts( array( 'post_type' => array('klimo_localGroups') ) );
		return $newQuery;
	}
	
	
	public static function prepare_map() {
		// add group map script
	    wp_register_script('klimo-groupspage', get_stylesheet_directory_uri() . '/script/klimo_localgroupspage.js', array('jquery', 'jvectormap'));
	    wp_enqueue_script('klimo-groupspage');
		wp_register_script('jvectormap', get_stylesheet_directory_uri() . '/script/jquery-jvectormap-1.2.2/jquery-jvectormap-1.2.2.min.js', array('jquery'));
	    wp_enqueue_script('jvectormap');
		wp_register_script('jvectormap-map', get_stylesheet_directory_uri() . '/script/jquery-jvectormap-1.2.2/lower_saxony_map.js', array('jquery'));
	    wp_enqueue_script('jvectormap-map');
		
		// add group map styles
		wp_enqueue_style('jvectormap', get_stylesheet_directory_uri() . '/script/jquery-jvectormap-1.2.2/jquery-jvectormap-1.2.2.css');
		wp_enqueue_style('localgroupspage', get_stylesheet_directory_uri() . '/css/localgroupspage.css');
	}
	
	public static function render_map() {
		echo '<div id="groupmap">-----</div>';
	}
}

?>