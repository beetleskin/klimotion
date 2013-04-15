<?php
/**
 * @package Klimotion_Post_Types
 */

 
/* back end action hooks */
add_action('add_meta_boxes', 'kpt_hook_metaboxes' );
add_action('save_post', 'kpt_hook_save_post_idea', 1, 2);
add_action('save_post', 'kpt_hook_save_post_group', 1, 2);
add_action('admin_init',  'kpt_hook_add_admin_style');
add_action('admin_init',  'kpt_hook_add_admin_script');
 
 
 
function kpt_hook_add_admin_style() {
	wp_enqueue_style('kpt-admin-style', plugins_url('css/klimoPT_admin.css', __FILE__) );
	wp_enqueue_style('jquery.ui.theme','http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/smoothness/jquery-ui.css', false);
	wp_enqueue_style('jquery.ui.multiselect', plugins_url('script/jquery.ui.multiselect/jquery.multiselect.css', __FILE__) );
}


function kpt_hook_add_admin_script() {
	// adaptivetableinput
    wp_enqueue_script('adaptivetableinput', plugins_url('script/adaptiveTableInput.js', __FILE__), array('jquery'));
	// multiselect
	wp_enqueue_script('jquery.ui.multiselect', plugins_url('script/jquery.ui.multiselect/src/jquery.multiselect.min.js', __FILE__), array('jquery-ui-core', 'jquery-ui-widget'));
	// kpt admin script
	wp_enqueue_script('kpt-admin-script', plugins_url('script/klimoPT_admin.js', __FILE__), array('jquery', 'adaptivetableinput'));
}


function kpt_hook_metaboxes() {
	// idea
	add_meta_box('idea-post-meta-links', 'Links',  'kpt_hook_metabox_idea_links', 'klimo_idea', 'normal', 'default');
	add_meta_box('idea-post-meta-group', 'Gruppe',  'kpt_hook_metabox_idea_groups', 'klimo_idea', 'side', 'default');
	// group
	add_meta_box('group-post-meta', 'Meta',  'kpt_hook_metabox_group_meta', 'klimo_localgroup', 'side', 'default');
	add_meta_box('group-post-meta-contact', __('Kontakt'),  'kpt_hook_metabox_contact', 'klimo_localgroup', 'normal', 'default');
}


function kpt_hook_metabox_idea_groups($post) {
	
	$groups_selected_list = kpt_get_localgroups_by_idea($post->ID, false);
	$groups_selected_IDs = array();
	foreach ($groups_selected_list as $group) {
		$groups_selected_IDs[] = $group->ID;
	}
	$groups_all = kpt_get_all_posts_by_type('klimo_localgroup', array('ID', 'post_title'));
	
	// render multiselect dropdown
	echo '<input type="hidden" name="groupmeta_nonce" id="groupmeta" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	echo '<select name="meta-group[]" id="meta-group" multiple="multiple">';
	foreach ($groups_all as $group) {
		echo '<option value="' . $group->ID . '" ' . (in_array($group->ID, $groups_selected_IDs)? 'selected="selected"' : '') . '>' . $group->post_title . '</option>';
	}
	
	echo '</select>';
}


function kpt_hook_metabox_idea_links($post) {
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
		echo '<td><input type="url" name="_linkurl_' . $i . '" value="' . $link['url']  . '"></td>';
		echo '<td><a class="removebutton" href="#" onclick="return false;">entfernen</a></td></tr>';
	}
	
	echo '</tbody></table>';
	echo '<a class="addbutton" href="#" onclick="return false;">hinzufügen</a>';
}


function kpt_hook_metabox_contact($post) {
	// get current _city meta
	$contact_meta_slug = '_contact';
    $contact_meta = get_post_meta($post->ID, $contact_meta_slug, true);
	
	// create html
	echo '<input type="hidden" name="groupmeta_nonce" id="groupmeta" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	echo '<table><tbody>';
	echo '<tr>';
	echo '<td><label>' . __("Name") . '</label></td>';
	echo '<td><input type="text" id="meta-group_contact_name" name="meta-group_contact_name" placeholder="Name" value="' . $contact_meta['name'] . '"></td></tr>';
	echo '</tr><tr>';
	echo '<tr>';
	echo '<td><label>' . __("Vorname") . '</label></td>';
	echo '<td><input type="text" id="meta-group_contact_surname" name="meta-group_contact_surname" placeholder="Vorname" value="' . $contact_meta['surname'] . '"></td></tr>';
	echo '</tr><tr>';
	echo '<tr>';
	echo '<td><label>' . __("E-Mail") . '</label></td>';
	echo '<td><input type="text" id="meta-group_contact_mail" name="meta-group_contact_mail" placeholder="E-Mail" value="' . $contact_meta['mail'] . '"></td></tr>';
	echo '</tr><tr>';
	echo '<tr>';
	echo '<td><label>' . __("Telefon") . '</label></td>';
	echo '<td><input type="text" id="meta-group_contact_phone" name="meta-group_contact_phone" placeholder="Telefon" value="' . $contact_meta['phone'] . '"></td></tr>';
	echo '</tr><tr>';
	echo '<tr>';
	echo '<td><label>' . __("Öffentlich") . '</label></td>';
	echo '<td><input type="checkbox" id="meta-group_contact_publish" name="meta-group_contact_publish"' . (($contact_meta['publish'] == True)? 'checked="checked"' : '') . '"></td></tr>';
	echo '</tr><tr>';
	echo '</tbody></table>';
}


