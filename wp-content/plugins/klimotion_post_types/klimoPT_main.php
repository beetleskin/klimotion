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


include_once('klimoPT_data.php');
include_once('klimoPT_admin.php');
include_once('klimoPT_frontend.php');


register_activation_hook(__FILE__, 'kpt_activate');
register_activation_hook(__FILE__, 'kpt_create_menu');
register_activation_hook(__FILE__, 'kpt_change_roles');
register_uninstall_hook(__FILE__, 'kpt_uninstall');

add_action('init', 'kpt_data_init');


function kpt_change_roles() {
	$subscriber_role = get_role('subscriber');
	$subscriber_role->add_cap("edit_posts");
}



?>