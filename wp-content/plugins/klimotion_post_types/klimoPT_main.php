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


/* front end action hooks */
register_activation_hook( __FILE__, 'my_rewrite_flush' );



function my_rewrite_flush() {
    flush_rewrite_rules();
}




?>