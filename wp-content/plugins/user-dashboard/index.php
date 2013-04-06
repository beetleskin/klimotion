<?php
/*
Plugin Name: User-Dashboard
Plugin URI: http://mecontact.wordpress.com/user-dashboard/
Description: User-Dashboard include the option with login, registration and forget password from front-end also provide you the option to logged out profile editing and image uploading for profile . it provides you proper dashboard from front end user to add more options and edit html easily without any coding problem.<strong>Use full width page for best view and to use this plugin please add [user-dashboard] in your page editor from backend</strong>
Version: 1.0.0
Author: anil kumar
Author URI: http://about.me/anilDhiman
License: GPLv2 or later
*/
include( plugin_dir_path( __FILE__ ) . 'includes.php');

function udashboard(){
	$action	=	(isset($_REQUEST['ac'])) ? $_REQUEST['ac'] : 'none';
	switch($action){
		case 'login':
			MJdashboard::LoggedInUser();
			MJdashboard::LoggedProcess();
			MJdashboard::LogScreen();
		break;
		case 'dashboard':
			MJdashboard::LoggedoutUser();
			MJdashboard::Dashboard();
		break;
		case 'lostpass':
			MJdashboard::LoggedInUser();
			MJdashboard::PassProcess();
			MJdashboard::Lostpassword();
		break;
		case 'registration':
			MJdashboard::LoggedInUser();
			MJdashboard::RegProcess();
			MJdashboard::Registration();
		break;
		case 'profile':
			MJdashboard::LoggedoutUser();
			MJdashboard::ProProcess();
			MJdashboard::profile();
		break;
		case 'profileimage':
			MJdashboard::LoggedoutUser();
			MJdashboard::imageprocess();
			MJdashboard::Imageupload();
		break;
		default:
			MJdashboard::LoggedInUser();
			MJdashboard::LoggedProcess();
			MJdashboard::Logincontrols();
		break;
	}	
}
function setting_page(){
	MJadmin::SettingPage();
}

add_shortcode('user-dashboard','udashboard');
?>