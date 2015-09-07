/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.3.1 																		 *
 *****************************************************************************************/
 /*
	######################
	##	Friend System   ##
	######################
*/
var removeF_ID = 0;
var addF_ID = 0;

$(".btn_add_friend").live("click", function(){
	b = $(this).attr('name');
	whichcall = 'addfriend';
	loading_text = 'Sending friend request..';
	if($(this).hasClass('approve_friend')){
		whichcall = 'acceptfriend';
		loading_text = 'Accepting Friend..';
	}
	if (addF_ID != b) {
		addF_ID = b;
		hideNotification();
		setLoading(loading_text, true);
		$.ajax({
			url: "ajax_calls.php",
			type: "POST",
			data: "call="+whichcall+"&fid=" + b,
			dataType: "json",
			success: function(a) {
				addF_ID = 0;
				removeLoading();
				if (!a.status) {
					showNotification(a.message, 'Error', 'error')
				} else {
					if(whichcall == 'acceptfriend'){
						$("#flr_"+b+" .btn_add_friend").fadeOut("fast");
						$("#flr_"+b+" .friend_pending").removeClass('friend_pending').addClass('friend_accepted').html('<a href="#">Accepted</a>');
						
					}else{
						if($("button.btn_add_friend").length){
							$("button.btn_add_friend").fadeOut(200, function() {
								$("button.btn_remove_friend").removeClass('hidden').fadeIn(200);
							});
						}
					}
					showNotification(a.message, 'Success', 'success')
				}
				return false
			}
		})
	}
});

$(".btn_remove_friend").live("click", function(){
	a = $(this).attr('name');
	if (removeF_ID != a) {
		removeF_ID = a;
		hideNotification();
		setLoading("Processing..", true);
		$("#flr_" + a + " .friend_accepted a, #flr_" + a + " .friend_pending a, button.removeFriend").html("Removing friend..");
		$.ajax({
			url: "ajax_calls.php",
			type: "POST",
			data: "call=removefriend&fid=" + a,
			dataType: "json",
			success: function(b) {
				if($("button.btn_remove_friend").length){
					$("button.btn_remove_friend").fadeOut(200, function(){
						$("button.btn_remove_friend").html("<i class=\"icon-remove icon-white\"></i> Cancel Friend Request");
						$("button.btn_add_friend").removeClass('hidden').fadeIn(200);
					});
				}
				if($("#flr_"+a).length){
					$("#flr_" + a).fadeOut(300).remove();
				}
				removeF_ID = 0;
				removeLoading();
				if (!b.status) {
					showNotification(b.message)
				} else {
					showNotification("Friend was removed.", 'Removed', 'error')
				}
				return false
			}
		})
	}
});