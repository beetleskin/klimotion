<?php
/**
 * @package Klimotion_Post_Types
 */


/* data action hooks */
register_activation_hook(__FILE__, "kpt_create_districtss");
register_uninstall_hook(__FILE__, "kpt_delete_districtss");
add_action('init', 'kpt_data_init');



function kpt_data_init() {
	// add post types
	kpt_add_idea();
	kpt_add_localGroup();
}


function kpt_create_districtss() {
	require_once('lower_saxony_list.php');
	foreach ($lower_saxony_districts as $id => $name) {
		wp_insert_term($name, 'klimo_districts', array('slug' => '_district_' . $id));
	}
}


function kpt_delete_districts() {
	$districtTerms = get_terms('klimo_districts', array('name__linke' => '_district_'));
	foreach ($districtTerms as $term) {
		wp_delete_term($term->term_id);
	}
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
        'has_archive'   => false,
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
        
        
    register_taxonomy("klimo_idea_topics", array("klimo_idea"), $post_taxonomy1_args);
	register_taxonomy("klimo_idea_aims", array("klimo_idea"), $post_taxonomy2_args);
    register_post_type('klimo_idea', $post_type_args);
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
        'has_archive'   => false,
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
        
        
    register_taxonomy("klimo_districts", array("klimo_localgroup"), $post_taxonomy_args1);
	register_taxonomy("klimo_scopes", array("klimo_localgroup"), $post_taxonomy_args2);
    register_post_type('klimo_localgroup', $post_type_args);
}
?>