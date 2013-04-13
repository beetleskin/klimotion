<?php
/**
 * @package Klimotion_Post_Types
 */

 
 

class NewGroupForm {

    private $form_action;
    private $form_id;
    private $form_method;
	private $renderData;
    
    private static $ioConfig = array();
	private static $nonceName = "klimoGroupFormNonce";
	private static $validationConfig = array(
        'name_max_chars'     	=> 200,
        'name_min_chars'     	=> 3,
        'city_max_chars'		=> 200,
        'zipcode_chars'			=> 5,
        'scope_min'				=> 1,
        'description_max_chars' => 5000,
        'homepage_max_chars'	=> 300,
        'contact_max_chars'		=> 200,
        'image_size_max'		=> 5000000,
    );
    

    function __construct() {
        $this -> form_action = "";
        $this -> form_id = "groupform";
        $this -> form_method = "POST";
    }

    function preRender() {
        $data = array();
        
		// defaults
        $nopriv = 'nopriv';
        $submitLink = wp_login_url(get_permalink());
        $onClick = '';
		$isLoggedIn = get_current_user_id() != 0;
        
		// login settings
        if($isLoggedIn) {
            $nopriv = '';
            $submitLink = '#';
            $onClick = 'onclick="return false;"';
        }
		
		
		// districts:
		$districts = get_terms("klimo_districts", array(
            'hide_empty'    => false,
            'hierarchical'  => false,
            'order_by'      =>'count'
        ));
		$data['districts'] = array();
		foreach ($districts as &$district) {
			$data['districts'][] = array("value" => $district->term_id, "name" => $district->name);
		}
		
		
		// scopes:
		// TODO: Wen keine 'scopes' in der Datenbank liegen, scheiß AutoSuggest ab und das Formular funktioniert nicht richtig.
		$scopes = get_terms("klimo_scopes", array(
            'hide_empty'    => false,
            'hierarchical'  => false,
            'order_by'      =>'count'
        ));
		$data['scopes'] = array();
		foreach ($scopes as &$scope) {
			$data['scopes'][] = array("value" => $scope->term_id, "name" => $scope->name);
		}

		

		
		// leftover
        $data['isLoggedIn'] = $isLoggedIn;
        $data['nopriv'] = $nopriv;
        $data['submitLink'] = $submitLink;
        $data['onClick'] = $onClick;
		
		$this->renderData = &$data;
        return $data;
    }
	
	function postRender() {
		$this->enqueue_styles();
	    $this->enqueue_scripts();
	    $this->printAjaxConfig();
	}


