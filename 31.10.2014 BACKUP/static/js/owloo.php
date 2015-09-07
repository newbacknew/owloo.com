<?php require_once('../../owloo_config.php'); ?>
function formatNumber(number){
    var number = new String(number);
    var result = '';
    while( number.length > 3 ){
        result = '.' + number.substr(number.length - 3) + result;
        number = number.substring(0, number.length - 3);
    }
    return number + result;
}
var notificationInt = null;
function hideNotification_2() {
    clearInterval(notificationInt);
    $("#alertMessage").animate({
        top: -$("#alertMessage").height()
    }, 500)
}
function owloo_display_error_bar(type, msg){
    hideNotification_2();
 
    position = $("#alertMessage").position();
    $("#alertMessage").html('<div class="alert ' + type + '"><a class="close" data-dismiss="alert" href="#">&times;</a>' + msg + "</div>").stop(true, true).animate({
        top: 0
    }, 500, function(){
        if(true){
            notificationInt = setInterval(hideNotification_2, 10000);
        }
    })
}
function load_facebook_avatar(){
    $(".owloo_fb_page_avatar").each(function (e) {
        $(this).attr('src', $(this).attr('data'));
    })
}
$(document).ready(function(e) {
    $('#owloo_login').load('<?=URL_ROOT?>ajax/is_login.php');
    $("#owloo_login").on('click', '#logout', function() {
        $('#owloo_login .usernav, #owloo_login .dropdown-menu').removeClass('owloo_active');
        $('#owloo_ajax_loader').fadeIn(250);
        $.ajax({
            type: "POST",
            data: "logout=" + $("#logout").attr("name"),
            dataType: "json",
            success: function(a) {
                if (a.status) {
                    window.location.href = "";
                } else {
                    owloo_display_error_bar('', "No se ha podido cerrar la sesión, por favor actualice la página e inténtelo de nuevo.");
                    $('#owloo_ajax_loader').fadeOut(250);
                }
            }
        });
        return false;
    });
    $('#owloo_login').on('click', '.usernav', function(){
        $('#owloo_login .usernav, #owloo_login .dropdown-menu').removeClass('owloo_active');
        $(this).toggleClass('owloo_active');
        $(this).next('.dropdown-menu').toggleClass('owloo_active');
        return false;
    });
    
    $('.owloo_msj_popup').click(function(){
        $('#owloo_msj_popup .owloo_msj_popup_text').html($(this).attr('data'));
        $('#owloo_msj_popup').fadeIn(250);
    });
    $('#owloo_msj_popup .owloo_msj_popup_content').click(function(){
        return false;
    });
    $('#owloo_msj_popup, #owloo_msj_popup .owloo_close').click(function(){
        $('#owloo_msj_popup').fadeOut(250);
    });
    
    $('body').keydown(function(evt){
        var theEvent = evt || window.event;
        var key = theEvent.keyCode || theEvent.which;
        key2 = String.fromCharCode(key);
        if(key == 27) {
            $('#owloo_msj_popup, .owloo_tw_download_content').fadeOut(250);
        }
    });
    
    $('html').click(function(){
        $('#owloo_login .usernav, #owloo_login .dropdown-menu').removeClass('owloo_active');
    });
    
    $('#owloo_tw_download_btn').click(function(){
        $('.owloo_tw_download_content').load('<?=URL_ROOT?>ranking_twitter/ajax/generate_tw_download.php');
        $('.owloo_tw_download_content').fadeIn(250);
        return false;
    });
    
    $('#owloo_tw_download_sl_btn').click(function(){
        $('.owloo_tw_download_content .owloo_tw_download_sl').load('<?=URL_ROOT?>ranking_twitter/ajax/generate_tw_download_sl.php');
        $('.owloo_tw_download_content').fadeIn(250);
        return false;
    });
    
    $('.owloo_tw_download_content').on('click', '.owloo_close', function(){
        $('.owloo_tw_download_content').fadeOut(250);
        return false;
    });
    
    $("#alertMessage").on("click", function() {
        hideNotification_2()
    });
    
    $('#owloo_favorite').click(function(){
        var type = $(this).attr('type');
        $.post('<?=URL_ROOT?>ajax/check_favorite.php', {type: $(this).attr('type'), id_element: $(this).attr('element')}, function(res){
            if(res == 0){
                owloo_display_error_bar('', 'Inicia sesión para monitorear esta página');
            }else if(res == 2){
                owloo_display_error_bar('', 'Ahora ya estás monitoreando esta página');
                $('#owloo_favorite').addClass('owloo_active');
            }else if(res == 1 || res == 3 || res == 5){
                owloo_display_error_bar('alert-error', 'Ha ocurrido un error, Favor inténtelo más tarde');
            }else if(res == 4){
                owloo_display_error_bar('', 'Ahora ya no estás monitoreando esta página.');
                $('#owloo_favorite').removeClass('owloo_active');
            }else if(res == 6){
                owloo_display_error_bar('', 'Sólo puedes monitorear hasta 6 ' + (type=='country'?'países':(type=='page'?'páginas de Facebook':'perfiles de Twitter')));
                $('#owloo_favorite').removeClass('owloo_active');
            }
        });
    });
    
    if($('.owloo_favorite_country_ajax')[0]){
        $.post('<?=URL_ROOT?>ajax/is_favorite.php', {type: $('#owloo_favorite').attr('type'), id_element: $('#owloo_favorite').attr('element')}, function(res){
            if(res == 1){
                $('#owloo_favorite').addClass('owloo_active');
            }
        });
    };
    
    if($('#owloo_user_count')[0]){
        $.post('<?=URL_ROOT?>user_count', function(res){
            $('#owloo_user_count').html(res.toString());
        });
    };
    if($('.owloo_wrap_tw_last_add_content')[0]){
        $.post('<?=URL_ROOT?>ranking_twitter/list_last_add_acounts.php', function(res){
            $('.owloo_wrap_tw_last_add_content').html(res);
        });
    };
    
    if($('.owloo_tw_search_submit')[0]){
        $('.owloo_tw_search_submit').click(function(){
           $('#owloo_tw_search_form').submit();
        });
    };
    
    if($('#owloo_fb_search_form')[0]){
        $('.owloo_fb_search_submit').click(function(){
           $('#owloo_fb_search_form').submit();
        });
        $('#owloo_fb_search_form').submit(function(){
            if($('#owloo_fb_search_username').val() != ''){
                window.location = '<?=URL_ROOT?>facebook-stats/pages/' + $('#owloo_fb_search_username').val().replace('http://', '').replace('https://', '');
            }else{
                $('#owloo_fb_search_username').focus();
            }
            return false;
        });
    };
    
    // hide #back-top first
    $("#back-top").hide();
    
    // fade in #back-top
    /*$(function () {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('#back-top').fadeIn();
            } else {
                $('#back-top').fadeOut();
            }
        });

        // scroll body to 0px on click
        $('#back-top a').click(function () {
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
    });*/
    
    /*** Social plugins ***/
    if($('.owloo_social_plugin')[0]){
        window.setTimeout(function(){ 
            $('.owloo_social_plugin .owloo_social_plugin_content').load('<?=URL_ROOT?>ajax/social_plugins/'+$('.owloo_social_plugin').attr('data')); 
        }, 2000);
    }
    /*** END - Social plugins ***/
    /*** Facebook pages ***/
    if($('.owloo_fb_page_avatar')[0]){
        window.setTimeout(function(){ load_facebook_avatar() }, 1000);
    }
    /*** END - Facebook pages ***/
});

