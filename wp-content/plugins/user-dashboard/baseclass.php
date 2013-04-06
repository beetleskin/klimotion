<?php
class MJdashboard{
	function LogScreen() {
	$new_query = add_query_arg(array('ac' => 'login'), get_permalink());
	?>
		<div class="tabdivouter" style="width: 600px; margin: 0px auto;">
			
			<fieldset style=" border: 1px solid;padding: 15px;">
			<legend>User Login</legend>
			<div class="tabdivinner-left" style="float:left; margin: 0px auto; width: 50%;">
				<div id="login">	
					<p class="message">	<br></p>
				<form method="post" action="<?php echo get_permalink($new_query);?>" id="loginform" name="loginform">
					<p>
						<label for="user_login"><?php _e('Username'); ?><br>
						<input type="text" size="20" value="" class="input" id="user_login" name="log"></label>
					</p>
					<p>
						<label for="user_pass"><?php _e('Password'); ?><br>
						<input type="password" size="20" value="" class="input" id="user_pass" name="pwd"></label>
					</p>
					<p class="forgetmenot"><label for="rememberme"><input type="checkbox" value="forever" id="rememberme" name="rememberme"><?php _e('Remember Me'); ?></label></p>
					<p class="submit" style="margin-top: 15px;">
						<input type="submit" value="Log In" class="button button-primary button-large" id="wp-submit" name="wp-submit">
						<input type="button" value="Back" class="button button-primary button-large" name="back" onclick="javascript:window.history.back()">
					
						<input type="hidden" value="<?php echo get_permalink(); ?>" name="redirect_to">
						<input type="hidden" value="1" name="testcookie">
					</p>	
				</form>
					</div>
					</div>
					<div class="tabdivinner-right" style="float:left; margin: 0px auto; width: 50%;"><img height="187px" src="<?php echo IMGPATH; ?>login_icon.jpg" alt="login" title="login"></div>
				
				</fieldset>
			</div>

		
		<?php 
	}
	function Lostpassword(){ 
		$new_query = add_query_arg(array('ac' => 'lostpass'), get_permalink());
	?>
		<div class="tabdivouter" style="width: 600px; margin: 0px auto;">
			
			<fieldset style=" border: 1px solid;padding: 15px;">
			<legend>User Forgot Password</legend>
			<div class="tabdivinner-left" style="float:left; margin: 0px auto; width: 50%;">
				<div id="registration">
			
					<p class="message">	<br>
				</p>

				<form method="post" action="<?php echo get_permalink($new_query); ?>" id="lostpasswordform" name="lostpasswordform">
					<p>
						<label for="user_login"><?php _e('Username or E-mail:'); ?><br>
						<input type="text" size="20" value="" class="input" id="user_login" name="user_login"></label>
					</p>
					<input type="hidden" value="" name="redirect_to">
					<p class="submit" style="margin-top: 15px;">
					
					<input type="submit" value="Get New Password" class="button button-primary button-large" id="wp-submit" name="wp-submit">
					<input type="button" value="Back" class="button button-primary button-large" name="back" onclick="javascript:window.history.back()">
					</p>
					</form>
	
					</div>
					</div>
					<div class="tabdivinner-right" style="float:left; margin: 0px auto; width: 50%;"><img height="187px" src="<?php echo IMGPATH; ?>forgot_left_icon.png" alt="Forgot Password" title="Forgot Password"></div>
				
				</fieldset>
			</div>
	<?php }
	
