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
}


function kpt_hook_add_admin_script() {
	// adaptivetableinput
    wp_enqueue_script('adaptivetableinput', plugins_url('script/adaptiveTableInput.js', __FILE__), array('jquery'));
	wp_enqueue_script('kpt-admin-script', plugins_url('script/klimoPT_admin.js', __FILE__), array('jquery', 'adaptivetableinput'));
}


function kpt_hook_metaboxes() {
	// idea
	add_meta_box('idea-post-meta-links', 'Links',  'kpt_hook_metabox_idea_links', 'klimo_idea', 'normal', 'default');
	add_meta_box('idea-post-meta-group', 'Gruppe',  'kpt_hook_metabox_idea_group', 'klimo_idea', 'side', 'default');
	// group
	add_meta_box('group-post-meta', 'Meta',  'kpt_hook_metabox_group_meta', 'klimo_localgroup', 'side', 'default');
	add_meta_box('group-post-meta-contact', __('Kontakt'),  'kpt_hook_metabox_contact', 'klimo_localgroup', 'normal', 'default');
}


function kpt_hook_metabox_idea_group($post) {
	// get current _links meta
	$idea_group_meta_slug = '_group';
    $group_meta = get_post_meta($post->ID, $idea_group_meta_slug, TRUE);
	if(!$group_meta) {
		$group_meta = -1;
	}
	
	// get local groups
	$groupQueryArgs = array( 'post_type' => 'klimo_localgroups', 'suppress_filters' => true, 'numberposts' => -1);
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
		echo '<td><input type="text" name="_linkurl_' . $i . '" value="' . $link['url']  . '"></td>';
		echo '<td><a class="removelink" href="#" onclick="return false;">entfernen</a></td></tr>';
	}
	
	echo '</tbody></table>';
	echo '<a id="addlink" href="#" onclick="return false;">hinzufügen</a>';
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
    $city_meta = get_post_meta($post->ID, $city_meta_slug, TRUE);
	$zipcode_meta_slug = '_zip';
    $zipcode_meta = get_post_meta($post->ID, $zipcode_meta_slug, TRUE);
	
	// create html
	echo '<input type="hidden" name="groupmeta_nonce" id="groupmeta" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	echo '<table><tbody>';
	echo '<tr>';
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
	if ( !current_user_can( 'edit_post', $post->ID ))
		return;
	if ( !wp_verify_nonce( $_POST['groupmeta_nonce'], plugin_basename(__FILE__) ))
		return;
	
	
	// save city
	$new_city = $_POST['meta-group_city'];
	$city_meta_slug = '_city';
	update_post_meta($post->ID, $city_meta_slug, $new_city);
	
	// save zip code
	$new_zipcode = $_POST['meta-group_zipcode'];
	$zipcode_meta_slug = '_zip';
	update_post_meta($post->ID, $zipcode_meta_slug, $new_zipcode);
	
	// save contact
	$new_contactData = array(
		'name'		=> $_POST['meta-group_contact_name'],
		'surname'	=> $_POST['meta-group_contact_surname'],
		'mail'		=> $_POST['meta-group_contact_mail'],
		'phone'		=> $_POST['meta-group_contact_phone'],
		'publish'	=> array_key_exists('meta-group_contact_publish', $_POST),
	);
	$contact_meta_slug = '_contact';
	update_post_meta($post->ID, $contact_meta_slug, $new_contactData);
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

 
 
?>