<?php
/**
 * @package Klimotion_Group_Map
 */

/*
 Plugin Name: Klimotion Post Types
 Description: I owe you a descriptive text
 Version: 0.1
 Author: stfn
 License: GPLv2 or later
 */


include_once('klimoPT_idea_form.php');


/* front end action hooks */
add_action('init', 'kpt_hook_init');
register_activation_hook( __FILE__, 'my_rewrite_flush' );

/* back end action hooks */
add_action('add_meta_boxes', 'kpt_hook_metaboxes' );
add_action('save_post', 'kpt_hook_save_post_idea', 1, 2); 
add_action('admin_init',  'kpt_hook_add_admin_style');
add_action('admin_init',  'kpt_hook_add_admin_script');
add_action( 'attachments_register', 'init_attachments' );

// database hooks
register_activation_hook(__FILE__, "kpt_create_db_tables");
register_uninstall_hook(__FILE__, "kpt_delete_db_tables");




function kpt_hook_init() {
	// add post types
	kpt_add_idea();
	kpt_add_localGroups();
	
	// register ajax callbacks
	NewIdeaForm::initAjax();
}


function kpt_create_db_tables() {
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	require_once('lower_saxony_list.php');
    global $wpdb;
    $table_name = $wpdb->prefix . "klimotion";
      
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
	id TINYINT NOT NULL ,
	name tinytext NOT NULL,
	UNIQUE KEY id (id)
    );";
	   
    dbDelta( $sql );
	foreach ($lower_saxony_array as $key => $value) {
		$rows_affected = $wpdb->insert( $table_name, array('id' => $key, 'name' => $value ));
	}
	
}


// TODO: noch zu testen! wer weiss ob die beim Uninstall tut was sie soll, die alte function.
function kpt_delete_db_tables() {
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	global $wpdb;
	
	$table_name = $wpdb->prefix . "klimotion";
	$sql = "DROP TABLE IF EXISTS $table_name;";
	
	dbDelta( $sql );
}	


function my_rewrite_flush() {
    kpt_hook_init();
    flush_rewrite_rules();
}


function kpt_hook_add_admin_style() {
	wp_enqueue_style('kpt-admin-style', plugins_url('css/klimoPT_admin.css', __FILE__) );
}


function kpt_hook_add_admin_script() {
	// adaptivetableinput
    wp_enqueue_script('adaptivetableinput', plugins_url('script/adaptiveTableInput.js', __FILE__), array('jquery'));
	wp_enqueue_script('kpt-admin-script', plugins_url('script/klimoPT_admin.js', __FILE__), array('jquery', 'adaptivetableinput'));
}


function kpt_hook_metaboxes() {
	add_meta_box('idea-post-meta-links', 'Links',  'kpt_hook_metabox_links', 'klimo_idea', 'normal', 'default');
	add_meta_box('idea-post-meta-group', 'Gruppe',  'kpt_hook_metabox_group', 'klimo_idea', 'side', 'default');
}


function kpt_hook_metabox_group($post) {
	// get current _links meta
	$idea_group_meta_slug = '_group';
    $group_meta = get_post_meta($post->ID, $idea_group_meta_slug, TRUE);
	if(!$group_meta) {
		$group_meta = -1;
	}
	
	// get local groups
	$groupQueryArgs = array( 'post_type' => 'klimo_localGroups', 'suppress_filters' => true, 'numberposts' => -1);
	$groups = get_posts( $groupQueryArgs );
	
	
	// render select dropdown
	echo '<input type="hidden" name="groupmeta_nonce" id="groupmeta" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	echo '<select name="meta-group" id="meta-group">';
	echo '<option value="-1" ' . (($group_meta == -1)? 'selected="selected"' : '') . '>keine Gruppe</option>';
	foreach ($groups as $group) {
		echo '<option value="' . $group->ID . '" ' . (($group_meta == $group->ID)? 'selected="selected"' : '') . '>' . $group->post_title . '</option>';
	}
	
	echo '</select>';	
}


function kpt_hook_metabox_links($post) {
	// get current _links meta
	$idea_links_meta_slug = '_links';
    $links_meta = get_post_meta($post->ID, $idea_links_meta_slug, TRUE);
	if(!$links_meta) {
		$links_meta = array(array('text' => '', 'url' => ''));
	}

	// create html
	echo '<input type="hidden" name="linksmeta_nonce" id="linksmeta" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	echo '<table id="meta-links">';
	echo '<thead>
			<tr>
				<th class="left"><label for="linksmetatext">Text</label></th>
				<th><label for="linksmetavalue">URL</label></th>
				<th></th>
			</tr>
		</thead>
		<tbody>';

	
	$i = 0;
	foreach ($links_meta as $i => $link) {
		echo '<tr class="links_meta_pair">';
		echo '<td><input type="text" maxlength="40" name="_linktext_' . $i . '" value="' . $link['text']  . '"></td>';
		echo '<td><input type="text" name="_linkurl_' . $i . '" value="' . $link['url']  . '"></td>';
		echo '<td><a class="removelink" href="#" onclick="return false;">entfernen</a></td></tr>';
	}
	
	echo '</tbody></table>';
	echo '<a id="addlink" href="#" onclick="return false;">hinzufügen</a>';
}



function kpt_hook_save_post_idea($post_id, $post) {

	// authorization,
	if ( !array_key_exists ( 'post_type' , $_POST ) || 'klimo_idea' != $_POST['post_type'] )
		return;
	if ( !current_user_can( 'edit_post', $post->ID ))
		return;
	if ( !wp_verify_nonce( $_POST['linksmeta_nonce'], plugin_basename(__FILE__) ))
		return;
	
	
	
	// save local group
	$new_group_id = $_POST['meta-group'];
	$group_meta_slug = '_group';
	if($new_group_id == -1) {
		delete_post_meta($post->ID, $group_meta_slug);
	} else {
		update_post_meta($post->ID, $group_meta_slug, $new_group_id);
	}
	
	
	// save links
	$new_links = array();
	for ($i=0; ;$i++) { 
		$keyText = '_linktext_' . $i;
		$keyUrl = '_linkurl_' . $i;
		if(!array_key_exists ( $keyText , $_POST ) || !array_key_exists ( $keyUrl , $_POST ))
			break;
		$valText  = trim(wp_strip_all_tags($_POST[$keyText]));
		$valUrl  = trim(wp_strip_all_tags($_POST[$keyUrl]));
		
		if(strlen($valUrl))
			$new_links[] = array('text' => $valText, 'url' => $valUrl);
	}

	// update post link meta
	$idea_links_meta_slug = '_links';
	update_post_meta($post->ID, $idea_links_meta_slug, $new_links);
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
        'supports'      => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'comments', 'attachment'),
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
        "hierarchical"      => false,
        "label"             => "Landkreise",
        "singular_label"    => "Landkreis",
        "rewrite"           => true,
    );
        
        
    register_taxonomy("klimo_localGroups_states", array("klimo_localGroups"), $post_taxonomy_args);
    register_post_type('klimo_localGroups', $post_type_args);
}




?>