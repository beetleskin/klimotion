<?php
/**
 * @package Klimotion_Group_Map
 */

 
 
add_action('init', 'NewIdeaForm::init');
 

class NewIdeaForm {

    private $form_action;
    private $form_id;
    private $form_method;
    
    private static $ioConfig;
	private static $nonceName = "klimo-newidea";
    

    function __construct() {
        $this -> form_action = "";
        $this -> form_id = "ideaform";
        $this -> form_method = "POST";
    }

    function preRender() {
        $data = array();
        
        $nopriv = 'nopriv';
        $submitLink = wp_login_url(get_permalink());
        $onClick = '';
		$isLoggedIn = get_current_user_id() != 0;
        
        if($isLoggedIn) {
            $nopriv = '';
            $submitLink = '#';
            $onClick = 'onclick="return false;"';
        }
        
        $data['isLoggedIn'] = $isLoggedIn;
        $data['nopriv'] = $nopriv;
        $data['submitLink'] = $submitLink;
        $data['onClick'] = $onClick;
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
		        	<input type="text" id="idea_title" name="idea_title" placeholder="Gib deiner Idee einen Namen">
		        
			        <h2>Gruppe</h2>
			        <select id="idea_group" name="idea_group">
			        	<option value="-1">Keine Gruppe</option>
			        </select>
			        
			        <h2>Kurze Beschreibung</h2>
			        <textarea id="idea_excerp" name="idea_excerp" placeholder="Textfeld begrenzt auf 200 Wörter"></textarea>

					<h2>Titelbild</h2>
					<input type="file" id="idea_image" name="idea_image" accept="image/*">
		        
		            <h2>Detaillierte Beschreibung</h2>
			        <textarea id="idea_description" name="idea_description" placeholder="Textfeld unbegrenzt"></textarea>
		
		
					<h2>Dateien Hinzufügen</h2>
					<input type="file" id="idea_file1" name="idea_file1">
					
					<h2>Weiterführende Links</h2>
					<input type="text" maxlength="40" name="idea_linktext_1" placeholder="Beschreibung">
					<input type="text" name="idea_linkurl_1" placeholder="Link">
					
					<h2>Thema</h2>
					<select id="idea_topic" name="idea_topic">
			        	<?php foreach ( $data['topics'] as &$topic ): ?>
		                    <option value="<?php echo $topic->term_id ?>"><?php echo $topic->name ?></option>
		                <?php endforeach; ?>
			        </select>
			        
			        
			        <h2>Ziele</h2>
			        <input type="text" id="idea_goals" name="idea_goals" placeholder="Was willst du erreichen?">
				</div>	
        	</form>
       </div>
	<?php
	}


    public function printAjaxConfig() {
    	// add security check
        self::$ioConfig['shoohooooh'] = wp_create_nonce  (self::$nonceName);
        
        // Print data to sourcecode
        wp_localize_script('klimo_frontend_forms', 'ideaform_config', self::$ioConfig);
    }


    function enqueue_scripts() {
    	// autosuggest
        wp_enqueue_script('klimo_frontend_forms', plugins_url('script/klimoPT_frontend_forms.js', __FILE__), array('jquery'));// autosuggest
    }
    
    
    function enqueue_styles() {
    }


    public static function init() {
    	self::$ioConfig = array(
            'ajaxurl'               => admin_url('admin-ajax.php'),
            'submitAction'          => 'ideaform_submit',
        );
    	
		// register form ajax
        add_action('wp_ajax_' . self::$ioConfig['submitAction'], 'IdeaForm::submit');
    }


    public static function submit() {
    	header("Content-Type: text/plain");
        echo json_encode(array("peter" => "enis"));
        die();
    }


    public static function myHandleUploadError($file, $message) {
    }


    private static function validate() {
        return true;
    }


    private static function securityCheck() {
        return true;
    }
    
	
    private static function sumitSuccessHTML($postID) {
    }
}
?>