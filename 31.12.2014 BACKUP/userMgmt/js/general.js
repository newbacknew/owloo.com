/*
 * jQuery hashchange event - v1.3 - 7/21/2010
 * http://benalman.com/projects/jquery-hashchange-plugin/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function($,e,b){var c="hashchange",h=document,f,g=$.event.special,i=h.documentMode,d="on"+c in e&&(i===b||i>7);function a(j){j=j||location.href;return"#"+j.replace(/^[^#]*#?(.*)$/,"$1")}$.fn[c]=function(j){return j?this.bind(c,j):this.trigger(c)};$.fn[c].delay=50;g[c]=$.extend(g[c],{setup:function(){if(d){return false}$(f.start)},teardown:function(){if(d){return false}$(f.stop)}});f=(function(){var j={},p,m=a(),k=function(q){return q},l=k,o=k;j.start=function(){p||n()};j.stop=function(){p&&clearTimeout(p);p=b};function n(){var r=a(),q=o(m);if(r!==m){l(m=r,q);$(e).trigger(c)}else{if(q!==m){location.href=location.href.replace(/#.*/,"")+q}}p=setTimeout(n,$.fn[c].delay)}$.browser.msie&&!d&&(function(){var q,r;j.start=function(){if(!q){r=$.fn[c].src;r=r&&r+a();q=$('<iframe tabindex="-1" title="empty"/>').hide().one("load",function(){r||l(a());n()}).attr("src",r||"javascript:0").insertAfter("body")[0].contentWindow;h.onpropertychange=function(){try{if(event.propertyName==="title"){q.document.title=h.title}}catch(s){}}}};j.stop=k;o=function(){return a(q.location.href)};l=function(v,s){var u=q.document,t=$.fn[c].domain;if(v!==s){u.title=h.title;u.open();t&&u.write('<script>document.domain="'+t+'"<\/script>');u.close();q.location.hash=v}}})();return j})()})(jQuery,this);

/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.3.1 																		 *
 *****************************************************************************************/

var logged = true;

/*
	This will handle the tabs in the admin control panel
*/
$(function(){
	//Load the tab which is selected in the address hash
	$('a[href="'+window.location.hash.replace('/', '')+'"]').tab("show");
	
	//when you click a tab, change the address hash
	$('a[data-toggle="tab"]').click(function(){
		window.location.hash = '/'+$(this).attr('href').replace('#', '');
	});
});

