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
add_filter('sidebar_login_widget_logged_out_links', 'kpt_sidebar_login_loggedout_links_hook');
add_filter('sidebar_login_widget_logged_in_links', 'kpt_sidebar_login_loggedin_links_hook');
add_filter( 'wpmem_register_form', 'kpt_adapt_register_form' );


/**
 * INIT 
 */
function kpt_fe_hook_init() {
	// register ajax callbacks
	NewIdeaForm::initAjax();
	NewGroupForm::initAjax();
}

/**
 * WP_MEMBERS
 */
function kpt_adapt_register_form(&$form) {
	$form = str_replace("First Name", "Vorname", $form);
	$form = str_replace("Last Name", "Nachname", $form);
	$form = str_replace("Wähle einen Mitgliedernamen", "Benutzername", $form);
	
	$fieldset1_begin = '<label for="username" class="text">Benutzername<font class="req">*</font></label>';
	$fieldset_inter = '<label for="first_name" class="text">Vorname</label>';
	$fieldset2_end = '<div class="div_text"><input name="tos"';
	$form = str_replace($fieldset1_begin, '<fieldset><legend>Benötigt</legend><div class="slide-wrap">' . $fieldset1_begin, $form);
	$form = str_replace($fieldset_inter, '</div></fieldset><fieldset><legend>Details</legend><div class="slide-wrap" collapsed="collapsd">' . $fieldset_inter, $form);
	$form = str_replace($fieldset2_end, '</div></fieldset>' . $fieldset2_end, $form);
	 
	
	
	$script = 'jQuery(function($) { 
			$(document).ready(function() {
				$("#wpmem_reg div.slide-wrap[collapsed]").slideToggle(0);
				$("#wpmem_reg form fieldset fieldset legend").click(function() {
  					$(".slide-wrap", $(this).parent()).slideToggle();
				});
			});
		});';
	return $form . "<script>" . $script . "</script>";
	 
}


/**
 * SIDEBAR LOGIN
 */
function kpt_sidebar_login_loggedout_links_hook(&$links) {
	if( array_key_exists('register', $links)) {
		$links['register']['href'] = home_url( '/wpm_register/' );
	}
	if( array_key_exists('lost_password', $links)) {
		$links['lost_password']['href'] = home_url( '/wpm_password/' );
	}
	return $links;
}

function kpt_sidebar_login_loggedin_links_hook(&$links) {
	if( array_key_exists('profile', $links)) {
		$links['profile']['href'] = home_url( '/wpm_profile/' );
	}
	return $links;
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