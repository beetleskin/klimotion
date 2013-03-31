<?php
/**
 * @package Klimotion_Group_Map
 */

 
 

class NewIdeaForm {

    private $form_action;
    private $form_id;
    private $form_method;
	private $renderData;
    
    private static $ioConfig = array();
	private static $nonceName = "klimoIdeaFormNonce";
	private static $validationConfig = array(
        'title_max_chars'     	=> 100,
        'title_min_chars'     	=> 20,
        'excerp_max_chars'    	=> 200,
    );
    

    function __construct() {
        $this -> form_action = "";
        $this -> form_id = "ideaform";
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
		
		
		// groups:
		$groups = get_posts( array( 
			'post_type' 	=> array('klimo_localGroups'),
			'post_status'	=> 'publish',
			'numberposts' 	=> -1
		));
		$data['groups'] = array();
		foreach ($groups as $group) {
			$data['groups'][] = array("value" => $group->ID, "name" => $group->post_title);
		}

		// topics
		$topics = get_terms("klimo_idea_topics", array(
            'hide_empty'    => false,
            'hierarchical'  => false,
            'order_by'      =>'count'
        ));
		$data['topics'] = array();
		foreach ($topics as $topic) {
			$data['topics'][] = array("value" => $topic->term_id, "name" => $topic->name);
		}
		
		
		// aims		
		$aims = get_terms("klimo_idea_aims", array(
            'hide_empty'    => false,
            'hierarchical'  => false,
            'order_by'      =>'count'
        ));
		$data['aims'] = array();
		foreach ($aims as $aim) {
			$data['aims'][] = array("value" => $aim->term_id, "name" => $aim->name);
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
        
        
        <div id="ideaform_wrap">   	
		    <form action="<?php echo $this->form_action ?>" id="<?php echo $this->form_id ?>" class="<?php echo $data['nopriv'] ?>" method="<?php echo $this->form_method ?>">
		        <div class="wrap">   
		        	<div id="errormessage"></div>
		        
		      	  	<h1>Idee-Formular</h1>
		      	  	
		      	  	<h2>Titel</h2>
		        	<input type="text" id="idea_title" name="idea_title" placeholder="Gib deiner Idee einen Namen" maxlength="<?php echo self::$validationConfig['title_max_chars'] ?>">
		        
			        <h2>Gruppe</h2>
			        <select id="idea_group" name="idea_group">
			        	<option value="-1" selected="selected">Keine Gruppe</option>
			        	<?php foreach ( $data['groups'] as &$group ): ?>
		                    <option value="<?php echo $group['value']; ?>"><?php echo $group['name']; ?></option>
		                <?php endforeach; ?>
			        </select>

			        <h2>Kurze Beschreibung</h2>
			        <textarea id="idea_excerp" name="idea_excerp" placeholder="Textfeld begrenzt auf 200 Wörter" maxlength="<?php echo self::$validationConfig['excerp_max_chars'] ?>"></textarea>

					<h2>Titelbild</h2>
					<input type="file" id="idea_image" name="idea_image" accept="image/*">
		        
		            <h2>Detaillierte Beschreibung</h2>
			        <textarea id="idea_description" name="idea_description" placeholder="Textfeld unbegrenzt"></textarea>
		
		
					<h2>Dateien Hinzufügen</h2>
					<input type="file" id="idea_file1" name="idea_file1">
					
					<h2>Weiterführende Links</h2>
					<div id="idea_links">
						<table>
							<tbody>
								<tr class="links_meta_pair">
									<td><input type="url" maxlength="40" name="linktext_0" placeholder="Beschreibung"></td>
									<td><input type="url" name="linkurl_0" placeholder="Link"></td>
									<td><a class="removelink" href="#" onclick="return false;">entfernen</a></td>
								</tr>
							</tbody>
						</table>
						<a id="addlink" href="#" onclick="return false;">hinzufügen</a>
					</div>
					
					
					<h2>Thema</h2>
					<select id="idea_topic" name="idea_topic">
			        	<?php foreach ( $data['topics'] as &$topic ): ?>
		                    <option value="<?php echo $topic['value'] ?>"><?php echo $topic['name'] ?></option>
		                <?php endforeach; ?>
			        </select>
			        
			        
			        <h2>Ziele</h2>
			        <input type="text" id="idea_aim" name="idea_aim">
			        
			        
			        <div class="idea_submit_container"><a href="<?php echo $data['submitLink'] ?>" <?php echo $data['onClick'] ?> id="idea_submit">Abschicken</a></div>
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
			'as_groups'		=> $this->renderData['groups'],
			'as_aims'		=> $this->renderData['aims'],
		);
        
        // Print data to sourcecode
        wp_localize_script('klimo_frontend_forms', 'ideaform_config', $formData);
    }


