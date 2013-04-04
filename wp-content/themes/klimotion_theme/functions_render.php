<?php 


/* front end action hooks */
add_action('init', 'LocalGroupsPage::initAjax');


class LocalGroupsPage {
	
	private static $ioConfig = array();
	private static $nonceName = "klimoLocalGroupNonce";
	private static $defaultQuery = array( 'post_type' => array('klimo_localGroups') );
	
	
	
	public static function initAjax() {
		self::$ioConfig['ajaxurl'] = admin_url('admin-ajax.php');
		self::$ioConfig['submitAction'] = 'localgroupspage_query';

		// register ajax request
        add_action('wp_ajax_' . self::$ioConfig['submitAction'], 'LocalGroupsPage::ajaxGetGroups');
	}
	
	public static function ajaxGetGroups() {
		
		// check nonce
		if(!self::securityCheck($_REQUEST, 'nonsense')) {
			$message = '<div id="securityErrorMessage"><p>Sorry, deine Session ist abgelaufen ... </p><a href="' . get_home_url() . '">Hier </a>gehts weiter</div>';
			$response['securityError'] = array(
                'redirect' => get_home_url(),
                'message'  => $message,
            );
			header("Content-Type: text/plain");
			echo json_encode($response);
			die();
		}
		
		header("Content-Type: text/plain");
		echo json_encode($_REQUEST);
		die();
	}
	
	
	private static function securityCheck(&$args, $nonceKey) {
        if(key_exists($nonceKey, $args) && wp_verify_nonce($args[$nonceKey], self::$nonceName) == 1) {
			return true;
        }
		
		return false;
	}
	
	
	
	
	
	
	
	public function postRender() {
		// add scripts
		wp_enqueue_script('jvectormap', get_stylesheet_directory_uri() . '/script/jquery-jvectormap-1.2.2/jquery-jvectormap-1.2.2.min.js', array('jquery'));
		wp_enqueue_script('jvectormap_losaxony', get_stylesheet_directory_uri() . '/script/jquery-jvectormap-1.2.2/lower_saxony_map.js', array('jquery'));
		wp_enqueue_script('klimo_localgroupspage', get_stylesheet_directory_uri() . '/script/klimo_localgroupspage.js', array('jquery', 'jvectormap', 'jvectormap_losaxony'));
		
		
		// add group map styles
		wp_enqueue_style('jvectormap', get_stylesheet_directory_uri() . '/script/jquery-jvectormap-1.2.2/jquery-jvectormap-1.2.2.css');
		wp_enqueue_style('klimo_localgroupspage', get_stylesheet_directory_uri() . '/css/localgroupspage.css');
		
		// print ajax config
        $ioConfig = self::$ioConfig;
        $ioConfig['nonsense'] = wp_create_nonce (self::$nonceName);
		$vals = array();
		for ($i=0; $i < 47; $i++) { 
			$vals[''.$i] = $i;
		}
		
		$data = array(
			'ajaxConfig'	=> $ioConfig,
			'map'			=> 'lower_saxony_de',
			'mapVals'		=> $vals,
		);
        
        // Print data to sourcecode
        wp_localize_script('klimo_localgroupspage', 'localgroupspage_config', $data);
	}
	
	
	
	
	public function renderMap() {
		?>
			<div id="groupmap"></div>
			
		<?php
	}
	
	
	
	public function local_groups_query($args = array()) {
		$newQuery = query_posts( array_merge($args, self::$defaultQuery) );
		return $newQuery;
	}
}

?>