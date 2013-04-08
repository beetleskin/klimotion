/**
 * @author Stefan Kaiser
 */

jQuery(function($) { 
	
	adminidea : {

		$(document).ready(function() {
			
			
			if( $('form input#post_type').val() == "klimo_idea" ) {
				
				$("#idea-post-meta-links").adaptiveTableInput({ trSelector: '.links_meta_pair', maxRows: 10});
				$('#idea-post-meta-group select#meta-group').multiselect({
					selectedList: 5,
					header: false
				});
				
				
			} else if( $('#idea-post-meta-group').val() == "klimo_localgroup" ) {
				
			}
		});
	}
});
