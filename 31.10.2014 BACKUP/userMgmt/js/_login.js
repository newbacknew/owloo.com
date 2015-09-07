/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.3.1 																		 *
 *****************************************************************************************/
 $('#btn_login').click(function(e){
	if($("#formSignup input[name=login_username], #formSignup input[name=login_password]").val() == ''){
		showNotification("Please type both a username and password", 'Login Failed');
		return false;
	}
	
	hideNotification();
	setLoading('Verifying.. ',true);
	$.ajax({
		url: 'login.php',
		type: 'POST',
		data: $('#form_login').serialize(),
		dataType: 'json',
		success: function(data) {
			removeLoading();
			if(!data.status){
				showNotification(data.message, 'Error', 'error');
				return false;
			}
			hideNotification();
			$('body').fadeOut(600, function(){
				window.location.href='index.php';
			});
			return false;
		}
	});
	
	return false;
	e.preventDefault();
});

$('#btn_signup').click(function(e){
	hideNotification();
	
	setLoading('Processing.. ',true);
	$.ajax({
		url: 'signup.php',
		type: 'POST',
		data: $('#formSignup').serialize(),
		dataType: 'json',
		success: function(data){
			removeLoading();
			if(!data){
				showNotification("An error occured while trying to create your account.", 'Error', 'error');
				return false;
			}else{
				if(data.status){
					$("#formSignup").get(0).reset();
					showNotification(data.message, 'Success', 'success');
				}else{
					if(data.reload){
						Recaptcha.reload();
					}
					showNotification(data.message, 'Error', 'error');
				}
			}
			
			return false;
		}
	});
	return false;
	e.preventDefault();
});

/*
	######################
	## Forgot Passowrd  ##
	######################
*/
$("#formforgotpass button[type=submit]").live("click", function(e){
	hideNotification();
	setLoading("Processing..", true);
	$.ajax({
		url: "ajax_calls.php",
		type: "POST",
		data: $("#formforgotpass").serialize()+"&call=forgotpass",
		dataType: "json",
		success: function(reply) {
			removeLoading();
			if(reply.status) {
				$("#formforgotpass").get(0).reset()
			}
			showNotification(reply.message, 'Success', reply.msgtype)
			return false
		}
	})
	
	e.preventDefault();
});