	function LoggedInUser(){
		
		if ( is_user_logged_in() ) {
			 $new_query = add_query_arg(array('ac' => 'dashboard'), get_permalink());
			wp_safe_redirect( $new_query);
			exit;
		}
	}
	function LoggedoutUser(){
		if ( !is_user_logged_in() ) {
			$new_query = add_query_arg(array('ac' => 'login'), get_permalink());
			wp_safe_redirect( $new_query);
			exit;
		}
	}
	
	
	function LoggedProcess(){
		if(isset($_POST['testcookie'])){
		global $wpdb;
		$username = $wpdb->escape($_REQUEST['log']);  
		$password = $wpdb->escape($_REQUEST['pwd']);  
		$rememberme = $wpdb->escape($_REQUEST['rememberme']);     
		if($remember) $remember = "true";  
		else $remember = "false";
		
			$user = wp_authenticate( $username, $password );
			if( is_wp_error( $user ) )
			{
				echo $error = $user->get_error_message();
				
			}else{
			
			$username		=	(isset($_POST['log'])) ? $_POST['log'] : '';
			$password		=	(isset($_POST['pwd'])) ? $_POST['pwd'] : '';
			$rememberme	=	(isset($_POST['rememberme'])) ? '1' : '0';
			
				$credentials = array();
				$credentials['user_login'] = $username;
				$credentials['user_password'] = $password;
				$credentials['remember'] = $rememberme;
				$user = wp_signon( $credentials, false );

				if ( is_wp_error( $user ) ) {
					echo $error = $user->get_error_message();
				} else {
					wp_set_current_user( $user->ID, $username );
					do_action('set_current_user');
					$new_query = add_query_arg(array('ac' => 'dashboard'), get_permalink());
					wp_safe_redirect( $new_query);
					
					exit; 
			}
		}
	}
	}
	
	function Dashboard(){ 
		
		MJdashboard::controls(); 
	
	}
	
	
	public static function controls(){ ?>
		<div class="tabdivouter" style="width: 600px; margin: 0px auto;">
			<div class="tabdivinner" style="text-align: center; margin: 0px auto; width: 100%;">
			<fieldset style=" border: 1px solid;padding: 15px;">
			<legend>User Dashboard</legend>
				<table>
					<tr>
						<td style=" padding: 34px;">
							<div class="tabouter">
								<div class="tabinner-image"><a href="<?php echo wp_logout_url( LOGIN ); ?>"><img src="<?php echo IMGPATH; ?>logout.png" alt="Logout" title="Logout"></a></div>
								<div class="tabinner-label" style="text-align: center; margin-top: 21px;"><a title="Logout" href="<?php echo wp_logout_url( LOGIN ); ?>">Logout</a></div>
							</div>
						</td>
						<td style=" padding: 34px;">
							<div class="tabouter">
								<div class="tabinner-image"><a href="<?php echo PROFILE; ?>"><img src="<?php echo IMGPATH; ?>profile.png" alt="Profile" title="Profile"></a></div>
								<div class="tabinner-label" style="text-align: center; margin-top: 21px;"><a title="Profile" href="<?php echo PROFILE; ?>">Profile</a></div>
							</div>
						</td>
						<td style=" padding: 34px;">
							<div class="tabouter">
								<div class="tabinner-image"><a href="<?php echo PROFILEIMAGE; ?>"><?php MJdashboard::currentuserimage('123px'); ?></a></div>
								<div class="tabinner-label" style="text-align: center; margin-top: 21px;"><a title="profile-pic" href="<?php echo PROFILEIMAGE; ?>">profile-pic</a></div>
							</div>
						</td>
						
					</tr>
				</table>
				</fieldset>
			</div>
		</div>
	<?php }
	
	function Imageupload(){ ?>
	<div class="tabdivouter" style="width: 600px; margin: 0px auto;">
			<div class="tabdivinner" style="text-align: center; margin: 0px auto; width: 100%;">
			<fieldset style=" border: 1px solid;padding: 15px;">
			<legend>Upload Profile Image</legend>
				<?php MJdashboard::currentuserimage(); ?>
				<form name="profile" id="your-profile" action="<?php echo PROFILEIMAGE; ?>" method="post" enctype="multipart/form-data">
				<table>
					<tr>
						<td>
							Upload Image : 
						</td>
						<td>
							<input type="file" name="file" id="file">
						</td>
						</tr><tr>
						<td align="right">
							<p class="submit" style="margin-top: 15px;">
							<input type="submit" value="upload Image" class="button button-primary button-large" id="wp-submit" name="wp-submit">
							<input type="button" value="Back" class="button button-primary button-large" name="back" onclick="javascript:window.history.back()">
							</p>
						</td>
						
					</tr>
				</table>
				</form>
				</fieldset>
			</div>
		</div>
		
	<?php }
	
