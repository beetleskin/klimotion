<?php
	
	// Caution: This file does not do anything at the moment. It is ment for possible future use.
	
	$root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
	require_once($root.'/wp-load.php');
	
	$args = array(
		"post_parent"=>$_GET["post_id"],
		"numberposts"=>-1,
		"post_type" => "attachment"
	);
	$attachments = get_children($args);
	foreach($attachments as $att){
		echo "<h3>ID {$att->ID}: {$att->post_title}</h3>";
		// for changing native keys
		$attach_post					= array();
		$attach_post['ID']				= $att->ID;
		$attach_post['post_title']		= "Some Text";
		//wp_update_post($attach_post);
		//$new_att = get_post($att->ID);
		//echo "This is from post_content: {$new_att->post_content} <br />";
		//var_dump($new_att);
		
		// for adding custom meta data
		$custom_value = "<strong>This</strong> is some text added by update_post_meta.";
		//update_post_meta($att->ID, 'my_key', $custom_value);
		//$custom = get_post_meta($att->ID, "my_key");
		//echo $custom[0];
		
	}
?>