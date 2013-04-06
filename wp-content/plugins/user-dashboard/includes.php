<?php
ob_start();
$upload_dir	=	wp_upload_dir();
/* defined variables */
DEFINE('NAME', basename(dirname(__FILE__)));
DEFINE('IMGPATH',plugins_url().'/'.NAME.'/images/');
DEFINE('DASHBOARD',add_query_arg(array('ac' => 'dashboard'), get_permalink()));
DEFINE('LOGIN',add_query_arg(array('ac' => 'login'), get_permalink()));
DEFINE('LOSTPASS',add_query_arg(array('ac' => 'lostpass'), get_permalink()));
DEFINE('REGISTRATION',add_query_arg(array('ac' => 'registration'), get_permalink()));
DEFINE('PROFILE',add_query_arg(array('ac' => 'profile'), get_permalink()));
DEFINE('PROFILEIMAGE',add_query_arg(array('ac' => 'profileimage'), get_permalink()));
DEFINE('PROFILEIMAGEDIR',$upload_dir['baseurl']);
/* includes files */
require_once(ABSPATH . WPINC . '/registration.php');
include( plugin_dir_path( __FILE__ ) . 'filters.php');
include( plugin_dir_path( __FILE__ ) . 'baseclass.php');
include( plugin_dir_path( __FILE__ ) . 'adminclass.php');
?>