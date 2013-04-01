(function($){
	var $,
		deleteText;
		
	jQuery(document).ready(function(){
		$ = jQuery;
		deleteText = $("#delete-all-media").text();
		
		$("#bulk-delete-ui-wrapper").insertAfter($("#save-all"));
		$("#bulk-delete-ui-wrapper").css('display', 'block');
		
		addCheckBoxes();
		
		$("#delete-all-media").click(function(e){
			e.preventDefault();
			if(!$(this).hasClass('delete-on-click')){
				if($(".delete_me input:checked").length == 0){
					alert($("#abd-none-selected").text());
				} else {
					confirmDeletion();
				}
			} else {
				executeMediaDelete();
			}
		});
		
		$("#cancel-delete-all-media").click(function(e){
			e.preventDefault();
			setIdle();
		});
	})
	function setIdle(){
		$("#cancel-delete-all-media-wrapper").hide();
		$("#delete-all-media").removeClass('delete-on-click');
		$("#delete-all-media").text(deleteText);
	}
	function confirmDeletion(){
		$("#delete-all-media").addClass('delete-on-click');
		$("#cancel-delete-all-media-wrapper").show('fast');
		$("#delete-all-media").text(deleteText + '?');
	}
	function executeMediaDelete(){
		var ids = "";
		$(".delete_me input:checked").each(function(index){
			if(index > 0) ids += ",";
			ids += $(this).attr('value');
			$(this).parents(".media-item").css({
				"background": "red",
				"overflow": "hidden"
			}).fadeOut("slow");
		})
		$("#cancel-delete-all-media-wrapper, #delete-all-media").hide();
		$("#bulk-delete-ajax-loader").show();
		var url = $("#delete-all-media").attr('href')+"&ids="+ids;
		$.get(url, function(data) {
			$("#bulk-delete-ajax-loader").hide();
			var loc = window.location.href;
			var rootUrl = loc.split('?')[0];
			redirectUrl = loc;
			var afterPostId = loc.split("post_id=")[1];
			var postId = afterPostId.split("&")[0];
			// redirect to upload form, if all attachments were deleted
			if($(".delete_me input:checked").length == $(".delete_me input").length){
				redirectUrl = rootUrl + "?type=file&tab=type&is_inline=1&post_id=" + postId;
			}
			window.location.href = redirectUrl;
			$('#inline_attachments iframe', top.document).attr("src", redirectUrl);
		});
	}
	function addCheckBoxes(){
		// add a checkbox to every media item
		$(".media-item").each(function(index) {
			var id = $(this).attr('id').split("media-item-")[1];
			$(this).prepend("<span class='delete_me'><input type='checkbox' value='"+id+"'></input></span>");
		})
		$(".delete_me").css({
			"display": "block",
			"float": "right",
			"padding": "12px 12px 0px 0px"
		})
		$(".delete_me input").change(function(){
			$(".delete_me input").removeClass("lastSelected");
			$(this).addClass("lastSelected");
		})
		$(".delete_me input").click(function(e){
			if (e.altKey) {
				//do something, alt was down when clicked
				selectSingle($(this));
			} else if(e.shiftKey) {
				selectRange($(this));
			}
		})
		// add the toggle all checkbox
		$("#gallery-form .widefat thead tr:first").append("<th class='bulk-delete-head'><input type='checkbox' id='bulk-delete-toggle' value=''></input></th>");
		$("#bulk-delete-toggle").css({
			"position": "relative",
			"display": "block",
			"float": "right",
			"margin": "3px 8px 0px 0px"
		})
		$("#bulk-delete-toggle").click(function(){
			if($(this).attr("checked")){
				$(".delete_me input").attr("checked", true);
			} else {
				$(".delete_me input").attr("checked", false);
			}
		})
	}
	function selectSingle(_input){
		$("#bulk-delete-toggle").attr("checked", false);
		//$(".singleSelected").removeClass("singleSelected");
		//_input.addClass("singleSelected");
		$(".delete_me input").attr("checked", false);
		_input.attr("checked", true);
	}
	function selectRange(_input){
		if($(".lastSelected").length > 0){
			var lastOffset = $(".lastSelected").offset().top;
			var thisOffset = _input.offset().top;
			var lower = lastOffset;
			var higher = thisOffset;
			if(lastOffset > thisOffset){
				lower = thisOffset;
				higher = lastOffset;
			}
			$(".delete_me input").each(function(){
				if($(this).offset().top >= lower && $(this).offset().top <= higher){
					$(this).attr("checked", true);
				} else {
					$(this).attr("checked", false);
				}
			})
		}
	}
})();