$(document).ready(function() {
	$(".usernav").css('width', $(".usernav").outerWidth());
	/*
		shows the terms and conditions on the signup page.
	*/
	$(document).ready(function(){
		$('#termsconditions').modal({
			show: false
		});
	});
	
	// Close notifications when to user clicks the message.
    $("#alertMessage").live("click", function() {
        hideNotification()
    });
	
	/*
		This will show the tooltips for all <a> tags with the "rel" set to "tooltip"
		example: <a href="#" rel="tooltip" title="This is the tooltip!">Show Tooltip</a>
	*/
	$('[data-rel=tooltip]').tooltip();
	$('a[data-rel=tooltip]').live("click", function(e){
		e.preventDefault();
	});	
	
    $("#logout").click(function() {
        hideNotification();
        //setLoading("Cerrando sesión...", true);
		$('#owloo_ajax_loader').fadeIn(100);
        $.ajax({
            type: "POST",
            data: "logout=" + $("#logout").attr("name"),
            dataType: "json",
            success: function(a) {
                //removeLoading();
				$('#owloo_ajax_loader').fadeOut(100);
                if (a.status) {
                    window.location.href = "login.php"
                } else {
                    showNotification("No se ha podido cerrar la sesión, por favor actualice la página e inténtelo de nuevo.", "Error", "error")
                    }
            }
        })
    });	
	
	$('#top_search').typeahead({
		source: function(typeahead, query) {
			$.ajax({
				url: "ajax_calls.php",
				dataType: "json",
				type: "POST",
				data: {
					call: 'top_search',
					user: query
				},
				success: function(data) {
					typeahead.process(data);
				}
			});
		},
		onselect: function(obj) {
			location.href='profile.php?user='+obj;
		}
	})
	
	$("#search_now").click(function(a) {
		var b = $("#search_query").val();
		hideNotification();
		setLoading('Buscando...', false);
		$.ajax({
			url: "ajax_calls.php",
			type: "POST",
			data: $("#user_search").serialize()+"&call=search",
			dataType: "json",
			success: function(a) {
				removeLoading();
				$("#search_result").html(a.message);
				if(a.status) {
					if (a.result.members.length > 0) {
						$("#search_result").append('<table id="serch_members"><thead><tr><th>Avatar</th><th>User</th><th>Join Date</th><th>Member Group</th></tr></thead><tbody id="srch_members_result"></tbody></table>');
						$.each(a.result.members, function(a, b) {
							$("#srch_members_result").append(b)
						})
					}
					return false
				}
			}
		});
		a.preventDefault()
	});
	
	/*
		########################
		##	Profile related   ##
		########################
	*/
	if($("#webcam").length > 0) {
		// Profile webcam 
		var cam = $('#webcam .screen');
		webcam.set_api_url('ajax_calls.php?call=upload_avatar');
		cam.html(webcam.get_html(220, 220));
		var shootEnabled = false;
		
		$("#useWebcam").click(function() {
			$("#webcam").show();
			return false;
		});

		$("#closeWebcam").click(function() {
			$("#webcam").hide();
			return false;
		});

		$('#takePhoto').click(function() {
			if (!shootEnabled) {
				return false;
			}
			webcam.freeze();
			toggleButtons()
			return false;
		});

		$('#retakePhoto').click(function() {
			webcam.reset();
			toggleButtons()
			return false;
		});

		$('#uploadAvatar').click(function() {
			setLoading('Actualizando su avatar, por favor espere...', true);
			webcam.upload();
			toggleButtons()
			webcam.reset();
			$.ajax({
				url: 'ajax_calls.php',
				type: 'post',
				data: 'call=get_avatar',
				dataType: "json",
				error: function() {
					removeLoading();
				},   
				success: function(a) {
					if(a.status){
						$("#big_avatar").delay(400).html('<img src="'+baseurl+'/uploads/avatars/b/' + a.avatar + '" width="180" height="180" alt="avatar" />');
						$("#account_info img.avatar").delay(400).attr("src", "").attr("src", baseurl+"/uploads/avatars/s/" + a.avatar + "");
					}
					removeLoading();
					return false;
				}
			});
			return false;
		});

		webcam.set_hook('onLoad', function() {
			shootEnabled = true;
		});

		webcam.set_hook('onError', function(e) {
			cam.html(e);
		});

		function toggleButtons() {
			var visible = $(' .buttonPane:visible:first');
			var hidden = $(' .buttonPane:hidden:first');
			visible.fadeOut('fast', function() {
				hidden.show();
			});
		}
	};
	
	$("#submit_account_changes").click(function(a) {
		hideNotification();
		setLoading("Guardando... ", true);
		$.ajax({
			url: "ajax_calls.php",
			type: "POST",
			data: $("#formLogin").serialize()+'&call='+($("input[name=userid]").length > 0 ? 'admin_process_settings' : 'updaccount'),
			dataType: "json",
			success: function(a) {
				removeLoading();
				if (!a.status) {
					showNotification(a.message, 'Error', 'error');
					return false
				} else {
					showNotification(a.message, 'Éxito', 'success');
					return false
				}
			}
		});
		a.preventDefault();
		return false;
	});
	
	$("#submit_profile_changes").click(function(a) {
		hideNotification();
		setLoading("Guardando... ", true);
		
		var call = ($("input[name='userid']").length ? 'admupdprofile' : 'updprofile')
		$.ajax({
			url: "ajax_calls.php",
			type: "POST",
			data: $("#edit_profile_fields").serialize()+'&call='+call,
			dataType: "json",
			success: function(a) {
				removeLoading();
				if (!a.status) {
					showNotification(a.message, 'Error', 'error');
					return false
				} else {
					showNotification(a.message, 'Éxito', 'success');
					return false
				}
			}
		});
		a.preventDefault();
		return false;
	});
});

/*
	###################
	##	General JS   ##
	#####%%############
*/
var firstcheck = true;

function load_messages_dav() {
	$.ajax({
		url: "ajax_calls.php",
		type: "POST",
		data: "call=pmlist_dav",
		dataType: "json",
		success: function(a) {
			var htmlContent = '';
			var num_item = a.length;
			if(num_item > 5)
				num_item = 5;
			for(var i = 0; i < num_item; i++){
				var to = '';
				var read = '';
				if(a[i].sender == 'owloo' && a[i].status != 'read')
					to = ' pm_new_count_admin';
				if(a[i].status == 'read')
					read = ' pm_new_count_read';
				htmlContent += '<div id="pm_new_count_item_' + a[i].pm_id + '" class="pm_new_count_item' + to + '"><div>' + a[i].subject + '</div><a class="btn btn-success pop_box ReadSelectedPM ReadSelectedPM_dav' + read + '" name="' + a[i].pm_id + '" href="#">Leer</a></div>';
			}
			$('.pm_new_count_content').html(htmlContent);
		}
	});
}

