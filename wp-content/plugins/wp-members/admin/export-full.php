<?php
/**
 * WP-Members Export Functions
 *
 * The full user list to a CSV file.
 * 
 * This file is part of the WP-Members plugin by Chad Butler
 * You can find out more about this plugin at http://rocketgeek.com
 * Copyright (c) 2006-2013  Chad Butler (email : plugins@butlerblog.com)
 * WP-Members(tm) is a trademark of butlerblog.com
 *
 * @package WordPress
 * @subpackage WP-Members
 * @author Chad Butler
 * @copyright 2006-2013
 */


/** 
 * WordPress Administration Bootstrap 
 */
include( '../../../../wp-load.php' );
include( '../../../../wp-admin/includes/admin.php' );


/**
 * Prevent access by users who should not
 * have access to the user list.
 */
if ( !current_user_can( 'list_users' ) )
	wp_die( __( 'Cheatin&#8217; uh?' ) );


/**
 * Output needs to be buffered, start the buffer
 */
ob_start();


/**
 * Get all of the users
 */
$user_arr = get_users();


/**
 * Generate headers and a filename based on date of export
 */
$today = date( "m-d-y" ); 
$filename = "user-export-" . $today . ".csv";
header( "Content-type: application/octet-stream" );
header( "Content-Disposition: attachment; filename=\"$filename\"" );


/**
 * get the fields
 */
$wpmem_fields = get_option( 'wpmembers_fields' );

/**
 * do the header row
 */
$hrow = "User ID,Username,";
for( $row = 0; $row < count( $wpmem_fields ); $row++ ) {
	$hrow.= $wpmem_fields[$row][1] . ",";
}

if( WPMEM_MOD_REG == 1 ) {
	$hrow.= __( 'Activated?', 'wp-members' ) . ",";
}
if( WPMEM_USE_EXP == 1 ) {
	$hrow.= __( 'Subscription', 'wp-members' ) . "," . __( 'Expires', 'wp-members' ) . ",";
}

$hrow.= __( 'Registered', 'wp-members' ) . ",";
$hrow.= __( 'IP', 'wp-members' );
$data = $hrow . "\r\n";

/**
 * we used the fields array once,
 * rewind so we can use it again
 */
reset( $wpmem_fields );

/**
 * Loop through the array of users,
 * build the data, delimit by commas, wrap fields with double quotes, 
 * use \n switch for new line
 */
foreach( $user_arr as $user ) {

 	$data.= '"' . $user->ID . '","' . $user->user_login . '",';
	
	for( $row = 0; $row < count( $wpmem_fields ); $row++ ) {
	
		if( $wpmem_fields[$row][2] == 'user_email' ) {
			$data.= '"' . $user->user_email . '",';
		} else {
			$data.= '"' . get_user_meta( $user->ID, $wpmem_fields[$row][2], true ) . '",';
		}
		
	}
	
	if( WPMEM_MOD_REG == 1 ) {
	
		if( get_user_meta( $user->ID, 'active', 1 ) ) {
			$data.= '"' . __( 'Yes', 'wp-members' ) . '",';
		} else {
			$data.= '"' . __( 'No', 'wp-members' ) . '",';
		}
		
	}

	if( WPMEM_USE_EXP ==1 ) {
		$data.= '"' . get_user_meta( $user->ID, "exp_type", true ) . '",';
		$data.= '"' . get_user_meta( $user->ID, "expires", true ) . '",';
	}
	
	$data.= '"' . $user->user_registered . '",';
	$data.= '"' . get_user_meta( $user->ID, "wpmem_reg_ip", true ) . '"';
	$data.= "\r\n";

}

/**
 * We are done, output the CSV
 */
echo $data; 

/**
 * Clear the buffer 
 */
ob_flush();
?>