<?php 


/* front end action hooks */
add_action('init', 'LocalGroupsPage::initAjax');
add_action('init', 'klimo_render_init');

function klimo_render_init() {
	wp_enqueue_style('thickbox');
	wp_enqueue_script('thickbox');
}

class LocalGroupsPage {
	
	private static $ioConfig = array();
	private static $nonceName = "klimoLocalGroupNonce";
	private static $defaultQuery = array( 
		'post_type' => array('klimo_localgroup') );
	
	
	
	public static function initAjax() {
		self::$ioConfig['ajaxurl'] = admin_url('admin-ajax.php');
		self::$ioConfig['submitAction'] = 'localgroupspage_query';
		

		// register ajax request
        add_action('wp_ajax_' . self::$ioConfig['submitAction'], 'LocalGroupsPage::ajaxGetGroups');
	}
	
	public static function ajaxGetGroups() {
		
		$response = array();
		
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

		// prepare query
		$district = $_REQUEST['district'];
		$query = self::local_groups_query(
			array(
				'tax_query' => array(array(
					'taxonomy' =>'klimo_districts',
					'field' => 'slug',
					'terms' => '_district_' . $district 
				))
			)
		);
		
		// query groups
		ob_start();
        while (have_posts()) : the_post();
            get_template_part('content', 'klimo_localgroup');
        endwhile;
	
		$buffer = ob_get_contents();
		ob_end_clean();
		
		
		if(empty($buffer)) {
			$termName = get_term_by( 'slug', '_district_' . $district, 'klimo_districts')->name;
			$response['success'] = '<div id="noqueryresults">Für ' . $termName . ' ist keine Gruppe eingetragen.</div>';
		} else {
			$response['success'] = $buffer;
		}
	    
	
		header("Content-Type: text/plain");
		echo json_encode($response);
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
		
		// generate map data
		$klimo_districts = get_categories(array(
			'type' 			=> 'klimo_localgroups',
			'hide_empty' 	=> 0,
			'hierarchical'	=> 0,
			'taxonomy'		=> 'klimo_districts',
		));
		$district_population = array();
		foreach ($klimo_districts as $district) {
			if( strpos( $district->slug, '_district_') === false )
				continue;
			$id = str_replace("_district_", '', $district->slug);
			$district_population[''.$id] = $district->count;
		}
		
		
		// print ajax config
        $ioConfig = self::$ioConfig;
        $ioConfig['nonsense'] = wp_create_nonce (self::$nonceName);
		
		
		$data = array(
			'ajaxConfig'	=> $ioConfig,
			'map'			=> 'lower_saxony_de',
			'mapVals'		=> $district_population,
		);
        
        // Print data to sourcecode
        wp_localize_script('klimo_localgroupspage', 'localgroupspage_config', $data);
	}
	
	
	
	
	public function renderMap() {
		?>
			<div id="groupmap"></div>
			
		<?php
	}
	
	
	
	public static function local_groups_query($args = array()) {
		$newQuery = query_posts( array_merge(self::$defaultQuery, $args) );
		return $newQuery;
	}
}

?>