    function enqueue_scripts() {
    	// frontend forms script
        wp_enqueue_script('klimo_frontend_forms', plugins_url('script/klimoPT_frontend_forms.js', __FILE__), array('jquery', 'jquery-form'));
		// autosuggest
        wp_enqueue_script('autosuggest', plugins_url('script/autoSuggestv14/jquery.autoSuggest.packed.js', __FILE__), array('jquery'));
    }
    
    
    function enqueue_styles() {
    	// autosuggest style
        wp_enqueue_style('autosuggest', plugins_url('script/autoSuggestv14/autoSuggest.css', __FILE__));
    }


    public static function initAjax() {
    	self::$ioConfig['ajaxurl'] = admin_url('admin-ajax.php');
		self::$ioConfig['submitAction'] = 'ideaform_submit-ajax.php';
    	
		// register form ajax
        add_action('wp_ajax_' . self::$ioConfig['submitAction'], 'NewIdeaForm::submitHandler');
    }


    public static function submitHandler() {
    	
		$postData = array();
		
		
		// security check and validations
		$securityVeto = self::securityCheck($_REQUEST);
		$validationVeto = self::validate($_REQUEST, $postData);
		if ( !empty($securityVeto))  {
			self::ajaxRespond($securityVeto);
			die();
		} else if( !empty($validationVeto)) {
			self::ajaxRespond($validationVeto);
			die();
		}
		
		
		
		// add post
		// collect values
		$error = null;
        $idea_title = $postData['idea_title'];
		$idea_group_id = $postData['idea_group'];
		$idea_excerp = $postData['idea_excerp'];
		$idea_description = $postData['idea_description'];
		$idea_topic_id = $postData['idea_topic'];
		$idea_aim_id = $postData['idea_aim'];
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
            	'klimo_idea_aims' => array(intval($idea_aim_id)),
			),
        );
		
		
		// create post
		$postID = wp_insert_post($post_args);
        if(is_wp_error($postID)) {
            header("HTTP/1.0 500 Internal Server Error");
            die();
        }
		
		// save local group meta
		$group_meta_slug = '_group';
		if($idea_group_id == -1) {
			delete_post_meta($postID, $group_meta_slug);
		} else {
			update_post_meta($postID, $group_meta_slug, $idea_group_id);
		}
		
		
		// save link meta
		$idea_links_meta_slug = '_links';
		if(!empty($idea_links)){
			update_post_meta($postID, $idea_links_meta_slug, $idea_links);
		} 
		
		
		
		
		
		// return
		$response = array();
        if($error == null) {
            $response['success'] = self::createSuccessMessage($postID);
        } else {
            $response['error'] = $error;
        }
            
			
			

        self::ajaxRespond($response);
        die();
    }


    public static function myHandleUploadError($file, $message) {
    }


    private static function validate(&$args, &$postData) {
    	
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
		$element = 'idea_aim';
		$value = intval(wp_strip_all_tags($args['idea_aim']));
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
                'args' => $args
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