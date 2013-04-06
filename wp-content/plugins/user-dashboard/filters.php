<?php
add_filter( 'lostpassword_url', 'custom_lostpass_url' );
function custom_lostpass_url( $lostpassword_url ) {
    $new_query = add_query_arg(array('ac' => 'lostpass'), get_permalink());
	return $new_query;
}
?>