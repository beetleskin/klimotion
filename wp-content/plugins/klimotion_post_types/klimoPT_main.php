<?php
/**
 * @package Klimotion_Post_Types
 */

/*
 Plugin Name: Klimotion Post Types
 Description: I owe you a descriptive text
 Version: 0.1
 Author: stfn
 License: GPLv2 or later
 */

include_once dirname( __FILE__ ).'/klimoPT_install.php';

/* install hooks*/
register_activation_hook( __FILE__, 'kpt_hook_install' );

/* action hooks */
add_action('init', 'kpt_hook_init');




function kpt_hook_init() {
    // enable gzip compression
    ob_start("ob_gzhandler");
	
	// add post types
	kpt_add_pt_idea();
	kpt_add_localGroups();
}




function kpt_add_pt_idea()  {
    
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
        'supports'      => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'comments'),
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


function kpt_add_localGroups()  {
    
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
        'supports'      => array('title', 'author', 'thumbnail', 'custom-fields', 'comments'),
        'taxonomies'    => array('klimo_localGroups_ZIPCode'),
    );

    
    $post_taxonomy_args = array(
        "hierarchical"      => true,
        "label"             => "Postleitzahlen",
        "singular_label"    => "Postleitzahl",
        "rewrite"           => true,
    );
        
        
    register_taxonomy("klimo_localGroups_ZIPCode", array("klimo_localGroups"), $post_taxonomy_args);
    register_post_type('klimo_localGroups', $post_type_args);
}




?>