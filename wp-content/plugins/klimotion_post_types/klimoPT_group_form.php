<?php
/**
 * @package Klimotion_Group_Map
 */

 
 

class NewGroupForm {

    private $form_action;
    private $form_id;
    private $form_method;
	private $renderData;
    
    private static $ioConfig = array();
	private static $nonceName = "klimoGroupFormNonce";
	private static $validationConfig = array(
        'name_max_chars'     	=> 100,
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
		        <div class="wrap">   
		        	<h1><?php echo __("Gruppe-Formular") ?></h1>
		        	<div id="errormessage"></div>
		        
		        	<fieldset>
			      	  	<label><?php echo __("Name der Lokalgruppe") ?></label>
			        	<input type="text" id="group_name" name="group_name" placeholder="Name" maxlength="<?php echo self::$validationConfig['name_max_chars'] ?>">
			        
				        <label><?php echo __("Landkreis") ?></label>
				        <select id="group_district" name="group_district">
				        	<?php foreach ( $data['districts'] as &$district ): ?>
			                    <option value="<?php echo $district['value']; ?>"><?php echo $district['name']; ?></option>
			                <?php endforeach; ?>
				        </select>
				        
				        <label><?php echo __("Ort") ?></label>
						<input type="text" id="group_city" name="group_city"  placeholder="Ort">
						<input type="text" id="idea_image" name="idea_image"  placeholder="Postleitzahl">
						
						<label><?php echo __("Wirkungskreis") ?></label>
						<input type="text" id="group_scopes" name="group_scopes" placeholder="Schule / etc.">
	
						<label><?php echo __("Kurzvorstellung") ?></label>
				        <?php wp_editor("", 'group_description', array(
				        	'media_buttons' => false,
				        	'textarea_name' => 'group_description',
				        	'tabindex'		=> 0
							));
						?>
				        
				        <label><?php echo __("Homepage") ?></label>
				        <input type="url" id="group_homepage" name="group_homepage" placeholder="www.deineseite.de">
			        </fieldset>
			        
			        
			        <fieldset>
				        <h1><?php echo __("Ansprechpartner") ?></h1>
	
						<label><?php echo __("Name") ?></label>
						<input type="text" id="group_contact_name" name="group_contact_name"  placeholder="Ort">
						
						<label><?php echo __("Vorname") ?></label>
						<input type="text" id="group_contact_surname" name="group_contact_surname"  placeholder="Ort">
						
						<label><?php echo __("E-Mail") ?></label>
						<input type="email" id="group_contact_mail" name="group_contact_mail"  placeholder="Ort">
						
						<label><?php echo __("Telefon") ?></label>
						<input type="email" id="group_contact_mail" name="group_contact_mail"  placeholder="Ort">
						<input type="checkbox" id="group_contact_publish" name="group_contact_publish" value="public" checked="checked"><?php echo __("öffentlich") ?>
					</fieldset>

			        <div class="group_submit_container"><a href="<?php echo $data['submitLink'] ?>" <?php echo $data['onClick'] ?> id="group_submit">Abschicken</a></div>
				</div>	
        	</form>
       </div>
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
        wp_localize_script('klimo_frontend_forms', 'groupform_config', $formData);
    }


    function enqueue_scripts() {
    	// autosuggest
        wp_enqueue_script('autosuggest', plugins_url('script/autoSuggestv14/jquery.autoSuggest.packed.js', __FILE__), array('jquery'));
		// autosuggest
        wp_enqueue_script('adaptivetableinput', plugins_url('script/adaptiveTableInput.js', __FILE__), array('jquery'));
		
    	// frontend forms script
        wp_enqueue_script('klimo_frontend_forms', plugins_url('script/klimoPT_group_form.js', __FILE__), array('jquery', 'jquery-form', 'autosuggest', 'adaptivetableinput'));
    }
    
    
    function enqueue_styles() {
    	// autosuggest style
        wp_enqueue_style('autosuggest', plugins_url('script/autoSuggestv14/autoSuggest.css', __FILE__));
		// form styles
		wp_enqueue_style('klimo_frontend_forms', plugins_url('css/klimoPT_forms.css', __FILE__));
    }