/***** Pager *****/
function load_page(page, from_page, from_url, country){
	location.hash = "stats-"+page;
    $('#owloo_ajax_loader').fadeIn(250);
    if(!from_url)
        $('html, body').animate({scrollTop:((from_page=='hispanic_page'||from_page=='global_page')?494:240)},500);
    $('#owloo_ranking').load('<?=URL_ROOT?>ajax/ranking_page.php', 
       {page:page,from_page:from_page,country:country}, 
       function(){$('#owloo_ajax_loader').fadeOut(500);}
    );
}
function next(var_pag, element_count, element_per_page){
    var_pag = parseInt(var_pag);
    var_total = parseInt(element_count);
    element_per_page = parseInt(element_per_page);
    var_total_pag = Math.ceil(var_total/element_per_page);
    for(cp=0; cp<=var_total_pag; cp++){
        if(cp==var_pag){
            $("#owloo_nav_"+cp).addClass("owloo_pactive");
        }
        else{
            $("#owloo_nav_"+cp).removeClass("owloo_pactive");
        }
    }
    if(var_pag < 7){
        for(cpc=0; cpc<=var_total_pag; cpc++){
            if(cpc <= 7){
                $("#owloo_nav_"+cpc).removeClass("owloo_inactive");
            } else {
                $("#owloo_nav_"+cpc).addClass("owloo_inactive");
            }
        }
        if(var_total_pag > 7){
            $("#owloo_nav_"+var_total_pag).removeClass("owloo_inactive");
        }
        $("#owloo_no-pag-ini,#owloo_no-pag-fin").removeClass("owloo_inactive");
        $("#owloo_no-pag-ini").addClass("owloo_inactive");
    }
    else if(var_pag > (var_total_pag - 6)){
        for(cpc=0; cpc<=var_total_pag; cpc++){
            if(cpc > var_total_pag - 7){
                $("#owloo_nav_"+cpc).removeClass("owloo_inactive");
            } else {
                $("#owloo_nav_"+cpc).addClass("owloo_inactive");
            }
        }
        if(var_total_pag > 1){
            $("#owloo_nav_1").removeClass("owloo_inactive");
        }
        $("#owloo_no-pag-ini,#owloo_no-pag-fin").removeClass("owloo_inactive");
        $("#owloo_no-pag-fin").addClass("owloo_inactive");
    }
    else{
        for(cpc=0; cpc<=var_total_pag; cpc++){
            if(cpc > (var_pag - 3) && cpc < (var_pag + 3)){
                $("#owloo_nav_"+cpc).removeClass("owloo_inactive");
            } else {
                $("#owloo_nav_"+cpc).addClass("owloo_inactive");
            }
        }
        $("#owloo_nav_1").removeClass("owloo_inactive");
        $("#owloo_nav_"+var_total_pag).removeClass("owloo_inactive");
        $("#owloo_no-pag-ini,#owloo_no-pag-fin").removeClass("owloo_inactive");
    }
}
/***** END Pager *****/

