/*	#######################	##	Admin Controls   ##	#######################*/
var isIE = false;
if (navigator.appName == 'Microsoft Internet Explorer') {
    isIE = true;
}
$(document).ready(function() {
    /*	#################################################  MEMBERS  #################################################*/
    var crtl_status;
    $(".ctrl_membergroups").autocomplete({
        source: function(req, res) {
            crtl_status = this.element;
            $.ajax({
                url: "ajax_calls.php",
                type: 'POST',
                dataType: "json",
                data: 'call=adm_membersgroups&group=' + req.term,
                success: function(data) {
                    res($.map(data.groups, function(item) {
                        return {
                            label: item.name,
                            value: item.name,
                            id: item.id,
                            uid: crtl_status.attr('name')
						}
                    }));
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            crtl_status.parent().children('span').removeClass("icon-ok icon-remove").html('<img src="images/loading.gif" width="14px" title="Updating.." />');
            $.ajax({
                url: "ajax_calls.php",
                type: 'POST',
                dataType: "json",
                data: 'call=adm_updgroup&uid=' + ui.item.uid + '&gid=' + ui.item.id,
                success: function(data) {
                    if (data.status) {
                        crtl_status.parent().children('span').addClass("icon-ok").html('');
                    } else {
                        crtl_status.parent().children('span').addClass("icon-remove").html('');
                    }
                }
            });
        }
    });
    /* 		Show/Hide the "Add New User" tab 	*/
    if (!isIE) {
        $("#adm_newuser").css('height', $("#adm_newuser").height());
    }
    $(".admShowAddUser").click(function() {
        if ($("#adm_newuser").is(':hidden')) {
            $("#adm_newuser").slideDown();
        } else {
            $("#adm_newuser").slideUp();
        }
    });
    /* 		create the new user 	*/
    $("#adm_newuser").submit(function() {
        $.ajax({
            url: "ajax_calls.php",
            type: 'POST',
            dataType: "json",
            data: $("#adm_newuser").serialize() + '&call=adm_adduser',
            success: function(data) {
                if (data.status) {
                    $("#adm_newuser").get(0).reset();
                    $("#user_list .user_list").prepend(data.html);
                }
                showNotification(data.message, data.title, data.msgtype);
            }
        });
        return false;
    })
    /* 		Delete user 	*/
    $(".ctrl_deltuser").live("click", function() {
        if (confirm('Are you sure you wish to delete this user?')) {
            button = $(this);
            $.ajax({
                url: "ajax_calls.php",
                type: 'POST',
                dataType: "json",
                data: 'call=adm_deltuser&uid=' + button.attr('href'),
                success: function(data) {
                    if (data.status) {
                        button.parents('tr').fadeOut(400, function() {
                            button.remove();
                        });
                    } else {
                        showNotification(data.message, 'Error', 'error');
                    }
                }
            });
        }
        return false;
    });
    /*	#################################################					MEMBER GROUPS	#################################################*/
    /*		Colour Picker	*/
    jQuery.farbtastic('#AddNewGroup .colorpicker').linkTo('#AddNewGroup .groupcolour');
    $("#AddNewGroup .groupcolour").live("click", function() {
        $('#AddNewGroup .colorpicker').fadeIn();
        $('#AddNewGroup .colorpicker').parents('div').mouseleave(function() {
            $('#AddNewGroup .colorpicker').fadeOut();
        });
    })
    /* 		create the new user group 	*/
    $("#adm_newgroup").live("click", function() {
        $.ajax({
            url: "ajax_calls.php",
            type: 'POST',
            dataType: "json",
            data: $("#AddNewGroup form").serialize() + '&call=adm_addgroup',
            success: function(data) {
                if (data.status) {
                    $("select[name=usergroup]").append('<option value="' + data.id + '##' + data.name + '">' + data.name + '</option>');
                    $("#AddNewGroup form").get(0).reset();
                    $("#group_list .group_list").prepend(data.html);
                }
                showNotification(data.message, data.title, data.msgtype);
            }
        });
        return false;
    })
    /*		delete user group	*/
    $(".delete_group").live("click", function() {
        if (confirm('Are you sure you wish to delete this user group?')) {
            $(this).attr('disabled', true).html('Deleting');
            var groupid = $(this).attr("data-groupid");
            var groupname = $(this).parents('tr').children('td').children('span.label').html();
            $.ajax({
                url: "ajax_calls.php",
                type: 'POST',
                dataType: "json",
                data: 'call=adm_deletegroup&id=' + groupid,
                success: function(data) {
                    if (data.status) {
                        $('select[name=usergroup] option[value="' + groupid + '##' + groupname + '"]').remove();
                        $("#AddNewGroup form").get(0).reset();
                        $("#group_" + groupid).fadeOut(400, function() {
                            $(this).remove();
                        });
                    }
                }
            });
        }
        return false;
    })
    /*		edit user group	*/
    $(".edit_group").live("click", function() {
        var groupid = $(this).attr("data-groupid");
        $.ajax({
            url: "ajax_calls.php",
            type: 'POST',
            dataType: "json",
            data: 'call=adm_getgroup&id=' + groupid,
            success: function(data) {
                if (data.status) {
                    $("#EditGroup form").html(data.html);
                    jQuery.farbtastic('#EditGroup .colorpicker').linkTo('#EditGroup .groupcolour');
                    $("#EditGroup .groupcolour").live("click", function() {
                        $('#EditGroup .colorpicker').fadeIn();
                        $('#EditGroup .colorpicker').parents('div').mouseleave(function() {
                            $('#EditGroup .colorpicker').fadeOut();
                        });
                    });
                    $("#EditGroup").fadeIn(400, function() {
                        $('#EditGroup a[data-rel=tooltip]').tooltip();
                    });
                }
            }
        });
        return false;
    })
        $("#close_adm_editgroup").click(function() {
        if ($("#EditGroup").is(":visible")) {
            $("#EditGroup").fadeOut(400);
        }
    });
    /* 		Submit User Group Changes	*/
    $("#adm_update_usergroup").live("click", function() {
        $.ajax({
            url: "ajax_calls.php",
            type: 'POST',
            dataType: "json",
            data: $("#EditGroup form").serialize() + '&call=adm_editgroup',
            success: function(data) {
                if (data.status) {
                    $("#EditGroup").fadeOut(400);
                    showNotification(data.message, '', 'success');
                }
            }
        });
        return false;
    });
    $("#show_adm_newgroup").click(function() {
        if (!$("#AddNewGroup").is(":visible")) {
            $("#AddNewGroup").fadeIn(400);
        }
    });
    $("#close_adm_newgroup").click(function() {
        if ($("#AddNewGroup").is(":visible")) {
            $("#AddNewGroup").fadeOut(400);
        }
    });
    /*	#################################################					GENERAL	#################################################*/
    /*		Pagination clicking	*/
    $(".pagination li a").live("click", function(e) {
        page = $(this).attr('href');
        if (page != '#') {
            location.href = page + window.location.hash;
        }
        e.preventDefault();
    });
    /*		Version Check	*/
    var syscheck = false;
    $("#version-check button").live("click", function(e) {
        if (!syscheck) {
            syscheck = true;
            button = $(this);
            button.attr('disabled', 'disabled').html('Checking Version..');
            $("#checkmessage").html('Running..')
                $.ajax({
                url: "ajax_calls.php",
                type: 'POST',
                dataType: "json",
                data: 'call=adm_checkversion',
                success: function(data) {
                    button.attr('disabled', false).html('Check Completed');
                    syscheck = false;
                    if (data.status) {
                        $("#latestversion").html(data.version);
                        $("#checkmessage").html(data.message)
                        }
                }
            });
        }
        e.preventDefault();
    })
    /*		Clear Error Log	*/
    $("#clearErrLog").live("click", function(e) {
        button = $(this);
        button.attr('disabled', 'disabled').html('Deleting Log..');
        $.ajax({
            url: "ajax_calls.php",
            type: 'POST',
            dataType: "json",
            data: 'call=adm_clearlog',
            success: function(data) {
                if (data.status) {
                    $("#error-log textarea").html('');
                }
                button.attr('disabled', false).html('Clear Error Log');
            }
        });
        e.preventDefault();
    })
    /*	#################################################					SETTINGS	#################################################*/
    /*		mailer type show/hide	*/
    if ($("select[name=mailtype]").val() == 'smtp') {
        $('#smtp_settings').show();
    }else{
		 $('#php_settings').show();
	}
	
    $("select[name=mailtype]").change(function() {
        if ($(this).val() == 'smtp') {
			$('#php_settings').fadeOut();
			
            if ($('#smtp_settings').is(':hidden')) {
                $('#smtp_settings').slideDown();
            }
        } else {
            if ($('#smtp_settings').is(':visible')) {
                $('#smtp_settings').slideUp();
            }
			
			$('#php_settings').fadeIn();
        }
    })
    /*		Save Settings	*/
    $(".btn_savesettings").click(function(e) {
        $.ajax({
            url: "ajax_calls.php",
            type: 'POST',
            dataType: "json",
            data: 'call=adm_savesettings' + '&' + $("#adm_settings_general").serialize() + '&' + $("#adm_settings_security").serialize() + '&' + $("#adm_settings_account").serialize() + '&' + $("#adm_settings_legal").serialize() + '&' + $("#adm_settings_invref").serialize() + '&' + $("#adm_settings_forum").serialize() + '&' + $("#adm_paypal_settings").serialize(),
            success: function(data) {
                if (data.status) {
                    showNotification('Settings Saved!', 'Success', 'success');
                } else {
                    showNotification('Error while saving!', 'Error', 'error');
                }
            }
        });
        e.preventDefault()
            return false;
    })
    /*		Clear Cache	*/
    $(".adm_clearCache").live("click", function(e) {
        $.ajax({
            url: "ajax_calls.php",
            type: 'POST',
            dataType: "json",
            data: '&call=adm_clearCache',
            success: function(data) {
                if (data.status) {
                    $(".adm_clearCache span").removeClass('icon-remove').addClass("icon-ok");
                } else {
                    $(".adm_clearCache span").removeClass('icon-ok').addClass("icon-remove");
                }
                setTimeout("$('.adm_clearCache span').removeClass('icon-ok icon-remove')", 3000);
            }
        });
        e.preventDefault();
    })
    /*	#################################################					TEMPLATING	#################################################*/
    /*		Load Email Template	*/
    $("select[name=emailtemplatelist]").change(function() {
        if ($(this).val() == '') {
            return false;
        }
        $.ajax({
            url: "ajax_calls.php",
            type: 'POST',
            dataType: "json",
            data: 'call=adm_loademailtmpl&tmpl=' + $(this).val(),
            success: function(data) {
                if (data.status) {
                    $("#emailtemplate").html(data.html);
                } else {
                    showNotification(data.message, 'Error', 'error');
                }
                $('#emailtemplate a[rel=tooltip]').tooltip();
            }
        });
    })
    /*		Save Email Template	*/
    $(".saveEmailTemplate").live("click", function() {
        $.ajax({
            url: "ajax_calls.php",
            type: 'POST',
            dataType: "json",
            data: $("#emailtemplate").serialize() + '&call=adm_saveemailtmpl',
            success: function(data) {
                if (data.status) {
                    showNotification(data.message, 'Success', 'success');
                } else {
                    showNotification(data.message, 'Error', 'error');
                }
            }
        });
    })
	/*		Enviar mensajes 	*/
    $(".enviarMensjeAll").live("click", function() {
		setLoading('Enviando... ',true);
        $.ajax({
            url: "ajax_calls.php",
            type: 'POST',
            dataType: "json",
            data: $("#envio-emails").serialize() + '&call=adm_enviarmensajeall',
            success: function(data) {
				removeLoading();
                if (data.status) {
                    showNotification(data.message, 'Success', 'success');
                } else {
                    showNotification(data.message, 'Error', 'error');
                }
            }
        });
    })
    /*		Load (edit) Language	*/
    $("select[name=languagelist]").change(function() {
        if ($(this).val() == '') {
            return false;
        }
        $.ajax({
            url: "ajax_calls.php",
            type: 'POST',
            dataType: "json",
            data: 'call=adm_loadEditLanguage&lang=' + $(this).val(),
            success: function(data) {
                if (data.status) {
                    $("#languageeditor").html(data.html);
                } else {
                    showNotification(data.message, 'Error', 'error');
                }
            }
        });
    })
    /*		Save Language	*/
    $(".saveLanguage").live("click", function() {
        $.ajax({
            url: "ajax_calls.php",
            type: 'POST',
            dataType: "json",
            data: $("#languageeditor").serialize() + '&call=adm_savelanguage',
            success: function(data) {
                if (data.status) {
                    showNotification(data.message, 'Success', 'success');
                } else {
                    showNotification(data.message, 'Error', 'error');
                }
            }
        });
    })
    /*	#################################################
		PROFILE FIELDS
		#################################################*/
    /*		Add Field	*/
    var c = 0;
    if ($("table.profile_fields tbody > tr").length) {
        c = parseInt($("table.profile_fields tbody tr:last-child").attr('id').replace('field_row_', ''));
    }
    $(".profile_field_add").live("click", function(e) {
        c++;
        $("table.profile_fields tbody").append('<tr id="field_row_' + c + '">' + '<td><input type="text" name="profile_field_group[' + c + ']" placeholder="Group Name" class="input-medium"></td>' + '<td>' + '<select name="profile_field_type[' + c + ']" class="input-small">' + '<option value="0">Textarea</option>' + '<option value="1">Text Field</option>' + '<option value="2">Checkbox</option>' + '<option value="3">Radio</option>' + '<option value="4">Select</option>' + '</select>' + '</td>' + '<td>' + '<input type="text" name="profile_field_options[' + c + ']" placeholder="Separate options with ," class="input-medium" />' + '</td>' + '<td>' + '<select name="profile_field_signup[' + c + ']" class="input-small">' + '<option value="0">Optional</option>' + '<option value="1">Required</option>' + '<option value="2">Hidden</option>' + '</select>' + '</td>' + '<td><input type="text" name="profile_field_label[' + c + ']" placeholder="Field Label" class="input-medium"></td>' + '<td><input type="checkbox" name="profile_field_profile[' + c + ']" value="1"></td>' + '<td><button class="btn btn-warning profile_field_delete">Delete</button></td>' + '</tr>');
        e.preventDefault();
    })
    /*		Delete profile field	*/
    var id = 0;
    $(".profile_field_delete").live("click", function(e) {
        if (confirm('Are you sure you wish to delete this field?')) {
            id = parseInt($(this).parent().parent().attr('id').replace('field_row_', ''));
            $.ajax({
                url: "ajax_calls.php",
                type: 'POST',
                dataType: "json",
                data: 'call=adm_deleteProfileField&fid=' + id,
                success: function(data) {
                    $("#field_row_" + id).slideUp(400, function() {
                        $(this).remove();
                    });
                }
            });
        }
        e.preventDefault();
    })
    /*		save profile fields	*/
    $(".adm_saveProfileFields").live("click", function(e) {
        button = $(this);
        var message = '';
        button.attr('disabled', 'disabled').html('Saving Fields..');
        $.ajax({
            url: "ajax_calls.php",
            type: 'POST',
            dataType: "json",
            data: $("#adm_profile_fields").serialize() + '&call=adm_saveProfileFields',
            success: function(data) {
                if (data.status) {
                    message = 'Save Complete!';
                } else {
                    message = 'Nothing Saved.';
                }
                button.attr('disabled', false).html(message);
                setTimeout('button.html("Save Profile Fields");', 3500);
            }
        });
        e.preventDefault();
    })
	
    /*	#################################################
						INVITE MANAGEMENT
		#################################################
	
	*/
    /*		Give/Remove invites from user	*/
    $("#manage_user_invites").submit(function(e) {
        var button = $(this).children('button');
        button.removeClass('btn-info btn-success btn-danger').addClass('btn-info').attr('disabled', 'disabled').html('Processing..');
        $.ajax({
            url: "ajax_calls.php",
            type: 'POST',
            dataType: "json",
            data: $("#manage_user_invites").serialize() + '&call=adm_give_user_invites',
            success: function(data) {
                var btnclass = 'btn-danger';
                var btnhtml = 'Failed!';
                if (data.status) {
                    btnclass = 'btn-success';
                    btnhtml = 'Success!';
                }
                button.attr('disabled', false).removeClass('btn-info').addClass(btnclass).html(btnhtml);
            }
        });
        e.preventDefault();
    })
    /*		Give/Remove invites from group	*/
    $("#manage_group_invites").submit(function(e) {
        var button = $(this).children('button');
        button.removeClass('btn-info btn-success btn-danger').addClass('btn-info').attr('disabled', 'disabled').html('Processing..');
        $.ajax({
            url: "ajax_calls.php",
            type: 'POST',
            dataType: "json",
            data: $("#manage_group_invites").serialize() + '&call=adm_give_group_invites',
            success: function(data) {
                var btnclass = 'btn-danger';
                var btnhtml = 'Failed!';
                if (data.status) {
                    btnclass = 'btn-success';
                    btnhtml = 'Success!';
                }
                button.attr('disabled', false).removeClass('btn-info').addClass(btnclass).html(btnhtml);
            }
        });
        e.preventDefault();
    })
    /*		Searcher for user groups (invite manager)	*/
    $("#manage_group_invites input[name=group]").autocomplete({
        source: function(req, res) {
            $.ajax({
                url: "ajax_calls.php",
                type: 'POST',
                dataType: "json",
                data: 'call=adm_membersgroups&group=' + req.term,
                success: function(data) {
                    res($.map(data.groups, function(item) {
                        return {
                            label: item.name,
                            value: item.name,
                            id: item.id
                        }
                    }));
                }
            });
        },
        minLength: 2
    });
    /*		Searcher for members (invite manager)	*/
    $("#manage_user_invites input[name=username]").autocomplete({
        source: function(req, res) {
            $.ajax({
                url: "ajax_calls.php",
                type: 'POST',
                dataType: "json",
                data: 'call=adm_members&user=' + req.term,
                success: function(data) {
                    res($.map(data.users, function(item) {
                        return {
                            label: item.name,
                            value: item.name,
                            id: item.id
                        }
                    }));
                }
            });
        },
        minLength: 2
    });
	
	
	/*		FORUM MANAGEMENT 		*/
	$('#forum-forums .dd').nestable();
	
	// Add new categories
	$('#addForumCateogry').click(function(){
		var name = prompt("Category Name: ", "");
		if(name != null && name != ''){
			$('#forum-manager .forum_list').append('<li class="dd-item dd3-item" data-id="'+(parseInt($('.dd-item').length)+1)+'">'+
														'<div class="dd-handle dd3-handle">Drag</div>'+
														'<div class="dd3-content"><span>'+ name +'</span>'+
															'<button data-new="true" data-cid="'+(parseInt($('.dd-item').length)+1)+'" class="forum_cat_delete pull-right btn btn-danger btn-mini">Delete</button>'+
															'<button style="display:none;" data-cid="'+(parseInt($('.dd-item').length)+1)+'" class="forum_cat_edit pull-right btn btn-success btn-mini">Edit Category</button>'+
														'</div>'+
													'</li>');
		}
		
		return false;
	});
	
	// Save the forum display settings and new categories
	$('#saveForum').click(function () {
		var parent;
        var data = {
            forums: []
        };
        $('#forum-forums .dd .dd-item').each(function (key, value) {
			parent = 0;
			if($(this).parents('.dd-item').length){
				parent = $(this).parents('.dd-item').attr('data-id');
			}
            data.forums.push({
                "name": $(this).children('.dd3-content').children('span').html(),
                "id": $(this).attr('data-id'),
                "sub": parent,
                "sort": key
            });
        });
        $.ajax({
            url: "ajax_calls.php",
            type: 'POST',
            dataType: "json",
            data: 'call=adm_saveforum&data=' + JSON.stringify(data),
            success: function (reply) {
                if (reply.status) {
                    showNotification(reply.msg, '', 'success');
					$('.dd3-content button').each(function(){
						$(this).attr('data-new', '');
						if($(this).hasClass('forum_cat_edit')){
							$(this).show();
						}
					});
                } else {
                    showNotification(reply.msg, '', 'error');
                }
            }
        });
        return false;
    });
	
	// Delete Category
	$('.forum_cat_delete').live("click", function(){
		var button = $(this);
		var sub = $('.dd3-item[data-id='+button.attr('data-cid')+'] .dd-list');
		// check if the category got any sub categories.
		
		if(sub.length && sub.html() != ''){
			showNotification('The category must not have any sub categories.', '', 'error');
			return false;
		}
		
		if(confirm('All posts in this category will be deleted! Are you sure you wish to do this?')){
			setLoading('Please wait..');
			$.ajax({
				url: "ajax_calls.php",
				type: 'POST',
				dataType: "json",
				data: 'call=adm_deleteforum&fid=' + button.attr('data-cid'),
				success: function (reply) {
					if(reply.status || button.attr('data-new') == 'true'){
						button.parent().parent('.dd-item').fadeOut(400, function(){
							$(this).remove();
						});
						showNotification(reply.msg, '', 'success');
					} else {
						showNotification(reply.msg, '', 'error');
					}
					
					removeLoading();
				}
			});
		}
		
		return false;
	});
	
	// Load Category Settings
	$('.forum_cat_edit').live("click", function(){
		var cid = $(this).attr('data-cid');
		
		setLoading('Please wait..');
		$.ajax({
			url: "ajax_calls.php",
			type: 'POST',
			dataType: "json",
			data: 'call=adm_loadcatsettings&fid=' + cid,
			success: function (reply) {
				if(reply.status){
					$('#forum_cat_settings .modal-body form').html(reply.data);
					$('#forum_cat_settings').fadeIn(300);
				}else{
					showNotification(reply.msg, '', 'error');
				}
				
				removeLoading();
			}
		});
		
		return false;
	});
	
	$('#forum_catset_cancel').live("click", function(){		
		$('#forum_cat_settings').fadeOut(300);
	});
	
	// Save Category Settings
	$('#forum_catset_save').live("click", function(){		
		setLoading('Please wait..', true);
		$.ajax({
			url: "ajax_calls.php",
			type: 'POST',
			dataType: "json",
			data: $('#forum_cat_settings .modal-body form').serialize()+'&call=adm_savecatsettings',
			success: function (reply){
				if(reply.status){
					showNotification(reply.msg, '', 'success');
					$('.dd3-item[data-id='+$('#forum_cat_settings .modal-body form input[name=cat_id]').val()+'] > .dd3-content span').html($('#cat_name').val());
					$('#forum_cat_settings').fadeOut(300);
				}else{
					showNotification(reply.msg, '', 'error');
				}
				removeLoading();
			}
		});
		
		return false;
	});
	
	/* Social Integration */
	// Save Settings
	$('.btn_savesocialsettings').live("click", function(){		
		setLoading('Please wait..', true);
		$.ajax({
			url: "ajax_calls.php",
			type: 'POST',
			dataType: "json",
			data: $('#socialIntegration').serialize()+'&call=adm_savesocialsettings',
			success: function (reply){
				removeLoading();
				
				if(reply.status){
					showNotification(reply.msg, '', 'success');
				}else{
					showNotification(reply.msg, '', 'error');
				}
			}
		});
		
		return false;
	});
	
	
	/*	#################################################
						TRIGGER MANAGEMENT
		#################################################
	
	*/
    /*	Add/remove trigger to user/group/all	*/
    $("#apply_trigger, #remove_trigger").submit(function(e) {
		var button = $(this).children("button[type='submit']"),
			origText = button.html(),
			call = '';
			
		if($(this).attr('id') == 'apply_trigger'){
			call = 'adm_addtrigger';
		}else{
			call = 'adm_removetrigger';
		}
			
        button.removeClass('btn-info btn-success btn-danger').addClass('btn-info').attr('disabled', 'disabled').html('Processing..');
        $.ajax({
            url: "ajax_calls.php",
            type: 'POST',
            dataType: "json",
            data: $(this).serialize() + '&call='+call,
            success: function(data) {
				if(data.status){
					showNotification(data.message, 'Success', 'success');
				}else{
					showNotification(data.message, 'Error', 'error');
				}
				
				button.removeClass('btn-info').addClass('btn-success').attr('disabled', false).html(origText);
            }
        });
		
        e.preventDefault();
		return false;
    })
   
   /* change the search field depending on what the user selects */
	$("#apply_trigger select[name='range'], #remove_trigger select[name='range']").change(function() {
		if($(this).val() == 2){
			$(this).parent().children('input[name=to]').fadeOut(300);
		}else{
			$(this).parent().children('input[name=to]').fadeIn(300);
		}
	});
	
    /*	Searcher for users/groups */
    $("#apply_trigger input[name=to], #remove_trigger input[name='to']").autocomplete({
        source: function(req, res) {
            $.ajax({
                url: "ajax_calls.php",
                type: 'POST',
                dataType: "json",
                data: 'call=adm_searchall&data=' + req.term,
                success: function(data) {
                    res($.map(data.results, function(item) {
                        return {
                            label: item.name,
                            value: item.name,
                            id: item.id
                        }
                    }));
                }
            });
        },
        minLength: 1
    });
	
	/*
		Admin Account Activation
	*/
	var actacc = false;
	$('.admActivateAcc').live("click", function(e){
		if(!actacc){
			actacc = true;
			
			var button = $(this),
				text = button.html();
			
			button.removeClass('btn-warning').addClass('btn-info').html('Activating..').attr('disabled', true);
			
			$.ajax({
				url: "ajax_calls.php",
				type: 'POST',
				dataType: "json",
				data: 'call=adm_activateacc&uid='+button.attr('data-uid'),
				success: function(data){
					actacc = false;
					
					if(data.status){
						button.fadeOut(400, function(){
							button.parent().html('Never');
						});
					}else{
						button.removeClass('btn-info').addClass('btn-warning').html(text).attr('disabled', false);
						showNotification(data.msg, 'Error', 'error');
					}
				}
			});
		}
		
        e.preventDefault();
		return false;
	});
		
	 /*	#################################################
						MEMBERSHIPS
		#################################################*/
	/*	Add Membership	*/
	$('.plandata').editable();
	
	var ms_c = 0;
	if ($("table.memberships_list tbody > tr").length) {
		ms_c = parseInt($("table.memberships_list tbody tr:last-child").attr('id').replace('membership_row_', ''));
	}
	$("#membership_plan_add").live("click", function(e) {
		ms_c++;
		$("table.memberships_list tbody").append('<tr id="membership_row_' + ms_c + '">'+
													'<td>'+
														'<a href="#" class="membership_'+ ms_c +'_editable plandata" data-html="true" data-type="text" data-name="membership_plan_name[' + ms_c + ']" data-original-title="Enter plan name"></a>'+
													'<td>'+
														'<a href="#" class="membership_'+ ms_c +'_editable plandata" data-html="true" data-type="textarea" data-name="membership_plan_description[' + ms_c + ']" data-original-title="Enter Plan Description"></a>'+
													'</td>'+
													'<td>'+
														'<a href="#" class="membership_'+ ms_c +'_editable plandata" data-html="true" data-type="text" data-inputclass="input-mini" data-name="membership_plan_duration[' + ms_c + ']" data-original-title="Enter Price"></a>&nbsp;'+
														'<a href="#" class="membership_'+ ms_c +'_editable plandata" data-inputclass="input-small" data-html="true" data-type="select" data-name="membership_plan_durationtype[' + ms_c + ']" data-original-title="Select member group" data-source="ajax_calls.php?call=getlist&list=duration" ></a>'+
													'</td>'+
													'<td>'+
														'<a href="#" class="membership_'+ ms_c +'_editable plandata" data-html="true" data-type="text" data-inputclass="input-mini" data-name="membership_plan_price[' + ms_c + ']" data-original-title="Enter Price"></a>'+
													'</td>'+
													'<td>'+
														'<a href="#" class="membership_'+ ms_c +'_editable plandata" data-html="true" data-type="select" data-name="membership_plan_group[' + ms_c + ']" data-original-title="Select member group" data-source="ajax_calls.php?call=getlist&list=groups" ></a>'+
													'</td>'+
													'<td><button class="btn btn-warning membership_plan_delete">Delete Plan</button></td>'+
												'</tr>');
		
		$('.membership_'+ ms_c +'_editable').editable();
		e.preventDefault();
	})
	
	//		Delete profile field
	var msl_id = 0;
	$(".membership_plan_delete").live("click", function(e) {
		if (confirm('Are you sure you wish to delete this Membership Plan?')) {
			msl_id = $(this).parent().parent().attr('id').replace('membership_row_', '');
			$.ajax({
				url: "ajax_calls.php",
				type: 'POST',
				dataType: "json",
				data: 'call=adm_deleteMembership&msid=' + msl_id,
				success: function(reply) {
					$("#membership_row_" + msl_id).fadeOut(400, function() {
						$(this).remove();
					});
					
					showNotification(reply.message, 'Success', 'success');
				}
			});
		}
		e.preventDefault();
	})
	
	//		save profile fields
	$("#adm_savememberships").click(function(e){
		var button = $(this),
			origTxt = button.html();
			
		button.attr('disabled', 'disabled').html('Saving Plans..');
		
		var data,
			$elems = $('.plandata'),
			data = $elems.editable('getValue'); //get all values
			
		$.ajax({
			type: 'POST',
			url: 'ajax_calls.php?call=adm_savememberships', 
			data: data, 
			dataType: 'json'
		}).success(function(reply){
			if(reply.status){
				$elems.editable('markAsSaved');  //on success mark fields as saved
				showNotification(reply.message, 'Success', 'success');
			}else{
			   showNotification(reply.message, 'Error', 'error');
			}
			
			button.attr('disabled', false).html(origTxt);
		}).error(function(){
			showNotification('Error Saving Plans!', null, 'error');
		});
	
		e.preventDefault();
		return false;
	});
});