    function render() {

        $data = $this->preRender();
        ?>
        
        // display hint if not logged in
        <?php if($data['isLoggedIn'] == false) : ?>
        <div class="entry-content">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content', 'page' ); ?>
		<?php endwhile; // end of the loop. ?>
        </div>
        <?php endif; ?>
        
        
        <div id="groupform_wrap">   	
		    <form action="<?php echo $this->form_action ?>" id="<?php echo $this->form_id ?>" class="<?php echo $data['nopriv'] ?>" method="<?php echo $this->form_method ?>">
	        	<h1><?php echo __("Gruppe-Formular") ?></h1>
	        	<div id="errormessage"></div>
	        
	        	<fieldset>
	        		<div class="form-field-wrap">
			      	  	<label for="group_name"><?php echo __("Name der Lokalgruppe") ?></label>
			        	<input type="text" id="group_name" name="group_name" placeholder="Name" maxlength="<?php echo self::$validationConfig['name_max_chars'] ?>">
		        	</div><!-- .form-field-wrap -->
		        
		        	<div class="form-field-wrap">
				        <label for="group_district"><?php echo __("Landkreis") ?></label>
				        <select id="group_district" name="group_district">
				        	<?php foreach ( $data['districts'] as &$district ): ?>
			                    <option value="<?php echo $district['value']; ?>"><?php echo $district['name']; ?></option>
			                <?php endforeach; ?>
				        </select>
			        </div><!-- .form-field-wrap -->
			        
			        <div class="form-field-wrap">
				        <label for="group_city"><?php echo __("Ort") ?></label>
						<input type="text" id="group_city" name="group_city"  placeholder="Ort">
						<input type="text" id="group_zipcode" name="group_zipcode"  placeholder="Postleitzahl">
					</div><!-- .form-field-wrap -->
					
					<div class="form-field-wrap">
						<label for="group_scopes"><?php echo __("Wirkungskreis") ?></label>
						<input type="text" id="group_scopes" name="group_scopes">
					</div><!-- .form-field-wrap -->
					
					<div class="form-field-wrap">
						<label for="groupdescription"><?php echo __("Kurzvorstellung") ?></label>
				        <?php wp_editor("", 'groupdescription', array(
				        	'media_buttons' => false,
				        	'textarea_name' => 'group_description',
				        	'tabindex'		=> 0
							));
						?>
					</div><!-- .form-field-wrap -->
					
					<div class="form-field-wrap">
  	  					<label for="group_image">Logo</label>
  	  					<input type="file" id="group_image" name="group_image" accept="image/*">
  	  				</div><!-- .form-field-wrap -->
			        
			        <div class="form-field-wrap">
			        	<label for="group_homepage"><?php echo __("Homepage") ?></label>
			        	<input type="url" id="group_homepage" name="group_homepage" placeholder="www.deineseite.de">
			        </div><!-- .form-field-wrap -->
		        </fieldset>
		        
		        
		        <fieldset>
			        <h1><?php echo __("Ansprechpartner") ?></h1>
					<div class="form-field-wrap">
						<label for="group_contact_name"><?php echo __("Name") ?></label>
						<input type="text" id="group_contact_name" name="group_contact_name"  placeholder="Name">
					</div><!-- .form-field-wrap -->
					
					<div class="form-field-wrap">
						<label for="group_contact_surname"><?php echo __("Vorname") ?></label>
						<input type="text" id="group_contact_surname" name="group_contact_surname"  placeholder="Vorname">
					</div><!-- .form-field-wrap -->
					
					<div class="form-field-wrap">
						<label for="group_contact_mail"><?php echo __("E-Mail") ?></label>
						<input type="email" id="group_contact_mail" name="group_contact_mail"  placeholder="E-Mail">
					</div><!-- .form-field-wrap -->
					
					<div class="form-field-wrap">
						<label for="group_contact_phone"><?php echo __("Telefon") ?></label>
						<input type="email" id="group_contact_phone" name="group_contact_phone"  placeholder="Telefon">
					</div><!-- .form-field-wrap -->
					
					<div class="form-field-wrap">
						<label for="group_contact_publish"><?php echo __("öffentlich") ?></label>
						<input type="checkbox" id="group_contact_publish" name="group_contact_publish" value="group_contact_publish" checked="checked">
					</div><!-- .form-field-wrap -->
				</fieldset>

		        <div class="group_submit_container">
		        	<a href="<?php echo $data['submitLink'] ?>" <?php echo $data['onClick'] ?> id="group_submit">Abschicken</a>
		        </div>
        	</form>
       </div><!-- .groupform_wrap -->
	<?php
	}


    public function printAjaxConfig() {
    	// add security check
        $ioConfig = self::$ioConfig;
        $ioConfig[self::$nonceName] = wp_create_nonce  (self::$nonceName);
		$formData = array(
			'ajaxConfig' 	=> $ioConfig,
			'as_scopes'		=> (object)(array('items' => $this->renderData['scopes'])),
		);
        
        // Print data to sourcecode
        wp_localize_script('klimo_groupform', 'groupform_config', $formData);
    }


    function enqueue_scripts() {
    	// autosuggest
        wp_enqueue_script('autosuggest', plugins_url('script/autoSuggestv14/jquery.autoSuggest.packed.js', __FILE__), array('jquery'));
		// adaptiveTableInput
        wp_enqueue_script('adaptivetableinput', plugins_url('script/adaptiveTableInput.js', __FILE__), array('jquery'));
		// inputCounter
		wp_enqueue_script('inputCounter', plugins_url('script/inputCounter.js', __FILE__), array('jquery'));
		// multiselect
		wp_enqueue_script('jquery.ui.multiselect', plugins_url('script/jquery.ui.multiselect/src/jquery.multiselect.min.js', __FILE__), array('jquery-ui-core', 'jquery-ui-widget'));
		
    	// frontend forms script
        wp_enqueue_script('klimo_groupform', plugins_url('script/klimoPT.groupform.js', __FILE__), array('jquery', 'jquery-form', 'autosuggest', 'adaptivetableinput'));
    }
    
    
    function enqueue_styles() {
    	// multiselect style
		wp_enqueue_style('jquery.ui.multiselect', plugins_url('script/jquery.ui.multiselect/jquery.multiselect.css', __FILE__) );
    	// jquery ui theme
    	wp_enqueue_style('jquery.ui.theme','http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/smoothness/jquery-ui.css', false);
    	// autosuggest style
        wp_enqueue_style('autosuggest', plugins_url('script/autoSuggestv14/autoSuggest.css', __FILE__));
		
		// form styles
		wp_enqueue_style('klimo_frontend_forms', plugins_url('css/klimoPT_forms.css', __FILE__));
    }