function kpt_hook_metabox_group_meta($post) {
	// get current _city meta
	$city_meta_slug = '_city';
    $city_meta = get_post_meta($post->ID, $city_meta_slug, true);
	$zipcode_meta_slug = '_zip';
    $zipcode_meta = get_post_meta($post->ID, $zipcode_meta_slug, true);
    $homepage_meta_slug = '_homepage';
    $homepage_meta = get_post_meta($post->ID, $homepage_meta_slug, true);
	
	// create html
	echo '<input type="hidden" name="groupmeta_nonce" id="groupmeta" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	echo '<table><tbody>';
	echo '<tr>';
	echo '<td><label>' . __("Homepage") . '</label></td>';
	echo '<td><input type="text" id="meta-group_homepage" name="meta-group_homepage" placeholder="Homepage" value="' . $homepage_meta . '"></td></tr>';
	echo '</tr><tr>';
	echo '<td><label>' . __("Ort") . '</label></td>';
	echo '<td><input type="text" id="meta-group_city" name="meta-group_city" placeholder="Ort" value="' . $city_meta . '"></td></tr>';
	echo '</tr><tr>';
	echo '<td><label>' . __("Postleitzahl") . '</label></td>';
	echo '<td><input type="text" id="meta-group_zipcode" name="meta-group_zipcode" placeholder="Postleitzahl" value="' . $zipcode_meta . '"></td>';
	echo '</tr>';
	echo '</tbody></table>';
}


function kpt_hook_save_post_group($post_id, $post) {
	// authorization,
	if ( !array_key_exists ( 'post_type' , $_POST ) || 'klimo_localgroup' != $_POST['post_type'] )
		return;
	if ( $_POST['action'] != 'editpost' || !current_user_can( 'edit_post', $post->ID ))
		return;
	if ( !wp_verify_nonce( $_POST['groupmeta_nonce'], plugin_basename(__FILE__) ))
		return;
	
	
	// save homepage
	$new_homepage = $_POST['meta-group_homepage'];
	$new_homepage = preg_match('/^(https?|ftps?|mailto|news|gopher|file):/is', $new_homepage) ? $new_homepage : 'http://' . $new_homepage;
	update_post_meta($post->ID, '_homepage', $new_homepage);
	
	// save city
	$new_city = $_POST['meta-group_city'];
	update_post_meta($post->ID, '_city', $new_city);
	
	// save zip code
	$new_zipcode = $_POST['meta-group_zipcode'];
	update_post_meta($post->ID, '_zip', $new_zipcode);
	
	// save contact
	$new_contactData = array(
		'name'		=> $_POST['meta-group_contact_name'],
		'surname'	=> $_POST['meta-group_contact_surname'],
		'mail'		=> $_POST['meta-group_contact_mail'],
		'phone'		=> $_POST['meta-group_contact_phone'],
		'publish'	=> (bool)array_key_exists('meta-group_contact_publish', $_POST),
	);
	$contact_meta_slug = '_contact';
	update_post_meta($post->ID, $contact_meta_slug, $new_contactData);
}



function kpt_hook_save_post_idea($post_id, $post) {

	// authorization,
	if ( !array_key_exists ( 'post_type' , $_POST ) || 'klimo_idea' != $_POST['post_type'] )
		return;
	if ( $_POST['action'] != 'editpost' || !current_user_can( 'edit_post', $post->ID ))
		return;
	if ( !wp_verify_nonce( $_POST['linksmeta_nonce'], plugin_basename(__FILE__) ))
		return;
	
	
	
	// save local groups
	$old_groups = kpt_get_localgroups_by_idea($post->ID);
	$new_group_ids = array_key_exists('meta-group', $_POST)? $_POST['meta-group'] : array();
	foreach ($old_groups as $old_group) {
		if(! in_array($old_group->ID, $new_group_ids)) {
			kpt_delete_idea_group_relation($post->ID, $old_group->ID);
		}
	}
	foreach ($new_group_ids as $group_id) {
		kpt_insert_idea_group_relation($post->ID, $group_id);
	}
	
	
	
	// save links
	$new_links = array();
	for ($i=0; ;$i++) { 
		$keyText = '_linktext_' . $i;
		$keyUrl = '_linkurl_' . $i;
		if(!array_key_exists ( $keyText , $_POST ) || !array_key_exists ( $keyUrl , $_POST ))
			break;
		$valText  = wp_strip_all_tags($_POST[$keyText], true);
		$valUrl  = esc_url_raw($_POST[$keyUrl]);
		
		if(strlen($valUrl)) {
			$valUrl = preg_match('/^(https?|ftps?|mailto|news|gopher|file):/is', $valUrl) ? $valUrl : 'http://' . $valUrl;
			$new_links[] = array('text' => $valText, 'url' => $valUrl);
		}
	}

	// update post link meta
	$idea_links_meta_slug = '_links';
	update_post_meta($post->ID, $idea_links_meta_slug, $new_links);
}

 
 
?>