<?php
/**
 * @package Klimotion_Post_Types
 */
 

include_once('klimoPT_idea_form.php');
include_once('klimoPT_group_form.php');


/* front end action hooks */
add_action('init', 'kpt_fe_hook_init');


function kpt_fe_hook_init() {
	// register ajax callbacks
	NewIdeaForm::initAjax();
	NewGroupForm::initAjax();
}
 
 
?>