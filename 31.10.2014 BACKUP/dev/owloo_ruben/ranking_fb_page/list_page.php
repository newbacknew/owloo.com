<?php
    $page_total_count = ($_GET['ranking']=='hispanic'?get_fb_page_total_rows_count(' hispanic = 1 AND '):get_fb_page_total_rows_count());
    
    $n_a = '<span class="owloo_not_change_audition"><em>n/a</em></span>';
    
?>
<div id="owloo_ranking">
<?php

    $datos = '[{"id_page":"1012","username":"facebookmexico","name":"Facebook","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xpa1\/v\/t1.0-1\/p50x50\/1385735_579237715465351_654134565_n.png?oh=3d811f5166d6757d40c9d94a556ec37a&oe=54941D68&__gda__=1418726498_53a149facbb94426eb89906fcad01c2d","location":null,"is_verified":"1","likes":"163601490","talking_about":"604934","first_local_fans_country":"31","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"35","crecimiento_fans_semana":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">249.726<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">0,15%<\/span>"}},{"id_page":"7","username":"Cristiano","name":"Cristiano Ronaldo","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xfp1\/v\/t1.0-1\/p50x50\/10363769_10152518873712164_4951848658666973254_n.jpg?oh=a58b55ef5e822b30902b977a7ae2ae84&oe=549A1A5F&__gda__=1419871796_c17e96d5391735170849fab441a859b4","location":"47","is_verified":"1","likes":"97627263","talking_about":"2985798","first_local_fans_country":"22","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"106","crecimiento_fans_semana":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">548.804<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">0,56%<\/span>"}},{"id_page":"8","username":"fcbarcelona","name":"FC Barcelona","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xap1\/v\/t1.0-1\/p50x50\/10635796_10152824672664305_5517843515675888088_n.jpg?oh=af572f5ea6af32cae2c8fb5bad389881&oe=5485EEF6&__gda__=1419573235_a2da4122c9cc48fb87f01c1a2b7b186c","location":"47","is_verified":"1","likes":"74394674","talking_about":"2261872","first_local_fans_country":"22","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"106","crecimiento_fans_semana":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">531.846<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">0,71%<\/span>"}},{"id_page":"2811","username":"LeoMessi","name":"Leo Messi","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xpa1\/v\/t1.0-1\/p50x50\/10413362_849258208427108_1560372031922095847_n.jpg?oh=7c75c8caa85b56f180180b9aac556fea&oe=549D76B5&__gda__=1418723497_c3dfe55fbf49e54186273fb7dd179c11","location":"47","is_verified":"1","likes":"71687431","talking_about":"1804663","first_local_fans_country":"31","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"2"},{"id_page":"1870","username":"DavidGuetta","name":"David Guetta","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-prn2\/v\/t1.0-1\/c41.41.517.517\/s50x50\/543833_10151326464026356_1033041830_n.jpg?oh=1a9ba52610d10a47119d009efaf702a2&oe=548DDCD6&__gda__=1418991469_cc331880e26f594cc8a93cb659add97c","location":null,"is_verified":"1","likes":"56381252","talking_about":"429518","first_local_fans_country":"31","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"25","crecimiento_fans_semana":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">217.905<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">0,39%<\/span>"}},{"id_page":"267","username":"nikefutbolparaguay","name":"Nike F\u00fatbol","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xfp1\/v\/t1.0-1\/p50x50\/10152005_835211439827451_1379729984_n.png?oh=8d4f63fb5193fcfda6ba16f51f8f7b3c&oe=549780F6&__gda__=1418927581_46ba1a3808545029c0d7cad9f05433d0","location":null,"is_verified":"1","likes":"38058164","talking_about":"227878","first_local_fans_country":"80","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"101","crecimiento_fans_semana":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">26.796<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">0,070408%<\/span>"}},{"id_page":"1036","username":"nikefutbolmexico","name":"Nike F\u00fatbol","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xpa1\/v\/t1.0-1\/p50x50\/1377049_10151758072737825_974955338_n.png?oh=666ff7821cd3bbc8eb585bbc7ff38dea&oe=54D05D73&__gda__=1419701723_38fe0d3d392e2852476418ae3d31d481","location":null,"is_verified":"1","likes":"38058164","talking_about":"227878","first_local_fans_country":"31","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"34","crecimiento_fans_semana":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">26.796<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">0,070408%<\/span>"}},{"id_page":"35","username":"nikefutbolargentina","name":"Nike F\u00fatbol","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xfp1\/v\/t1.0-1\/p50x50\/1489216_10152138501609232_740838500_n.png?oh=465b644153b98e2227f71b484fce64e9&oe=54833340&__gda__=1419247467_e6e69d5432e67f33e331125167b13380","location":null,"is_verified":"1","likes":"38058164","talking_about":"227878","first_local_fans_country":"4","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"101","crecimiento_fans_semana":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">26.796<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">0,070408%<\/span>"}},{"id_page":"1465","username":"NikeFootballSpain","name":"Nike Football","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xaf1\/v\/t1.0-1\/p50x50\/1901369_10152405542056785_3066084629373518336_n.jpg?oh=759ca171046c44ad79f8ad0679efb7cc&oe=54A07F25&__gda__=1419779752_ca308b1439f459e2dc80cf774575eb39","location":null,"is_verified":"1","likes":"38058164","talking_about":"227878","first_local_fans_country":"47","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"26","crecimiento_fans_semana":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">26.796<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">0,070408%<\/span>"}},{"id_page":"125","username":"KFCperu","name":"KFC","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xfa1\/v\/t1.0-1\/c3.0.50.50\/p50x50\/10556356_708772272510016_2536449419301411859_n.png?oh=6bd6d95527e85c0d243edc33b2e12812&oe=548500FC&__gda__=1418173660_45d39f593e6c4089cf314032bcfb918e","location":null,"is_verified":"1","likes":"34845402","talking_about":"627431","first_local_fans_country":"38","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"101","crecimiento_fans_semana":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">117.707<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">0,34%<\/span>"}},{"id_page":"1670","username":"KFCMexico","name":"KFC","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xaf1\/v\/t1.0-1\/p50x50\/26564_339943676645_6401979_n.jpg?oh=b02762de97bb6c31de8d46f8218b9ff1&oe=549CF7AE&__gda__=1417879622_e3a6e5efcd13b7e0fdfe41be60202f19","location":null,"is_verified":"1","likes":"34845402","talking_about":"627431","first_local_fans_country":"31","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"26","crecimiento_fans_semana":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">117.706<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">0,34%<\/span>"}},{"id_page":"1193","username":"TransformersLatam","name":"Transformers","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xfa1\/v\/t1.0-1\/p50x50\/1911876_648136248591292_6462515518258225975_n.jpg?oh=2018a2031a7f818e7ec918f74e124626&oe=54931FBD&__gda__=1418683631_bd20cc49cb22b7fdc3265f75b00eeaba","location":null,"is_verified":"1","likes":"33604095","talking_about":"284378","first_local_fans_country":"31","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"26","crecimiento_fans_semana":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">59.693<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">0,18%<\/span>"}},{"id_page":"2818","username":"pepsiES","name":"Pepsi","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xap1\/v\/t1.0-1\/p50x50\/10629653_10152735243530522_213194389268957944_n.jpg?oh=b23e79eee1282d18f17cf08290f7ae06&oe=5493F080&__gda__=1419274817_4ba340512f13be6a38083a767801506b","location":null,"is_verified":"1","likes":"33295431","talking_about":"250752","first_local_fans_country":"47","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"2"},{"id_page":"18","username":"PepsiPanama","name":"Pepsi","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xfa1\/v\/t1.0-1\/p50x50\/10599717_749151651797459_2117038984471085511_n.png?oh=29e92ae09247a4db447529f949a14d1c&oe=54A2B62D&__gda__=1417845885_9196c33657a3ac36244835d6f6d8cf66","location":"37","is_verified":"1","likes":"33295430","talking_about":"250752","first_local_fans_country":"37","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"101","crecimiento_fans_semana":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">41.198<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">0,12%<\/span>"}},{"id_page":"994","username":"pepsiargentina","name":"Pepsi","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xap1\/v\/t1.0-1\/p50x50\/14820_630080180418881_4423726537776616697_n.png?oh=4275ecbeb9fc52e353e2f86956af21d7&oe=549758C0&__gda__=1418740241_d8c21fbcee780497464bc01eaa9ea311","location":null,"is_verified":"1","likes":"33295430","talking_about":"250752","first_local_fans_country":"4","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"39","crecimiento_fans_semana":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">41.198<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">0,12%<\/span>"}},{"id_page":"108","username":"Pringles.ES","name":"Pringles","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xpf1\/v\/t1.0-1\/p50x50\/320178_413317435384210_1314443813_n.jpg?oh=62b17eedea9bb7028aed69f9547bf667&oe=549D94A6&__gda__=1418376980_173887510fac1dbefd39f74bd2778418","location":null,"is_verified":"1","likes":"27515279","talking_about":"50695","first_local_fans_country":"47","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"101","crecimiento_fans_semana":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">13.523<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">0,049147%<\/span>"}},{"id_page":"2541","username":"PringlesLA","name":"Pringles","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xaf1\/v\/t1.0-1\/p50x50\/10574485_473324012804877_1132715812393573776_n.png?oh=0768debe7f60affa332bcddd9a53f72c&oe=54D13A5B&__gda__=1418570220_6dd96b35750c9402da11555a84b0528b","location":null,"is_verified":"1","likes":"27515279","talking_about":"50695","first_local_fans_country":"31","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"3"},{"id_page":"1959","username":"princeroyce","name":"Prince Royce","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xfp1\/v\/t1.0-1\/p50x50\/10565107_10152474163879003_5931435466976509699_n.jpg?oh=cc82c8ea0456a2583bd49720d379d9c1&oe=549A4422&__gda__=1419135817_1500f36c16bc03184810fdf28bf3d00e","location":null,"is_verified":"1","likes":"26865608","talking_about":"308474","first_local_fans_country":"31","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"25","crecimiento_fans_semana":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">206.916<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">0,77%<\/span>"}},{"id_page":"94","username":"nickelodeon","name":"Nickelodeon","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xpa1\/v\/t1.0-1\/p50x50\/10345818_10152483715066318_1492663945480167815_n.jpg?oh=2e11eceb49a9b173a72f94333eafca83&oe=54946F0D&__gda__=1419128752_92508983db60e0423f1a6f7d4da04c40","location":null,"is_verified":"1","likes":"26133451","talking_about":"492635","first_local_fans_country":"31","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"101","crecimiento_fans_semana":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">47.085<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">0,18%<\/span>"}},{"id_page":"2231","username":"mundonick","name":"Nickelodeon","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xfa1\/v\/t1.0-1\/c0.0.50.50\/p50x50\/10570432_10152197823051776_2289820103757822757_n.png?oh=0fc0ee7362d215c8fcd8293b41b660d5&oe=54918861&__gda__=1419279945_d6b9d9cdffab11e4a305c5ac0cbf6a46","location":null,"is_verified":"1","likes":"26133451","talking_about":"492635","first_local_fans_country":"31","fb_page_likes_last_update":"2014-09-10","fb_page_likes_nun_dates":"17","crecimiento_fans_semana":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">47.085<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">0,18%<\/span>"}}]';
    $datos = json_decode($datos, true);
    
    $cont = 1;
	foreach($datos as $fila){
	    
        $crecimiento_fans = array('semana' => array('value' => $n_a, 'porcentaje' => $n_a)); 
	    
        $fb_page_likes_last_update = $fila['fb_page_likes_last_update'];
        $fb_page_likes_nun_dates = $fila['fb_page_likes_nun_dates'];
        
        if($fb_page_likes_nun_dates > 7)
            $crecimiento_fans['semana'] = $fila['crecimiento_fans_semana'];
	        
		?>
                <div class="owloo_ranking_item">
                    <div class="owloo_rank"><?=str_pad($cont++, 2, '0', STR_PAD_LEFT);?></div>
                    <div class="owloo_fb_username">
                        <div class="owloo_fb_avatar">
                        	<a href="<?=URL_ROOT?>facebook-stats/pages/<?=strtolower($fila['username'])?>/" title="Estadísticas de <?=$fila['name']?> en Facebook">
                            	<img class="owloo_fb_page_avatar" src="<?=URL_IMAGES?>loader-24x24.gif" alt="<?=$fila['username']?>" data="<?=$fila['picture']?>" />
                            </a>
                            &nbsp;
                        </div>
                        <a href="<?=URL_ROOT?>facebook-stats/pages/<?=strtolower($fila['username'])?>/" title="Estadísticas de <?=$fila['name']?> en Facebook"><?=$fila['name']?></a>
                    </div>
                    <div class="owloo_fb_ranking_item">
                        <div class="owloo_title">Fans totales</div>
                        <div class="owloo_fb_num_green"><?=owloo_number_format($fila['likes'])?></div>
                    </div>
                    <div class="owloo_fb_ranking_item">
                        <div class="owloo_title owloo_fb_pta_td">PTA</div>
                        <div class="owloo_fb_num_green"><?=owlooFormatPorcent($fila['talking_about'], $fila['likes'])?>%</div>
                    </div>
                    <div class="owloo_fb_ranking_item owloo_fb_ranking_item_change_audition">
                        <div class="owloo_title">Semana</div>
                        <div class="owloo_change_audition"><?=$crecimiento_fans['semana']['value']?></div>
                    </div>
                    <div class="owloo_fb_ranking_item">
                        <div class="owloo_fb_flag">
                            <?php if(!empty($fila['location']) || !empty($fila['first_local_fans_country'])){ ?>
                            <a href="<?=URL_ROOT?>facebook-stats/pages/country/<?=convert_to_url_string(get_country_data((!empty($fila['location'])?$fila['location']:$fila['first_local_fans_country']), 'code'))?>/" title="Páginas más populares de Facebook en <?=get_country_data((!empty($fila['location'])?$fila['location']:$fila['first_local_fans_country']), 'nombre')?>">
                                <span class="owloo_country_flag" style="background-position:0 <?=(-20 * ((!empty($fila['location'])?$fila['location']:$fila['first_local_fans_country'])-1))?>px"></span>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if($fila['is_verified']){ ?>
                    <div class="owloo_fb_ranking_item owloo_no_border">
                        <span class="owloo_fb_page_verified"></span>
                    </div>
                    <?php } ?>
                    
                </div>
	<?php
	
	} ?>
</div>
<?php if($page_total_count > PAGER_PP){ ?>
<div class="owloo_nav_container">
<?php
for($p=0; $p*PAGER_PP <  $page_total_count; $p++){
    $pactive = ($p*PAGER_PP) == 0 ? "owloo_pactive" : "" ; //0 = page_start
    $pc = $p+1;
    $pf = "";
    if($pc>7) {
        $pf = "owloo_inactive";
        if($pc >= ($page_total_count / PAGER_PP)){
            $pf = 'owloo_no-pag';
            echo '<div class="owloo_nav owloo_page-space '.$pf.'" id="owloo_no-pag-fin" >...</div>';
        }
    }
    else
    if($pc == 2){
        echo '<div class="owloo_nav owloo_page-space owloo_inactive" id="owloo_no-pag-ini" >...</div>';
    }
    ?> 
    <div onclick="next(<?=($p+1)?>, <?=$page_total_count?>, <?=PAGER_PP?>); load_page(<?=($p+1)?>, '<?=($_GET['ranking']=='hispanic'?'hispanic':'global')?>_page', false, '');" class="owloo_nav <?=$pactive?> <?=$pf?>" id="owloo_nav_<?=($p+1)?>"><?=($p+1)?></div>
    <?php 
}
?>
</div>
<?php } ?>