	public static function currentuserimage($height='100px'){
		$userID	=	get_current_user_id( );
		$userdata = get_userdata($userID);
		$image = basename($userdata->userphoto_thumb_file);
		echo "<div class='imagediv'>";
		if($image){
			echo '<image src="'.PROFILEIMAGEDIR.'/userphoto/'.$image.' " height="'.$height.'" title="Profile Image">';
		}else{
			echo '<image src="'.IMGPATH.'/no-avatar.png" height="'.$height.'" title="No Profile Image">';
		}
		echo "</div>";
	}
	
	public static function imageprocess(){
		$userID	=	get_current_user_id( );
		$userphoto_validtypes = array(
			"image/jpeg" => true,
			"image/pjpeg" => true,
			"image/gif" => true,
			"image/png" => true,
			"image/x-png" => true
		);
		$userphoto_validextensions = array('jpeg', 'jpg', 'gif', 'png');

		if(isset($_FILES['file']) && @$_FILES['file']['name']){
			
			#Upload error
			if( !$_FILES['file']['size'] ){
				echo  sprintf(__("The file &ldquo;%s&rdquo; was not uploaded. Did you provide the correct filename?", 'user-photo'), $_FILES['file']['name']);
			}
			else if( !preg_match("/\.(" . join('|', $userphoto_validextensions) . ")$/i", $_FILES['file']['name']) ){
				echo sprintf(__("The file extension &ldquo;%s&rdquo; is not allowed. Must be one of: %s.", 'user-photo'), preg_replace('/.*\./', '', $_FILES['file']['name']), join(', ', $userphoto_validextensions));
			}
			else if( @!$userphoto_validtypes[$_FILES['file']['type']] ){
				echo sprintf(__("The uploaded file type &ldquo;%s&rdquo; is not allowed.", 'user-photo'), $_FILES['file']['type']);
			}else{
				$tmppath = $_FILES['file']['tmp_name'];
				
				$imageinfo = null;
				$thumbinfo = null;
				$upload_dir = wp_upload_dir();
				$dir = trailingslashit($upload_dir['basedir']) . 'userphoto';
				
				#$umask = umask(0);
				if(!file_exists($dir) && @!mkdir($dir, 0777))
					echo sprintf(__("The userphoto upload content directory does not exist and could not be created. Please ensure that you have write permissions for the '%s' directory. Did you put slash at the beginning of the upload path in Misc. settings? It should be a path relative to the WordPress root directory. <code>wp_upload_dir()</code> returned:<br /> <code style='white-space:pre'>%s</code>", 'user-photo'), $dir, print_r($upload_dir, true));
				#umask($umask);
				
				if(!$error){
					$userdata = get_userdata($userID);
					$oldimagefile = basename($userdata->userphoto_image_file);
					$imagefile = "$userID." . preg_replace('{^.+?\.(?=\w+$)}', '', strtolower($_FILES['file']['name']));
					$imagepath = $dir . '/' . $imagefile;
					$thumbfile = preg_replace("/(?=\.\w+$)/", '', $imagefile);
					$thumbpath = $dir . '/' . $thumbfile;
					
					if(!move_uploaded_file($tmppath, $imagepath)){
						$error = sprintf(__("Unable to place the user photo at: %s", 'user-photo'), $imagepath);
					}
					
						
						update_usermeta($userID, "userphoto_thumb_file", $thumbfile);
						echo "Image uploaded successfully";
						
						//Delete old thumbnail if it has a different filename (extension)
						 if($oldimagefile != $imagefile)
							@unlink($dir . '/' . $oldimagefile);
						if($oldthumbfile != $thumbfile)
							@unlink($dir . '/' . $oldthumbfile); 
					}
				}
			}
		}
	
