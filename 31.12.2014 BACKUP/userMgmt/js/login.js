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
		showNotification("Por favor, ingrese su nombre de usuario y contraseña", 'Autenticación fallida');
		return false;
	}
	
	var url_from = '../';
	if($('#url_from').val() != '')
		url_from = $('#url_from').val();
	
	hideNotification();
	//setLoading('Verificando... ',true);
	$('#owloo_ajax_loader').fadeIn(100);
	$.ajax({
		url: 'login.php',
		type: 'POST',
		data: $('#form_login').serialize(),
		dataType: 'json',
		success: function(data) {
			//removeLoading();
			$('#owloo_ajax_loader').fadeOut(100);
			if(!data.status){
				showNotification(data.message, 'Error', 'error');
				return false;
			}
			hideNotification();
			$('body').fadeOut(600, function(){
				window.location.href=url_from;
			});
			return false;
		}
	});
	
	return false;
	e.preventDefault();
});

$('#btn_signup').click(function(e){
	hideNotification();
	
	//setLoading('Procesando... ',true);
	$('#owloo_ajax_loader').fadeIn(100);
	$.ajax({
		url: 'signup.php',
		type: 'POST',
		data: $('#formSignup').serialize(),
		dataType: 'json',
		success: function(data){
			//removeLoading();
			$('#owloo_ajax_loader').fadeOut(100);
			if(!data){
				showNotification("Se produjo un error al intentar crear su cuenta.", 'Error', 'error');
				return false;
			}else{
				if(data.status == 'activado'){
					window.location = 'login.php?active='+data.message;
					return;
				}
				if(data.status){
					$("#formSignup").get(0).reset();
					showNotification(data.message, 'Éxito', 'success');
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
	//setLoading("Procesando...", true);
	$('#owloo_ajax_loader').fadeIn(100);
	$.ajax({
		url: "ajax_calls.php",
		type: "POST",
		data: $("#formforgotpass").serialize()+"&call=forgotpass",
		dataType: "json",
		success: function(reply) {
			//removeLoading();
			$('#owloo_ajax_loader').fadeOut(100);
			if(reply.status) {
				$("#formforgotpass").get(0).reset()
			}
			showNotification(reply.message, 'Éxito', reply.msgtype)
			return false
		}
	})
	
	e.preventDefault();
});