    public static function initAjax() {
    	self::$ioConfig['ajaxurl'] = admin_url('admin-ajax.php');
		self::$ioConfig['submitAction'] = 'groupform_submit-ajax';
    	
		// register form ajax
        add_action('wp_ajax_' . self::$ioConfig['submitAction'], 'NewGroupForm::submitHandler');
    }


    public static function submitHandler() {
    	
		$postData = array();
		
		
		// security check and validations
		$securityVeto = self::securityCheck($_REQUEST);
		$validationVeto = self::validate($_REQUEST, $_FILES, $postData);
		if ( !empty($securityVeto))  {
			self::ajaxRespond($securityVeto);
			die();
		} else if( !empty($validationVeto)) {
			self::ajaxRespond($validationVeto);
			die();
		}
		
		
		// add post
		// collect post values
		$errors = array();
        $group_name = $postData['group_name'];
		$group_description = $postData['group_description'];
		$group_district_id = $postData['group_district'];
		$group_scopes = $postData['group_scopes'];
		
		
		$post_args = array(
            'ping_status'   => 'open',
            'post_status'   => 'pending',
            'post_type'     => 'klimo_localgroup',
            'post_title'    => $group_name,
            'post_content'  => $group_description,
            'tax_input'		=> array(
            	'klimo_districts' => array(intval($group_district_id)),
            	'klimo_scopes' => $group_scopes,
			),
        );
		
		
		// create post
		$postID = wp_insert_post($post_args);
        if(is_wp_error($postID)) {
            header("HTTP/1.0 500 Internal Server Error");
            die();
        }
		
		
		// attach city
		$city_meta_slug = "_city";
		if(!empty($postData['group_city'])){
			update_post_meta($postID, $city_meta_slug, $postData['group_city']);
		}
		
		
		// attach zip code
		$city_meta_slug = "_zip";
		if(!empty($postData['group_zipcode'])){
			update_post_meta($postID, $city_meta_slug, $postData['group_zipcode']);
		}

		
		// attach zip code
		$homepage_meta_slug = "_homepage";
		if(!empty($postData['group_homepage'])){
			update_post_meta($postID, $homepage_meta_slug, $postData['group_homepage']);
		}


		// attach contact person
		$contact_meta_slug = "_contact";
		$contactData = array(
			'name'		=> $postData['group_contact_name'],
			'surname'	=> $postData['group_contact_surname'],
			'mail'		=> $postData['group_contact_mail'],
			'phone'		=> $postData['group_contact_phone'],
			'publish'	=> $postData['group_contact_publish']
		);
		update_post_meta($postID, $contact_meta_slug, $contactData);
		
		
		// attach featured image
		if( !empty($postData['group_image']['name']) ) {
            $attach_id = media_handle_upload( 'group_image', $postID );
            if(!is_wp_error($attach_id)) {
                // update_post_meta( $postID, '_thumbnail_id', $attach_id );
				set_post_thumbnail($postID, $attach_id);
            } else {
                $errors[] = array(
                    'element'   => 'group_image',
                    'message'   => $attach_id->get_error_message(),
				);
				wp_delete_post($postID, true);
				goto finish;
            }
        }


		// return
		finish: {
			$response = array();
	        if(empty($errors)) {
	            $response['success'] = self::createSuccessMessage($postID);
	        } else {
	            $response['error'] = $errors;
	        }
		}
		
            
			
			

        self::ajaxRespond($response);
        die();
    }


    public static function myHandleUploadError($file, $message) {
    }