    public static function initAjax() {
    	self::$ioConfig['ajaxurl'] = admin_url('admin-ajax.php');
		self::$ioConfig['submitAction'] = 'groupform_submit-ajax.php';
    	
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
		// collect values
		$errors = array();
        $idea_title = $postData['idea_title'];
		$idea_group_id = $postData['idea_group'];
		$idea_excerp = $postData['idea_excerp'];
		$idea_description = $postData['idea_description'];
		$idea_topic_id = $postData['idea_topic'];
		$idea_aim_ids = $postData['idea_aims'];
		$idea_links = $postData['idea_links'];
		// $idea_file1 = wp_strip_all_tags($postData['']);
		// $idea_image = wp_strip_all_tags($postData['']);
		
		
		
		$post_args = array(
            'ping_status'   => 'open',
            'post_status'   => 'pending',
            'post_type'     => 'klimo_idea',
            'post_title'    => $idea_title,
            'post_content'  => $idea_description,
            'post_excerpt'  => $idea_excerp,
            'tax_input'		=> array(
            	'klimo_idea_topics' => array(intval($idea_topic_id)),
            	'klimo_idea_aims' => $idea_aim_ids,
			),
        );
		
		
		// create post
		$postID = wp_insert_post($post_args);
        if(is_wp_error($postID)) {
            header("HTTP/1.0 500 Internal Server Error");
            die();
        }
		
		// attach local group meta
		$group_meta_slug = '_group';
		if($idea_group_id == -1) {
			delete_post_meta($postID, $group_meta_slug);
		} else {
			update_post_meta($postID, $group_meta_slug, $idea_group_id);
		}
		
		
		// attach link meta
		$idea_links_meta_slug = '_links';
		if(!empty($idea_links)){
			update_post_meta($postID, $idea_links_meta_slug, $idea_links);
		} 
		
		
		// attach features image
		if( !empty($_FILES['idea_image']['name']) ) {
            $attach_id = media_handle_upload( 'idea_image', $postID );
            if(!is_wp_error($attach_id)) {
                // update_post_meta( $postID, '_thumbnail_id', $attach_id );
				set_post_thumbnail($postID, $attach_id);
            } else {
                $errors[] = array(
                    'element'   => 'idea_image',
                    'message'   => $attach_id->get_error_message(),
				);
				wp_delete_post($postID, true);
				goto finish;
            }
        }
		
		
		
		
		// attach other files
		foreach ($postData['idea_files'] as $attachment) {
			$attach_id = media_handle_upload($attachment['name'], $postID, array('post_title' => $attachment['description']));
			if(is_wp_error($attach_id)) {
				$errors[] = array(
                    'element'   => 'idea_files',
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
		
		// check title
        $element = "idea_title";
        $value = trim(wp_strip_all_tags($args[$element]));
        // too short?
        if(strlen($value) < self::$validationConfig['title_min_chars']) {
            $response['error'][] = array(
                'element'   => $element,
                'message'   => "Der Titel muss mindestens " . self::$validationConfig['title_min_chars'] . " Zeichen lang sein.",
            );
        // too long?
        } else if(strlen($value) > self::$validationConfig['title_max_chars']) {
            $response['error'][] = array(
                'element'   => $element,
                'message'   => "Der Titel darf maximal " . self::$validationConfig['title_max_chars'] . " Zeichen lang sein.",
            );
        // check if post is already there
        } else {
            $matchingPosts = get_posts(array(
                'name' => $value,
                'post_type' => 'klimo_idea',
                'post_status' => 'publish',
                'posts_per_page' => 1,)
            );
            
            
            if($matchingPosts && count($matchingPosts) > 0) {
                $post = &$matchingPosts[0];
                $response['error'][] = array(
                    'element'   => $element,
                    'message'   => 'Diese Idee gibt es <a href="' . get_post_permalink($post->ID , false) . '" title="' . $post->post_title . '" target="_blank">hier</a> schon!',
                );
            }
        }
		$postData[$element] = $value;
		
		
		// check group
		$element = 'idea_group';
		$value = intval(wp_strip_all_tags($args['idea_group']));
		$postData[$element] = $value;
		

		// check excerp
		$element = 'idea_excerp';
		$value = trim(wp_strip_all_tags($args[$element]));
		// too long?
		if(strlen($value) > self::$validationConfig['excerp_max_chars']) {
            $response['error'][] = array(
                'element'   => $element,
                'message'   => "Kurzbeschreibung darf maximal " . self::$validationConfig['excerp_max_chars'] . " Zeichen lang sein.",
            );
        }
		$postData[$element] = $value;
		
		
		// check description
        $element = "idea_description";
        $value = trim(wp_strip_all_tags($args[$element]));
		$postData[$element] = $value;
		
		
		// check topic
		$element = 'idea_topic';
		$value = intval(wp_strip_all_tags($args['idea_topic']));
		$postData[$element] = $value;
		
		
		// check aim
		$element = 'idea_aims';
		$value = explode(",", wp_strip_all_tags($args['as_values_idea_aims']));
		foreach ($value as &$aim) {
			if(is_numeric($aim))
				$aim = intval($aim);
		}
		$last = end($value);
		if( empty($last) ) array_pop($value);
		$postData[$element] = $value;
		
		
		// check links
		$element = 'idea_links';
		$value = array();
		for ($i=0; ;$i++) { 
			$keyText = 'linktext_' . $i;
			$keyUrl = 'linkurl_' . $i;
			if(!array_key_exists ( $keyText , $args ) || !array_key_exists ( $keyUrl , $postData ))
				break;
			$valText  = trim(wp_strip_all_tags($args[$keyText]));
			$valUrl  = trim(wp_strip_all_tags($args[$keyUrl]));
			
			if(strlen($valUrl))
				$value[] = array('text' => $valText, 'url' => $valUrl);
		}
		$postData[$element] = $value;
		
		
		// check featured image
        $element = "idea_image";
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
		
		
		// check attachments
		$element = 'idea_files';
		$value = array();
		for ($i=0; ;$i++) { 
			$descrKey = 'filetext_' . $i;
			$fileKey = 'idea_file_' . $i;
			
			// check (assumption: element names are ordered, starting from 0)
			if(!array_key_exists ( $descrKey , $args ) || !array_key_exists ( $fileKey , $files ))
				break;
			if( (empty($files[$fileKey]['name'])) || (intval($files[$fileKey]['error'] != 0)) )
				continue;
			
			$valText  = trim(wp_strip_all_tags($args[$descrKey]));
			$fileValue = $files[$fileKey];
			$fileValue['description'] = $valText;
			$value[] = $fileValue;
		}
		$postData[$element] = $value;
		
		
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
                'message'  => '<div id="securityErrorMessage"><p>Sorry, deine Session ist abgelaufen ... </p><a href=".">Neue Idee Schreiben</a></div>',
            );
        }
        
        return $response;
    }
    
	
    private static function createSuccessMessage($postID) {
		$postPermaLink = get_post_permalink($postID, false);
        
        $html = '<div id="submitSuccessMessage">';
        $html .= '<p>Deine Idee wurde erfoglreich abgeschickt und wird von uns geprüft.</p>';
        $html .= '<div class="redirect thePost"><a href="' . $postPermaLink . '">Idee ansehen</a></div>';
        $html .= '<div class="redirect newIdea"><a href=".">Neue Idee Schreiben</a></div>';
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