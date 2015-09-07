/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.3.1 																		 *
 *****************************************************************************************/
/*
	################################
	##	Private Message related   ##
	################################
*/
var total_messages = 0;
var cur_pm_space_left = 0;
var loadingPM = false;
var pmtable = null;
var send_data = '';
var deletingpm = false;

$(document).ready(function() {
	$("#checkAll").live("click", function() {
		var a = this.checked;
		var b = this.id;
		$("#InboxPM table tbody tr td:first-child input:checkbox").each(function() {
			this.checked = a;
			if (this.checked) {
				$(this).attr("checked", true);
				$("label[for=" + $(this).attr("id") + "]").addClass("checked ")
				} else {
				$(this).attr("checked", false);
				$("label[for=" + $(this).attr("id") + "]").removeClass("checked ")
				}
		})
	});
	
	$(".ReadSelectedPM").live("click", function(e){
		if(!loadingPM){
			loadingPM = true;
			var a = $(this).attr('name');
			var boton = $(this);
			var origen = '';
			if($(this).hasClass('ReadSelectedPM_dav'))
				origen = '&origen=profile';
			
			setLoading("Recibiendo mensaje...", false);
			
			if ($("#ReadPM").is(":visible")) {
				$("#ReadPM").hide()
			}
			
			$.ajax({
				url: "ajax_calls.php",
				type: "POST",
				data: "call=readpm" + origen + "&id=" + a,
				dataType: "json",
				success: function(data) {
					loadingPM = false;
					removeLoading();
					if (data.status) {
						$("#read_pm_username").val(data.sender);
						$("#read_pm_subject").val(data.subject);
						$("#read_pm_message").html(data.message);
						$("#ReadPM input[name=id]").val(data.pmid);
						$("#replypm").attr("name", data.sender);
						$("#ReadPM").fadeIn(200);
						if(data.origen == 'profile')
							$("#ReadPM .DeleteSelectedPM").addClass('DeleteSelectedPM_dav');
						if (data.unread) {
							$("#InboxPM table tr td a[name='" + a + "']").parents("tr").children("td").children("div.status").removeClass("unread").addClass("read");
							if (total_messages - 1 < 1) {
								if ($("#account_info span.notification").is(":visible")) {
									$("#account_info span.notification").fadeOut()
									}
								$(".nav.pull-right span.badge-pm").html(parseInt($(".nav.pull-right span.badge-pm").html()) - 1);
								total_messages = 0
							} else {
								$(".nav.pull-right span.badge-pm").html(parseInt($(".nav.pull-right span.badge-pm").html()) - 1);
								total_messages--
							}
							if(!$('#pm_new_count_item_'+a + ' > a').hasClass('pm_new_count_read')){
								$('#pm_new_count_item_'+a + ' > a').addClass('pm_new_count_read');
								$('#pm_new_count_item_' + a).removeClass('pm_new_count_admin');
								$("#pm_new_count").html(parseInt($("#pm_new_count").html()) - 1);
							}
						}
						if(boton.hasClass('ReadSelectedPM_dav') && !boton.hasClass('pm_new_count_read')){
							boton.addClass('pm_new_count_read');
							$('#pm_new_count_item_' + a).removeClass('pm_new_count_admin');
							$("#pm_new_count").html(parseInt($("#pm_new_count").html()) - 1);
							$(".nav.pull-right span.badge-pm").html(parseInt($(".nav.pull-right span.badge-pm").html()) - 1);
						}
					} else {
						showNotification("No se pudo recuperar el mensaje...", 'Error', 'error')
						}
				}
			})
		}
	})

	$(".DeleteSelectedPM").live("click", function(e){
		var a = '';
		var c = false;
		var boton = $(this);
		
		if($(this).parent().hasClass('modal-footer')){
			send_data = $("#ReadPM form").serialize()+"&call=deletepm";
			a = $("#ReadPM form input[name=id]").val();
			c = true;
		}else{
			a = $(this).attr('name');
			send_data = "call=deletepm&id="+ a +"&deletepm="+$("#ReadPM form input[name=deletepm]").val();
		}
		
		var b = $("#InboxPM table tr td a[name=" + a + "]");	
		
		if(!deletingpm){
			deletingpm = true;
			setLoading('Eliminando mensaje...', false);
			hideNotification();
			$.ajax({
				url: "ajax_calls.php",
				type: "POST",
				data: send_data,
				dataType: "json",
				success: function(data) {
					if(!data.status){
						showNotification("No se ha podido borrar el mensaje. Tal vez ya se ha eliminado?", 'Error', 'error')
					}else{
						if(!boton.hasClass('DeleteSelectedPM_dav')){
							pmPos = pmtable.fnGetPosition(b.parents("td")[0]);
							pmtable.fnDeleteRow(pmPos[0]);
						}
						
						if (b.parents("tr").children("td").children(".status").hasClass("unread")) {
							total_messages--;
							$(".nav.pull-right span.badge-pm").html(parseInt($(".nav.pull-right span.badge-pm").html()) - 1)
							if((parseInt($(".nav.pull-right span.badge-pm").html()) - 1) < 1){
								$(".nav.pull-right span.badge-pm").html('0').fadeOut(300);
							}
						}
						
						if(c){
							if ($("#ReadPM").is(":visible")) {
								$("#ReadPM").fadeOut(400)
							}
						}
						cur_pm_space_left++
						$("#storagecount div span").html("Inbox Limit: " + (data.pm_max - cur_pm_space_left) + " of " + data.pm_max);
						$("#storagecount div").css('width', ((data.pm_max - cur_pm_space_left) / data.pm_max * 100)+'%');
						showNotification("Mensaje eliminado.", 'Éxito', 'success');
						
						$('#pm_new_count_item_' + a).remove();
						
						load_messages_dav();
					}
					removeLoading();
					deletingpm = false;
					return false
				}
			})
		}
		
		e.preventDefault();
	});

	$("#owloo_login").on('click', '.CreateNewPM', function(a) {
		$("#NewPM form").get(0).reset();
		$("#NewPM").fadeIn(200);
		return false;
		a.preventDefault()
		});
	$("#closepm").click(function(a) {
		$("#NewPM").fadeOut(200);
		return false;
		a.preventDefault()
		});
	$("#close_InboxPM").click(function() {
		$("#InboxPM").fadeOut(200)
		});
	$("#CloseSelectedPM").click(function() {
		$("#ReadPM").fadeOut(200)
		});
	$("#replypm").click(function() {
		$("#NewPM form").get(0).reset();
		$("#NewPM input[name='sendto']").val($(this).attr("name"));
		$("#NewPM input[name='subject']").val("RE: " + $("#read_pm_subject").val());
		$("#ReadPM").fadeOut(200);
		$("#NewPM").fadeIn(200);
		return false;
		e.preventDefault()
		});
	$("#owloo_login").on('click', '.showpmlist', function(a) {
		setLoading("Descargando mensajes... ", false);
		$("#ReadPM").fadeOut(200);
		pmtable.fnClearTable();
		$.ajax({
			url: "ajax_calls.php",
			type: "POST",
			data: "call=pmlist",
			dataType: "json",
			success: function(a) {
				if(a != ''){
					if(a.messages.length){
						$.each(a.messages, function() {
							$("#InboxPM table").dataTable().fnAddData([this.check, this.sender, this.date, this.controls])
						});
					}
					
					if (a.pm_space_left < 20) {
						showNotification("Usted sólo puede guardar un adicional " + a.pm_space_left + " mensajes. Si llega al límite, los mensajes se eliminan automáticamente.")
					}
					cur_pm_space_left = a.pm_space_left;
					$("#storagecount div span").html("Inbox Limit: " + (a.pm_max - a.pm_space_left) + " of " + a.pm_max);
					$("#storagecount div").css('width', ((a.pm_max - a.pm_space_left) / a.pm_max * 100)+'%');
					$("#InboxPM").fadeIn(200);
				}
				removeLoading()
			}
		});
		a.preventDefault()
	});
	$("#pm_new_count").click(function(a) {
		setLoading("Descargando mensajes... ", false);
		$("#ReadPM").fadeOut(200);
		pmtable.fnClearTable();
		$.ajax({
			url: "ajax_calls.php",
			type: "POST",
			data: "call=pmlist",
			dataType: "json",
			success: function(a) {
				if(a != ''){
					if(a.messages.length){
						$.each(a.messages, function() {
							$("#InboxPM table").dataTable().fnAddData([this.check, this.sender, this.date, this.controls])
						});
					}
					
					if (a.pm_space_left < 20) {
						showNotification("Usted sólo puede guardar un adicional " + a.pm_space_left + " mensajes. Si llega al límite, los mensajes se eliminan automáticamente.")
					}
					cur_pm_space_left = a.pm_space_left;
					$("#storagecount div span").html("Inbox Limit: " + (a.pm_max - a.pm_space_left) + " of " + a.pm_max);
					$("#storagecount div").css('width', ((a.pm_max - a.pm_space_left) / a.pm_max * 100)+'%');
					$("#InboxPM").fadeIn(200);
				}
				removeLoading()
			}
		});
		a.preventDefault()
	});
	$("#sendpm").click(function(a) {
		hideNotification();
		setLoading("Enviando mensaje.. ", true);
		$.ajax({
			url: "ajax_calls.php",
			type: "POST",
			data: $("#NewPM form").serialize(),
			dataType: "json",
			success: function(a) {
				removeLoading();
				if (!a.status) {
					showNotification(a.message, 'Error', 'error');
					return false
				} else {
					$("#NewPM").fadeOut();
					showNotification(a.message, 'Éxito', 'success');
					return false
				}
			}
		});
		a.preventDefault()
		});
	$("#deleteAllSelected").click(function(a) {
		hideNotification();
		setLoading("Eliminando todos los mensajes seleccionados...", true);
		var b = "";
		var c = new Array;
		var id_dav = new Array();
		var cont_id_dav = 0;
		$.each($("#InboxPM table tbody input[type=checkbox]:checked"), function() {
			if (b.length < 1) {
				b += $(this).attr("id").replace("pm_check_", "")
				} else {
				b += "," + $(this).attr("id").replace("pm_check_", "")
				}
				if(cont_id_dav < 5)
					id_dav[cont_id_dav++] = $(this).attr("id").replace("pm_check_", "");
			c.push($(this).parent())
			});
		if (b.length > 0) {
			$.ajax({
				url: "ajax_calls.php",
				type: "POST",
				data: "call=masspmdelete&id=" + b,
				dataType: "json",
				success: function(a) {
					removeLoading();
					if (!a.status) {
						showNotification("El mensaje no fue eliminado.", 'Error', 'error')
					} else {
						del_count = 0;
						$.each(c, function() {
							pmPos = pmtable.fnGetPosition($(this)[0]);
							pmtable.fnDeleteRow(pmPos[0]);
							cur_pm_space_left++
							del_count++;
						});
						
						total_messages = total_messages-del_count;
						/*$(".nav.pull-right span.badge-pm").html(parseInt($(".nav.pull-right span.badge-pm").html()) - del_count)
						if((parseInt($(".nav.pull-right span.badge-pm").html()) - 1) < 1){
							$(".nav.pull-right span.badge-pm").html('0').fadeOut(300);
						}*/
						
						for(var i = 0; i < cont_id_dav; i++){
							if($('#pm_new_count_item_'+ id_dav[i])){
								$('#pm_new_count_item_'+id_dav[i]).remove();
							}
						}
						
						RunCheck();
						
						showNotification("El mensaje seleccionado fue eliminado.", 'Éxito', 'success')
					}
					$("#storagecount div span").html("Inbox Limit: " + (a.pm_max - cur_pm_space_left) + " of " + a.pm_max);
					$("#storagecount div").css('width', ((a.pm_max - cur_pm_space_left) / a.pm_max * 100)+'%');
					$('#checkAll').attr("checked", false)
					}
			})
			} else {
			removeLoading();
			showNotification("Mensaje no seleccionado.")
			}
		return false;
		a.preventDefault()
	});
});

function unix_time(){
	/* Not used yet */
	var thedate = new Date; // Generic JS date object
	var unixtime_ms = thedate.getTime(); // Returns milliseconds since the epoch
	return parseInt(unixtime_ms / 1000);
}
function nl2br(a) {
    return a.replace(/\n/gm, "<br />")
    }
function profile_newpm(a) {
    $("#NewPM form").get(0).reset();
    $("#NewPM input[name='sendto']").val(a);
    $("#NewPM").fadeIn(200);
    return false
}

if ($(".draggable").length > 0) {
		$(".draggable").draggable({
			handle: ".modal-header"
		})
	}

if ($("#InboxPM").length > 0) {
	pmtable = $("#InboxPM table").dataTable({
					sDom: 'fCl<"clear">rtip',
					aaSorting: [],
					iDisplayLength: 5,
					aLengthMenu: [5, 10, 20],
					aoColumns: [{
						bSortable: false
					}, null, {
						bSortable: false
					}, {
						bSortable: false
					}]
				})
}