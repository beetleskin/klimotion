<?php
/**
 * @package Klimotion_Post_Types
 */
 // TODO: create crucial pages on install
 
 
define("KPT_dbtable_post_idea_relations", "klimo_post_relations");


function kpt_data_init() {
	// add post types
	kpt_add_idea();
	kpt_add_localGroup();
}

function kpt_delete_idea_group_relation($idea_id, $group_id) {
	global $wpdb;
	$table_name = KPT_dbtable_post_idea_relations;
	$query = "DELETE FROM $table_name WHERE $table_name.localgroup = $group_id AND idea = $idea_id;";
	return $wpdb->query($query);
}

function kpt_insert_idea_group_relation($idea_id, $group_id, $initiated=false, $uid=-1) {
	global $wpdb;
	$table_name = KPT_dbtable_post_idea_relations;
	if($uid == -1)
		$uid = get_current_user_id();
	
	// TODO: INSERT IGNORE ... needs manual query
	$result = $wpdb->insert(KPT_dbtable_post_idea_relations, array(
		'localgroup' 	=> $group_id,
		'idea' 			=> $idea_id,
		'uid'			=> $uid,
		'initiated'		=> $initiated,
	));
	return $result;
}

function kpt_get_ideas_by_localgroup($group_id, $get_posts=false) {
	global $wpdb;
	$table_name = KPT_dbtable_post_idea_relations;
	$query = "
		SELECT $table_name.idea AS ID, $table_name.initiated, $wpdb->posts.post_title
		FROM $table_name
		INNER JOIN $wpdb->posts
		ON $table_name.idea = $wpdb->posts.ID
		WHERE $table_name.localgroup = $group_id";
	$relations = $wpdb->get_results($query);
	
	if( !$get_posts ) {
		return $relations;
	} else {
		$result = array();
		foreach ($relations as $rel) {
			$idea_post = get_post($rel->ID);
			$idea_post->initiated = (bool)$rel->initiated;
			$result[] = $idea_post;
		}
		return $result;
	}
}


function kpt_get_localgroups_by_idea($idea_id, $get_posts=false) {
	global $wpdb;
	$table_name = KPT_dbtable_post_idea_relations;
	$query = "
		SELECT $table_name.localgroup AS ID, $table_name.initiated, $wpdb->posts.post_title
		FROM $table_name
		INNER JOIN $wpdb->posts
		ON $table_name.localgroup = $wpdb->posts.ID
		WHERE $table_name.idea = $idea_id";
	$relations = $wpdb->get_results($query);
	
	
	if( !$get_posts ) {
		return $relations;
	} else {
		$result = array();
		foreach ($relations as $rel) {
			$group_post = get_post($rel->ID);
			$group_post->initiated = (bool)$rel->initiated;
			$result[] = $group_post;
		}
		return $result;
	}
}


function kpt_get_all_posts_by_type($post_type, $fields=array('ID')) {
	global $wpdb;
	
	if(empty($fields))
		$fields = '*';
	else {
		$fields = implode($fields, ",");
	}
	$query = "SELECT $fields FROM $wpdb->posts WHERE post_type = '$post_type';";
	return $wpdb->get_results($query);
}


function kpt_activate() {
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	require_once('lower_saxony_list.php');
	
	
	kpt_data_init();
	flush_rewrite_rules();
	
	// populate district taxonomy
	foreach ($lower_saxony_districts as $id => $name) {
		// TODO: check if term exists? actually ... wp does that?
		wp_insert_term($name, 'klimo_districts', array('slug' => '_district_' . $id));
	}
	
	
	// create databases
	global $wpdb;
    $sql = "CREATE TABLE IF NOT EXISTS " . KPT_dbtable_post_idea_relations . " (
		localgroup bigint(20) unsigned NOT NULL default 0,
		idea bigint(20) unsigned NOT NULL default 0,
		uid bigint(20) unsigned NOT NULL default 0,
		initiated bit NOT NULL default 0,
		created TIMESTAMP DEFAULT NOW(),
		CONSTRAINT uc UNIQUE (localgroup, idea)
    );";
    dbDelta( $sql );
}


