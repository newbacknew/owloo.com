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
            
                $first = true;
                
                $datos = '[{"nombre":"Afganist\u00e1n","code":"AF","name":"Afghanistan","id_country":"133"},{"nombre":"\u00c5land","code":"AX","name":"Aland Islands","id_country":"180"},{"nombre":"Albania","code":"AL","name":"Albania","id_country":"100"},{"nombre":"Alemania","code":"DE","name":"Germany","id_country":"18"},{"nombre":"Andorra","code":"AD","name":"Andorra","id_country":"146"},{"nombre":"Angola","code":"AO","name":"Angola","id_country":"154"},{"nombre":"Anguila","code":"AI","name":"Anguilla","id_country":"185"},{"nombre":"Antigua","code":"AG","name":"Antigua","id_country":"138"},{"nombre":"Arabia Saudita","code":"SA","name":"Saudi Arabia","id_country":"42"},{"nombre":"Argelia","code":"DZ","name":"Algeria","id_country":"99"},{"nombre":"Argentina","code":"AR","name":"Argentina","id_country":"4"},{"nombre":"Armenia","code":"AM","name":"Armenia","id_country":"136"},{"nombre":"Aruba","code":"AW","name":"Aruba","id_country":"131"},{"nombre":"Australia","code":"AU","name":"Australia","id_country":"5"},{"nombre":"Austria","code":"AT","name":"Austria","id_country":"6"},{"nombre":"Azerbaiy\u00e1n","code":"AZ","name":"Azerbaijan","id_country":"110"},{"nombre":"Bahamas","code":"BS","name":"The Bahamas","id_country":"92"},{"nombre":"Banglad\u00e9s","code":"BD","name":"Bangladesh","id_country":"64"},{"nombre":"Barbados","code":"BB","name":"Barbados","id_country":"109"},{"nombre":"Bar\u00e9in","code":"BH","name":"Bahrain","id_country":"86"},{"nombre":"B\u00e9lgica","code":"BE","name":"Belgium","id_country":"7"},{"nombre":"Belice","code":"BZ","name":"Belize","id_country":"149"},{"nombre":"Ben\u00edn","code":"BJ","name":"Benin","id_country":"145"},{"nombre":"Bermudas","code":"BM","name":"Bermuda","id_country":"134"},{"nombre":"Bielorrusia","code":"BY","name":"Belarus","id_country":"123"},{"nombre":"Bolivia","code":"BO","name":"Bolivia","id_country":"73"},{"nombre":"Bosnia y Herzegovina","code":"BA","name":"Bosnia and Herzegovina","id_country":"83"},{"nombre":"Botsuana","code":"BW","name":"Botswana","id_country":"115"},{"nombre":"Brasil","code":"BR","name":"Brazil","id_country":"8"},{"nombre":"Brun\u00e9i","code":"BN","name":"Brunei","id_country":"106"},{"nombre":"Bulgaria","code":"BG","name":"Bulgaria","id_country":"57"},{"nombre":"Burkina Faso","code":"BF","name":"Burkina Faso","id_country":"158"},{"nombre":"Burundi","code":"BI","name":"Burundi","id_country":"190"},{"nombre":"But\u00e1n","code":"BT","name":"Bhutan","id_country":"167"},{"nombre":"Cabo Verde","code":"CV","name":"Cape Verde","id_country":"187"},{"nombre":"Camboya","code":"KH","name":"Cambodia","id_country":"130"},{"nombre":"Camer\u00fan","code":"CM","name":"Cameroon","id_country":"114"},{"nombre":"Canad\u00e1","code":"CA","name":"Canada","id_country":"2"},{"nombre":"Chad","code":"TD","name":"Chad","id_country":"207"},{"nombre":"Chile","code":"CL","name":"Chile","id_country":"9"},{"nombre":"China","code":"CN","name":"China","id_country":"10"},{"nombre":"Chipre","code":"CY","name":"Cyprus","id_country":"69"},{"nombre":"Ciudad del Vaticano","code":"VA","name":"Vatican City","id_country":"206"},{"nombre":"Colombia","code":"CO","name":"Colombia","id_country":"11"},{"nombre":"Comoras","code":"KM","name":"Comoros","id_country":"200"},{"nombre":"Corea del Sur","code":"KR","name":"South Korea","id_country":"46"},{"nombre":"Costa Rica","code":"CR","name":"Costa Rica","id_country":"75"},{"nombre":"Croacia","code":"HR","name":"Croatia","id_country":"12"},{"nombre":"Dinamarca","code":"DK","name":"Denmark","id_country":"13"},{"nombre":"Dominica","code":"DM","name":"Dominica","id_country":"178"},{"nombre":"Ecuador","code":"EC","name":"Ecuador","id_country":"71"},{"nombre":"Egipto","code":"EG","name":"Egypt","id_country":"15"},{"nombre":"El Salvador","code":"SV","name":"El Salvador","id_country":"77"},{"nombre":"Emiratos \u00c1rabes Unidos","code":"AE","name":"United Arab Emirates","id_country":"53"},{"nombre":"Eritrea","code":"ER","name":"Eritrea","id_country":"194"},{"nombre":"Eslovaquia","code":"SK","name":"Slovakia","id_country":"61"},{"nombre":"Eslovenia","code":"SI","name":"Slovenia","id_country":"59"},{"nombre":"Espa\u00f1a","code":"ES","name":"Spain","id_country":"47"},{"nombre":"Estados Federados de Micronesia","code":"FM","name":"Federated States of Micronesia","id_country":"202"},{"nombre":"Estados Unidos","code":"US","name":"United States","id_country":"1"},{"nombre":"Estonia","code":"EE","name":"Estonia","id_country":"97"},{"nombre":"Etiop\u00eda","code":"ET","name":"Ethiopia","id_country":"116"},{"nombre":"Filipinas","code":"PH","name":"Philippines","id_country":"39"},{"nombre":"Finlandia","code":"FI","name":"Finland","id_country":"16"},{"nombre":"Fiyi","code":"FJ","name":"Fiji","id_country":"122"},{"nombre":"Francia","code":"FR","name":"France","id_country":"17"},{"nombre":"Gab\u00f3n","code":"GA","name":"Gabon","id_country":"162"},{"nombre":"Gambia","code":"GM","name":"The Gambia","id_country":"141"},{"nombre":"Georgia","code":"GE","name":"Georgia","id_country":"105"},{"nombre":"Ghana","code":"GH","name":"Ghana","id_country":"88"},{"nombre":"Gibraltar","code":"GI","name":"Gibraltar","id_country":"163"},{"nombre":"Granada","code":"GD","name":"Grenada","id_country":"147"},{"nombre":"Grecia","code":"GR","name":"Greece","id_country":"19"},{"nombre":"Groenlandia","code":"GL","name":"Greenland","id_country":"161"},{"nombre":"Guadalupe","code":"GP","name":"Guadeloupe","id_country":"108"},{"nombre":"Guam","code":"GU","name":"Guam","id_country":"125"},{"nombre":"Guatemala","code":"GT","name":"Guatemala","id_country":"74"},{"nombre":"Guayana Francesa","code":"GF","name":"French Guiana","id_country":"155"},{"nombre":"Guernsey","code":"GG","name":"Guernsey","id_country":"140"},{"nombre":"Guinea","code":"GN","name":"Guinea","id_country":"188"},{"nombre":"Guinea Ecuatorial","code":"GQ","name":"Equatorial Guinea","id_country":"198"},{"nombre":"Guyana","code":"GY","name":"Guyana","id_country":"135"},{"nombre":"Hait\u00ed","code":"HT","name":"Haiti","id_country":"129"},{"nombre":"Honduras","code":"HN","name":"Honduras","id_country":"78"},{"nombre":"Hong Kong","code":"HK","name":"Hong Kong","id_country":"20"},{"nombre":"Hungr\u00eda","code":"HU","name":"Hungary","id_country":"67"},{"nombre":"India","code":"IN","name":"India","id_country":"21"},{"nombre":"Indonesia","code":"ID","name":"Indonesia","id_country":"22"},{"nombre":"Irak","code":"IQ","name":"Iraq","id_country":"98"},{"nombre":"Irlanda","code":"IE","name":"Ireland","id_country":"23"},{"nombre":"Isla de Man","code":"IM","name":"Isle Of Man","id_country":"128"},{"nombre":"Islandia","code":"IS","name":"Iceland","id_country":"60"},{"nombre":"Islas Caim\u00e1n","code":"KY","name":"Cayman Islands","id_country":"144"},{"nombre":"Islas Feroe","code":"FO","name":"Faroe Islands","id_country":"142"},{"nombre":"Islas Malvinas","code":"FK","name":"Falkland Islands","id_country":"197"},{"nombre":"Islas Marianas del Norte","code":"MP","name":"Northern Mariana Islands","id_country":"173"},{"nombre":"Islas Marshall","code":"MH","name":"Marshall Islands","id_country":"205"},{"nombre":"Islas Salom\u00f3n","code":"SB","name":"Solomon Islands","id_country":"193"},{"nombre":"Islas Turcas y Caicos","code":"TC","name":"Turks and Caicos Islands","id_country":"177"},{"nombre":"Islas V\u00edrgenes Brit\u00e1nicas","code":"VG","name":"British Virgin Islands","id_country":"176"},{"nombre":"Islas V\u00edrgenes de los Estados Unidos","code":"VI","name":"US Virgin Islands","id_country":"148"},{"nombre":"Israel","code":"IL","name":"Israel","id_country":"24"},{"nombre":"Italia","code":"IT","name":"Italy","id_country":"25"},{"nombre":"Jamaica","code":"JM","name":"Jamaica","id_country":"70"},{"nombre":"Jap\u00f3n","code":"JP","name":"Japan","id_country":"26"},{"nombre":"Jersey","code":"JE","name":"Jersey","id_country":"124"},{"nombre":"Jordania","code":"JO","name":"Jordan","id_country":"27"},{"nombre":"Kazajist\u00e1n","code":"KZ","name":"Kazakhstan","id_country":"117"},{"nombre":"Kenia","code":"KE","name":"Kenya","id_country":"66"},{"nombre":"Kirguist\u00e1n","code":"KG","name":"Kyrgyzstan","id_country":"165"},{"nombre":"Kiribati","code":"KI","name":"Kiribati","id_country":"208"},{"nombre":"Kuwait","code":"KW","name":"Kuwait","id_country":"28"},{"nombre":"Laos","code":"LA","name":"Laos","id_country":"171"},{"nombre":"Lesoto","code":"LS","name":"Lesotho","id_country":"170"},{"nombre":"Letonia","code":"LV","name":"Latvia","id_country":"96"},{"nombre":"L\u00edbano","code":"LB","name":"Lebanon","id_country":"29"},{"nombre":"Libia","code":"LY","name":"Libya","id_country":"112"},{"nombre":"Liechtenstein","code":"LI","name":"Liechtenstein","id_country":"172"},{"nombre":"Lituania","code":"LT","name":"Lithuania","id_country":"62"},{"nombre":"Luxemburgo","code":"LU","name":"Luxembourg","id_country":"56"},{"nombre":"Macau","code":"MO","name":"Macau","id_country":"102"},{"nombre":"Madagascar","code":"MG","name":"Madagascar","id_country":"119"},{"nombre":"Malasia","code":"MY","name":"Malaysia","id_country":"30"},{"nombre":"Malaui","code":"MW","name":"Malawi","id_country":"137"},{"nombre":"Maldivas","code":"MV","name":"Maldives","id_country":"93"},{"nombre":"Mal\u00ed","code":"ML","name":"Mali","id_country":"153"},{"nombre":"Malta","code":"MT","name":"Malta","id_country":"91"},{"nombre":"Marruecos","code":"MA","name":"Morocco","id_country":"68"},{"nombre":"Martinica","code":"MQ","name":"Martinique","id_country":"113"},{"nombre":"Mauricio","code":"MU","name":"Mauritius","id_country":"89"},{"nombre":"Mauritania","code":"MR","name":"Mauritania","id_country":"179"},{"nombre":"Mayotte","code":"YT","name":"Mayotte","id_country":"186"},{"nombre":"M\u00e9xico","code":"MX","name":"Mexico","id_country":"31"},{"nombre":"Moldavia","code":"MD","name":"Moldova","id_country":"121"},{"nombre":"M\u00f3naco","code":"MC","name":"Monaco","id_country":"159"},{"nombre":"Mongolia","code":"MN","name":"Mongolia","id_country":"151"},{"nombre":"Montenegro","code":"ME","name":"Montenegro","id_country":"103"},{"nombre":"Mozambique","code":"MZ","name":"Mozambique","id_country":"152"},{"nombre":"Namibia","code":"NA","name":"Namibia","id_country":"118"},{"nombre":"Nauru","code":"NR","name":"Nauru","id_country":"211"},{"nombre":"Nepal","code":"NP","name":"Nepal","id_country":"101"},{"nombre":"Nicaragua","code":"NI","name":"Nicaragua","id_country":"79"},{"nombre":"N\u00edger","code":"NE","name":"Niger","id_country":"183"},{"nombre":"Nigeria","code":"NG","name":"Nigeria","id_country":"34"},{"nombre":"Noruega","code":"NO","name":"Norway","id_country":"35"},{"nombre":"Nueva Caledonia","code":"NC","name":"New Caledonia","id_country":"120"},{"nombre":"Nueva Zelanda","code":"NZ","name":"New Zealand","id_country":"33"},{"nombre":"Om\u00e1n","code":"OM","name":"Oman","id_country":"94"},{"nombre":"Pa\u00edses Bajos","code":"NL","name":"Netherlands","id_country":"32"},{"nombre":"Pakist\u00e1n","code":"PK","name":"Pakistan","id_country":"36"},{"nombre":"Palaos","code":"PW","name":"Palau","id_country":"201"},{"nombre":"Palestina","code":"PS","name":"Palestine","id_country":"84"},{"nombre":"Panam\u00e1","code":"PA","name":"Panama","id_country":"37"},{"nombre":"Pap\u00faa Nueva Guinea","code":"PG","name":"Papua New Guinea","id_country":"166"},{"nombre":"Paraguay","code":"PY","name":"Paraguay","id_country":"80"},{"nombre":"Per\u00fa","code":"PE","name":"Peru","id_country":"38"},{"nombre":"Polinesia Francesa","code":"PF","name":"French Polynesia","id_country":"132"},{"nombre":"Polonia","code":"PL","name":"Poland","id_country":"40"},{"nombre":"Portugal","code":"PT","name":"Portugal","id_country":"55"},{"nombre":"Puerto Rico","code":"PR","name":"Puerto Rico","id_country":"82"},{"nombre":"Qatar","code":"QA","name":"Qatar","id_country":"76"},{"nombre":"Reino Unido","code":"GB","name":"United Kingdom","id_country":"3"},{"nombre":"Rep\u00fablica Centroafricana","code":"CF","name":"Central African Republic","id_country":"203"},{"nombre":"Rep\u00fablica Checa","code":"CZ","name":"Czech Republic","id_country":"58"},{"nombre":"Rep\u00fablica de Macedonia","code":"MK","name":"Macedonia","id_country":"95"},{"nombre":"Rep\u00fablica del Congo","code":"CG","name":"Republic of the Congo","id_country":"184"},{"nombre":"Rep\u00fablica Democr\u00e1tica del Congo","code":"CD","name":"Democratic Republic of the Congo","id_country":"164"},{"nombre":"Rep\u00fablica Dominicana","code":"DO","name":"Dominican Republic","id_country":"14"},{"nombre":"Reuni\u00f3n","code":"RE","name":"R\u00e9union","id_country":"212"},{"nombre":"Ruanda","code":"RW","name":"Rwanda","id_country":"139"},{"nombre":"Rumania","code":"RO","name":"Romania","id_country":"72"},{"nombre":"Rusia","code":"RU","name":"Russia","id_country":"41"},{"nombre":"Samoa","code":"WS","name":"Samoa","id_country":"195"},{"nombre":"Samoa Americana","code":"AS","name":"American Samoa","id_country":"196"},{"nombre":"San Crist\u00f3bal y Nieves","code":"KN","name":"Saint Kitts and Nevis","id_country":"168"},{"nombre":"San Marino","code":"SM","name":"San Marino","id_country":"181"},{"nombre":"San Vicente y las Granadinas","code":"VC","name":"Saint Vincent and the Grenadines","id_country":"150"},{"nombre":"Santa Luc\u00eda","code":"LC","name":"St. Lucia","id_country":"143"},{"nombre":"Santo Tom\u00e9 y Pr\u00edncipe","code":"ST","name":"Sao Tome and Principe","id_country":"209"},{"nombre":"Senegal","code":"SN","name":"Senegal","id_country":"104"},{"nombre":"Serbia","code":"RS","name":"Serbia","id_country":"43"},{"nombre":"Seychelles","code":"SC","name":"Seychelles","id_country":"175"},{"nombre":"Sierra Leona","code":"SL","name":"Sierra Leone","id_country":"182"},{"nombre":"Singapur","code":"SG","name":"Singapore","id_country":"44"},{"nombre":"Somalia","code":"SO","name":"Somalia","id_country":"204"},{"nombre":"Sri Lanka","code":"LK","name":"Sri Lanka","id_country":"65"},{"nombre":"Suazilandia","code":"SZ","name":"Swaziland","id_country":"169"},{"nombre":"Sud\u00e1frica","code":"ZA","name":"South Africa","id_country":"45"},{"nombre":"Suecia","code":"SE","name":"Sweden","id_country":"48"},{"nombre":"Suiza","code":"CH","name":"Switzerland","id_country":"49"},{"nombre":"Surinam","code":"SR","name":"Suriname","id_country":"174"},{"nombre":"Tailandia","code":"TH","name":"Thailand","id_country":"51"},{"nombre":"Taiw\u00e1n","code":"TW","name":"Taiwan","id_country":"50"},{"nombre":"Tanzania","code":"TZ","name":"Tanzania","id_country":"111"},{"nombre":"Tayikist\u00e1n","code":"TJ","name":"Tajikistan","id_country":"191"},{"nombre":"Togo","code":"TG","name":"Togo","id_country":"160"},{"nombre":"Tonga","code":"TO","name":"Tonga","id_country":"199"},{"nombre":"Trinidad y Tobago","code":"TT","name":"Trinidad and Tobago","id_country":"63"},{"nombre":"T\u00fanez","code":"TN","name":"Tunisia","id_country":"85"},{"nombre":"Turkmenist\u00e1n","code":"TM","name":"Turkmenistan","id_country":"189"},{"nombre":"Turqu\u00eda","code":"TR","name":"Turkey","id_country":"52"},{"nombre":"Tuvalu","code":"TV","name":"Tuvalu","id_country":"210"},{"nombre":"Ucrania","code":"UA","name":"Ukraine","id_country":"90"},{"nombre":"Uganda","code":"UG","name":"Uganda","id_country":"107"},{"nombre":"Uruguay","code":"UY","name":"Uruguay","id_country":"81"},{"nombre":"Uzbekist\u00e1n","code":"UZ","name":"Uzbekistan","id_country":"156"},{"nombre":"Vanuatu","code":"VU","name":"Vanuatu","id_country":"192"},{"nombre":"Venezuela","code":"VE","name":"Venezuela","id_country":"54"},{"nombre":"Vietnam","code":"VN","name":"Vietnam","id_country":"87"},{"nombre":"Yemen","code":"YE","name":"Yemen","id_country":"126"},{"nombre":"Yibuti","code":"DJ","name":"Djibouti","id_country":"157"},{"nombre":"Zambia","code":"ZM","name":"Zambia","id_country":"127"}]';
                $datos = json_decode($datos, true);
                
                foreach($datos as $fila){ ?>
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
            
                $first = true;
                
                $datos = ' [{"nombre":"Afganist\u00e1n","code":"AF","name":"Afghanistan","id_country":"133"},{"nombre":"\u00c5land","code":"AX","name":"Aland Islands","id_country":"180"},{"nombre":"Albania","code":"AL","name":"Albania","id_country":"100"},{"nombre":"Alemania","code":"DE","name":"Germany","id_country":"18"},{"nombre":"Andorra","code":"AD","name":"Andorra","id_country":"146"},{"nombre":"Angola","code":"AO","name":"Angola","id_country":"154"},{"nombre":"Anguila","code":"AI","name":"Anguilla","id_country":"185"},{"nombre":"Antigua","code":"AG","name":"Antigua","id_country":"138"},{"nombre":"Arabia Saudita","code":"SA","name":"Saudi Arabia","id_country":"42"},{"nombre":"Argelia","code":"DZ","name":"Algeria","id_country":"99"},{"nombre":"Argentina","code":"AR","name":"Argentina","id_country":"4"},{"nombre":"Armenia","code":"AM","name":"Armenia","id_country":"136"},{"nombre":"Aruba","code":"AW","name":"Aruba","id_country":"131"},{"nombre":"Australia","code":"AU","name":"Australia","id_country":"5"},{"nombre":"Austria","code":"AT","name":"Austria","id_country":"6"},{"nombre":"Azerbaiy\u00e1n","code":"AZ","name":"Azerbaijan","id_country":"110"},{"nombre":"Bahamas","code":"BS","name":"The Bahamas","id_country":"92"},{"nombre":"Banglad\u00e9s","code":"BD","name":"Bangladesh","id_country":"64"},{"nombre":"Barbados","code":"BB","name":"Barbados","id_country":"109"},{"nombre":"Bar\u00e9in","code":"BH","name":"Bahrain","id_country":"86"},{"nombre":"B\u00e9lgica","code":"BE","name":"Belgium","id_country":"7"},{"nombre":"Belice","code":"BZ","name":"Belize","id_country":"149"},{"nombre":"Ben\u00edn","code":"BJ","name":"Benin","id_country":"145"},{"nombre":"Bermudas","code":"BM","name":"Bermuda","id_country":"134"},{"nombre":"Bielorrusia","code":"BY","name":"Belarus","id_country":"123"},{"nombre":"Bolivia","code":"BO","name":"Bolivia","id_country":"73"},{"nombre":"Bosnia y Herzegovina","code":"BA","name":"Bosnia and Herzegovina","id_country":"83"},{"nombre":"Botsuana","code":"BW","name":"Botswana","id_country":"115"},{"nombre":"Brasil","code":"BR","name":"Brazil","id_country":"8"},{"nombre":"Brun\u00e9i","code":"BN","name":"Brunei","id_country":"106"},{"nombre":"Bulgaria","code":"BG","name":"Bulgaria","id_country":"57"},{"nombre":"Burkina Faso","code":"BF","name":"Burkina Faso","id_country":"158"},{"nombre":"Burundi","code":"BI","name":"Burundi","id_country":"190"},{"nombre":"But\u00e1n","code":"BT","name":"Bhutan","id_country":"167"},{"nombre":"Cabo Verde","code":"CV","name":"Cape Verde","id_country":"187"},{"nombre":"Camboya","code":"KH","name":"Cambodia","id_country":"130"},{"nombre":"Camer\u00fan","code":"CM","name":"Cameroon","id_country":"114"},{"nombre":"Canad\u00e1","code":"CA","name":"Canada","id_country":"2"},{"nombre":"Chad","code":"TD","name":"Chad","id_country":"207"},{"nombre":"Chile","code":"CL","name":"Chile","id_country":"9"},{"nombre":"China","code":"CN","name":"China","id_country":"10"},{"nombre":"Chipre","code":"CY","name":"Cyprus","id_country":"69"},{"nombre":"Ciudad del Vaticano","code":"VA","name":"Vatican City","id_country":"206"},{"nombre":"Colombia","code":"CO","name":"Colombia","id_country":"11"},{"nombre":"Comoras","code":"KM","name":"Comoros","id_country":"200"},{"nombre":"Corea del Sur","code":"KR","name":"South Korea","id_country":"46"},{"nombre":"Costa Rica","code":"CR","name":"Costa Rica","id_country":"75"},{"nombre":"Croacia","code":"HR","name":"Croatia","id_country":"12"},{"nombre":"Dinamarca","code":"DK","name":"Denmark","id_country":"13"},{"nombre":"Dominica","code":"DM","name":"Dominica","id_country":"178"},{"nombre":"Ecuador","code":"EC","name":"Ecuador","id_country":"71"},{"nombre":"Egipto","code":"EG","name":"Egypt","id_country":"15"},{"nombre":"El Salvador","code":"SV","name":"El Salvador","id_country":"77"},{"nombre":"Emiratos \u00c1rabes Unidos","code":"AE","name":"United Arab Emirates","id_country":"53"},{"nombre":"Eritrea","code":"ER","name":"Eritrea","id_country":"194"},{"nombre":"Eslovaquia","code":"SK","name":"Slovakia","id_country":"61"},{"nombre":"Eslovenia","code":"SI","name":"Slovenia","id_country":"59"},{"nombre":"Espa\u00f1a","code":"ES","name":"Spain","id_country":"47"},{"nombre":"Estados Federados de Micronesia","code":"FM","name":"Federated States of Micronesia","id_country":"202"},{"nombre":"Estados Unidos","code":"US","name":"United States","id_country":"1"},{"nombre":"Estonia","code":"EE","name":"Estonia","id_country":"97"},{"nombre":"Etiop\u00eda","code":"ET","name":"Ethiopia","id_country":"116"},{"nombre":"Filipinas","code":"PH","name":"Philippines","id_country":"39"},{"nombre":"Finlandia","code":"FI","name":"Finland","id_country":"16"},{"nombre":"Fiyi","code":"FJ","name":"Fiji","id_country":"122"},{"nombre":"Francia","code":"FR","name":"France","id_country":"17"},{"nombre":"Gab\u00f3n","code":"GA","name":"Gabon","id_country":"162"},{"nombre":"Gambia","code":"GM","name":"The Gambia","id_country":"141"},{"nombre":"Georgia","code":"GE","name":"Georgia","id_country":"105"},{"nombre":"Ghana","code":"GH","name":"Ghana","id_country":"88"},{"nombre":"Gibraltar","code":"GI","name":"Gibraltar","id_country":"163"},{"nombre":"Granada","code":"GD","name":"Grenada","id_country":"147"},{"nombre":"Grecia","code":"GR","name":"Greece","id_country":"19"},{"nombre":"Groenlandia","code":"GL","name":"Greenland","id_country":"161"},{"nombre":"Guadalupe","code":"GP","name":"Guadeloupe","id_country":"108"},{"nombre":"Guam","code":"GU","name":"Guam","id_country":"125"},{"nombre":"Guatemala","code":"GT","name":"Guatemala","id_country":"74"},{"nombre":"Guayana Francesa","code":"GF","name":"French Guiana","id_country":"155"},{"nombre":"Guernsey","code":"GG","name":"Guernsey","id_country":"140"},{"nombre":"Guinea","code":"GN","name":"Guinea","id_country":"188"},{"nombre":"Guinea Ecuatorial","code":"GQ","name":"Equatorial Guinea","id_country":"198"},{"nombre":"Guyana","code":"GY","name":"Guyana","id_country":"135"},{"nombre":"Hait\u00ed","code":"HT","name":"Haiti","id_country":"129"},{"nombre":"Honduras","code":"HN","name":"Honduras","id_country":"78"},{"nombre":"Hong Kong","code":"HK","name":"Hong Kong","id_country":"20"},{"nombre":"Hungr\u00eda","code":"HU","name":"Hungary","id_country":"67"},{"nombre":"India","code":"IN","name":"India","id_country":"21"},{"nombre":"Indonesia","code":"ID","name":"Indonesia","id_country":"22"},{"nombre":"Irak","code":"IQ","name":"Iraq","id_country":"98"},{"nombre":"Irlanda","code":"IE","name":"Ireland","id_country":"23"},{"nombre":"Isla de Man","code":"IM","name":"Isle Of Man","id_country":"128"},{"nombre":"Islandia","code":"IS","name":"Iceland","id_country":"60"},{"nombre":"Islas Caim\u00e1n","code":"KY","name":"Cayman Islands","id_country":"144"},{"nombre":"Islas Feroe","code":"FO","name":"Faroe Islands","id_country":"142"},{"nombre":"Islas Malvinas","code":"FK","name":"Falkland Islands","id_country":"197"},{"nombre":"Islas Marianas del Norte","code":"MP","name":"Northern Mariana Islands","id_country":"173"},{"nombre":"Islas Marshall","code":"MH","name":"Marshall Islands","id_country":"205"},{"nombre":"Islas Salom\u00f3n","code":"SB","name":"Solomon Islands","id_country":"193"},{"nombre":"Islas Turcas y Caicos","code":"TC","name":"Turks and Caicos Islands","id_country":"177"},{"nombre":"Islas V\u00edrgenes Brit\u00e1nicas","code":"VG","name":"British Virgin Islands","id_country":"176"},{"nombre":"Islas V\u00edrgenes de los Estados Unidos","code":"VI","name":"US Virgin Islands","id_country":"148"},{"nombre":"Israel","code":"IL","name":"Israel","id_country":"24"},{"nombre":"Italia","code":"IT","name":"Italy","id_country":"25"},{"nombre":"Jamaica","code":"JM","name":"Jamaica","id_country":"70"},{"nombre":"Jap\u00f3n","code":"JP","name":"Japan","id_country":"26"},{"nombre":"Jersey","code":"JE","name":"Jersey","id_country":"124"},{"nombre":"Jordania","code":"JO","name":"Jordan","id_country":"27"},{"nombre":"Kazajist\u00e1n","code":"KZ","name":"Kazakhstan","id_country":"117"},{"nombre":"Kenia","code":"KE","name":"Kenya","id_country":"66"},{"nombre":"Kirguist\u00e1n","code":"KG","name":"Kyrgyzstan","id_country":"165"},{"nombre":"Kiribati","code":"KI","name":"Kiribati","id_country":"208"},{"nombre":"Kuwait","code":"KW","name":"Kuwait","id_country":"28"},{"nombre":"Laos","code":"LA","name":"Laos","id_country":"171"},{"nombre":"Lesoto","code":"LS","name":"Lesotho","id_country":"170"},{"nombre":"Letonia","code":"LV","name":"Latvia","id_country":"96"},{"nombre":"L\u00edbano","code":"LB","name":"Lebanon","id_country":"29"},{"nombre":"Libia","code":"LY","name":"Libya","id_country":"112"},{"nombre":"Liechtenstein","code":"LI","name":"Liechtenstein","id_country":"172"},{"nombre":"Lituania","code":"LT","name":"Lithuania","id_country":"62"},{"nombre":"Luxemburgo","code":"LU","name":"Luxembourg","id_country":"56"},{"nombre":"Macau","code":"MO","name":"Macau","id_country":"102"},{"nombre":"Madagascar","code":"MG","name":"Madagascar","id_country":"119"},{"nombre":"Malasia","code":"MY","name":"Malaysia","id_country":"30"},{"nombre":"Malaui","code":"MW","name":"Malawi","id_country":"137"},{"nombre":"Maldivas","code":"MV","name":"Maldives","id_country":"93"},{"nombre":"Mal\u00ed","code":"ML","name":"Mali","id_country":"153"},{"nombre":"Malta","code":"MT","name":"Malta","id_country":"91"},{"nombre":"Marruecos","code":"MA","name":"Morocco","id_country":"68"},{"nombre":"Martinica","code":"MQ","name":"Martinique","id_country":"113"},{"nombre":"Mauricio","code":"MU","name":"Mauritius","id_country":"89"},{"nombre":"Mauritania","code":"MR","name":"Mauritania","id_country":"179"},{"nombre":"Mayotte","code":"YT","name":"Mayotte","id_country":"186"},{"nombre":"M\u00e9xico","code":"MX","name":"Mexico","id_country":"31"},{"nombre":"Moldavia","code":"MD","name":"Moldova","id_country":"121"},{"nombre":"M\u00f3naco","code":"MC","name":"Monaco","id_country":"159"},{"nombre":"Mongolia","code":"MN","name":"Mongolia","id_country":"151"},{"nombre":"Montenegro","code":"ME","name":"Montenegro","id_country":"103"},{"nombre":"Mozambique","code":"MZ","name":"Mozambique","id_country":"152"},{"nombre":"Namibia","code":"NA","name":"Namibia","id_country":"118"},{"nombre":"Nauru","code":"NR","name":"Nauru","id_country":"211"},{"nombre":"Nepal","code":"NP","name":"Nepal","id_country":"101"},{"nombre":"Nicaragua","code":"NI","name":"Nicaragua","id_country":"79"},{"nombre":"N\u00edger","code":"NE","name":"Niger","id_country":"183"},{"nombre":"Nigeria","code":"NG","name":"Nigeria","id_country":"34"},{"nombre":"Noruega","code":"NO","name":"Norway","id_country":"35"},{"nombre":"Nueva Caledonia","code":"NC","name":"New Caledonia","id_country":"120"},{"nombre":"Nueva Zelanda","code":"NZ","name":"New Zealand","id_country":"33"},{"nombre":"Om\u00e1n","code":"OM","name":"Oman","id_country":"94"},{"nombre":"Pa\u00edses Bajos","code":"NL","name":"Netherlands","id_country":"32"},{"nombre":"Pakist\u00e1n","code":"PK","name":"Pakistan","id_country":"36"},{"nombre":"Palaos","code":"PW","name":"Palau","id_country":"201"},{"nombre":"Palestina","code":"PS","name":"Palestine","id_country":"84"},{"nombre":"Panam\u00e1","code":"PA","name":"Panama","id_country":"37"},{"nombre":"Pap\u00faa Nueva Guinea","code":"PG","name":"Papua New Guinea","id_country":"166"},{"nombre":"Paraguay","code":"PY","name":"Paraguay","id_country":"80"},{"nombre":"Per\u00fa","code":"PE","name":"Peru","id_country":"38"},{"nombre":"Polinesia Francesa","code":"PF","name":"French Polynesia","id_country":"132"},{"nombre":"Polonia","code":"PL","name":"Poland","id_country":"40"},{"nombre":"Portugal","code":"PT","name":"Portugal","id_country":"55"},{"nombre":"Puerto Rico","code":"PR","name":"Puerto Rico","id_country":"82"},{"nombre":"Qatar","code":"QA","name":"Qatar","id_country":"76"},{"nombre":"Reino Unido","code":"GB","name":"United Kingdom","id_country":"3"},{"nombre":"Rep\u00fablica Centroafricana","code":"CF","name":"Central African Republic","id_country":"203"},{"nombre":"Rep\u00fablica Checa","code":"CZ","name":"Czech Republic","id_country":"58"},{"nombre":"Rep\u00fablica de Macedonia","code":"MK","name":"Macedonia","id_country":"95"},{"nombre":"Rep\u00fablica del Congo","code":"CG","name":"Republic of the Congo","id_country":"184"},{"nombre":"Rep\u00fablica Democr\u00e1tica del Congo","code":"CD","name":"Democratic Republic of the Congo","id_country":"164"},{"nombre":"Rep\u00fablica Dominicana","code":"DO","name":"Dominican Republic","id_country":"14"},{"nombre":"Reuni\u00f3n","code":"RE","name":"R\u00e9union","id_country":"212"},{"nombre":"Ruanda","code":"RW","name":"Rwanda","id_country":"139"},{"nombre":"Rumania","code":"RO","name":"Romania","id_country":"72"},{"nombre":"Rusia","code":"RU","name":"Russia","id_country":"41"},{"nombre":"Samoa","code":"WS","name":"Samoa","id_country":"195"},{"nombre":"Samoa Americana","code":"AS","name":"American Samoa","id_country":"196"},{"nombre":"San Crist\u00f3bal y Nieves","code":"KN","name":"Saint Kitts and Nevis","id_country":"168"},{"nombre":"San Marino","code":"SM","name":"San Marino","id_country":"181"},{"nombre":"San Vicente y las Granadinas","code":"VC","name":"Saint Vincent and the Grenadines","id_country":"150"},{"nombre":"Santa Luc\u00eda","code":"LC","name":"St. Lucia","id_country":"143"},{"nombre":"Santo Tom\u00e9 y Pr\u00edncipe","code":"ST","name":"Sao Tome and Principe","id_country":"209"},{"nombre":"Senegal","code":"SN","name":"Senegal","id_country":"104"},{"nombre":"Serbia","code":"RS","name":"Serbia","id_country":"43"},{"nombre":"Seychelles","code":"SC","name":"Seychelles","id_country":"175"},{"nombre":"Sierra Leona","code":"SL","name":"Sierra Leone","id_country":"182"},{"nombre":"Singapur","code":"SG","name":"Singapore","id_country":"44"},{"nombre":"Somalia","code":"SO","name":"Somalia","id_country":"204"},{"nombre":"Sri Lanka","code":"LK","name":"Sri Lanka","id_country":"65"},{"nombre":"Suazilandia","code":"SZ","name":"Swaziland","id_country":"169"},{"nombre":"Sud\u00e1frica","code":"ZA","name":"South Africa","id_country":"45"},{"nombre":"Suecia","code":"SE","name":"Sweden","id_country":"48"},{"nombre":"Suiza","code":"CH","name":"Switzerland","id_country":"49"},{"nombre":"Surinam","code":"SR","name":"Suriname","id_country":"174"},{"nombre":"Tailandia","code":"TH","name":"Thailand","id_country":"51"},{"nombre":"Taiw\u00e1n","code":"TW","name":"Taiwan","id_country":"50"},{"nombre":"Tanzania","code":"TZ","name":"Tanzania","id_country":"111"},{"nombre":"Tayikist\u00e1n","code":"TJ","name":"Tajikistan","id_country":"191"},{"nombre":"Togo","code":"TG","name":"Togo","id_country":"160"},{"nombre":"Tonga","code":"TO","name":"Tonga","id_country":"199"},{"nombre":"Trinidad y Tobago","code":"TT","name":"Trinidad and Tobago","id_country":"63"},{"nombre":"T\u00fanez","code":"TN","name":"Tunisia","id_country":"85"},{"nombre":"Turkmenist\u00e1n","code":"TM","name":"Turkmenistan","id_country":"189"},{"nombre":"Turqu\u00eda","code":"TR","name":"Turkey","id_country":"52"},{"nombre":"Tuvalu","code":"TV","name":"Tuvalu","id_country":"210"},{"nombre":"Ucrania","code":"UA","name":"Ukraine","id_country":"90"},{"nombre":"Uganda","code":"UG","name":"Uganda","id_country":"107"},{"nombre":"Uruguay","code":"UY","name":"Uruguay","id_country":"81"},{"nombre":"Uzbekist\u00e1n","code":"UZ","name":"Uzbekistan","id_country":"156"},{"nombre":"Vanuatu","code":"VU","name":"Vanuatu","id_country":"192"},{"nombre":"Venezuela","code":"VE","name":"Venezuela","id_country":"54"},{"nombre":"Vietnam","code":"VN","name":"Vietnam","id_country":"87"},{"nombre":"Yemen","code":"YE","name":"Yemen","id_country":"126"},{"nombre":"Yibuti","code":"DJ","name":"Djibouti","id_country":"157"},{"nombre":"Zambia","code":"ZM","name":"Zambia","id_country":"127"}]';
                $datos = json_decode($datos, true);
                
                foreach($datos as $fila){ ?>
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