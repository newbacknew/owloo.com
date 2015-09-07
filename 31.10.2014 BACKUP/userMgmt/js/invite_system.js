/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.3.1 																		 *
 *****************************************************************************************/
 $(document).ready(function(){
	
	$("#send_invite button").click(function(e){
		var inv_orig_html = '';
		var button = $(this);
		inv_orig_html = button.html();
		
		hideNotification();
		button.attr('disabled', true).html( button.attr('data-loadingmsg') );
		setLoading( 'Proceesing..', false);
		
		$.ajax({
			url: "ajax_calls.php",
			type: "POST",
			data: $("#send_invite").serialize()+"&call=sendinvite",
			dataType: "json",
			success: function(data) {
				removeLoading();
				button.attr('disabled', false).html(inv_orig_html);
				showNotification(data.message, (data.status ? 'Success!' : 'Error!'), (data.status ? 'success' : 'error'));
				
				if(data.status){
					$("#send_invite").get(0).reset();
					$("#invites table tbody").prepend(data.html);
					$("#noofinvites").html(parseInt($("#noofinvites").html())-1);
				}
			}
		});
		
		e.preventDefault()
		return false;
	});
	
	$("button.revokeinvite").live("click",function(e){
		hideNotification();
		var button = $(this);
		
		button.css('disabled', true);
		setLoading('Processing..', false);
		$.ajax({
			url: "ajax_calls.php",
			type: "POST",
			data: "call=revokeinvite&invite="+button.attr('data-inviteid'),
			dataType: "json",
			success: function(data) {
				removeLoading();
				
				if(data.status){
					button.parents('tr').fadeOut("slow", function(){
						$(this).remove();
					});
					$("#noofinvites").html(parseInt($("#noofinvites").html())+1);
				}else{
					showNotification(data.message, 'Error', 'error');
					button.css('disabled', false);
				}
			}
		});
		a.preventDefault()
	});
	
});