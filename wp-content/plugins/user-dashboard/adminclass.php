<?php
class MJadmin{
	function SettingPage(){ ?>
		  <div class="wrap">  
        <?php screen_icon('themes'); ?> <h2>User-Dashboard Setting page </h2>  
        <form method="POST" action="">  
            <table class="form-table">  
                <tr valign="top">  
                    <th scope="row">  
                        <label for="num_elements">  
                            <?php _e('Login With');?>
                        </label>  
                    </th>  
                    <td>  
                       <?php _e('Email ');?><input type="radio" name="loginwith" value="0" size="25" />
                       <?php _e('Username ');?><input type="radio" name="loginwith" value="1" size="25" />  
                    </td>  
                </tr>  
            </table>  
			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
        </form>  
    </div> 
	<?php }	
}
?>