function RunCheck() {
    if (logged) {
        $.ajax({
            url: "ajax_calls.php",
            type: "POST",
            data: "call=checkaccount",
            dataType: "json",
            success: function(a) {
                if (a.status) {
                    if (a.unread_pms > 0) {
                        $(".nav.pull-right .badge-pm").html(a.unread_pms);
						if($("#pm_new_count")){
							$("#pm_new_count").html(a.unread_pms);
							load_messages_dav();
						}
                        if (!$(".nav.pull-right .badge-pm").is(":visible")) {
                            $(".nav.pull-right .badge-pm").show()
                            }
                        if ($("#InboxPM").is(":visible") && total_messages < a.unread_pms) {
							setLoading('Descargando nuevos mensajes...', true);
                            $.ajax({
                                url: "ajax_calls.php",
                                type: "POST",
                                data: "call=pmlist",
                                dataType: "json",
                                success: function(b) {
									pmtable.fnClearTable();
                                    $.each(b.messages, function() {
                                        $("#InboxPM table").dataTable().fnAddData([this.check, this.sender, this.date, this.controls])
                                    })
									
									cur_pm_space_left = b.pm_space_left;
									$("#storagecount div span").html("Inbox Limit: " + (b.pm_max - b.pm_space_left) + " of " + b.pm_max);
									$("#storagecount div").css('width', ((b.pm_max - b.pm_space_left) / b.pm_max * 100)+'%');
									
									removeLoading();
                                }
                            })
                        }
                        total_messages = a.unread_pms
                    } else {
                        if ($(".nav.pull-right .badge-pm").is(":visible")) {
                           	$(".nav.pull-right .badge-pm").fadeOut()
                        }
						if($("#pm_new_count"))
							$("#pm_new_count").html(a.unread_pms);

                    }
                    if (a.friend_requests > 0) {
                        if ($("#main_menu a[href='friends.php'] img").length < 1) {
                            $("#main_menu a[href='friends.php']").append(' <img src="images/icon/color_18/new_request.png" title="New Friend Request!" alt="" />')
                            }
                    } else {
                        if (!$("#main_menu a[href='friends.php'] img")) {
                            $("#main_menu a[href='friends.php'] img").remove()
                            }
                    }
					if (a.unread_pms > 0) {
						requests = '';
						if(a.friend_requests > 0){
							requests = '<img src="images/new.gif" alt="" title="New friend Request" />';
						}
						$(".nav .badge.badge-friends").html(a.online_friends);
						$(".nav .badge.badge-friends").parent().append(requests);
					}
					
                } else {
					if(logged && !firstcheck){
						showNotification('Usted no está logueado.', 'Inactivo', 'error', true);
					}
					
                    logged = false
				}
				
				if(firstcheck)
				{
					firstcheck = false;
				}
            }
        });
	}
}

var notificationInt = null;
function showNotification(a, b, c, sticky) {
	hideNotification();
	
	if( typeof sticky == 'undefined'){
		sticky = false;
	}
	
	if(typeof b == 'undefined'){
		b = '';
	}else{
		b = '';
	}
	
    switch (c) {
    case "error":
        c = "alert-error";
        break;
    case "info":
        c = "alert-info";
        break;
    case "success":
        c = "alert-success";
        break;
    default:
        c = "";
        break
    }
    position = $("#alertMessage").position();
    $("#alertMessage").html('<div class="alert ' + c + '"><a class="close" data-dismiss="alert" href="#">&times;</a>' + b + a + "</div>").stop(true, true).animate({
        top: 0
	}, 500, function(){
		if(!sticky){
			notificationInt = setInterval(hideNotification, 10000);
		}
	})
}
	
function hideNotification() {
	clearInterval(notificationInt);
	
    $("#alertMessage").animate({
        top: -$("#alertMessage").height()
    }, 500)
}

function setLoading(a, b) {
    $("body").append('<div id="loading_overlay"></div><div id="loading_loader">' + a + "</div>");
	if(b){
		$("#loading_overlay").css("opacity", .15).fadeIn(function() {
			$("#loading_loader").fadeIn()
		})
	}else{
		$("#loading_loader").fadeIn()
	}
}

function removeLoading() {
	$("#loading_loader").fadeOut("fast", function() {
		$("#loading_loader").remove();
		$("#loading_overlay").animate({
			opacity: 0
		},function(){
			$(this).remove()
		})
	})
}

function highlightsub(pid){
	if(typeof pid != 'undefined' && $('#'+pid).length){
		$('#'+pid).append('<div class="activeplan"></div>').addClass('plan_highlight');
	}
}

RunCheck();
if(logged){
	setInterval(function() {
		RunCheck()
	}, AccCheckIntval)
}