    private static function validate(&$args, &$files, &$postData) {
    	$response = array();
		
		// check name
        $element = "group_name";
        $value = trim(wp_strip_all_tags($args[$element]));
        // too short?
        if(strlen($value) < self::$validationConfig['name_min_chars']) {
            $response['error'][] = array(
                'element'   => $element,
                'message'   => "Gib einen Namen für deine Lokalgruppe an (mindestens " . self::$validationConfig['name_min_chars'] . " Zeichen).",
            );
        // too long?
        } else if(strlen($value) > self::$validationConfig['name_max_chars']) {
            $response['error'][] = array(
                'element'   => $element,
                'message'   => "Der Gruppenname darf maximal " . self::$validationConfig['name_max_chars'] . " Zeichen lang sein.",
            );
        // check if post is already there
        } else {
            $matchingPosts = get_posts(array(
                'name' => $value,
                'post_type' => 'klimo_localgroup',
                'post_status' => 'publish',
                'posts_per_page' => 1,)
            );
            
            
            if($matchingPosts && count($matchingPosts) > 0) {
                $post = &$matchingPosts[0];
                $response['error'][] = array(
                    'element'   => $element,
                    'message'   => 'Diese Lokalgruppe gibt es <a href="' . get_post_permalink($post->ID , false) . '" title="' . $post->post_title . '" target="_blank">hier</a> schon!',
                );
            }
        }
		$postData[$element] = $value;
		
		
		// check district
		$element = 'group_district';
		$value = intval(wp_strip_all_tags($args[$element]));
		$postData[$element] = $value;
		
		
		// check city
		$element = 'group_city';
		$value = wp_strip_all_tags($args[$element]);
		if(empty($value)) {
            $response['error'][] = array(
                'element'   => $element,
                'message'   => "Gib eine Stadt für deine Lokalgruppe an.",
            );
        // too long?
        } else if(strlen($value) > self::$validationConfig['city_max_chars']) {
            $response['error'][] = array(
                'element'   => $element,
                'message'   => "Die Stadt deiner Lokalgruppe ist zu lang.",
            );
        }
		$postData[$element] = $value;
		
		
		// check zip code
		$element = 'group_zipcode';
		$value = wp_strip_all_tags($args[$element]);
		if(strlen($value) != 5) {
            $response['error'][] = array(
                'element'   => $element,
                'message'   => "Die Postleitzahl deiner Lokalgruppe ist ungültig.",
            );
		}
		$postData[$element] = $value;
		
		
		// check scopes
		$element = 'group_scopes';
		$value = explode(",", wp_strip_all_tags($args['as_values_group_scopes']));
		foreach ($value as &$scope) {
			if(is_numeric($scope))
				$scope = intval($scope);
		}
		$last = end($value);
		if( empty($last) ) array_pop($value);
		if( count($value) < self::$validationConfig['scope_min']) {
            $response['error'][] = array(
                'element'   => $element,
                'message'   => "Gib bitte mindestens " . self::$validationConfig['scope_min'] . " Wirkungsbereich" . ((self::$validationConfig['scope_min'] > 1)? "e" : "") . " an.",
            );
        }
		$postData[$element] = $value;
		
		
		// check description
		$element = 'group_description';
		$value = $args[$element];
        if(strlen($value) > self::$validationConfig['description_max_chars']) {
            $response['error'][] = array(
                'element'   => $element,
                'message'   => "Die Kurzbeschreibung deiner Lokalgruppe ist zu lang (maximal " . self::$validationConfig['description_max_chars'] . " Zeichen).",
            );
        }
		$postData[$element] = $value;
		

		// check homepage
		$element = 'group_homepage';
		$value = intval(wp_strip_all_tags($args[$element]));
        if(strlen($value) > self::$validationConfig['homepage_max_chars']) {
            $response['error'][] = array(
                'element'   => $element,
                'message'   => "Die URL deiner Lokalgruppe ist zu lang (maximal " . self::$validationConfig['homepage_max_chars'] . " Zeichen).",
            );
        }
		if(strlen($value)) {
			$value = preg_match('/^(https?|ftps?|mailto|news|gopher|file):/is', $value) ? $value : 'http://' . $value;
		}
		$postData[$element] = $value;
		

		// check contact name
		$element = 'group_contact_name';
		$value = trim(wp_strip_all_tags($args[$element]));
		if(empty($value)) {
            $response['error'][] = array(
                'element'   => $element,
                'message'   => "Gib den Nachnamen deiner Kontaktperson an.",
            );
        // too long?
        } else if(strlen($value) > self::$validationConfig['contact_max_chars']) {
            $response['error'][] = array(
                'element'   => $element,
                'message'   => "Der Nachname deiner Kontaktperson ist zu lang an (maximal " . self::$validationConfig['contact_max_chars'] . " Zeichen).",
            );
        }
		$postData[$element] = $value;
		
		
		// check contact surname
		$element = 'group_contact_surname';
		$value = trim(wp_strip_all_tags($args[$element]));
		if(empty($value)) {
            $response['error'][] = array(
                'element'   => $element,
                'message'   => "Gib den Vornamen deiner Kontaktperson an.",
            );
        // too long?
        } else if(strlen($value) > self::$validationConfig['contact_max_chars']) {
            $response['error'][] = array(
                'element'   => $element,
                'message'   => "Der Vorname deiner Kontaktperson ist zu lang an (maximal " . self::$validationConfig['contact_max_chars'] . " Zeichen).",
            );
        }
		$postData[$element] = $value;
		
		
		// check contact email
		$element = 'group_contact_mail';
		$value = trim(wp_strip_all_tags($args[$element]));
		if(empty($value)) {
            $response['error'][] = array(
                'element'   => $element,
                'message'   => "Gib die E-Mail-Adresse deiner Kontaktperson an.",
            );
        // too long?
        } else if(strlen($value) > self::$validationConfig['contact_max_chars']) {
            $response['error'][] = array(
                'element'   => $element,
                'message'   => "Die E-Mail-Adresse deiner Kontaktperson ist zu lang an (maximal " . self::$validationConfig['contact_max_chars'] . " Zeichen).",
            );
        }
		$postData[$element] = $value;
		
		
		// check contact phone
		$element = 'group_contact_phone';
		$value = trim(wp_strip_all_tags($args[$element]));
		if(empty($value)) {
            $response['error'][] = array(
                'element'   => $element,
                'message'   => "Gib die Telefonnummer deiner Kontaktperson an.",
            );
        // too long?
        } else if(strlen($value) > self::$validationConfig['contact_max_chars']) {
            $response['error'][] = array(
                'element'   => $element,
                'message'   => "Die Telefonnummer deiner Kontaktperson ist zu lang an (maximal " . self::$validationConfig['contact_max_chars'] . " Zeichen).",
            );
        }
		$postData[$element] = $value;



		// check publish
        $element = "group_contact_publish";
        $value = array_key_exists($element, $args);
		$postData[$element] = $value;
		
		
		
		// check featured image
        $element = "group_image";
        if( key_exists($element, $files) && !empty($files[$element]['name']) ) {
        	$value = $files[$element];
            if( $files[$element]['size'] > self::$validationConfig['image_size_max'] ) {
                $response['error'][] = array(
                    'element'   => $element,
                    'message'   => "Bilder dürfen nicht größer als " . (self::$validationConfig['image_size_max'] / 1000000) . " MB groß sein.",
                );
            } else {
            	$postData[$element] = $value;
            }
        }
		
		
		return $response;
    }


