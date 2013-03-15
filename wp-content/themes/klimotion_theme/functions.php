<?php 


add_action('init', 'klimo_init');   


/*
include_once ('functions_admin.php');
include_once ('functions_render.php');
include_once ('functions_script.php');
*/


function klimo_init() {
    
    
    klimo_add_post_type();
    
    
    // enable gzip compression
    ob_start("ob_gzhandler");
}

function klimo_add_post_type()  {
    
    $klimo_type_labels = array(
        'name' =>               _x('Ideen', 'post type general name'),
        'singular_name' =>      _x('Ideeht', 'post type singular name'),
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
        'menu_name' => 'Ideenpool'
    );
    
    $klimo_type_args = array(
        'labels'        => $klimo_type_labels, 
        'public'        => true,
        'description'   => 'klimotion custom post type for project ideas',
        'show_in_menu'  => true,
        'map_meta_cap'  => true,
        'has_archive'   => false,
        'supports'      => array('title', 'editor', 'author', 'thumbnail', 'custom-fields', 'comments', 'post-formats'),
        'taxonomies'    => array('klimo_idea_topics'),
    );
//     
    
    $klimo_taxonomy_args = array(
        "hierarchical"      => true,
        "label"             => "Thema",
        "singular_label"    => "Thema",
        "rewrite"           => true,
    );
        
        
    register_taxonomy("klimo_idea_topics", array("klimo_idea"), $klimo_taxonomy_args);
    register_post_type('klimo_idea', $klimo_type_args);
}


?>