(function($){
	var $,
		galleryTabTimeout,
		ajaxBusy = false,
		currentMenuOrder,
		galleryLink,
		windowLocation;
	
	jQuery(document).ready(function(){
		$ = jQuery;
		galleryLink = phpGalleryLink;
		currentMenuOrder = getMenuOrder();
		windowLocation = window.location.href;
		// If there is no Gallery tab
		if($("#tab-gallery").length == 0){
			$("#sidemenu #tab-type").after('<li id="tab-gallery"></li>');
			$("#tab-gallery").append(galleryLink);
			$("#tab-gallery").css("display", "none");
			checkIfMoreThanZero();
		} else if($("#tab-gallery a").hasClass("current")) {
			
			// If there are more than one Items
			if($(".media-item").length > 1){
				putMenuOrderAfterShowHide();
				addInvertOrderButton();
				reverseInitialOrder();
			}
			saveAjax();
			addAjaxOrderAutoSave();
			addAjaxFieldsAutoSave();
			customizeMediaItemHeads();
			addKeyboardListeners();
			hideSortButtons();
		}
	})
	function hideSortButtons(){
		var showHideAllSpan = $('#sort-buttons span:first');
		if(!showHideAllSpan.length) return false;
		showHideAllSpan.css({
			"margin-right": 0
		})
		$('#sort-buttons').after(showHideAllSpan);
		$('#sort-buttons').html("");
		$('#sort-buttons').append(showHideAllSpan);
	}
	function putMenuOrderAfterShowHide(){
		$(".media-item").each(function(){
			$(this).find(".menu_order").prependTo($(this));
		})
	}
	function addInvertOrderButton(){
		$(".widefat thead tr").append("<th class='invert-order-head'></th>");
		$("#invertHolder").appendTo(".widefat .invert-order-head").css("display", "block");
		$("#invertOrderButton").click(function(){
			var items = new Array();
			$(".media-item").each(function(index){
				items.push($(this));
			})
			reverseOrder(items);
			$(".media-item").addClass("updated-media-item");
			
			saveAjax();
		})
		$("thead .actions-head, thead .order-head").remove();
	}
	function reverseInitialOrder(){
		// I want uploaded items to stay in the order they had on my computer
		var blankItems = new Array();
		$(".media-item").each(function(index){
			var order = parseInt($(this).find(".menu_order_input").attr("value"));
			if(isNaN(order)){
				blankItems.push($(this));
			}
		})
		reverseOrder(blankItems);
	}
	function reverseOrder(items){
		items.reverse();
		$(items).each(function(){
			$(this).appendTo($("#media-items"));
		})
	}
	function addKeyboardListeners(){
		$("tr.post_title input").keyup(function(e){
			$(this).parents(".media-item").find(".filename .title").text($(this).attr("value"));
		})
	}
	function customizeMediaItemHeads(){
		$(".media-item .menu_order input").attr("readonly", "readonly");
	}
	// Checks for new uploaded files and adds the gallery tab, if there are more then 0 files uploaded
	function checkIfMoreThanZero(){
		if(parseInt($("#attachments-count").text()) > 0){
			$("#tab-gallery").css("display", "block");
		} else {
			galleryTabTimeout = setTimeout(checkIfMoreThanZero, 1000);
		}
	}
	// Autosaving of changed fields on blur
	function addAjaxFieldsAutoSave(){
		$(".media-item input").change(function(){
			$(".updated-media-item").removeClass("updated-media-item");
			$(this).parents(".media-item").addClass("updated-media-item");
			saveAjax();
		})
		$(".media-item input:text, .media-item textarea").blur(function(){
			$(".updated-media-item").removeClass("updated-media-item");
			$(this).parents(".media-item").addClass("updated-media-item");
			saveAjax();
		})
	}
	// Autosaving of the menu_order of the attachments
	function addAjaxOrderAutoSave(){
		$(".media-item").mouseup(function(){
			$(".updated-media-item").removeClass("updated-media-item");
			$(this).addClass("updated-media-item");
			setTimeout(function(){
				if(currentMenuOrder != getMenuOrder()){
					saveAjax();
				}
			}, 50);
		})
	}
	// The AutoSave AJAX-Call
	function saveAjax(){
		if(!ajaxBusy){
			ajaxBusy = true;
			currentMenuOrder = getMenuOrder();
			$('.updated-media-item .menu_order input').hide().after('<span class="waiting"><img alt="processing" src="images/wpspin_light.gif"" /></span>');
			var items = new Array();
			// Give every Item its number
			$(".media-item").each(function(index){
				$(this).find(".menu_order_input").attr("value", index+1);
			})
			$.post($("#gallery-form").attr("action"), $("#gallery-form").serialize(), function(data){
				$('.media-item .waiting').remove();
				$('.menu_order input').show();
				ajaxBusy = false;
			});
		}
	}
	// Get the menu order of the media items by their DOM order
	function getMenuOrder(){
		var mo = "";
		$(".media-item").each(function(){
			var id = $(this).attr("id").split("media-item-")[1];
			var menuOrder = $(this).find(".menu_order_input").attr("value");
			mo += id;
		})
		return mo;
	}
})();