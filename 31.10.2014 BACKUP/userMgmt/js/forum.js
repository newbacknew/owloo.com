/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.3.1 																		 *
 *****************************************************************************************/
 
$(document).ready(function(){
	// User ToolTip
	var tt_user = '';
	$('.usr-tooltip').live("mouseover", function(){
		if(!$('#usr-tt').length){
			$('body').append('<div id="usr-tt"></div>');
		}
		
		$('#usr-tt').stop().css({opacity:0.9, display:"none"}).fadeIn(400);
		
		if(tt_user != $(this).html()){	
			tt_user = $(this).html();
			$('#usr-tt').html('<img src="images/loading.gif" alt="loading">');
			$.ajax({
				url: "ajax_calls.php",
				type: 'POST',
				dataType: "json",
				data: 'call=usrtooltip&user=' + $(this).html(),
				success: function(data){
					$('#usr-tt').html(data.message);
				}
			});
		}
		
	}).mousemove(function(kmouse){
		$('#usr-tt').css({left:kmouse.pageX+15, top:kmouse.pageY+15});
		
	}).mouseout(function(){
		$('#usr-tt').fadeOut(400);
	});
	
	
	// New Topic
	$('#newtopic').submit(function(){
		return false;
	});
	
	$('#newtopic button[type=submit]').click(function(){
		var additional = '';
		var call = 'newreply';
		if($('#stickyTopic').length && ('#lockedTopic').length){
			additional = '&sticky='+($('#stickyTopic').hasClass('active') ? 'true' : 'false')+'&locked='+($('#lockedTopic').hasClass('active') ? 'true' : 'false');
		}
		
		if($(this).attr('data-forum') != 'newreply'){
			call = 'newtopic';
			
			//check if the title is empty
			if($('#topic_title').val() == ''){
				$('#topic_title').parent().parent().addClass('error');
				return false;
			}else{
				$('#topic_title').parent().parent().removeClass('error');
			}
		}
		
		if($('input[name=topiccall]').length){
			call = $('input[name=topiccall]').val();
		}
		
		//populate the textarea with the body so we can send it
		$('#newtopic textarea').data("sceditor").updateTextareaValue();
		
		$.ajax({
			url: "ajax_calls.php",
			type: 'POST',
			dataType: "json",
			data: $('#newtopic').serialize()+'&call='+call+additional,
			success: function(reply){
				if(reply.status){
					$('body').fadeOut(600, function(){
						window.location.href='posts.php?topic='+reply.id+(call == 'newreply' ? '#post_'+reply.pid : '');
					});
				}else{
					showNotification(reply.msg, '', 'error');
				}
			}
		});
	});
	
	$('.deletePost').live("click",function(){
		if(confirm('are you sure you wish to delete this topic/post?')){
			setLoading('Please wait..', false);
			var button = $(this);
			$.ajax({
				url: "ajax_calls.php",
				type: 'POST',
				dataType: "json",
				data: 'call=deletePost&t='+$(this).attr('data-tid')+'&'+$(this).attr('data-tokenname')+'='+$(this).attr('data-token'),
				success: function(reply){
					removeLoading();
					if(reply.status){
						if(typeof button.attr('data-cid') != 'undefined'){
							$('body').fadeOut(600, function(){
								window.location.href='topics.php?cat='+button.attr('data-cid');
							});
						}else{
							$('#post_'+button.attr('data-tid')).fadeOut(400, function(){
								$(this).remove();
							})
						}
					}else{
						showNotification(reply.msg, '', 'error');
					}
				}
			});
		}
	});
});