function kpt_uninstall() {
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	
	// delete districts
	$districtTerms = get_terms('klimo_districts', array('name__linke' => '_district_'));
	foreach ($districtTerms as $term) {
		wp_delete_term($term->term_id);
	}
	
	
	// delete databases
	global $wpdb;
	$sql = "DROP TABLE IF EXISTS " . KPT_dbtable_post_idea_relations . ";";
	
	dbDelta( $sql );
}	


function kpt_add_idea()  {
    
    $post_type_labels = array(
        'name' =>               _x('Ideen', 'post type general name'),
        'singular_name' =>      _x('Idee', 'post type singular name'),
        'add_new' =>            _x('Hinzufügen', 'Idee'),
        'add_new_item' =>       __('Idee Hinzufügen'),
        'edit_item' =>          __('Idee Editieren'),
        'new_item' =>           __('Neue Idee'),
        'all_items' =>          __('Alle Ideen'),
        'view_item' =>          __('Idee Ansehen'),
        'search_items' =>       __('Ideen Suchen'),
        'not_found' =>          __('Keine Idee gefunden!'),
        'not_found_in_trash' => __('Keine Idee im Papierkorb'), 
        'parent_item_colon' =>  '',
        'menu_name' => 			'Ideenpool'
    );
    
    $post_type_args = array(
        'labels'        => $post_type_labels, 
        'public'        => true,
        'description'   => 'klimotion custom post type for project ideas',
        'show_in_menu'  => true,
        'map_meta_cap'  => true,
        'has_archive'   => true,
        'supports'      => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        'taxonomies'    => array('klimo_idea_topics'),
    );

    
    $post_taxonomy1_args = array(
        "hierarchical"      => true,
        "label"             => "Thema",
        "singular_label"    => "Thema",
        "rewrite"           => true,
    );
	
	$post_taxonomy2_args = array(
        "hierarchical"      => false,
        "label"             => "Ziele",
        "singular_label"    => "Ziel",
        "rewrite"           => true,
    );
        
        
    register_post_type('klimo_idea', $post_type_args);
	register_taxonomy("klimo_idea_topics", array("klimo_idea"), $post_taxonomy1_args);
	register_taxonomy("klimo_idea_aims", array("klimo_idea"), $post_taxonomy2_args);
}


function kpt_add_localGroup()  {
    
    $post_type_labels = array(
        'name' =>               _x('Lokale Gruppen', 'post type general name'),
        'singular_name' =>      _x('Lokale Gruppe', 'post type singular name'),
        'add_new' =>            _x('Hinzufügen', 'Gruppe'),
        'add_new_item' =>       __('Lokale Gruppe Hinzufügen'),
        'edit_item' =>          __('Lokale Gruppe Editieren'),
        'new_item' =>           __('Neue Lokale Gruppe'),
        'all_items' =>          __('Alle Lokalen Gruppen'),
        'view_item' =>          __('Lokale Gruppe Ansehen'),
        'search_items' =>       __('Lokale Gruppen Suchen'),
        'not_found' =>          __('Keine Lokale Gruppe gefunden!'),
        'not_found_in_trash' => __('Keine Lokale Gruppe im Papierkorb'), 
        'parent_item_colon' =>  '',
        'menu_name' => 			'Lokale Gruppen'
    );
    
    $post_type_args = array(
        'labels'        => $post_type_labels, 
        'public'        => true,
        'description'   => 'klimotion custom post type for local groups',
        'show_in_menu'  => true,
        'map_meta_cap'  => true,
        'has_archive'   => true,
        'supports'      => array('title', 'editor', 'author', 'thumbnail', 'comments'),
        'taxonomies'    => array('klimo_districts', 'klimo_scopes'),
    );

    
    $post_taxonomy_args1 = array(
        "hierarchical"      => true,
        "label"             => "Landkreise",
        "singular_label"    => "Landkreis",
        "rewrite"           => true,
    );
	
	$post_taxonomy_args2 = array(
        "hierarchical"      => false,
        "label"             => "Wirkungskreise",
        "singular_label"    => "Wirkungskreis",
        "rewrite"           => true,
    );
        
        
    register_post_type('klimo_localgroup', $post_type_args);
	register_taxonomy("klimo_districts", array("klimo_localgroup"), $post_taxonomy_args1);
	register_taxonomy("klimo_scopes", array("klimo_localgroup"), $post_taxonomy_args2);
}
?>