	public static function Logincontrols(){   ?>
		<div class="tabdivouter" style="width: 600px; margin: 0px auto;">
			<div class="tabdivinner" style="text-align: center; margin: 0px auto; width: 100%;">
			<fieldset style=" border: 1px solid;padding: 15px;">
			<legend>User Dashboard</legend>
				<table>
					<tr>
						<td style=" padding: 34px;">
							<div class="tabouter">
								<div class="tabinner-image"><a href="<?php echo LOGIN; ?>"><img src="<?php echo IMGPATH; ?>login.png" alt="Login" title="Login"></a></div>
								<div class="tabinner-label" style="text-align: center; margin-top: 21px;"><a title="Login" href="<?php echo LOGIN; ?>">Login</a></div>
							</div>
						</td>
						<td style=" padding: 34px;">
							<div class="tabouter">
								<div class="tabinner-image"><a href="<?php echo REGISTRATION; ?>"><img src="<?php echo IMGPATH; ?>registration.png" alt="Registration" title="Registration"></a></div>
								<div class="tabinner-label" style="text-align: center; margin-top: 21px;"><a title="Registration" href="<?php echo REGISTRATION; ?>">Registration</a></div>
							</div>
						</td>
						<td style=" padding: 34px;">
							<div class="tabouter">
								<div class="tabinner-image"><a href="<?php echo LOSTPASS; ?>"><img src="<?php echo IMGPATH; ?>forgot-password.png" alt="Forgot-Password" title="forgot-password"></a></div>
								<div class="tabinner-label" style="text-align: center; margin-top: 21px;"><a title="forgot-password" href="<?php echo LOSTPASS; ?>">Forgot-Password</a></div>
							</div>
						</td>
					</tr>
				</table>
				</fieldset>
			</div>
		</div>
	<?php }
	
	function PassProcess(){
		if(isset($_POST['user_login'])){
			
				$email	=	(isset($_REQUEST['user_login'])) ? $_REQUEST['user_login'] : ''; 
				if (empty($email) || !is_email($email)) {
					echo 'Not A Valid Email address';
				}else if ( !email_exists($email) ){
					echo 'Email address not exist';
				}else{
					MJdashboard::SetPassword($email);
				}
				
		}
	}
	
	function SetPassword($email){
		$user = get_user_by('email', $email);
		$newpass	=	wp_generate_password();
		$name	=	$user->first_name .' '. $user->last_name;
		wp_set_password( $newpass, $user->ID );
		MJdashboard::Passwordmail($email,$name,$newpass);
	}
	     
	function Passwordmail($email,$name,$newpass){
		$blog_title = get_bloginfo('name'); 
		$admin_email = get_bloginfo('admin_email'); 
		add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
		$headers = 'From: My Name <'.$admin_email.'>' . "\r\n";
		$message	=
		"hello $name,
		<p>We have reset your password here is your new password please check it</p>
		<p>======================================================================</p>
		<p>New Password : $newpass</p>
		<p>======================================================================</p>
		<p>For more details please contact to administrator</p>
		<p>Thanks & Regards<br/>$blog_title</p>
		<br/>";
		$sent	=	wp_mail($email, 'New Password', $message, $headers);
		if($sent){
			echo "New Password Sent on you `$email` mail address.";
		}else{
			echo "Error while sending mail. Please try it later.";
		}
	}
	
	function Registration(){ 
	$new_query = add_query_arg(array('ac' => 'registration'), get_permalink());

	?>
	<div class="tabdivouter" style="width: 600px; margin: 0px auto;">
			
			<fieldset style=" border: 1px solid;padding: 15px;">
			<legend>User Registration</legend>
			<div class="tabdivinner-left" style="float:left; margin: 0px auto; width: 50%;">
				<div id="registration">
			
					<p class="message">	<br>
				</p>

				<form method="post" action="<?php echo get_permalink($new_query);?>" id="loginform" name="loginform">
					<p>
						<label for="user_login"><?php _e('Username:'); ?><br>
						<input type="text" size="20" value="<?php echo (isset($_REQUEST['username']))? $_REQUEST['username'] : ''; ?>" class="input" id="user_login" name="username"></label>
					</p>
					<p>
						<label for="user_email"><?php _e('Email:'); ?><br>
						<input type="text" size="20" value="<?php echo (isset($_REQUEST['email']))? $_REQUEST['email'] : ''; ?>" class="input" id="user_email" name="email"></label>
					</p>
					<p class="submit" style="margin-top: 15px;">
						<input type="submit" value="SignUp" class="button button-primary button-large" id="wp-submit" name="wp-submit">	
						<input type="button" value="Back" class="button button-primary button-large" name="back" onclick="javascript:window.history.back()">			
					</p>	
				</form>
					</div>
					</div>
					<div class="tabdivinner-right" style="float:left; margin: 0px auto; width: 50%;"><img height="187px" src="<?php echo IMGPATH; ?>regform.jpg" alt="Registration" title="Registration"></div>
				
				</fieldset>
			</div>
<?php
}