/***** WS-COMBO *****/
<?php require_once('ws-combo.js'); ?>
$(document).ready(function(){
    if($('#owloo_select_country')[0]){
        $('#owloo_select_country').wscombo({
            id:'spOwloo_country_ws_hispanic',
            name:'spOwloo_country_ws_form',
            img:'<?=URL_IMAGES?>country_flags_16x16.png',
            reset:' ',
            combo:' ',
            text:'Datos de Facebook por país',
            width:215,
            maxHeight:280,
            maxLength:16,
            validate:/^[^0-9]+$/,
            onselect:function(i){ window.location = i.v; }, // onselect handler
            items:[
            <?php
                $sql =   "SELECT nombre, code, name, id_country
                            FROM country
                            ORDER BY 1 ASC;
                            ";
                $res = mysql_query($sql) or die(mysql_error());
                $first = true;
                while($fila = mysql_fetch_assoc($res)){ ?>
                    <?=(!$first)?',':$first=false?>{v:'<?=URL_ROOT?>facebook-stats/<?=convert_to_url_string($fila['name'])?>/',h:'<?=$fila["nombre"]?>',i:{x:0,y:<?=(-16 * ($fila['id_country']-1))?>}}
                <?php } ?>
            ]
        });
    }
    if($('#owloo_select_country_fb')[0]){
        $('#owloo_select_country_fb').wscombo({
            id:'spOwloo_select_country_fb',
            name:'spOwloo_country_ws_form',
            img:'<?=URL_IMAGES?>country_flags_16x16.png',
            reset:' ',
            combo:' ',
            text:'Buscar páginas por país',
            width:215,
            maxHeight:280,
            maxLength:16,
            validate:/^[^0-9]+$/,
            onselect:function(i){ window.location = i.v; }, // onselect handler
            items:[
            <?php
                $sql =   "SELECT nombre, code, name, id_country
                            FROM country
                            ORDER BY 1 ASC;
                            ";
                $res = mysql_query($sql) or die(mysql_error());
                $first = true;
                while($fila = mysql_fetch_assoc($res)){ ?>
                    <?=(!$first)?',':$first=false?>{v:'<?=URL_ROOT?>facebook-stats/pages/country/<?=convert_to_url_string($fila['code'])?>/',h:'<?=$fila["nombre"]?>',i:{x:0,y:<?=(-16 * ($fila['id_country']-1))?>}}
                <?php } ?>
            ]
        });
    }
});
/***** END WS-COMBO *****/

/***** TOOLTIP *****/
<?php require_once('tooltipster.js'); ?>
$(document).ready(function() {
    $('.owloo_tooltip').tooltipster({
        position: 'top-left',
        tooltipTheme: '.tooltip-questions'
    });
    $('.owloo_tooltip_right').tooltipster({
        position: 'top-right',
        tooltipTheme: '.tooltip-questions-right'
    });
    $('.owloo_tooltip_center').tooltipster({
        position: 'top-right',
        tooltipTheme: '.tooltip-questions-center'
    });
    $('.owloo_tooltip_charts').tooltipster({
        position: 'top-left',
        tooltipTheme: '.tooltip-chart-relationship'
    });
    $('.owloo_tooltip_charts_center').tooltipster({
        position: 'top-right',
        tooltipTheme: '.tooltip-chart-relationship-center'
    });
    $('.owloo_tooltip_audience').tooltipster({
        position: 'top-left',
        tooltipTheme: '.tooltip-chart-audience'
    });
});
/***** END TOOLTIP *****/