    private static function securityCheck(&$args) {
        $response = array();
        
        $nonce = NULL;
        if(key_exists(self::$nonceName, $args)) {
            $nonce = $args[self::$nonceName];
        }

        if( !isset($nonce) || wp_verify_nonce($nonce, self::$nonceName) != 1 ) {
            $response['securityError'] = array(
                'message'  => '<div id="securityErrorMessage"><p>Sorry, deine Session ist abgelaufen ... </p><a href=".">Neue Lokalgruppe erstellen</a></div>',
            );
        }
        
        return $response;
    }
    
	
    private static function createSuccessMessage($postID) {
		$postPermaLink = get_post_permalink($postID, false);
        
        $html = '<div id="submitSuccessMessage">';
        $html .= '<p>Deine Lokalgruppe wurde erfoglreich abgeschickt und wird von uns geprüft.</p>';
        $html .= '<div class="redirect thePost"><a href="' . $postPermaLink . '">Lokalgruppe ansehen</a></div>';
        $html .= '<div class="redirect newGroup"><a href=".">Neue Lokalgruppe erstellen</a></div>';
        $html .= '</div>';
        
        return $html;
    }
	
	public static function ajaxRespond(&$message) {
		header("Content-Type: text/plain");
        echo json_encode($message);
        die();
    }
}
?>