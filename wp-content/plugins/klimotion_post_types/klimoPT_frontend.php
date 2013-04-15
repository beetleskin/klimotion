<?php
/**
 * @package Klimotion_Post_Types
 */
// TODO: initialize menu with install


include_once('klimoPT_idea_form.php');
include_once('klimoPT_group_form.php');


/* front end action hooks */
add_action('init', 'kpt_fe_hook_init', 100);
add_filter('created_klimo_idea_topics', 'kpt_add_idea_menu_term_item_hook');

/**
 * INIT 
 */
function kpt_fe_hook_init() {
	// register ajax callbacks
	NewIdeaForm::initAjax();
	NewGroupForm::initAjax();
}


/**
 * MENU
 */
function kpt_create_menu() {
	$menu_name = 'klimo_primary';
	$menu = wp_get_nav_menu_object( $menu_name );
	$menu_id = 0;
	
	if(!$menu) {
		// create menu if not exist
		$menu_id = wp_create_nav_menu($menu_name);
	} else {
		// TODO: log
		$menu_id = $menu->term_id;
	}

	
	if($menu_id == 0 || is_wp_error($menu_id)) {
		// TODO: log
		return;
	}
	
	// create 'alle-ideen' item if not exist
	$menu_items = wp_get_nav_menu_items($menu_id);
	foreach ($menu_items as $item) {
		if($item->object == 'custom' && $item->post_name == "alle-ideen") {
			return $menu_id;
		}
	}
	
	wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' 	=>  __('Alle Ideen'),
        'menu-item-object'  => 'klimo_idea',
        'menu-item-type' 	=> 'custom',  
        'menu-item-url' 	=> get_post_type_archive_link( 'klimo_idea' ),
        'menu-item-status'	=> 'publish',
	));
	
	return $menu_id;
}


function kpt_add_idea_menu_term_item_hook($term_id) {
	// get menu
	$menu_name = 'klimo_primary';
	$menu = wp_get_nav_menu_object( $menu_name );
	if( !$menu ){
		$menu_id = kpt_create_menu();
		$menu = wp_get_nav_menu_object( $menu_id );
	}
	$term = get_term($term_id, 'klimo_idea_topics');
	
	// create item
	$menu_items = wp_get_nav_menu_items($menu->term_id);
	$parent_id = 0;
	foreach ($menu_items as $item) {
		if($item->object == 'custom' && $item->post_name == "alle-ideen") {
			$parent_id = $item->ID;
			break;
		}
	}
	$menu_item_id = kpt_create_nav_menu_item($menu->term_id, $term, "klimo_idea_topics", $parent_id);
}


function kpt_create_nav_menu_item( $menu_id, $term, $taxonomy, $parent_id, $args=array()) {
	// Setup Menu Item Args
	$args = array(
		'menu-item-object-id' => $term->term_id,
		'menu-item-object' => $taxonomy,
		'menu-item-type' => 'taxonomy',
		'menu-item-status' => 'publish',
		'menu-item-parent-id' => $parent_id,
		'menu-item-attr-title' => $term->name,
		'menu-item-description' => $term->description,
		'menu-item-title' => $term->name,
		'menu-item-target' => '',
	);
	return wp_update_nav_menu_item( $menu_id, 0, $args );
}
 
 
?>