	function RegProcess(){
	
		if($_POST['wp-submit']){
			global $wpdb;
			$email = $wpdb->escape($_REQUEST['email']);
			$username = $wpdb->escape($_REQUEST['username']);
			if(empty($username)) {
				echo "User name should not be empty.";
			}else if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $email)) {
				echo "Please enter a valid email.";
			}else{
					$random_password = wp_generate_password( 12, false );
					$status = wp_create_user( $username, $random_password, $email );
					if ( is_wp_error($status) ){
						echo $error = $status->get_error_message();
					}else {
						$from = get_option('admin_email');
						$headers = 'From: '.$from . "\r\n";
						$subject = "Registration successful";
						echo $msg = "Registration successful.\nYour login details\nUsername: $username\nPassword: $random_password";
						wp_mail( $email, $subject, $msg, $headers );
						echo "Please check your email for login details.";
					}
				}
		}
	}
	
	function profile(){ 
	global $current_user, $wp_roles;
$new_query = add_query_arg(array('ac' => 'profile'), get_permalink());

	?>
		<div class="entry-content entry">
            <?php if ( !is_user_logged_in() ) : ?>
                    <p class="warning">
                        <?php _e('You must be logged in to edit your profile.', 'profile'); ?>
                    </p><!-- .warning -->
            <?php else : ?>
                <?php if ( count($error) > 0 ) echo '<p class="error">' . implode("<br />", $error) . '</p>'; ?>
                <form method="post" id="adduser" action="<?php echo get_permalink($new_query);?>">
                    <p class="form-username">
                        <label for="first-name"><?php _e('First Name', 'profile'); ?></label>
                        <input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta( 'user_firstname', $current_user->id ); ?>" />
                    </p><!-- .form-username -->
                    <p class="form-username">
                        <label for="last-name"><?php _e('Last Name', 'profile'); ?></label>
                        <input class="text-input" name="last-name" type="text" id="last-name" value="<?php the_author_meta( 'user_lastname', $current_user->id ); ?>" />
                    </p><!-- .form-username -->
					<p class="form-nickname">
                        <label for="nick-name"><?php _e('Nick Name', 'profile'); ?></label>
                        <input class="text-input" name="nickname" type="text" id="nickname" value="<?php echo $current_user->user_nicename ?>" />
                    </p><!-- .form-username -->
                    <p class="form-email">
                        <label for="email"><?php _e('E-mail *', 'profile'); ?></label>
                        <input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->id ); ?>" />
                    </p><!-- .form-email -->
                    <p class="form-url">
                        <label for="url"><?php _e('Website', 'profile'); ?></label>
                        <input class="text-input" name="url" type="text" id="url" value="<?php the_author_meta( 'user_url', $current_user->id ); ?>" />
                    </p><!-- .form-url -->
					<p class="form-url">
                        <label for="url"><?php _e('AIM', 'profile'); ?></label>
                        <input class="text-input" name="aim" type="text" id="aim" value="<?php the_author_meta( 'aim', $current_user->id ); ?>" />
                    </p>
					<p class="form-url">
                        <label for="url"><?php _e('Yahoo IM', 'profile'); ?></label>
                        <input class="text-input" name="yim" type="text" id="yim" value="<?php the_author_meta( 'yim', $current_user->id ); ?>" />
                    </p>
					<p class="form-url">
                        <label for="url"><?php _e('Jabber / Google Talk', 'profile'); ?></label>
                        <input class="text-input" name="jabber" type="text" id="jabber" value="<?php the_author_meta( 'jabber', $current_user->id ); ?>" />
                    </p><!-- .form-url -->
                    <p class="form-password">
                        <label for="pass1"><?php _e('Password *', 'profile'); ?> </label>
                        <input class="text-input" name="pass1" type="password" id="pass1" />
                    </p><!-- .form-password -->
                    <p class="form-password">
                        <label for="pass2"><?php _e('Repeat Password *', 'profile'); ?></label>
                        <input class="text-input" name="pass2" type="password" id="pass2" />
                    </p><!-- .form-password -->
                    <p class="form-textarea">
                        <label for="description"><?php _e('Biographical Information', 'profile') ?></label>
                        <textarea name="description" id="description" rows="3" cols="50"><?php the_author_meta( 'description', $current_user->id ); ?></textarea>
                    </p><!-- .form-textarea -->

                    <?php 
                        //action hook for plugin and extra fields
                        do_action('edit_user_profile',$current_user); 
                    ?>
                    <p class="form-submit">
                        <?php echo $referer; ?>
                        <input name="updateuser" type="submit" id="updateuser" class="submit button" value="<?php _e('Update', 'profile'); ?>" />
						<input type="button" value="Back" class="button button-primary button-large" name="back" onclick="javascript:window.history.back()">
                        <?php wp_nonce_field( 'update-user' ) ?>
                        <input name="action" type="hidden" id="action" value="update-user" />
                    </p><!-- .form-submit -->
                </form><!-- #adduser -->
            <?php endif; ?>
        </div><!-- .entry-content --> 
	<?php
	}
	
	function ProProcess(){
		global $current_user, $wp_roles;
		get_currentuserinfo();

		/* Load the registration file. */
		require_once( ABSPATH . WPINC . '/registration.php' );
		$error = array();    
		/* If profile was saved, update profile. */
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {

			/* Update user password. */
			if ( !empty($_POST['pass1'] ) && !empty( $_POST['pass2'] ) ) {
				if ( $_POST['pass1'] == $_POST['pass2'] )
					wp_update_user( array( 'ID' => $current_user->id, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );
				else
					$error[] = __('The passwords you entered do not match.  Your password was not updated.', 'profile');
			}

			/* Update user information. */
			
			if ( !empty( $_POST['email'] ) ){
				if (!is_email(esc_attr( $_POST['email'] )))
					$error[] = __('The Email you entered is no valid.  please try again.', 'profile');
				elseif(email_exists(esc_attr( $_POST['email'] )) != $current_user->id )
					$error[] = __('This email is allready used by another user.  try a different one.', 'profile');
				else{
					wp_update_user( array ('ID' => $current_user->id, 'user_email' => esc_attr( $_POST['email'] )));
				}
			}
			
			if ( !empty( $_POST['nickname'] ) ){
					wp_update_user( array ('ID' => $current_user->id, 'user_nicename' => esc_attr( $_POST['nickname'] )));
			}
			
			if ( !empty( $_POST['url'] ) )
				update_usermeta( $current_user->id, 'user_url', esc_url( $_POST['url'] ) );
			if ( !empty( $_POST['first-name'] ) )
				update_usermeta( $current_user->id, 'first_name', esc_attr( $_POST['first-name'] ) );
			if ( !empty( $_POST['last-name'] ) )
				update_usermeta($current_user->id, 'last_name', esc_attr( $_POST['last-name'] ) );
			if ( !empty( $_POST['description'] ) )
				update_usermeta($current_user->id, 'description', esc_attr( $_POST['description'] ) );
			if ( !empty( $_POST['aim'] ) )
				update_usermeta( $current_user->id, 'aim', esc_attr( $_POST['aim'] ) );
			if ( !empty( $_POST['yim'] ) )
				update_usermeta( $current_user->id, 'yim', esc_attr( $_POST['yim'] ) );
			if ( !empty( $_POST['jabber'] ) )
				update_usermeta( $current_user->id, 'jabber', esc_attr( $_POST['jabber'] ) );

			/* Redirect so the page will show updated info. */
			 if ( count($error) == 0 ) {
				//action hook for plugins and extra fields saving
				do_action('edit_user_profile_update', $current_user->id);
				//wp_redirect( get_permalink() );
				//exit;
				_e('Profile Update Successfully"');
			} 
		}
	}
}
?>